<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

class forum_sitemap extends cms_rudi_sitemap {
    public function getConfig() { return false; }

    public function getSections($target_id=0, $target='') {
        $cats = array();
        
        if (empty($target_id)) {
            $results = cmsCore::c('db')->query('SELECT id, title, seolink FROM cms_forum_cats ORDER BY id ASC');
            if (!cmsCore::c('db')->num_rows($results)) { return false; }
            
            while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
                $cats[] = array(
                    'target_id' => $cat['id'],
                    'target' => '',
                    'title' => $cat['title'],
                    'link' => '/forum/'. $cat['seolink']
                );
            }
        } else if (empty($target)) {
            $root_forum_ns_level = cmsCore::c('db')->get_field('cms_forums', 'category_id=0 AND parent_id=0', 'NSLevel');
            $results = cmsCore::c('db')->query("SELECT id, title FROM cms_forums WHERE category_id='". $target_id ."' AND NSLevel = ". ($root_forum_ns_level+1) ." ". (cmsCore::c('user')->is_admin ? '' : ' AND published=1'));
        } else {
            $results = cmsCore::c('db')->query("SELECT id, title FROM cms_forums WHERE parent_id='". $target_id ."' ". (cmsCore::c('user')->is_admin ? '' : ' AND published=1'));
        }
        
        if (!empty($target_id)){
            if (!cmsCore::c('db')->num_rows($results)) { return false; }

            while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
                $cats[] = array(
                    'target_id' => $cat['id'],
                    'target' => 'forum',
                    'title' => $cat['title'],
                    'link' => '/forum/'. $cat['id']
                );
            }
        }
        
        return $cats;
    }

    public function getSection($target_id=0, $target='') {
        if (empty($target_id)) { return false; }
        
        if ($target == 'forum') {
            $cat = cmsCore::c('db')->get_fields('cms_forums', 'id='. $target_id .' '. (cmsCore::c('user')->is_admin ? '' : ' AND published=1'), 'id, title');
        } else {
            $cat = cmsCore::c('db')->get_fields('cms_forum_cats', 'id='. $target_id .' '. (cmsCore::c('user')->is_admin ? '' : ' AND published=1'), 'id, title, seolink');
        }
        
        if (!$cat) { return false; }

        return array(
            'target_id' => $cat['id'],
            'target' => $target == 'forum' ? 'forum' : '',
            'title' => $cat['title'],
            'link' => '/forum/'. $target == 'forum' ? $cat['id'] : $cat['seolink']
        );
    }

    public function getSectionItems($target_id=0, $target='') {
        if (empty($target_id) || empty($target)) {
            return false;
        }
        
        $results = cmsCore::c('db')->query("SELECT id, title FROM cms_forum_threads WHERE forum_id = '". $target_id ."'". (cmsCore::c('user')->is_admin ? '' : ' AND is_hidden = 0') .' ORDER BY pubdate DESC '. (!empty(cmsCore::c('db')->limit) ? ' LIMIT '. cmsCore::c('db')->limit : ''));
        
        if (!cmsCore::c('db')->num_rows($results)) { return false; }
        
        $items = array();
        while ($item = cmsCore::c('db')->fetch_assoc($results)) {
            $items[] = array(
                'id' => $item['id'],
                'title' => $item['title'],
                'link' => '/forum/thread'. $item['id'] .'.html'
            );
        }
        
        return $items;
    }

    public function getSectionItemsCount($target_id=0, $target='') {
        $count = 0;
        
        if (!empty($target_id) && !empty($target)) {
            $count = cmsCore::c('db')->rows_count('cms_forum_threads', "forum_id = '". $target_id ."'". (cmsCore::c('user')->is_admin ? '' : ' AND is_hidden = 0'));
        }

        return $count;
    }

    //==========================================================================

    public function generateMap() {
        $gen_map = parent::generateMap();
        
        if ($gen_map === false) { return; }
        
        $is_end = false;
        $item_nums = 0;
        
        // Заносим в карту категории
        $results = cmsCore::c('db')->query("SELECT seolink FROM cms_forum_cats WHERE published = 1 ORDER BY id ASC");

        if (cmsCore::c('db')->num_rows($results)) {
            while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
                $this->writeMapItem(array(
                    'loc' => cmsCore::c('config')->host .'/forum/'. $cat['seolink'],
                    'changefreq' => 'daily',
                    'priority' => '0.8'
                ));
            }
        }
        
        // Заносим в карту форумы
        $results = cmsCore::c('db')->query("SELECT id FROM cms_forums WHERE published = 1 ORDER BY id ASC");
        
        if (cmsCore::c('db')->num_rows($results)) {
            while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
                $this->writeMapItem(array(
                    'loc' => cmsCore::c('config')->host .'/forum/'. $cat['id'],
                    'changefreq' => 'daily',
                    'priority' => '0.8'
                ));
            }
        }
        
        // Заносим в карту темы
        while($is_end === false) {
            $results = cmsCore::c('db')->query("SELECT id, last_msg FROM cms_forum_threads WHERE is_hidden = 0 ORDER BY pubdate ASC LIMIT ". $item_nums .', 1000');
            
            if (cmsCore::c('db')->num_rows($results)) {
                while ($item = cmsCore::c('db')->fetch_assoc($results)) {
                    $last_msg = cmsCore::yamlToArray($item['last_msg']);
                    $last_date = explode(' ', $last_msg['pubdate']);

                    $this->writeMapItem(array(
                        'loc' => cmsCore::c('config')->host .'/thread'. $item['id'] .'.html',
                        'changefreq' => 'weekly',
                        'priority' => '0.7',
                        'lastmod' => $last_date[0]
                    ));
                    
                    $item_nums++;
                }
                
            } else {
                $is_end = true;
            }
        }
        
        $this->closeFile();
    }
}
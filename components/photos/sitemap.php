<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.8                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

class photos_sitemap extends cms_rudi_sitemap {
    public function getConfig() { return false; }

    public function getSections($target_id=0, $target='') {
        if (empty($target_id)) {
            $target_id = cmsCore::c('db')->getNsRootCatId('cms_photo_albums');
        }
        
        $results = cmsCore::c('db')->query("SELECT id, title FROM cms_photo_albums WHERE parent_id='". $target_id ."' AND NSDiffer = '' ". (cmsCore::c('user')->is_admin ? '' : ' AND published=1'));
        
        if (!cmsCore::c('db')->num_rows($results)) { return false; }
        
        $cats = array();
        
        while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
            $cats[] = array(
                'target_id' => $cat['id'],
                'target' => '',
                'title' => $cat['title'],
                'link' => '/photos/'. $cat['id']
            );
        }
        
        return $cats;
    }

    public function getSection($target_id=0, $target='') {
        if (empty($target_id)) { return false; }
        
        $cat = cmsCore::c('db')->get_fields('cms_photo_albums', 'id='.$target_id .' '. (cmsCore::c('user')->is_admin ? '' : ' AND published=1'), 'id, title');
        
        if (!$cat) { return false; }

        return array(
            'target_id' => $cat['id'],
            'target' => '',
            'title' => $cat['title'],
            'link' => '/photos/'. $cat['id']
        );
    }

    public function getSectionItems($target_id=0, $target='') {
        if (empty($target_id)) { return false; }
        
        $results = cmsCore::c('db')->query("SELECT id, title FROM cms_photo_files WHERE album_id='". $target_id ."' AND owner='photos' ". (cmsCore::c('user')-is_admin ? '' : ' AND published = 1') ." ORDER BY pubdate ASC". (!empty(cmsCore::c('db')->limit) ? ' LIMIT '. cmsCore::c('db')->limit : ''));
        
        if (!cmsCore::c('db')->num_rows($results)) { return false; }
        
        $items = array();
        while ($item = cmsCore::c('db')->fetch_assoc($results)) {
            $items[] = array(
                'id' => $item['id'],
                'title' => $item['title'],
                'link' => '/photos/photo'. $item['id'] .'.html'
            );
        }
        
        return $items;
    }

    public function getSectionItemsCount($target_id=0, $target='') {
        $count = 0;
        
        if (!empty($target_id)) {
            $count = cmsCore::c('db')->rows_count('cms_photo_files', "album_id='". $target_id ."' AND owner='photos'");
        }
        
        return $count;
    }

    //==========================================================================

    public function generateMap() {
        $gen_map = parent::generateMap();
        
        if ($gen_map === false) { return; }
        
        $is_end = false;
        $item_nums = 0;
        
        // Заносим в карту альбомы
        $results = cmsCore::c('db')->query("SELECT id, pubdate FROM cms_photo_albums WHERE parent_id != 0 AND NSDiffer = '' AND published = 1");

        if (cmsCore::c('db')->num_rows($results)) {
            while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
                $last_date = cmsCore::c('db')->get_field('cms_photo_files', "album_id = '". $cat['id'] ."' AND owner = 'photos' ORDER BY pubdate DESC", 'pubdate');
                $last_date = explode(' ', !empty($last_date) ? $last_date : $cat['pubdate']);

                $this->writeMapItem(array(
                    'loc' => cmsCore::c('config')->host .'/photos/'. $cat['id'],
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                    'lastmod' => $last_date[0]
                ));
            }
        }
        
        // Заносим в карту фотографии
        while($is_end === false) {
            $results = cmsCore::c('db')->query("SELECT id, pubdate FROM cms_photo_files WHERE published = 1 AND owner='photos' ORDER BY pubdate ASC LIMIT ". $item_nums .", 1000");
            
            if (cmsCore::c('db')->num_rows($results)) {
                while ($item = cmsCore::c('db')->fetch_assoc($results)) {
                    $last_date = explode(' ', $item['pubdate']);

                    $this->writeMapItem(array(
                        'loc' => cmsCore::c('config')->host .'/photos/photo'. $item['id'] .'.html',
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
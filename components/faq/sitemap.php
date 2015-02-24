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

class faq_sitemap extends cms_rudi_sitemap {
    public function getConfig() { return false; }

    public function getSections($target_id=0, $target='') {
        $results = cmsCore::c('db')->query("SELECT id, title FROM cms_faq_cats WHERE parent_id='". $target_id ."' ". (cmsCore::c('user')->is_admin ? '' : 'AND published = 1'));
        
        if (!cmsCore::c('db')->num_rows($results)) { return false; }
        
        $cats = array();
        
        while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
            $cats[] = array(
                'target_id' => $cat['id'],
                'target' => '',
                'title' => $cat['title'],
                'link' => '/faq/'. $cat['id']
            );
        }
        
        return $cats;
    }

    public function getSection($target_id=0, $target='') {
        $cat = cmsCore::c('db')->get_fields('cms_faq_cats', 'id='.$target_id .' '. (cmsCore::c('user')->is_admin ? '' : ' AND published = 1'), 'id, title');
        
        if (!$cat) { return false; }

        return array(
            'target_id' => $cat['id'],
            'target' => '',
            'title' => $cat['title'],
            'link' => '/faq/'. $cat['id']
        );
    }

    public function getSectionItems($target_id=0, $target='') {
        if (empty($target_id)) { return false; }
        
        $results = cmsCore::c('db')->query('SELECT id, quest FROM cms_faq_quests WHERE category_id = '. $target_id .' '. (cmsCore::c('user')->is_admin ? '' : 'AND published = 1') .' '. (cmsCore::c('db')->limit ? 'LIMIT '. cmsCore::c('db')->limit : ''));

        if (!cmsCore::c('db')->num_rows($results)) {
            return false;
        }
        
        $items = array();
        while ($item = cmsCore::c('db')->fetch_assoc($results)) {
            $items[] = array(
                'id' => $item['id'],
                'title' => mb_substr($item['quest'], 0, 64) .'...',
                'link' => '/faq/quest'. $item['id'] .'.html'
            );
        }
        
        return $items;
    }

    public function getSectionItemsCount($target_id=0, $target='') {
        return cmsCore::c('db')->rows_count('cms_faq_quests', "category_id = '". $target_id."' ". (cmsCore::c('user')->is_admin ? '' : 'AND published = 1'));
    }

    //==========================================================================

    public function generateMap() {
        $gen_map = parent::generateMap();
        
        if ($gen_map === false) { return; }
        
        $is_end = false;
        $item_nums = 0;
        
        // Заносим в карту категории
        $results = cmsCore::c('db')->query("SELECT id FROM cms_faq_cats WHERE published = 1 ORDER BY id ASC");

        if (cmsCore::c('db')->num_rows($results)) {
            while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
                $last_date = cmsCore::c('db')->get_field('cms_faq_quests', "category_id='". $cat['id'] ."' ORDER BY answerdate DESC", 'answerdate');
                $last_date = explode(' ', !empty($last_date) ? $last_date : date('Y-m-d'));

                $this->writeMapItem(array(
                    'loc' => cmsCore::c('config')->host .'/'. $cat['seolink'],
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                    'lastmod' => $last_date[0]
                ));
            }
        }
        
        // Заносим в карту вопросы
        while($is_end === false) {
            $results = cmsCore::c('db')->query("SELECT id, answerdate FROM cms_faq_quests WHERE published = 1 ORDER BY pubdate ASC LIMIT ". $item_nums .', 1000');
            
            if (cmsCore::c('db')->num_rows($results)) {
                while ($item = cmsCore::c('db')->fetch_assoc($results)) {
                    $last_date = explode(' ', $item['answerdate']);

                    $this->writeMapItem(array(
                        'loc' => cmsCore::c('config')->host .'/faq/quest'. $item['id'] .'.html',
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
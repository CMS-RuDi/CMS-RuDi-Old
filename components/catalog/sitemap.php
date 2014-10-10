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

class catalog_sitemap extends cms_rudi_sitemap {
    public function getConfig() { return false; }

    public function getSections($target_id=0, $target='') {
        if (empty($target_id)) {
            $target_id = cmsCore::c('db')->getNsRootCatId('cms_uc_cats');
        }
        
        $results = cmsCore::c('db')->query("SELECT id, title FROM cms_uc_cats WHERE parent_id='". $target_id ."' ". (cmsCore::c('user')->is_admin ? '' : ' AND published = 1'));
        
        if (!cmsCore::c('db')->num_rows($results)) { return false; }
        
        $cats = array();
        
        while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
            $cats[] = array(
                'target_id' => $cat['id'],
                'target' => '',
                'title' => $cat['title'],
                'link' => '/catalog/'. $cat['id']
            );
        }
        
        return $cats;
    }

    public function getSection($target_id=0, $target='') {
        if (empty($target_id)) {
            $target_id = cmsCore::c('db')->getNsRootCatId('cms_uc_cats');
        }
        
        $cat = cmsCore::c('db')->get_fields('cms_uc_cats', 'id='.$target_id .' '. (cmsCore::c('user')->is_admin ? '' : ' AND published = 1'), 'id, title');
        
        if (!$cat) { return false; }

        return array(
            'target_id' => $cat['id'],
            'target' => '',
            'title' => $cat['title'],
            'link' => '/catalog/'. $cat['id']
        );
    }

    public function getSectionItems($target_id=0, $target='') {
        if (empty($target_id)) {
            $target_id = cmsCore::c('db')->getNsRootCatId('cms_uc_cats');
        }
        
        $results = cmsCore::c('db')->query('SELECT id, title FROM cms_uc_items WHERE '. (cmsCore::c('user')->is_admin ? '' : 'published = 1 AND ') .' category_id = '. $target_id .' '. (!empty(cmsCore::c('db')->limit) ? ' LIMIT '. cmsCore::c('db')->limit : ''));
        
        $items = array();
        while ($item = cmsCore::c('db')->fetch_assoc($results)) {
            $items[] = array(
                'id' => $item['id'],
                'title' => $item['title'],
                'link' => '/catalog/item'. $item['id'] .'.html'
            );
        }
        
        return $items;
    }

    public function getSectionItemsCount($target_id=0, $target='') {
        if (empty($target_id)) {
            $target_id = cmsCore::c('db')->getNsRootCatId('cms_uc_cats');
        }
        
        return cmsCore::c('db')->rows_count('cms_uc_items', (cmsCore::c('user')->is_admin ? '' : 'published = 1 AND ') .' category_id = '. $target_id);
    }

    //==========================================================================

    public function generateMap() {
        $gen_map = parent::generateMap();
        
        if ($gen_map === false) { return; }
        
        $is_end = false;
        
        // Заносим в карту категории
        $results = cmsCore::c('db')->query("SELECT id, pubdate FROM cms_uc_cats WHERE parent_id != 0 AND NSDiffer = '' AND published = 1");

        if (cmsCore::c('db')->num_rows($results)) {
            while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
                $last_date = cmsCore::c('db')->get_field('cms_uc_items', "category_id='". $cat['id'] ."' ORDER BY pubdate DESC", 'pubdate');
                $last_date = explode(' ', !empty($last_date) ? $last_date : $cat['pubdate']);

                $this->writeMapItem(array(
                    'loc' => cmsCore::c('config')->host .'/catalog/'. $cat['id'],
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                    'lastmod' => $last_date[0]
                ));
            }
        }
        
        $item_nums = 0;
        
        // Заносим в карту материалы
        while($is_end === false) {
            $results = cmsCore::c('db')->query("SELECT id, pubdate FROM cms_uc_items WHERE published = 1 ORDER BY pubdate ASC LIMIT ". $item_nums .', 1000');
            
            if (cmsCore::c('db')->num_rows($results)) {
                while ($item = cmsCore::c('db')->fetch_assoc($results)) {
                    $last_date = explode(' ', $item['pubdate']);

                    $this->writeMapItem(array(
                        'loc' => cmsCore::c('config')->host .'/catalog/item'. $item['id'] .'.html',
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
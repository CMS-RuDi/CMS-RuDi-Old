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

class content_sitemap extends cms_rudi_sitemap {
    public function getConfig() { return false; }

    public function getSections($target_id=0, $target='') {
        if (empty($target_id)) {
            $target_id = cmsCore::c('db')->getNsRootCatId('cms_category');
        }
        
        $results = cmsCore::c('db')->query("SELECT id, title, seolink FROM cms_category WHERE parent_id='". $target_id ."' ". (cmsCore::c('user')->is_admin ? '' : ' AND published=1'));
        
        if (!cmsCore::c('db')->num_rows($results)) { return false; }
        
        $cats = array();
        
        while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
            if (!cmsCore::checkUserAccess('category', $cat['id'])) {
                continue;
            }
            
            $cats[] = array(
                'target_id' => $cat['id'],
                'target' => '',
                'title' => $cat['title'],
                'link' => '/'. (cmsCore::c('config')->com_without_name_in_url != 'content' ? 'content/' : '') . $cat['seolink']
            );
        }
        
        return $cats;
    }

    public function getSection($target_id=0, $target='') {
        if (empty($target_id)) {
            $target_id = cmsCore::c('db')->getNsRootCatId('cms_category');
        }
        
        $cat = cmsCore::c('db')->get_fields('cms_category', 'id='.$target_id .' '. (cmsCore::c('user')->is_admin ? '' : ' AND published=1'), 'id, title, seolink');
        
        if (!$cat) { return false; }
        
        if (!cmsCore::checkUserAccess('category', $cat['id'])) {
            return false;
        }

        return array(
            'target_id' => $cat['id'],
            'target' => '',
            'title' => $cat['title'],
            'link' => '/'. (cmsCore::c('config')->com_without_name_in_url != 'content' ? 'content/' : '') . $cat['seolink']
        );
    }

    public function getSectionItems($target_id=0, $target='') {
        if (empty($target_id)) {
            $target_id = cmsCore::c('db')->getNsRootCatId('cms_category');
        }
        
        cmsCore::m('content')->whereCatIs($target_id);
        cmsCore::c('db')->orderBy('con.pubdate', 'DESC');
        
        $content_list = cmsCore::m('content')->getArticlesList(cmsCore::c('user')->is_admin ? false : true);
        
        if (empty($content_list)) { return false; }
        
        $items = array();
        foreach ($content_list as $item) {
            $items[] = array(
                'id' => $item['id'],
                'title' => $item['title'],
                'link' => '/'. (cmsCore::c('config')->com_without_name_in_url != 'content' ? 'content/' : '') . $item['seolink'] .'.html'
            );
        }
        
        return $items;
    }

    public function getSectionItemsCount($target_id=0, $target='') {
        if (empty($target_id)) {
            $target_id = cmsCore::c('db')->getNsRootCatId('cms_category');
        }
        
        cmsCore::m('content')->whereCatIs($target_id);
        
        $count = cmsCore::m('content')->getArticlesCount(cmsCore::c('user')->is_admin ? false : true);
        
        cmsCore::c('db')->resetConditions();
        
        return $count;
    }

    //==========================================================================

    public function generateMap() {
        $gen_map = parent::generateMap();
        
        if ($gen_map === false) { return; }
        
        $today = date('Y-m-d H:i:s');
        $is_end = false;
        
        // Заносим в карту категории
        $results = cmsCore::c('db')->query("SELECT * FROM cms_category WHERE parent_id != 0 AND NSDiffer = '' AND published = 1");

        if (cmsCore::c('db')->num_rows($results)) {
            while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
                if (!cmsCore::checkUserAccess('category', $cat['id'])) {
                    continue;
                }
                
                $last_date = cmsCore::c('db')->get_field('cms_content', "category_id='". $cat['id'] ."' ORDER BY pubdate DESC", 'pubdate');
                $last_date = explode(' ', !empty($last_date) ? $last_date : $cat['pubdate']);

                $this->writeMapItem(array(
                    'loc' => cmsCore::c('config')->host .'/'. (cmsCore::c('config')->com_without_name_in_url != 'content' ? 'content/' : '') . $cat['seolink'],
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                    'lastmod' => $last_date[0]
                ));
            }
        }
        
        $article_nums = 0;
        
        // Заносим в карту статьи
        while($is_end === false) {
            $results = cmsCore::c('db')->query("SELECT id, seolink, pubdate FROM cms_content WHERE published = 1 AND pubdate <= '". $today ."' ORDER BY pubdate ASC LIMIT ". $article_nums .', 1000');
            
            if (cmsCore::c('db')->num_rows($results)) {
                while ($article = cmsCore::c('db')->fetch_assoc($results)) {
                    if (cmsCore::checkUserAccess('material', $article['id'])) {
                        $last_date = explode(' ', $article['pubdate']);

                        $this->writeMapItem(array(
                            'loc' => cmsCore::c('config')->host .'/'. (cmsCore::c('config')->com_without_name_in_url != 'content' ? 'content/' : '') . $article['seolink'] .'.html',
                            'changefreq' => 'weekly',
                            'priority' => '0.7',
                            'lastmod' => $last_date[0]
                        ));
                    }
                    
                    $article_nums++;
                }
                
            } else {
                $is_end = true;
            }
        }
        
        $this->closeFile();
    }
}
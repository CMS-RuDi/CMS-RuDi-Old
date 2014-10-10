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

class blogs_sitemap extends cms_rudi_sitemap {
    public function getConfig() { return false; }

    public function getSections($target_id=0, $target='') {
        if (!empty($target)) { return false; }
        
        global $_LANG;
        
        return array(
            array(
                'target_id' => 0,
                'target' => 'single',
                'title' => $_LANG['PERSONALS'],
                'link' => '/blogs/single.html'
            ),
            array(
                'target_id' => 0,
                'target' => 'multi',
                'title' => $_LANG['COLLECTIVES'],
                'link' => '/blogs/multi.html'
            )
        );
    }

    public function getSection($target_id=0, $target='') {
        if ($target == 'single') {
            return array(
                'target_id' => 0,
                'target' => 'single',
                'title' => $_LANG['PERSONALS'],
                'link' => '/blogs/single.html'
            );
        }
        
        if ($target == 'multi') {
            return array(
                'target_id' => 0,
                'target' => 'multi',
                'title' => $_LANG['COLLECTIVES'],
                'link' => '/blogs/multi.html'
            );
        }
        
        if ($target == 'blog') {
            $blog = cmsCore::c('blog')->getBlog($target_id);
            
            if (!empty($blog)) {
                return array(
                    'target_id' => $blog['id'],
                    'target' => 'blog',
                    'title' => $blog['title'],
                    'link' => '/blogs/'. $blog['seolink']
                );
            }
        }
        
        return false;
    }

    public function getSectionItems($target_id=0, $target='') {
        if (!in_array($target, array('single', 'multi', 'blog'))) {
            return false;
        }
        
        cmsCore::c('blog')->owner = 'user';
        
        if ($target != 'blog') {
            
            cmsCore::c('blog')->whereOwnerTypeIs($target);

            cmsCore::c('db')->where("b.allow_who='all'");
            cmsCore::c('db')->orderBy('b.rating', 'DESC');

            $blogs = cmsCore::c('blog')->getBlogs(cmsCore::m('blogs'));

            if (empty($blogs)) { return false; }

            $items = array();
            foreach ($blogs as $blog) {
                $items[] = array(
                    'target_id' => $blog['id'],
                    'target' => 'blog',
                    'title' => $blog['title'],
                    'link' => $blog['url']
                );
            }
        } else {
            cmsCore::c('blog')->whereBlogIs($target_id);
            
            if (!cmsCore::c('user')->is_admin) {
                cmsCore::c('blog')->whereOnlyPublic();
            }
            
            cmsCore::c('db')->orderBy('p.pubdate', 'DESC');
            
            $posts = cmsCore::c('blog')->getPosts(cmsCore::c('user')->is_admin, cmsCore::m('blogs'));
            
            $items = array();
            foreach ($posts as $post) {
                $items[] = array(
                    'id' => $post['id'],
                    'title' => $post['title'],
                    'link' => $post['url']
                );
            }
        }
        
        return $items;
    }

    public function getSectionItemsCount($target_id=0, $target='') {
        if (!in_array($target, array('single', 'multi'))) {
            return 0;
        }
        
        cmsCore::c('blog')->owner = 'user';
        cmsCore::c('blog')->whereOwnerTypeIs($target);
        cmsCore::c('db')->where("b.allow_who='all'");
        
        $count = cmsCore::c('blog')->getBlogsCount();
        
        cmsCore::c('db')->resetConditions();
        
        return $count;
    }

    //==========================================================================

    public function generateMap() {
        $gen_map = parent::generateMap();
        
        if ($gen_map === false) { return; }

        $is_end = false;
        $item_nums = 0;
        
        cmsCore::c('blog')->owner = 'user';
        cmsCore::c('db')->where("b.allow_who='all'");
        cmsCore::c('db')->orderBy('b.pubdate', 'ASC');
        
        // Заносим в карту блоги
        while($is_end === false) {
            $results = cmsCore::c('db')->query("SELECT * FROM cms_blogs WHERE owner = 'user' AND allow_who = 'all' ORDER BY pubdate ASC LIMIT ". $item_nums .", 1000");

            if (cmsCore::c('db')->num_rows($results)) {
                while ($blog = cmsCore::c('db')->fetch_assoc($results)) {
                    $last_date = cmsCore::c('db')->get_field('cms_blog_posts', "blog_id='". $blog['id'] ."' ORDER BY pubdate DESC", 'pubdate');
                    $last_date =  explode(' ', !empty($last_date) ? $last_date : $blog['pubdate']);

                    $this->writeMapItem(array(
                        'loc' => cmsCore::c('config')->host .'/blogs/'. $blog['seolink'],
                        'changefreq' => 'daily',
                        'priority' => '0.8',
                        'lastmod' => $last_date[0]
                    ));

                    $item_nums++;
                }
            } else {
                $is_end = true;
            }
        }
        
        $is_end = false;
        $item_nums = 0;
        
        // Заносим в карту посты блогов
        while($is_end === false) {
            $results = cmsCore::c('db')->query("SELECT p.seolink, p.pubdate, b.seolink as blog_seolink FROM cms_blog_posts p INNER JOIN cms_blogs b ON b.id=p.blog_id WHERE p.published=1 AND p.allow_who = 'all' AND b.allow_who='all' AND b.owner='user' ORDER BY pubdate ASC LIMIT ". $item_nums .', 1000');
            
            if (cmsCore::c('db')->num_rows($results)) {
                while ($post = cmsCore::c('db')->fetch_assoc($results)) {
                    $last_date = explode(' ', $post['pubdate']);

                    $this->writeMapItem(array(
                        'loc' => cmsCore::c('config')->host .'/blogs/'. $post['blog_seolink'] .'/'. $post['seolink'] .'.html',
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
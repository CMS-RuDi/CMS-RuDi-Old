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

class clubs_sitemap extends cms_rudi_sitemap {
    public function getConfig() { return false; }

    public function getSections($target_id=0, $target='') {
        if (!empty($target)) { return false; }
        
        if (!cmsCore::c('user')->is_admin) {
            cmsCore::c('db')->where("c.clubtype='public'");
        }
        
        $clubs = cmsCore::m('clubs')->getClubs(cmsCore::c('user')->is_admin ? false : true);
        
        $items = array();
        foreach ($clubs as $club) {
            $items[] = array(
                'target_id' => $club['id'],
                'target' => 'club',
                'title' => $club['title'],
                'link' => '/clubs/'. $club['id']
            );
        }
        
        return $items;
    }

    public function getSection($target_id=0, $target='') {
        if (!in_array($target, array('club', 'blog', 'album'))) {
            return false;
        }
        
        if ($target == 'club') {
            $club = cmsCore::m('clubs')->getClub($target_id);
            return array(
                'target_id' => $club['id'],
                'target' => 'club',
                'title' => $club['title'],
                'link' => '/clubs/'. $club['id']
            );
        }
        
        if ($target == 'blog') {
            $club = cmsCore::m('clubs')->getClub($target_id);
            if (!$club['enabled_blogs']) { return false; }
            
            cmsCore::c('blog')->owner = 'club';
            $blog = cmsCore::c('blog')->getBlogByUserId($club['id']);
            
            if ($club['enabled_blogs']) {
                return array(
                    'target_id' => $club['id'],
                    'target' => 'blog',
                    'title' => $blog['title'],
                    'link' => '/clubs/'. $club['id'] .'_blog'
                );
            }
        }
        
        if ($target == 'album') {
            
            if (!$club) { return false; }
            if (
                ((!$club['published'] || $club['clubtype'] == 'private') &&
                !cmsCore::c('user')->is_admin) ||
                !$club['enabled_photos']
            ) {
                return false;
            }

            return array(
                'target_id' => $album['id'],
                'target' => 'album',
                'title' => $album['title'],
                'link' => '/clubs/photoalbum'. $album['id']
            );
        }
        
        return false;
    }

    public function getSectionItems($target_id=0, $target='') {
        if ($target == 'club' || $target == 'blog') {
            $club = cmsCore::m('clubs')->getClub($target_id);
        } else if ($target == 'album') {
            $album = cmsCore::c('db')->getNsCategory('cms_photo_albums', $target_id, null);
            
            if (!$album) { return false; }
            if (
                (!$album['published'] && !cmsCore::c('user')->is_admin) ||
                ($album['NSDiffer'] != 'club'. $album['user_id'])
            ) {
                return false;
            }
            
            $club = cmsCore::m('clubs')->getClub($album['user_id']);
        }
        
        if (empty($club)) { return false; }
            
        if (
            (!$club['published'] || $club['clubtype'] == 'private') &&
            !cmsCore::c('user')->is_admin
        ) {
            return false;
        }

        $items = array();
        
        if ($target == 'club') {
            if ($club['enabled_blogs']) {
                cmsCore::c('blog')->owner = 'club';
                $blog = cmsCore::c('blog')->getBlogByUserId($club['id']);
                
                $items[] = array(
                    'target_id' => $club['id'],
                    'target' => 'blog',
                    'title' => $blog['title'],
                    'link' => '/clubs/'. $club['id'] .'_blog'
                );
            }
            
            if ($club['enabled_photos']) {
                $root_id = cmsCore::c('db')->get_field('cms_photo_albums', "parent_id=0 AND NSDiffer='club". $club['id'] ."'", 'id');
                if (!empty($root_id)) {
                    $results = cmsCore::c('db')->query('SELECT id, title FROM cms_photo_albums WHERE parent_id='. $root_id);
                    
                    if (cmsCore::c('db')->num_rows($results)) {
                        while ($album = cmsCore::c('db')->fetch_assoc($results)) {
                            $items[] = array(
                                'target_id' => $album['id'],
                                'target' => 'album',
                                'title' => $album['title'],
                                'link' => '/clubs/photoalbum'. $album['id']
                            );
                        }
                    }
                }
            }
        }
        
        if ($target == 'blog') {
            if ($club['enabled_blogs']) {
                cmsCore::c('blog')->owner = 'club';
                $blog = cmsCore::c('blog')->getBlogByUserId($club['id']);
                
                cmsCore::c('db')->addSelect('b.user_id as bloglink');
                cmsCore::c('blog')->whereBlogIs($blog['id']);
                
                if (!cmsCore::c('user')->is_admin) {
                    cmsCore::c('blog')->whereOnlyPublic();
                }
                
                $posts = cmsCore::c('blog')->getPosts(cmsCore::c('user')->is_admin, cmsCore::m('clubs'));
                
                foreach ($posts as $post) {
                    $items[] = array(
                        'id' => $post['id'],
                        'title' => $post['title'],
                        'link' => $post['url']
                    );
                }
            }
        }
        
        if ($target == 'album') {
            if ($club['enabled_photos']) {
                cmsCore::c('photo')->whereAlbumIs($album['id']);
                
                $photos = cmsCore::c('photo')->getPhotos(cmsCore::c('user')->is_admin);
                
                foreach ($photos as $photo) {
                    $items[] = array(
                        'id' => $photo['id'],
                        'title' => $photo['title'],
                        'link' => '/clubs/photo'. $photo['id'] .'.html'
                    );
                }
            }
        }
        
        return $items;
    }

    public function getSectionItemsCount($target_id=0, $target='') {
        $count = 0;
        
        if ($target == 'blog') {
            $club = cmsCore::m('clubs')->getClub($target_id);
            if ($club['enabled_blogs']) {
                cmsCore::c('blog')->owner = 'club';
                $blog = cmsCore::c('blog')->getBlogByUserId($club['id']);
                
                cmsCore::c('blog')->whereBlogIs($blog['id']);
                
                if (!cmsCore::c('user')->is_admin) {
                    cmsCore::c('blog')->whereOnlyPublic();
                }
                
                $count = cmsCore::c('blog')->getPostsCount(cmsCore::c('user')->is_admin);
            }
        }
        
        if ($target == 'album') {
            $album = cmsCore::c('db')->getNsCategory('cms_photo_albums', $target_id, null);
            
            if (!$album) { return false; }
            if (
                (!$album['published'] && !cmsCore::c('user')->is_admin) ||
                ($album['NSDiffer'] != 'club'. $album['user_id'])
            ) {
                return false;
            }
            
            $club = cmsCore::m('clubs')->getClub($album['user_id']);
            
            if ($club['enabled_photos']) {
                cmsCore::c('photo')->whereAlbumIs($album['id']);
                $count = cmsCore::c('photo')->getPhotosCount(cmsCore::c('user')->is_admin);
            }
        }
        
        return $count;
    }

    //==========================================================================

    public function generateMap() {
        $gen_map = parent::generateMap();
        
        if ($gen_map === false) { return; }

        $is_end = false;
        $item_nums = 0;
        
        cmsCore::c('blog')->owner = 'club';
        
        while($is_end === false) {
            $results = cmsCore::c('db')->query("SELECT id, title, pubdate FROM cms_clubs WHERE clubtype='public' AND published=1 ORDER BY pubdate ASC LIMIT ". $item_nums .", 1000");
            
            if (cmsCore::c('db')->num_rows($results)) {
                while ($club = cmsCore::c('db')->fetch_assoc($results)) {
                    if ($club['enabled_blogs']) {
                        $blog_id = cmsCore::c('db')->get_field('cms_blogs', "owner='club' AND user_id='". $club['id'] ."'", 'id');
                        $last_post_date = cmsCore::c('db')->get_field('cms_blog_posts', "blog_id='". $blog_id ."' ORDER BY pubdate DESC", 'pubdate');
                    }
                    
                    $last_date =  explode(' ', !empty($last_post_date) ? $last_post_date : $club['pubdate']);

                    //Записываем клуб
                    $this->writeMapItem(array(
                        'loc' => cmsCore::c('config')->host .'/clubs/'. $club['id'],
                        'changefreq' => 'daily',
                        'priority' => '0.8',
                        'lastmod' => $last_date[0]
                    ));
                    
                    if ($club['enabled_blogs']) {
                        // Заносим в карту блог клуба
                        $this->writeMapItem(array(
                            'loc' => cmsCore::c('config')->host .'/clubs/'. $club['id'] .'_blog',
                            'changefreq' => 'daily',
                            'priority' => '0.8',
                            'lastmod' => $last_date[0]
                        ));
                        
                        // Заносим в карту посты клубного блога
                        $this->writeClubBlogPosts($blog_id, $club['id']);
                    }
                    
                    if ($club['enabled_photos']) {
                        // Заносим в карту фотоальбомы и фотографии клуба
                        $this->writeClubAlbums($club['id']);
                    }

                    $item_nums++;
                }
            } else {
                $is_end = true;
            }
        }
        
        $this->closeFile();
    }
    
    private function writeClubBlogPosts($blog_id, $club_id) {
        $is_end = false;
        $item_nums = 0;
        
        //Записываем посты блога
        while($is_end === false) {
            $results = cmsCore::c('db')->query("SELECT p.seolink, p.pubdate FROM cms_blog_posts WHERE blog_id = ". $blog_id ." published = 1 AND allow_who = 'all' ORDER BY pubdate ASC LIMIT ". $item_nums .', 1000');

            if (cmsCore::c('db')->num_rows($results)) {
                while ($post = cmsCore::c('db')->fetch_assoc($results)) {
                    $last_date = explode(' ', $post['pubdate']);

                    $this->writeMapItem(array(
                        'loc' => cmsCore::c('config')->host .'/clubs/'. $club_id .'_'. $post['seolink'] .'.html',
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
    }
    
    private function writeClubAlbums($club_id) {
        $root_id = cmsCore::c('db')->get_field('cms_photo_albums', "parent_id=0 AND NSDiffer='club". $club_id ."'", 'id');
        
        if (!empty($root_id)) {
            $results = cmsCore::c('db')->query('SELECT id, pubdate FROM cms_photo_albums WHERE parent_id='. $root_id);
            if (cmsCore::c('db')->num_rows($results)) {
                while ($album = cmsCore::c('db')->fetch_assoc($results)) {
                    $last_date = explode(' ', $album['pubdate']);

                    $this->writeMapItem(array(
                        'loc' => cmsCore::c('config')->host .'/clubs/photoalbum'. $album['id'],
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                        'lastmod' => $last_date[0]
                    ));

                    $results2 = cmsCore::c('db')->query("SELECT id, pubdate FROM cms_photo_files WHERE album_id = ". $album['id'] ." AND published = 1");
                    
                    if (cmsCore::c('db')->num_rows($results2)) {
                        while ($photo = cmsCore::c('db')->fetch_assoc($results2)) {
                            $last_date = explode(' ', $photo['pubdate']);

                            $this->writeMapItem(array(
                                'loc' => cmsCore::c('config')->host .'/clubs/photo'. $photo['id'] .'.html',
                                'changefreq' => 'monthly',
                                'priority' => '0.7',
                                'lastmod' => $last_date[0]
                            ));
                        }
                    }
                }
            }
        }
    }
}
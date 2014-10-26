<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.9                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

class video_sitemap extends cms_rudi_sitemap {
    private $cfg;
    
    protected $g_num = 0; // Количество ссылок записанных в файл google видео
    protected $g_open_file = null; // Ссылка на открытый файл google видео
    protected $g_page = 1; // Номер текущего файла google видео
    
    public function __construct() {
        parent::__construct();
        
        $this->cfg = cmsCore::getInstance()->loadComponentConfig('video');
    }
    
    public function getConfig() {
        global $_LANG;

        return array(
            array(
                'type'  => 'btn_yes_no',
                'title' => $_LANG['VIDEO_TAGS'],
                'description' => $_LANG['VIDEO_TAGS_DESC'],
                'name'  => 'tags'
            )
        );
    }
    
    public function getSections($target_id=0, $target='') {
        global $_LANG;
        
        if (empty($target_id) && empty($target)) {
            return array(
                array(
                    'target_id' => 0,
                    'target' => 'video-cat',
                    'title' => $_LANG['CATS'],
                    'link' => '/video'
                ),
                array(
                    'target_id' => 0,
                    'target' => 'video-rubric',
                    'title' => $_LANG['RUBRICS'],
                    'link' => '/video/rubrics'
                ),
            );
        } else if (!empty($target)) {
            if ($target == 'video-cat') {
                if (empty($target_id)) {
                    $target_id = cmsCore::c('db')->getNsRootCatId('cms_video_category');
                }
                
                $results = cmsCore::c('db')->query("SELECT id, title, seolink FROM cms_video_category WHERE parent_id='". $target_id ."' ". (cmsCore::c('user')->is_admin ? '' : ' AND published=1'));
                
                if (!cmsCore::c('db')->num_rows($results)) { return false; }
        
                $cats = array();

                while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
                    if (!cmsCore::checkUserAccess('video', $cat['id'])) {
                        continue;
                    }

                    $cats[] = array(
                        'target_id' => $cat['id'],
                        'target' => 'video-cat',
                        'title' => $cat['title'],
                        'link' => '/video/'. ($this->cfg['is_seo_url'] ? $cat['seolink'] : $cat['id'])
                    );
                }
            }
        }

        return !empty($cats) ? $cats : false;
    }

    public function getSection($target_id=0, $target='') {
        if (empty($target_id)) {
            global $_LANG;
            if ($target == 'video-cat') {
                return array(
                    'target_id' => 0,
                    'target' => 'video-cat',
                    'title' => $_LANG['CATS'],
                    'link' => '/video'
                );
            } else if ($target == 'video-rubric') {
                return array(
                    'target_id' => 0,
                    'target' => 'video-rubric',
                    'title' => $_LANG['CATS'],
                    'link' => '/video/rubrics'
                );
            } else {
                return false;
            }
        } else if ($target == 'video-cat') {
            $cat = cmsCore::c('db')->get_fields('cms_video_category', 'id='.$target_id .' '. (cmsCore::c('user')->is_admin ? '' : ' AND published=1'), 'id, title, seolink');

            if (!$cat) { return false; }

            if (!cmsCore::checkUserAccess('video', $cat['id'])) {
                return false;
            }
            
            if ($cat['id'] > 1) {
                $cat['seolink'] = ($this->cfg['is_seo_url'] ? $cat['seolink'] : $cat['id']);
            } else {
                $cat['seolink'] = '';
            }

            return array(
                'target_id' => $cat['id'],
                'target' => 'video_cat',
                'title' => $cat['title'],
                'link' => '/video/'. $cat['seolink']
            );
        } else {
            return false;
        }
    }

    public function getSectionItems($target_id=0, $target='') {
        if (empty($target_id) && $target != 'video-rubric') {
            return false;
        }
        
        $items = array();
        
        if ($target == 'video-rubric') {
            $results = cmsCore::c('db')->query("SELECT id, title, seolink FROM cms_video_rubric WHERE ". (cmsCore::c('user')->is_admin ? '1=1' : 'published=1') ." ORDER BY title ASC ". (!empty(cmsCore::c('db')->limit) ? ' LIMIT '. cmsCore::c('db')->limit : ''));
            
            if (!cmsCore::c('db')->num_rows($results)) { return false; }

            while ($item = cmsCore::c('db')->fetch_assoc($results)) {
                $items[] = array(
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'link' => '/video/rubrics/'. ($this->cfg['is_seo_url'] ? $item['seolink'] : 'view'. $item['id']) .'.html'
                );
            }
        } else if ($target == 'video-cat') {
            $results = cmsCore::c('db')->query("SELECT m.id, m.title, m.seolink, m.cat_id, cat.seolink as cat_seolink FROM cms_video_movie m INNER JOIN cms_video_category cat ON cat.id=m.cat_id WHERE ". (cmsCore::c('user')->is_admin ? '1=1' : 'm.published=1 AND cat.published=1') ." ORDER BY title ASC ". (!empty(cmsCore::c('db')->limit) ? ' LIMIT '. cmsCore::c('db')->limit : ''));
            
            if (!cmsCore::c('db')->num_rows($results)) { return false; }

            while ($item = cmsCore::c('db')->fetch_assoc($results)) {
                if ($this->cfg['is_seo_url']) {
                    if ($this->cfg['short_seo_url']) {
                        $item['seolink'] = $item['seolink'];
                    } else {
                        $item['seolink'] = $item['cat_seolink'] .'/'. $item['seolink'];
                    }
                } else {
                    $item['seolink'] = 'movie'. $item['id'];
                }
                
                $items[] = array(
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'link' => '/video/'. $item['seolink'] .'.html'
                );
            }
        }
        
        return !empty($items) ? $items : false;
    }

    public function getSectionItemsCount($target_id=0, $target='') {
        $count = 0;
        
        if ($target == 'video-rubric' && empty($target_id)) {
            $count = cmsCore::c('db')->rows_count('cms_video_rubric', (cmsCore::c('user')->is_admin ? '1=1' : 'published=1'));
        }
        
        if ($target == 'video-cat' && !empty($target_id)) {
            $count = cmsCore::c('db')->rows_count('cms_video_movie', 'cat_id='. $target_id .' '. (cmsCore::c('user')->is_admin ? '' : 'AND published=1'));
        }
        
        return $count;
    }

    //==========================================================================

    public function generateMap() {
        $gen_map = parent::generateMap();
        
        if ($gen_map === false) { return; }

        $is_end = false;
        $access_cats = array();
        
        // Заносим в карту категории
        $results = cmsCore::c('db')->query("SELECT * FROM cms_video_category WHERE parent_id != 0 AND published = 1 ORDER BY id ASC");

        if (cmsCore::c('db')->num_rows($results)) {
            while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
                if (!cmsCore::checkUserAccess('video', $cat['id'])) {
                    $access_cats[] = $cat['id'];
                    continue;
                }
                
                $last_date = cmsCore::c('db')->get_field('cms_video_movie', "cat_id='". $cat['id'] ."' ORDER BY pubdate DESC", 'pubdate');
                $last_date = explode(' ', !empty($last_date) ? $last_date : $cat['pubdate']);

                $this->writeMapItem(array(
                    'target' => 'video-cat',
                    'loc' => HOST .'/video/'. ($this->cfg['is_seo_url'] ? $cat['seolink'] : $cat['id']),
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                    'lastmod' => $last_date[0]
                ));
            }
        }
        
        // Заносим в карту рубрики
        $results = cmsCore::c('db')->query("SELECT * FROM cms_video_rubric WHERE published = 1 ORDER BY id ASC");

        if (cmsCore::c('db')->num_rows($results)) {
            while ($cat = cmsCore::c('db')->fetch_assoc($results)) {
                $last_date = cmsCore::c('db')->get_field('cms_video_movie', "rubric_id='". $cat['id'] ."' ORDER BY pubdate DESC", 'pubdate');
                $last_date = explode(' ', !empty($last_date) ? $last_date : $cat['pubdate']);

                $this->writeMapItem(array(
                    'target' => 'video-rubric',
                    'loc' => HOST .'/video/rubrics/'. ($this->cfg['is_seo_url'] ? $cat['seolink'] : 'view'. $cat['id']) .'.html',
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                    'lastmod' => $last_date[0]
                ));
            }
        }
        
        $item_nums = 0;
        
        // Заносим в карту видео материалы
        while($is_end === false) {
            $results = cmsCore::c('db')->query("SELECT m.*, cat.title as cat_title, cat.seolink as cat_seolink FROM cms_video_movie m INNER JOIN cms_video_category cat ON cat.id=m.cat_id WHERE m.published=1 AND cat.published=1 ORDER BY id ASC LIMIT ". $item_nums .', 1000');
            
            if (cmsCore::c('db')->num_rows($results)) {
                while ($item = cmsCore::c('db')->fetch_assoc($results)) {
                    if (in_array($cat['id'], $access_cats)) {
                        continue;
                    }
                    
                    if ($this->cfg['is_seo_url']) {
                        if ($this->cfg['short_seo_url']) {
                            $item['seolink'] = $item['seolink'];
                        } else {
                            $item['seolink'] = $item['cat_seolink'] .'/'. $item['seolink'];
                        }
                    } else {
                        $item['seolink'] = 'movie'. $item['id'];
                    }
                    
                    $last_date = explode(' ', $item['pubdate']);
                    
                    $item['loc'] = HOST .'/video/'. $item['seolink'] .'.html';
                    $item['changefreq'] = 'weekly';
                    $item['priority']   = '0.7';
                    $item['lastmod'] = $last_date[0];
                    $item['cat_link'] = HOST .'/video/'. ($this->cfg['is_seo_url'] ? $item['cat_seolink'] : $item['cat_id']);

                    $this->writeMapItem($item);
                    
                    $item_nums++;
                }
            } else {
                $is_end = true;
            }
        }
        
        $this->closeFile();
    }

    protected function deleteAllFiles() {
        parent::deleteAllFiles();
        
        $dir = PATH .'/upload/sitemaps';
        $pdir = opendir($dir);
        $page = 1;

        while ($nextfile = readdir($pdir)){
            $match = array();
            if (($nextfile != '.') && ($nextfile != '..') && !is_dir($dir .'/'. $nextfile) && preg_match('#'. $this->config['component'] .'_g_[0-9]+#is', $nextfile)){
                unlink($dir .'/'. $nextfile);
            }
        }
    }

    protected function openFile() {
        parent::openFile();
        
        $this->g_open_file = fopen(PATH .'/upload/sitemaps/'. $this->config['component'] .'_g_'. $this->g_page .'.xml', 'w');
        fwrite($this->g_open_file, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' ."\n");
    }
    
    protected function closeFile() {
        parent::closeFile();
        
        if (!empty($this->g_open_file)) {
            fwrite($this->g_open_file, '</urlset>');
            fclose($this->g_open_file);
            $this->g_open_file = null;
        }
    }

    protected function createNewPage() {
        parent::createNewPage();
        
        $this->closeFile();
        $this->g_page++;
        $this->openFile();
    }

    protected function writeMapItem($item) {
        parent::writeMapItem($item);
        
        if (isset($item['target'])) {
            return;
        }
        
        if (empty($item) || empty($item['loc'])) { return false; }
        
        $this->g_num++;
        
        if ($this->g_num > $this->max_num*$this->g_page) {
            $this->createNewPage();
        }
            
        $xml  = '    <url>'. "\n";
        $xml .= '        <loc>'. $item['loc'] .'</loc>'. "\n";
        $xml .= '        <video:video>'. "\n";
        $xml .= '            <video:thumbnail_loc>'. HOST .'/upload/video/thumbs/medium/'. $item['img'] .'</video:thumbnail_loc>'. "\n";
        $xml .= '            <video:title>'. htmlspecialchars($item['title']) .'</video:title>'. "\n";
        $xml .= '            <video:description>'. htmlspecialchars(strip_tags($item['description'])) .'</video:description>'. "\n";
        $xml .= '            <video:player_loc>'. HOST .'/embed/'. $item['id'] .'</video:player_loc>'. "\n";
        $xml .= '            <video:duration>'. $item['duration'] .'</video:duration>'. "\n";
        $xml .= '            <video:view_count>'. $item['hits'] .'</video:view_count>'. "\n";
        $xml .= '            <video:publication_date>'. date(DATE_ATOM, strtotime($item['pubdate'])) .'</video:publication_date>'. "\n";
        $xml .= '            <video:family_friendly>'. ($item['is_adult'] ? 'no' : 'yes') .'</video:family_friendly>'. "\n";
        
        if ($this->config['tags']) {
            $tags = $this->getTags($item['id']);
            
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $xml .= '            <video:tag>'. htmlspecialchars($tag) .'</video:tag>'. "\n";
                }
            }
        }
        
        $xml .= '            <video:category>'. htmlspecialchars($item['cat_title']) .'</video:category>'. "\n";
        $xml .= '            <video:gallery_loc title="'. htmlspecialchars($item['cat_title']) .'">'. $item['cat_link'] .'</video:gallery_loc>'. "\n";
        $xml .= '        </video:video>'. "\n";
        $xml .= '    </url>'. "\n";
        
        fwrite($this->g_open_file, $xml);
        
        return true;
    }
    
    public function getMapFiles() {
        $files = parent::getMapFiles();
        
        $num = $this->g_num - $this->max_num*($this->g_page-1);
        
        if ($num <= 0) {
            unlink(PATH .'/upload/sitemaps/'. $this->config['component'] .'_g_'. $this->page .'.xml');
            
            if ($this->g_page == 1) {
                return $files;
            }
            
            $this->g_page--;
        }
        
        for ($i=1; $i <= $this->g_page; $i++) {
            $files[] = $this->config['component'] .'_g_'. $i .'.xml';
        }

        return $files;
    }
    
    //==========================================================================
    
    private function getTags($video_id) {
        $tags = array();
        
        $results = cmsCore::c('db')->query("SELECT tag FROM cms_tags WHERE target='video' AND item_id='". $video_id ."' LIMIT 32");
        
        if (cmsCore::c('db')->num_rows($results)) {
            while ($tag = cmsCore::c('db')->fetch_assoc($results)) {
                $tags[] = $tag['tag'];
            }
        }
        
        return $tags;
    }
}
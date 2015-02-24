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

class users_sitemap extends cms_rudi_sitemap {
    public function getConfig() { return false; }

    public function getSections($target_id=0, $target='') {
        return false;
    }

    public function getSection($target_id=0, $target='') {
        return false;
    }

    public function getSectionItems($target_id=0, $target='') {
        $config = cmsCore::getInstance()->loadComponentConfig('users');
        
        if ($config['sw_guest']) {
            $results = cmsCore::c('db')->query("SELECT id, login, nickname FROM cms_users WHERE ". (cmsCore::c('user')->is_admin ? "1=1" : "is_locked=0 AND is_deleted=0") ." ORDER BY nickname ASC". (!empty(cmsCore::c('db')->limit) ? ' LIMIT '. cmsCore::c('db')->limit : ''));
            
            if (!cmsCore::c('db')->num_rows($results)) { return false; }
            
            $items = array();
            while ($user = cmsCore::c('db')->fetch_assoc($results)) {
                $items[] = array(
                    'id' => $user['id'],
                    'title' => $user['nickname'],
                    'link' => cmsUser::getProfileURL($user['ligin'])
                );
            }
            
            return $items;
        }
        
        return false;
    }

    public function getSectionItemsCount($target_id=0, $target='') {
        return cmsCore::c('db')->rows_count('cms_users', 'is_locked=0 AND is_deleted=0');
    }

    //==========================================================================

    public function generateMap() {
        $config = cmsCore::getInstance()->loadComponentConfig('users');
        
        if (!$config['sw_guest']) {
            $this->page = 0;
            return;
        }

        $gen_map = parent::generateMap();
        
        if ($gen_map === false) { return; }
        
        $is_end = false;
        $item_nums = 0;
        
        // Заносим в карту пользователей
        while ($is_end === false) {
            $results = cmsCore::c('db')->query("SELECT id, login, nickname FROM cms_users WHERE is_locked=0 AND is_deleted=0 ORDER BY nickname ASC LIMIT ". $item_nums .", 1000");
            
            if (cmsCore::c('db')->num_rows($results)) {
                while ($user = cmsCore::c('db')->fetch_assoc($results)) {
                    $this->writeMapItem(array(
                        'loc' => cmsCore::c('config')->host . cmsUser::getProfileURL($user['ligin']),
                        'changefreq' => 'daily',
                        'priority' => '0.8'
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
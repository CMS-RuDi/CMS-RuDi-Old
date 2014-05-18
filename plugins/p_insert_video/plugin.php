<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.4                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2013 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_insert_video extends cmsPlugin {

    public function __construct(){

        parent::__construct();

        $this->info['plugin']           = 'p_insert_video';
        $this->info['title']            = 'Прикрепление к статьям Видео материалов';
        $this->info['description']      = 'На страницу добавления редактирования статьи плагин встраивает возможность прикреплять видео материалы к статье. После прикрепления видео в текст статью нужно прописать команду {video#100} где 100 это id прикрепленного видео, при просмотре статьи эта команда будет заменена на сам код видео плеера.';
        $this->info['author']           = 'DS Soft';
        $this->info['version']          = '0.0.1';

        $this->config['PIV_DOMENS']     = 'youtube.com,vk.com,vkontakte.ru,rutube.ru,instagram.com';

        $this->events[]                 = 'AFTER_COMPONENT_CONTENT';
        $this->events[]                 = 'ADD_ARTICLE_SUCCESS';
        $this->events[]                 = 'UPDATE_ARTICLE';
        $this->events[]                 = 'GET_ARTICLE';
        $this->events[]                 = 'DELETE_ARTICLE';

    }

    public function install(){
        cmsCore::c('db')->query('CREATE TABLE IF NOT EXISTS `cms_content_videos` ( `id` int(11) NOT NULL AUTO_INCREMENT, `target_id` int(11) NOT NULL, `target` varchar(32) NOT NULL, `code` text NOT NULL, `user_id` int(11) NOT NULL, `pubdate` datetime NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
        return parent::install();
    }

    public function upgrade(){
        return parent::upgrade();
    }

    public function execute($event='', $item=array()){
        
        parent::execute();

        switch ($event){
            case 'GET_ARTICLE':
                $item = $this->eventGetArticle($item);
            break;

            case 'AFTER_COMPONENT_CONTENT':
                $item = $this->insertForm($item);
            break;
            
            case 'ADD_ARTICLE_SUCCESS':
                $item = $this->submitForm($item['id']);
            break;
            
            case 'DELETE_ARTICLE':
                cmsCore::c('db')->delete('`cms_content_videos`', "`target` = 'content' AND `target_id` = '". $item ."'");
            break;
        }

        return $item;
        
    }
    
    private function eventGetArticle($item){
        cmsCore::c('page')->addHead('<style type="text/css">.p_insert_video{ width:500px; margin: 0 auto; } .p_insert_video iframe, .p_insert_video object, .p_insert_video embed{ max-width: 500px; }</style>');
        
        $item['content'] = preg_replace('#\{video\#([0-9]+)\}#is', '<div class="p_insert_video" id="p_insert_video\\1">{video#\\1}<script type="text/javascript">$(function(){ $.post("/plugins/p_insert_video/ajax/ajax.php", "video_id=\\1&do=get_video", function (msg){ $("#p_insert_video\\1").html(msg); }); });</script></div>', $item['content']);
        
        return $item;
        
    }
    
    private function insertForm($html){
        
        if (($this->inCore->do == 'addarticle' || $this->inCore->do == 'editarticle') && !cmsCore::inRequest('add_mod')){
        
            $id = cmsCore::request('id', 'int', 0);

            ob_start();
            cmsPage::initTemplate('plugins', 'p_insert_video.tpl')->
                assign('target', 'content')->
                assign('target_id', $id)->
                assign('videos', cmsCore::c('db')->get_table('cms_content_videos', "`target` = 'content' AND `target_id` = '". $id ."'". ($id == 0 ? " AND `user_id` = '". cmsCore::c('user')->id ."'" : "")))->
                assign('cfg', $this->config)->
                display('p_insert_video.tpl');
            
            $html = preg_replace('#<script type="text/javascript">[\s]+var LANG_SELECT_CAT#is',  ob_get_clean() ."\n". '<script type="text/javascript"> var LANG_SELECT_CAT', $html);
            
        }
        
        return $html;
    }
    
    private function submitForm($id){
        cmsCore::c('db')->query("UPDATE `cms_content_videos` SET `target_id` = '". $id ."' WHERE `target` = 'content' AND `user_id` = '". cmsCore::c('user')->id ."' AND `target_id` = '0'");
    }
}

?>
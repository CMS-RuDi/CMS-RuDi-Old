<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_insert_video extends cmsPlugin {
    public function __construct() {
        $this->info = array(
            'plugin'      => 'p_insert_video',
            'title'       => 'Прикрепление к статьям Видео материалов',
            'description' => 'На страницу добавления редактирования статьи плагин встраивает возможность прикреплять видео материалы к статье. После прикрепления видео в текст статьи нужно прописать команду {video#100} где 100 это id прикрепленного видео, при просмотре статьи эта команда будет заменена на сам код видео плеера.', 
            'author'      => 'DS Soft',
            'version'     => '0.0.5'
        );
        
        $this->config = array(
            'PIV_DOMENS' => 'youtube.com,vk.com,vkontakte.ru,rutube.ru,instagram.com',
            'PIV_TAB' => 'Видео'
        );
        
        $this->events = array(
            'AFTER_COMPONENT_CONTENT', 'ADD_ARTICLE_SUCCESS', 'GET_ARTICLE',
            'DELETE_ARTICLE', 'ADMIN_CONTENT_TABS'
        );
        
        parent::__construct();
    }
    
    public function getConfigFields() {
        global $_LANG;
        return array(
            array(
                'type' => 'textarea',
                'title' => $_LANG['PIV_DOMENS'],
                'name' => 'PIV_DOMENS'
            ),
            array(
                'type' => 'text',
                'title' => $_LANG['PIV_TAB'],
                'name' => 'PIV_TAB'
            )
        );
    }

    public function install() {
        cmsCore::c('db')->query('CREATE TABLE IF NOT EXISTS `cms_content_videos` ( `id` int(11) NOT NULL AUTO_INCREMENT, `target_id` int(11) NOT NULL, `target` varchar(32) NOT NULL, `code` text NOT NULL, `user_id` int(11) NOT NULL, `pubdate` datetime NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
        return parent::install();
    }
    
    public function uninstall() {
        cmsCore::c('db')->query('DROP TABLE `cms_content_videos` IF EXISTS');
        return parent::uninstall();
    }

    public function execute($event='', $item=array()) {
        switch ($event) {
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
            
            case 'ADMIN_CONTENT_TABS':
                $this->info['tab'] = $this->config['PIV_TAB'];
                return $this->getFormHtml($item);
            break;
        }

        return $item;
    }
    
    private function eventGetArticle($item) {
        cmsCore::c('page')->addHead('<style type="text/css">.p_insert_video{ width:500px; margin: 0 auto; } .p_insert_video iframe, .p_insert_video object, .p_insert_video embed{ max-width: 500px; }</style>');
        
        $item['content'] = preg_replace('#\{video\#([0-9]+)\}#is', '<div class="p_insert_video" id="p_insert_video\\1">{video#\\1}<script type="text/javascript">$(function(){ $.post("/plugins/p_insert_video/ajax/ajax.php", "video_id=\\1&do=get_video", function (msg){ $("#p_insert_video\\1").html(msg); }); });</script></div>', $item['content']);
        
        return $item;
    }
    
    private function insertForm($html) {
        if (($this->inCore->do == 'addarticle' || $this->inCore->do == 'editarticle') && !cmsCore::inRequest('add_mod')) {
            $html = preg_replace('#<script type="text/javascript">[\s]+var LANG_SELECT_CAT#is', $this->getFormHtml(array('id' => cmsCore::request('id', 'int', 0))) ."\n". '<script type="text/javascript"> var LANG_SELECT_CAT', $html);
        }
        
        return $html;
    }
    
    private function getFormHtml($item) {
        $id = empty($item['id']) ? 0 : $item['id'];

        return cmsPage::initTemplate('plugins', 'p_insert_video')->
            assign('target', 'content')->
            assign('target_id', $id)->
            assign('videos', cmsCore::c('db')->get_table('cms_content_videos', "`target` = 'content' AND `target_id` = '". $id ."'". ($id == 0 ? " AND `user_id` = '". cmsCore::c('user')->id ."'" : "")))->
            assign('cfg', $this->config)->
            fetch();
    }
    
    private function submitForm($id) {
        cmsCore::c('db')->query("UPDATE `cms_content_videos` SET `target_id` = '". $id ."' WHERE `target` = 'content' AND `user_id` = '". cmsCore::c('user')->id ."' AND `target_id` = '0'");
    }
}
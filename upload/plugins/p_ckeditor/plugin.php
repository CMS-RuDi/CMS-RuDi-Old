<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_ckeditor extends cmsPlugin {
    private $removePlugins_all = array(
        'uicolor', 'scayt', 'about', 'bbcode'
    );
    private $removePlugins_user = array(
        'codesnippet', 'forms', 'flash', 'iframe'
    );
    private $removePlugins_forBBcode = array(
        'codesnippet', 'forms', 'flash', 'iframe', 'uicolor', 'scayt', 'div', 'about'
    );
    
    private $tbar_admin = "[{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] }, { name: 'clipboard', groups: [ 'clipboard', 'undo' ] }, { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] }, '/', { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] }, { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] }, '/', { name: 'links' }, { name: 'forms' }, { name: 'insert' }, '/', { name: 'styles' }, { name: 'colors' }, { name: 'tools' }, { name: 'others' } ]";
    
    private $tbar_user = "[{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] }, { name: 'clipboard', groups: [ 'clipboard', 'undo' ] }, { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] }, '/', { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] }, { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ] }, { name: 'links' }, { name: 'insert' }, { name: 'styles' }, { name: 'colors' }, { name: 'tools' }, { name: 'others' } ]";
    
    private $admin_tbar = "[{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] }, { name: 'clipboard', groups: [ 'clipboard', 'undo' ] }, { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] }, { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] }, { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] }, '/', { name: 'links' }, { name: 'forms' }, { name: 'insert' }, { name: 'styles' }, { name: 'colors' }, { name: 'tools' }, { name: 'others' } ]";


    public function __construct() {
        $this->info = array(
            'plugin'      => 'p_ckeditor',
            'title'       => 'CKEditor 4.4.5',
            'description' => 'Визуальный редактор',
            'author'      => 'Plugin - DS SOFT. CKEditor - Frederico Knabben',
            'version'     => '0.0.3',
            'type'        => 'wysiwyg'
        );
        
        $this->config = array(
            'inline'     => 1,
            'admin_skin' => 'moono',
            'user_skin'  => 'moono'
        );
        
        $this->events = array(
            'INSERT_WYSIWYG',
            'INSERT_BBCODE_EDITOR',
            'GET_ARTICLE'
        );
        
        parent::__construct();
    }
    
    public function getConfigFields() {
        global $_LANG;
        
        return array(
            array(
                'type' => 'btn_yes_no',
                'title' => $_LANG['PCK_INLINE'],
                'name' => 'inline',
            ),
            array(
                'type' => 'dir_list',
                'title' => $_LANG['PCK_ADMIN_SKIN'],
                'name' => 'admin_skin',
                'path' => '/plugins/p_ckeditor/ckeditor/skins'
            ),
            array(
                'type' => 'dir_list',
                'title' => $_LANG['PCK_USER_SKIN'],
                'name' => 'user_skin',
                'path' => '/plugins/p_ckeditor/ckeditor/skins'
            )
        );
    }

    public function execute($event='', $item=array()) {
        switch ($event) {
            case 'INSERT_WYSIWYG':
                    return $this->insertEditor($item);
                break;
            case 'GET_ARTICLE':
                    return $this->insertInlineEditor($item);
                break;
            case 'INSERT_BBCODE_EDITOR':
                    return $this->insertBBcodeEditor($item);
                break;
            default:
                    return $item;
                break;
        }
    }
    
    private function getToolbarAndRPlugins() {
        cmsCore::c('page')->addHeadJS('plugins/p_ckeditor/ckeditor/ckeditor.js');
        
        $rplugins = cmsCore::c('user')->is_admin ? $this->removePlugins_all : array_merge_recursive($this->removePlugins_all, $this->removePlugins_user);
        $rplugins = implode(',', $rplugins);
        
        $tbar = '';
        if (!defined('VALID_CMS_ADMIN')) {
            if (cmsCore::c('user')->is_admin) {
                $tbar = $this->tbar_admin;
            } else {
                $tbar = $this->tbar_user;
            }
        } else {
            $tbar = $this->admin_tbar;
        }
        
        return array( 'rplugins' => $rplugins, 'tbar' => $tbar, 'skin' => cmsCore::c('user')->is_admin ? $this->config['admin_skin'] : $this->config['user_skin'] );
    }

    private function insertEditor($item) {
        cmsCore::c('page')->addHead('<script type="text/javascript">function wysiwygInsertHtml(html, name){ if (!name){ name="content"; } eval("CKEDITOR.instances.con_"+ name +".insertHtml(\'"+ html +"\');") }</script>');
        
        $opt = $this->getToolbarAndRPlugins();
        
        $html = '<textarea id="con_'. $item['name'] .'" name="'. $item['name'] .'">'. htmlspecialchars($item['text']) .'</textarea>';
        $html .= '<script type="text/javascript">$(function(){ CKEDITOR.replace("con_'. $item['name'] .'", {height:"'. $item['height'] .'", width:"'. $item['width'] .'", language:"'. cmsCore::c('config')->lang .'"'. (!empty($opt['rplugins']) ? ', removePlugins:"'. $opt['rplugins'] .'"' : '') .', toolbarGroups: '. $opt['tbar'] .', allowedContent:true, skin: "'. $opt['skin'] .'"}); });</script>';
        
        return $html;
    }
    
    private function insertInlineEditor($item) {
        $this->info['type'] = 'plugin';
        
        if (empty($this->config['inline'])) {
            return $item;
        }
        
        if (cmsCore::c('user')->is_admin || ($item['user_id'] == cmsCore::c('user')->id) || ($item['modgrp_id'] == cmsCore::c('user')->group_id && cmsUser::isUserCan('content/autoadd')))
        {
            global $_LANG;
            
            cmsCore::c('page')->addHead('<script type="text/javascript">function ajaxSaveArticle(id){ $.post("/plugins/p_ckeditor/ajax.php", "item_id="+ id +"&content="+encodeURIComponent(CKEDITOR.instances.conEditable.getData()), function (msg){ core.alert(msg); }); }</script>');
            
            $opt = $this->getToolbarAndRPlugins();

            $item['content'] = '<div id="conEditable" contenteditable="true">'. $item['content'] .'</div><div style="text-align:right"><input type="button" onclick="ajaxSaveArticle('. $item['id'] .');" value="'. $_LANG['SAVE'] .'" class="button" /></div>';
            
            $item['content'] .= '<script type="text/javascript">$(function(){ CKEDITOR.disableAutoInline = true; CKEDITOR.config.language = "'. cmsCore::c('config')->lang .'"; CKEDITOR.config.skin = "'. $opt['skin'] .'";'. (!empty($opt['rplugins']) ? ' CKEDITOR.config.removePlugins="'. $opt['rplugins'] .'";' : '') .'  CKEDITOR.config.allowedContent=true;  CKEDITOR.config.toolbarGroups = '. $opt['tbar'] .'; CKEDITOR.inline("conEditable"); });</script>';
            
            $item['content'] .= '<style type="text/css">.cke_editable.cke_editable_inline{cursor: pointer;}.cke_editable.cke_editable_inline.cke_focus{box-shadow: inset 0px 0px 20px 3px #ddd, inset 0 0 1px #000;outline: none;cursor: text;}</style>';
        }
        
        return $item;
    }
    
    private function insertBBcodeEditor($item){
        return '';
    }
}
<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.3                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2013 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_ckeditor extends cmsPlugin {
    private $removePlugins_all = array(
        'uicolor', 'scayt', 'about', 'bbcode', 'autosave'
    );
    private $removePlugins_user = array(
        'codesnippet', 'forms', 'flash', 'iframe'
    );
    private $removePlugins_forBBcode = array(
        'codesnippet', 'forms', 'flash', 'iframe', 'uicolor', 'scayt', 'div', 'about', 'autosave'
    );
    
    public function __construct(){

        parent::__construct();

        $this->info['plugin']      = 'p_ckeditor';
        $this->info['title']       = 'CKEditor 4.4';
        $this->info['description'] = 'Визуальный редактор';
        $this->info['author']      = 'Plugin - DS SOFT. CKEditor - Frederico Knabben';
        $this->info['version']     = '0.0.1';
        $this->info['type']        = 'wysiwyg';
        
        $this->config['PCK_INLINE']= '1';

        $this->events[]            = 'INSERT_WYSIWYG';
        $this->events[]            = 'INSERT_BBCODE_EDITOR';
        $this->events[]            = 'GET_ARTICLE';
    }

    public function install(){

        return parent::install();

    }

    public function upgrade(){

        return parent::upgrade();

    }

    public function execute($event='', $item=array()){

        parent::execute();
        
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
    
    private function getToolbarAndRPlugins(){
        $rplugins = cmsCore::c('user')->is_admin ? $this->removePlugins_all : array_merge_recursive($this->removePlugins_all, $this->removePlugins_user);
        $rplugins = implode(',', $rplugins);
        
        $tbar = '';
        if (cmsCore::c('user')->is_admin){
            $tbar = "[{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] }, { name: 'clipboard', groups: [ 'clipboard', 'undo' ] }, { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] }, '/', { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] }, { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] }, '/', { name: 'links' }, { name: 'forms' }, { name: 'insert' }, '/', { name: 'styles' }, { name: 'colors' }, { name: 'tools' }, { name: 'others' } ]";
        }else{
            $tbar = "[{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] }, { name: 'clipboard', groups: [ 'clipboard', 'undo' ] }, { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] }, '/', { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] }, { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ] }, { name: 'links' }, { name: 'insert' }, '/', { name: 'styles' }, { name: 'colors' }, { name: 'tools' }, { name: 'others' } ]";
        }
        
        return array('rplugins' => $rplugins, 'tbar' => $tbar);
    }

    private function insertEditor($item){
        
        cmsCore::c('page')->addHeadJS('plugins/p_ckeditor/ckeditor/ckeditor.js');
        cmsCore::c('page')->addHead('<script type="text/javascript">function wysiwygInsertHtml(html, name){ eval("CKEDITOR.instances.con_"+ name +".insertHtml(\'"+ html +"\');") }</script>');
        
        $opt = $this->getToolbarAndRPlugins();
        
        $html = '<textarea id="con_'. $item['name'] .'" name="'. $item['name'] .'">'. htmlspecialchars($item['text']) .'</textarea>';
        $html .= '<script type="text/javascript">$(function(){ CKEDITOR.replace("con_'. $item['name'] .'", {height:"'. $item['height'] .'", width:"'. $item['width'] .'", language:"'. cmsCore::c('config')->lang .'"'. (!empty($opt['rplugins']) ? ', removePlugins:"'. $opt['rplugins'] .'"' : '') .', toolbarGroups: '. $opt['tbar'] .', allowedContent:true}); });</script>';
        
        return $html;
        
    }
    
    private function insertInlineEditor($item){
        $this->info['type'] = 'plugin';
        
        if((cmsCore::c('user')->is_admin || ($item['user_id'] == cmsCore::c('user')->id) || ($item['modgrp_id'] == cmsCore::c('user')->group_id && cmsUser::isUserCan('content/autoadd'))) && $this->config['PCK_INLINE'] == 1)
        {
            global $_LANG;
            
            cmsCore::c('page')->addHeadJS('plugins/p_ckeditor/ckeditor/ckeditor.js');
            cmsCore::c('page')->addHead('<script type="text/javascript">function ajaxSaveArticle(id){ $.post("/plugins/p_ckeditor/ajax.php", "item_id="+ id +"&content="+encodeURIComponent(CKEDITOR.instances.conEditable.getData()), function (msg){ core.alert(msg); }); }</script>');
            
            $opt = $this->getToolbarAndRPlugins();

            $item['content'] = '<div id="conEditable" contenteditable="true">'. $item['content'] .'</div><div style="text-align:right"><input type="button" onclick="ajaxSaveArticle('. $item['id'] .');" value="'. $_LANG['SAVE'] .'" class="button" /></div>';
            
            $item['content'] .= '<script type="text/javascript">$(function(){ CKEDITOR.disableAutoInline = true; CKEDITOR.config.language = "'. cmsCore::c('config')->lang .'";'. (!empty($opt['rplugins']) ? ' CKEDITOR.config.removePlugins="'. $opt['rplugins'] .'";' : '') .'  CKEDITOR.config.allowedContent=true;  CKEDITOR.config.toolbarGroups = '. $opt['tbar'] .'; CKEDITOR.inline("conEditable"); });</script><style type="text/css"></style>';
            
            $item['content'] .= '<style type="text/css">.cke_editable.cke_editable_inline{cursor: pointer;}.cke_editable.cke_editable_inline.cke_focus{box-shadow: inset 0px 0px 20px 3px #ddd, inset 0 0 1px #000;outline: none;cursor: text;}</style>';
        }
        
        return $item;
        
    }
    
    private function insertBBcodeEditor($item){
        return '';
    }

}

?>
<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.3                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2013                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

class p_fckeditor extends cmsPlugin {

// ==================================================================== //

    public function __construct(){

        parent::__construct();

        $this->info['plugin']      = 'p_fckeditor';
        $this->info['title']       = 'FCKEditor';
        $this->info['description'] = 'Визуальный редактор';
        $this->info['author']      = 'F. C. Knabben';
        $this->info['version']     = '2.63';
        $this->info['type']        = 'wysiwyg';

        $this->events[]            = 'INSERT_WYSIWYG';

    }

// ==================================================================== //

    public function install(){
        return parent::install();
    }

// ==================================================================== //

    public function upgrade(){
        return parent::upgrade();
    }

// ==================================================================== //

    public function execute($event='', $item=array()){

        parent::execute();
        
        cmsCore::c('page')->addHead('<script type="text/javascript">function wysiwygInsertHtml(html, name){ if (!name){ name="content"; } var oEditor = FCKeditorAPI.GetInstance(name); if (oEditor.EditMode == FCK_EDITMODE_WYSIWYG ){ oEditor.InsertHtml(html); }else{ alert("EDITOR ERROR");}}</script>');

        cmsCore::includeFile('plugins/p_fckeditor/fckeditor/fckeditor.php');

        $oFCKeditor             = new FCKeditor($item['name']) ;
        $oFCKeditor->BasePath   = '/plugins/p_fckeditor/fckeditor/';
        $oFCKeditor->Height     = $item['height'];
        $oFCKeditor->Width      = $item['width'];

        $oFCKeditor->ToolbarSet = (cmsCore::c('user')->is_admin ? 'Admin' : 'Basic');

        $oFCKeditor->Value      = $item['text'];

        $oFCKeditor->Config['DefaultLanguage']    = cmsConfig::getConfig('lang');
        $oFCKeditor->Config['AutoDetectLanguage'] = false;

        if (!cmsCore::c('user')->is_admin){
            $oFCKeditor->Config['ImageBrowser'] = false;
            $oFCKeditor->Config['LinkUpload']   = false;
            $oFCKeditor->Config['LinkBrowser']  = false;
        }

        ob_start();

        $oFCKeditor->Create();

        return ob_get_clean();

    }


// ==================================================================== //

}

?>

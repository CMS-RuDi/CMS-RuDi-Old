<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.5                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

// загружаем библиотеку Smarty
cmsCore::includeFile('/includes/smarty/libs/Smarty.class.php');

/**
 * Класс инициализации шаблонизатора Smarty
 */
class smartyTpl {
    private $smarty;
    
    public function __construct($tpl_file, $template) {
        global $_LANG;
        
        $folders = explode('/', $tpl_file);

        $this->smarty = new cmsSmarty();

        $this->smarty->compile_id = $folders[count($folders)-2];
        
        $this->smarty->assign('LANG', $_LANG);
        $this->smarty->assign('template', $template);
        $this->smarty->assign('tpl_cfgs', cmsCore::getTplCfg($template));
        
        $domain = cmsCore::strToURL(cmsCore::getHost());
        
        $compile_dir = PATH .'/cache/tpl_'. $domain .'_'. $template;
        if (!file_exists($compile_dir)) {
            mkdir($compile_dir, 0777);
        }
        
        $this->smarty->setTemplateDir(PATH .'/templates/'. $template);
        $this->smarty->setCompileDir($compile_dir);
        
        $this->smarty->rudi_tpl_file = $tpl_file;
    }
    
    public function __set($name, $value) {
        $this->smarty->{$name} = $value;
    }
    
    public function __get($name) {
        return $this->smarty->{$name};
    }
    
    public function __call($name, $arguments) {
        return call_user_func_array(array($this->smarty, $name), $arguments);
    }
}

/**
 * Вспомогательный класс наследник Smarty
 */
class cmsSmarty extends Smarty {
    public $rudi_tpl_file;
    
    public function __construct() {
        parent::__construct();
        
        $this->assign('is_admin', cmsCore::c('user')->is_admin);
        $this->assign('user_id', cmsCore::c('user')->id);
        $this->assign('is_ajax', cmsCore::isAjax());
        $this->assign('is_auth', cmsCore::c('user')->id);
    }
    
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null) {
        parent::display($this->rudi_tpl_file, $cache_id, $compile_id, $parent);
    }
    
    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false) {
        return parent::fetch($this->rudi_tpl_file, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
    }
}
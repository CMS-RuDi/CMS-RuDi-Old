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
    private static $i_smarty;
    private $smarty;

    public function __construct($tpl_folder, $tpl_file) {
        global $_LANG;
        
        $tpl_folder = rtrim($tpl_folder, '/');
        $tpl_folder = explode('/', $tpl_folder);
        unset($tpl_folder[count($tpl_folder)-1]);
        $template_dir = implode('/', $tpl_folder);

        $this->smarty = $this->loadSmarty();
        
        $tdirs = array(
            'components' => $template_dir . '/components',
            'modules'    => $template_dir . '/modules',
            'plugins'    => $template_dir . '/plugins',
            'special'    => $template_dir . '/special',
            'splash'     => $template_dir . '/splash',
        );
        
        if (defined('VALID_CMS_ADMIN')) {
            unset($tdirs['splash']);
            $tdirs['applets'] = $template_dir . '/applets';
        }
        
        $this->smarty->setTemplateDir($tdirs);
        
        $this->smarty->compile_id = $tpl_folder[count($tpl_folder)-1];
        
        $this->smarty->assign('LANG', $_LANG);
        
        $this->smarty->rudi_tpl_file = $tpl_file;
    }

    private function loadSmarty() {
        if (isset(self::$i_smarty)) {
            return self::$i_smarty;
        }
        
        $smarty = new cmsRuDiSmarty();

        self::$i_smarty = $smarty;
        
        return $smarty;
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
class cmsRuDiSmarty extends Smarty {
    public $rudi_tpl_file;
    
    public function __construct(array $options = array()) {
        parent::__construct($options);
        
        $this->assign('is_admin', cmsCore::c('user')->is_admin);
        $this->assign('user_id', cmsCore::c('user')->id);
        $this->assign('is_ajax', cmsCore::isAjax());
        $this->assign('is_auth', cmsCore::c('user')->id);
        
        $this->compile_dir = PATH .'/cache';
    }
    
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null) {
        parent::display($this->rudi_tpl_file, $cache_id, $compile_id, $parent);
    }
    
    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false) {
        return parent::fetch($this->rudi_tpl_file, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
    }
}
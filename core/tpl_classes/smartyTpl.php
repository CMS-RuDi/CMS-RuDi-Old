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
        
        $this->smarty->setTemplateDir(array(
            'admin'      => $template_dir . '/admin',
            'components' => $template_dir . '/components',
            'modules'    => $template_dir . '/modules',
            'plugins'    => $template_dir . '/plugins',
            'special'    => $template_dir . '/special',
            'splash'     => $template_dir . '/splash',
        ));
        
        $this->smarty->compile_id = $tpl_folder[count($tpl_folder)-1];
        
        $this->smarty->assign('LANG', $_LANG);
        $this->smarty->assign('user_id', cmsCore::c('user')->id);
        $this->smarty->assign('is_admin', cmsCore::c('user')->is_admin);
        
        $this->smarty->rudi_tpl_file = $tpl_file;
    }

    private function loadSmarty() {
//        if (isset(self::$i_smarty)) {
//            return self::$i_smarty;
//        }
        
        $smarty = new cmsRuDiSmarty();

//        self::$i_smarty = $smarty;
        
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
        
        $this->registerPlugin('function', 'wysiwyg', 'cmsSmartyWysiwyg');
        $this->registerPlugin('function', 'profile_url', 'cmsSmartyProfileURL');
        $this->registerPlugin('function', 'component', 'cmsSmartyCurrentComponent');
        $this->registerPlugin('function', 'template', 'cmsSmartyCurrentTemplate');
        $this->registerPlugin('function', 'add_js', 'cmsSmartyAddJS');
        $this->registerPlugin('function', 'add_css', 'cmsSmartyAddCSS');
        $this->registerPlugin('function', 'comments', 'cmsSmartyComments');
        $this->registerPlugin('function', 'callEvent', 'cmsSmartyCallEvent');
        
        $this->assign('is_ajax', cmsCore::isAjax());
        
        $this->compile_dir = PATH .'/cache';
    }
    
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null) {
        parent::display($this->rudi_tpl_file, $cache_id, $compile_id, $parent);
    }
    
    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false) {
        return parent::fetch($this->rudi_tpl_file, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
    }
}

/**
 * Вспомогательные функции
 */
function cmsSmartyComments($params, $smarty){
    if (empty($params['target']) || empty($params['target_id'])) {
        return false;
    }

    cmsCore::includeComments();

    comments($params['target'], $params['target_id'], cmsCore::getArrVal($params, 'labels', array()));

    return;
}

function cmsSmartyAddJS($params, $smarty){
    if (empty($params['file'])) { return false; }

    cmsPage::getInstance()->addHeadJS($params['file']);
}

function cmsSmartyAddCSS($params, $smarty){
    if (empty($params['file'])) { return false; }

    cmsPage::getInstance()->addHeadCSS($params['file']);
}

function cmsSmartyWysiwyg($params, $smarty){
    if (empty($params['name'])) { return false; }
    
    ob_start();
        cmsCore::insertEditor(
            $params['name'],
            cmsCore::getArrVal($params, 'value', ''),
            cmsCore::getArrVal($params, 'height', 350),
            cmsCore::getArrVal($params, 'width', '100%')
        );
    return ob_get_clean();
}

function cmsSmartyProfileURL($params, $smarty){
    if (empty($params['login'])) { return false; }
    
    return cmsUser::getProfileURL($params['login']);
}

function cmsSmartyCurrentComponent($params, $smarty){
    return cmsCore::getInstance()->component;
}

function cmsSmartyCurrentTemplate($params, $smarty){
    return cmsCore::c('config')->template;
}

function cmsSmartyCallEvent($params, $smarty){
    if (empty($params['event'])) { return false; }
    
    return cmsCore::callEvent($params['event'], cmsCore::getArrVal($params, 'item', ''));
}
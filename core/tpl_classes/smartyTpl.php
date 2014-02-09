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
/**
 * Класс инициализации шаблонизатора Smarty
 */
class smartyTpl{

    private static $i_smarty;
    private $smarty;

    public function __construct($tpl_folder, $tpl_file){

        global $_LANG;

        $this->smarty = $this->loadSmarty();

        $is_exists_tpl_file = file_exists(TEMPLATE_DIR . $tpl_folder.'/'.$tpl_file);

        $this->smarty->template_dir = $is_exists_tpl_file ? TEMPLATE_DIR . $tpl_folder : DEFAULT_TEMPLATE_DIR . $tpl_folder;
        $this->smarty->compile_id   = $is_exists_tpl_file ? TEMPLATE : '_default_';
        $this->smarty->assign('LANG', $_LANG);

    }

    private function loadSmarty(){

        if(isset(self::$i_smarty)){
            return self::$i_smarty;
        }

        cmsCore::includeFile('/includes/smarty/libs/Smarty.class.php');

        $smarty = new Smarty();

        $smarty->compile_dir = PATH.'/cache';
        $smarty->register_function('wysiwyg', 'cmsSmartyWysiwyg');
        $smarty->register_function('profile_url', 'cmsSmartyProfileURL');
        $smarty->register_function('component', 'cmsSmartyCurrentComponent');
        $smarty->register_function('template', 'cmsSmartyCurrentTemplate');
        $smarty->register_function('add_js', 'cmsSmartyAddJS');
        $smarty->register_function('add_css', 'cmsSmartyAddCSS');
        $smarty->register_function('comments', 'cmsSmartyComments');
        $smarty->assign('is_ajax', cmsCore::isAjax());

        self::$i_smarty = $smarty;

        return $smarty;

    }

    public function __set($name, $value){
        $this->smarty->{$name} = $value;
    }

    public function __get($name){
        return $this->smarty->{$name};
    }

    public function __call($name, $arguments){
        return call_user_func_array(array($this->smarty, $name), $arguments);
    }

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Вспомогательные функции
 */
function cmsSmartyComments($params){

    if (!$params['target']) { return false; }
    if (!$params['target_id']) { return false; }

    cmsCore::includeComments();

    comments($params['target'], $params['target_id'], $params['labels']);

    return;

}

function cmsSmartyAddJS($params){
    cmsPage::getInstance()->addHeadJS($params['file']);
}

function cmsSmartyWysiwyg($params){
    ob_start();
    cmsCore::insertEditor($params['name'], $params['value'], $params['height'], $params['width']);
    return ob_get_clean();
}

function cmsSmartyAddCSS($params){
    cmsPage::getInstance()->addHeadCSS($params['file']);
}

function cmsSmartyProfileURL($params){
    return cmsUser::getProfileURL($params['login']);
}

function cmsSmartyCurrentComponent(){
	return cmsCore::getInstance()->component;
}
function cmsSmartyCurrentTemplate(){
	return TEMPLATE;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

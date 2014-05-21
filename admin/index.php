<?php Error_Reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
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

    header('Content-Type: text/html; charset=utf-8');
    header('X-Frame-Options: DENY');
    
    session_start();

    define("VALID_CMS", 1);
    define("VALID_CMS_ADMIN", 1);

    define('PATH', $_SERVER['DOCUMENT_ROOT']);

    require("../core/cms.php");
    require("includes/cp.php");

    require("../includes/tools.inc.php");

    $inCore = cmsCore::getInstance(false, true);

    cmsCore::loadClass('page');
    cmsCore::loadClass('user');
    cmsCore::loadClass('actions');

    if (!cmsCore::c('user')->update()) { cmsCore::error404(); }

    // проверяем доступ по Ip
    if(!cmsCore::checkAccessByIp(cmsCore::c('config')->allow_ip)) { cmsCore::error404(); }

    define('TEMPLATE_DIR', PATH .'/templates/'. cmsCore::c('config')->template .'/');
    define('DEFAULT_TEMPLATE_DIR', PATH .'/templates/_default_/');

    cmsCore::loadLanguage('admin/lang');
    global $_LANG;
    
    //-------CHECK AUTHENTICATION--------------------------------------//
    if (!cmsCore::c('user')->is_admin){
        include PATH.'/admin/login.php';
        cmsCore::halt();
    }
    
    //--------LOAD ACCESS OPTIONS LIST---------------------------------//

    $adminAccess = cmsUser::getAdminAccess();

    //------------------------------------------------------------------//

    cmsCore::c('user')->onlineStats();

    $GLOBALS['applet'] = cmsCore::request('view', 'str', 'main');
    if (!preg_match('/^[a-z0-9]+$/i', $GLOBALS['applet'])) { cmsCore::error404(); }

    cmsCore::c('page')->setAdminTitle();
    cmsCore::c('page')->addHeadJS('admin/js/common.js');
    cmsCore::c('page')->addHeadJS('includes/jquery/jquery.js');
    $GLOBALS['cp_page_head']  = array();
    $GLOBALS['cp_page_body']  = '';

    $GLOBALS['cp_pathway']             = array();
    $GLOBALS['cp_pathway'][0]['title'] = $_LANG['PATH_HOME'];
    $GLOBALS['cp_pathway'][0]['link']  = 'index.php';

    cpProceedBody();

    include("template.php");

?>
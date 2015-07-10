<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

//ini_set('display_errors','On');
//error_reporting('E_ALL');

Error_Reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
header('Content-Type: text/html; charset=utf-8');
header('X-Frame-Options: DENY');

session_start();

define('VALID_CMS', 1);
define('VALID_CMS_ADMIN', 1);

define('PATH', $_SERVER['DOCUMENT_ROOT']);

require(PATH .'/core/cms.php'); 
require(PATH .'/admin/includes/cp.php');

$inCore = cmsCore::getInstance(false, true);

cmsCore::loadClass('page');
cmsCore::loadClass('user');
cmsCore::loadClass('actions');

cmsCore::c('user')->autoLogin();

if (!cmsCore::c('user')->update()) {
    cmsCore::error404();
}

// проверяем доступ по Ip
if (!cmsCore::checkAccessByIp(cmsCore::c('config')->allow_ip)) {
    cmsCore::error404();
}

cmsCore::loadLanguage('admin/lang');
global $_LANG;

//-------CHECK AUTHENTICATION--------------------------------------//
if (!cmsCore::c('user')->is_admin && cmsAdmin::getApplet() != 'login') {
    cmsCore::redirect('/admin/index.php?view=login');
}

//--------LOAD ACCESS OPTIONS LIST---------------------------------//

$adminAccess = cmsUser::getAdminAccess();

//------------------------------------------------------------------//

cmsCore::c('user')->onlineStats();
cmsCore::c('page')->setTitle();

$GLOBALS['cp_page_title'] = '';
$GLOBALS['cp_page_head']  = array();

cpProceedBody();

cmsCore::c('page')->showTemplate();
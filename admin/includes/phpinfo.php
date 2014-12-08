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

session_start();

define("VALID_CMS", 1);
define("VALID_CMS_ADMIN", 1);

define('PATH', $_SERVER['DOCUMENT_ROOT']);

require(PATH .'/core/cms.php');
require(PATH .'/admin/includes/cp.php');

cmsCore::getInstance();
cmsCore::loadClass('user');

if (!cmsCore::c('user')->update()) { cmsCore::error404(); }

if(!cmsCore::checkAccessByIp(cmsCore::c('config')->allow_ip)) { cmsCore::error404(); }

if (!cmsCore::c('user')->is_admin){
    cmsCore::error404();
}

$adminAccess = cmsUser::getAdminAccess();

if (!cmsUser::isAdminCan('admin/config', $adminAccess)) { cpAccessDenied(); }

@phpinfo();
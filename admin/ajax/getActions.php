<?php

header('Content-Type: text/html; charset=utf-8');
header('X-Frame-Options: DENY');

session_start();

define("VALID_CMS", 1);
define("VALID_CMS_ADMIN", 1);

define('PATH', $_SERVER['DOCUMENT_ROOT']);

require(PATH .'/core/cms.php'); 
require(PATH .'/admin/includes/cp.php');

$inCore = cmsCore::getInstance(false, true);

cmsCore::loadClass('page');
cmsCore::loadClass('user');
cmsCore::loadClass('actions');

if (!cmsCore::c('user')->update() || !cmsCore::isAjax()) {
    cmsCore::error404();
}

if (!cmsCore::c('user')->is_admin) { cmsCore::halt($_LANG['ACCESS_DENIED']); }

cmsCore::c('actions')->showTargets(true);

$total = cmsCore::c('actions')->getCountActions();
$page = cmsCore::request('page', 'int', 1);

cmsCore::c('db')->limitPage($page, 10);

cmsCore::c('page')->initTemplate('components', 'actions_list')->
    assign('actions', cmsCore::c('actions')->getActionsLog())->
    assign('pagebar', cmsPage::getPagebar($total, $page, 10, '#" onclick="$.post(\'/admin/ajax/getActions.php\', \'page=%page%\', function(m){ $(\'#actions\').html(m); }); return false'))->
    display();
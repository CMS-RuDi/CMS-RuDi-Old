<?php

define('PATH', $_SERVER['DOCUMENT_ROOT']);
include(PATH.'/core/ajax/ajax_core.php');

if (!$inUser->is_admin) { cmsCore::halt(); }

cmsCore::c('actions')->showTargets(true);

$total = cmsCore::c('actions')->getCountActions();
$page = cmsCore::request('page', 'int', 1);

cmsCore::c('db')->limitPage($page, 10);

$actions = cmsCore::c('actions')->getActionsLog();

$pagebar = cmsPage::getPagebar($total, $page, 10, '#" onclick="$.post(\'/admin/ajax/getActions.php\', \'page=%page%\', function(m){ $(\'#actions\').html(m); }); return false');

$tpl_file = 'admin/actions.php';
$tpl_dir  = file_exists(TEMPLATE_DIR . $tpl_file) ? TEMPLATE_DIR : DEFAULT_TEMPLATE_DIR;

include($tpl_dir . $tpl_file);
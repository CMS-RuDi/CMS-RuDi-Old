<?php

define('PATH', $_SERVER['DOCUMENT_ROOT']);
include(PATH.'/core/ajax/ajax_core.php');

if (!$inUser->is_admin) { cmsCore::halt($_LANG['ACCESS_DENIED']); }

cmsCore::c('actions')->showTargets(true);

$total = cmsCore::c('actions')->getCountActions();
$page = cmsCore::request('page', 'int', 1);

cmsCore::c('db')->limitPage($page, 10);

$actions = cmsCore::c('actions')->getActionsLog();

$pagebar = cmsPage::getPagebar($total, $page, 10, '#" onclick="$.post(\'/admin/ajax/getActions.php\', \'page=%page%\', function(m){ $(\'#actions\').html(m); }); return false');

cmsCore::c('page')->initTemplate('components', 'actions_list')->
    assign('actions', $actions)->
    assign('pagebar', $pagebar)->
    display();
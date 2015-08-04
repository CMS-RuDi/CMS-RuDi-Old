<?php

define('PATH', $_SERVER['DOCUMENT_ROOT']);
include(PATH.'/core/ajax/ajax_core.php');

$tpl = cmsCore::request('tpl', 'str', '');
$target_id = cmsCore::request('target_id', 'int', 0);
$component = cmsCore::request('component', 'str', '');
$target    = cmsCore::request('target', 'str', '');

if (empty($target_id) || empty($component)) {
    cmsCore::halt();
}

cmsCore::callEvent('INSERT_SLIDER', array('tpl' => $tpl, 'target_id' => $target_id, 'component' => $component, 'target' => $target));
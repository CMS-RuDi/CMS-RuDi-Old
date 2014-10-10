<?php

define('PATH', $_SERVER['DOCUMENT_ROOT']);
include PATH .'/core/ajax/ajax_core.php';

//------------------------------------------------------------------------------
if (!cmsCore::m('sitemap')->config['html_map_enable']) {
    cmsCore::halt();
}

$components = cmsCore::m('sitemap')->getHtmlMapComponents();
if (empty($components)) { cmsCore::halt(); }
//==============================================================================

$id = cmsCore::request('id', 'str', '');
if (empty($id)) { cmsCore::halt(); }

$items = array();

if ($id == '#') {
    foreach ($components as $component) {
        $items[] = array( 'text' => $component['title'], 'children' => true, 'id' => $component['link'], 'icon' => 'folder', 'a_attr' => array( 'href' => '/'. $component['link'] ) );
    }
    
    $items = array( array( 'text' => 'Главная', 'children' => $items, 'id' => '/', 'state' => array( 'opened' => true, 'disabled' => true ) ) );
} else {
    if (mb_strstr($id, '|')) {
        $id = explode('|', $id);
        
        $page = $id[1];
        
        $id = $id[0];
    } else {
        $page = 1;
    }
    
    $id = explode('_', $id);
    
    if (!isset($components[$id[0]])) { cmsCore::halt(); }
    
    $sections = cmsCore::m('sitemap')->getComponentSections(
        $id[0],
        cmsCore::getArrVal($id, 2, 0),
        cmsCore::getArrVal($id, 1, ''),
        $page
    );
    
    $item_count = 0;
    
    if (!empty($sections)) {
        if (isset($sections['total'])) {
            $total = $sections['total'];
            unset($sections['total']);
        }
        
        foreach ($sections as $section) {
            if (isset($section['target_id'])) {
                $items[] = array( 'text' => $section['title'], 'children' => true, 'id' => $id[0] .'_'. $section['target'] .'_'. $section['target_id'], 'icon' => 'folder', 'a_attr' => array( 'href' => $section['link'] ) );
            } else {
                $items[] = array( 'text' => $section['title'], 'icon' => 'file', 'a_attr' => array( 'href' => $section['link'] ) );
                $item_count++;
            }
        }
        
        if ($page == 1 && $total > cmsCore::m('sitemap')->config['perpage']) {
            $itm = array();
            $total_pages = ceil($total/cmsCore::m('sitemap')->config['perpage']);
            for ($i=2; $i<=$total_pages; $i++) {
                $itm[] = array( 'text' => 'Страница №'. $i, 'children' => true, 'id' => implode('_', $id) .'|'. $i, 'icon' => 'folder' );
            }
            $items[] = array( 'text' => 'Показать еще...', 'children' => $itm, 'id' => implode('_', $id) .'|pages', 'icon' => 'folder' );
        }
    } else if ($page > 1) {
        $items[] = array( 'text' => '--показаны все материалы раздела--', 'id' => implode('_', $id) .'|'. $page, 'icon' => 'folder', 'state' => array( 'opened' => true, 'disabled' => true ) );
    }
}

cmsCore::jsonOutput($items);
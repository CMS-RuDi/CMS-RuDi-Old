<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function sitemap() {
    if (!cmsCore::m('sitemap')->config['html_map_enable']) {
        return false;
    }
    
    $components = cmsCore::m('sitemap')->getHtmlMapComponents();
    if (empty($components)) {
        return false;
    }
    //==========================================================================

    cmsCore::c('page')->addHeadJS('includes/jstree/jstree.min.js');
    cmsCore::c('page')->addHeadCSS('includes/jstree/themes/default/style.min.css');
    
    $do = cmsCore::getInstance()->do;
    
    $page = cmsCore::request('page', 'int', 1);
    $component = cmsCore::request('component', 'str', '');
    $target = cmsCore::request('target', 'str', '');
    $target_id = cmsCore::request('target_id', 'int', 0);

    cmsCore::c('page')->addPathway('Карта сайта', '/sitemap');
    
    if ($do == 'gen') {
        cmsCore::m('sitemap')->generateSitemaps();
    }
    
    if ($do == 'view') {
        cmsPage::initTemplate('components', 'com_sitemap')->
            assign('do', $do)->
            assign('components', $components)->
            display();
    }
    
    if ($do == 'view_component') {
        if (!isset($components[$component])) { return false; }
        
        cmsCore::c('page')->addPathway($components[$component]['title']);
        
        $sections = cmsCore::m('sitemap')->getComponentSections($component, 0, '', $page);
        if (isset($sections['total'])) {
            $total = $sections['total'];
            unset($sections['total']);
        }
        
        $components[$component]['sections'] = $sections;
        
        cmsPage::initTemplate('components', 'com_sitemap')->
            assign('do', $do)->
            assign('components', $components)->
            assign('pagebar', cmsPage::getPagebar($total, $page, cmsCore::m('sitemap')->config['perpage'], '/sitemap/'. $component .'.html?page=%page%'))->
            display();
    }
    
    if ($do == 'view_section') {
        if (!isset($components[$component])) { return false; }
        
        cmsCore::c('page')->addPathway($components[$component]['title'], '/sitemap/'. $components[$component]['link'] .'.html');
        
        $section = cmsCore::m('sitemap')->getComponentSection($component, $target_id, $target);
        if (!empty($section)) {
            $components[$component]['section'] = $section;
            cmsCore::c('page')->addPathway($section['title'], '/sitemap/'. $component .'_'. $section['target'] .'_'. $section['target_id'] .'.html');
        }
        
        if ($page > 1) {
            cmsCore::c('page')->addPathway('Страница №'. $page);
        }
        
        $sections = cmsCore::m('sitemap')->getComponentSections($component, $target_id, $target, $page);
        if (isset($sections['total'])) {
            $total = $sections['total'];
            unset($sections['total']);
        }
        
        $components[$component]['sections'] = $sections;
        
        cmsPage::initTemplate('components', 'com_sitemap')->
            assign('do', $do)->
            assign('components', $components)->
            assign('pagebar', cmsPage::getPagebar($total, $page, cmsCore::m('sitemap')->config['perpage'], '/sitemap/'. $component .'_'. $target .'_'. $target_id .'.html?page=%page%'))->
            display();
    }
}
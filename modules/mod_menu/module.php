<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.4                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

function mod_menu($module_id, $cfg) {
    $inCore      = cmsCore::getInstance();
    $menuid      = $inCore->menuId();
    $full_menu   = $inCore->getMenuStruct();

    $cfg = array_merge(array(
        'menu' => 'mainmenu',
        'show_home' => 1,
        'is_sub_menu' => 0,
        'tpl' => 'mod_menu'
    ), $cfg);
    
    // текущий пункт меню
    $currentmenu = isset($full_menu[$menuid]) ? $full_menu[$menuid] : array();

    // результирующий массив меню
    $items = array();

    // id корня меню если обычный вывод меню, $menuid если режим подменю
    if ($cfg['is_sub_menu']) {
        // в подменю не должно быть ссылки на главную
        $cfg['show_home'] = 0;
        
        // на главной или нет активного пункта меню
        if ($menuid == 1 || !$currentmenu) {
            return false;
        }
        
        foreach ($full_menu as $item) {
            if ($item['NSLeft'] > $currentmenu['NSLeft'] &&
                    $item['NSRight'] < $currentmenu['NSRight'] &&
                    in_array($cfg['menu'], $item['menu']) &&
                    ($item['is_lax'] || cmsCore::checkContentAccess($item['access_list'], false)) && $item['published']
            ){
                $item['link'] = cmsUser::stringReplaceUserProperties($item['link']);
                $item['title'] = cmsUser::stringReplaceUserProperties($item['title'], true);
                $items[] = $item;
                // массивы для сортировки
                $nsl[] = $item['NSLeft'];
                $ord[] = $item['ordering'];
            }
        }

    } else {
        foreach ($full_menu as $item) {
            if (in_array($cfg['menu'], $item['menu']) &&
                (($item['is_lax']) || cmsCore::checkContentAccess($item['access_list'], false)) && $item['published']
            ){
                $item['link'] = cmsUser::stringReplaceUserProperties($item['link']);
                $item['title'] = cmsUser::stringReplaceUserProperties($item['title'], true);
                $items[] = $item;
                // массивы для сортировки
                $nsl[] = $item['NSLeft'];
                $ord[] = $item['ordering'];
            }
        }
    }

    if (!$items) { return false; }

    // сортируем массив
    array_multisort($nsl, SORT_ASC, $ord, SORT_ASC, $items);

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('menuid', $menuid)->
        assign('currentmenu', $currentmenu)->
        assign('menu', $cfg['menu'])->
        assign('items', $items)->
        assign('last_level', 0)->
        assign('user_id', cmsCore::c('user')->id)->
        assign('is_admin', cmsCore::c('user')->is_admin)->
        assign('cfg', $cfg)->
        display();

    return true;
}
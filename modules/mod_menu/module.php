<?php
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

function mod_menu($module_id, $cfg){

    $inCore      = cmsCore::getInstance();
    $inUser      = cmsUser::getInstance();
    $menuid      = $inCore->menuId();
    $full_menu   = $inCore->getMenuStruct();

    if (!isset($cfg['menu'])) { $menu = 'mainmenu'; } else { $menu = $cfg['menu']; }
    if (!isset($cfg['show_home'])) { $cfg['show_home'] = 1; }
    if (!isset($cfg['is_sub_menu'])) { $cfg['is_sub_menu'] = 0; }

    // текущий пункт меню
    $currentmenu = $full_menu[$menuid];

    // результирующий массив меню
    $items = array();

    // id корня меню если обычный вывод меню, $menuid если режим подменю
    if($cfg['is_sub_menu']){

        // в подменю не должно быть ссылки на главную
        $cfg['show_home'] = 0;
        // на главной подменю не можт быть
        if($menuid == 1){
            return false;
        }
        foreach ($full_menu as $item) {
            if($item['NSLeft'] > $currentmenu['NSLeft'] &&
                    $item['NSRight'] < $currentmenu['NSRight'] &&
                    $item['menu'] == $menu &&
                    cmsCore::checkContentAccess($item['access_list']) && $item['published']){
                $items[] = $item;
                // массивы для сортировки
                $nsl[] = $item['NSLeft'];
                $ord[] = $item['ordering'];
            }
        }

    } else {

        foreach ($full_menu as $item) {
            if($item['menu'] == $menu &&
                    cmsCore::checkContentAccess($item['access_list']) && $item['published']){
                $items[] = $item;
                // массивы для сортировки
                $nsl[] = $item['NSLeft'];
                $ord[] = $item['ordering'];
            }
        }
    }

    if(!$items) { return false; }

    // сортируем массив
    array_multisort($nsl, SORT_ASC, $ord, SORT_ASC, $items);

    $template = ($cfg['tpl'] ? $cfg['tpl'] : 'mod_menu.tpl');

    cmsPage::initTemplate('modules', $template)->
            assign('menuid', $menuid)->
            assign('currentmenu', $currentmenu)->
            assign('menu', $menu)->
            assign('items', $items)->
            assign('last_level', 0)->
            assign('user_id', $inUser->id)->
            assign('is_admin', $inUser->is_admin)->
            assign('cfg', $cfg)->display($template);

    return true;

}

?>
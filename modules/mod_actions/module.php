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

function mod_actions($mod, $cfg) {
    global $_LANG;
    
    if (!isset($cfg['action_types'])) {
        echo $_LANG['MODULE_NOT_CONFIGURED'];
        return true;
    }
    
    $cfg = array_merge(
        array(
            'show_target' => 1,
            'limit' => 15,
            'show_link' => 1
        ),
        $cfg
    );
    
    if (!$cfg['show_target']) {
        cmsCore::c('actions')->showTargets(false);
    }

    cmsCore::c('actions')->onlySelectedTypes($cfg['action_types']);
    cmsCore::c('db')->limitIs($cfg['limit']);
    
    $actions = cmsCore::c('actions')->getActionsLog();
    if (!$actions) { return false; }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('actions', $actions)->
        assign('cfg', $cfg)->
        assign('user_id', cmsCore::c('user')->id)->
        display();

    return true;
}
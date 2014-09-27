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

if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function actions(){
    $inCore = cmsCore::getInstance();
    
    global $_LANG;

    $page    = cmsCore::request('page', 'int', 1);
    $user_id = cmsCore::request('user_id', 'int', 0);
    $perpage = 6;

    $pagetitle = $inCore->getComponentTitle();

    cmsCore::c('page')->setTitle($pagetitle);
    cmsCore::c('page')->addPathway($pagetitle, '/actions');

//======================================================================================================================//

    if ($inCore->do == 'delete'){
        if (!cmsCore::c('user')->is_admin) { cmsCore::error404(); }

        $id = cmsCore::request('id', 'int', 0);
        if (!$id) { cmsCore::error404(); }

        cmsCore::m('actions')->deleteAction($id);
        
        cmsCore::redirectBack();
    }

//======================================================================================================================//

    if ($inCore->do == 'view'){
        cmsCore::c('actions')->showTargets(cmsCore::m('actions')->config['show_target']);
        
        if(cmsCore::m('actions')->config['act_type'] && !cmsCore::m('actions')->config['is_all']){
            cmsCore::c('actions')->onlySelectedTypes(cmsCore::m('actions')->config['act_type']);
	}
        
        $total = cmsCore::c('actions')->getCountActions();

        cmsCore::c('db')->limitPage($page, cmsCore::m('actions')->config['perpage']);

        $actions = cmsCore::c('actions')->getActionsLog();
        if (!$actions && $page > 1) { cmsCore::error404(); }

        cmsPage::initTemplate('components', 'com_actions_view')->
            assign('actions', $actions)->
            assign('pagetitle', $pagetitle)->
            assign('total', $total)->
            assign('user_id', cmsCore::c('user')->id)->
            assign('pagebar', cmsPage::getPagebar($total, $page, cmsCore::m('actions')->config['perpage'], '/actions/page-%page%'))->
            display();
    }

//======================================================================================================================//

    if ($inCore->do == 'view_user_feed'){
        if (!cmsCore::c('user')->id) { cmsCore::error404(); }

        if (!cmsCore::isAjax()) { cmsCore::error404(); }

        // Получаем друзей
        $friends = cmsUser::getFriends(cmsCore::c('user')->id);

        $friends_total = count($friends);

        // нам нужно только определенное количество друзей
        $friends = array_slice($friends, ($page-1)*$perpage, $perpage, true);
        $actions  = array();
        
        if ($friends){
            cmsCore::c('actions')->onlyMyFriends();
            cmsCore::c('actions')->showTargets(cmsCore::m('actions')->config['show_target']);
            cmsCore::c('db')->limitIs(cmsCore::m('actions')->config['perpage_tab']);
            $actions = cmsCore::c('actions')->getActionsLog();
        }

        cmsPage::initTemplate('components', 'com_actions_view_tab')->
            assign('actions', $actions)->
            assign('friends', $friends)->
            assign('user_id', $user_id)->
            assign('page', $page)->
            assign('cfg', cmsCore::m('actions')->config)->
            assign('total_pages', ceil($friends_total / $perpage))->
            assign('friends_total', $friends_total)->
            display();
    }
//======================================================================================================================//
    if ($inCore->do == 'view_user_feed_only'){
        if(!cmsCore::c('user')->id) { cmsCore::error404(); }

        if(!cmsCore::isAjax()) { cmsCore::error404(); }

        if($user_id){
            if(!cmsUser::isFriend($user_id)) { cmsCore::error404(); }
            cmsCore::c('actions')->whereUserIs($user_id);
        } else {
            cmsCore::c('actions')->onlyMyFriends();
        }

        cmsCore::c('actions')->showTargets(cmsCore::m('actions')->config['show_target']);
        cmsCore::c('db')->limitIs(cmsCore::m('actions')->config['perpage_tab']);
        $actions = cmsCore::c('actions')->getActionsLog();
        // получаем последний элемент массива для выборки имя пользователя и ссылки на профиль.
        if ($actions) {
            $user = end($actions);
        } else {
            $user = cmsUser::getShortUserData($user_id);
        } 

        cmsPage::initTemplate('components', 'com_actions_tab')->
            assign('actions', $actions)->
            assign('user_id', $user_id)->
            assign('user', $user)->
            assign('cfg', cmsCore::m('actions')->config)->
            display();
    }
//======================================================================================================================//
    if ($inCore->do == 'view_user_friends_only'){
        if(!cmsCore::c('user')->id) { cmsCore::error404(); }

        if(!cmsCore::isAjax()) { cmsCore::error404(); }

        // Получаем друзей
        $friends = cmsUser::getFriends(cmsCore::c('user')->id);

        $friends_total = count($friends);

        // нам нужно только определенное количество друзей
        $friends = array_slice($friends, ($page-1)*$perpage, $perpage, true);

        cmsPage::initTemplate('components', 'com_actions_friends')->
            assign('friends', $friends)->
            assign('page', $page)->
            assign('user_id', $user_id)->
            assign('total_pages', ceil($friends_total / $perpage))->
            assign('friends_total', $friends_total)->
            display();
    }

}
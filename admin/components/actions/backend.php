<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
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
cmsCore::loadClass('actions');
$opt = cmsCore::request('opt', 'str', 'list');

$act_components = cmsActions::getActionsComponents();
$act_component  = cmsCore::request('act_component', 'str', '');

if ($opt != 'config') {
    cmsCore::c('page')->initTemplate('components', 'actions_filter_form')->
        assign('id', $id)->
        assign('act_components', $act_components)->
        assign('act_component', $act_component)->
        display();
}

if ($opt == 'list') {
    $page    = cmsCore::request('page', 'int', 1);
    $perpage = 15;

    cmsCore::c('actions')->showTargets(true);

    if ($act_component) {
        cmsCore::c('db')->where("a.component = '". $act_component ."'");
    }

    $total = cmsCore::c('actions')->getCountActions();

    cmsCore::c('db')->limitPage($page, $perpage);

    $actions = cmsCore::c('actions')->getActionsLog();

    $pagebar = cmsPage::getPagebar($total, $page, $perpage, '?view=components&do=config&id='. $id .'&opt=list&page=%page%');

    cmsCore::c('page')->initTemplate('components', 'actions_list')->
        assign('actions', $actions)->
        assign('pagebar', $pagebar)->
        display();
}

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array();

    $cfg['show_target'] = cmsCore::request('show_target', 'int', 1);
    $cfg['perpage']     = cmsCore::request('perpage', 'int', 10);
    $cfg['perpage_tab'] = cmsCore::request('perpage_tab', 'int', 15);
    $cfg['is_all']      = cmsCore::request('is_all', 'int', 0);
    $cfg['act_type']    = cmsCore::request('act_type', 'array_str', array());
    $cfg['meta_keys']   = cmsCore::request('meta_keys', 'str', '');
    $cfg['meta_desc']   = cmsCore::request('meta_desc', 'str', '');

    $inCore->saveComponentConfig('actions', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');

    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=config');
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS'], '?view=components&do=config&id='.$id.'&opt=config');

    echo '<h3>'. $_LANG['AD_SETTINGS'] .': '. $com['title'] .'</h3>';

    $sql = "SELECT * FROM cms_actions ORDER BY title LIMIT 100";

    $result = cmsCore::c('db')->query($sql);
    
    $act_lists = array();
    if (cmsCore::c('db')->num_rows($result)) {
        while($act = cmsCore::c('db')->fetch_assoc($result)) {
            $act_lists[] = $act;
        }
    }
    
    cmsCore::c('page')->initTemplate('components', 'actions_config')->
        assign('id', $id)->
        assign('cfg', cmsCore::m('actions')->config)->
        display();
}
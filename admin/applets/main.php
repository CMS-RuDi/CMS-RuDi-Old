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

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function newContent($table, $where='') {
    if ($where) { $where = ' AND '. $where; }
    $new = cmsCore::c('db')->get_field($table, "DATE_FORMAT(pubdate, '%d-%m-%Y') = DATE_FORMAT(NOW(), '%d-%m-%Y')". $where, 'COUNT(id)');
    return $new;
}

function applet_main() {
    $inCore = cmsCore::getInstance();
    
    global $_LANG;
    
    cmsCore::c('page')->setTitle($_LANG['PATH_HOME']);
    
    $new = array();

    $tpl = cmsCore::c('page')->initTemplate('applets', 'main')->
            assign('users_count', cmsCore::c('db')->rows_count('cms_users', 'is_deleted=0'))->
            assign('today_users_count', (int)cmsCore::c('db')->get_field('cms_users', "DATE_FORMAT(regdate, '%d-%m-%Y') = DATE_FORMAT(NOW(), '%d-%m-%Y') AND is_deleted = 0", 'COUNT(id)'))->
            assign('week_users_count', (int)cmsCore::c('db')->get_field('cms_users', "regdate >= DATE_SUB(NOW(), INTERVAL 7 DAY)", 'COUNT(id)'))->
            assign('month_users_count', (int)cmsCore::c('db')->get_field('cms_users', "regdate >= DATE_SUB(NOW(), INTERVAL 1 MONTH)", 'COUNT(id)'))->
            assign('people', cmsUser::getOnlineCount());
    
    if ($inCore->isComponentEnable('content')) {
        $tpl->assign('content_enable', true);
        $new['content'] = (int)newContent('cms_content');
    }
    
    if ($inCore->isComponentEnable('photos')) {
        $tpl->assign('photos_enable', true);
        $new['photos'] = (int)newContent('cms_photo_files');
    }
    
    if ($inCore->isComponentEnable('video')) {
        $tpl->assign('video_enable', true);
        $new['video'] = (int)newContent('cms_video_movie');
    }
    
    if ($inCore->isComponentEnable('maps')) {
        $tpl->assign('maps_enable', true);
        $new['maps'] = (int)newContent('cms_map_items');
    }
    
    if ($inCore->isComponentEnable('faq')) {
        $tpl->assign('faq_enable', true);
        $new['faq'] = (int)newContent('cms_faq_quests');
    }
    
    if ($inCore->isComponentEnable('board')) {
        $tpl->assign('board_enable', true);
        $new['board'] = (int)newContent('cms_board_items');
    }
    
    if ($inCore->isComponentEnable('catalog')) {
        $tpl->assign('catalog_enable', true);
        $new['catalog'] = (int)newContent('cms_uc_items');
    }
    
    if ($inCore->isComponentEnable('forum')) {
        $tpl->assign('forum_enable', true);
        $new['forum'] = (int)newContent('cms_forum_posts');
    }
    
    cmsCore::c('actions')->showTargets(true);
    $total = cmsCore::c('actions')->getCountActions();
    cmsCore::c('db')->limitPage(1, 10);
    
    $actions_html = cmsCore::c('page')->initTemplate('components', 'actions_list')->
        assign('actions', cmsCore::c('actions')->getActionsLog())->
        assign('pagebar', cmsPage::getPagebar($total, 1, 10, '#" onclick="$.post(\'/admin/ajax/getActions.php\', \'page=%page%\', function(m){ $(\'#actions\').html(m); }); return false'))->
        fetch();
    
    $tpl->assign('new', $new)->
        assign('rssfeed_installed', $inCore->isComponentInstalled('rssfeed'))->
        assign('actions_html', $actions_html)->
        assign('new_quests', cmsCore::c('db')->rows_count('cms_faq_quests', 'published = 0'))->
        assign('new_content', cmsCore::c('db')->rows_count('cms_content', 'published = 0 AND is_arhive = 0'))->
        assign('new_catalog', cmsCore::c('db')->rows_count('cms_uc_items', 'on_moderate = 1'))->
        display();
}
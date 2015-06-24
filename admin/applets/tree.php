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

function applet_tree() {
    $inCore = cmsCore::getInstance();
    
    cmsCore::loadLib('tags');

    global $_LANG;
    global $adminAccess;
    
    if (!cmsUser::isAdminCan('admin/content', $adminAccess)) { cpAccessDenied(); }

    cmsCore::c('page')->setTitle($_LANG['AD_ARTICLES']);
    cpAddPathway($_LANG['AD_ARTICLES'], 'index.php?view=tree');

    cmsCore::c('page')->addHeadJS('admin/js/content.js');
    
    echo '<script>';
    echo cmsPage::getLangJS('AD_NO_SELECTED_ARTICLES');
    echo cmsPage::getLangJS('AD_DELETE_SELECTED_ARTICLES');
    echo cmsPage::getLangJS('AD_PIECES');
    echo cmsPage::getLangJS('AD_CATEGORY_DELETE');
    echo cmsPage::getLangJS('AD_AND_SUB_CATS');
    echo cmsPage::getLangJS('AD_DELETE_SUB_ARTICLES');
    echo '</script>';

    $do = cmsCore::request('do', 'str', 'tree');

    if ($do == 'tree') {
        $toolmenu = array(
            array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETUP_CATEGORY'], 'link' => '?view=components&do=config&link=content' ),
            array( 'icon' => 'help.gif', 'title' => $_LANG['AD_HELP'], 'link' => '?view=components&do=config&link=content' )
        );

        cpToolMenu($toolmenu);

        $only_hidden = cmsCore::request('only_hidden', 'int', 0);
        $category_id = cmsCore::request('cat_id', 'int', 0);
        $base_uri    = 'index.php?view=tree';

        $title_part  = cmsCore::request('title', 'str', '');

        $def_order   = $category_id ? 'con.ordering' : 'pubdate';
        $orderby     = cmsCore::request('orderby', 'str', $def_order);
        $orderto     = cmsCore::request('orderto', 'str', 'asc');
        $page        = cmsCore::request('page', 'int', 1);
        $perpage     = 20;

        if ($category_id) {
            cmsCore::m('content')->whereCatIs($category_id);
        }

        if ($title_part) {
            cmsCore::c('db')->where('LOWER(con.title) LIKE \'%'.mb_strtolower($title_part).'%\'');
        }

        if ($only_hidden) {
            cmsCore::c('db')->where('con.published = 0');
        }

        cmsCore::c('db')->orderBy($orderby, $orderto);
        cmsCore::c('db')->limitPage($page, $perpage);

        $total = cmsCore::m('content')->getArticlesCount(false);
        
        cmsCore::c('page')->initTemplate('applets', 'tree')->
            assign('hide_cats', cmsCore::request('hide_cats', 'int', 0))->
            assign('only_hidden', $only_hidden)->
            assign('base_uri', $base_uri)->
            assign('category_id', $category_id)->
            assign('cats', cmsCore::m('content')->getCatsTree())->
            assign('orderto', $orderto)->
            assign('orderby', $orderby)->
            assign('title_part', $title_part)->
            assign('category_opt', $inCore->getListItemsNS('cms_category', $category_id))->
            assign('page', $page)->
            assign('total', $total)->
            assign('perpage', $perpage)->
            assign('pages', ceil($total / $perpage))->
            assign('items', cmsCore::m('content')->getArticlesList(false))->
            display();
    }
}
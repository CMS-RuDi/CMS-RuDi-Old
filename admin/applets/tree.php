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

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_tree(){

    $inCore = cmsCore::getInstance();

	cmsCore::loadLib('tags');

    global $_LANG;
    global $adminAccess;
	if (!cmsUser::isAdminCan('admin/content', $adminAccess)) { cpAccessDenied(); }

    $cfg = $inCore->loadComponentConfig('content');

    cmsCore::loadModel('content');
    $model = new cms_model_content();

    cmsCore::c('page')->setAdminTitle($_LANG['AD_ARTICLES']);
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

//============================================================================//
//============================================================================//

	if ($do == 'tree'){

        $toolmenu[] = array('icon'=>'config.gif', 'title'=>$_LANG['AD_SETUP_CATEGORY'], 'link'=>'?view=components&do=config&link=content');
        $toolmenu[] = array('icon'=>'help.gif', 'title'=>$_LANG['AD_HELP'], 'link'=>'?view=components&do=config&link=content');

		cpToolMenu($toolmenu);

        $only_hidden    = cmsCore::request('only_hidden', 'int', 0);
        $category_id    = cmsCore::request('cat_id', 'int', 0);
        $base_uri       = 'index.php?view=tree';

        $title_part     = cmsCore::request('title', 'str', '');

        $def_order  = $category_id ? 'con.ordering' : 'pubdate';
        $orderby    = cmsCore::request('orderby', 'str', $def_order);
        $orderto    = cmsCore::request('orderto', 'str', 'asc');
        $page       = cmsCore::request('page', 'int', 1);
        $perpage    = 20;

        $hide_cats  = cmsCore::request('hide_cats', 'int', 0);

        $cats       = $model->getCatsTree();

        if ($category_id) {
            $model->whereCatIs($category_id);
        }

        if ($title_part){
            cmsCore::c('db')->where('LOWER(con.title) LIKE \'%'.mb_strtolower($title_part).'%\'');
        }

        if ($only_hidden){
            cmsCore::c('db')->where('con.published = 0');
        }

        cmsCore::c('db')->orderBy($orderby, $orderto);

        cmsCore::c('db')->limitPage($page, $perpage);

        $total = $model->getArticlesCount(false);

        $items = $model->getArticlesList(false);

        $pages = ceil($total / $perpage);


        $tpl_file   = 'admin/content.php';
        $tpl_dir    = file_exists(TEMPLATE_DIR.$tpl_file) ? TEMPLATE_DIR : DEFAULT_TEMPLATE_DIR;

        include($tpl_dir.$tpl_file);

	}

} ?>

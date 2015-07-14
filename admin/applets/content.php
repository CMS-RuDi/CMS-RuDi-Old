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

function createMenuItem($menu, $id, $title) {
    $inCore = cmsCore::getInstance();
    $rootid = cmsCore::c('db')->get_field('cms_menu', 'parent_id=0', 'id');
    $ns     = $inCore->nestedSetsInit('cms_menu');

    cmsCore::c('db')->update('cms_menu', array(
        'menu' => $menu,
        'title' => $title,
        'link' => $inCore->getMenuLink('content', $id),
        'linktype' => 'content',
        'linkid' => $id,
        'target' => '_self',
        'published' => 1,
        'template' => 0,
        'access_list' => '',
        'iconurl' => ''
    ), $ns->AddNode($rootid));

    return true;
}

function applet_content() {
    $inCore = cmsCore::getInstance();
    cmsCore::m('content');
    
    global $_LANG;

    //check access
    global $adminAccess;
    if (!cmsUser::isAdminCan('admin/content', $adminAccess)) { cpAccessDenied(); }

    $cfg = $inCore->loadComponentConfig('content');

    cmsCore::c('page')->setTitle($_LANG['AD_ARTICLES']);
    cpAddPathway($_LANG['AD_ARTICLES'], 'index.php?view=tree');

    $do = cmsCore::request('do', 'str', 'add');
    $id = cmsCore::request('id', 'int', -1);

    if ($do == 'arhive_on') {
        cmsCore::c('db')->setFlag('cms_content', $id, 'is_arhive', '1');
        cmsCore::addSessionMessage($_LANG['AD_ARTICLES_TO_ARHIVE'], 'success');
        cmsCore::redirectBack();
    }

    if ($do == 'move') {
        $item_id = cmsCore::request('id', 'int', 0);
        $cat_id  = cmsCore::request('cat_id', 'int', 0);

        $dir     = cmsCore::request('dir', 'str');
        $step    = 1;

        cmsCore::m('content')->moveItem($item_id, $cat_id, $dir, $step);
        cmsCore::halt(1);
    }

    if ($do == 'move_to_cat') {
        $items     = cmsCore::request('item', 'array_int');
        $to_cat_id = cmsCore::request('obj_id', 'int', 0);

        if ($items && $to_cat_id) {
            $last_ordering = (int)cmsCore::c('db')->get_field('cms_content', "category_id = '". $to_cat_id ."' ORDER BY ordering DESC", 'ordering');
            foreach ($items as $item_id) {
                $article = cmsCore::m('content')->getArticle($item_id);
                if (!$article) { continue; }
                $last_ordering++;
                
                cmsCore::m('content')->updateArticle(
                    $article['id'],
                    array(
                        'category_id' => $to_cat_id,
                        'ordering' => $last_ordering,
                        'url' => $article['url'],
                        'title' => cmsCore::c('db')->escape_string($article['title']),
                        'id' => $article['id'],
                        'user_id' => $article['user_id']
                    )
                );
            }
            
            cmsCore::addSessionMessage($_LANG['AD_ARTICLES_TO'], 'success');
        }

        cmsCore::redirect('?view=tree&cat_id='. $to_cat_id);
    }

    if ($do == 'show') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) { cmsCore::c('db')->setFlag('cms_content', $id, 'published', '1'); }
            cmsCore::halt('1');
        } else {
            cmsCore::c('db')->setFlags('cms_content', cmsCore::request('item', 'array_int'), 'published', '1');
            cmsCore::redirectBack();
        }
    }

    if ($do == 'hide') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) { cmsCore::c('db')->setFlag('cms_content', $id, 'published', '0'); }
            cmsCore::halt('1');
        } else {
            cmsCore::c('db')->setFlags('cms_content', cmsCore::request('item', 'array_int'), 'published', '0');
            cmsCore::redirectBack();
        }
    }

    if ($do == 'delete') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) {
                cmsCore::m('content')->deleteArticle($id);
                cmsCore::addSessionMessage($_LANG['AD_ARTICLE_REMOVE'], 'success');
            }
        } else {
            cmsCore::m('content')->deleteArticles(cmsCore::request('item', 'array_int'));
            cmsCore::addSessionMessage($_LANG['AD_ARTICLES_REMOVE'], 'success');
        }
        cmsCore::redirectBack();
    }

    if ($do == 'submit') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        $article = array();
        
        $article['category_id'] = cmsCore::request('category_id', 'int', 1);
        $article['categories']  = cmsCore::request('categories', 'array_int', array());
        $article['categories'][] = $article['category_id'];
        $article['categories']  = implode(',', $article['categories']);
        $article['title']       = cmsCore::request('title', 'str');
        $article['url']         = cmsCore::request('url', 'str');
        $article['showtitle']   = cmsCore::request('showtitle', 'int', 0);
        $article['description'] = cmsCore::request('description', 'html', '');
        $article['description'] = cmsCore::c('db')->escape_string($article['description']);
        $article['content']     = cmsCore::request('content', 'html', '');
        $article['content']     = cmsCore::c('db')->escape_string($article['content']);
        $article['published']   = cmsCore::request('published', 'int', 0);

        $article['showdate']    = cmsCore::request('showdate', 'int', 0);
        $article['showlatest']  = cmsCore::request('showlatest', 'int', 0);
        $article['showpath']    = cmsCore::request('showpath', 'int', 0);
        $article['comments']    = cmsCore::request('comments', 'int', 0);
        $article['canrate']     = cmsCore::request('canrate', 'int', 0);

        $enddate                = explode('.', cmsCore::request('enddate', 'str'));
        $article['enddate']     = $enddate[2] .'-'. $enddate[1] .'-'. $enddate[0];
        $article['is_end']      = cmsCore::request('is_end', 'int', 0);
        $article['pagetitle']   = cmsCore::request('pagetitle', 'str', '');

        $article['tags']        = cmsCore::request('tags', 'str');

        $olddate                = cmsCore::request('olddate', 'str', '');
        $pubdate                = cmsCore::request('pubdate', 'str', '');
        
        $article['user_id']     = cmsCore::request('user_id', 'int', cmsCore::c('user')->id);
        $article['tpl']         = cmsCore::request('tpl', 'str', 'com_content_read');
        
        $autokeys = cmsCore::request('autokeys', 'int');
        
        if (cmsCore::inRequest('id')) {
            $article['id'] = cmsCore::request('id', 'int', 0);

            if ($olddate != $pubdate) {
                $date = explode('.', $pubdate);
                $article['pubdate'] = $date[2] .'-'. $date[1] .'-'. $date[0] .' '.  date('H:i');
            }
        } else {
            $date = explode('.', $pubdate);
            $article['pubdate'] = $date[2] .'-'. $date[1] .'-'. $date[0] .' '. date('H:i');
        }
        
        switch ($autokeys) {
            case 1: $article['meta_keys'] = $inCore->getKeywords($article['content']);
                    $article['meta_desc'] = $article['title'];
                    break;

            case 2: $article['meta_desc'] = strip_tags($article['description']);
                    $article['meta_keys'] = $article['tags'];
                    break;

            case 3: $article['meta_desc'] = cmsCore::request('meta_desc', 'str');
                    $article['meta_keys'] = cmsCore::request('meta_keys', 'str');
                    break;
        }
        
        if (!empty($article['id'])) {
            cmsCore::m('content')->updateArticle($article['id'], $article);
            
            cmsCore::addSessionMessage($_LANG['AD_ARTICLE_SAVE'], 'success');
        } else {
            $article['id'] = cmsCore::m('content')->addArticle($article);
            
            $inmenu = cmsCore::request('createmenu', 'str', '');

            if ($inmenu) {
                createMenuItem($inmenu, $article['id'], $article['title']);
            }
            
            cmsCore::addSessionMessage($_LANG['AD_ARTICLE_ADD'], 'success');
        }
        
        cmsCore::c('db')->delete('cms_content_fields', 'article_id='. $article['id'], 1);
        $fields = cmsCore::request('fields', 'array', array());
        if (!empty($fields)) {
            foreach ($fields as $key => $val) {
                $fields[$key] = cmsCore::c('db')->escape_string($val);
            }
            $fields['cat_id'] = $article['category_id'];
            $fields['article_id'] = $article['id'];
            cmsCore::c('db')->insert('cms_content_fields', $fields);
        }

        if (!cmsCore::request('is_public', 'int', 0)) {
            $showfor = cmsCore::request('showfor', 'array_int', array());
            cmsCore::setAccess($article['id'], $showfor, 'material');
        } else {
            cmsCore::clearAccess($article['id'], 'material');
        }

        cmsCore::m('content')->uploadArticeImage($article['id'], cmsCore::request('delete_image', 'int', 0));

        if (!isset($_SESSION['editlist']) || count($_SESSION['editlist']) == 0) {
            cmsCore::redirect('?view=tree&cat_id='. $article['category_id']);
        } else {
            cmsCore::redirect('?view=content&do=edit');
        }
    }
    
    if ($do == 'get_cat_fields') {
        ob_end_clean();
        
        $cat_id = cmsCore::request('cat_id', 'int', 0, 'post');
        $article_id = cmsCore::request('article_id', 'int', 0, 'post');
        
        $cat = cmsCore::c('db')->get_fields('cms_category', 'id='. $cat_id, '*');
        
        if (empty($cat) || $cat_id == 1 || !cmsCore::isAjax()) {
            cmsCore::halt();
        }
        
        if (!empty($cat['fields'])) {
            $cat['fields'] = json_decode($cat['fields'], true);
        }
        
        if (!empty($cat['fields'])) {
            cmsCore::c('page')->initTemplate('applets', 'content_fields')->
                assign('fields', $cat['fields'])->
                assign('values', cmsCore::c('db')->get_fields('cms_content_fields', 'article_id='.$article_id, '*'))->
                display();
        }
        
        cmsCore::halt();
    }

    if ($do == 'add' || $do == 'edit') {
        if ($do == 'add') {
            echo '<h3>'. $_LANG['AD_CREATE_ARTICLE'] .'</h3>';
            cpAddPathway($_LANG['AD_CREATE_ARTICLE'], 'index.php?view=content&do=add');
            
            $mod = array(
                'category_id' => cmsCore::request('to', 'int'),
                'showpath' => 1,
                'tpl' => 'com_content_read'
            );
        } else {
            if (isset($_REQUEST['item'])){
                $_SESSION['editlist'] = $_REQUEST['item'];
            }

            $ostatok = '';

            if (isset($_SESSION['editlist'])) {
                $id = array_shift($_SESSION['editlist']);
                if (count($_SESSION['editlist'])==0) {
                    unset($_SESSION['editlist']);
                } else {
                    $ostatok = '('. $_LANG['AD_NEXT_IN'] . count($_SESSION['editlist']) .')';
                }
            } else {
                $id = (int)$_REQUEST['id'];
            }
            
            $mod = cmsCore::m('content')->getArticle($id);
            if (empty($mod)) { cmsCore::error404(); }
            
            $dt = new DateTime($mod['pubdate']);
            $mod['pubdate'] = $dt->format('d.m.Y');
            
            $dt = new DateTime($mod['enddate']);
            $mod['enddate'] = $dt->format('d.m.Y');

            echo '<h3>'. $_LANG['AD_EDIT_ARTICLE'] . $ostatok .'</h3>';
            cpAddPathway($mod['title'], 'index.php?view=content&do=edit&id='. $mod['id']);
        }
        
        $ajaxUploader = cmsCore::c('page')->initAjaxUpload(
            'plupload',
            array(
                'component' => 'content',
                'target_id' => cmsCore::getArrVal($mod, 'id', 0),
                'insertEditor' => 'content'
            ),
            cmsCore::getArrVal($mod, 'images', false)
        );
        
        $sql    = "SELECT * FROM cms_user_groups";
        $result = cmsCore::c('db')->query($sql) ;

        $group_style  = 'disabled="disabled"';
        $group_public = 'checked="checked"';

        if ($do == 'edit') {
            $sql2 = "SELECT * FROM cms_content_access WHERE content_id = ". $mod['id'] ." AND content_type = 'material'";
            $result2 = cmsCore::c('db')->query($sql2);
            $ord = array();

            if (cmsCore::c('db')->num_rows($result2)){
                $group_public = '';
                $group_style = '';
                while ($r = cmsCore::c('db')->fetch_assoc($result2)){
                    $ord[] = $r['group_id'];
                }
            }
        }

        $user_groups = array();
        if (cmsCore::c('db')->num_rows($result)) {
            while ($item = cmsCore::c('db')->fetch_assoc($result)) {
                $group = array(
                    'title' => $item['title'],
                    'value' => $item['id']
                );

                if ($do == 'edit' && in_array($item['id'], $ord)) {
                    $group['selected'] = 'selected';
                }

                $user_groups[] = $group;
            }
        }
        
        cmsCore::c('page')->initTemplate('applets', 'content_edit')->
            assign('do', $do)->
            assign('cfg', $cfg)->
            assign('tags', isset($mod['id']) ? cmsTagLine('content', $mod['id'], false) : '')->
            assign('tab_plugins', cmsCore::callTabEventPlugins('ADMIN_CONTENT_TABS', !empty($mod['id']) ? $mod : array()))->
            assign('cats_opt', $inCore->getListItemsNS('cms_category', cmsCore::getArrVal($mod, 'category_id', array())))->
            assign('multi_cats_opt', $inCore->getListItemsNS('cms_category', cmsCore::getArrVal($mod, 'categories', array())))->
            assign('users_opt', $inCore->getListItems('cms_users', cmsCore::getArrVal($mod, 'user_id', cmsCore::c('user')->id), 'nickname', 'ASC', 'is_deleted=0 AND is_locked=0', 'id', 'nickname'))->
            assign('menu_list', cpGetList('menu'))->
            assign('user_groups', $user_groups)->
            assign('group_public', $group_public)->
            assign('group_style', $group_style)->
            assign('ajaxUploader', $ajaxUploader)->
            assign('mod', $mod)->
            display();
    }
}
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
    $ns = $inCore->nestedSetsInit('cms_menu');
    
    cmsCore::c('db')->update(
        'cms_menu',
        array(
            'menu' => $menu,
            'title' => $title,
            'link' => $inCore->getMenuLink('category', $id),
            'linktype' => 'category',
            'linkid' => $id,
            'target' => '_self',
            'published' => '1',
            'template' => '0',
            'access_list' => '',
            'iconurl' => ''
        ),
        $ns->AddNode(cmsCore::c('db')->getNsRootCatId('cms_menu'))
    );

    return true;
}

function applet_cats() {
    $inCore = cmsCore::getInstance();

    global $_LANG;

    cmsCore::c('page')->setTitle($_LANG['AD_ARTICLES']);
    cpAddPathway($_LANG['AD_ARTICLES'], 'index.php?view=tree');

    $do = cmsCore::request('do', 'str', 'add');
    $id = cmsCore::request('id', 'int', -1);

    define('IS_BILLING', $inCore->isComponentInstalled('billing'));
    if (IS_BILLING) { cmsCore::loadClass('billing'); }

    if ($do == 'delete') {
        $is_with_content = cmsCore::inRequest('content');
        cmsCore::m('content')->deleteCategory($id, $is_with_content);
        
        cmsCore::addSessionMessage(($is_with_content ? $_LANG['AD_CATEGORY_REMOVED'] : $_LANG['AD_CATEGORY_REMOVED_NOT_ARTICLE']), 'success');
        cmsCore::redirect('?view=tree');
    }

    if ($do == 'update') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        if (cmsCore::inRequest('id')) {
            $category['id']          = cmsCore::request('id', 'int', 0);
            $category['title']       = cmsCore::request('title', 'str', $_LANG['AD_SECTION_UNTITLED']);
            $category['parent_id']   = cmsCore::request('parent_id', 'int');
            $category['description'] = cmsCore::request('description', 'html', '');
            $category['description'] = cmsCore::c('db')->escape_string($category['description']);
            $category['published']   = cmsCore::request('published', 'int', 0);
            $category['showdate']    = cmsCore::request('showdate', 'int', 0);
            $category['showcomm']    = cmsCore::request('showcomm', 'int', 0);
            $category['orderby']     = cmsCore::request('orderby', 'str', '');
            $category['orderto']     = cmsCore::request('orderto', 'str', '');
            $category['modgrp_id']   = cmsCore::request('modgrp_id', 'int', 0);
            $category['maxcols']     = cmsCore::request('maxcols', 'int', 0);
            $category['showtags']    = cmsCore::request('showtags', 'int', 0);
            $category['showrss']     = cmsCore::request('showrss', 'int', 0);
            $category['showdesc']    = cmsCore::request('showdesc', 'int', 0);
            $category['is_public']   = cmsCore::request('is_public', 'int', 0);
            $category['url']         = cmsCore::request('url', 'str', '');
            $category['pagetitle']   = cmsCore::request('pagetitle', 'str', '');
            $category['meta_desc']   = cmsCore::request('meta_desc', 'str', '');
            $category['meta_keys']   = cmsCore::request('meta_keys', 'str', '');
            
            if (!empty($category['url'])) {
                $category['url'] = cmsCore::strToURL($category['url'], cmsCore::m('content')->config['is_url_cyrillic']);
            }
            $category['tpl']         = cmsCore::request('tpl', 'str', 'com_content_view');
            
            $category['cost']        = cmsCore::request('cost', 'str', '');
            if (!is_numeric($category['cost'])) { $category['cost'] = ''; }

            $album = array();
            $album['id']      = cmsCore::request('album_id', 'int', 0);
            $album['header']  = cmsCore::request('album_header', 'str', '');
            $album['orderby'] = cmsCore::request('album_orderby', 'str', '');
            $album['orderto'] = cmsCore::request('album_orderto', 'str', '');
            $album['maxcols'] = cmsCore::request('album_maxcols', 'int', 0);
            $album['max']     = cmsCore::request('album_max', 'int', 0);

            if ($album['id']) {
                $category['photoalbum'] = serialize($album);
            } else {
                $category['photoalbum'] = '';
            }

            // получаем старую категорию
            $old = cmsCore::c('db')->get_fields('cms_category', "id='". $category['id'] ."'", '*');
            if (!$old) { cmsCore::error404(); } 
            
            // если сменили категорию
            if ($old['parent_id'] != $category['parent_id']) {
                // перемещаем ее в дереве
                $inCore->nestedSetsInit('cms_category')->MoveNode($category['id'], $category['parent_id']);
                // обновляем сеолинки категорий
                cmsCore::c('db')->updateNsCategorySeoLink('cms_category', $category['id'], cmsCore::m('content')->config['is_url_cyrillic']);
                // Обновляем ссылки меню на категории
                cmsCore::m('content')->updateCatMenu();
                // обновляем сеолинки всех вложенных статей
                cmsCore::m('content')->updateArticlesSeoLink($category['id']);
                cmsCore::addSessionMessage($_LANG['AD_CATEGORY_NEW_URL'], 'info');
            }

            cmsCore::c('db')->update('cms_category', $category, $category['id']);

            // если пришел запрос на обновление ссылок
            // и категория не менялась - если менялась, мы выше все обновили
            if (cmsCore::inRequest('update_seolink') && ($old['parent_id'] == $category['parent_id'])) {
                // обновляем сеолинки категорий
                cmsCore::c('db')->updateNsCategorySeoLink('cms_category', $category['id'], cmsCore::m('content')->config['is_url_cyrillic']);
                // Обновляем ссылки меню на категории
                cmsCore::m('content')->updateCatMenu();
                // обновляем сеолинки всех вложенных статей
                cmsCore::m('content')->updateArticlesSeoLink($category['id']);
                cmsCore::addSessionMessage($_LANG['AD_SECTION_AND_ARTICLES_NEW_URL'], 'info');
            }

            if (!cmsCore::request('is_access', 'int', 0)) {
                $showfor = cmsCore::request('showfor', 'array_int');
                cmsCore::setAccess($category['id'], $showfor, 'category');
            } else {
                cmsCore::clearAccess($category['id'], 'category');
            }

            cmsCore::addSessionMessage($_LANG['AD_CATEGORY_SAVED'], 'success');

            if (!isset($_SESSION['editlist']) || @sizeof($_SESSION['editlist']) == 0) {
                cmsCore::redirect('?view=tree&cat_id='. $category['id']);
            } else {
                cmsCore::redirect('?view=tree');
            }
        }
    }

    if ($do == 'submit') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $category['title']       = cmsCore::request('title', 'str', $_LANG['AD_CATEGORY_UNTITLED']);
        $category['url']         = cmsCore::request('url', 'str', '');
        if (!empty($category['url'])) {
            $category['url'] = cmsCore::strToURL($category['url']);
        }
        $category['parent_id']   = cmsCore::request('parent_id', 'int');
        $category['description'] = cmsCore::request('description', 'html', '');
        $category['description'] = cmsCore::c('db')->escape_string($category['description']);
        $category['published']   = cmsCore::request('published', 'int', 0);
        $category['showdate']    = cmsCore::request('showdate', 'int', 0);
        $category['showcomm']    = cmsCore::request('showcomm', 'int', 0);
        $category['orderby']     = cmsCore::request('orderby', 'str', '');
        $category['orderto']     = cmsCore::request('orderto', 'str', '');
        $category['modgrp_id']   = cmsCore::request('modgrp_id', 'int', 0);
        $category['maxcols']     = cmsCore::request('maxcols', 'int', 0);
        $category['showtags']    = cmsCore::request('showtags', 'int', 0);
        $category['showrss']     = cmsCore::request('showrss', 'int', 0);
        $category['showdesc']    = cmsCore::request('showdesc', 'int', 0);
        $category['is_public']   = cmsCore::request('is_public', 'int', 0);
        $category['tpl']         = cmsCore::request('tpl', 'str', 'com_content_view');
        $category['pagetitle']   = cmsCore::request('pagetitle', 'str', '');
        $category['meta_desc']   = cmsCore::request('meta_desc', 'str', '');
        $category['meta_keys']   = cmsCore::request('meta_keys', 'str', '');

        $category['cost']        = cmsCore::request('cost', 'str', 0);
        if (!is_numeric($category['cost'])) { $category['cost'] = ''; }

        $album = array();
        $album['id']      = cmsCore::request('album_id', 'int', 0);
        $album['header']  = cmsCore::request('album_header', 'str', '');
        $album['orderby'] = cmsCore::request('album_orderby', 'str', '');
        $album['orderto'] = cmsCore::request('album_orderto', 'str', '');
        $album['maxcols'] = cmsCore::request('album_maxcols', 'int', 0);
        $album['max']     = cmsCore::request('album_max', 'int', 0);

        if ($album['id']) {
            $category['photoalbum'] = serialize($album);
        } else {
            $category['photoalbum'] = '';
        }

        $ns = $inCore->nestedSetsInit('cms_category');
        $category['id'] = $ns->AddNode($category['parent_id']);

        $category['seolink'] = cmsCore::generateCatSeoLink($category, 'cms_category', cmsCore::m('content')->config['is_url_cyrillic']);

        if ($category['id']) {
            cmsCore::c('db')->update('cms_category', $category, $category['id']);

            if (!cmsCore::request('is_access', 'int', 0)) {
                $showfor = cmsCore::request('showfor', 'array_int');
                cmsCore::setAccess($category['id'], $showfor, 'category');
            } else {
                cmsCore::clearAccess($category['id'], 'category');
            }
        }

        $inmenu = cmsCore::request('createmenu', 'str', '');

        if ($inmenu) {
            createMenuItem($inmenu, $category['id'], $category['title']);
        }

        cmsCore::addSessionMessage($_LANG['AD_CATEGORY_ADD'], 'success');

        cmsCore::redirect('?view=tree');
    }

    if ($do == 'add' || $do == 'edit') {
        $toolmenu = array(
            array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' ),
            array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => 'javascript:history.go(-1);' ),
        );
        
        cpToolMenu($toolmenu);
        
        $menu_list = cpGetList('menu');
        
        if ($do == 'add') {
            echo '<h3>'. $_LANG['AD_CREATE_SECTION'] .'</h3>';
            
            cpAddPathway($_LANG['AD_CREATE_SECTION'], 'index.php?view=cats&do=add');
            
            $mod = array();
            $mod['tpl'] = 'com_content_view';
        } else {
            if (cmsCore::inRequest('multiple')) {
                if (cmsCore::inRequest('item')) {
                    $_SESSION['editlist'] = cmsCore::request('item', 'array_int');
                } else {
                    echo '<p class="error">'. $_LANG['AD_NO_SELECT_OBJECTS'] .'</p>';
                    return;
                }
            }
            
            $ostatok = '';
            
            if (isset($_SESSION['editlist'])) {
                $id = array_shift($_SESSION['editlist']);
                if (sizeof($_SESSION['editlist']) == 0) {
                    unset($_SESSION['editlist']);
                } else {
                    $ostatok = '('. $_LANG['AD_NEXT_IN'] . sizeof($_SESSION['editlist']) .')';
                }
            } else {
                $id = cmsCore::request('id', 'int', 0);
            }
            
            $mod = cmsCore::c('db')->get_fields('cms_category', 'id='.$id, '*');
            if (!empty($mod['photoalbum'])) {
                $mod['photoalbum'] = unserialize($mod['photoalbum']);
            }
            
            echo '<h3>'. $_LANG['AD_EDIT_SECTION'] . $ostatok .'</h3>';
            cpAddPathway($mod['title'], 'index.php?view=cats&do=edit&id='. $mod['id']);
        }
?>
<form id="addform" name="addform" method="post" action="index.php">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <input type="hidden" name="view" value="cats" />

    <table class="table">
        <tr>
            <!-- главная ячейка -->
            <td valign="top">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_TITLE_PARTITION'];?></label>
                    <input type="text" id="title" class="form-control" name="title" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', ''));?>" />
                </div>
                    
                <div class="form-group">
                    <label><?php echo $_LANG['AD_TEMPLATE_PARTITION'];?></label>
                    <input type="text" class="form-control" name="tpl" value="<?php echo cmsCore::getArrVal($mod, 'tpl', '');?>" />
                </div>
                    
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PARENT_PARTITION'];?></label>
                    <div class="parent_notice" style="color:red;margin:4px 0px;display:none"><?php echo $_LANG['AD_ANOTHER_PARENT'];?></div>

                    <select name="parent_id" size="12" id="parent_id" class="form-control" onchange="if($('option:selected',this).data('nsleft')>='<?php echo cmsCore::getArrVal($mod, 'NSLeft', 0); ?>' && $('option:selected',this).data('nsright')<='<?php echo cmsCore::getArrVal($mod, 'NSRight', 0); ?>'){ $('.parent_notice').show();$('#add_mod').prop('disabled', true); } else { $('.parent_notice').hide();$('#add_mod').prop('disabled', false); }">
                        <?php $rootid = cmsCore::c('db')->getNsRootCatId('cms_category'); ?>
                        <option value="<?php echo $rootid; ?>" <?php if (!isset($mod['parent_id']) || cmsCore::getArrVal($mod, 'parent_id', '') == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_SECTION'];?></option>
                        <?php echo $inCore->getListItemsNS('cms_category', cmsCore::getArrVal($mod, 'parent_id', $rootid)); ?>
                    </select>
                </div>
                    
                <div class="form-group">
                    <label><?php echo $_LANG['AD_SECTION_DESCRIPT'];?></label>
                    <?php $inCore->insertEditor('description', cmsCore::getArrVal($mod, 'description', ''), '250', '100%'); ?>
                </div>
            </td>
                
            <!-- боковая -->
            <td valign="top" style="width:500px;">
                <div class="uitabs">
                    <ul id="tabs">
                        <li><a href="#upr_publish"><span><?php echo $_LANG['AD_TAB_PUBLISH']; ?></span></a></li>
                        <li><a href="#upr_seo"><span>SEO</span></a></li>
                        <li><a href="#upr_editors"><span><?php echo $_LANG['AD_EDITORS']; ?></span></a></li>
                        <li><a href="#upr_foto"><span><?php echo $_LANG['AD_FOTO']; ?></span></a></li>
                        <li><a href="#upr_access"><span><?php echo $_LANG['AD_TAB_ACCESS']; ?></span></a></li>
                    </ul>
                        
                    <div id="upr_publish">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="published" id="published" value="1" <?php if (cmsCore::getArrVal($mod, 'published', 0) || $do=='add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_PUBLIC_SECTION'];?>
                            </label>
                        </div>
                            
                        <div class="form-group url_cat" style=" <?php if ($do == 'edit'){  ?>display:none;<?php } ?>">
                            <label><?php echo $_LANG['AD_SECTION_URL'];?></label>
                            <input type="text" class="form-control" name="url" value="<?php echo cmsCore::getArrVal($mod, 'url', ''); ?>" />
                            <div class="help-block"><?php echo $_LANG['AD_FROM_TITLE'];?></div>
                        </div>
                            
                        <?php if ($do == 'edit') {  ?>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="update_seolink" value="1" onclick="$('.url_cat').slideToggle('fast');" />
                                    <?php echo $_LANG['AD_NEW_LINK'];?>
                            </label>
                            <div class="help-block url_cat" style="display:none;"><b style="color:#F00;"><?php echo $_LANG['ATTENTION'];?>:</b> <?php echo $_LANG['AD_NO_LINKS'];?></div>
                        </div>
                        <?php } ?>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_SORT_ARTICLES'];?></label>
                            <select id="orderby" class="form-control" name="orderby">
                                <?php $mod['orderby'] = cmsCore::getArrVal($mod, 'orderby', ''); ?>
                                <option value="pubdate" <?php if ($mod['orderby'] == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR'];?></option>
                                <option value="title" <?php if ($mod['orderby'] == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_TITLE'];?></option>
                                <option value="ordering" <?php if ($mod['orderby'] == 'ordering') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ORDER'];?></option>
                                <option value="hits" <?php if ($mod['orderby'] == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS'];?></option>
                            </select>
                            <select id="orderto" class="form-control" name="orderto">
                                <?php $mod['orderto'] = cmsCore::getArrVal($mod, 'orderto', ''); ?>
                                <option value="ASC" <?php if ($mod['orderto'] == 'ASC') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT'];?></option>
                                <option value="DESC" <?php if ($mod['orderto'] == 'DESC') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT'];?></option>
                            </select>
                        </div>

                        <table width="100%">
                            <tr>
                                <td>
                                    <strong><?php echo $_LANG['AD_HOW_MANY_COLUMNS'];?></strong>
                                </td>
                                <td>
                                    <input class="form-control uispin" name="maxcols" type="text" style="width:50px" value="<?php echo cmsCore::getArrVal($mod, 'maxcols', 1); ?>" />
                                </td>
                            </tr>
                        </table>
                            
                        <div class="form-group">
                            <h4><?php echo $_LANG['AD_HOW_PUBLISH_SET'];?></h4>
                            <table class="table">
                                <tr>
                                    <td>
                                        <?php echo $_LANG['AD_PREVIEW'];?>
                                    </td>
                                    <td>
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php
                                                $act1=$act2=$chk1=$chk2='';
                                                if (cmsCore::getArrVal($mod, 'showdesc') || $do == 'add') {
                                                    $act1 = 'active';
                                                    $chk1 = 'checked="checked"';
                                                } else {
                                                    $act2 = 'active';
                                                    $chk2 = 'checked="checked"';
                                                }
                                            ?>
                                            <label class="btn btn-default <?php echo $act1; ?>">
                                                <input type="radio" name="showdesc" <?php echo $chk1; ?> value="1" /> <?php echo $_LANG['YES'];?>
                                            </label>
                                            <label class="btn btn-default <?php echo $act2; ?>">
                                                <input type="radio" name="showdesc" <?php echo $chk2; ?> value="0" /> <?php echo $_LANG['NO'];?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $_LANG['AD_CALENDAR_VIEW'];?>
                                    </td>
                                    <td>
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php
                                                $act1=$act2=$chk1=$chk2='';
                                                if (cmsCore::getArrVal($mod, 'showdate') || $do == 'add') {
                                                    $act1 = 'active';
                                                    $chk1 = 'checked="checked"';
                                                } else {
                                                    $act2 = 'active';
                                                    $chk2 = 'checked="checked"';
                                                }
                                            ?>
                                            <label class="btn btn-default <?php echo $act1; ?>">
                                                <input type="radio" name="showdate" <?php echo $chk1; ?> value="1" /> <?php echo $_LANG['YES'];?>
                                            </label>
                                            <label class="btn btn-default <?php echo $act2; ?>">
                                                <input type="radio" name="showdate" <?php echo $chk2; ?> value="0" /> <?php echo $_LANG['NO'];?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $_LANG['AD_HOW_MANY_COMENTS'];?>
                                    </td>
                                    <td>
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php
                                                $act1=$act2=$chk1=$chk2='';
                                                if (cmsCore::getArrVal($mod, 'showcomm') || $do == 'add') {
                                                    $act1 = 'active';
                                                    $chk1 = 'checked="checked"';
                                                } else {
                                                    $act2 = 'active';
                                                    $chk2 = 'checked="checked"';
                                                }
                                            ?>
                                            <label class="btn btn-default <?php echo $act1; ?>">
                                                <input type="radio" name="showcomm" <?php echo $chk1; ?> value="1" /> <?php echo $_LANG['YES'];?>
                                            </label>
                                            <label class="btn btn-default <?php echo $act2; ?>">
                                                <input type="radio" name="showcomm" <?php echo $chk2; ?> value="0" /> <?php echo $_LANG['NO'];?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $_LANG['AD_HOW_MANY_TAGS'];?>
                                    </td>
                                    <td>
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php
                                                $act1=$act2=$chk1=$chk2='';
                                                if (cmsCore::getArrVal($mod, 'showtags') || $do == 'add') {
                                                    $act1 = 'active';
                                                    $chk1 = 'checked="checked"';
                                                } else {
                                                    $act2 = 'active';
                                                    $chk2 = 'checked="checked"';
                                                }
                                            ?>
                                            <label class="btn btn-default <?php echo $act1; ?>">
                                                <input type="radio" name="showtags" <?php echo $chk1; ?> value="1" /> <?php echo $_LANG['YES'];?>
                                            </label>
                                            <label class="btn btn-default <?php echo $act2; ?>">
                                                <input type="radio" name="showtags" <?php echo $chk2; ?> value="0" /> <?php echo $_LANG['NO'];?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $_LANG['AD_RSS_VIEW'];?>
                                    </td>
                                    <td>
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php
                                                $act1=$act2=$chk1=$chk2='';
                                                if (cmsCore::getArrVal($mod, 'showrss') || $do == 'add') {
                                                    $act1 = 'active';
                                                    $chk1 = 'checked="checked"';
                                                } else {
                                                    $act2 = 'active';
                                                    $chk2 = 'checked="checked"';
                                                }
                                            ?>
                                            <label class="btn btn-default <?php echo $act1; ?>">
                                                <input type="radio" name="showrss" <?php echo $chk1; ?> value="1" /> <?php echo $_LANG['YES'];?>
                                            </label>
                                            <label class="btn btn-default <?php echo $act2; ?>">
                                                <input type="radio" name="showrss" <?php echo $chk2; ?> value="0" /> <?php echo $_LANG['NO'];?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                            
                        <?php if ($do == 'add'){ ?>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_CREATE_LINK'];?></label>
                            <select class="form-control" style="width:99%" name="createmenu">
                                <option value="0" selected="selected"><?php echo $_LANG['AD_DONT_CREATE'];?></option>
                                <?php foreach ($menu_list as $menu) { ?>
                                <option value="<?php echo $menu['id']; ?>">
                                    <?php echo $menu['title']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php } ?>
                    </div>
                        
                    <div id="upr_seo">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PAGE_TITLE']; ?></label>
                            <input type="text" id="pagetitle" class="form-control" name="pagetitle" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'pagetitle', '')); ?>" />
                            <div class="help-block"><?php echo $_LANG['AD_IF_UNKNOWN_PAGETITLE']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['KEYWORDS']; ?></label>
                            <textarea class="form-control" name="meta_keys" rows="4"><?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'meta_keys', ''));?></textarea>
                            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['DESCRIPTION']; ?></label>
                            <textarea class="form-control" name="meta_desc" rows="6"><?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'meta_desc', ''));?></textarea>
                            <div class="help-block"><?php echo $_LANG['AD_LESS_THAN']; ?></div>
                        </div>
                    </div>
                        
                    <div id="upr_editors">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_USERS_ARTICLES'];?></label>
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default <?php echo $act1; ?>">
                                    <input type="radio" name="is_public" <?php if (cmsCore::getArrVal($mod, 'is_public')) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES'];?>
                                </label>
                                <label class="btn btn-default <?php echo $act2; ?>">
                                    <input type="radio" name="is_public" <?php if (!cmsCore::getArrVal($mod, 'is_public')) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO'];?>
                                </label>
                            </div>
                            <div class="help-block"><?php echo $_LANG['AD_IF_SWITCH'];?></div>
                        </div>

                        <?php if (IS_BILLING){ ?>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_COST_ARTICLES_ADD'];?></label>
                            <input type="text" class="form-control" style="width:50px" name="cost" value="<?php echo cmsCore::getArrVal($mod, 'cost', ''); ?>" /><?php echo $_LANG['BILLING_POINT10'];?>
                            <div class="help-block"><?php echo $_LANG['AD_COST_ARTICLES_BY_DEFAULT'];?></div>
                        </div>
                        <?php } ?>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_EDITORS_SECTION'];?></label>
                            <select class="form-control" name="modgrp_id">
                                <option value="0" <?php if (!isset($mod['modgrp_id']) || cmsCore::getArrVal($mod, 'modgrp_id', '') == 0) { echo 'selected'; }?>><?php echo $_LANG['AD_ONLY_ADMINS'];?></option>
                                <?php
                                    echo $inCore->getListItems('cms_user_groups', cmsCore::getArrVal($mod, 'modgrp_id', 0), 'id', 'ASC', 'is_admin = 0');
                                ?>
                            </select>
                            <div class="help-block"><?php echo $_LANG['AD_USERS_CAN_ADMIN'];?></div>
                        </div>
                    </div>
                        
                    <div id="upr_foto">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PHOTOALBUM_CONNECT'];?></label>
                            <select id="album_id" class="form-control" name="album_id" onchange="choosePhotoAlbum()">
                                <option value="0" <?php if (empty($mod['photoalbum']['id'])) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_DONT_CONNECT'];?></option>
                                <?php  //FIND ROOT
                                    echo $inCore->getListItemsNS('cms_photo_albums', cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'id', 0));
                                ?>
                            </select>
                            <div class="help-block"><?php echo $_LANG['AD_PHOTO_BY_ARTICLES'];?></div>
                        </div>
                            
                        <div id="con_photoalbum" <?php if (empty($mod['photoalbum']['id'])) { echo 'style="display:none;"'; }?>>
                            <div class="form-group">
                                <label><?php echo $_LANG['AD_TITLE'];?></label>
                                <input type="text" id="album_header" class="form-control" name="album_header" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'header', 0); ?>" />
                                <div class="help-block"><?php echo $_LANG['AD_OVER_PHOTOS'];?></div>
                            </div>
                                
                            <div class="form-group">
                                <label><?php echo $_LANG['AD_PHOTOS_SORT'];?></label>
                                <select class="form-control" name="album_orderby">
                                    <?php $mod['photoalbum']['orderby'] = cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'orderby', 0); ?>
                                    <option value="title" <?php if ($mod['photoalbum']['orderby'] == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET'];?></option>
                                    <option value="pubdate" <?php if ($mod['photoalbum']['orderby'] == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR'];?></option>
                                    <option value="rating" <?php if ($mod['photoalbum']['orderby'] == 'rating') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_RATING'];?></option>
                                    <option value="hits" <?php if ($mod['photoalbum']['orderby'] == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS'];?></option>
                                </select>
                                <select class="form-control" name="album_orderto">
                                    <?php $mod['photoalbum']['orderto'] = cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'orderto', 0); ?>
                                    <option value="desc" <?php if ($mod['photoalbum']['orderto'] == 'desc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT'];?></option>
                                    <option value="asc" <?php if ($mod['photoalbum']['orderto'] == 'asc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT'];?></option>
                                </select>
                            </div>
                                
                            <div class="form-group">
                                <label><?php echo $_LANG['AD_HOW_MANY_COLUMNS'];?></label>
                                <input type="text" class="form-control" name="album_maxcols" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'maxcols', 2); ?>"/>
                            </div>
                                
                            <div class="form-group">
                                <label><?php echo $_LANG['AD_HOW_MANY_PHOTO'];?></label>
                                <input type="text" class="form-control" name="album_max" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'max', 8); ?>"/>
                            </div>
                        </div>
                    </div>
                        
                    <div id="upr_access">
                        <div class="form-group">
                            <?php
                                $sql    = "SELECT * FROM cms_user_groups";
                                $result = cmsCore::c('db')->query($sql) ;

                                $style  = 'disabled="disabled"';
                                $public = 'checked="checked"';

                                if ($do == 'edit'){
                                    $sql2 = "SELECT * FROM cms_content_access WHERE content_id = ". $mod['id'] ." AND content_type = 'category'";
                                    $result2 = cmsCore::c('db')->query($sql2);
                                    $ord = array();

                                    if (cmsCore::c('db')->num_rows($result2)){
                                        $public = '';
                                        $style = '';
                                        while ($r = cmsCore::c('db')->fetch_assoc($result2)){
                                            $ord[] = $r['group_id'];
                                        }
                                    }
                                }
                            ?>
                            <label>
                                <input type="checkbox" id="is_public" name="is_access" onclick="checkGroupList()" value="1" <?php echo $public; ?> />
                                <?php echo $_LANG['AD_SHARE'];?>
                            </label>
                            <div class="help-block"><?php echo $_LANG['AD_IF_NOTED'];?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_GROUPS_VIEW'];?></label>
                            <?php
                                echo '<select id="showin" class="form-control" name="showfor[]" size="6" multiple="multiple" '. $style .'>';
                                if (cmsCore::c('db')->num_rows($result)) {
                                    while ($item = cmsCore::c('db')->fetch_assoc($result)) {
                                        echo '<option value="'. $item['id'] .'"';
                                        if ($do == 'edit' && in_array($item['id'], $ord)) {
                                            echo 'selected="selected"';
                                        }
                                        echo '>';
                                        echo $item['title'] .'</option>';
                                    }
                                }
                                echo '</select>';
                            ?>
                            <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL'];?></div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" <?php if ($do == 'add') { echo 'value="'.$_LANG['AD_SAVE_SECTION'].'"'; } else { echo 'value="'.$_LANG['AD_SAVE_SECTION'].'"'; } ?> />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />
        <input type="hidden" name="do" <?php if ($do == 'add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
        <?php
            if ($do == 'edit') {
                echo '<input type="hidden" name="id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
<script type="text/javascript">
    function choosePhotoAlbum(){
        id = $('select[name=album_id]').val();
        if(id != 0){
            $('#con_photoalbum').fadeIn();
        }else{
            $('#con_photoalbum').hide();
        }
    }
</script>
<?php
    }
}
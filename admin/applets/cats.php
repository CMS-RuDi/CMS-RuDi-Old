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

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function createMenuItem($menu, $id, $title){
    $inCore = cmsCore::getInstance();
    
    $rootid = cmsCore::c('db')->getNsRootCatId('cms_menu');
    
    $ns = $inCore->nestedSetsInit('cms_menu');
    
    $myid = $ns->AddNode($rootid);
    
    $link = $inCore->getMenuLink('category', $id);
    
    $sql = "UPDATE cms_menu
                    SET menu='$menu',
                            title='$title',
                            link='$link',
                            linktype='category',
                            linkid='$id',
                            target='_self',
                            published='1',
                            template='0',
                            access_list='',
                            iconurl=''
                    WHERE id = '$myid'";

    cmsCore::c('db')->query($sql);
    return true;
}

function applet_cats(){
    $inCore = cmsCore::getInstance();

    global $_LANG;

    cmsCore::c('page')->setAdminTitle($_LANG['AD_ARTICLES']);
    cpAddPathway($_LANG['AD_ARTICLES'], 'index.php?view=tree');

    cmsCore::loadModel('content');
    $model = new cms_model_content();

    $do = cmsCore::request('do', 'str', 'add');
    $id = cmsCore::request('id', 'int', -1);

    define('IS_BILLING', $inCore->isComponentInstalled('billing'));
    if (IS_BILLING) { cmsCore::loadClass('billing'); }

    if ($do == 'delete'){
        $is_with_content = cmsCore::inRequest('content');
        $model->deleteCategory($id, $is_with_content);
        
        cmsCore::addSessionMessage(($is_with_content ? $_LANG['AD_CATEGORY_REMOVED'] : $_LANG['AD_CATEGORY_REMOVED_NOT_ARTICLE']), 'success');
        cmsCore::redirect('?view=tree');
    }

    if ($do == 'update'){
        if (!cmsCore::validateForm()) { cmsCore::error404(); }
        if (isset($_REQUEST['id'])){
            $category['id']          = cmsCore::request('id', 'int', 0);
            $category['title']       = cmsCore::request('title', 'str', $_LANG['AD_SECTION_UNTITLED']);
            $category['parent_id']   = cmsCore::request('parent_id', 'int');
            $category['description'] = cmsCore::request('description', 'html', '');
            $category['description'] = cmsCore::c('db')->escape_string($category['description']);
            $category['pagetitle']   = cmsCore::request('pagetitle', 'str', '');
            $category['meta_desc']   = cmsCore::request('meta_desc', 'str', '');
            $category['meta_keys']   = cmsCore::request('meta_keys', 'str', '');
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
            if (!empty($category['url'])){
                $category['url'] = cmsCore::strToURL($category['url'], $model->config['is_url_cyrillic']);
            }
            $category['tpl']         = cmsCore::request('tpl', 'str', 'com_content_view.tpl');
            $category['cost']        = cmsCore::request('cost', 'str', '');

            if (!is_numeric($category['cost'])){ $category['cost'] = ''; }

            $album = array();
            $album['id']      = cmsCore::request('album_id', 'int', 0);
            $album['header']  = cmsCore::request('album_header', 'str', '');
            $album['orderby'] = cmsCore::request('album_orderby', 'str', '');
            $album['orderto'] = cmsCore::request('album_orderto', 'str', '');
            $album['maxcols'] = cmsCore::request('album_maxcols', 'int', 0);
            $album['max']	  = cmsCore::request('album_max', 'int', 0);

            if($album['id']){
                $category['photoalbum'] = serialize($album);
            }else{
                $category['photoalbum'] = '';
            }

            // получаем старую категорию
            $old = cmsCore::c('db')->get_fields('cms_category', "id='". $category['id'] ."'", '*');

            // если сменили категорию
            if($old['parent_id'] != $category['parent_id']){
                // перемещаем ее в дереве
                $inCore->nestedSetsInit('cms_category')->MoveNode($category['id'], $category['parent_id']);

                // обновляем сеолинки категорий
                cmsCore::c('db')->updateNsCategorySeoLink('cms_category', $category['id'], $model->config['is_url_cyrillic']);

                // Обновляем ссылки меню на категории
                $model->updateCatMenu();

                // обновляем сеолинки всех вложенных статей
                $model->updateArticlesSeoLink($category['id']);

                cmsCore::addSessionMessage($_LANG['AD_CATEGORY_NEW_URL'], 'info');
            }

            cmsCore::c('db')->update('cms_category', $category, $category['id']);

            // если пришел запрос на обновление ссылок
            // и категория не менялась - если менялась, мы выше все обновили
            if (cmsCore::inRequest('update_seolink') && ($old['parent_id'] == $category['parent_id'])){
                // обновляем сеолинки категорий
                cmsCore::c('db')->updateNsCategorySeoLink('cms_category', $category['id'], $model->config['is_url_cyrillic']);

                // Обновляем ссылки меню на категории
                $model->updateCatMenu();

                // обновляем сеолинки всех вложенных статей
                $model->updateArticlesSeoLink($category['id']);

                cmsCore::addSessionMessage($_LANG['AD_SECTION_AND_ARTICLES_NEW_URL'], 'info');
            }

            if (!cmsCore::request('is_access', 'int', 0)){
                $showfor = $_REQUEST['showfor'];
                cmsCore::setAccess($category['id'], $showfor, 'category');
            }else{
                cmsCore::clearAccess($category['id'], 'category');
            }

            cmsCore::addSessionMessage($_LANG['AD_CATEGORY_SAVED'], 'success');

            if (!isset($_SESSION['editlist']) || @sizeof($_SESSION['editlist'])==0){
                cmsCore::redirect('?view=tree&cat_id='.$category['id']);
            }else{
                cmsCore::redirect('?view=tree');
            }
        }
    }

    if ($do == 'submit'){
        if (!cmsCore::validateForm()) { cmsCore::error404(); }

        $category['title']       = cmsCore::request('title', 'str', $_LANG['AD_CATEGORY_UNTITLED']);
        $category['url']         = cmsCore::request('url', 'str', '');
        if (!empty($category['url'])) {
            $category['url'] = cmsCore::strToURL($category['url']);
        }
        $category['parent_id']   = cmsCore::request('parent_id', 'int');
        $category['description'] = cmsCore::request('description', 'html', '');
        $category['description'] = cmsCore::c('db')->escape_string($category['description']);
        $category['pagetitle']   = cmsCore::request('pagetitle', 'str', '');
        $category['meta_desc']   = cmsCore::request('meta_desc', 'str', '');
        $category['meta_keys']   = cmsCore::request('meta_keys', 'str', '');
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
        $category['tpl']         = cmsCore::request('tpl', 'str', 'com_content_view.tpl');

        $category['cost']        = cmsCore::request('cost', 'str', 0);
        if (!is_numeric($category['cost'])){ $category['cost'] = ''; }

        $album = array();
        $album['id']      = cmsCore::request('album_id', 'int', 0);
        $album['header']  = cmsCore::request('album_header', 'str', '');
        $album['orderby'] = cmsCore::request('album_orderby', 'str', '');
        $album['orderto'] = cmsCore::request('album_orderto', 'str', '');
        $album['maxcols'] = cmsCore::request('album_maxcols', 'int', 0);
        $album['max']     = cmsCore::request('album_max', 'int', 0);

        if($album['id']){
            $category['photoalbum'] = serialize($album);
        }else{
            $category['photoalbum'] = '';
        }

        $ns = $inCore->nestedSetsInit('cms_category');
        $category['id'] = $ns->AddNode($category['parent_id']);

        $category['seolink'] = cmsCore::generateCatSeoLink($category, 'cms_category', $model->config['is_url_cyrillic']);

        if ($category['id']){
            cmsCore::c('db')->update('cms_category', $category, $category['id']);

            if (!cmsCore::request('is_access', 'int', 0)){
                $showfor = $_REQUEST['showfor'];
                cmsCore::setAccess($category['id'], $showfor, 'category');
            }else{
                cmsCore::clearAccess($category['id'], 'category');
            }
        }

        $inmenu = cmsCore::request('createmenu', 'str', '');

        if ($inmenu){
            createMenuItem($inmenu, $category['id'], $category['title']);
        }

        cmsCore::addSessionMessage($_LANG['AD_CATEGORY_ADD'], 'success');

        cmsCore::redirect('?view=tree');
    }

    if ($do == 'add' || $do == 'edit'){
        require('../includes/jwtabs.php');
        cmsCore::c('page')->addHead(jwHeader());
        
        $toolmenu = array(
            array(
                'icon' => 'save.gif',
                'title' => $_LANG['SAVE'],
                'link' => 'javascript:document.addform.submit();'
            ),
            array(
                'icon' => 'cancel.gif',
                'title' => $_LANG['CANCEL'],
                'link' => 'javascript:history.go(-1);'
            ),
        );
        cpToolMenu($toolmenu);
        
        $menu_list = cpGetList('menu');
        
        if ($do=='add'){
            echo '<h3>'.$_LANG['AD_CREATE_SECTION'].'</h3>';
            cpAddPathway($_LANG['AD_CREATE_SECTION'], 'index.php?view=cats&do=add');
            $mod['tpl'] = 'com_content_view.tpl';
        }else{
            if(isset($_REQUEST['multiple'])){
                if (isset($_REQUEST['item'])){
                    $_SESSION['editlist'] = $_REQUEST['item'];
                }else{
                    echo '<p class="error">'.$_LANG['AD_NO_SELECT_OBJECTS'].'</p>';
                    return;
                }
            }
            
            $ostatok = '';
            
            if (isset($_SESSION['editlist'])){
                $id = array_shift($_SESSION['editlist']);
                if (sizeof($_SESSION['editlist'])==0){
                    unset($_SESSION['editlist']);
                }else{
                    $ostatok = '('. $_LANG['AD_NEXT_IN'] . sizeof($_SESSION['editlist']) .')';
                }
            }else{
                $id = cmsCore::request('id', 'int', 0);
            }
            
            $mod = cmsCore::c('db')->get_fields('cms_category', 'id='.$id, '*');
            if (!empty($mod['photoalbum'])){
                $mod['photoalbum'] = unserialize($mod['photoalbum']);
            }
            
            echo '<h3>'. $_LANG['AD_EDIT_SECTION'] . $ostatok .'</h3>';
            cpAddPathway($mod['title'], 'index.php?view=cats&do=edit&id='. $mod['id']);
        }
?>

    <form id="addform" name="addform" method="post" action="index.php">
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
        <input type="hidden" name="view" value="cats" />
        <table class="proptable" width="100%" cellpadding="15" cellspacing="2">
            <tr>
                <!-- главная ячейка -->
                <td valign="top">
                    <table border="0" cellpadding="0" cellspacing="5" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <strong><?php echo $_LANG['AD_TITLE_PARTITION'];?></strong>
                                </td>
                                <td width="190" style="padding-left:6px">
                                    <strong><?php echo $_LANG['AD_TEMPLATE_PARTITION'];?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input name="title" type="text" id="title" style="width:100%" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', ''));?>" />
                                </td>
                                <td style="padding-left:6px">
                                    <input name="tpl" type="text" style="width:98%" value="<?php echo cmsCore::getArrVal($mod, 'tpl', '');?>" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div><strong><?php echo $_LANG['AD_PARENT_PARTITION'];?></strong></div>
                    <div>
                        <div class="parent_notice" style="color:red;margin:4px 0px;display:none"><?php echo $_LANG['AD_ANOTHER_PARENT'];?></div>
                        <select name="parent_id" size="12" id="parent_id" style="width:100%" onchange="if($(this).val()=='<?php echo cmsCore::getArrVal($mod, 'id', ''); ?>'){ $('.parent_notice').show();$('#add_mod').prop('disabled', true); } else { $('.parent_notice').hide();$('#add_mod').prop('disabled', false); }">
                            <?php $rootid = cmsCore::c('db')->getNsRootCatId('cms_category'); ?>
                            <option value="<?php echo $rootid; ?>" <?php if (!isset($mod['parent_id']) || cmsCore::getArrVal($mod, 'parent_id', '') == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_SECTION'];?></option>
                            <?php echo $inCore->getListItemsNS('cms_category', cmsCore::getArrVal($mod, 'parent_id', 0)); ?>
                        </select>
                    </div>
                    
                    <div><strong><?php echo $_LANG['AD_SECTION_DESCRIPT'];?></strong></div>
                    <div>
                        <?php $inCore->insertEditor('description', cmsCore::getArrVal($mod, 'description', ''), '250', '100%'); ?>
                    </div>

                </td>
                
                <!-- боковая -->
                <td valign="top" width="300" style="background:#ECECEC;">
                    <?php ob_start(); ?>
                    
                    {tab=<?php echo $_LANG['AD_TAB_PUBLISH']; ?>}
                    
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="checklist">
                        <tr>
                            <td width="20"><input type="checkbox" name="published" id="published" value="1" <?php if (cmsCore::getArrVal($mod, 'published', 0) || $do=='add') { echo 'checked="checked"'; } ?>/></td>
                            <td><label for="published"><strong><?php echo $_LANG['AD_PUBLIC_SECTION'];?></strong></label></td>
                        </tr>
                    </table>
                    
                    <div style=" <?php if ($do=='edit'){  ?>display:none;<?php } ?>" class="url_cat">
                        <div style="margin-top:15px">
                            <strong><?php echo $_LANG['AD_SECTION_URL'];?></strong><br/>
                            <div style="color:gray"><?php echo $_LANG['AD_FROM_TITLE'];?></div>
                        </div>
                        <div>
                            <input type="text" name="url" value="<?php echo cmsCore::getArrVal($mod, 'url', ''); ?>" style="width:99%"/>
                        </div>
                    </div>
                        
                    <?php if ($do=='edit'){  ?>
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:15px">
                            <tr>
                                <td width="20"><input type="checkbox" name="update_seolink" id="update_seolink" value="1" onclick="$('.url_cat').slideToggle('fast');" /></td>
                                <td><label for="update_seolink"><strong><?php echo $_LANG['AD_NEW_LINK'];?></strong></label></td>
                            </tr>
                        </table>
                        <div class="url_cat" style="display:none;"><strong style="color:#F00;"><?php echo $_LANG['ATTENTION'];?>:</strong> <?php echo $_LANG['AD_NO_LINKS'];?></div>
                    <?php } ?>

                    <div style="margin-top:20px"><strong><?php echo $_LANG['AD_SORT_ARTICLES'];?></strong></div>
                    <div>
                        <select name="orderby" id="orderby" style="width:100%">
                            <?php $mod['orderby'] = cmsCore::getArrVal($mod, 'orderby', ''); ?>
                            <option value="pubdate" <?php if ($mod['orderby'] == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR'];?></option>
                            <option value="title" <?php if ($mod['orderby'] == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_TITLE'];?></option>
                            <option value="ordering" <?php if ($mod['orderby'] == 'ordering') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ORDER'];?></option>
                            <option value="hits" <?php if ($mod['orderby'] == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS'];?></option>
                        </select>
                        <select name="orderto" id="orderto" style="width:100%">
                            <?php $mod['orderto'] = cmsCore::getArrVal($mod, 'orderto', ''); ?>
                            <option value="ASC" <?php if ($mod['orderto'] == 'ASC') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT'];?></option>
                            <option value="DESC" <?php if ($mod['orderto'] == 'DESC') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT'];?></option>
                        </select>
                    </div>

                    <div style="margin-top:20px"><strong><?php echo $_LANG['AD_HOW_MANY_COLUMNS'];?></strong></div>
                    <div>
                        <input class="uispin" name="maxcols" type="text" id="maxcols" style="width:99%" value="<?php echo cmsCore::getArrVal($mod, 'maxcols', 1); ?>" />
                    </div>

                    <div style="margin-top:20px"><strong><?php echo $_LANG['AD_HOW_PUBLISH_SET'];?></strong></div>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="checklist">
                        <tr>
                            <td width="20"><input type="checkbox" name="showdesc" id="showdesc" value="1" <?php if (cmsCore::getArrVal($mod, 'showdesc') || $do=='add') { echo 'checked="checked"'; } ?>/></td>
                            <td><label for="showdesc"><?php echo $_LANG['AD_PREVIEW'];?></label></td>
                        </tr>
                        <tr>
                            <td width="20"><input type="checkbox" name="showdate" id="showdate" value="1" <?php if (cmsCore::getArrVal($mod, 'showdate') || $do=='add') { echo 'checked="checked"'; } ?>/></td>
                            <td><label for="showdate"><?php echo $_LANG['AD_CALENDAR_VIEW'];?></label></td>
                        </tr>
                        <tr>
                            <td width="20"><input type="checkbox" name="showcomm" id="showcomm" value="1" <?php if (cmsCore::getArrVal($mod, 'showcomm') || $do=='add') { echo 'checked="checked"'; } ?>/></td>
                            <td><label for="showcomm"><?php echo $_LANG['AD_HOW_MANY_COMENTS'];?></label></td>
                        </tr>
                        <tr>
                            <td width="20"><input type="checkbox" name="showtags" id="showtags" value="1" <?php if (cmsCore::getArrVal($mod, 'showtags') || $do=='add') { echo 'checked="checked"'; } ?>/></td>
                            <td><label for="showtags"><?php echo $_LANG['AD_HOW_MANY_TAGS'];?></label></td>
                        </tr>
                        <tr>
                            <td width="20"><input type="checkbox" name="showrss" id="showrss" value="1" <?php if (cmsCore::getArrVal($mod, 'showrss') || $do=='add') { echo 'checked="checked"'; } ?>/></td>
                            <td><label for="showrss"><?php echo $_LANG['AD_RSS_VIEW'];?></label></td>
                        </tr>
                    </table>

                    <?php if ($do=='add'){ ?>
                        <div style="margin-top:25px">
                            <strong><?php echo $_LANG['AD_CREATE_LINK'];?></strong>
                        </div>
                        <div>
                            <select name="createmenu" id="createmenu" style="width:99%">
                                <option value="0" selected="selected"><?php echo $_LANG['AD_DONT_CREATE'];?></option>
                            <?php foreach ($menu_list as $menu) { ?>
                                <option value="<?php echo $menu['id']; ?>">
                                    <?php echo $menu['title']; ?>
                                </option>
                            <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                    
                    {tab=SEO}

                    <div style="margin-top:5px">
                        <strong><?php echo $_LANG['AD_PAGE_TITLE']; ?></strong>
                        <div class="hinttext"><?php echo $_LANG['AD_IF_UNKNOWN']; ?></div>
                    </div>
                    <div>
                        <input name="pagetitle" type="text" id="pagetitle" style="width:99%" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'pagetitle', '')); ?>" />
                    </div>

                    <div style="margin-top:20px">
                        <strong><?php echo $_LANG['KEYWORDS']; ?></strong>
                        <div class="hinttext"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                    </div>
                    <div>
                        <textarea name="meta_keys" style="width:97%" rows="2" id="meta_keys"><?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'meta_keys', ''));?></textarea>
                    </div>

                    <div style="margin-top:20px">
                        <strong><?php echo $_LANG['DESCRIPTION']; ?></strong>
                        <div class="hinttext"><?php echo $_LANG['AD_LESS_THAN']; ?></div>
                    </div>
                    <div>
                        <textarea name="meta_desc" style="width:97%" rows="4" id="meta_desc"><?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'meta_desc', ''));?></textarea>
                    </div>

                    {tab=<?php echo $_LANG['AD_EDITORS']; ?>}
                    
                    <div style="margin-top:10px">
                        <strong><?php echo $_LANG['AD_USERS_ARTICLES'];?></strong>
                        <div class="hinttext"><?php echo $_LANG['AD_IF_SWITCH'];?></div>
                    </div>
                    
                    <div>
                        <select name="is_public" style="width:100%">
                            <option value="0" <?php if(!cmsCore::getArrVal($mod, 'is_public')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['NO'];?></option>
                            <option value="1" <?php if(cmsCore::getArrVal($mod, 'is_public')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['YES'];?></option>
                        </select>
                    </div>
                    
                    <?php if (IS_BILLING){ ?>
                        <div style="margin-top:15px">
                            <strong><?php echo $_LANG['AD_COST_ARTICLES_ADD'];?></strong><br/>
                            <div style="color:gray"><?php echo $_LANG['AD_COST_ARTICLES_BY_DEFAULT'];?></div>
                        </div>
                        <div>
                            <input type="text" name="cost" value="<?php echo cmsCore::getArrVal($mod, 'cost', ''); ?>" style="width:50px"/><?php echo $_LANG['BILLING_POINT10'];?>
                        </div>
                    <?php } ?>
                    
                    <div style="margin-top:20px">
                        <strong><?php echo $_LANG['AD_EDITORS_SECTION'];?></strong>
                        <div class="hinttext"><?php echo $_LANG['AD_USERS_CAN_ADMIN'];?></div>
                    </div>
                    <div>
                        <select name="modgrp_id" id="modgrp_id" style="width:100%">
                            <option value="0" <?php if (!isset($mod['modgrp_id']) || cmsCore::getArrVal($mod, 'modgrp_id', '') == 0) { echo 'selected'; }?>><?php echo $_LANG['AD_ONLY_ADMINS'];?></option>
                            <?php
                                echo $inCore->getListItems('cms_user_groups', cmsCore::getArrVal($mod, 'modgrp_id', 0), 'id', 'ASC', 'is_admin = 0');
                            ?>
                        </select>
                    </div>

                    {tab=<?php echo $_LANG['AD_FOTO']; ?>}
                    
                    <div style="margin-top:10px">
                        <strong><?php echo $_LANG['AD_PHOTOALBUM_CONNECT'];?></strong>
                        <div class="hinttext"><?php echo $_LANG['AD_PHOTO_BY_ARTICLES'];?></div>
                    </div>
                    
                    <div>
                        <select name="album_id" id="album_id" style="width:100%" onchange="choosePhotoAlbum()">
                            <option value="0" <?php if (empty($mod['photoalbum']['id'])) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_DONT_CONNECT'];?></option>
                            <?php  //FIND ROOT
                                echo $inCore->getListItemsNS('cms_photo_albums', cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'id', 0));
                            ?>
                        </select>
                    </div>
                    
                    <div id="con_photoalbum" <?php if (empty($mod['photoalbum']['id'])) { echo 'style="display:none;"'; }?>>
                        <div style="margin-top:20px">
                            <strong><?php echo $_LANG['AD_TITLE'];?></strong>
                            <div class="hinttext"><?php echo $_LANG['AD_OVER_PHOTOS'];?></div>
                        </div>
                        <div>
                            <input name="album_header" type="text" id="album_header" style="width:99%" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'header', 0); ?>" />
                        </div>

                        <div style="margin-top:20px">
                            <strong><?php echo $_LANG['AD_PHOTOS_SORT'];?></strong>
                        </div>
                        <div>
                            <select name="album_orderby" id="album_orderby" style="width:100%">
                                <?php $mod['photoalbum']['orderby'] = cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'orderby', 0); ?>
                                <option value="title" <?php if ($mod['photoalbum']['orderby'] == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET'];?></option>
                                <option value="pubdate" <?php if ($mod['photoalbum']['orderby'] == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR'];?></option>
                                <option value="rating" <?php if ($mod['photoalbum']['orderby'] == 'rating') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_RATING'];?></option>
                                <option value="hits" <?php if ($mod['photoalbum']['orderby'] == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS'];?></option>
                            </select>
                            <select name="album_orderto" id="album_orderto" style="width:100%">
                                <?php $mod['photoalbum']['orderto'] = cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'orderto', 0); ?>
                                <option value="desc" <?php if ($mod['photoalbum']['orderto'] == 'desc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT'];?></option>
                                <option value="asc" <?php if ($mod['photoalbum']['orderto'] == 'asc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT'];?></option>
                            </select>
                        </div>

                        <div style="margin-top:20px">
                            <strong><?php echo $_LANG['AD_HOW_MANY_COLUMNS'];?></strong>
                        </div>
                        <div>
                            <input name="album_maxcols" type="text" id="album_maxcols" style="width:99%" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'maxcols', 2); ?>"/>
                        </div>

                        <div style="margin-top:20px">
                            <strong><?php echo $_LANG['AD_HOW_MANY_PHOTO'];?></strong>
                        </div>
                        <div>
                            <input name="album_max" type="text" id="album_max" style="width:99%" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'max', 8); ?>"/>
                        </div>
                    </div>
                    
                    {tab=<?php echo $_LANG['AD_TAB_ACCESS']; ?>}
                    
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="checklist" style="margin-top:5px">
                        <tr>
                            <td width="20">
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
                                <input name="is_access" type="checkbox" id="is_public" onclick="checkGroupList()" value="1" <?php echo $public; ?> />
                            </td>
                            <td><label for="is_public"><strong><?php echo $_LANG['AD_SHARE'];?></strong></label></td>
                        </tr>
                    </table>
                    
                    <div class="hinttext" style="padding:5px">
                        <?php echo $_LANG['AD_IF_NOTED'];?>
                    </div>
                    
                    <div style="margin-top:10px;padding:5px;padding-right:0px;" id="grp">
                        <div>
                            <strong><?php echo $_LANG['AD_GROUPS_VIEW'];?></strong>
                            <div class="hinttext">
                                <?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL'];?>
                            </div>
                        </div>
                        
                        <div>
                            <?php
                                echo '<select style="width: 99%" name="showfor[]" id="showin" size="6" multiple="multiple" '. $style .'>';
                                if (cmsCore::c('db')->num_rows($result)){
                                    while ($item = cmsCore::c('db')->fetch_assoc($result)){
                                        echo '<option value="'. $item['id'] .'"';
                                        if ($do=='edit'){
                                            if (inArray($ord, $item['id'])){
                                                echo 'selected="selected"';
                                            }
                                        }
                                        echo '>';
                                        echo $item['title'].'</option>';
                                    }
                                }
                                echo '</select>';
                            ?>
                        </div>
                    </div>

                    {/tabs}

                    <?php echo jwTabs(ob_get_clean()); ?>
                </td>
            </tr>
        </table>
        <p>
            <input name="add_mod" type="submit" id="add_mod" <?php if ($do=='add') { echo 'value="'.$_LANG['AD_SAVE_SECTION'].'"'; } else { echo 'value="'.$_LANG['AD_SAVE_SECTION'].'"'; } ?> />
            <input name="back" type="button" id="back" value="<?php echo $_LANG['CANCEL'];?>" onclick="window.history.back();"/>
            <input name="do" type="hidden" id="do" <?php if ($do=='add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
            <?php
                if ($do=='edit'){
                    echo '<input name="id" type="hidden" value="'. $mod['id'] .'" />';
                }
            ?>
        </p>

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
?>
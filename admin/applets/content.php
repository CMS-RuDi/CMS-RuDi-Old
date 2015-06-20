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

    if ($do == 'update'){
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        if (cmsCore::inRequest('id')) {
            $id                     = cmsCore::request('id', 'int', 0);
            $article['category_id'] = cmsCore::request('category_id', 'int', 1);
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

            if ($olddate != $pubdate) {
                $date = explode('.', $pubdate);
                $article['pubdate'] = $date[2] .'-'. $date[1] .'-'. $date[0] .' '.  date('H:i');
            }

            $autokeys               = cmsCore::request('autokeys', 'int');

            switch($autokeys){
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

            cmsCore::m('content')->updateArticle($id, $article);

            if (!cmsCore::request('is_public', 'int', 0)) {
                $showfor = cmsCore::request('showfor', 'array_int', array());
                cmsCore::setAccess($id, $showfor, 'material');
            } else {
                cmsCore::clearAccess($id, 'material');
            }

            cmsCore::m('content')->uploadArticeImage($id, cmsCore::request('delete_image', 'int', 0));

            cmsCore::addSessionMessage($_LANG['AD_ARTICLE_SAVE'], 'success');

            if (!isset($_SESSION['editlist']) || count($_SESSION['editlist']) == 0) {
                cmsCore::redirect('?view=tree&cat_id='.$article['category_id']);
            } else {
                cmsCore::redirect('?view=content&do=edit');
            }
        }
    }

    if ($do == 'submit') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        $article['category_id'] = cmsCore::request('category_id', 'int', 1);
        $article['title']       = cmsCore::request('title', 'str');
        $article['url']         = cmsCore::request('url', 'str');
        $article['showtitle']   = cmsCore::request('showtitle', 'int', 0);
        $article['description'] = cmsCore::request('description', 'html', '');
        $article['description'] = cmsCore::c('db')->escape_string($article['description']);
        $article['content']     = cmsCore::request('content', 'html', '');
        $article['content']    	= cmsCore::c('db')->escape_string($article['content']);

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

        $article['pubdate']     = cmsCore::request('pubdate', 'str');
        $date                   = explode('.', $article['pubdate']);
        $article['pubdate']     = $date[2] .'-'. $date[1] .'-'. $date[0] .' '. date('H:i');

        $article['user_id']     = cmsCore::request('user_id', 'int', cmsCore::c('user')->id);

        $article['tpl']         = cmsCore::request('tpl', 'str', 'com_content_read');

        $autokeys               = cmsCore::request('autokeys', 'int');

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

        $article['id'] = cmsCore::m('content')->addArticle($article);

        if (!cmsCore::request('is_public', 'int', 0)) {
            $showfor = cmsCore::request('showfor', 'array_int', array());
            cmsCore::setAccess($article['id'], $showfor, 'material');
        }

        $inmenu = cmsCore::request('createmenu', 'str', '');

        if ($inmenu) {
            createMenuItem($inmenu, $article['id'], $article['title']);
        }

        cmsCore::m('content')->uploadArticeImage($article['id']);

        cmsCore::addSessionMessage($_LANG['AD_ARTICLE_ADD'], 'success');

        cmsCore::redirect('?view=tree&cat_id='. $article['category_id']);
    }

    if ($do == 'add' || $do == 'edit') {
        $toolmenu = array(
            array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' ),
            array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => 'javascript:history.go(-1);' )
        );

        cpToolMenu($toolmenu);
        $menu_list = cpGetList('menu');

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

            $sql = "SELECT *, (TO_DAYS(enddate) - TO_DAYS(CURDATE())) as daysleft, DATE_FORMAT(pubdate, '%d.%m.%Y') as pubdate, DATE_FORMAT(enddate, '%d.%m.%Y') as enddate
                     FROM cms_content
                     WHERE id = ". $id ." LIMIT 1";
            $result = cmsCore::c('db')->query($sql) ;
            if (cmsCore::c('db')->num_rows($result)) {
                $mod = cmsCore::c('db')->fetch_assoc($result);
                if (!empty($mod['images'])) {
                    $mod['images'] = json_decode($mod['images'], true);
                }
            }

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
        
        $tab_plugins = cmsCore::callTabEventPlugins('ADMIN_CONTENT_TABS', !empty($mod['id']) ? $mod : array());
?>
<form id="addform" name="addform" method="post" action="index.php" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <input type="hidden" name="view" value="content" />

    <table class="table">
        <tr>
            <!-- главная ячейка -->
            <td valign="top">
                <table width="100%" cellpadding="0" cellspacing="4" border="0">
                    <tr>
                        <td valign="top">
                            <label><?php echo $_LANG['AD_ARTICLE_NAME']; ?></label>
                            <div>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td><input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($mod['title']);?>" /></td>
                                        <td style="width:15px;padding-left:10px;padding-right:10px;">
                                            <input type="checkbox" class="uittip" title="<?php echo $_LANG['AD_VIEW_TITLE']; ?>" name="showtitle" <?php if ($mod['showtitle'] || $do=='add') { echo 'checked="checked"'; } ?> value="1">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td width="130" valign="top">
                            <label><?php echo $_LANG['AD_PUBLIC_DATE']; ?></label>
                            <div>
                                <input type="text" id="pubdate" class="form-control" name="pubdate" style="width:100px;display: inline-block" <?php if(@!$mod['pubdate']) { echo 'value="'.date('d.m.Y').'"'; } else { echo 'value="'.$mod['pubdate'].'"'; } ?>/>

                                <input type="hidden" name="olddate" value="<?php echo @$mod['pubdate']?>" />
                            </div>
                        </td>
                        <td width="16" valign="bottom" style="padding-bottom:10px">
                            <input type="checkbox" id="showdate" class="uittip" name="showdate" title="<?php echo $_LANG['AD_VIEW_DATE_AND_AUTHOR']; ?>" value="1" <?php if ($mod['showdate'] || $do=='add') { echo 'checked="checked"'; } ?>/>
                        </td>
                        <td width="160" valign="top">
                            <label><?php echo $_LANG['AD_ARTICLE_TEMPLATE']; ?></label>
                            <div><input type="text" class="form-control" style="width:160px" name="tpl" value="<?php echo @$mod['tpl'];?>"></div>
                        </td>
                    </tr>
                </table>
                    
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ARTICLE_NOTICE']; ?></label>
                    <div><?php $inCore->insertEditor('description', $mod['description'], '200', '100%'); ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_ARTICLE_TEXT']; ?></label>
                    <?php insertPanel(); ?>
                    <div><?php $inCore->insertEditor('content', $mod['content'], '400', '100%'); ?></div>
                </div>
                    
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ARTICLE_TAGS']; ?></label>
                    <input type="text" id="tags" class="form-control" name="tags" value="<?php if (isset($mod['id'])) { echo cmsTagLine('content', $mod['id'], false); } ?>" />
                </div>

                <div>
                    <label>
                        <input type="radio" name="autokeys" <?php if ($do == 'add' && $cfg['autokeys']) { ?>checked="checked"<?php } ?> value="1"/>
                        <?php echo $_LANG['AD_AUTO_GEN_KEY']; ?>
                    </label>
                </div>
                <div>
                    <label>
                        <input type="radio" name="autokeys" value="2" />
                        <?php echo $_LANG['AD_TAGS_AS_KEY']; ?>
                    </label>
                </div>
                <div>
                    <label>
                        <input type="radio" name="autokeys" id="autokeys3" value="3" <?php if ($do == 'edit' || !$cfg['autokeys']) { ?>checked="checked"<?php } ?>/>
                        <?php echo $_LANG['AD_MANUAL_KEY']; ?>
                    </label>
                </div>
                    
                <?php if ($cfg['af_on'] && $do=='add') { ?>
                <div>
                    <label>
                        <input type="checkbox" name="noforum" id="noforum" value="1" />
                        <?php echo $_LANG['AD_NO_CREATE_THEME']; ?>
                    </label>
                </div>
                <?php } ?>
            </td>

            <!-- боковая ячейка -->
            <td valign="top" style="width:450px">
                <div class="uitabs">
                    <ul id="tabs">
                        <li><a href="#upr_publish"><span><?php echo $_LANG['AD_TAB_PUBLISH']; ?></span></a></li>
                        <li><a href="#upr_restrictions"><span><?php echo $_LANG['AD_RESTRICTIONS']; ?></span></a></li>
                        <li><a href="#upr_photos"><span><?php echo $_LANG['AD_PHOTOS']; ?></span></a></li>
                        <?php if (!empty($tab_plugins)){ foreach ($tab_plugins as $tab_plugin){ ?>
                            <li><a href="<?php if ($tab_plugin['ajax_link']){ echo $tab_plugin['ajax_link']; }else{ echo '#upr_'. $tab_plugin['name']; } ?>" title="<?php echo $tab_plugin['name']; ?>"><span><?php echo $tab_plugin['title']; ?></span></a></li>
                        <?php }} ?>
                    </ul>
                        
                    <div id="upr_publish">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="published" id="published" value="1" <?php if ($mod['published'] || $do=='add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_PUBLIC_ARTICLE']; ?>
                            </label>
                        </div>
                            
                        <div class="form-group">
                            <select id="category_id" class="form-control" style="height:200px" name="category_id" size="10">
                                <option value="1" <?php if (@$mod['category_id']==1 || !isset($mod['category_id'])) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_ROOT_CATEGORY'] ; ?></option>
                                <?php
                                    if (isset($mod['category_id'])){
                                        echo $inCore->getListItemsNS('cms_category', $mod['category_id']);
                                    } else {
                                        echo $inCore->getListItemsNS('cms_category');
                                    }
                                ?>
                            </select>
                            <select id="showpath" name="showpath" class="form-control">
                                <option value="0" <?php if (@!$mod['showpath']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PATHWAY_NAME_ONLY']; ?></option>
                                <option value="1" <?php if (@$mod['showpath']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PATHWAY_FULL']; ?></option>
                            </select>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_ARTICLE_URL']; ?></label>
                            <input type="text" class="form-control" name="url" value="<?php echo $mod['url']; ?>" />
                            <div class="help-block"><?php echo $_LANG['AD_IF_UNKNOWN_PAGETITLE']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_ARTICLE_AUTHOR']; ?></label>
                            <select id="user_id" class="form-control" name="user_id">
                            <?php
                                if (isset($mod['user_id'])) {
                                    echo $inCore->getListItems('cms_users', $mod['user_id'], 'nickname', 'ASC', 'is_deleted=0 AND is_locked=0', 'id', 'nickname');
                                } else {
                                    echo $inCore->getListItems('cms_users', cmsCore::c('user')->id, 'nickname', 'ASC', 'is_deleted=0 AND is_locked=0', 'id', 'nickname');
                                }
                            ?>
                            </select>
                        </div>
                            
                        <h4><?php echo $_LANG['AD_PUBLIC_PARAMETRS']; ?></h4>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="showlatest" value="1" <?php if ($mod['showlatest'] || $do=='add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_VIEW_NEW_ARTICLES']; ?>
                            </label>
                            <label>
                                <input type="checkbox" name="comments" value="1" <?php if ($mod['comments'] || $do=='add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_ENABLE_COMMENTS']; ?>
                            </label>
                            <label>
                                <input type="checkbox" name="canrate" value="1" <?php if ($mod['canrate']) { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_ENABLE_RATING']; ?>
                            </label>
                        </div>
                            
                        <h4>SEO</h4>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PAGE_TITLE']; ?></label>
                            <input type="text" class="form-control" name="pagetitle" value="<?php if (isset($mod['pagetitle'])) { echo htmlspecialchars($mod['pagetitle']); } ?>" />
                            <div class="help-block"><?php echo $_LANG['AD_IF_UNKNOWN']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['KEYWORDS']; ?></label>
                            <textarea class="form-control" name="meta_keys" rows="4"><?php echo htmlspecialchars($mod['meta_keys']);?></textarea>
                            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['DESCRIPTION']; ?></label>
                            <textarea class="form-control" name="meta_desc" rows="6"><?php echo htmlspecialchars($mod['meta_desc']);?></textarea>
                            <div class="help-block"><?php echo $_LANG['AD_LESS_THAN']; ?></div>
                        </div>
                            
                        <?php if ($do=='add'){ ?>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_CREATE_LINK']; ?></label>
                            <select class="form-control" name="createmenu">
                                <option value="0" selected="selected"><?php echo $_LANG['AD_DONT_CREATE_LINK']; ?></option>
                            <?php foreach ($menu_list as $menu) { ?>
                                <option value="<?php echo $menu['id']; ?>">
                                    <?php echo $menu['title']; ?>
                                </option>
                            <?php } ?>
                            </select>
                        </div>
                        <?php } ?>
                    </div>
                        
                    <div id="upr_restrictions">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_ARTICLE_TIME']; ?></label>
                            <select class="form-control" name="is_end" onchange="if($(this).val() == 1){ $('#final_time').show(); }else {$('#final_time').hide();}">
                                <option value="0" <?php if (@!$mod['is_end']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_UNLIMITED']; ?></option>
                                <option value="1" <?php if (@$mod['is_end']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TO_FINAL_TIME']; ?></option>
                            </select>
                        </div>
                            
                        <div id="final_time" class="form-group" <?php if (@!$mod['is_end']) { echo 'style="display: none"'; } ?>>
                            <label><?php echo $_LANG['AD_FINAL_TIME']; ?></label>
                            <input type="text" id="enddate" class="form-control" name="enddate" <?php if(@!$mod['is_end']) { echo 'value="'.date('d.m.Y').'"'; } else { echo 'value="'. $mod['enddate'] .'"'; } ?> />
                            <div class="help-block"><?php echo $_LANG['AD_CALENDAR_FORMAT']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <?php
                                $sql    = "SELECT * FROM cms_user_groups";
                                $result = cmsCore::c('db')->query($sql) ;

                                $style  = 'disabled="disabled"';
                                $public = 'checked="checked"';

                                if ($do == 'edit') {
                                    $sql2 = "SELECT * FROM cms_content_access WHERE content_id = ".$mod['id']." AND content_type = 'material'";
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
                                <input name="is_public" type="checkbox" id="is_public" onclick="checkGroupList()" value="1" <?php echo $public?> />
                                <?php echo $_LANG['AD_SHARE']; ?>
                            </label>
                            <div class="help-block"><?php echo $_LANG['AD_IF_NOTED']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_GROUPS_VIEW']; ?></label>
                            <?php
                                echo '<select id="showin" class="form-control" name="showfor[]" size="6" multiple="multiple" '.$style.'>';

                                if (cmsCore::c('db')->num_rows($result)){
                                    while ($item = cmsCore::c('db')->fetch_assoc($result)){
                                        echo '<option value="'.$item['id'].'"';
                                        if ($do=='edit'){
                                            if (in_array($item['id'], $ord)){
                                                echo 'selected="selected"';
                                            }
                                        }

                                        echo '>';
                                        echo $item['title'].'</option>';
                                    }
                                }

                                echo '</select>';
                            ?>
                            <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?></div>
                        </div>
                    </div>
                        
                    <div id="upr_photos">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PHOTO']; ?></label>
                                
                            <?php
                                if ($do == 'edit' && file_exists(PATH.'/images/photos/small/article'. $mod['id'] .'.jpg')){
                            ?>
                            <div style="margin-top:3px;margin-bottom:3px;padding:10px;border:solid 1px gray;text-align:center">
                                <img src="/images/photos/small/article<?php echo $id; ?>.jpg" border="0" />
                            </div>
                            <label>
                                <input type="checkbox" name="delete_image" value="1" />
                                <?php echo $_LANG['AD_PHOTO_REMOVE']; ?>
                            </label>
                            <?php
                                }
                            ?>

                            <input type="file" class="form-control" name="picture" />
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_INSERTED_IMAGES']; ?></label>
                            <?php echo $ajaxUploader; ?>
                        </div>
                    </div>
                        
                    <?php foreach ($tab_plugins as $tab_plugin) { ?>
                        <div id="upr_<?php echo $tab_plugin['name']; ?>"><?php echo $tab_plugin['html']; ?></div>
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" <?php if ($do == 'add') { echo 'value="'. $_LANG['AD_CREATE_CONTENT'] .'"'; } else { echo 'value="'. $_LANG['AD_SAVE_CONTENT'] .'"'; } ?> />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();"/>
        <input type="hidden" name="do" <?php if ($do == 'add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
        <?php
            if ($do == 'edit') {
                echo '<input type="hidden" name="id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
    <?php
    }
}
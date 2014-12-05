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
function uploadCategoryIcon($file='') {
    cmsCore::loadClass('upload_photo');
    $inUploadPhoto = cmsUploadPhoto::getInstance();
    $inUploadPhoto->upload_dir    = PATH.'/upload/forum/';
    $inUploadPhoto->dir_medium    = 'cat_icons/';
    $inUploadPhoto->medium_size_w = 32;
    $inUploadPhoto->medium_size_h = 32;
    $inUploadPhoto->only_medium   = true;
    $inUploadPhoto->is_watermark  = false;
    $files = $inUploadPhoto->uploadPhoto($file);
    $icon = $files['filename'] ? $files['filename'] : $file;
    return $icon;
}

define('IS_BILLING', $inCore->isComponentInstalled('billing'));
if (IS_BILLING) { cmsCore::loadClass('billing'); }

$opt = cmsCore::request('opt', 'str', 'list_forums');

cmsCore::loadModel('forum');
$model = new cms_model_forum();

$cfg = $model->config;

if ($opt == 'list_forums' || $opt == 'list_cats' || $opt == 'config'){
    $toolmenu = array(
        array( 'icon' => 'newfolder.gif', 'title' => $_LANG['AD_CREATE_CATEGORY'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_cat' ),
        array( 'icon' => 'newforum.gif', 'title' => $_LANG['AD_FORUM_NEW'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_forum' ),
        array( 'icon' => 'folders.gif', 'title' => $_LANG['AD_FORUMS_CATS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_cats' ),
        array( 'icon' => 'listforums.gif', 'title' => $_LANG['AD_FORUMS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_forums' ),
        array( 'icon' => 'ranks.gif', 'title' => $_LANG['AD_RANKS_FORUM'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_ranks' )
    );

    if ($opt == 'list_forums') {
        $toolmenu[] = array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=edit_forum&multiple=1');" );
        $toolmenu[] = array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=show_forum&multiple=1');" );
        $toolmenu[] = array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=hide_forum&multiple=1');" );
    }
    
    $toolmenu[] = array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&id='. $id .'&opt=config' );
} else {
    $toolmenu = array(
        array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' ),
        array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components&do=config&id='. $id )
    );
}

cpToolMenu($toolmenu);

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg['is_rss']     = cmsCore::request('is_rss', 'int', 1);
    $cfg['pp_thread']  = cmsCore::request('pp_thread', 'int', 15);
    $cfg['pp_forum']   = cmsCore::request('pp_forum', 'int', 15);
    $cfg['showimg']    = cmsCore::request('showimg', 'int', 1);
    $cfg['img_on']     = cmsCore::request('img_on', 'int', 1);
    $cfg['img_max']    = cmsCore::request('img_max', 'int', 1);
    $cfg['fast_on']    = cmsCore::request('fast_on', 'int', 1);
    $cfg['fast_bb']    = cmsCore::request('fast_bb', 'int', 1);
    $cfg['fa_on']      = cmsCore::request('fa_on', 'int');
    $cfg['fa_max']     = cmsCore::request('fa_max', 'int');
    $cfg['fa_ext']     = cmsCore::request('fa_ext', 'str');

    while (mb_strpos($cfg['fa_ext'], 'htm') ||
           mb_strpos($cfg['fa_ext'], 'php') ||
           mb_strpos($cfg['fa_ext'], 'ht')) {
        $cfg['fa_ext'] = str_replace(array('htm','php','ht'), '', mb_strtolower($cfg['fa_ext']));
    }
    $cfg['fa_size']       = cmsCore::request('fa_size', 'int');
    $cfg['edit_minutes']  = cmsCore::request('edit_minutes', 'int');
    $cfg['watermark']     = cmsCore::request('watermark', 'int');
    $cfg['min_karma_add'] = cmsCore::request('min_karma_add', 'int', 0);
    
    $cfg['meta_keys'] = cmsCore::request('meta_keys', 'str', '');
    $cfg['meta_desc'] = cmsCore::request('meta_desc', 'str', '');

    $is_access = cmsCore::request('is_access', 'int', '');
    if (!$is_access) {
        $cfg['group_access'] = cmsCore::request('allow_group', 'array_int', '');
    } else {
        $cfg['group_access'] = '';
    }

    $inCore->saveComponentConfig('forum', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'info');
    cmsCore::redirectBack();
}

if ($opt == 'saveranks') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $ranks = cmsCore::request('rank', 'array_str', array());
    $cfg['modrank'] = cmsCore::request('modrank', 'int');
    
    foreach ($ranks as $key => $row) {
        $msg[$key]  = $row['msg'];
    }
    
    array_multisort($msg, SORT_ASC, $ranks);
    $num = 1;
    $cfg['ranks'] = array();
    
    foreach ($ranks as $key => $row) {
        if (!$row['msg'] || !$row['title']) {
            unset($ranks[$key]); continue;
        }
        
        $cfg['ranks'][$num] = $row; $num++; 
    } 

    $inCore->saveComponentConfig('forum', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'info');
    cmsCore::redirectBack();
}

if ($opt == 'show_forum'){
    if (!cmsCore::inRequest('item')){
        if (cmsCore::inRequest('item_id')){
            cmsCore::c('db')->setFlag('cms_forums', cmsCore::request('item_id', 'int', 0), 'published', '1');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_forums', cmsCore::request('item', 'array_int'), 'published', '1');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_forum'){
    if (!cmsCore::inRequest('item')){
        if (cmsCore::inRequest('item_id')){
            cmsCore::c('db')->setFlag('cms_forums', cmsCore::request('item_id', 'int', 0), 'published', '0');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_forums', cmsCore::request('item', 'array_int'), 'published', '0');
        cmsCore::redirectBack();
    }
}

if ($opt == 'submit_forum'){
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $moder_list  = cmsCore::request('moder_list', 'array_int', array());
    $moder_list  = $moder_list ? cmsCore::arrayToYaml($moder_list) : '';

    $is_access = cmsCore::request('is_access', 'int', '');
    if (!$is_access) {
        $access_list = cmsCore::request('access_list', 'array_int');
        $group_access = $access_list ? cmsCore::arrayToYaml($access_list) : '';
    } else {
        $group_access = '';
    }

    $icon = uploadCategoryIcon();
    
    cmsCore::c('db')->addNsCategory('cms_forums', array(
        'category_id' => cmsCore::request('category_id', 'int'),
        'parent_id'   => cmsCore::request('parent_id', 'int'),
        'title'       => cmsCore::c('db')->escape_string(cmsCore::request('title', 'str', 'NO_TITLE')),
        'description' => cmsCore::c('db')->escape_string(cmsCore::request('description', 'str', '')),
        'access_list' => $group_access,
        'moder_list'  => $moder_list,
        'published'   => cmsCore::request('published', 'int', 0),
        'icon'        => $icon,
        'pagetitle'   => cmsCore::request('pagetitle', 'str', ''),
        'meta_keys'   => cmsCore::request('meta_keys', 'str', ''),
        'meta_desc'   => cmsCore::request('meta_desc', 'str', ''),
        'topic_cost'  => cmsCore::request('topic_cost', 'int', 0))
    );

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'info');
    cmsCore::redirect('?view=components&do=config&opt=list_forums&id='. $id);
}

if ($opt == 'update_forum'){
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item_id     = cmsCore::request('item_id', 'int');
    $category_id = cmsCore::request('category_id', 'int');
    $title       = cmsCore::request('title', 'str', 'NO_TITLE');
    $pagetitle   = cmsCore::request('pagetitle', 'str', '');
    $meta_keys   = cmsCore::request('meta_keys', 'str', '');
    $meta_desc   = cmsCore::request('meta_desc', 'str', '');
    $published   = cmsCore::request('published', 'int');
    $parent_id   = cmsCore::request('parent_id', 'int');
    $description = cmsCore::request('description', 'str');
    $topic_cost  = cmsCore::request('topic_cost', 'int', 0);
    $moder_list  = cmsCore::request('moder_list', 'array_int', array());
    $moder_list  = $moder_list ? cmsCore::arrayToYaml($moder_list) : '';

    $is_access = cmsCore::request('is_access', 'int', '');
    if (!$is_access) {
        $access_list = cmsCore::request('access_list', 'array_int');
        $group_access = $access_list ? cmsCore::arrayToYaml($access_list) : '';
        cmsCore::c('db')->query("UPDATE cms_forum_threads SET is_hidden = 1 WHERE forum_id = '". $item_id ."'");
    } else {
        $group_access = '';
        cmsCore::c('db')->query("UPDATE cms_forum_threads SET is_hidden = 0 WHERE forum_id = '". $item_id ."'");
    }

    $ns = $inCore->nestedSetsInit('cms_forums');
    $old = cmsCore::c('db')->get_fields('cms_forums', "id='". $item_id ."'", '*');

    $icon = uploadCategoryIcon($old['icon']);

    if ($parent_id != $old['parent_id']) {
        $ns->MoveNode($item_id, $parent_id);
    }

    $sql = "UPDATE cms_forums
            SET category_id=". $category_id .",
                title='". cmsCore::c('db')->escape_string($title) ."',
                description='". cmsCore::c('db')->escape_string($description) ."',
                access_list='". $group_access ."',
                moder_list='". $moder_list ."',
                published=". $published .",
                icon='". $icon ."',
                topic_cost='". $topic_cost ."',
                pagetitle = '". cmsCore::c('db')->escape_string($pagetitle) ."',
                meta_keys = '". cmsCore::c('db')->escape_string($meta_keys) ."',
                meta_desc = '". cmsCore::c('db')->escape_string($meta_desc) ."'
            WHERE id = '". $item_id ."'
            LIMIT 1";

    cmsCore::c('db')->query($sql);

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'info');

    if (empty($_SESSION['editlist'])) {
        cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_forums');
    } else {
        cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=edit_forum');
    }
}

if ($opt == 'delete_forum') {
    $forum = $model->getForum(cmsCore::request('item_id', 'int'));
    if (!$forum){ cmsCore::error404(); }

    cmsCore::c('db')->addJoin('INNER JOIN cms_forums f ON f.id = t.forum_id');
    $model->whereThisAndNestedForum($forum['NSLeft'], $forum['NSRight']);

    $threads = $model->getThreads();

    foreach ($threads as $thread) {
        $model->deleteThread($thread['id']);
    }

    cmsCore::c('db')->deleteNS('cms_forums', $forum['id']);
    if (file_exists(PATH.'/upload/forum/cat_icons/'. $forum['icon'])) {
        @chmod(PATH.'/upload/forum/cat_icons/'. $forum['icon'], 0777);
        @unlink(PATH.'/upload/forum/cat_icons/'. $forum['icon']);
    }

    cmsCore::addSessionMessage($_LANG['AD_FORUM_IS_DELETE'], 'info');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_forums');
}


if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);
?>
<form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id;?>" method="post" name="addform" target="_self" id="form1" style="margin-top:10px">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div class="uitabs" style="width:600px;">
        <ul>
            <li><a href="#tab_reviev"><?php echo $_LANG['AD_REVIEV']; ?></a></li>
            <li><a href="#tab_pictures"><?php echo $_LANG['AD_PICTURES']; ?></a></li>
            <li><a href="#tab_inverstments"><?php echo $_LANG['AD_INVESTMENTS']; ?></a></li>
            <li><a href="#tab_limit"><?php echo $_LANG['AD_LIMIT']; ?></a></li>
            <li><a href="#tab_seo">SEO</a></li>
        </ul>
        
        <div id="tab_reviev">
            <fieldset>
                <legend><?php echo $_LANG['AD_FORUM_REVIEV']; ?></legend>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_TOPICS_PER_PAGE']; ?></label>
                    <input type="number" class="form-control" name="pp_forum" min="0" value="<?php echo $cfg['pp_forum'];?>" />
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ICON_RSS']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'active'; } ?>">
                            <input type="radio" name="is_rss" <?php if(cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'active'; } ?>">
                            <input type="radio" name="is_rss" <?php if (!cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </fieldset>
            
            <fieldset>
                <legend><?php echo $_LANG['AD_TOPIC_REVIEV']; ?></legend>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_POSTS_PER_PAGE']; ?></label>
                    <input type="number" class="form-control" name="pp_thread" min="0" value="<?php echo $cfg['pp_thread'];?>" />
                </div>
                
                <div class="form-group">
                    <label style="width:400px;"><?php echo $_LANG['AD_SHOW_PICCTURES']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'showimg', false)) { echo 'active'; } ?>">
                            <input type="radio" name="showimg" <?php if(cmsCore::getArrVal($cfg, 'showimg', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'showimg', false)) { echo 'active'; } ?>">
                            <input type="radio" name="showimg" <?php if (!cmsCore::getArrVal($cfg, 'showimg', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_FORM_QUICK_RESPONCE']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'fast_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fast_on" <?php if(cmsCore::getArrVal($cfg, 'fast_on', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'fast_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fast_on" <?php if (!cmsCore::getArrVal($cfg, 'fast_on', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_BBCODE_RENSPONCE']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'fast_bb', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fast_bb" <?php if(cmsCore::getArrVal($cfg, 'fast_bb', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'fast_bb', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fast_bb" <?php if (!cmsCore::getArrVal($cfg, 'fast_bb', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </fieldset>
        </div>
        
        <div id="tab_pictures">
            <fieldset>
                <legend><?php echo $_LANG['AD_PICTURES_MESS']; ?></legend>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PICTURES_INSERT']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="img_on" <?php if(cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="img_on" <?php if (!cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PICTURES_MAX']; ?></label>
                    <input type="number" class="form-control" name="img_max" min="0" value="<?php echo $cfg['img_max'];?>" />
                    <div class="help-block"><?php echo $_LANG['AD_PICTURES_NUMBER']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PICTURES_WATERMARK']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </fieldset>
        </div>
        
        <div id="tab_inverstments">
            <fieldset>
                <legend><?php echo $_LANG['AD_FILES_ATTACHMENTS']; ?></legend>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_FILES_ATTACH']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'fa_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fa_on" <?php if(cmsCore::getArrVal($cfg, 'fa_on', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'fa_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="fa_on" <?php if (!cmsCore::getArrVal($cfg, 'fa_on', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <?php
                        $groups = cmsUser::getGroups();

                        $style  = 'disabled="disabled"';
                        $public = 'checked="checked"';

                        if ($cfg['group_access']) {
                            $public = '';
                            $style  = '';
                        }
                    ?>
                    <label><?php echo $_LANG['AD_AVAILABLES_FOR_GROUPS']; ?></label>
                    <label style="padding-left: 50px;">
                        <input type="checkbox" id="is_access" name="is_access" onclick="checkGroupList()" value="1" <?php echo $public; ?> /> <?php echo $_LANG['AD_ALL_GROUPS']; ?>
                    </label>
                    <div class="help-block"><?php echo $_LANG['AD_AVAILABLE_GROUPS']; ?></div>
                    <div class="help-block"><?php echo $_LANG['AD_ALL_GROUPS_HINT']; ?></div>
                    
                    <label><?php echo $_LANG['AD_ALL_GROUPS_ONLY']; ?></label>
                    <?php
                        echo '<select id="showin" class="form-control" name="allow_group[]" size="6" multiple="multiple" '.$style.'>';
                        if ($groups) {
                            foreach($groups as $group) {
                                if ($group['alias'] != 'guest' && !$group['is_admin']) {
                                    echo '<option value="'. $group['id'] .'"';
                                    if ($cfg['group_access']) {
                                        if (in_array($group['id'], $cfg['group_access'])) {
                                            echo 'selected="selected"';
                                        }
                                    }

                                    echo '>';
                                    echo $group['title'] .'</option>';
                                }
                            }
                        }
                        echo '</select>';
                    ?>
                    <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?></div>
                    <script type="text/javascript">
                        function checkGroupList(){
                            if ($('input#is_access').prop('checked')) {
                                $('select#showin').prop('disabled', true);
                            } else {
                                $('select#showin').prop('disabled', false);
                            }
                        }
                    </script>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_FILES_MAX']; ?></label>
                    <input type="number" class="form-control" name="fa_max" min="0" value="<?php echo $cfg['fa_max'];?>" />
                    <div class="help-block"><?php echo $_LANG['AD_FILES_MAX_HINT']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ALLOWED_EXTENSIONS']; ?></label>
                    <textarea id="fa_ext" class="form-control" name="fa_ext" cols="35" rows="3"><?php echo $cfg['fa_ext'];?></textarea>
                    <div class="help-block"><?php echo $_LANG['AD_ALLOWED_EXTENSIONS_HINT']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_MAX_FILE_SIZE']; ?></label>
                    <input type="number" class="form-control" name="fa_size" min="0" value="<?php echo $cfg['fa_size'];?>" />
                    <div class="help-block"><?php echo $_LANG['AD_FILES_MAX_HINT']; ?></div>
                </div>
            </fieldset>
        </div>
        
        <div id="tab_limit">
            <div class="form-group">
                <label><?php echo $_LANG['AD_EDIT_DELIT']; ?></label>
                <select class="form-control" name="edit_minutes">
                    <option value="0" <?php if (!$cfg['edit_minutes']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_NOT_PROHIBIT']; ?></option>
                    <option value="-1" <?php if ($cfg['edit_minutes'] == -1) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PROHIBIT']; ?></option>
                    <option value="1" <?php if ($cfg['edit_minutes'] == 1) { echo 'selected="selected"'; } ?>>1 <?php echo $_LANG['MINUTU1']; ?></option>
                    <option value="5" <?php if ($cfg['edit_minutes'] == 5) { echo 'selected="selected"'; } ?>>5 <?php echo $_LANG['MINUTE10']; ?></option>
                    <option value="10" <?php if ($cfg['edit_minutes'] == 10) { echo 'selected="selected"'; } ?>>10 <?php echo $_LANG['MINUTE10']; ?></option>
                    <option value="15" <?php if ($cfg['edit_minutes'] == 15) { echo 'selected="selected"'; } ?>>15 <?php echo $_LANG['MINUTE10']; ?></option>
                    <option value="30" <?php if ($cfg['edit_minutes'] == 30) { echo 'selected="selected"'; } ?>>30 <?php echo $_LANG['MINUTE10']; ?></option>
                    <option value="60" <?php if ($cfg['edit_minutes'] == 60) { echo 'selected="selected"'; } ?>>1 <?php echo $_LANG['HOUR1']; ?></option>
                </select>
                <div class="help-block"><?php echo $_LANG['AD_EDIT_DELIT_TIME']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORUM_MIN_KARMA_ADD']; ?></label>
                <input type="number" class="form-control" name="min_karma_add" value="<?php echo $cfg['min_karma_add'];?>" size="5" min="0" />
            </div>
        </div>
        
        <div id="tab_seo">
            <div class="form-group">
                <label><?php echo $_LANG['AD_ROOT_METAKEYS']; ?></label>
                <textarea class="form-control" name="meta_keys" rows="2"><?php echo $cfg['meta_keys']; ?></textarea>
                <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_ROOT_METADESC']; ?></label>
                <textarea class="form-control" name="meta_desc" rows="4"><?php echo $cfg['meta_desc'] ?></textarea>
                <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
            </div>
        </div>
    </div>

    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';"/>
    </div>
</form>
<?php
}

if ($opt == 'list_ranks') {
    cpAddPathway($_LANG['AD_RANKS_FORUM']);
?>
<form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id;?>" method="post" name="addform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:500px;">
        <div class="form-group">
            <div class="col-sm-8">
                <label><?php echo $_LANG['AD_RANKS_FORUM_MODER']; ?></label>
            </div>
            <div class="col-sm-4 btn-group" data-toggle="buttons">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'modrank', false)) { echo 'active'; } ?>">
                    <input type="radio" name="modrank" <?php if(cmsCore::getArrVal($cfg, 'modrank', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'modrank', false)) { echo 'active'; } ?>">
                    <input type="radio" name="modrank" <?php if (!cmsCore::getArrVal($cfg, 'modrank', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div style="clear:both;"></div>
        
        <div class="form-group" style="margin-top:20px;">
            <div class="col-sm-8">
                <label><?php echo $_LANG['AD_RANKS']; ?></label>
            </div>
            <div class="col-sm-4">
                <label><?php echo $_LANG['AD_NUMBER_POSTS']; ?></label>
            </div>
        </div>
        
        <?php for ($r = 1; $r <= 10; $r++) { ?>
        <div class="form-group">
            <div class="col-sm-8">
                <input type="text" class="form-control" name="rank[<?php echo $r?>][title]" style="width:250px;" value="<?php echo htmlspecialchars($cfg['ranks'][$r]['title']) ?>" />
            </div>
            <div class="col-sm-4">
                <input type="number" class="form-control" name="rank[<?php echo $r?>][msg]" min="0" value="<?php echo htmlspecialchars($cfg['ranks'][$r]['msg']) ?>" />
            </div>
        </div>
        <?php } ?>
    </div>
    
    <div style="clear:both;"></div>
    
    <div style="margin-top:20px;">
        <input type="hidden" name="opt" value="saveranks" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>';"/>
    </div>
</form>
<?php
}


if ($opt == 'show_cat') {
    $item_id = cmsCore::request('item_id', 'int');
    if (!empty($item_id)) {
        $sql = "UPDATE cms_forum_cats SET published = 1 WHERE id = ". $item_id;
        cmsCore::c('db')->query($sql);
        cmsCore::halt('1');
    }
}

if ($opt == 'hide_cat') {
    $item_id = cmsCore::request('item_id', 'int');
    if (!empty($item_id)) {
        $sql = "UPDATE cms_forum_cats SET published = 0 WHERE id = ". $item_id;
        cmsCore::c('db')->query($sql);
        cmsCore::halt('1');
    }
}

if ($opt == 'submit_cat') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cat['title']     = cmsCore::request('title', 'str', 'NO_TITLE');
    $cat['pagetitle'] = cmsCore::request('pagetitle', 'str', '');
    $cat['meta_keys'] = cmsCore::request('meta_keys', 'str', '');
    $cat['meta_desc'] = cmsCore::request('meta_desc', 'str', '');
    $cat['published'] = cmsCore::request('published', 'int');
    $cat['ordering']  = cmsCore::request('ordering', 'int');
    $cat['seolink']   = $model->getCatSeoLink($cat['title']);

    cmsCore::c('db')->insert('cms_forum_cats', $cat);

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'info');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_cats');
}

if ($opt == 'delete_cat') {
    $item_id = cmsCore::request('item_id', 'int');
    cmsCore::c('db')->query("UPDATE cms_forums SET category_id = 0, published = 0  WHERE category_id = '". $item_id ."'");
    cmsCore::c('db')->query("DELETE FROM cms_forum_cats WHERE id = '". $item_id ."'");

    cmsCore::addSessionMessage($_LANG['AD_CATEGORY_REMOVED'], 'info');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_cats');
}

if ($opt == 'update_cat') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item_id = cmsCore::request('item_id', 'int');

    $cat['title']     = cmsCore::request('title', 'str', 'NO_TITLE');
    $cat['pagetitle'] = cmsCore::request('pagetitle', 'str', '');
    $cat['meta_keys'] = cmsCore::request('meta_keys', 'str', '');
    $cat['meta_desc'] = cmsCore::request('meta_desc', 'str', '');
    $cat['published'] = cmsCore::request('published', 'int');
    $cat['ordering']  = cmsCore::request('ordering', 'int');
    $cat['seolink']   = $model->getCatSeoLink($cat['title'], $item_id);

    cmsCore::c('db')->update('cms_forum_cats', $cat, $item_id);
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'info');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_cats');
}

if ($opt == 'list_cats') {
    cpAddPathway($_LANG['AD_FORUMS_CATS']);
    echo '<h3>'. $_LANG['AD_FORUMS_CATS'] .'</h3>';
    
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%' ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_cat' )
    );

    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%' ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_DELETE_CATEGORY'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_cat&item_id=%id%' )
    );

    cpListTable('cms_forum_cats', $fields, $actions);
}

if ($opt == 'list_forums') {
    echo '<h3>'. $_LANG['AD_FORUMS'] .'</h3>';
    
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_forum&item_id=%id%', 'filter' => '15' ),
        array( 'title' => $_LANG['AD_TOPICS'], 'field' => 'thread_count', 'width' => '60'),
        array( 'title' => $_LANG['AD_FORUM_MESSAGES'], 'field' => 'post_count', 'width' => '90' ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '60', 'do' => 'opt', 'do_suffix' => '_forum' ),
        array( 'title' => $_LANG['AD_CATEGORY'], 'field' => 'category_id', 'width' => '150', 'prc' => 'cpForumCatById', 'filter' => '1', 'filterlist' => cpGetList('cms_forum_cats'))
    );

    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_forum&item_id=%id%' ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_FORUM_DELETE'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_forum&item_id=%id%' )
    );

    cpListTable('cms_forums', $fields, $actions, 'parent_id>0', 'NSLeft');
}

if ($opt == 'add_cat' || $opt == 'edit_cat') {
    if ($opt == 'add_cat') {
         echo '<h3>'. $_LANG['AD_CREATE_CATEGORY'] .'</h3>';
         cpAddPathway($_LANG['AD_CREATE_CATEGORY']);
         $mod = array( 'published' => 1, 'ordering' => (int)cmsCore::c('db')->get_field('cms_forum_cats', '1=1 ORDER BY ordering DESC', 'ordering')+1 );
    } else {
        $mod = $model->getForumCat(cmsCore::request('item_id', 'int', 0));
        if (!$mod) { cmsCore::error404(); }
        
        cpAddPathway($_LANG['AD_EDIT_CATEGORY']);
        echo '<h3>'. $_LANG['AD_EDIT_CATEGORY'] .'</h3>';
    }
?>
<form id="addform" name="addform" method="post" action="index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:550px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_CATEGORY_NAME']; ?>:</label>
            <input type="text" class="form-control" name="title" size="30" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', '')); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_CATEGORY_POST']; ?>?</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SERIAL_NUMBER']; ?>:</label>
            <input type="number" class="form-control" name="ordering" size="30" value="<?php echo cmsCore::getArrVal($mod, 'ordering', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_PAGETITLE']; ?>:</label>
            <textarea class="form-control" name="pagetitle" rows="2"><?php echo cmsCore::getArrVal($mod, 'pagetitle', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_PAGETITLE_HINT']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METAKEYS']; ?>:</label>
            <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($mod, 'meta_keys', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METADESCR']; ?>:</label>
            <textarea class="form-control" name="meta_desc" rows="4"><?php echo cmsCore::getArrVal($mod, 'meta_desc', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
        </div>
    </div>
    
    <div>
        <input type="hidden" id="opt" name="opt" value="<?php if ($opt == 'add_cat') { echo 'submit_cat'; } else { echo 'update_cat'; } ?>" />
        
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';" />
        <?php
            if ($opt == 'edit_cat') {
                echo '<input type="hidden" name="item_id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
<?php
}

if ($opt == 'add_forum' || $opt == 'edit_forum') {
    if ($opt == 'add_forum') {
         echo '<h3>'. $_LANG['AD_FORUM_NEW'] .'</h3>';
         cpAddPathway($_LANG['AD_FORUM_NEW']);
         $mod = array('published' => 1);
    } else {
        if (cmsCore::inRequest('multiple')) {
            if (cmsCore::inRequest('item')) {
                $_SESSION['editlist'] = cmsCore::request('item', 'array_int', array());
            } else {
                cmsCore::addSessionMessage($_LANG['AD_NO_SELECT_OBJECTS'], 'error');
                cmsCore::redirectBack();
            }
        }

        $ostatok = '';

        if (isset($_SESSION['editlist'])) {
           $item_id = array_shift($_SESSION['editlist']);
            if (sizeof($_SESSION['editlist']) == 0) {
               unset($_SESSION['editlist']);
            } else {
                $ostatok = '('. $_LANG['AD_NEXT_IN'] . count($_SESSION['editlist']) .')';
            }
        } else {
            $item_id = cmsCore::request('item_id', 'int', 0);
        }

        $mod = $model->getForum($item_id);
        if (!$mod){ cmsCore::error404(); }

        echo '<h3>'. $mod['title'] .' '. $ostatok .'</h3>';
        cpAddPathway($mod['title']);
    }
?>
<form action="index.php?view=components&do=config&id=<?php echo $id; ?>" method="post" name="addform" id="addform" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_TITLE']; ?>:</label>
            <input type="text" class="form-control" name="title" size="30" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', '')); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_DESCR']; ?>:</label>
            <textarea class="form-control" name="description" cols="35" rows="2"><?php echo cmsCore::getArrVal($mod, 'description', ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_POST']; ?>?</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_PARENTS']; ?>:</label>
            <?php $rootid = cmsCore::c('db')->get_field('cms_forums', 'parent_id=0', 'id'); ?>
            <select id="parent_id" class="form-control" name="parent_id">
                <option value="<?php echo $rootid?>" <?php if ($mod['parent_id']==$rootid || !isset($mod['parent_id'])) { echo 'selected'; }?>><?php echo $_LANG['AD_FORUM_SQUARE']; ?> </option>
            <?php
                echo $inCore->getListItemsNS('cms_forums', cmsCore::getArrVal($mod, 'parent_id', 0));
            ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_CATEGORY']; ?>:</label>
            <?php $rootid = cmsCore::c('db')->get_field('cms_forums', 'parent_id=0', 'id'); ?>
            <select id="category_id" class="form-control" name="category_id">
            <?php
                echo $inCore->getListItems('cms_forum_cats', cmsCore::getArrVal($mod, 'category_id', cmsCore::request('addto', 'int', 0)), 'ordering');
            ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_GROUP']; ?>:</label>
            <?php
            $groups = cmsUser::getGroups();

            $style  = 'disabled="disabled"';
            $public = 'checked="checked"';

            if ($mod['access_list']) {
                $public = '';
                $style  = '';

                $access_list = $inCore->yamlToArray($mod['access_list']);
            }

            echo '<select id="showin" class="form-control" name="access_list[]" size="6" multiple="multiple" '. $style .'>';
            if ($groups) {
                foreach ($groups as $group) {
                    if (!$group['is_admin']) {
                        echo '<option value="'. $group['id'] .'"';
                        if ($access_list) {
                            if (in_array($group['id'], $access_list)) {
                                echo 'selected="selected"';
                            }
                        }
                        echo '>';
                        echo $group['title'] .'</option>';
                    }
                }

            }
            echo '</select>';
            ?>
            
            <label><input type="checkbox" id="is_access" name="is_access" onclick="checkAccesList()" value="1" <?php echo $public; ?> /> <?php echo $_LANG['AD_ALL_GROUPS']; ?></label>
            
            <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?>.</div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_MODERATORS']; ?>:</label>
            <?php
            if ($mod['moder_list']) {
                $public = '';
                $style  = '';

                $moder_list = $inCore->yamlToArray($mod['moder_list']);
                if ($moder_list) {
                    $moder_list = cmsUser::getAuthorsList($moder_list, $moder_list);
                }
            }

            echo '<select id="users_list" class="form-control" name="users_list">';
            echo cmsUser::getUsersList();
            echo '</select> <div><a class="ajaxlink" href="javascript:" onclick="addModer()">'. $_LANG['AD_ADD_SELECTED'] .'</a></div>';
            ?>

            <select id="moder_list" class="form-control" name="moder_list[]" size="8" multiple>
                <?php if ($moder_list) { echo $moder_list; } ?>
            </select>  <div><a class="ajaxlink" href="javascript:" onclick="deleteModer()"><?php echo $_LANG['AD_DELETE_SELECTED']; ?></a></div>
            <div class="help-block"><?php echo $_LANG['AD_FORUM_HINT']; ?>.</div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_ICON']; ?>:</label>
            <?php if ($mod['icon']) { ?>
                <div style="text-align:center;"><img src="/upload/forum/cat_icons/<?php echo $mod['icon']; ?>" border="0" /></div>
            <?php } ?>
            <input type="file" class="form-control" name="Filedata" />
            <div class="help-block"><?php echo $_LANG['AD_FORUM_ICON_HINT']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_COST_CREATING']; ?> (<?php echo $_LANG['BILLING_POINT10']; ?>):</label>
            <?php if (IS_BILLING) { ?>
                <input type="text" class="form-control" name="topic_cost" value="<?php echo $mod['topic_cost']; ?>" />
            <?php } else { ?>
                <?php echo $_LANG['AD_REGUIRED']; ?> &laquo;<a href="http://www.instantcms.ru/billing/about.html"><?php echo $_LANG['AD_BILLING_USERS']; ?></a>&raquo;
            <?php } ?>
            <div class="help-block">0 &mdash; <?php echo $_LANG['AD_COST_FREE']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_PAGETITLE']; ?>:</label>
            <textarea class="form-control" name="pagetitle" rows="2"><?php echo cmsCore::getArrVal($mod, 'pagetitle', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_PAGETITLE_HINT']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METAKEYS']; ?>:</label>
            <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($mod, 'meta_keys', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METADESCR']; ?>:</label>
            <textarea class="form-control" name="meta_desc" rows="4"><?php echo cmsCore::getArrVal($mod, 'meta_desc', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
        </div>
    </div>
    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';" />
        
        <input type="hidden" name="opt" value="<?php if ($opt == 'add_forum') { echo 'submit_forum'; } else { echo 'update_forum'; } ?>" />
        <?php
        if ($opt == 'edit_forum') {
            echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />';
        }
        ?>
    </div>
</form>
<script type="text/javascript">
    $().ready(function() {
        $("#addform").submit(function() {
              $('#moder_list').each(function(){
                  $('#moder_list option').prop("selected", true);
              });
        });
    });
    function deleteModer(){
        $('#moder_list option:selected').each(function () {
            $(this).remove();
        });
    }
    function addModer(){
        $('#users_list option:selected').each(function () {
            $(this).appendTo('#moder_list');
        });
    }
    function checkAccesList(){
        if (document.addform.is_access.checked) {
            $('select#showin').prop('disabled', true);
        } else {
            $('select#showin').prop('disabled', false);
        }
    }
</script>
<?php
}
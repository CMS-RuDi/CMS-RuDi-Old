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

function cpBlogOwner($item) {
    if ($item['owner'] == 'user') {
        $nickname = cmsCore::c('db')->get_field('cms_users', "id='{$item['user_id']}'", 'nickname');
        $link = '<a href="?view=users&do=edit&id='.$item['user_id'].'" class="user_link" target="_blank">
                 '.$nickname.'
                 </a>';
    } else {
        $title = cmsCore::c('db')->get_field('cms_clubs', "id='{$item['user_id']}'", 'title');
        $link = '<a href="?view=components&do=config&link=clubs&opt=edit&item_id='.$item['user_id'].'" class="club_link" target="_blank">'.$title.'</a>';
    }
    return $link;
}
/******************************************************************************/

$opt = cmsCore::request('opt', 'str', 'list_blogs');

$cfg = $inCore->loadComponentConfig('blogs');

cmsCore::loadModel('blogs');
$model = new cms_model_blogs();

cmsCore::loadClass('blog');
$inBlog = cmsBlogs::getInstance();

/******************************************************************************/

if ($opt == 'list_blogs') {
    $toolmenu = array(
        array( 'icon' => 'listblogs.gif', 'title' => $_LANG['AD_BLOGS'], 'link'=>'?view=components&do=config&link=blogs&opt=list_blogs'),
        array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&link=blogs&opt=edit_blog&multiple=1');"),
        array( 'icon' => 'delete.gif', 'title' => $_LANG['AD_DELETE_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&link=blogs&opt=delete_blog&multiple=1');"),
        array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&link=blogs&opt=config')
    );

    cpToolMenu($toolmenu);

    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['AD_CREATED'], 'field' => 'pubdate', 'width' => '80', 'filter' => 15, 'fdate' => '%d/%m/%Y' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'filter' => 15, 'link' => '?view=components&do=config&link=blogs&opt=edit_blog&item_id=%id%' ),
        array( 'title' => $_LANG['AD_OWNER'], 'field' => array('id','owner','user_id'), 'width' => '300', 'prc' => 'cpBlogOwner' )
    );
    
    $actions = array(
        array( 'title' => $_LANG['AD_RENAME'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&link=blogs&opt=edit_blog&item_id=%id%'),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_IF_BLOG_DELETE'], 'link' => '?view=components&do=config&link=blogs&opt=delete_blog&item_id=%id%')
    );

    cpListTable('cms_blogs', $fields, $actions, '', 'pubdate DESC');
}

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg['perpage']             = cmsCore::request('perpage', 'int', 0);
    $cfg['perpage_blog']        = cmsCore::request('perpage_blog', 'int', 0);
    $cfg['update_date']         = cmsCore::request('update_date', 'int', 0);
    $cfg['update_seo_link']     = cmsCore::request('update_seo_link', 'int', 0);
    $cfg['min_karma_private']   = cmsCore::request('min_karma_private', 'int', 0);
    $cfg['min_karma_public']    = cmsCore::request('min_karma_public', 'int', 0);
    $cfg['min_karma']           = cmsCore::request('min_karma', 'int', 0);
    $cfg['list_min_rating']     = cmsCore::request('list_min_rating', 'int', 0);
    $cfg['watermark']           = cmsCore::request('watermark', 'int', 0);
    $cfg['img_on']              = cmsCore::request('img_on', 'int', 0);
    $cfg['update_seo_link_blog']= cmsCore::request('update_seo_link_blog', 'int', 0);
    $cfg['meta_keys']           = cmsCore::request('meta_keys', 'str', '');
    $cfg['meta_desc']           = cmsCore::request('meta_desc', 'str', '');
    $cfg['seo_user_access']     = cmsCore::request('seo_user_access', 'int', 0);

    $inCore->saveComponentConfig('blogs', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'delete_blog') {
    if (!isset($_REQUEST['item'])) {
        $inBlog->deleteBlog(cmsCore::request('item_id', 'int', 0));
    } else {
        $inBlog->deleteBlogs(cmsCore::request('item', 'array_int', array()));
    }
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'update_blog') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $blog = $inBlog->getBlog(cmsCore::request('item_id', 'int', 0));
    if (!$blog) { cmsCore::error404(); }

    $title = cmsCore::request('title', 'str', $blog['title']);

    $seolink_new = $inBlog->updateBlog($blog['id'], array('title'=>$title), true);

    $blog['seolink'] = $seolink_new ? $seolink_new : $blog['seolink'];

    if (stripslashes($title) != $blog['title']) {
        cmsActions::updateLog( 'add_post', array( 'target' => $title, 'target_url' => $model->getBlogURL($blog['seolink']) ), 0, $blog['id'] );
        cmsActions::updateLog( 'add_blog', array( 'object' => $title, 'object_url' => $model->getBlogURL($blog['seolink']) ), $blog['id'] );
    }

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] .'.  '. $_LANG['AD_SAVE_SUCCESS'], 'success');

    if (empty($_SESSION['editlist'])) {
        cmsCore::redirect('?view=components&do=config&link=blogs&opt=list_blogs');
    } else {
        cmsCore::redirect('?view=components&do=config&link=blogs&opt=edit_blog');
    }
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);

    $toolmenu = array(
        array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.optform.submit();' ),
        array( 'icon' => 'listblogs.gif', 'title' => $_LANG['AD_BLOGS'], 'link'=>'?view=components&do=config&link=blogs&opt=list_blogs'),
        array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&link=blogs&opt=config')
    );

    cpToolMenu($toolmenu);
    
    echo '<h3>'. $_LANG['AD_SETTINGS'] .': '. $com['title'] .'</h3>';

?>
<form action="index.php?view=components&do=config&id=<?php echo $id;?>" method="post" name="optform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="uitabs">
            <ul id="tabs">
                <li><a href="#tab_blog_view"><span><?php echo $_LANG['AD_BLOG_VIEW']; ?></span></a></li>
                <li><a href="#tab_blog_photo_opt"><span><?php echo $_LANG['AD_PHOTO_OPTIONS']; ?></span></a></li>
                <li><a href="#tab_blog_edit_setup"><span><?php echo $_LANG['AD_EDIT_SETUP']; ?></span></a></li>
                <li><a href="#tab_blog_limit"><span><?php echo $_LANG['AD_LIMIT']; ?></span></a></li>
                <li><a href="#tab_blog_seo"><span>SEO</span></a></li>
            </ul>
            </ul>
            
            <div id="tab_blog_view">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_BLOG_POSTS_QUANTITY']; ?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
                    <input type="number" class="form-control" name="perpage" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'perpage', 10); ?>" />
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_BLOGS_QUANTITY']; ?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
                    <input type="number" class="form-control" name="perpage_blog" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'perpage_blog', 15); ?>" />
                </div>
            </div>
            
            <div id="tab_blog_photo_opt">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ENABLE_PHOTO_LOAD']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="img_on" <?php if (cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'active'; } ?>">
                            <input type="radio" name="img_on" <?php if (!cmsCore::getArrVal($cfg, 'img_on', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_ENABLE_WATERMARK']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if (cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_IF_ENABLE_WATERMARK']; ?>"<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</div>
                </div>
            </div>
            
            <div id="tab_blog_edit_setup">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_UPDATE_CALENDAR_DATA']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'update_date', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_date" <?php if (cmsCore::getArrVal($cfg, 'update_date', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'update_date', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_date" <?php if (!cmsCore::getArrVal($cfg, 'update_date', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_IF_ENABLE_TODAY']; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_UPDATE_BLOG_LINK']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'update_seo_link_blog', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_seo_link_blog" <?php if (cmsCore::getArrVal($cfg, 'update_seo_link_blog', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'update_seo_link_blog', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_seo_link_blog" <?php if (!cmsCore::getArrVal($cfg, 'update_seo_link_blog', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_IF_ENABLE_LINK']; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_UPDATE_TITLE_LINK']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'update_seo_link', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_seo_link" <?php if (cmsCore::getArrVal($cfg, 'update_seo_link', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'update_seo_link', false)) { echo 'active'; } ?>">
                            <input type="radio" name="update_seo_link" <?php if (!cmsCore::getArrVal($cfg, 'update_seo_link', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_IF_ENABLE_TITLE']; ?></div>
                </div>
            </div>
            
            <div id="tab_blog_limit">
                <fieldset>
                    <legend><?php echo $_LANG['AD_KARMA_LIMIT']; ?></legend>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_USE_LIMIT']; ?></label>
                        <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                            <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'active'; } ?>">
                                <input type="radio" name="min_karma" <?php if (cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                            </label>
                            <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'active'; } ?>">
                                <input type="radio" name="min_karma" <?php if (!cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                            </label>
                        </div>
                        <div class="help-block"><?php echo $_LANG['AD_IF_DISABLE_KARMA_LIMIT']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_CREATE_PERSONAL_BLOG']; ?></label>
                        <input type="number" class="form-control" name="min_karma_private" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'min_karma_private', 0); ?>" size="5" />
                        <div class="help-block"><?php echo $_LANG['AD_HOW_MANY_KARMA_P']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_CREATE_COLLECTIVE_BLOG']; ?></label>
                        <input type="number" class="form-control" name="min_karma_public" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'min_karma_public', 0); ?>" size="5" />
                        <div class="help-block"><?php echo $_LANG['AD_HOW_MANY_KARMA_C']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_RATING_MIN']; ?></label>
                        <input type="number" class="form-control" name="list_min_rating" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'list_min_rating', 0); ?>" size="5" />
                        <div class="help-block"><?php echo $_LANG['AD_POST_LIST']; ?></div>
                    </div>
                </fieldset>
            </div>
            
            <div id="tab_blog_seo">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ROOT_METAKEYS']; ?></label>
                    <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($cfg, 'meta_keys', ''); ?></textarea>
                    <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ROOT_METADESC']; ?></label>
                    <textarea class="form-control" name="meta_desc" rows="2"><?php echo cmsCore::getArrVal($cfg, 'meta_desc', ''); ?></textarea>
                    <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_USER_SEO_ACCESS']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                            <input type="radio" name="seo_user_access" <?php if (cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                            <input type="radio" name="seo_user_access" <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 10px;">
        <input name="opt" type="hidden" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
    </div>
</form>
<?php } ?>

<?php
if ($opt == 'edit_blog') {
    if (isset($_REQUEST['multiple'])) {
        if (isset($_REQUEST['item'])) {
            $_SESSION['editlist'] = cmsCore::request('item', 'array_int', array());
        } else {
            cmsCore::addSessionMessage($_LANG['AD_NO_SELECT_OBJECTS'], 'error');
            cmsCore::redirectBack();
        }
    }

    $ostatok = '';

    if (isset($_SESSION['editlist'])) {
       $item_id = array_shift($_SESSION['editlist']);
       if (sizeof($_SESSION['editlist'])==0) { unset($_SESSION['editlist']); } else
       { $ostatok = '('.$_LANG['AD_NEXT_IN'].sizeof($_SESSION['editlist']).')'; }
    } else { $item_id = cmsCore::request('item_id', 'int', 0); }

    $mod = cmsCore::c('db')->get_fields('cms_blogs', "id = '$item_id'", '*');
    if (!$mod){ cmsCore::error404(); }

    echo '<h3>'.$_LANG['AD_EDIT_BLOG'].' '.$ostatok.'</h3>';
    cpAddPathway($mod['title']);

?>
<form action="index.php?view=components&do=config&link=blogs&opt=update_blog&item_id=<?php echo $mod['id']; ?>" method="post" name="optform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_BLOG_NAME']; ?>: </label>
            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($mod['title']);?>" />
            <div class="help-block"><?php echo $_LANG['AD_CHANGE_URL']; ?></div>
        </div>
    </div>
    
    <div>
        <input name="opt" type="hidden" value="update_blog" />
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&link=blogs&opt=list_blogs';"/>
    </div>
</form>
<?php
}
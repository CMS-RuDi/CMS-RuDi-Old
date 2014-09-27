<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
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

$cfg = $inCore->loadComponentConfig('clubs');

$opt = cmsCore::request('opt', 'str', 'list');

cmsCore::loadModel('clubs');
$model = new cms_model_clubs();

if ($opt == 'list') {
    $toolmenu = array(
        array( 'icon' => 'new.gif', 'title' => $_LANG['CREATE_CLUB'], 'link' => '?view=components&do=config&id='. $id .'&opt=add' ),
        array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=show_club&multiple=1');" ),
        array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=hide_club&multiple=1');" ),
        array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=edit&multiple=1');" )
    );
}

if (in_array($opt, array('add', 'edit', 'config'))) {
    $toolmenu[] = array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' );
    $toolmenu[] = array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components&do=config&id='. $id );
}

$toolmenu[] = array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&id='. $id .'&opt=config' );

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array();
    $cfg['seo_club']           = cmsCore::request('seo_club', 'str');
    $cfg['enabled_blogs']      = cmsCore::request('enabled_blogs', 'str');
    $cfg['enabled_photos']     = cmsCore::request('enabled_photos', 'str');
    $cfg['thumb1']             = cmsCore::request('thumb1', 'int');
    $cfg['thumb2']             = cmsCore::request('thumb2', 'int');
    $cfg['thumbsqr']           = cmsCore::request('thumbsqr', 'int');
    $cfg['cancreate']          = cmsCore::request('cancreate', 'int');
    $cfg['perpage']            = cmsCore::request('perpage', 'int');
    $cfg['member_perpage']     = cmsCore::request('member_perpage', 'int');
    $cfg['club_perpage']       = cmsCore::request('club_perpage', 'int');
    $cfg['wall_perpage']       = cmsCore::request('wall_perpage', 'int');
    $cfg['club_album_perpage'] = cmsCore::request('club_album_perpage', 'int');
    $cfg['posts_perpage']      = cmsCore::request('posts_perpage', 'int');
    $cfg['club_posts_perpage'] = cmsCore::request('club_posts_perpage', 'int');
    $cfg['photo_perpage']      = cmsCore::request('photo_perpage', 'int');
    $cfg['create_min_karma']   = cmsCore::request('create_min_karma', 'int');
    $cfg['create_min_rating']  = cmsCore::request('create_min_rating', 'int');
    $cfg['notify_in']          = cmsCore::request('notify_in', 'int');
    $cfg['notify_out']         = cmsCore::request('notify_out', 'int');
    $cfg['every_karma']        = cmsCore::request('every_karma', 'int', 100);
    $cfg['photo_watermark']    = cmsCore::request('photo_watermark', 'int', 0);
    $cfg['photo_thumb_small']  = cmsCore::request('photo_thumb_small', 'int', 96);
    $cfg['photo_thumbsqr']     = cmsCore::request('photo_thumbsqr', 'int', 0);
    $cfg['photo_thumb_medium'] = cmsCore::request('photo_thumb_medium', 'int', 450);
    $cfg['photo_maxcols']      = cmsCore::request('photo_maxcols', 'int', 4);

    $inCore->saveComponentConfig('clubs', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'show_club') {
    if (!cmsCore::inRequest('item')) {
        if (cmsCore::inRequest('item_id')) {
            cmsCore::c('db')->setFlag('cms_clubs', cmsCore::request('item_id', 'int', 0), 'published', '1');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_clubs', cmsCore::request('item', 'array_int'), 'published', '1');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_club') {
    if (!cmsCore::inRequest('item')) {
        if (cmsCore::inRequest('item_id')) {
            cmsCore::c('db')->setFlag('cms_clubs', cmsCore::request('item_id', 'int', 0), 'published', '0');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_clubs', cmsCore::request('item', 'array_int'), 'published', '0');
        cmsCore::redirectBack();
    }
}

if ($opt == 'submit') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $title          = cmsCore::request('title', 'str', 'NO_TITLE');
    $description    = cmsCore::c('db')->escape_string(cmsCore::request('description', 'html', ''));
    $published      = cmsCore::request('published', 'int');
    $admin_id       = cmsCore::request('admin_id', 'int');
    $clubtype       = cmsCore::request('clubtype', 'str');
    $maxsize        = cmsCore::request('maxsize', 'int');
    $enabled_blogs  = cmsCore::request('enabled_blogs', 'int');
    $enabled_photos = cmsCore::request('enabled_photos', 'int');

    $date = explode('.', cmsCore::request('pubdate', 'str'));
    $pubdate = (int)$date[2] .'-'. (int)$date[1] .'-'. (int)$date[0];

    $new_imageurl = $model->uploadClubImage();
    $filename = !empty($new_imageurl['filename']) ? $new_imageurl['filename'] : '';

    $model->addClub(array(
        'admin_id' => $admin_id,
        'title' => $title,
        'description' => $description,
        'imageurl' => $filename,
        'pubdate' => $pubdate,
        'clubtype' => $clubtype,
        'published' => $published,
        'maxsize' => $maxsize,
        'create_karma' => cmsUser::getKarma($admin_id),
        'enabled_blogs' => $enabled_blogs,
        'enabled_photos' => $enabled_photos
    ));

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('index.php?view=components&do=config&opt=list&id='.$id);
}

if ($opt == 'update') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item_id = cmsCore::request('item_id', 'int', 0);

    $new_club['title']          = cmsCore::request('title', 'str', 'NO_TITLE');
    $new_club['description']    = cmsCore::c('db')->escape_string(cmsCore::request('description', 'html', ''));
    $new_club['published']      = cmsCore::request('published', 'int');
    $new_club['admin_id']       = cmsCore::request('admin_id', 'int');
    $new_club['clubtype']       = cmsCore::request('clubtype', 'str');
    $new_club['maxsize']        = cmsCore::request('maxsize', 'int');
    $new_club['enabled_blogs']	= cmsCore::request('enabled_blogs', 'int');
    $new_club['enabled_photos']	= cmsCore::request('enabled_photos', 'int');

    $olddate = cmsCore::request('olddate', 'str');
    $pubdate = cmsCore::request('pubdate', 'str');

    $club = $model->getClub($item_id);
    if (!$club) { cmsCore::error404(); }

    if ($olddate != $pubdate) {
        $date = explode('.', $pubdate);
        $new_club['pubdate'] = (int)$date[2] .'-'. (int)$date[1] .'-'. (int)$date[0];
    }

    $new_imageurl = $model->uploadClubImage($club['imageurl']);
    $new_club['imageurl'] = !empty($new_imageurl['filename']) ? $new_imageurl['filename'] : $club['imageurl'];

    $model->updateClub($item_id, $new_club);

    cmsCore::addSessionMessage($_LANG['CONFIG_SAVE_OK'], 'success');

    if (empty($_SESSION['editlist'])) {
        cmsCore::redirect('index.php?view=components&do=config&id='. $id .'&opt=list');
    } else {
        cmsCore::redirect('index.php?view=components&do=config&id='. $id .'&opt=edit');
    }
}

if ($opt == 'delete') {
    $model->deleteClub(cmsCore::request('item_id', 'int', 0));
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('index.php?view=components&do=config&id='. $id .'&opt=list');
}

cpToolMenu($toolmenu);

if ($opt == 'list') {
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40'),
        array( 'title' => $_LANG['DATE'], 'field' => 'pubdate', 'width' => '100', 'filter' => '15', 'fdate' => '%d/%m/%Y'),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'filter' => '15', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%'),
        array( 'title' => $_LANG['CLUB_TYPE'], 'field' => 'clubtype', 'width' => '100'),
        array( 'title' => $_LANG['MEMBERS'], 'field' => 'members_count', 'width' => '100'),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_club')
    );
    
    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%'),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_DELETE_CLUB'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete&item_id=%id%')
    );

    cpListTable('cms_clubs', $fields, $actions, '', 'pubdate DESC');
}

if ($opt == 'add' || $opt == 'edit') {
    if ($opt=='add') {
        echo '<h3>'. $_LANG['CREATE_CLUB'] .'</h3>';
        cpAddPathway($_LANG['CREATE_CLUB'] );
        $mod = array();
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
                $ostatok = '('. $_LANG['AD_NEXT_IN'] . sizeof($_SESSION['editlist']) .')';
            }
        } else {
            $item_id = cmsCore::request('item_id', 'int', 0);
        }

        $mod = $model->getClub($item_id);
        if (!$mod) { cmsCore::error404(); }

        echo '<h3>'. $mod['title'] .' '. $ostatok .'</h3>';
        cpAddPathway($mod['title']);
    }

    if (!isset($mod['maxsize'])) { $mod['maxsize'] = 0; }
    if (!isset($mod['admin_id'])) { $mod['admin_id'] = cmsCore::c('user')->id; }
    if (!isset($mod['clubtype'])) { $mod['clubtype'] = 'public'; }
?>
<form id="addform" class="form-horizontal" role="form" name="addform" action="index.php?view=components&amp;do=config&amp;id=<?php echo $id;?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div class="uitabs" style="width:600px;">
        <ul>
            <li><a href="#tab_ad_overall"><?php echo $_LANG['AD_OVERALL']; ?></a></li>
            <li><a href="#tab_club_desc"><?php echo $_LANG['CLUB_DESC']; ?></a></li>
            <li><a href="#tab_access"><?php echo $_LANG['AD_TAB_ACCESS']; ?></a></li>
        </ul>
        
        <div id="tab_ad_overall">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_NAME'];?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', '')); ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['UPLOAD_LOGO'];?></label>
                <div class="col-sm-7">
                    <?php if (cmsCore::getArrVal($mod, 'imageurl', false)) {
                        echo '<div style="margin-bottom:5px;text-align:center;"><img src="/images/clubs/small/'. $mod['imageurl'] .'" /></div>';
                    } ?>
                    <input type="file" class="form-control" name="picture" size="33" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['MAX_MEMBERS'];?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="maxsize" value="<?php echo cmsCore::getArrVal($mod, 'maxsize', ''); ?>"/>
                    <div class="help-block"><?php echo $_LANG['MAX_MEMBERS_TEXT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CLUB_DATE'];?></label>
                <div class="col-sm-7">
                    <input type="text" id="pubdate" class="form-control" style="display:inline-block;width:auto;" name="pubdate" value="<?php if (!cmsCore::getArrVal($mod, 'pubdate', false)) { echo date('d.m.Y'); } else { echo date('d.m.Y', strtotime($mod['pubdate'])); } ?>" />
                    <input type="hidden" name="olddate" value="<?php echo date('d.m.Y', strtotime(cmsCore::getArrVal($mod, 'pubdate', 0)))?>"/>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PUBLISH_CLUB'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                        <input type="radio" name="published" <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                        <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_PUBLISH_CLUB_HINT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_BLOG'];?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="enabled_blogs">
                        <option value="-1" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_blogs', false) == '-1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DEFAULT']; ?></option>
                        <option value="1" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_blogs', false) == '1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_IS_ENABLED']; ?></option>
                        <option value="0" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_blogs', false) == '0') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_IS_DISABLED']; ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_PHOTOALBUMS'];?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="enabled_photos">
                        <option value="-1" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_photos', false) == '-1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DEFAULT']; ?></option>
                        <option value="1" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_photos', false) == '1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_IS_ENABLED']; ?></option>
                        <option value="0" <?php if (cmsCore::getArrVal($mod, 'orig_enabled_photos', false) == '0') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_IS_DISABLED']; ?></option>
                    </select>
                </div>
            </div>
        </div>
        
        <div id="tab_club_desc">
            <div class="form-group">
                <?php $inCore->insertEditor('description', $mod['description'], '400', '100%'); ?>
            </div>
        </div>
        
        <div id="tab_access">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_ADMIN'];?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="admin_id">
                        <?php
                            echo $inCore->getListItems('cms_users', cmsCore::getArrVal($mod, 'admin_id', 0), 'nickname', 'ASC', 'is_deleted=0 AND is_locked=0', 'id', 'nickname');
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_TYPE'];?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="clubtype">
                        <option value="public" <?php if (cmsCore::getArrVal($mod, 'clubtype', false) == 'public') { echo 'selected="selected"'; } ?>><?php echo $_LANG['PUBLIC']; ?></option>
                        <option value="private" <?php if (cmsCore::getArrVal($mod, 'clubtype', false) == 'private') { echo 'selected="selected"'; } ?>><?php echo $_LANG['PRIVATE']; ?></option>
                    </select>
                </div>
            </div>
            
            <?php if($opt == 'edit'){ ?>
                <p><?php echo $_LANG['AD_MEMBERS_EDIT_ON_SITE']; ?> <a target="_blank" href="/clubs/<?php echo $mod['id']; ?>/config.html#moders"><?php echo $_LANG['AD_EDIT_ON_SITE']; ?></a>.</p>
            <?php } ?>
        </div>
    </div>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
        <input type="hidden" name="opt" value="<?php if ($opt == 'add') { echo 'submit'; } else { echo 'update'; } ?>" />
        <?php
        if ($opt == 'edit') {
            echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />';
        }
        ?>
    </div>
</form>
<?php
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);
?>
<form id="addform" name="addform" class="form-horizontal" role="form" action="index.php?view=components&do=config&id=<?php echo $id;?>" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:650px;" class="uitabs">
        <ul>
            <li><a href="#basic"><span><?php echo $_LANG['AD_OVERALL']; ?></span></a></li>
            <li><a href="#limits"><span><?php echo $_LANG['AD_LISTS_LIMIT']; ?></span></a></li>
            <li><a href="#photos"><span><?php echo $_LANG['AD_FOTO']; ?></span></a></li>
            <li><a href="#restrict"><span><?php echo $_LANG['LIMITS']; ?></span></a></li>
        </ul>

        <div id="basic">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SEO_FOR_CLUB'];?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="seo_club">
                        <option value="deskr" <?php if ($cfg['seo_club'] == 'deskr') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_SEO_FOR_CLUB_DESCR']; ?></option>
                        <option value="title" <?php if ($cfg['seo_club'] == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_SEO_FOR_CLUB_TITLE']; ?></option>
                        <option value="def" <?php if ($cfg['seo_club'] == 'def') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_SEO_FOR_CLUB_DEFAULT']; ?></option>
                    </select>
                    <div class="help-block"><?php echo $_LANG['AD_SEO_FOR_CLUB_HINT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_BLOG'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'enabled_blogs', false)) { echo 'active'; } ?>">
                        <input type="radio" name="enabled_blogs" <?php if(cmsCore::getArrVal($cfg, 'enabled_blogs', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'enabled_blogs', false)) { echo 'active'; } ?>">
                        <input type="radio" name="enabled_blogs" <?php if (!cmsCore::getArrVal($cfg, 'enabled_blogs', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_LOGO_SMALL_WIDTH'];?> (<?php echo $_LANG['AD_PX']; ?>)</label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="thumb1" value="<?php echo $cfg['thumb1']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_LOGO_MEDIUM_WIDTH'];?> (<?php echo $_LANG['AD_PX']; ?>)</label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="thumb2" value="<?php echo $cfg['thumb2']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SQUARE_LOGO'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'thumbsqr', false)) { echo 'active'; } ?>">
                        <input type="radio" name="thumbsqr" <?php if(cmsCore::getArrVal($cfg, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'thumbsqr', false)) { echo 'active'; } ?>">
                        <input type="radio" name="thumbsqr" <?php if (!cmsCore::getArrVal($cfg, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_NOTIFICATION_IN'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'notify_in', false)) { echo 'active'; } ?>">
                        <input type="radio" name="notify_in" <?php if(cmsCore::getArrVal($cfg, 'notify_in', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'notify_in', false)) { echo 'active'; } ?>">
                        <input type="radio" name="notify_in" <?php if (!cmsCore::getArrVal($cfg, 'notify_in', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_NOTIFICATION_IN_HINT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_NOTIFICATION_OUT'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'notify_out', false)) { echo 'active'; } ?>">
                        <input type="radio" name="notify_out" <?php if(cmsCore::getArrVal($cfg, 'notify_out', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'notify_out', false)) { echo 'active'; } ?>">
                        <input type="radio" name="notify_out" <?php if (!cmsCore::getArrVal($cfg, 'notify_out', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_NOTIFICATION_OUT_HINT']; ?></div>
                </div>
            </div>
        </div>
        
        <div id="limits">
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_CLUB_COUNT'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="perpage" value="<?php echo $cfg['perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_MEMBER_COUNT_CLUB_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="club_perpage" value="<?php echo $cfg['club_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_MEMBER_COUNT_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="member_perpage" value="<?php echo $cfg['member_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_RECORDS_COUNT'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="wall_perpage" value="<?php echo $cfg['wall_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_POST_COUNT_CLUB_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="club_posts_perpage" value="<?php echo $cfg['club_posts_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_POST_COUNT_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="posts_perpage" value="<?php echo $cfg['posts_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_ALBUM_COUNT_CLUB_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="club_album_perpage" value="<?php echo $cfg['club_album_perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-9 control-label"><?php echo $_LANG['AD_ALBUM_COUNT_PAGE'];?>:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="photo_perpage" value="<?php echo $cfg['photo_perpage']; ?>" />
                </div>
            </div>
        </div>
        
        <div id="photos">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['CLUB_PHOTOALBUMS'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'enabled_photos', false)) { echo 'active'; } ?>">
                        <input type="radio" name="enabled_photos" <?php if(cmsCore::getArrVal($cfg, 'enabled_photos', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'enabled_photos', false)) { echo 'active'; } ?>">
                        <input type="radio" name="enabled_photos" <?php if (!cmsCore::getArrVal($cfg, 'enabled_photos', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ENABLE_WATERMARK'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'photo_watermark', false)) { echo 'active'; } ?>">
                        <input type="radio" name="photo_watermark" <?php if(cmsCore::getArrVal($cfg, 'photo_watermark', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'photo_watermark', false)) { echo 'active'; } ?>">
                        <input type="radio" name="photo_watermark" <?php if (!cmsCore::getArrVal($cfg, 'photo_watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_ENABLE_WATERMARK_HINT']; ?> "<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PHOTO_SMALL_WIDTH'];?> (<?php echo $_LANG['AD_PX']; ?>)</label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="photo_thumb_small" min="0" value="<?php echo $cfg['photo_thumb_small']; ?>">

                    <label><?php echo $_LANG['AD_SQUARE_PHOTO']; ?></label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'photo_thumbsqr', false)) { echo 'active'; } ?>">
                            <input type="radio" name="photo_thumbsqr" <?php if(cmsCore::getArrVal($cfg, 'photo_thumbsqr', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'photo_thumbsqr', false)) { echo 'active'; } ?>">
                            <input type="radio" name="photo_thumbsqr" <?php if (!cmsCore::getArrVal($cfg, 'photo_thumbsqr', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PHOTO_MEDIUM_WIDTH'];?> (<?php echo $_LANG['AD_PX']; ?>)</label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="photo_thumb_medium" min="0" value="<?php echo $cfg['photo_thumb_medium']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PHOTO_MAXCOLS'];?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="photo_maxcols" min="0" value="<?php echo $cfg['photo_maxcols']; ?>" />
                </div>
            </div>
        </div>
        
        <div id="restrict">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CANCREATE'];?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'cancreate', false)) { echo 'active'; } ?>">
                        <input type="radio" name="cancreate" <?php if(cmsCore::getArrVal($cfg, 'cancreate', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'cancreate', false)) { echo 'active'; } ?>">
                        <input type="radio" name="cancreate" <?php if (!cmsCore::getArrVal($cfg, 'photo_watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_CANCREATE_HINT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_EVERY_KARMA'];?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="every_karma" min="0" value="<?php echo $cfg['every_karma']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_EVERY_KARMA_HINT']; ?></div>
                </div>
            </div>
        
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CREATE_MIN_KARMA'];?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="create_min_karma" min="0" value="<?php echo $cfg['create_min_karma']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_CREATE_MIN_KARMA_HINT']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CREATE_MIN_RATING'];?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="create_min_rating" min="0" value="<?php echo $cfg['create_min_rating']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_CREATE_MIN_RATING_HINT']; ?></div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>'"/>
    </div>
</form>
<?php
}
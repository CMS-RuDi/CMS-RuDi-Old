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

$cfg = $inCore->loadComponentConfig('photos');

cmsCore::loadClass('photo');
cmsCore::loadModel('photos');
$model = new cms_model_photos();

$opt = cmsCore::request('opt', 'str', 'list_albums');

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array(
        'link'                => cmsCore::request('show_link', 'int', 0),
        'saveorig'            => cmsCore::request('saveorig', 'int', 0),
        'maxcols'             => cmsCore::request('maxcols', 'int', 0),
        'orderby'             => cmsCore::request('orderby', 'str', ''),
        'orderto'             => cmsCore::request('orderto', 'str', ''),
        'showlat'             => cmsCore::request('showlat', 'int', 0),
        'watermark'           => cmsCore::request('watermark', 'int', 0),
        'meta_keys'           => cmsCore::request('meta_keys', 'str', ''),
        'meta_desc'           => cmsCore::request('meta_desc', 'str', ''),
        'seo_user_access'     => cmsCore::request('seo_user_access', 'int', 0),
        'best_latest_perpage' => cmsCore::request('best_latest_perpage', 'int', 0),
        'best_latest_maxcols' => cmsCore::request('best_latest_maxcols', 'int', 0)
    );

    $inCore->saveComponentConfig('photos', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

$toolmenu = array();

if ($opt == 'list_albums') {
    $toolmenu[] = array( 'icon' => 'newfolder.gif', 'title' => $_LANG['AD_ALBUM_ADD'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_album' );
    $toolmenu[] = array( 'icon' => 'folders.gif', 'title' => $_LANG['AD_ALBUMS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_albums' );
    $toolmenu[] = array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&id='. $id .'&opt=config' );
}

if (in_array($opt, array('config', 'add_album', 'edit_album'))) {
    $toolmenu[] = array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' );
    $toolmenu[] = array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components&do=config&id='. $id );
}

cpToolMenu($toolmenu);

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);
    
    cpCheckWritable('/images/photos', 'folder');
    cpCheckWritable('/images/photos/medium', 'folder');
    cpCheckWritable('/images/photos/small', 'folder');
?>
<form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="addform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:500px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_LINKS_ORIGINAL']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'link', false)) { echo 'active'; } ?>">
                    <input type="radio" name="show_link" <?php if(cmsCore::getArrVal($cfg, 'link', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'link', false)) { echo 'active'; } ?>">
                    <input type="radio" name="show_link" <?php if (!cmsCore::getArrVal($cfg, 'link', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_RETAIN_BOOT']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'saveorig', false)) { echo 'active'; } ?>">
                    <input type="radio" name="saveorig" <?php if(cmsCore::getArrVal($cfg, 'saveorig', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'saveorig', false)) { echo 'active'; } ?>">
                    <input type="radio" name="saveorig" <?php if (!cmsCore::getArrVal($cfg, 'saveorig', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_NUMBER_COLUMS']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" id="maxcols" class="form-control" name="maxcols" min="0" value="<?php echo $cfg['maxcols']; ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_SORT']; ?>:</label>
            <select class="form-control" name="orderby">
                <option value="title" <?php if ($cfg['orderby'] == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET']; ?></option>
                <option value="pubdate" <?php if ($cfg['orderby'] == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR']; ?></option>
            </select>
            <select class="form-control" name="orderto">
                <option value="desc" <?php if ($cfg['orderto'] == 'desc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT']; ?></option>
                <option value="asc" <?php if ($cfg['orderto'] == 'asc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT']; ?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_LINKS_LATEST']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'showlat', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showlat" <?php if(cmsCore::getArrVal($cfg, 'showlat', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'showlat', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showlat" <?php if (!cmsCore::getArrVal($cfg, 'showlat', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_NUMBER']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" class="form-control" name="best_latest_perpage" min="0" value="<?php echo $cfg['best_latest_perpage']; ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_NUMBER_COLUMN']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" class="form-control" name="best_latest_maxcols" min="0" value="<?php echo $cfg['best_latest_maxcols']; ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ENABLE_WATERMARK']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                    <input type="radio" name="watermark" <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                    <input type="radio" name="watermark" <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_WATERMARK_PHOTOS_HINT']; ?> "<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ROOT_METAKEYS']; ?></label>
            <textarea class="form-control" name="meta_keys" rows="2"><?php echo $cfg['meta_keys'] ?></textarea>
            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ROOT_METADESC']; ?></label>
            <textarea class="form-control" name="meta_desc" rows="4"><?php echo $cfg['meta_desc'] ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_USER_SEO_ACCESS']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                    <input type="radio" name="seo_user_access" <?php if(cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                    <input type="radio" name="seo_user_access" <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
    </div>
    
    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';"/>
    </div>
</form>
<?php
}

if ($opt == 'show_album') {
    $item_id = cmsCore::request('item_id', 'int', 0);
    cmsCore::c('db')->query("UPDATE cms_photo_albums SET published = 1 WHERE id = '". $item_id ."'") ;
    cmsCore::halt('1');
}

if ($opt == 'hide_album') {
    $item_id = cmsCore::request('item_id', 'int', 0);
    cmsCore::c('db')->query("UPDATE cms_photo_albums SET published = 0 WHERE id = '". $item_id ."'") ;
    cmsCore::halt('1');
}

if ($opt == 'submit_album') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    $album = array(
        'title'       => cmsCore::request('title', 'str', 'NO_TITLE'),
        'description' => cmsCore::request('description', 'str'),
        'published'   => cmsCore::request('published', 'int'),
        'showdate'    => cmsCore::request('showdate', 'int'),
        'parent_id'   => cmsCore::request('parent_id', 'int'),
        'showtype'    => cmsCore::request('showtype', 'str'),
        'public'      => cmsCore::request('public', 'int'),
        'orderby'     => cmsCore::request('orderby', 'str'),
        'orderto'     => cmsCore::request('orderto', 'str'),
        'perpage'     => cmsCore::request('perpage', 'int'),
        'thumb1'      => cmsCore::request('thumb1', 'int'),
        'thumb2'      => cmsCore::request('thumb2', 'int'),
        'thumbsqr'    => cmsCore::request('thumbsqr', 'int'),
        'cssprefix'   => cmsCore::request('cssprefix', 'str'),
        'nav'         => cmsCore::request('nav', 'int'),
        'uplimit'     => cmsCore::request('uplimit', 'int'),
        'maxcols'     => cmsCore::request('maxcols', 'int'),
        'orderform'   => cmsCore::request('orderform', 'int'),
        'showtags'    => cmsCore::request('showtags', 'int'),
        'bbcode'      => cmsCore::request('bbcode', 'int'),
        'is_comments' => cmsCore::request('is_comments', 'int'),
        'meta_keys'   => cmsCore::request('meta_keys', 'str', ''),
        'meta_desc'   => cmsCore::request('meta_desc', 'str', ''),
        'pagetitle'   => cmsCore::request('pagetitle', 'str', '')
    );
    
    $album = cmsCore::callEvent('ADD_ALBUM', $album);
    
    cmsCore::c('db')->addNsCategory('cms_photo_albums', $album);
    
    cmsCore::addSessionMessage($_LANG['AD_ALBUM'].' "'.stripslashes($album['title']).'" '.$_LANG['AD_ALBUM_CREATED'], 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_albums');
}

if ($opt == 'delete_album') {
    if (cmsCore::inRequest('item_id')) {
        $album = cmsCore::c('db')->getNsCategory('cms_photo_albums', cmsCore::request('item_id', 'int', 0));
        if (!$album) {
            cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_albums');
        }
        
        cmsCore::addSessionMessage($_LANG['AD_ALBUM'] .' "'. stripslashes($album['title']) .'", '. $_LANG['AD_EMBEDED_PHOTOS_REMOVED'] .'.', 'success');
        
        cmsPhoto::getInstance()->deleteAlbum($album['id'], '', $model->initUploadClass($album));
    }
    
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_albums');
}

if ($opt == 'update_album') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    $item_id = cmsCore::request('item_id', 'int', 0);
    
    $old_album = $inDB->getNsCategory('cms_photo_albums', $item_id);
    if (!$old_album) { cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_albums'); }
    
    $album = array(
        'title'         => cmsCore::request('title', 'str', 'NO_TITLE'),
        'description'   => cmsCore::request('description', 'str', ''),
        'published'     => cmsCore::request('published', 'int'),
        'showdate'      => cmsCore::request('showdate', 'int'),
        'parent_id'     => cmsCore::request('parent_id', 'int'),
        'is_comments'   => cmsCore::request('is_comments', 'int'),
        'showtype'      => cmsCore::request('showtype', 'str'),
        'public'        => cmsCore::request('public', 'int'),
        'orderby'       => cmsCore::request('orderby', 'str'),
        'orderto'       => cmsCore::request('orderto', 'str'),
        'perpage'       => cmsCore::request('perpage', 'int'),
        'thumb1'        => cmsCore::request('thumb1', 'int'),
        'thumb2'        => cmsCore::request('thumb2', 'int'),
        'thumbsqr'      => cmsCore::request('thumbsqr', 'int'),
        'cssprefix'     => cmsCore::request('cssprefix', 'str'),
        'nav'           => cmsCore::request('nav', 'int'),
        'uplimit'       => cmsCore::request('uplimit', 'int'),
        'maxcols'       => cmsCore::request('maxcols', 'int'),
        'orderform'     => cmsCore::request('orderform', 'int'),
        'showtags'      => cmsCore::request('showtags', 'int'),
        'bbcode'        => cmsCore::request('bbcode', 'int'),
        'iconurl'       => cmsCore::request('iconurl', 'str'),
        'meta_keys'     => cmsCore::request('meta_keys', 'str', ''),
        'meta_desc'     => cmsCore::request('meta_desc', 'str', ''),
        'pagetitle'     => cmsCore::request('pagetitle', 'str', '')
    );

    // если сменили категорию
    if ($old_album['parent_id'] != $album['parent_id']) {
        // перемещаем ее в дереве
        $inCore->nestedSetsInit('cms_photo_albums')->MoveNode($item_id, $album['parent_id']);
    }
    
    cmsCore::c('db')->update('cms_photo_albums', $album, $item_id);
    cmsCore::addSessionMessage($_LANG['AD_ALBUM'] .' "'. stripslashes($album['title']) .'" '. $_LANG['AD_ALBUM_SAVED'] .'.', 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_albums');
}

if ($opt == 'list_albums') {
    echo '<h3>'. $_LANG['AD_ALBUMS'] .'</h3>';
    
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_album&item_id=%id%' ),
        array( 'title' => $_LANG['AD_ALBUM_COMMENTS'], 'field' => 'is_comments', 'width' => '100', 'prc' => 'cpYesNo' ),
        array( 'title' => $_LANG['AD_ADDING_USERS'], 'field' => 'public', 'width' => '100', 'prc' => 'cpYesNo' ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '60', 'do' => 'opt', 'do_suffix' => '_album' )
    );
    
    $actions = array(
        array( 'title' => $_LANG['AD_VIEW_ONLINE'], 'icon' => 'search.gif', 'link' => '/photos/%id%' ),
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_album&item_id=%id%' ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_ALBUM_PHOTOS_DEL'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_album&item_id=%id%' )
    );
    
    cpListTable('cms_photo_albums', $fields, $actions, 'parent_id>0 AND NSDiffer=""', 'NSLeft');
}

if ($opt == 'add_album' || $opt == 'edit_album') {
    if ($opt == 'add_album') {
        cpAddPathway($_LANG['AD_ALBUM_ADD']);
        echo '<h3>'. $_LANG['AD_ALBUM_ADD'] .'</h3>';
        $mod = array();
    } else {
        $item_id = cmsCore::request('item_id', 'int', 0);
        $mod = cmsCore::c('db')->getNsCategory('cms_photo_albums', $item_id);
        
        if (!$mod) { cmsCore::error404(); }

        cpAddPathway($_LANG['AD_ALBUM_EDIT']);
        echo '<h3>'. $_LANG['AD_ALBUM_EDIT'] .' "'. $mod['title'] .'"</h3>';
    }

    $mod = array_merge(
        array(
            'thumb1' => 96,
            'thumb2' => 450,
            'thumbsqr' => 1,
            'is_comments' => 0,
            'maxcols' => 4,
            'showtype' => 'lightbox',
            'perpage' => 20,
            'uplimit' => 20,
            'published' => 1,
            'orderby' => 'pubdate'
        ),
        $mod
    );
?>
    <script type="text/javascript">
        function showMapMarker(){
            var file = $('select[name=iconurl]').val();
            if (file) {
                $('#marker_demo').attr('src', '/images/photos/small/'+file).fadeIn();
            } else {
                $('#marker_demo').hide();
            }

        }
    </script>

<form id="addform" name="addform" method="post" action="index.php?view=components&do=config&id=<?php echo $id;?>">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_TITLE']; ?>:</label>
            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', '')); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_PARENT']; ?>:</label>
            <?php $rootid = cmsCore::c('db')->get_field('cms_photo_albums', "parent_id=0 AND NSDiffer=''", 'id'); ?>
            <select id="parent_id" class="form-control" name="parent_id" size="8">
                <option value="<?php echo $rootid; ?>" <?php if (cmsCore::getArrVal($mod, 'parent_id', $rootid) == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_ALBUM_ROOT']; ?></option>
                <?php
                    echo $inCore->getListItemsNS('cms_photo_albums', cmsCore::getArrVal($mod, 'parent_id', 0));
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_POST']; ?></label>
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
            <label><?php echo $_LANG['AD_SHOW_DATES_COMMENTS']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'showdate', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showdate" <?php if(cmsCore::getArrVal($mod, 'showdate', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'showdate', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showdate" <?php if (!cmsCore::getArrVal($mod, 'showdate', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_TAGS']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'showtags', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showtags" <?php if(cmsCore::getArrVal($mod, 'showtags', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'showtags', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showtags" <?php if (!cmsCore::getArrVal($mod, 'showtags', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_CODE_FORUM']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'bbcode', false)) { echo 'active'; } ?>">
                    <input type="radio" name="bbcode" <?php if(cmsCore::getArrVal($mod, 'bbcode', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'bbcode', false)) { echo 'active'; } ?>">
                    <input type="radio" name="bbcode" <?php if (!cmsCore::getArrVal($mod, 'bbcode', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_COMMENTS_ALBUM']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'is_comments', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comments" <?php if(cmsCore::getArrVal($mod, 'is_comments', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'is_comments', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comments" <?php if (!cmsCore::getArrVal($mod, 'is_comments', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SORT_PHOTOS']; ?>:</label>
            <select id="orderby" class="form-control" name="orderby">
                <option value="title" <?php if (cmsCore::getArrVal($mod, 'orderby') == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET']; ?></option>
                <option value="pubdate" <?php if (cmsCore::getArrVal($mod, 'orderby') == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR']; ?></option>
                <option value="rating" <?php if (cmsCore::getArrVal($mod, 'orderby') == 'rating') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_RATING']; ?></option>
                <option value="hits" <?php if (cmsCore::getArrVal($mod, 'orderby') == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS']; ?></option>
            </select>
            <select id="orderto" class="form-control" name="orderto">
                <option value="desc" <?php if (cmsCore::getArrVal($mod, 'orderto') == 'desc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT']; ?></option>
                <option value="asc" <?php if (cmsCore::getArrVal($mod, 'orderto') == 'asc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT']; ?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_OUTPUT_PHOTOS']; ?>:</label>
            <select id="showtype" class="form-control" name="showtype">
                <option value="thumb" <?php if (cmsCore::getArrVal($mod, 'showtype') == 'thumb') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_GALLERY']; ?></option>
                <option value="lightbox" <?php if (cmsCore::getArrVal($mod, 'showtype') == 'lightbox') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_GALLERY_LIGHTBOX']; ?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_NUMBER_COLUMS_PHOTOS']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" class="form-control" name="maxcols" min="0" value="<?php echo cmsCore::getArrVal($mod, 'maxcols', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ADD_PHOTOS_USERS']; ?>:</label>
            <select class="form-control" name="public">
                <option value="0" <?php if (cmsCore::getArrVal($mod, 'public') == '0') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PROCHBITED']; ?></option>
                <option value="1" <?php if (cmsCore::getArrVal($mod, 'public') == '1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_FROM_PREMODERATION']; ?></option>
                <option value="2" <?php if (cmsCore::getArrVal($mod, 'public') == '2') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_WITHOUT_PREMODERATION']; ?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_UPLOAD_MAX']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" class="form-control" name="uplimit" min="0" value="<?php echo cmsCore::getArrVal($mod, 'uplimit', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORM_SORTING']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'orderform', false)) { echo 'active'; } ?>">
                    <input type="radio" name="orderform" <?php if(cmsCore::getArrVal($mod, 'orderform', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['SHOW']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'orderform', false)) { echo 'active'; } ?>">
                    <input type="radio" name="orderform" <?php if (!cmsCore::getArrVal($mod, 'orderform', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['HIDE']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_NAVIGATTING']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'nav', false)) { echo 'active'; } ?>">
                    <input type="radio" name="nav" <?php if(cmsCore::getArrVal($mod, 'nav', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'nav', false)) { echo 'active'; } ?>">
                    <input type="radio" name="nav" <?php if (!cmsCore::getArrVal($mod, 'nav', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_CSS_PREFIX']; ?>:</label>
            <input type="text" class="form-control" name="cssprefix" size="10" value="<?php echo cmsCore::getArrVal($mod, 'cssprefix', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_PHOTOS_ON_PAGE']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
            <input type="number" class="form-control" name="perpage" min="0" value="<?php echo cmsCore::getArrVal($mod, 'perpage', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_WIDTH_SMALL_COPY']; ?> (<?php echo $_LANG['AD_PX']; ?>):</label>
            <input type="number" class="form-control" name="thumb1" min="0" value="<?php echo cmsCore::getArrVal($mod, 'thumb1', ''); ?>" />
            <label><?php echo $_LANG['AD_PHOTOS_SQUARE']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'active'; } ?>">
                    <input type="radio" name="thumbsqr" <?php if(cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'active'; } ?>">
                    <input type="radio" name="thumbsqr" <?php if (!cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_WIDTH_MIDDLE_COPY']; ?> (<?php echo $_LANG['AD_PX']; ?>):</label>
            <input type="number" class="form-control" name="thumb2" min="0" value="<?php echo cmsCore::getArrVal($mod, 'thumb2', ''); ?>" />
        </div>
        
        <?php if ($opt == 'edit_album') { ?>
            <div class="form-group">
                <label><?php echo $_LANG['AD_MINI_SKETCH']; ?>:</label>
            
                <div style="text-align:center;">
                <?php if (!empty($mod['iconurl']) && file_exists(PATH .'/images/photos/small/'. $mod['iconurl'])){ ?>
                    <img id="marker_demo" src="/images/photos/small/<?php echo $mod['iconurl']; ?>">
                <?php  } else { ?>
                    <img id="marker_demo" src="/images/photos/no_image.png" style="display: none;">
                <?php  } ?>
                </div>
                
                <?php if (cmsCore::c('db')->rows_count('cms_photo_files', 'album_id = '.$item_id.'')) { ?>
                    <select name="iconurl" id="iconurl" style="width:285px" onchange="showMapMarker()">
                        <?php
                        if (!empty($mod['iconurl']) && file_exists(PATH .'/images/photos/small/'. $mod['iconurl'])){
                            echo $inCore->getListItems('cms_photo_files', $mod['iconurl'], 'id', 'ASC', 'album_id = '. $item_id .' AND published = 1', 'file');
                        } else {
                            echo '<option value="" selected="selected">'. $_LANG['AD_MINI_SKETCH_CHOOSE'] .'</option>';
                            echo $inCore->getListItems('cms_photo_files', '', 'id', 'ASC', 'album_id = '. $item_id .' AND published = 1', 'file');
                        }
                        ?>
                    </select>
                <?php  } else { ?>
                    <div><?php echo $_LANG['AD_ALBUM_NO_PHOTOS']; ?>.</div>
                <?php  } ?>
            </div>
        <?php } ?>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALBUM_DESCR']; ?>:</label>
            <textarea class="form-control" name="description" rows="4"><?php echo cmsCore::getArrVal($mod, 'description', ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_PAGETITLE']; ?></label>
            <textarea class="form-control" name="pagetitle" rows="2"><?php echo cmsCore::getArrVal($mod, 'pagetitle', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_PAGETITLE_HINT']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METAKEYS']; ?></label>
            <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($mod, 'meta_keys', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METADESCR']; ?></label>
            <textarea class="form-control" name="meta_desc" rows="2"><?php echo cmsCore::getArrVal($mod, 'meta_desc', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
        </div>
    </div>
    
    <div>
        <input type="hidden" name="opt" value="<?php if ($opt == 'add_album') { echo 'submit_album'; } else { echo 'update_album'; } ?>" />
        
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';"/>
        <?php
            if ($opt=='edit_album'){
                echo '<input type="hidden" name="item_id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
<?php
}
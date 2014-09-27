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

cmsCore::loadModel('users');
$model = new cms_model_users();

$opt = cmsCore::request('opt', 'str', 'list');

$toolmenu = array(
    array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.optform.submit();' ),
    array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components' )
);

cpToolMenu($toolmenu);

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array();
    $cfg['sw_comm']   = cmsCore::request('sw_comm', 'int', 0);
    $cfg['sw_search'] = cmsCore::request('sw_search', 'int', 0);
    $cfg['sw_forum']  = cmsCore::request('sw_forum', 'int', 0);
    $cfg['sw_photo']  = cmsCore::request('sw_photo', 'int', 0);
    $cfg['sw_wall']   = cmsCore::request('sw_wall', 'int', 0);
    $cfg['sw_blogs']  = cmsCore::request('sw_blogs', 'int', 0);
    $cfg['sw_clubs']  = cmsCore::request('sw_clubs', 'int', 0);
    $cfg['sw_feed']   = cmsCore::request('sw_feed', 'int', 0);
    $cfg['sw_awards'] = cmsCore::request('sw_awards', 'int', 0);
    $cfg['sw_board']  = cmsCore::request('sw_board', 'int', 0);
    $cfg['sw_msg']    = cmsCore::request('sw_msg', 'int', 0);
    $cfg['sw_guest']  = cmsCore::request('sw_guest', 'int', 0);
    $cfg['sw_files']  = cmsCore::request('sw_files', 'int', 0);

    $cfg['karmatime'] = cmsCore::request('karmatime', 'int', 0);
    $cfg['karmaint']  = cmsCore::request('karmaint', 'str', 'DAY');

    $cfg['photosize'] = cmsCore::request('photosize', 'int', 0);
    $cfg['watermark'] = cmsCore::request('watermark', 'int', 0);
    $cfg['smallw']    = cmsCore::request('smallw', 'int', 64);
    $cfg['medw']      = cmsCore::request('medw', 'int', 200);
    $cfg['medh']      = cmsCore::request('medh', 'int', 200);

    $cfg['filessize'] = cmsCore::request('filessize', 'int', 0);
    $cfg['filestype'] = mb_strtolower(cmsCore::request('filestype', 'str', 'jpeg,gif,png,jpg,bmp,zip,rar,tar'));
    while (mb_strpos($cfg['fa_ext'], 'htm') ||
           mb_strpos($cfg['fa_ext'], 'php') ||
           mb_strpos($cfg['fa_ext'], 'ht')) {
        $cfg['filestype'] = str_replace(array('htm','php','ht'), '', $cfg['filestype']);
    }

    $cfg['privforms'] = cmsCore::request('privforms', 'array_int');

    $cfg['deltime']   = cmsCore::request('deltime', 'int', 0);
    $cfg['users_perpage'] = cmsCore::request('users_perpage', 'int', 10);
    $cfg['wall_perpage']  = cmsCore::request('wall_perpage', 'int', 10);

    $inCore->saveComponentConfig('users', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=config');
}

cpCheckWritable('/images/users/avatars', 'folder');
cpCheckWritable('/images/users/avatars/small', 'folder');
cpCheckWritable('/images/users/photos', 'folder');
cpCheckWritable('/images/users/photos/small', 'folder');
cpCheckWritable('/images/users/photos/medium', 'folder');

?>
<form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id;?>" method="post" name="optform" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <div class="uitabs">
        <ul id="tabs">
            <li><a href="#basic"><span><?php echo $_LANG['AD_PROFILE_SETTINGS']; ?></span></a></li>
            <li><a href="#avatars"><span><?php echo $_LANG['AD_AVATARS']; ?></span></a></li>
            <li><a href="#proftabs"><span><?php echo $_LANG['AD_PROFILES_TAB']; ?></span></a></li>
            <li><a href="#forms"><span><?php echo $_LANG['AD_MORE_FIELDS']; ?></span></a></li>
            <li><a href="#photos"><span><?php echo $_LANG['AD_PROFILE_SETTINGS']; ?></span></a></li>
            <li><a href="#files"><span><?php echo $_LANG['AD_FILE_ARCHIVES']; ?></span></a></li>
        </ul>

        <div id="basic">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_VIEV_PROFILES']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_guest']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_guest" <?php if($model->config['sw_guest']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_guest']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_guest" <?php if (!$model->config['sw_guest']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_SEARCH_USERS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_search'] == 1) { echo 'active'; } ?>">
                            <input type="radio" name="sw_search" <?php if($model->config['sw_search'] == 1) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if(!$model->config['sw_search']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_search" <?php if(!$model->config['sw_search']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['AD_YES_ONLY_VIEW']; ?>
                        </label>
                        <label class="btn btn-default <?php if ($model->config['sw_search'] == 2) { echo 'active'; } ?>">
                            <input type="radio" name="sw_search" <?php if ($model->config['sw_search'] == 2) { echo 'checked="checked"'; } ?> value="2" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_SHOW_MORE_COMMENTS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_comm']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_comm" <?php if($model->config['sw_comm']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_comm']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_comm" <?php if (!$model->config['sw_comm']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_SHOW_FORUM']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_forum']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_forum" <?php if($model->config['sw_forum']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_forum']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_forum" <?php if (!$model->config['sw_forum']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_USERS_WALL']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_wall']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_wall" <?php if($model->config['sw_wall']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_wall']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_wall" <?php if (!$model->config['sw_wall']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_PERSONAL_BLOGS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_blogs']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_blogs" <?php if($model->config['sw_blogs']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_blogs']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_blogs" <?php if (!$model->config['sw_blogs']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_SHOW_ADS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_board']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_board" <?php if($model->config['sw_board']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_board']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_board" <?php if (!$model->config['sw_board']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_PRIVATE_MESS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_msg']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_msg" <?php if($model->config['sw_msg']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_msg']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_msg" <?php if (!$model->config['sw_msg']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_NOTIFICATION_TEXT']; ?>:</label>
                    <div><?php echo '/languages/'. cmsConfig::getConfig('lang') .'/letters/newmessage.txt'; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_PEROD_KARMA']; ?>:</label>
                    <input type="number" id="int_1" class="form-control" name="karmatime" min="0" value="<?php echo $model->config['karmatime']; ?>"/>
                    <select id="int_2" class="form-control" name="karmaint">
                        <option value="MINUTE"  <?php if (mb_strstr($model->config['karmaint'], 'MINUTE')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['MINUTE10']; ?></option>
                        <option value="HOUR"  <?php if (mb_strstr($model->config['karmaint'], 'HOUR')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['HOUR10']; ?></option>
                        <option value="DAY" <?php if (mb_strstr($model->config['karmaint'], 'DAY')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['DAY10']; ?></option>
                        <option value="MONTH" <?php if (mb_strstr($model->config['karmaint'], 'MONTH')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['MONTH10']; ?></option>
                    </select>
                    <div class="help-block"><?php echo $_LANG['AD_PEROD_KARMA_HINT']; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_DELETE_INACTIVE']; ?> (<?php echo $_LANG['MONTH10']; ?>):</label>
                    <input type="number" class="form-control" name="deltime" min="0" value="<?php echo $model->config['deltime']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_DELETE_INACTIVE_HINT']; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_USERS_ON_PAGE']; ?>:</label>
                    <input type="number" class="form-control" name="users_perpage" min="0" value="<?php echo $model->config['users_perpage']; ?>" />
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_NUMBER_ON_WALL']; ?>:</label>
                    <input type="number" class="form-control" name="wall_perpage" min="0" value="<?php echo $model->config['wall_perpage']; ?>" />
                </div>
            </div>
        </div>

        <div id="avatars">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_WIDTH_SMALL_AVATAR']; ?> (<?php echo $_LANG['AD_PX']; ?>):</label>
                    <input type="number" class="form-control" name="smallw" min="0" value="<?php echo $model->config['smallw']; ?>" />
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_WIDTH_LARGE_AVATAR']; ?> (<?php echo $_LANG['AD_PX']; ?>):</label>
                    <input type="number" class="form-control" name="medw" min="0" value="<?php echo $model->config['medw']; ?>" />
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_HEIGHT_LARGE_AVATAR']; ?> (<?php echo $_LANG['AD_PX']; ?>):</label>
                    <input type="number" class="form-control" name="medh" min="0" value="<?php echo $model->config['medh']; ?>" />
                </div>
            </div>
        </div>

        <div id="proftabs">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_TAB_RIBBON']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_feed']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_feed" <?php if($model->config['sw_feed']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_feed']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_feed" <?php if (!$model->config['sw_feed']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_TAB_CLUBS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_clubs']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_clubs" <?php if($model->config['sw_clubs']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_clubs']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_clubs" <?php if (!$model->config['sw_clubs']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_TAB_AWARDS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_awards']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_awards" <?php if($model->config['sw_awards']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_awards']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_awards" <?php if (!$model->config['sw_awards']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div id="forms">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_FORMS_IN_PROFILES']; ?>:</label>
                    <select class="form-control" name="privforms[]" size="10" multiple="multiple">
                    <?php
                        $sql = "SELECT * FROM cms_forms";
                        $rs = cmsCore::c('db')->query($sql);
                        if (cmsCore::c('db')->num_rows($rs)) {
                            while ($f = cmsCore::c('db')->fetch_assoc($rs)) {
                                if (in_array($f['id'], $model->config['privforms'])) { $selected='selected="selected"'; } else { $selected = ''; }
                                echo '<option value="'. $f['id'] .'" '. $selected .'>'. $f['title'] .'</option>';
                            }
                        }
                    ?>
                    </select>
                    <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?>.</div>
                    <div class="help-block"><?php echo $_LANG['AD_FORMS_IN_PROFILES_EDIT']; ?> <a href="index.php?view=components&do=config&link=forms"><?php echo $_LANG['AD_FORM_DESIGNER']; ?></a>.</div>
                </div>
            </div>
        </div>

        <div id="photos">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PHOTO_ALBUMS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_photo']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_photo" <?php if($model->config['sw_photo']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_photo']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_photo" <?php if (!$model->config['sw_photo']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_ENABLE_WATERMARK']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['watermark']) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if($model->config['watermark']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['watermark']) { echo 'active'; } ?>">
                            <input type="radio" name="watermark" <?php if (!$model->config['watermark']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_APPLY_WATERMARK_HINT']; ?> &quot;<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>&quot;</div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_LOTS_PHOTOS']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
                    <input type="number" class="form-control" name="photosize" min="0" value="<?php echo $model->config['photosize']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_LOTS_PHOTOS_HINT']; ?></div>
                </div>
            </div>
        </div>

        <div id="files">
            <div style="width:550px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_USER_FILES']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if($model->config['sw_files']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_files" <?php if($model->config['sw_files']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$model->config['sw_files']) { echo 'active'; } ?>">
                            <input type="radio" name="sw_files" <?php if (!$model->config['sw_files']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_DISK_SPACE']; ?> (<?php echo $_LANG['SIZE_MB']; ?>):</label>
                    <input type="number" class="form-control" name="filessize" min="0" value="<?php echo $model->config['filessize']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_DISK_SPACE_HINT']; ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_FILE_TIPES']; ?>:</label>
                    <input type="text" class="form-control" name="filestype" size="30" value="<?php echo $model->config['filestype']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_FILE_TIPES_HINT']; ?></div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';" />
    </div>
</form>
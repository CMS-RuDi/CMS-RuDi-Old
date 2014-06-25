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

$opt = cmsCore::request('opt', 'str', 'list');

echo '<h3>'.$_LANG['AD_SETTINGS'].'</h3>';

$cfg = $inCore->loadComponentConfig('content');

if($opt=='saveconfig'){
    if(!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array();
    $cfg['readdesc']    = cmsCore::request('readdesc', 'int', 0);
    $cfg['is_url_cyrillic'] = cmsCore::request('is_url_cyrillic', 'int', 0);
    $cfg['rating']      = cmsCore::request('rating', 'int', 0);
    $cfg['perpage']     = cmsCore::request('perpage', 'int', 0);
    $cfg['pt_show']     = cmsCore::request('pt_show', 'int', 0);
    $cfg['pt_disp']     = cmsCore::request('pt_disp', 'int', 0);
    $cfg['pt_hide']     = cmsCore::request('pt_hide', 'int', 0);
    $cfg['autokeys']    = cmsCore::request('autokeys', 'int', 0);

    // Настройки изображений
    $cfg['imgs_quality'] = cmsCore::request('imgs_quality', 'int', 80);
    if ($cfg['imgs_quality'] <= 0 || $cfg['imgs_quality'] > 100){
        $cfg['imgs_quality'] = 80;
    }
    $cfg['imgs_big_w'] = cmsCore::request('imgs_big_w', 'int', 300);
    $cfg['imgs_big_h'] = cmsCore::request('imgs_big_h', 'int', 300);
    $cfg['imgs_medium_w'] = cmsCore::request('imgs_medium_w', 'int', 200);
    $cfg['imgs_medium_h'] = cmsCore::request('imgs_medium_h', 'int', 200);
    $cfg['imgs_small_w'] = cmsCore::request('imgs_small_w', 'int', 100);
    $cfg['imgs_small_h'] = cmsCore::request('imgs_small_h', 'int', 100);
    $cfg['resize_type'] = cmsCore::request('resize_type', array('auto','exact','portrait','landscape','crop'), 'auto');
    $cfg['mresize_type'] = cmsCore::request('mresize_type', array('auto','exact','portrait','landscape','crop'), 'auto');
    $cfg['sresize_type'] = cmsCore::request('sresize_type', array('auto','exact','portrait','landscape','crop'), 'auto');
    $cfg['img_on'] = cmsCore::request('img_on', 'int', 0);
    //=======================
    
    $cfg['img_users']   = cmsCore::request('img_users', 'int', 1);
    $cfg['img_big_w'] = cmsCore::request('img_big_w', 'int', 300);
    $cfg['img_small_w'] = cmsCore::request('img_small_w', 'int', 100);
    $cfg['watermark']   = cmsCore::request('watermark', 'int', 0);
    $cfg['watermark_only_big'] = cmsCore::request('watermark_only_big', 'int', 0);
    
    $cfg['pagetitle']   = cmsCore::request('pagetitle', 'str', '');
    $cfg['meta_desc']   = cmsCore::request('meta_desc', 'str', '');
    $cfg['meta_keys']   = cmsCore::request('meta_keys', 'str', '');

    $inCore->saveComponentConfig('content', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');

    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=config');
}

require('../includes/jwtabs.php');
cmsCore::c('page')->addHead(jwHeader());

?>

<form action="index.php?view=components&do=config&id=<?php echo $id;?>" method="post" name="optform" target="_self" id="form1">
<input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <?php ob_start(); ?>
    {tab=<?php echo $_LANG['AD_OVERALL']; ?>}
    <table width="550" border="0" cellpadding="10" cellspacing="0" class="proptable">
        <tr>
            <td><strong><?php echo $_LANG['AD_GENERATE_CYRYLLIC_URL']; ?>: </strong></td>
            <td width="110">
                <label><input name="is_url_cyrillic" type="radio" value="1" <?php if ($cfg['is_url_cyrillic']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?> </label>
                <label><input name="is_url_cyrillic" type="radio" value="0" <?php if (!$cfg['is_url_cyrillic']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?> </label>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_OUTPUT_ANNOUNCEMENTS']; ?>: </strong></td>
            <td width="110">
                <label><input name="readdesc" type="radio" value="1" <?php if ($cfg['readdesc']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?> </label>
                <label><input name="readdesc" type="radio" value="0" <?php if (!$cfg['readdesc']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?> </label>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['ARTICLES_RATING']; ?>: </strong></td>
            <td>
                <label><input name="rating" type="radio" value="1" <?php if ($cfg['rating']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?> </label>
                <label><input name="rating" type="radio" value="0" <?php if (!$cfg['rating']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?></label>
            </td>
        </tr>
        <tr>
            <td>
                <strong><?php echo $_LANG['AD_GENERATE_KEY_DESCR']; ?>:</strong>
            </td>
            <td valign="top">
                <label><input name="autokeys" type="radio" value="1" <?php if ($cfg['autokeys']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?> </label>
                <label><input name="autokeys" type="radio" value="0" <?php if (!$cfg['autokeys']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?> </label>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_NUMBER_PER_PAGE']; ?>: </strong></td>
            <td><input class="uispin" name="perpage" type="text" id="perpage" value="<?php echo $cfg['perpage'];?>" size="5" /></td>
        </tr>
    </table>
    <table width="550" border="0" cellpadding="10" cellspacing="0" class="proptable">
        <tr>
            <td><strong><?php echo $_LANG['AD_SHOW_CONTENT']; ?>: </strong></td>
            <td width="110">
                <label><input name="pt_show" type="radio" value="1" <?php if ($cfg['pt_show']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?> </label>
                <label><input name="pt_show" type="radio" value="0" <?php if (!$cfg['pt_show']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?> </label>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_DEPLOY_CONTENT']; ?>: </strong></td>
            <td>
                <label><input name="pt_disp" type="radio" value="1" <?php if ($cfg['pt_disp']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?> </label>
                <label><input name="pt_disp" type="radio" value="0" <?php if (!$cfg['pt_disp']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?> </label>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_HIDE CONTENT']; ?>: </strong></td>
            <td>
                <label><input name="pt_hide" type="radio" value="1" <?php if ($cfg['pt_hide']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?> </label>
                <label><input name="pt_hide" type="radio" value="0" <?php if (!$cfg['pt_hide']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?> </label>
            </td>
        </tr>
    </table>
    
    {tab=<?php echo $_LANG['AD_PHOTO_ART']; ?>}
    <table width="550" border="0" cellpadding="10" cellspacing="0" class="proptable">
        <tr>
            <td>
                <strong><?php echo $_LANG['AD_ALLOW_USERS_TO']; ?>:</strong><br/>
                <span class="hinttext"><?php echo $_LANG['AD_ALLOW_USERS_TO_HINT']; ?></span>
            </td>
            <td>
                <label><input name="img_users" type="radio" value="1" <?php if ($cfg['img_users']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?> </label>
                <label><input name="img_users" type="radio" value="0" <?php if (!$cfg['img_users']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?> </label>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_PHOTO_BIG']; ?>:</strong></td>
            <td>
                <input class="uispin" name="img_big_w" type="text" id="img_big_w" value="<?php echo $cfg['img_big_w'];?>" size="5" />
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_PHOTO_SMALL']; ?>:</strong></td>
            <td width="120">
                <input class="uispin" name="img_small_w" type="text" id="img_small_w" value="<?php echo $cfg['img_small_w'];?>" size="5" />
            </td>
        </tr>
        <tr>
           <td><strong><?php echo $_LANG['AD_ENABLE_WATERMARK']; ?></strong><br />
		   <span class="hinttext"><?php echo $_LANG['AD_WATERMARK_HINT']; ?> "<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</span></td>
           <td width="260">
               <label><input name="watermark" type="radio" value="1" <?php if ($cfg['watermark']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?> </label>
               <label><input name="watermark" type="radio" value="0"  <?php if (!$cfg['watermark']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['NO']; ?> </label>
           </td>
        </tr>
    </table>
    
    <table width="550" border="0" cellpadding="10" cellspacing="0" class="proptable">
        <tr style="border-top: 1px #cccccc solid;">
            <td>
                <strong><?php echo $_LANG['AD_ALLOW_USERS_TO_MULTI']; ?>:</strong><br/>
                <span class="hinttext"><?php echo $_LANG['AD_ALLOW_USERS_TO_MULTI_HINT']; ?></span>
            </td>
            <td>
                <label><input name="img_on" type="radio" value="1" <?php if ($cfg['img_on']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?> </label>
                <label><input name="img_on" type="radio" value="0" <?php if (!$cfg['img_on']) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?> </label>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_PHOTO_BIG']; ?>:</strong></td>
            <td>
                <input class="uispin" name="imgs_big_w" type="text" id="imgs_big_w" value="<?php echo $cfg['imgs_big_w'];?>" size="5" /> x <input class="uispin" name="imgs_big_h" type="text" id="imgs_big_h" value="<?php echo $cfg['imgs_big_h'];?>" size="5" />
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_PHOTO_RESIZE_TYPE']; ?>:</strong></td>
            <td>
                <select name="resize_type" style="width: 200px;">
                    <option value="auto" <?php if ($cfg['resize_type'] == 'auto'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_AUTO']; ?></option>
                    <option value="exact" <?php if ($cfg['resize_type'] == 'exact'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_EXACT']; ?></option>
                    <option value="portrait" <?php if ($cfg['resize_type'] == 'portrait'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_PORTRAIT']; ?></option>
                    <option value="landscape" <?php if ($cfg['resize_type'] == 'landscape'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_LANDSCAPE']; ?></option>
                    <option value="crop" <?php if ($cfg['resize_type'] == 'crop'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_CROP']; ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_PHOTO_MEDIUM']; ?>:</strong></td>
            <td>
                <input class="uispin" name="imgs_medium_w" type="text" id="imgs_medium_w" value="<?php echo $cfg['imgs_medium_w'];?>" size="5" /> x <input class="uispin" name="imgs_medium_h" type="text" id="imgs_medium_h" value="<?php echo $cfg['imgs_medium_h'];?>" size="5" />
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_PHOTO_RESIZE_TYPE']; ?>:</strong></td>
            <td>
                <select name="mresize_type" style="width: 200px;">
                    <option value="auto" <?php if ($cfg['mresize_type'] == 'auto'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_AUTO']; ?></option>
                    <option value="exact" <?php if ($cfg['mresize_type'] == 'exact'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_EXACT']; ?></option>
                    <option value="portrait" <?php if ($cfg['mresize_type'] == 'portrait'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_PORTRAIT']; ?></option>
                    <option value="landscape" <?php if ($cfg['mresize_type'] == 'landscape'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_LANDSCAPE']; ?></option>
                    <option value="crop" <?php if ($cfg['mresize_type'] == 'crop'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_CROP']; ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_PHOTO_SMALL']; ?>:</strong></td>
            <td width="120">
                <input class="uispin" name="imgs_small_w" type="text" id="imgs_small_w" value="<?php echo $cfg['imgs_small_w'];?>" size="5" /> x <input class="uispin" name="imgs_small_h" type="text" id="imgs_small_h" value="<?php echo $cfg['imgs_small_h'];?>" size="5" />
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $_LANG['AD_PHOTO_RESIZE_TYPE']; ?>:</strong></td>
            <td>
                <select name="sresize_type" style="width: 200px;">
                    <option value="auto" <?php if ($cfg['sresize_type'] == 'auto'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_AUTO']; ?></option>
                    <option value="exact" <?php if ($cfg['sresize_type'] == 'exact'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_EXACT']; ?></option>
                    <option value="portrait" <?php if ($cfg['sresize_type'] == 'portrait'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_PORTRAIT']; ?></option>
                    <option value="landscape" <?php if ($cfg['sresize_type'] == 'landscape'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_LANDSCAPE']; ?></option>
                    <option value="crop" <?php if ($cfg['sresize_type'] == 'crop'){ echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PHOTO_RESIZE_VAL_CROP']; ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <strong><?php echo $_LANG['AD_IMG_QUALITY']; ?></strong>
            </td>
            <td>
                <input type="text" name="imgs_quality" value="<?php echo $cfg['imgs_quality']; ?>" style="width: 200px;" />
            </td>
        </tr>
        <tr>
           <td>
               <strong><?php echo $_LANG['AD_WATERMARK_ONLY_BIG']; ?></strong>
               <div class="hinttext">
                   <?php echo $_LANG['AD_WATERMARK_ONLY_BIG_HINT']; ?>
               </div>
           </td>
           <td width="260">
               <label><input name="watermark_only_big" type="radio" value="1" <?php if ($cfg['watermark_only_big']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?> </label>
               <label><input name="watermark_only_big" type="radio" value="0"  <?php if (!$cfg['watermark_only_big']) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['NO']; ?> </label>
           </td>
        </tr>
    </table>
    
    {tab=SEO}
    <table width="550" border="0" cellpadding="10" cellspacing="0" class="proptable">
        <tr>
            <td>
                <strong><?php echo $_LANG['AD_PAGE_TITLE']; ?>:</strong>
            </td>
            <td>
                <input name="pagetitle" type="text" id="pagetitle" style="width:99%" value="<?php echo htmlspecialchars($cfg['pagetitle']); ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <strong><?php echo icms_ucfirst($_LANG['KEYWORDS']); ?>:</strong>
                <div class="hinttext"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
            </td>
            <td>
                <textarea name="meta_keys" style="width:97%" rows="2" id="meta_keys"><?php echo htmlspecialchars($cfg['meta_keys']);?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <strong><?php echo $_LANG['DESCRIPTION']; ?>:</strong>
                <div class="hinttext"><?php echo $_LANG['AD_LESS_THAN']; ?></div>
            </td>
            <td>
                <textarea name="meta_desc" style="width:97%" rows="2" id="meta_desc"><?php echo htmlspecialchars($cfg['meta_desc']);?></textarea>
            </td>
        </tr>
    </table>
    
    {/tabs}
    <?php echo jwTabs(ob_get_clean()); ?>
    <p>
        <input name="opt" type="hidden" value="saveconfig" />
        <input name="save" type="submit" id="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input name="back" type="button" id="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
    </p>
</form>
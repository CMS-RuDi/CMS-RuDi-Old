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

function uploadCategoryIcon($file = '') {
    // Загружаем класс загрузки фото
    cmsCore::loadClass('upload_photo');
    $inUploadPhoto = cmsUploadPhoto::getInstance();
    
    // Выставляем конфигурационные параметры
    $inUploadPhoto->upload_dir    = PATH.'/upload/board/';
    $inUploadPhoto->dir_medium    = 'cat_icons/';
    $inUploadPhoto->medium_size_w = 32;
    $inUploadPhoto->medium_size_h = 32;
    $inUploadPhoto->only_medium   = true;
    $inUploadPhoto->is_watermark  = false;
    // Процесс загрузки фото
    $files = $inUploadPhoto->uploadPhoto($file);

    $icon = $files['filename'] ? $files['filename'] : $file;

    return $icon;
}

$cfg = $inCore->loadComponentConfig('board');
cmsCore::loadModel('board');
$model = new cms_model_board();

define('IS_BILLING', $inCore->isComponentInstalled('billing'));
if (IS_BILLING) { cmsCore::loadClass('billing'); }

$opt = cmsCore::request('opt', 'str', 'list_items');

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array(
        'maxcols'            => cmsCore::request('maxcols', 'int', 0),
        'obtypes'            => cmsCore::request('obtypes', 'html', ''),
        'showlat'            => cmsCore::request('showlat', 'str', ''),
        'public'             => cmsCore::request('public', 'int', 0),
        'photos'             => cmsCore::request('photos', 'int', 0),
        'srok'               => cmsCore::request('srok', 'int', 0),
        'pubdays'            => cmsCore::request('pubdays', 'int', 0),
        'watermark'          => cmsCore::request('watermark', 'int', 0),
        'aftertime'          => cmsCore::request('aftertime', 'str', ''),
        'comments'           => cmsCore::request('comments', 'int', 0),
        'extend'             => cmsCore::request('extend', 'int', 0),
        'auto_link'          => cmsCore::request('auto_link', 'int', 0),
        'vip_enabled'        => cmsCore::request('vip_enabled', 'int', 0),
        'vip_prolong'        => cmsCore::request('vip_prolong', 'int', 0),
        'vip_max_days'       => cmsCore::request('vip_max_days', 'int', 30),
        'vip_day_cost'       => cmsCore::request('vip_day_cost', 'str', 5),
        'home_perpage'       => cmsCore::request('home_perpage', 'int', 15),
        'maxcols_on_home'    => cmsCore::request('maxcols_on_home', 'int', 1),
        'publish_after_edit' => cmsCore::request('publish_after_edit', 'int', 0),
        'root_description'   => cmsCore::request('root_description', 'html', ''),
        'meta_keys'          => cmsCore::request('meta_keys', 'str', ''),
        'meta_desc'          => cmsCore::request('meta_desc', 'str', ''),
        'seo_user_access'    => cmsCore::request('seo_user_access', 'int', 0)
    );

    $cfg['vip_day_cost'] = str_replace(',', '.', trim($cfg['vip_day_cost']));

    $inCore->saveComponentConfig('board', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}


$toolmenu = array(
    array( 'icon' => 'newstuff.gif', 'title' => $_LANG['ADD_ADV'], 'link' => '/board/add.html' ),
    array( 'icon' => 'newfolder.gif', 'title' => $_LANG['AD_NEW_CAT'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_cat' ),
    array( 'icon' => 'liststuff.gif', 'title' => $_LANG['AD_ALL_AD'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_items' ),
    array( 'icon' => 'folders.gif', 'title' => $_LANG['AD_ALL_CAT'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_cats' )
);

if ($opt == 'list_items') {
    $toolmenu[] = array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=show_item&multiple=1');" );
    $toolmenu[] = array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link'=>"javascript:checkSel('?view=components&do=config&id=". $id ."&opt=hide_item&multiple=1');" );
}

$toolmenu[] = array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&id='. $id .'&opt=config');

cpToolMenu($toolmenu);

if ($opt == 'show_item') {
    if (!cmsCore::inRequest('item')){
        if (cmsCore::inRequest('item_id')){
            cmsCore::c('db')->setFlag('cms_board_items', cmsCore::request('item_id', 'int', 0), 'published', '1');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_board_items', cmsCore::request('item', 'array_int', 0), 'published', '1');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_item'){
    if (!cmsCore::inRequest('item')){
        if (cmsCore::inRequest('item_id')){
            cmsCore::c('db')->setFlag('cms_board_items', cmsCore::request('item_id', 'int', 0), 'published', '0');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_board_items', cmsCore::request('item', 'array_int', 0), 'published', '0');
        cmsCore::redirectBack();
    }
}

if ($opt == 'delete_item') {
    $model->deleteRecord(cmsCore::request('item_id', 'int', 0));
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);

    cpCheckWritable('/images/board', 'folder');
    cpCheckWritable('/images/board/medium', 'folder');
    cpCheckWritable('/images/board/small', 'folder');
?>

<form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>" method="post" name="optform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="margin-top:12px; width:600px;" class="uitabs">
        <ul id="tabs">
            <li><a href="#basic"><span><?php echo $_LANG['AD_OVERALL']; ?></span></a></li>
            <li><a href="#access"><span><?php echo $_LANG['AD_TAB_ACCESS']; ?></span></a></li>
            <li><a href="#types"><span><?php echo $_LANG['AD_TYPES']; ?></span></a></li>
            <li><a href="#vip"><span><?php echo $_LANG['AD_VIP']; ?></span></a></li>
            <li><a href="#seo"><span><?php echo $_LANG['AD_SEO']; ?></span></a></li>
        </ul>

        <div id="basic">
            <div class="form-group">
                <label><?php echo $_LANG['AD_PHOTO_ENABLE']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'photos', false)) { echo 'active'; } ?>">
                        <input type="radio" name="photos" <?php if(cmsCore::getArrVal($cfg, 'photos', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'photos', false)) { echo 'active'; } ?>">
                        <input type="radio" name="photos" <?php if (!cmsCore::getArrVal($cfg, 'photos', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_ENABLE_WATERMARK']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                        <input type="radio" name="watermark" <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                        <input type="radio" name="watermark" <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_COMENT_TO_AD']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'comments', false)) { echo 'active'; } ?>">
                        <input type="radio" name="comments" <?php if(cmsCore::getArrVal($cfg, 'comments', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'comments', false)) { echo 'active'; } ?>">
                        <input type="radio" name="comments" <?php if (!cmsCore::getArrVal($cfg, 'comments', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <table width="100%">
                <tr>
                    <td><label><?php echo $_LANG['AD_HOW_MANY_AD']; ?> (<?php echo $_LANG['AD_PIECES']; ?>): </label></td>
                    <td width="100"><input type="number" id="home_perpage" class="form-control" name="home_perpage" size="5" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'home_perpage', ''); ?>" /></td>
                </tr>
                <tr>
                    <td><label><?php echo $_LANG['AD_HOW_MANY_COLUMNS_AD']; ?> (<?php echo $_LANG['AD_PIECES']; ?>): </label></td>
                    <td><input type="number" id="maxcols_on_home" class="form-control" name="maxcols_on_home" size="5" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'maxcols_on_home', ''); ?>" /></td>
                </tr>
                <tr>
                    <td><label><?php echo $_LANG['AD_HOW_MANY_COLUMNS_CAT']; ?> (<?php echo $_LANG['AD_PIECES']; ?>): </label></td>
                    <td><input type="number" id="maxcols" class="form-control" name="maxcols" size="5" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'maxcols', ''); ?>" /></td>
                </tr>
            </table>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_AUTOLINK_ENABLE']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'auto_link', false)) { echo 'active'; } ?>">
                        <input type="radio" name="auto_link" <?php if(cmsCore::getArrVal($cfg, 'auto_link', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'auto_link', false)) { echo 'active'; } ?>">
                        <input type="radio" name="auto_link" <?php if (!cmsCore::getArrVal($cfg, 'auto_link', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
        </div>

        <div id="access">
            <div class="form-group">
                <label><?php echo $_LANG['AD_ADD_AD']; ?>:</label>
                <select class="form-control" name="public">
                    <option value="0" <?php if (cmsCore::getArrVal($cfg, 'public', false) == '0') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TABOO']; ?></option>
                    <option value="1" <?php if (cmsCore::getArrVal($cfg, 'public', false) == '1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PREMODERATION']; ?></option>
                    <option value="2" <?php if (cmsCore::getArrVal($cfg, 'public', false) == '2') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_WITHOUT_MODERATION']; ?></option>
                </select>
                <div class="help-block"><?php echo $_LANG['AD_RELATION_SETTING']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_WITH_MODERATION']; ?>:</label>
                <select class="form-control" name="publish_after_edit">
                    <option value="0" <?php if (cmsCore::getArrVal($cfg, 'publish_after_edit', false) == '0') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_DEFAULT']; ?></option>
                    <option value="1" <?php if (cmsCore::getArrVal($cfg, 'publish_after_edit', false) == '1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_NO_MODERATION']; ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_DATA_AD']; ?>:</label>
                <table width="100%">
                    <tr>
                        <td>
                            <div class="checkbox">
                                <label>
                                    <input name="srok" type="radio" value="1" <?php if (cmsCore::getArrVal($cfg, 'srok', false)) { echo 'checked="checked"'; } ?> /><?php echo $_LANG['AD_ENABLE_SELECTION']; ?>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox">
                            <label style="display:inline-block;">
                                <input name="srok" type="radio" value="0" <?php if (!cmsCore::getArrVal($cfg, 'srok', false)) { echo 'checked="checked"'; } ?> /><?php echo $_LANG['AD_FIXED']; ?>:
                            </label>
                            <input type="number" class="form-control" style="width:70px;display:inline-block;" name="pubdays" size="3" min="0" value="<?php echo cmsCore::getArrVal($cfg, 'pubdays', 0); ?>" /> <?php echo $_LANG['DAY10']; ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_OVERDUE_AD']; ?>:</label>
                <select class="form-control" name="aftertime">
                    <option value="delete" <?php if (cmsCore::getArrVal($cfg, 'aftertime', '') == 'delete') { echo 'selected="selected"'; } ?>><?php echo $_LANG['DELETE']; ?></option>
                    <option value="hide" <?php if (cmsCore::getArrVal($cfg, 'aftertime', '') == 'hide') { echo 'selected="selected"'; } ?>><?php echo $_LANG['HIDE']; ?></option>
                    <option value="" <?php if (cmsCore::getArrVal($cfg, 'aftertime', '') == '') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_NOTHING']; ?></option>
                </select>
                <div class="help-block"><?php echo $_LANG['AD_ACTION_SELECT']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_PROLONGATION']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'extend', false)) { echo 'active'; } ?>">
                        <input type="radio" name="extend" <?php if(cmsCore::getArrVal($cfg, 'extend', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'extend', false)) { echo 'active'; } ?>">
                        <input type="radio" name="extend" <?php if (!cmsCore::getArrVal($cfg, 'extend', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
                <div class="help-block"><?php echo $_LANG['AD_IF_HIDE']; ?></div>
            </div>
        </div>

        <div id="types">
            <div class="form-group">
                <label><?php echo $_LANG['AD_TYPES_AD']; ?>:</label>
                <textarea class="form-control" name="obtypes" rows="10"><?php echo cmsCore::getArrVal($cfg, 'obtypes', ''); ?></textarea>
                <div class="help-block"><?php echo $_LANG['AD_NEW_LINE_TYPES']; ?></div>
                <div class="help-block"><?php echo $_LANG['AD_DIFFERENT_TYPES']; ?></div>
            </div>
        </div>

        <div id="vip">
            <?php if (!IS_BILLING){ ?>
                <p>
                    <?php echo $_LANG['AD_SUPPORT_VIP_AD']; ?> &laquo;<a href="http://www.instantcms.ru/billing/about.html"><?php echo $_LANG['AD_BILLING']; ?></a>&raquo;
                </p>
                <p>
                    <?php echo $_LANG['AD_INFO_0']; ?>
                </p>
                <p>
                    <?php echo $_LANG['AD_WITHOUT_COMPONENT']; ?> &laquo;<a href="http://www.instantcms.ru/billing/about.html"><?php echo $_LANG['AD_BILLING']; ?></a>&raquo; <?php echo $_LANG['AD_INFO_1']; ?>
                </p>
            <?php } else { ?>
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ENABLE_VIP_AD']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'vip_enabled', false)) { echo 'active'; } ?>">
                            <input type="radio" name="vip_enabled" <?php if(cmsCore::getArrVal($cfg, 'vip_enabled', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'vip_enabled', false)) { echo 'active'; } ?>">
                            <input type="radio" name="vip_enabled" <?php if (!cmsCore::getArrVal($cfg, 'vip_enabled', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ENABLE_VIP_STATUS']; ?>:</label>
                    <div class="btn-group" data-toggle="buttons" style="float:right;">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'vip_prolong', false)) { echo 'active'; } ?>">
                            <input type="radio" name="vip_prolong" <?php if(cmsCore::getArrVal($cfg, 'vip_prolong', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'vip_prolong', false)) { echo 'active'; } ?>">
                            <input type="radio" name="vip_prolong" <?php if (!cmsCore::getArrVal($cfg, 'vip_prolong', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_MAX_DATE_VIP_STATUS']; ?> (<?php echo $_LANG['DAY10']; ?>):</label>
                    <input type="number" class="form-control" name="vip_max_days" size="5" value="<?php echo cmsCore::getArrVal($cfg, 'vip_max_days', ''); ?>"/>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_COST_VIP_STATUS']; ?> (<?php echo $_LANG['AD_COST_ONE_DAY']; ?>):</label>
                    <input type="number" class="form-control" name="vip_day_cost" size="5" value="<?php echo cmsCore::getArrVal($cfg, 'vip_day_cost', ''); ?>"/>
                </div>
            <?php } ?>
        </div>
        
        <div id="seo">
            <div class="form-group">
                <label><?php echo $_LANG['AD_ROOT_DESCRIPION']; ?>:</label>
                <textarea class="form-control" name="root_description" rows="6"><?php echo cmsCore::getArrVal($cfg, 'root_description', ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_ROOT_METAKEYS']; ?>:</label>
                <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($cfg, 'meta_keys', ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_ROOT_METADESC']; ?>:</label>
                <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
                <textarea class="form-control" name="meta_desc" rows="4"><?php echo cmsCore::getArrVal($cfg, 'meta_desc', ''); ?></textarea>
            </div>

            <div class="form-group">
                <label><?php echo $_LANG['AD_USER_SEO_ACCESS']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                        <input type="radio" name="seo_user_access" <?php if(cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'active'; } ?>">
                        <input type="radio" name="seo_user_access" <?php if (!cmsCore::getArrVal($cfg, 'seo_user_access', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div>
        <input name="opt" type="hidden" id="do" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
    </div>
</form>

<?php }

if ($opt == 'show_cat') {
    cmsCore::c('db')->setFlag('cms_board_cats', cmsCore::request('item_id', 'int', 0), 'published', 1);
    cmsCore::halt('1');
}

if ($opt == 'hide_cat') {
    cmsCore::c('db')->setFlag('cms_board_cats', cmsCore::request('item_id', 'int', 0), 'published', 0);
    cmsCore::halt('1');
}

if ($opt == 'submit_cat' || $opt == 'update_cat') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $types = array(
        'title'       => array('title', 'str', $_LANG['AD_UNTITLED_CAT']),
        'description' => array('description', 'str', ''),
        'published'   => array('published', 'int', 0),
        'showdate'    => array('showdate', 'int', 0),
        'parent_id'   => array('parent_id', 'int', 0),
        'public'      => array('public', 'int', 0),
        'orderby'     => array('orderby', 'str', 'pubdate'),
        'orderto'     => array('orderto', 'str', 'desc'),
        'perpage'     => array('perpage', 'int', 10),
        'is_photos'   => array('is_photos', 'int', 0),
        'thumb1'      => array('thumb1', 'int', 0),
        'thumb2'      => array('thumb2', 'int', 0),
        'thumbsqr'    => array('thumbsqr', 'int', 0),
        'uplimit'     => array('uplimit', 'int', 0),
        'maxcols'     => array('maxcols', 'int', 0),
        'orderform'   => array('orderform', 'int', 0),
        'form_id'     => array('form_id', 'int', 0),
        'obtypes'     => array('obtypes', 'str', ''),
        'pagetitle'   => array('pagetitle', 'str', ''),
        'meta_keys'   => array('meta_keys', 'str', ''),
        'meta_desc'   => array('meta_desc', 'str', '')
    );

    $item = cmsCore::getArrayFromRequest($types);

    if ($opt == 'submit_cat') {
        $item['icon'] = uploadCategoryIcon();
        $item['pubdate'] = date("Y-m-d H:i:s");

        cmsCore::c('db')->addNsCategory('cms_board_cats', $item);
    } else {
        $item_id = cmsCore::request('item_id', 'int', 0);
        $mod = cmsCore::c('db')->get_fields('cms_board_cats', "id = '". $item_id ."'", '*');
        
        if (!$mod) { cmsCore::error404(); }
        
        $mod['icon'] = ($mod['icon'] == 'folder_grey.png') ? '' : $mod['icon'];
        $icon = uploadCategoryIcon($mod['icon']);
        $item['icon'] = $icon ? $icon : $mod['icon'];

        if ($item['parent_id'] != $mod['parent_id']) {
            cmsCore::nestedSetsInit('cms_board_cats')->MoveNode($item_id, $item['parent_id']);
        }

        cmsCore::c('db')->update('cms_board_cats', $item, $item_id);
    }

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_cats');
}

if ($opt == 'delete_cat') {
    $item_id = cmsCore::request('item_id', 'int', 0);

    $sql = "SELECT id FROM cms_board_items WHERE category_id = '". $item_id ."'";
    $result = cmsCore::c('db')->query($sql);
    if (cmsCore::c('db')->num_rows($result)) {
        while ($photo = cmsCore::c('db')->fetch_assoc($result)) {
            $model->deleteRecord($photo['id']);
        }
    }
    $f_icon = cmsCore::c('db')->get_field('cms_board_cats', "id = '". $item_id ."'", 'icon');
    cmsCore::c('db')->deleteNS('cms_board_cats', $item_id);
    if (file_exists(PATH.'/upload/board/cat_icons/'. $f_icon)) {
        @chmod(PATH.'/upload/board/cat_icons/'. $f_icon, 0777);
        @unlink(PATH.'/upload/board/cat_icons/'. $f_icon);
    }

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_cats');
}

if ($opt == 'list_cats') {
    cpAddPathway($_LANG['AD_ALL_CAT']);

    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%'),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_cat')
    );
    
    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%'),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_IF_CAT_DELETE'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_cat&item_id=%id%')
    );

    cpListTable('cms_board_cats', $fields, $actions, 'parent_id>0', 'NSLeft');
}

if ($opt == 'list_items') {
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['DATE'], 'field' => 'pubdate', 'width' => '80', 'filter' => '15', 'fdate' => '%d/%m/%Y' ),
        array( 'title' => $_LANG['TYPE'], 'field' => 'obtype', 'width' => '80', 'filter' => '15' ),
        array( 'title' => $_LANG['AD_TITLE'], 'field' => 'title', 'width' => '', 'filter' => '15', 'link' => '/board/edit%id%.html' ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width'=> '60', 'do' => 'opt', 'do_suffix' => '_item' ),
        array( 'title' => $_LANG['AD_VIEWS'], 'field' => 'hits', 'width' => '100' ),
        array( 'title' => 'IP', 'field'=>'ip', 'width' => '80', 'prc' => 'long2ip' ),
        array( 'title' => $_LANG['CAT_BOARD'], 'field' => 'category_id', 'width' => '230', 'prc' => 'cpBoardCatById', 'filter' => '1', 'filterlist' => cpGetList('cms_board_cats') )
    );
    
    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '/board/edit%id%.html'),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['DELETE_ADV'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_item&item_id=%id%')
    );

    cpListTable('cms_board_items', $fields, $actions, '', 'pubdate DESC');
}

if ($opt == 'add_cat' || $opt == 'edit_cat') {
    cpAddPathway($_LANG['AD_ALL_CAT'], '?view=components&do=config&id='. $id .'&opt=list_cats');
    if ($opt=='add_cat') {
        cpAddPathway($_LANG['AD_NEW_CAT']);
    } else {
        $item_id = cmsCore::request('item_id', 'int', 0);

        $mod = cmsCore::c('db')->get_fields('cms_board_cats', "id = '". $item_id ."'", '*');
        if (!$mod){ cmsCore::error404(); }

        echo '<h3>'. $_LANG['AD_CAT_EDIT'] .'</h3>';
        cpAddPathway($_LANG['AD_CAT_EDIT'].' "'.$mod['title'].'"');
    }

    //DEFAULT VALUES
    if (!isset($mod['thumb1'])) { $mod['thumb1'] = 64; }
    if (!isset($mod['thumb2'])) { $mod['thumb2'] = 400; }
    if (!isset($mod['thumbsqr'])) { $mod['thumbsqr'] = 0; }
    if (!isset($mod['maxcols'])) { $mod['maxcols'] = 1; }
    if (!isset($mod['perpage'])) { $mod['perpage'] = '20'; }
    if (!isset($mod['uplimit'])) { $mod['uplimit'] = 10; }
    if (!isset($mod['public'])) { $mod['public'] = -1; }
    if (!isset($mod['published'])) { $mod['published'] = 1; }
    if (!isset($mod['showdate'])) { $mod['showdate'] = 1; }
    if (!isset($mod['orderform'])) { $mod['orderform'] = 1; }
    if (!isset($mod['orderby'])) { $mod['orderby'] = 'pubdate'; }
    if (!isset($mod['orderto'])) { $mod['orderto'] = 'desc'; }
?>

<form id="addform" class="form-horizontal" role="form" name="addform" enctype="multipart/form-data" method="post" action="index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CAT_NAME'];?></label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', '')); ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CAT_PARENT'];?></label>
            <div class="col-sm-7">
                <select id="parent_id" class="form-control" name="parent_id">
                    <?php
                        $rootid = cmsCore::c('db')->get_field('cms_board_cats', 'parent_id=0', 'id');
                    ?>
                    <option value="<?php echo $rootid?>" <?php if (cmsCore::getArrVal($mod, 'parent_id', $rootid) == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_CAT_ROOT'];?></option>
                    <?php
                        echo $inCore->getListItemsNS('cms_board_cats', cmsCore::getArrVal($mod, 'parent_id', 0));
                    ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CAT_ICON'];?></label>
            <div class="col-sm-7">
                <?php if (cmsCore::getArrVal($mod, 'icon', false)) { ?>
                    <div style="text-align:center;"><img src="/upload/board/cat_icons/<?php echo $mod['icon']; ?>" border="0" /></div>
                <?php } ?>
                <input type="file" class="form-control" name="Filedata" />
                <div class="help-block"><?php echo $_LANG['AD_INFO_3'];?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ATTACH_FORM'];?></label>
            <div class="col-sm-7">
                <select class="form-control" name="form_id">
                    <option value="" <?php if (!cmsCore::getArrVal($mod, 'form_id', false)) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_DONT_ATTACH'];?></option>
                    <?php
                    $sql = "SELECT id, title FROM cms_forms";
                    $rs = cmsCore::c('db')->query($sql);

                    if (cmsCore::c('db')->num_rows($rs)) {
                        while ($f = cmsCore::c('db')->fetch_assoc($rs)) {
                            if ($f['id'] == cmsCore::getArrVal($mod, 'form_id', false)) { $selected='selected="selected"'; } else { $selected = ''; }
                            echo '<option value="'. $f['id'] .'" '. $selected .'>'. $f['title'] .'</option>';
                        }
                    }
                    ?>
                </select>
                <div class="help-block"><?php echo $_LANG['AD_FORM_FIELDS_EXIST'];?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_IF_PUBLIC_CAT'];?></label>
            <div class="col-sm-7 btn-group" data-toggle="buttons">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_IF_DATA_VIEW'];?></label>
            <div class="col-sm-7 btn-group" data-toggle="buttons">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'showdate', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showdate" <?php if(cmsCore::getArrVal($mod, 'showdate', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'showdate', false)) { echo 'active'; } ?>">
                    <input type="radio" name="showdate" <?php if (!cmsCore::getArrVal($mod, 'showdate', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SORT_AD'];?></label>
            <div class="col-sm-7">
                <select class="form-control" name="orderby">
                    <option value="title" <?php if (cmsCore::getArrVal($mod, 'orderby', false) == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET']; ?></option>
                    <option value="pubdate" <?php if (cmsCore::getArrVal($mod, 'orderby', false) == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR']; ?></option>
                    <option value="hits" <?php if (cmsCore::getArrVal($mod, 'orderby', false) == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS']; ?></option>
                    <option value="obtype" <?php if (cmsCore::getArrVal($mod, 'orderby', false) == 'obtype') { echo 'selected="selected"'; } ?>><?php echo $_LANG['ORDERBY_TYPE']; ?></option>
                    <option value="user_id" <?php if (cmsCore::getArrVal($mod, 'orderby', false) == 'user_id') { echo 'selected="selected"'; } ?>><?php echo $_LANG['ORDERBY_AVTOR']; ?></option>
                </select>
                <select class="form-control" name="orderto">
                    <option value="desc" <?php if (cmsCore::getArrVal($mod, 'orderto', false) == 'desc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT']; ?></option>
                    <option value="asc" <?php if (cmsCore::getArrVal($mod, 'orderto', false) == 'asc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT']; ?></option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SORT_FORM'];?></label>
            <div class="col-sm-7">
                <div class="checkbox">
                    <label><input type="radio" name="orderform" value="1" <?php if (cmsCore::getArrVal($mod, 'orderform', false)) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['SHOW']; ?></label>
                    <label><input type="radio" name="orderform" value="0"  <?php if (!cmsCore::getArrVal($mod, 'orderform', false)) { echo 'checked="checked"'; } ?> /><?php echo $_LANG['HIDE']; ?></label>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_HOW_MANY_COLUMNS_VIEW'];?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
            <div class="col-sm-7">
                <input type="number" class="form-control" name="maxcols" size="5" min="0" value="<?php echo cmsCore::getArrVal($mod, 'maxcols', ''); ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_USERS_AD_ADD'];?></label>
            <div class="col-sm-7">
                <select class="form-control" name="public">
                    <option value="0" <?php if (cmsCore::getArrVal($mod, 'public', false) == '0') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TABOO']; ?></option>
                    <option value="1" <?php if (cmsCore::getArrVal($mod, 'public', false) == '1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PREMODERATION']; ?></option>
                    <option value="2" <?php if (cmsCore::getArrVal($mod, 'public', false) == '2') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_WITHOUT_MODERATION']; ?></option>
                    <option value="-1" <?php if (cmsCore::getArrVal($mod, 'public', false) == '-1') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_DEFAULT']; ?></option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MAX_AD'];?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
            <div class="col-sm-7">
                <input type="number" class="form-control" name="uplimit" size="5" min="0" value="<?php echo cmsCore::getArrVal($mod, 'uplimit', ''); ?>"/>
                <div class="help-block"><?php echo $_LANG['AD_ONE_USER_ONE_DAY']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_HOW_MANY_AD_TO_PAGE'];?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
            <div class="col-sm-7">
                <input type="number" class="form-control" name="perpage" size="5" min="0" value="<?php echo cmsCore::getArrVal($mod, 'perpage', ''); ?>"/>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PHOTO_TO_AD'];?></label>
            <div class="col-sm-7 btn-group" data-toggle="buttons">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'is_photos', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_photos" <?php if(cmsCore::getArrVal($mod, 'is_photos', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'is_photos', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_photos" <?php if (!cmsCore::getArrVal($mod, 'is_photos', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MINI_PHOTO_WIDTH'];?></label>
            <div class="col-sm-7">
                <input type="number" class="form-control" name="thumb1" size="5" min="0" value="<?php echo cmsCore::getArrVal($mod, 'thumb1', ''); ?>"/>
                <div class="help-block"><?php echo $_LANG['AD_IN_PIXELS']; ?></div>
                <div>
                    <label><?php echo $_LANG['AD_SQUARE']; ?></label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'active'; } ?>">
                            <input type="radio" name="thumbsqr" <?php if(cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'active'; } ?>">
                            <input type="radio" name="thumbsqr" <?php if (!cmsCore::getArrVal($mod, 'thumbsqr', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MIDI_PHOTO_WIDTH'];?></label>
            <div class="col-sm-7">
                <input type="number" class="form-control" name="thumb2" size="5" min="0" value="<?php echo cmsCore::getArrVal($mod, 'thumb2', ''); ?>"/>
                <div class="help-block"><?php echo $_LANG['AD_IN_PIXELS']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_TYPES_AD'];?></label>
            <div class="col-sm-7">
                <textarea class="form-control" name="obtypes" rows="6"><?php echo cmsCore::getArrVal($mod, 'obtypes', ''); ?></textarea>
                <div class="help-block"><?php echo $_LANG['AD_NEW_LINE_TYPES']; ?></div>
                <div class="help-block"><?php echo $_LANG['AD_PARENT_CAT_DEFAULT']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['SEO_PAGETITLE'];?></label>
            <div class="col-sm-7">
                <textarea class="form-control" name="pagetitle" rows="2"><?php echo cmsCore::getArrVal($mod, 'pagetitle', ''); ?></textarea>
                <div class="help-block"><?php echo $_LANG['SEO_PAGETITLE_HINT']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['SEO_METAKEYS'];?></label>
            <div class="col-sm-7">
                <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($mod, 'meta_keys', ''); ?></textarea>
                <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['SEO_METADESCR'];?></label>
            <div class="col-sm-7">
                <textarea class="form-control" name="meta_desc" rows="4"><?php echo cmsCore::getArrVal($mod, 'meta_desc', ''); ?></textarea>
                <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CAT_DESCRIPTION'];?></label>
            <div class="col-sm-7">
                <textarea class="form-control" name="description" rows="6"><?php echo cmsCore::getArrVal($mod, 'description', ''); ?></textarea>
            </div>
        </div>
    </div>
    <div>
        <input name="opt" type="hidden" value="<?php if ($opt == 'add_cat') { echo 'submit_cat'; } else { echo 'update_cat'; } ?>" />
        
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
        <?php
            if ($opt == 'edit_cat') {
                echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
<?php
}
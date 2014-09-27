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

$opt = cmsCore::request('opt', 'str', 'config');

$toolmenu = array(
    array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.optform.submit();' ),
    array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components' )
);

cpToolMenu($toolmenu);

$cfg = $inCore->loadComponentConfig('rssfeed');

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    $cfg = array();
    $cfg['addsite']  = cmsCore::request('addsite', 'int');
    $cfg['maxitems'] = cmsCore::request('maxitems', 'int');
    $cfg['icon_on']  = cmsCore::request('icon_on', 'int');
    $cfg['icon_url'] = cmsCore::request('icon_url', 'str', '');
    
    $inCore->saveComponentConfig('rssfeed', $cfg);
    
    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

?>
<form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id;?>" method="post" name="optform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:650px;">
        <fieldset>
            <legend><?php echo $_LANG['AD_FEEDS']; ?></legend>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_RSS_CHANNELS']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'addsite', false)) { echo 'active'; } ?>">
                        <input type="radio" name="addsite" <?php if(cmsCore::getArrVal($cfg, 'addsite', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'addsite', false)) { echo 'active'; } ?>">
                        <input type="radio" name="addsite" <?php if (!cmsCore::getArrVal($cfg, 'addsite', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_NUMBER_DISPLAY']; ?> (<?php echo $_LANG['AD_PIECES']; ?>):</label>
                <input type="number" class="form-control" name="maxitems" min="0" value="<?php echo $cfg['maxitems']; ?>" />
            </div>
        </fieldset>
        
        <fieldset>
            <legend><?php echo $_LANG['AD_RSS_ICON']; ?></legend>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_RSS_ICON']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'icon_on', false)) { echo 'active'; } ?>">
                        <input type="radio" name="icon_on" <?php if(cmsCore::getArrVal($cfg, 'icon_on', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'icon_on', false)) { echo 'active'; } ?>">
                        <input type="radio" name="icon_on" <?php if (!cmsCore::getArrVal($cfg, 'icon_on', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_RSS_ICON_URL']; ?>:</label>
                <input type="text" class="form-control" name="icon_url" size="45" value="<?php echo $cfg['icon_url']; ?>" />
            </div>
        </fieldset>
    </div>
    
    <div style="margin-top:10px;">
        <input type="hidden" name="opt" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';" />
    </div>
</form>
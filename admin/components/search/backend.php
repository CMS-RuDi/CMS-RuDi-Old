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
function getProvidersList() {
    $pdir = opendir(PATH .'/components/search/providers/');
    if (!$pdir) { return false; }
    
    $provider_array = array();
    
    while ($provider = readdir($pdir)) {
        if (($provider != '.') && ($provider != '..') && !is_dir(PATH .'/components/search/providers/'. $provider)) {
            $provider = mb_substr($provider, 0, mb_strrpos($provider, '.'));
            $provider_array[] = $provider;
        }
    }
    
    closedir($pdir);
    
    return $provider_array;
}

cmsCore::loadModel('search');
$model = cms_model_search::initModel();

$opt = cmsCore::request('opt', 'str', '');

$toolmenu = array(
    array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.optform.submit();' ),
    array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components' )
);

cpToolMenu($toolmenu);

if ($opt == 'save') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    $cfg = array();
    $cfg['perpage'] = cmsCore::request('perpage', 'int', 15);
    $cfg['comp']    = cmsCore::request('comp', 'array_str');
    $cfg['search_engine'] = preg_replace('/[^a-z_]/i', '', cmsCore::request('search_engine', 'str', ''));
    
    if (
        $model->config['search_engine'] &&
        class_exists($model->config['search_engine']) &&
        method_exists($model->config['search_engine'], 'getProviderConfig')
    ) {
        foreach ($model->getProviderConfig() as $key => $value) {
            $cfg[$model->config['search_engine']][$value] = cmsCore::request($value, 'str', '');
        }
    }
    
    $inCore->saveComponentConfig('search', $cfg);
    
    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'dropcache') {
    $model->truncateResults();
}

?>
<form action="index.php?view=components&do=config&id=<?php echo $id; ?>" name="optform" method="post" target="_self">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:500px">
        <div class="form-group">
            <label><?php echo $_LANG['AD_RESULTS_PAGE']; ?>:</label>
            <input type="number" class="form-control" name="perpage" min="0" value="<?php echo $model->config['perpage']; ?>" size="6" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SEARCH_PROVIDER']; ?>:</label>
            <select class="form-control" name="search_engine">
                <option value="" <?php if (!$model->config['search_engine']) {?>selected="selected"<?php } ?>><?php echo $_LANG['AD_NATIVE']; ?></option>
                <?php
                $provider_array = getProvidersList();
                if ($provider_array) {
                    foreach($provider_array as $provider){
                ?>
                        <option value="<?php echo $provider; ?>" <?php if ($model->config['search_engine'] == $provider) {?>selected="selected"<?php } ?>><?php echo str_replace('_', ' ', $provider); ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>
        
        <?php
            if (
                $model->config['search_engine'] &&
                class_exists($model->config['search_engine']) &&
                method_exists($model->config['search_engine'], 'getProviderConfig')
            ) {
                foreach ($model->getProviderConfig() as $key => $value) {
        ?>
                    <div class="form-group">
                        <label><?php echo cmsCore::getArrVal($_LANG, $key, $value); ?>:</label>
                        <input type="text" class="form-control" name="<?php echo $value; ?>" value="<?php echo $model->config[$model->config['search_engine']][$value]; ?>" />
                    </div>
        <?php
                }
            }
	?>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SEARCH_COMPONENTS']; ?>:</label>
            <?php
                foreach ($model->components as $component) {
                    $checked = '';
                    if (in_array($component['link'], $model->config['comp'])) {
                        $checked = 'checked="checked"';
                    }
                    echo '<div class="checkbox"><label><input name="comp[]" id="'. $component['link'] .'" type="checkbox" value="'.$component['link'].'" '. $checked .'/>'. $component['title'] .'</label></div>';
                }
            ?>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SEARCH_CASH']; ?>:</label>
            <?php
                $records = cmsCore::c('db')->rows_count('cms_search', '1=1');
                echo $records .' '. $_LANG['AD_PIECES'];
                if ($records) {
                    echo ' | <a href="?view=components&do=config&id='. $id .'&opt=dropcache">'. $_LANG['AD_CLEAN'] .'</a>';
                }
            ?>
        </div>
    </div>
    
    <div>
        <input type="hidden" name="opt" value="save" />
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';" />
    </div>
</form>
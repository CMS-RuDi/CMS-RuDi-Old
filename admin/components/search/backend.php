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

$tpl = cmsCore::c('page')->initTemplate('components', 'search_config')->
    assign('provider_array', getProvidersList())->
    assign('components', $model->components)->
    assign('records', cmsCore::c('db')->rows_count('cms_search', '1=1'))->
    assign('cfg', $model->config);

if (
    $cfg['search_engine'] &&
    class_exists($cfg['search_engine']) &&
    method_exists($cfg['search_engine'], 'getProviderConfig')
) {
    $tpl->assign('provider_config', $model->getProviderConfig());
}

$tpl->display();
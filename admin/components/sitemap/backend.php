<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

$components = cmsCore::m('sitemap')->getComponents(true);
$opt = cmsCore::request('opt', array('config', 'saveconfig'), 'config');

if ($opt == 'config') {
    foreach ($components as $k => $com) {
        $config = cmsCore::getArrVal(cmsCore::m('sitemap')->config, $com['link']);
        $com_config_fields = cmsCore::m('sitemap')->getSitemapClass($com['link'])->getConfig();

        if (!empty($com_config_fields)) {
            $components[$k]['form_html'] = cmsCore::c('form_gen')->generateForm($com_config_fields, $config, 'rudiFormGen.php', $com['link'], false);
        }
    }
    
    cmsCore::c('page')->initTemplate('components', 'sitemap_config')->
        assign('components', $components)->
        assign('cfg', cmsCore::m('sitemap')->config)->
        display();
}

if ($opt == 'saveconfig') {
    if (empty($components)) {
        cmsCore::addSessionMessage('В системе нет ни одного компонента поддерживающего генерацию карты.', 'error');
    }
    
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    $cfg = array();
    
    $cfg['perpage'] = cmsCore::request('perpage', 'int', 100);
    $cfg['html_map_enable'] = cmsCore::request('html_map_enable', 'int', 0);
    
    foreach ($components as $com) {
        $cfg[$com['link']] = cmsCore::request('sitemap_'. $com['link'], 'array', array());
        $com_config_fields = cmsCore::m('sitemap')->getSitemapClass($com['link'])->getConfig();
                    
        if (!empty($com_config_fields)) {
            $com_config = cmsCore::c('form_gen')->requestForm($com_config_fields, $com['link']);
            $cfg[$com['link']] = array_merge($cfg[$com['link']], $com_config);
        }
    }
    
    $inCore->saveComponentConfig('sitemap', $cfg);
    
    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}
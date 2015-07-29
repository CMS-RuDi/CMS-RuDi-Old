<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                          LICENSED BY GNU/GPL v2                            //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_templates() {
    global $adminAccess;
    global $_LANG;
    
    if (!cmsUser::isAdminCan('admin/config', $adminAccess)) { cpAccessDenied(); }
    
    $do = cmsCore::request('do', array('config', 'save_config'), 'main');
    
    cmsCore::c('page')->setTitle($_LANG['AD_TEMPLATES_SETTING']);
    cpAddPathway($_LANG['AD_TEMPLATES_SETTING'], 'index.php?view=templates');
    
    if ($do == 'main') {
        cmsCore::c('page')->initTemplate('applets', 'templates')->
            assign('templates', cmsCore::getDirsList('/templates'))->
            display();
    }
    
    if ($do == 'config') {
        $template = cmsCore::request('template', 'str', '');
        
        cpAddPathway($_LANG['AD_TEMPLATE'] .': '. $template, 'index.php?view=templates&do=config&template='. $template);

        if (!file_exists(PATH .'/templates/'. $template) || !file_exists(PATH .'/templates/'. $template .'/cfg_fields.json')) {
            cmsCore::error404();
        }
        
        $tpl_cfgs = cmsCore::getTplCfgFields($template);
        
        if (!empty($tpl_cfgs)) {
            if (!empty($tpl_cfgs)) {
                $tpl_cfgs_val = cmsCore::getTplCfg($template);
                
                cmsCore::c('page')->initTemplate('applets', 'templates_config')->
                    assign('template', $template)->
                    assign('form_gen_form', cmsCore::c('form_gen')->generateForm($tpl_cfgs, $tpl_cfgs_val))->
                    display();
            } else {
                cmsCore::addSessionMessage($_LANG['AD_TEMPLATE_NO_CONFIG'], 'error');
                cmsCore::redirectBack();
            }
        } else {
            cmsCore::addSessionMessage($_LANG['AD_TEMPLATE_CFG_ERROR'], 'error');
            cmsCore::redirectBack();
        }
    }
    
    if ($do == 'save_config') {
        $template = cmsCore::request('template', 'str', '');
        
        if (!file_exists(PATH .'/templates/'. $template) || !file_exists(PATH .'/templates/'. $template .'/cfg_fields.json') || !cmsUser::checkCsrfToken()) {
            cmsCore::error404();
        }

        $tpl_cfgs = cmsCore::getTplCfgFields($template);

        if (!empty($tpl_cfgs)) {
            if (!empty($tpl_cfgs)) {
                $tpl_cfgs = cmsCore::c('form_gen')->requestForm($tpl_cfgs);
                cmsCore::saveTplCfg($tpl_cfgs, $template);
                
                cmsCore::addSessionMessage($_LANG['AD_TEMPLATE_CFG_SAVED'], 'success');
                cmsCore::redirect('/admin/index.php?view=templates');
            } else {
                cmsCore::error404();
            }
        } else {
            cmsCore::error404();
        }
    }
}
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
        $templates = cmsCore::getDirsList('/templates');
        echo '<div class="panel panel-default"><div class="panel-heading">'. $_LANG['AD_TEMPLATES_LIST'] .'</div><div class="panel-body">';
        echo '<table class="table table-striped"><thead><tr>';
        echo '<th>'. $_LANG['AD_TEMPLATE'] .'</th>';
        echo '<th width="200">'. $_LANG['AD_TEMPLATE_AUTHOR'] .'</th>';
        echo '<th width="200">'. $_LANG['AD_TEMPLATE_RENDERER'] .'</th>';
        echo '<th width="200">'. $_LANG['AD_TEMPLATE_EXT'] .'</th><th width="100"></th><th width="100"></th>';
        echo '</tr></thead><tbody>';
        foreach ($templates as $template) {
            if ($template == 'admin') { continue; }
            $tpl_info = cmsCore::c('page')->getTplInfo($template);
            
            echo '<tr>';
            echo '<td><strong>'. $template .'</strong></td>';
            echo '<td>'. $tpl_info['author'] .'</td>';
            echo '<td>'. $tpl_info['renderer'] .'</td>';
            echo '<td>'. $tpl_info['ext'] .'</td><td>';
            
            if (file_exists(PATH .'/templates/'. $template .'/positions.jpg')) {
                echo '<a href="#'. $template .'" role="button" class="btn btn-sm btn-default" data-toggle="modal">'. $_LANG['AD_TPL_POS'] .'</a>
                <div class="modal fade" id="'. $template .'" tabindex="-1" role="dialog" aria-labelledby="'. $template .'Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="'. $template .'Label">'. $_LANG['AD_TPL_POS'] .'</h4>
                            </div>
                            <div class="modal-body">
                                <img src="/templates/'. $template .'/positions.jpg" alt="'. $_LANG['AD_TPL_POS'] .'" style="width:100%;height:auto;" />
                            </div>
                        </div>
                    </div>
                </div>';
            }
            
            echo '</td><td>';
            
            if (file_exists(PATH .'/templates/'. $template .'/config.php')) {
                echo '<a href="/admin/index.php?view=templates&do=config&template='. $template .'" class="btn btn-sm btn-primary">'. $_LANG['AD_CONFIG'] .'</a>';
            }
            
            echo '</td></tr>';
        }
        echo '</tbody></table></div></div>';
    }
    
    if ($do == 'config') {
        $template = cmsCore::request('template', 'str', '');
        
        cpAddPathway($_LANG['AD_TEMPLATE'] .': '. $template, 'index.php?view=templates&do=config&template='. $template);

        if (!file_exists(PATH .'/templates/'. $template) || !file_exists(PATH .'/templates/'. $template .'/config.php')) {
            cmsCore::error404();
        }
        
        include(PATH .'/templates/'. $template .'/config.php');
        
        if (function_exists('get_template_cfg_fields')) {
            $tpl_cfgs  = get_template_cfg_fields();
            
            if (!empty($tpl_cfgs)) {
                $tpl_cfgs_val = cmsCore::getTplCfg($template);
                
                echo '<form action="/admin/index.php?view=templates&template='. $template .'&do=save_config" method="post" style="width:650px;margin-bottom:30px">';
                echo cmsCore::c('form_gen')->generateForm($tpl_cfgs, $tpl_cfgs_val);
                echo '<div>';
                echo '<input type="submit" class="btn btn-primary" name="save" value="'. $_LANG['SAVE'] .'" /> ';
                echo '<input type="button" class="btn btn-default" name="back" value="'. $_LANG['CANCEL'] .'" onclick="window.history.back();" />';
                echo '</div>';
                echo '</form>';
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
        
        if (!file_exists(PATH .'/templates/'. $template) || !file_exists(PATH .'/templates/'. $template .'/config.php') || !cmsUser::checkCsrfToken()) {
            cmsCore::error404();
        }

        include(PATH .'/templates/'. $template .'/config.php');
        
        if (function_exists('get_template_cfg_fields')) {
            $tpl_cfgs  = get_template_cfg_fields();
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
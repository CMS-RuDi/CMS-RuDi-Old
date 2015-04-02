<?php
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

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_config() {
    // получаем оригинальный конфиг
    $config = cmsConfig::getDefaultConfig();
    
    global $_LANG;
    
    global $adminAccess;
    
    if (!cmsUser::isAdminCan('admin/config', $adminAccess)) { cpAccessDenied(); }
    
    cmsCore::c('page')->setAdminTitle($_LANG['AD_SITE_SETTING']);

    cpAddPathway($_LANG['AD_SITE_SETTING'], 'index.php?view=config');

    $do = cmsCore::request('do', 'str', 'list');

    if ($do == 'save') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $newCFG = cmsCore::getArrayFromRequest(array(
            'sitename'                => array('sitename', 'str', ''),
            'title_and_sitename'      => array('title_and_sitename', 'int', 0),
            'title_and_page'          => array('title_and_page', 'int', 0),
            'hometitle'               => array('hometitle', 'str', ''),
            'homecom'                 => array('homecom', 'str', ''),
            'com_without_name_in_url' => array('com_without_name_in_url', 'str', ''),
            'siteoff'                 => array('siteoff', 'int', 0),
            'only_authorized'         => array('only_authorized', 'int', 0),
            'debug'                   => array('debug', 'int', 0),
            'offtext'                 => array('offtext', 'str', ''),
            'keywords'                => array('keywords', 'str', ''),
            'metadesc'                => array('metadesc', 'str', ''),
            'seourl'                  => array('seourl', 'int', 0),
            'lang'                    => array('lang', 'str', 'ru'),
            'is_change_lang'          => array('is_change_lang', 'int', 0),
            'sitemail'                => array('sitemail', 'str', ''),
            'sitemail_name'           => array('sitemail_name', 'str', ''),
            'wmark'                   => array('wmark', 'str', ''),
            'template'                => array('template', 'str', ''),
            'cache'                   => array('cache', 'int', 0),
            'cache_type'              => array('cache_type', array('file', 'memcached'), ''),
            'memcached_host'          => array('memcached_host', 'str', ''),
            'memcached_port'          => array('memcached_port', 'int', 0),
            'combine_css_enable'      => array('combine_css_enable', 'int', 0),
            'combine_css'             => array('combine_css', 'html', ''),
            'combine_js_enable'       => array('combine_js_enable', 'int', 0),
            'combine_js'              => array('combine_js', 'html', ''),
            'splash'                  => array('splash', 'int', 0),
            'slight'                  => array('slight', 'int', 0),
            'show_pw'                 => array('show_pw', 'int', 0),
            'last_item_pw'            => array('last_item_pw', 'int', 0),
            'index_pw'                => array('index_pw', 'int', 0),
            'fastcfg'                 => array('fastcfg', 'int', 0),
            'mailer'                  => array('mailer', 'str', ''),
            'smtpsecure'              => array('smtpsecure', 'str', ''),
            'smtpauth'                => array('smtpauth', 'int', 0),
            'smtpuser'                => array('smtpuser', 'str', $config['smtpuser']),
            'smtppass'                => array('smtppass', 'str', $config['smtppass']),
            'smtphost'                => array('smtphost', 'str', ''),
            'smtpport'                => array('smtpport', 'int', '25'),
            'timezone'                => array('timezone', 'str', $config['timezone']),
            'user_stats'              => array('user_stats', 'int', 0),
            'seo_url_count'           => array('seo_url_count', 'int', 0),
            'max_pagebar_links'       => array('max_pagebar_links', 'int', 0),
            'allow_ip'                => array('allow_ip', 'str', ''),
            'iframe_enable'           => array('iframe_enable', 'int', 0),
            'vk_enable'               => array('vk_enable', 'int', 0),
            'vk_id'                   => array('vk_id', 'str', ''),
            'vk_private_key'          => array('vk_private_key', 'str', ''),
        ));
        
        $newCFG['sitename']  = stripslashes($newCFG['sitename']);
        $newCFG['hometitle'] = stripslashes($newCFG['hometitle']);
        $newCFG['offtext']   = htmlspecialchars($newCFG['offtext'], ENT_QUOTES);
        $newCFG['db_host']   = $config['db_host'];
        $newCFG['db_base']   = $config['db_base'];
        $newCFG['db_user']   = $config['db_user'];
        $newCFG['db_pass']   = $config['db_pass'];
        $newCFG['db_prefix'] = $config['db_prefix'];
        
        if (cmsConfig::saveToFile($newCFG)) {
            cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'] , 'success');
        } else {
            cmsCore::addSessionMessage($_LANG['AD_CONFIG_SITE_ERROR'], 'error');
        }

        cmsCore::clearCache();
        cmsCore::redirect('index.php?view=config');
    }

    cpCheckWritable('/includes/config/config.inc.json');
?>
<form class="form-horizontal" role="form" action="/admin/index.php?view=config" method="post" name="CFGform" target="_self" id="CFGform" style="margin-bottom:30px">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div class="uitabs">
        <ul id="tabs">
            <li><a href="#basic"><span><?php echo $_LANG['AD_SITE']; ?></span></a></li>
            <li><a href="#home"><span><?php echo $_LANG['AD_MAIN']; ?></span></a></li>
            <li><a href="#cache"><span><?php echo $_LANG['AD_CACHE']; ?></span></a></li>
            <li><a href="#database"><span><?php echo $_LANG['AD_DB'] ; ?></span></a></li>
            <li><a href="#mail"><span><?php echo $_LANG['AD_POST']; ?></span></a></li>
            <li><a href="#other"><span><?php echo $_LANG['AD_PATHWAY']; ?></span></a></li>
            <li><a href="#seq"><span><?php echo $_LANG['AD_SECURITY']; ?></span></a></li>
            <li><a href="#soc_apps"><span><?php echo $_LANG['AD_SOC_APPS']; ?></span></a></li>
        </ul>
        
        <div id="basic">
            <div style="width:750px;">
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_TIME_ARREA']; ?></label>
                    <div class="col-sm-7">
                        <select id="timezone" class="form-control" name="timezone">
                            <?php echo cmsCore::getTimeZonesOptions($config['timezone']); ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SITENAME']; ?></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="sitename" value="<?php echo htmlspecialchars($config['sitename']);?>" />
                        <div class="help-block"><?php echo $_LANG['AD_USE_HEADER']; ?></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['TEMPLATE']; ?></label>
                    <div class="col-sm-7">
                        <select id="template" class="form-control" name="template" onchange="document.CFGform.submit();">
                        <?php
                            $templates = cmsCore::getDirsList('/templates');
                            foreach ($templates as $template) {
                                echo '<option value="'. $template .'" '. ($config['template'] == $template ? 'selected="selected"' : '') .'>'. $template .'</option>';
                            }

                            $tpl_info = cmsCore::c('page')->getCurrentTplInfo();
                        ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SEARCH_RESULT']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['slight']) { echo 'active'; } ?>">
                            <input type="radio" name="slight" <?php if ($config['slight']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['SHOW']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['slight']) { echo 'active'; } ?>">
                            <input type="radio" name="slight" <?php if (!$config['slight']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['HIDE']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_TAGE_ADD']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['title_and_sitename']) { echo 'active'; } ?>">
                            <input type="radio" name="title_and_sitename" <?php if ($config['title_and_sitename']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['title_and_sitename']) { echo 'active'; } ?>">
                            <input type="radio" name="title_and_sitename" <?php if (!$config['title_and_sitename']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_TAGE_ADD_PAGINATION']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['title_and_page']) { echo 'active'; } ?>">
                            <input type="radio" name="title_and_page" <?php if ($config['title_and_page']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['title_and_page']) { echo 'active'; } ?>">
                            <input type="radio" name="title_and_page" <?php if (!$config['title_and_page']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_COM_WITHOUT_NAME_IN_URL']; ?></label>
                    <div class="col-sm-7">
                        <select class="form-control" name="com_without_name_in_url">
                            <?php echo cmsCore::getListItems('cms_components', $config['com_without_name_in_url'], 'title', 'ASC', 'internal=0', 'link'); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['TEMPLATE_INTERFACE_LANG']; ?></label>
                    <div class="col-sm-7">
                        <select class="form-control" name="lang">
                        <?php
                            $langs = cmsCore::getDirsList('/languages');
                            foreach ($langs as $lng) {
                                echo '<option value="'. $lng .'" '. ($config['lang'] == $lng ? 'selected="selected"' : '') .'>'. $lng .'</option>';
                            }
                        ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SITE_LANGUAGE_CHANGE']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['is_change_lang']) { echo 'active'; } ?>">
                            <input type="radio" name="is_change_lang" <?php if ($config['is_change_lang']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['is_change_lang']) { echo 'active'; } ?>">
                            <input type="radio" name="is_change_lang" <?php if (!$config['is_change_lang']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                        <div style="clear:both;"></div>
                        <div class="help-block"><?php echo $_LANG['AD_VIEW_FORM_LANGUAGE_CHANGE']; ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SITE_ON']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if (!$config['siteoff']) { echo 'active'; } ?>">
                            <input type="radio" name="siteoff" <?php if (!$config['siteoff']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if ($config['siteoff']) { echo 'active'; } ?>">
                            <input type="radio" name="siteoff" <?php if ($config['siteoff']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['NO']; ?>
                        </label>
                        <div style="clear:both;"></div>
                        <div class="help-block"><?php echo $_LANG['AD_ONLY_ADMINS']; ?></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SITE_ONLY_AUTHORIZED']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['only_authorized']) { echo 'active'; } ?>">
                            <input type="radio" name="only_authorized" <?php if ($config['only_authorized']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['only_authorized']) { echo 'active'; } ?>">
                            <input type="radio" name="only_authorized" <?php if (!$config['only_authorized']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                        <div style="clear:both;"></div>
                        <div class="help-block"><?php echo $_LANG['AD_SITE_ONLY_AUTHORIZED_INFO']; ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_DEBUG_ON']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['debug']) { echo 'active'; } ?>">
                            <input type="radio" name="debug" <?php if ($config['debug']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['debug']) { echo 'active'; } ?>">
                            <input type="radio" name="debug" <?php if (!$config['debug']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                        <div style="clear:both;"></div>
                        <div class="help-block"><?php echo $_LANG['AD_WIEW_DB_ERRORS']; ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_WHY_STOP']; ?></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="offtext" value="<?php echo htmlspecialchars($config['offtext']); ?>" />
                        <div class="help-block"><?php echo $_LANG['AD_VIEW_WHY_STOP']; ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_WATERMARK']; ?></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="wmark" value="<?php echo $config['wmark']; ?>" />
                        <div class="help-block"><?php echo $_LANG['AD_WATERMARK_NAME']; ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_QUICK_CONFIG']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['fastcfg']) { echo 'active'; } ?>">
                            <input type="radio" name="fastcfg" <?php if ($config['fastcfg']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['fastcfg']) { echo 'active'; } ?>">
                            <input type="radio" name="fastcfg" <?php if (!$config['fastcfg']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                        <div style="clear:both;"></div>
                        <div class="help-block"><?php echo $_LANG['AD_MODULE_CONFIG']; ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ONLINESTATS']; ?></label>
                    <div class="col-sm-7">
                        <select class="form-control" name="user_stats">
                            <option value="0" <?php if (!$config['user_stats']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_NO_ONLINESTATS']; ?></option>
                            <option value="1" <?php if ($config['user_stats'] == 1) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_YES_ONLINESTATS']; ?></option>
                            <option value="2" <?php if ($config['user_stats'] == 2) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_CRON_ONLINESTATS']; ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SEO_URL_COUNT']; ?></label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control" name="seo_url_count" value="<?php echo $config['seo_url_count']; ?>" />
                        <div class="help-block"><?php echo $_LANG['AD_SEO_URL_COUNT_HINT']; ?></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PAGEBAR_MAX_LINKS']; ?></label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control" name="max_pagebar_links" value="<?php echo $config['max_pagebar_links']; ?>" />
                    </div>
                </div>
            </div>
        </div>
        
        <div id="home">
            <div style="width:750px;">
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MAIN_PAGE']; ?></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="hometitle" value="<?php echo htmlspecialchars($config['hometitle']); ?>" />
                        <div class="help-block"><?php echo $_LANG['AD_MAIN_SITENAME']; ?></div>
                        <div class="help-block"><?php echo $_LANG['AD_BROWSER_TITLE']; ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_KEY_WORDS']; ?></label>
                    <div class="col-sm-7">
                        <textarea class="form-control" name="keywords" rows="3"><?php echo $config['keywords']; ?></textarea>
                        <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                        <div class="help-block"><a style="color:#09C" href="http://tutorial.semonitor.ru/#5" target="_blank"><?php echo $_LANG['AD_WHAT_KEY_WORDS']; ?></a></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_DESCRIPTION']; ?></label>
                    <div class="col-sm-7">
                        <textarea class="form-control" name="metadesc" rows="3"><?php echo $config['metadesc']; ?></textarea>
                        <div class="help-block"><?php echo $_LANG['AD_LESS_THAN']; ?></div>
                        <div class="help-block"><a style="color:#09C" href="http://tutorial.semonitor.ru/#219" target="_blank"><?php echo $_LANG['AD_WHAT_DESCRIPTION']; ?></a></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MAIN_PAGE_COMPONENT']; ?></label>
                    <div class="col-sm-7">
                        <select class="form-control" name="homecom">
                            <option value="" <?php if (!$config['homecom']) { ?>selected="selected"<?php } ?>><?php echo $_LANG['AD_ONLY_MODULES']; ?></option>
                            <?php echo cmsCore::getListItems('cms_components', $config['homecom'], 'title', 'ASC', 'internal=0', 'link'); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_GATE_PAGE']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['splash']) { echo 'active'; } ?>">
                            <input type="radio" name="splash" <?php if ($config['splash']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['SHOW']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['splash']) { echo 'active'; } ?>">
                            <input type="radio" name="splash" <?php if (!$config['splash']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['HIDE']; ?>
                        </label>
                        <div style="clear:both;"></div>
                        <div class="help-block"><?php echo $_LANG['AD_FIRST_VISIT']; ?></div>
                        <div class="help-block"><?php echo $_LANG['AD_FIRST_VISIT_TEMPLATE']; ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="cache">
            <div style="width:750px;">
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CACHE']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['cache']) { echo 'active'; } ?>">
                            <input type="radio" name="cache" <?php if ($config['cache']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['cache']) { echo 'active'; } ?>">
                            <input type="radio" name="cache" <?php if (!$config['cache']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                        <div style="clear:both;"></div>
                        <div class="help-block">
                            <?php echo $_LANG['AD_CACHE_INFO']; ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_CACHE_TYPE']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <select class="form-control" name="cache_type" onchange="if ($(this).val() == 'memcached'){$('.memcached').show();}else{$('.memcached').hide();}">
                            <option value="file">File</option>
                            <?php if (class_exists('Memcached')) { ?>
                                <option value="memcached">Memcached</option>
                            <?php } ?>
                        </select>
                        <div class="help-block">
                            <?php echo $_LANG['AD_CACHE_TYPE_INFO']; ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group memcached" <?php if ($config['cache'] != 'memcached'){ ?>style="display:none;"<?php } ?>>
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MEMCACHED_HOST']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <input type="text" class="form-control" name="memcached_host" value="<?php echo $config['memcached_host']; ?>" />
                        <div class="help-block">
                            <?php echo $_LANG['AD_MEMCACHED_HOST_INFO']; ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group memcached" <?php if ($config['cache'] != 'memcached'){ ?>style="display:none;"<?php } ?>>
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MEMCACHED_PORT']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <input type="number" class="form-control" name="memcached_port" value="<?php echo $config['memcached_port']; ?>" />
                        <div class="help-block">
                            <?php echo $_LANG['AD_MEMCACHED_PORT_INFO']; ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-5 control-label">
                        <?php echo $_LANG['AD_COLLECT_CSS']; ?><br/>
                        <input type="checkbox" name="combine_css_enable" value="1" <?php if ($config['combine_css_enable']) { ?>checked="checked"<?php } ?> />
                        <?php echo $_LANG['AD_DO_ENABLE']; ?>
                    </label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <textarea class="form-control" style="height:150px;" name="combine_css"><?php echo cmsCore::getArrVal($config, 'combine_css', ''); ?></textarea>
                        <div class="help-block">
                            <?php echo $_LANG['AD_COLLECT_CSS_INFO']; ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-5 control-label">
                        <?php echo $_LANG['AD_COLLECT_JS']; ?>
                        <br/>
                        <input type="checkbox" name="combine_js_enable" value="1" <?php if ($config['combine_js_enable']) { ?>checked="checked"<?php } ?> />
                        <?php echo $_LANG['AD_DO_ENABLE']; ?>
                    </label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <textarea class="form-control" style="height:150px;" name="combine_js"><?php echo cmsCore::getArrVal($config, 'combine_js', ''); ?></textarea>
                        <div class="help-block">
                            <?php echo $_LANG['AD_COLLECT_JS_INFO']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="database">
            <div style="width:750px;">
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_DB_SIZE']; ?></label>
                    <div class="col-sm-7">
                        <?php
                            $result = cmsCore::c('db')->query("SELECT (sum(data_length)+sum(index_length))/1024/1024 as size FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '". $config['db_base'] ."'", true);
                            if (!cmsCore::c('db')->error()) {
                                $s = cmsCore::c('db')->fetch_assoc($result);
                                echo round($s['size'], 2) .' '. $_LANG['SIZE_MB'];
                            } else {
                                echo $_LANG['AD_DB_SIZE_ERROR'];
                            }
                        ?>
                        <div class="help-block"><?php echo $_LANG['AD_MYSQL_CONFIG']; ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="mail">
            <div style="width:750px;">
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SITE_EMAIL']; ?></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="sitemail" value="<?php echo $config['sitemail']; ?>" />
                        <div class="help-block"><?php echo $_LANG['AD_SITE_EMAIL_POST']; ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SENDER_EMAIL']; ?></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="sitemail_name" value="<?php echo $config['sitemail_name']; ?>" />
                        <div class="help-block"><?php echo $_LANG['AD_IF_NOT_HANDLER']; ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SEND_METHOD']; ?></label>
                    <div class="col-sm-7">
                        <select class="form-control" name="mailer">
                            <option value="mail" <?php if ($config['mailer'] == 'mail') { echo 'selected="selected"'; } ?>><?php echo  $_LANG['AD_PHP_MAILER']; ?></option>
                            <option value="sendmail" <?php if ($config['mailer'] == 'sendmail') { echo 'selected="selected"'; } ?>><?php echo  $_LANG['AD_SEND_MAILER']; ?></option>
                            <option value="smtp" <?php if ($config['mailer'] == 'smtp') { echo 'selected="selected"'; } ?>><?php echo  $_LANG['AD_SMTP_MAILER']; ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ENCRYPTING']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if (!$config['smtpsecure']) { echo 'active'; } ?>">
                            <input type="radio" name="smtpsecure" <?php if (!$config['smtpsecure']) { echo 'checked="checked"'; } ?> value="" /> <?php echo $_LANG['NO']; ?>
                        </label>

                        <label class="btn btn-default <?php if ($config['smtpsecure'] == 'tls') { echo 'active'; } ?>">
                            <input type="radio" name="smtpsecure" <?php if ($config['smtpsecure'] == 'tls') { echo 'checked="checked"'; } ?> value="tls" /> tls
                        </label>

                        <label class="btn btn-default <?php if ($config['smtpsecure'] == 'ssl') { echo 'active'; } ?>">
                            <input type="radio" name="smtpsecure" <?php if ($config['smtpsecure'] == 'ssl') { echo 'checked="checked"'; } ?> value="ssl" /> ssl
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SMTP_LOGIN']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['smtpauth']) { echo 'active'; } ?>">
                            <input type="radio" name="smtpauth" <?php if ($config['smtpauth']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['smtpauth']) { echo 'active'; } ?>">
                            <input type="radio" name="smtpauth" <?php if (!$config['smtpauth']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SMTP_USER']; ?></label>
                    <div class="col-sm-7">
                        <?php if (!$config['smtpuser']) { ?>
                            <input type="text" class="form-control" name="smtpuser" value="<?php echo $config['smtpuser']; ?>" />
                        <?php } else { ?>
                            <div class="help-block"><?php echo $_LANG['AD_IF_CHANGE_USER']; ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SMTP_PASS']; ?></label>
                    <div class="col-sm-7">
                        <?php if (!$config['smtppass']) { ?>
                            <input type="text" class="form-control" name="smtppass" value="<?php echo $config['smtppass']; ?>" />
                        <?php } else { ?>
                            <div class="help-block"><?php echo $_LANG['AD_IF_CHANGE_PASS']; ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SMTP_HOST']; ?></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="smtphost" value="<?php echo $config['smtphost']; ?>" />
                        <div class="help-block"><?php echo $_LANG['AD_SOME_HOST']; ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SMTP_PORT']; ?></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="smtpport" value="<?php echo $config['smtpport']; ?>" />
                    </div>
                </div>
            </div>
        </div>
        
        <div id="other">
            <div style="width:750px;">
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_VIEW_PATHWAY']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['show_pw']) { echo 'active'; } ?>">
                            <input type="radio" name="show_pw" <?php if ($config['show_pw']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['show_pw']) { echo 'active'; } ?>">
                            <input type="radio" name="show_pw" <?php if (!$config['show_pw']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                        <div style="clear:both;"></div>
                        <div class="help-block"><?php echo $_LANG['AD_PATH_TO_CATEGORY']; ?></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MAINPAGE_PATHWAY']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if ($config['index_pw']) { echo 'active'; } ?>">
                            <input type="radio" name="index_pw" <?php if ($config['index_pw']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!$config['index_pw']) { echo 'active'; } ?>">
                            <input type="radio" name="index_pw" <?php if (!$config['index_pw']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_PAGE_PATHWAY']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if (!$config['last_item_pw']) { echo 'active'; } ?>">
                            <input type="radio" name="last_item_pw" <?php if (!$config['last_item_pw']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['HIDE']; ?>
                        </label>

                        <label class="btn btn-default <?php if ($config['last_item_pw'] == 1) { echo 'active'; } ?>">
                            <input type="radio" name="last_item_pw" <?php if (!$config['last_item_pw'] == 1) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['AD_PAGE_PATHWAY_LINK']; ?>
                        </label>

                        <label class="btn btn-default <?php if ($config['last_item_pw'] == 2) { echo 'active'; } ?>">
                            <input type="radio" name="last_item_pw" <?php if (!$config['last_item_pw'] == 2) { echo 'checked="checked"'; } ?> value="2" /> <?php echo $_LANG['AD_PAGE_PATHWAY_TEXT']; ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="seq">
            <div style="width:750px;">
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_IP_ADMIN']; ?></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="allow_ip" value="<?php echo htmlspecialchars($config['allow_ip']); ?>" />
                        <div class="help-block"><?php echo $_LANG['AD_IP_COMMA']; ?></div>
                    </div>
                </div>

                <p style="color:#900"><?php echo $_LANG['AD_ATTENTION']; ?></p>
            </div>
        </div>     
    
        <div id="soc_apps">
            <div style="width:750px;">
                <div class="form-group">
                    <label class="col-sm-5 control-label"><?php echo $_LANG['AD_IFRAME_ENABLE']; ?></label>
                    <div class="col-sm-7 btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($config, 'iframe_enable')) { echo 'active'; } ?>">
                            <input type="radio" name="iframe_enable" <?php if (cmsCore::getArrVal($config, 'iframe_enable')) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($config, 'iframe_enable')) { echo 'active'; } ?>">
                            <input type="radio" name="iframe_enable" <?php if (!cmsCore::getArrVal($config, 'iframe_enable')) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>

                <fieldset>
                    <legend>VK.COM</legend>
                    
                    <div class="form-group">
                        <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ENABLE']; ?></label>
                        <div class="col-sm-7 btn-group" data-toggle="buttons">
                            <label class="btn btn-default <?php if ($config['vk_enable']) { echo 'active'; } ?>">
                                <input type="radio" name="vk_enable" <?php if ($config['vk_enable']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                            </label>
                            <label class="btn btn-default <?php if (!$config['vk_enable']) { echo 'active'; } ?>">
                                <input type="radio" name="vk_enable" <?php if (!$config['vk_enable']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-5 control-label"><?php echo $_LANG['AD_IFRAME_APP_ID']; ?></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="vk_id" value="<?php echo $config['vk_id']; ?>" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-5 control-label"><?php echo $_LANG['AD_IFRAME_APP_PRIVATE_KEY']; ?></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="vk_private_key" value="<?php echo $config['vk_private_key']; ?>" />
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    
    <div>
        <input type="hidden" name="do" value="save" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />
    </div>
</form>
<?php
}
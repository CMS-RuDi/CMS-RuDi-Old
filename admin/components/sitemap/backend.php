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
    if (empty($components)) {
        echo '<p>В системе нет ни одного компонента поддерживающего генерацию карты.</p>';
    } else {

    ?>
<form action="index.php?view=components&do=config&id=<?php echo $id; ?>&opt=saveconfig" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:550px;">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#sitemap_components" role="tab" data-toggle="tab">Компоненты</a></li>
            <li><a href="#sitemap_cfg" role="tab" data-toggle="tab">Настройки</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="sitemap_components">
                <div class="form-group">
                    <h4>Включите компоненты для которых нужно генерировать карту сайта.</h4>
                </div>

                <?php foreach ($components as $com) {
                    $config = cmsCore::getArrVal(cmsCore::m('sitemap')->config, $com['link']);
                ?>
                <fieldset>
                    <legend><?php echo $com['title']; ?></legend>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_DO_ENABLE']; ?></label>

                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default <?php if (cmsCore::getArrVal($config, 'published')) { echo 'active'; } ?>" onclick="toggleSitemapComCfg('<?php echo $com['link']; ?>', 'show');">
                                <input type="radio" name="sitemap_<?php echo $com['link']; ?>[published]" <?php if (cmsCore::getArrVal($config, 'published')) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                            </label>
                            <label class="btn btn-default <?php if (!cmsCore::getArrVal($config, 'published')) { echo 'active'; } ?>" onclick="toggleSitemapComCfg('<?php echo $com['link']; ?>', 'hide');">
                                <input type="radio" name="sitemap_<?php echo $com['link']; ?>[published]" <?php if (!cmsCore::getArrVal($config, 'published')) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                            </label>
                        </div>

                        <div <?php if (!cmsCore::getArrVal($config, 'published')) { echo 'style="display:none;"'; } ?> id="sitemap_<?php echo $com['link']; ?>">
                            <div class="form-group">
                                <label class="col-sm-8">XML или HTML карту генерировать?</label>
                                <div class="col-sm-4">
                                    <select name="sitemap_<?php echo $com['link']; ?>[mode]" class="form-control">
                                        <option value="all" <?php if (cmsCore::getArrVal($config, 'mode', 'all') == 'all') { echo 'selected="selected"'; } ?>>XML и HTML</option>
                                        <option value="xml_map" <?php if (cmsCore::getArrVal($config, 'mode', 'all') == 'xml_map') { echo 'selected="selected"'; } ?>>Только XML</option>
                                        <option value="html_map" <?php if (cmsCore::getArrVal($config, 'mode', 'all') == 'html_map') { echo 'selected="selected"'; } ?>>Только HTML</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-8">Период перегенерации карты (часы)</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" name="sitemap_<?php echo $com['link']; ?>[regen_time]" value="<?php echo cmsCore::getArrVal($config, 'regen_time', 24); ?>" min="0" />
                                </div>
                            </div>
                            
                            <div style="margin-left:30px;">
                            <?php
                                $com_config_fields = cmsCore::m('sitemap')->getSitemapClass($com['link'])->getConfig();

                                if (!empty($com_config_fields)) {
                                    echo cmsCore::c('form_gen')->generateForm($com_config_fields, $config, 'rudiFormGen.php', $com['link'], false);
                                }
                            ?>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <?php } ?>
            </div>
            <div class="tab-pane" id="sitemap_cfg">
                <div class="form-group" style="margin-top: 15px;">
                    <label>Показывать html карту?</label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal(cmsCore::m('sitemap')->config, 'html_map_enable')) { echo 'active'; } ?>" onclick="toggleSitemapComCfg('<?php echo $com['link']; ?>', 'show');">
                            <input type="radio" name="html_map_enable" <?php if (cmsCore::getArrVal(cmsCore::m('sitemap')->config, 'html_map_enable')) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal(cmsCore::m('sitemap')->config, 'html_map_enable')) { echo 'active'; } ?>" onclick="toggleSitemapComCfg('<?php echo $com['link']; ?>', 'hide');">
                            <input type="radio" name="html_map_enable" <?php if (!cmsCore::getArrVal(cmsCore::m('sitemap')->config, 'html_map_enable')) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block">
                        Настроить показ html карты для каждого компонента в отдельности можно в соседней вкладке.
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Количество материалов на одной странице для html карты</label>
                    <input type="number" class="form-control" name="perpage" value="<?php echo cmsCore::getArrVal(cmsCore::m('sitemap')->config, 'perpage', 100); ?>" min="0" />
                    <div class="help-block">
                        Если указан 0 будут выводиться все материалы без пагинации
                    </div>
                </div>
            </div>

            <div style="margin-top: 10px;">
                <input type="submit" class="btn btn-primary" value="<?php echo $_LANG['SAVE']; ?>" />
                <input type="button" class="btn btn-default" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.go(-1);" />
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    function toggleSitemapComCfg($link, $do) {
        if ($do == 'show') {
            $('#sitemap_'+ $link).show();
        } else {
            $('#sitemap_'+ $link).hide();
        }
    }
</script>
    <?php

    }
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
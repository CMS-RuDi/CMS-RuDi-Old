<?php if (empty($components)) { ?>
<form action="index.php?view=components&do=config&link=sitemap&opt=saveconfig" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:550px;">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#sitemap_components" role="tab" data-toggle="tab">
                    <?php echo $_LANG['AD_COMPONENTS']; ?>
                </a>
            </li>
            <li>
                <a href="#sitemap_cfg" role="tab" data-toggle="tab">
                    <?php echo $_LANG['AD_CONFIG']; ?>
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="sitemap_components">
                <div class="form-group">
                    <h4><?php echo $_LANG['AD_ENABLE_COM']; ?></h4>
                </div>

                <?php foreach ($components as $com) {
                    $config = cmsCore::getArrVal($cfg, $com['link']);
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
                                <label class="col-sm-8"><?php echo $_LANG['AD_XML_OR_HTML']; ?></label>
                                <div class="col-sm-4">
                                    <select name="sitemap_<?php echo $com['link']; ?>[mode]" class="form-control">
                                        <option value="all" <?php if (cmsCore::getArrVal($config, 'mode', 'all') == 'all') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_XMLHTML']; ?></option>
                                        <option value="xml_map" <?php if (cmsCore::getArrVal($config, 'mode', 'all') == 'xml_map') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_XML']; ?></option>
                                        <option value="html_map" <?php if (cmsCore::getArrVal($config, 'mode', 'all') == 'html_map') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_HTML']; ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-8"><?php echo $_LANG['AD_REGEN_TIME']; ?></label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" name="sitemap_<?php echo $com['link']; ?>[regen_time]" value="<?php echo cmsCore::getArrVal($config, 'regen_time', 24); ?>" min="0" />
                                </div>
                            </div>
                            
                            <?php if (!empty($com['form_html'])) { ?>
                                <div style="margin-left:30px;">
                                    <?php echo $com['form_html']; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </fieldset>
                <?php } ?>
            </div>
            <div class="tab-pane" id="sitemap_cfg">
                <div class="form-group" style="margin-top: 15px;">
                    <label><?php echo $_LANG['AD_SHOW_HTML']; ?></label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if (cmsCore::getArrVal($cfg, 'html_map_enable')) { echo 'active'; } ?>" onclick="toggleSitemapComCfg('<?php echo $com['link']; ?>', 'show');">
                            <input type="radio" name="html_map_enable" <?php if (cmsCore::getArrVal($cfg, 'html_map_enable')) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'html_map_enable')) { echo 'active'; } ?>" onclick="toggleSitemapComCfg('<?php echo $com['link']; ?>', 'hide');">
                            <input type="radio" name="html_map_enable" <?php if (!cmsCore::getArrVal($cfg, 'html_map_enable')) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block">
                        <?php echo $_LANG['AD_SHOW_HTML_INFO']; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PERPAGE']; ?></label>
                    <input type="number" class="form-control" name="perpage" value="<?php echo cmsCore::getArrVal($cfg, 'perpage', 100); ?>" min="0" />
                    <div class="help-block">
                        <?php echo $_LANG['AD_PERPAGE_INFO']; ?>
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
<?php } else { ?>
    <p><?php echo $_LANG['AD_SUPPORT_COMS_EMPTY']; ?></p>
<?php } ?>

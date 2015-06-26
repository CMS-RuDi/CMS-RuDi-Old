<form action="index.php?view=components&amp;do=config&amp;link=board" method="post" name="optform" target="_self" id="form1">
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
            <?php if (!$is_billing) { ?>
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
<h3><?php echo $_LANG['AD_SETTINGS']; ?></h3>

<form id="form1" class="form-horizontal" role="form" name="optform" action="index.php?view=components&do=config&link=comments" method="post" target="_self">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <div style="width:650px;" class="uitabs">
        <ul>
            <li><a href="#basic"><span><?php echo $_LANG['AD_OVERALL']; ?></span></a></li>
            <li><a href="#format"><span><?php echo $_LANG['AD_FORMAT']; ?></span></a></li>
            <li><a href="#access"><span><?php echo $_LANG['AD_TAB_ACCESS']; ?></span></a></li>
            <li><a href="#restrict"><span><?php echo $_LANG['AD_LIMIT']; ?></span></a></li>
            <li><a href="#seo"><span><?php echo $_LANG['AD_SEO']; ?></span></a></li>
        </ul>
        
        <div id="seo">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ROOT_METAKEYS']; ?>:</label>
                <div class="col-sm-7">
                    <textarea class="form-control" name="meta_keys" rows="2"><?php echo $cfg['meta_keys'] ?></textarea>
                    <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ROOT_METADESC']; ?>:</label>
                <div class="col-sm-7">
                    <textarea class="form-control" name="meta_keys" rows="2"><?php echo $cfg['meta_desc'] ?></textarea>
                    <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
                </div>
            </div>
        </div>

        <div id="basic">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_COMENT_EMAIL']; ?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" name="email" size="30" value="<?php echo $cfg['email']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_NO_EMAIL']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SUBSCRIPTION']; ?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'subscribe', false)) { echo 'active'; } ?>">
                        <input type="radio" name="subscribe" <?php if(cmsCore::getArrVal($cfg, 'subscribe', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'subscribe', false)) { echo 'active'; } ?>">
                        <input type="radio" name="subscribe" <?php if (!cmsCore::getArrVal($cfg, 'subscribe', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_GET_MESSAGE']; ?></div>
                </div>
            </div>
        </div>

        <div id="format">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_IF_AJAX']; ?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'cmm_ajax', false)) { echo 'active'; } ?>">
                        <input type="radio" name="cmm_ajax" <?php if(cmsCore::getArrVal($cfg, 'cmm_ajax', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'cmm_ajax', false)) { echo 'active'; } ?>">
                        <input type="radio" name="cmm_ajax" <?php if (!cmsCore::getArrVal($cfg, 'cmm_ajax', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_WATERMARK']; ?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                        <input type="radio" name="watermark" <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                        <input type="radio" name="watermark" <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_ABOUT_NEW_COMENT']; ?></label>
                <div class="col-sm-7">
                    <?php echo '/languages/'. cmsCore::c('config')->template .'/letters/newcomment.txt'; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_MAX_LEVEL']; ?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="max_level" min="0" value="<?php echo $cfg['max_level']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_HOW_MANY_COMENTS']; ?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="perpage" min="0" value="<?php echo $cfg['perpage']; ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_SHOW_IP']; ?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="cmm_ip">
                        <option value="0" <?php if ($cfg['cmm_ip'] == 0) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_HIDE_IP'];?></option>
                        <option value="1" <?php if ($cfg['cmm_ip'] == 1) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_ONLY_GUEST_IP'];?></option>
                        <option value="2" <?php if ($cfg['cmm_ip'] == 2) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_ALL_IP'];?></option>
                    </select>
                </div>
            </div>
        </div>

        <div id="access">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_NEED_CAPCA']; ?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="regcap">
                        <option value="0" <?php if ($cfg['regcap'] == 0) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_FOR_GUEST'];?></option>
                        <option value="1" <?php if ($cfg['regcap'] == 1) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_FOR_ALL'];?></option>
                    </select>
                    <div class="help-block"><?php echo $_LANG['AD_USERS_CAPCA'];?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_DISALLOW_EDIT']; ?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="edit_minutes">
                        <option value="0" <?php if (!$cfg['edit_minutes']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_AT_ONCE']; ?></option>
                        <option value="1" <?php if ($cfg['edit_minutes'] == 1) { echo 'selected="selected"'; } ?>>1 <?php echo $_LANG['MINUTU1']; ?></option>
                        <option value="5" <?php if ($cfg['edit_minutes'] == 5) { echo 'selected="selected"'; } ?>>5 <?php echo $_LANG['MINUTE10']; ?></option>
                        <option value="10" <?php if ($cfg['edit_minutes'] == 10) { echo 'selected="selected"'; } ?>>10 <?php echo $_LANG['MINUTE10']; ?></option>
                        <option value="15" <?php if ($cfg['edit_minutes'] == 15) { echo 'selected="selected"'; } ?>>15 <?php echo $_LANG['MINUTE10']; ?></option>
                        <option value="30" <?php if ($cfg['edit_minutes'] == 30) { echo 'selected="selected"'; } ?>>30 <?php echo $_LANG['MINUTE10']; ?></option>
                        <option value="60" <?php if ($cfg['edit_minutes'] == 60) { echo 'selected="selected"'; } ?>>1 <?php echo $_LANG['HOUR1']; ?></option>
                    </select>
                    <div class="help-block"><?php echo $_LANG['AD_DISALLOW_TIMER'];?></div>
                </div>
            </div>
        </div>

        <div id="restrict">
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_USE_LIMIT']; ?></label>
                <div class="col-sm-7 btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'active'; } ?>">
                        <input type="radio" name="min_karma" <?php if(cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'active'; } ?>">
                        <input type="radio" name="min_karma" <?php if (!cmsCore::getArrVal($cfg, 'min_karma', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                    <div style="clear:both;"></div>
                    <div class="help-block"><?php echo $_LANG['AD_ALLOW_ALL']; ?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_COMENT_ADD']; ?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="min_karma_add" min="0" value="<?php echo $cfg['min_karma_add']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_HOW_MANY_KARMA'];?></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-5 control-label"><?php echo $_LANG['AD_HIDE_COMENT']; ?></label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" name="min_karma_show" value="<?php echo $cfg['min_karma_show']; ?>" />
                    <div class="help-block"><?php echo $_LANG['AD_MIN_RATING']; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <input type="hidden" name="opt" value="saveconfig" />

        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&link=comments';"/>
    </div>
</form>
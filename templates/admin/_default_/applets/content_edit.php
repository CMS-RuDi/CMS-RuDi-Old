<form id="addform" name="addform" method="post" action="index.php" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <input type="hidden" name="view" value="content" />

    <table class="table">
        <tr>
            <!-- главная ячейка -->
            <td valign="top">
                <table width="100%" cellpadding="0" cellspacing="4" border="0">
                    <tr>
                        <td valign="top">
                            <label><?php echo $_LANG['AD_ARTICLE_NAME']; ?></label>
                            <div>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td><input type="text" class="form-control" name="title" value="<?php echo $this->escape($mod['title']);?>" /></td>
                                        <td style="width:15px;padding-left:10px;padding-right:10px;">
                                            <input type="checkbox" class="uittip" title="<?php echo $_LANG['AD_VIEW_TITLE']; ?>" name="showtitle" <?php if ($mod['showtitle'] || $do == 'add') { echo 'checked="checked"'; } ?> value="1">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td width="130" valign="top">
                            <label><?php echo $_LANG['AD_PUBLIC_DATE']; ?></label>
                            <div>
                                <input type="text" id="pubdate" class="form-control" name="pubdate" style="width:100px;display: inline-block" <?php if(empty($mod['pubdate'])) { echo 'value="'. date('d.m.Y') .'"'; } else { echo 'value="'. $mod['pubdate'] .'"'; } ?>/>

                                <input type="hidden" name="olddate" value="<?php echo cmsCore::getArrVal($mod, 'pubdate', ''); ?>" />
                            </div>
                        </td>
                        <td width="16" valign="bottom" style="padding-bottom:10px">
                            <input type="checkbox" id="showdate" class="uittip" name="showdate" title="<?php echo $_LANG['AD_VIEW_DATE_AND_AUTHOR']; ?>" value="1" <?php if ($mod['showdate'] || $do == 'add') { echo 'checked="checked"'; } ?>/>
                        </td>
                        <td width="160" valign="top">
                            <label><?php echo $_LANG['AD_ARTICLE_TEMPLATE']; ?></label>
                            <div><input type="text" class="form-control" style="width:160px" name="tpl" value="<?php echo cmsCore::getArrVal($mod, 'tpl'); ?>"></div>
                        </td>
                    </tr>
                </table>
                    
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ARTICLE_NOTICE']; ?></label>
                    <div><?php cmsCore::insertEditor('description', $mod['description'], '200', '100%'); ?></div>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_ARTICLE_TEXT']; ?></label>
                    <?php insertPanel(); ?>
                    <div><?php cmsCore::insertEditor('content', $mod['content'], '400', '100%'); ?></div>
                </div>
                    
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ARTICLE_TAGS']; ?></label>
                    <input type="text" id="tags" class="form-control" name="tags" value="<?php echo $this->escape($tags); ?>" />
                </div>

                <div>
                    <label>
                        <input type="radio" name="autokeys" <?php if ($do == 'add' && $cfg['autokeys']) { ?>checked="checked"<?php } ?> value="1"/>
                        <?php echo $_LANG['AD_AUTO_GEN_KEY']; ?>
                    </label>
                </div>
                <div>
                    <label>
                        <input type="radio" name="autokeys" value="2" />
                        <?php echo $_LANG['AD_TAGS_AS_KEY']; ?>
                    </label>
                </div>
                <div>
                    <label>
                        <input type="radio" name="autokeys" id="autokeys3" value="3" <?php if ($do == 'edit' || !$cfg['autokeys']) { ?>checked="checked"<?php } ?>/>
                        <?php echo $_LANG['AD_MANUAL_KEY']; ?>
                    </label>
                </div>
                    
                <?php if ($cfg['af_on'] && $do == 'add') { ?>
                <div>
                    <label>
                        <input type="checkbox" name="noforum" id="noforum" value="1" />
                        <?php echo $_LANG['AD_NO_CREATE_THEME']; ?>
                    </label>
                </div>
                <?php } ?>
            </td>

            <!-- боковая ячейка -->
            <td valign="top" style="width:450px">
                <div class="uitabs">
                    <ul id="tabs">
                        <li><a href="#upr_publish"><span><?php echo $_LANG['AD_TAB_PUBLISH']; ?></span></a></li>
                        <li><a href="#upr_restrictions"><span><?php echo $_LANG['AD_RESTRICTIONS']; ?></span></a></li>
                        <li><a href="#upr_photos"><span><?php echo $_LANG['AD_PHOTOS']; ?></span></a></li>
                        <?php if (!empty($tab_plugins)){ foreach ($tab_plugins as $tab_plugin){ ?>
                            <li><a href="<?php if ($tab_plugin['ajax_link']){ echo $tab_plugin['ajax_link']; }else{ echo '#upr_'. $tab_plugin['name']; } ?>" title="<?php echo $tab_plugin['name']; ?>"><span><?php echo $tab_plugin['title']; ?></span></a></li>
                        <?php }} ?>
                    </ul>
                        
                    <div id="upr_publish">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="published" id="published" value="1" <?php if ($mod['published'] || $do == 'add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_PUBLIC_ARTICLE']; ?>
                            </label>
                        </div>
                            
                        <div class="form-group">
                            <select id="category_id" class="form-control" style="height:200px" name="category_id" size="10">
                                <option value="1" <?php if (cmsCore::getArrVal($mod, 'category_id', 1) == 1) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_ROOT_CATEGORY'] ; ?></option>
                                <?php echo $cats_opt; ?>
                            </select>
                            <select id="showpath" name="showpath" class="form-control">
                                <option value="0" <?php if (!cmsCore::getArrVal($mod, 'showpath')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PATHWAY_NAME_ONLY']; ?></option>
                                <option value="1" <?php if (cmsCore::getArrVal($mod, 'showpath')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PATHWAY_FULL']; ?></option>
                            </select>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_ARTICLE_URL']; ?></label>
                            <input type="text" class="form-control" name="url" value="<?php echo $mod['url']; ?>" />
                            <div class="help-block"><?php echo $_LANG['AD_IF_UNKNOWN_PAGETITLE']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_ARTICLE_AUTHOR']; ?></label>
                            <select id="user_id" class="form-control" name="user_id">
                                <?php echo $users_opt; ?>
                            </select>
                        </div>
                            
                        <h4><?php echo $_LANG['AD_PUBLIC_PARAMETRS']; ?></h4>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="showlatest" value="1" <?php if ($mod['showlatest'] || $do == 'add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_VIEW_NEW_ARTICLES']; ?>
                            </label>
                            <label>
                                <input type="checkbox" name="comments" value="1" <?php if ($mod['comments'] || $do == 'add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_ENABLE_COMMENTS']; ?>
                            </label>
                            <label>
                                <input type="checkbox" name="canrate" value="1" <?php if ($mod['canrate']) { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_ENABLE_RATING']; ?>
                            </label>
                        </div>
                            
                        <h4>SEO</h4>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PAGE_TITLE']; ?></label>
                            <input type="text" class="form-control" name="pagetitle" value="<?php if (isset($mod['pagetitle'])) { echo $this->escape($mod['pagetitle']); } ?>" />
                            <div class="help-block"><?php echo $_LANG['AD_IF_UNKNOWN']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['KEYWORDS']; ?></label>
                            <textarea class="form-control" name="meta_keys" rows="4"><?php echo $this->escape($mod['meta_keys']);?></textarea>
                            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['DESCRIPTION']; ?></label>
                            <textarea class="form-control" name="meta_desc" rows="6"><?php echo $this->escape($mod['meta_desc']);?></textarea>
                            <div class="help-block"><?php echo $_LANG['AD_LESS_THAN']; ?></div>
                        </div>
                            
                        <?php if ($do == 'add') { ?>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_CREATE_LINK']; ?></label>
                            <select class="form-control" name="createmenu">
                                <option value="0" selected="selected"><?php echo $_LANG['AD_DONT_CREATE_LINK']; ?></option>
                            <?php foreach ($menu_list as $menu) { ?>
                                <option value="<?php echo $menu['id']; ?>">
                                    <?php echo $menu['title']; ?>
                                </option>
                            <?php } ?>
                            </select>
                        </div>
                        <?php } ?>
                    </div>
                        
                    <div id="upr_restrictions">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_ARTICLE_TIME']; ?></label>
                            <select class="form-control" name="is_end" onchange="if($(this).val() == 1){ $('#final_time').show(); }else {$('#final_time').hide();}">
                                <option value="0" <?php if (!cmsCore::getArrVal($mod, 'is_end')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_UNLIMITED']; ?></option>
                                <option value="1" <?php if (cmsCore::getArrVal($mod, 'is_end')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TO_FINAL_TIME']; ?></option>
                            </select>
                        </div>
                            
                        <div id="final_time" class="form-group" <?php if (!cmsCore::getArrVal($mod, 'is_end')) { echo 'style="display: none"'; } ?>>
                            <label><?php echo $_LANG['AD_FINAL_TIME']; ?></label>
                            <input type="text" id="enddate" class="form-control" name="enddate" <?php if(!cmsCore::getArrVal($mod, 'is_end')) { echo 'value="'.date('d.m.Y').'"'; } else { echo 'value="'. $mod['enddate'] .'"'; } ?> />
                            <div class="help-block"><?php echo $_LANG['AD_CALENDAR_FORMAT']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label>
                                <input name="is_public" type="checkbox" id="is_public" onclick="checkGroupList()" value="1" <?php echo $group_public; ?> />
                                <?php echo $_LANG['AD_SHARE']; ?>
                            </label>
                            <div class="help-block"><?php echo $_LANG['AD_IF_NOTED']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_GROUPS_VIEW']; ?></label>
                            <select id="showin" class="form-control" name="showfor[]" size="6" multiple="multiple" <?php echo $group_style; ?>>
                                <?php
                                if (!empty($user_groups)) {
                                    foreach ($user_groups as $group) {
                                        echo '<option value="'. $group['value'] .'"';
                                        if (isset($group['selected'])) {
                                            echo 'selected="selected"';
                                        }
                                        echo '>';
                                        echo $group['title'] .'</option>';
                                    }
                                } ?>
                            </select>
                            <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?></div>
                        </div>
                    </div>
                        
                    <div id="upr_photos">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PHOTO']; ?></label>
                                
                            <?php if ($do == 'edit' && $image_exist) { ?>
                            <div style="margin-top:3px;margin-bottom:3px;padding:10px;border:solid 1px gray;text-align:center">
                                <img src="/images/photos/small/article<?php echo $id; ?>.jpg" border="0" />
                            </div>
                            <label>
                                <input type="checkbox" name="delete_image" value="1" />
                                <?php echo $_LANG['AD_PHOTO_REMOVE']; ?>
                            </label>
                            <?php } ?>

                            <input type="file" class="form-control" name="picture" />
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_INSERTED_IMAGES']; ?></label>
                            <?php echo $ajaxUploader; ?>
                        </div>
                    </div>
                        
                    <?php foreach ($tab_plugins as $tab_plugin) { ?>
                        <div id="upr_<?php echo $tab_plugin['name']; ?>"><?php echo $tab_plugin['html']; ?></div>
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" <?php if ($do == 'add') { echo 'value="'. $_LANG['AD_CREATE_CONTENT'] .'"'; } else { echo 'value="'. $_LANG['AD_SAVE_CONTENT'] .'"'; } ?> />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();"/>
        <input type="hidden" name="do" <?php if ($do == 'add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
        <?php
            if ($do == 'edit') {
                echo '<input type="hidden" name="id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
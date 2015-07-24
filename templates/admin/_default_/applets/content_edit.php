<form id="addform" name="addform" method="post" action="index.php?view=content&do=submit" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />

    <div class="tabs-container">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#upr_article" aria-controls="upr_article" role="tab" data-toggle="tab"><?php echo $_LANG['AD_ARTICLE']; ?></a>
            </li>
            <li role="presentation">
                <a href="#upr_addit_fields" aria-controls="upr_addit_fields" role="tab" data-toggle="tab"><?php echo $_LANG['AD_ADDITIONAL_FIELDS']; ?></a>
            </li>
            <li role="presentation">
                <a href="#upr_seo" aria-controls="upr_seo" role="tab" data-toggle="tab">SEO</a>
            </li>
            <li role="presentation">
                <a href="#upr_restrictions" aria-controls="upr_restrictions" role="tab" data-toggle="tab"><?php echo $_LANG['AD_RESTRICTIONS']; ?></a>
            </li>
            <li role="presentation">
                <a href="#upr_photos" aria-controls="upr_photos" role="tab" data-toggle="tab"><?php echo $_LANG['AD_PHOTOS']; ?></a>
            </li>
            
            <?php if (!empty($tab_plugins)) { foreach ($tab_plugins as $tab_plugin) { ?>
                <li role="presentation">
                    <a href="#upr_<?php echo $tab_plugin['name']; ?>" aria-controls="upr_<?php echo $tab_plugin['name']; ?>" role="tab" data-toggle="tab" <?php if ($tab_plugin['ajax_link']) { ?> data-url="<?php echo $tab_plugin['ajax_link']; ?>" class="ajax_tab_link" <?php } ?>><?php echo $tab_plugin['title']; ?></a>
                </li>
            <?php } } ?>
        </ul>
        
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="upr_article">
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_NAME']; ?></label>

                    <div class="col-lg-10">
                        <input type="text" class="form-control inline w750" name="title" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />

                        <div class="checkbox">
                            <input type="checkbox" id="showtitle" name="showtitle" <?php if ($mod['showtitle'] || $do == 'add') { echo 'checked="checked"'; } ?> value="1" />

                            <label for="showtitle" style="font-weight: normal;"><?php echo $_LANG['AD_VIEW_TITLE']; ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_AUTHOR']; ?></label>

                    <div class="col-lg-10">
                        <select class="form-control w750" name="user_id">
                            <?php echo $users_opt; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_CATEGORY']; ?></label>

                    <div class="col-lg-10">
                        <select class="form-control w750" name="category_id">
                            <option value="1" <?php if (cmsCore::getArrVal($mod, 'category_id', 1) == 1) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_ROOT_CATEGORY'] ; ?></option>
                            <?php echo $cats_opt; ?>
                        </select>
                    </div>
                </div>

                <?php if ($cfg['multicats']) { ?>
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_ADDITIONAL_CATEGORIES']; ?></label>

                    <div class="col-lg-10">
                        <select class="chosen-select form-control w750" name="categories[]" multiple="multiple" data-placeholder="<?php echo $_LANG['AD_SELECT_CATEGORIES']; ?>">
                            <?php echo $multi_cats_opt; ?>
                        </select>
                    </div>
                </div>
                <?php } ?>
                
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_TEMPLATE']; ?></label>

                    <div class="col-lg-10">
                        <input type="text" class="form-control w750" name="tpl" value="<?php echo cmsCore::getArrVal($mod, 'tpl', 'content/read'); ?>" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_NOTICE']; ?></label>

                    <div class="col-lg-10">
                        <div class="w750">
                            <?php cmsCore::insertEditor('description', $mod['description'], '200', '100%'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_FULL_TEXT']; ?></label>

                    <div class="col-lg-10">
                        <div class="w750">
                            <?php insertPanel(); ?>
                            <?php cmsCore::insertEditor('content', $mod['content'], '400', '100%'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_TAGS']; ?></label>

                    <div class="col-lg-10">
                        <input type="text" id="tags" class="form-control w750" name="tags" value="<?php echo $this->escape($tags); ?>" />
                    </div>
                </div>
                
                <?php if ($do == 'add') { ?>
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_CREATE_LINK']; ?></label>

                    <div class="col-lg-10">
                        <select class="form-control w750" name="createmenu">
                            <option value="0" selected="selected"><?php echo $_LANG['AD_DONT_CREATE_LINK']; ?></option>
                            <?php foreach ($menu_list as $menu) { ?>
                                <option value="<?php echo $menu['id']; ?>">
                                    <?php echo $menu['title']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <?php } ?>

                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_OPTIONS']; ?></label>

                    <div class="col-lg-10">
                        <div class="row" style="margin-bottom:15px;">
                            <div class="col-lg-3">
                                <label><?php echo $_LANG['AD_PUBLIC_DATE']; ?></label>

                                <input type="text" id="pubdate" class="form-control inline" name="pubdate" style="width: 100px;" <?php if (empty($mod['pubdate'])) { echo 'value="'. date('d.m.Y') .'"'; } else { echo 'value="'. $mod['pubdate'] .'"'; } ?> />
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="showdate" name="showdate" value="1" <?php if ($mod['showdate'] || $do == 'add') { echo 'checked="checked"'; } ?> />

                                    <label for="showdate" style="font-weight: normal;"><?php echo $_LANG['AD_VIEW_DATE_AND_AUTHOR']; ?></label>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <label><?php echo $_LANG['AD_FINAL_TIME']; ?></label>

                                <input type="text" id="enddate" class="form-control inline" name="enddate" value="<?php if (cmsCore::getArrVal($mod, 'is_end')) { echo $mod['enddate']; } ?>" style="width: 100px;" <?php if (!cmsCore::getArrVal($mod, 'is_end')) { ?>disabled="disabled" <?php } ?> />

                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="is_end" name="is_end" value="1" <?php if (cmsCore::getArrVal($mod, 'is_end')) { echo 'checked="checked"'; } ?> onchange="if ($('#is_end').prop('checked')) { $('#enddate').prop('disabled', false); } else { $('#enddate').prop('disabled', true); $('#enddate').val(''); }" />

                                    <label for="is_end" style="font-weight: normal;"><?php echo $_LANG['AD_ARTICLE_TIME']; ?></label>
                                </div>
                            </div>
                            
                            <div class="col-lg-6"></div>
                            <input type="hidden" name="olddate" value="<?php echo cmsCore::getArrVal($mod, 'pubdate', ''); ?>" />
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="published" name="published" value="1" <?php if ($mod['published'] || $do == 'add') { echo 'checked="checked"'; } ?> />

                                    <label for="published">
                                        <?php echo $_LANG['AD_PUBLIC_ARTICLE']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="showlatest" name="showlatest" value="1" <?php if ($mod['showlatest'] || $do == 'add') { echo 'checked="checked"'; } ?> />

                                    <label for="showlatest">
                                        <?php echo $_LANG['AD_VIEW_NEW_ARTICLES']; ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="comments" name="comments" value="1" <?php if ($mod['comments'] || $do == 'add') { echo 'checked="checked"'; } ?> />

                                    <label for="comments">
                                        <?php echo $_LANG['AD_ENABLE_COMMENTS']; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="col-lg-6"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="canrate" name="canrate" value="1" <?php if ($mod['canrate']) { echo 'checked="checked"'; } ?> />

                                    <label for="canrate">
                                        <?php echo $_LANG['AD_ENABLE_RATING']; ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <?php if ($cfg['af_on'] && $do == 'add') { ?>
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" name="noforum" id="noforum" value="1" />

                                    <label for="noforum">
                                        <?php echo $_LANG['AD_NO_CREATE_THEME']; ?>
                                    </label>
                                </div>
                                <?php } ?>
                            </div>
                            
                            <div class="col-lg-6"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="upr_addit_fields">
                <div id="no_fields_info" class="form-group">
                    <div class="alert alert-info" role="alert" style="width: 750px;">
                        <?php echo $_LANG['AD_ADDITIONAL_FIELDS_INFO']; ?>
                    </div>
                </div>
                
                <div id="article_fields_html">
                    
                </div>
            </div>
            
            <div role="tabpanel" class="tab-pane fade" id="upr_seo">
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_PAGE_TITLE']; ?></label>

                    <div class="col-lg-10">
                        <input type="text" class="form-control w750" name="pagetitle" value="<?php if (isset($mod['pagetitle'])) { echo $this->escape($mod['pagetitle']); } ?>" />
                        <div class="help-block"><?php echo $_LANG['AD_IF_UNKNOWN_PAGETITLE']; ?></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="radio radio-primary">
                                    <div>
                                        <input type="radio" id="autokeys1" name="autokeys" <?php if ($do == 'add' && $cfg['autokeys']) { ?>checked="checked"<?php } ?> value="1" />

                                        <label for="autokeys1">
                                            <?php echo $_LANG['AD_AUTO_GEN_KEY']; ?>
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" id="autokeys2" name="autokeys" value="2" />

                                        <label for="autokeys2">
                                            <?php echo $_LANG['AD_TAGS_AS_KEY']; ?>
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" id="autokeys3" name="autokeys" value="3" <?php if ($do == 'edit' || !$cfg['autokeys']) { ?>checked="checked"<?php } ?> />

                                        <label for="autokeys3">
                                            <?php echo $_LANG['AD_MANUAL_KEY']; ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['KEYWORDS']; ?></label>

                    <div class="col-lg-10">
                        <textarea class="form-control w750" name="meta_keys" rows="4" <?php if ($do == 'add') { ?>disabled="disabled"<?php } ?>><?php echo $this->escape($mod['meta_keys']);?></textarea>
                        <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['DESCRIPTION']; ?></label>

                    <div class="col-lg-10">
                        <textarea class="form-control w750" name="meta_desc" rows="6" <?php if ($do == 'add') { ?>disabled="disabled"<?php } ?>><?php echo $this->escape($mod['meta_desc']);?></textarea>
                        <div class="help-block"><?php echo $_LANG['AD_LESS_THAN']; ?></div>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_PATHWAY']; ?></label>

                    <div class="col-lg-10">
                        <div class="radio radio-primary">
                            <input type="radio" id="showpath0" name="showpath" value="0" <?php if (!cmsCore::getArrVal($mod, 'showpath')) { echo 'checked="checked"'; } ?> />
                            
                            <label for="showpath0"><?php echo $_LANG['AD_PATHWAY_NAME_ONLY']; ?></label>
                        </div>
                        
                        <div class="radio radio-primary">
                            <input type="radio" id="showpath1" name="showpath" value="1" <?php if (cmsCore::getArrVal($mod, 'showpath')) { echo 'checked="checked"'; } ?> />
                            
                            <label for="showpath1"><?php echo $_LANG['AD_PATHWAY_FULL']; ?></label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_ARTICLE_URL']; ?></label>

                    <div class="col-lg-10">
                        <input type="text" class="form-control w750" name="url" value="<?php echo $mod['url']; ?>" />
                        <div class="help-block"><?php echo $_LANG['AD_IF_UNKNOWN_PAGETITLE']; ?></div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="upr_restrictions">
                <div class="form-group row">
                    <div class="col-lg-2"></div>
                    
                    <div class="col-lg-10">
                        <div class="checkbox checkbox-primary">
                            <input name="is_public" type="checkbox" id="is_public" onclick="checkGroupList()" value="1" <?php echo $group_public; ?> />
                            <label for="is_public">
                                <?php echo $_LANG['AD_SHARE']; ?>
                            </label>
                        </div>
                        <div class="help-block w750"><?php echo $_LANG['AD_IF_NOTED']; ?></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_GROUPS_VIEW']; ?></label>
                    
                    <div class="col-lg-10">
                        <select id="showin" class="form-control w750" name="showfor[]" size="6" multiple="multiple" <?php echo $group_style; ?>>
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
                        <div class="help-block w750"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?></div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="upr_photos">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PHOTO']; ?></label>

                    <?php if (!empty($mod['image_small'])) { ?>
                    <div class="article_img w750">
                        <img src="<?php echo $mod['image_small']; ?>" border="0" />
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" id="delete_image" name="delete_image" value="1" />
                        <label for="delete_image">
                            <?php echo $_LANG['AD_PHOTO_REMOVE']; ?>
                        </label>
                    </div>
                    <?php } ?>

                    <input type="file" class="form-control w750" name="picture" />
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_INSERTED_IMAGES']; ?></label>
                    <?php echo $ajaxUploader; ?>
                </div>
            </div>

            <?php foreach ($tab_plugins as $tab_plugin) { ?>
                <div role="tabpanel" class="tab-pane fade" id="upr_<?php echo $tab_plugin['name']; ?>"><?php echo $tab_plugin['html']; ?></div>
            <?php } ?>
        </div>
    </div>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" <?php if ($do == 'add') { echo 'value="'. $_LANG['AD_CREATE_CONTENT'] .'"'; } else { echo 'value="'. $_LANG['AD_SAVE_CONTENT'] .'"'; } ?> />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();"/>

        <?php
            if ($do == 'edit') {
                echo '<input type="hidden" name="id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>

<script type="text/javascript">
    var fields_html = [];
    fields_html[1] = false;
    
    function insert_fields_html(cat_id) {
        if (fields_html[cat_id]) {
            $('#no_fields_info').hide();
            $('#article_fields_html').html(fields_html[cat_id])
        } else {
            $('#no_fields_info').show();
            $('#article_fields_html').html('')
        }
    }
    
    function get_article_fields() {
        var cat_id = $('select[name=category_id]').val(), isset = false;
        
        for (i in fields_html) {
            if (i === cat_id) {
                isset = true;
            }
        }

        if (isset === false) {
            $.post(
                '/admin/index.php?view=content&do=get_cat_fields',
                {
                    article_id: <?php echo !empty($mod['id']) ? $mod['id'] : 0; ?>,
                    cat_id: cat_id,
                },
                function (msg) {
                    fields_html[cat_id] = msg;
                    insert_fields_html(cat_id);
                }
            );
        } else {
            insert_fields_html(cat_id);
        }
    }
    
    $(function() {
        $('input[name=tags]').tagsInput({
            'autocomplete_url': '/core/ajax/tagsearch.php',
            'defaultText': '',
            'delimiter': ',',
            'width': '750px',
            'height': 'auto'
        });
    
        $('input[name=autokeys]').click(function(e) {
            if ($(this).val() === 3) {
                $('textarea[name=meta_keys]').prop('disabled', false);
                $('textarea[name=meta_desc]').prop('disabled', false);
            } else {
                $('textarea[name=meta_keys]').prop('disabled', true);
                $('textarea[name=meta_desc]').prop('disabled', true);
            }
        });

        $('select[name=category_id]').change(function() {
            get_article_fields();
        });
        
        get_article_fields();
    });
</script>
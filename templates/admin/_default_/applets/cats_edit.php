<form id="addform" name="addform" method="post" action="index.php">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <input type="hidden" name="view" value="cats" />

    <div class="tabs-container">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#upr_cat" aria-controls="upr_cat" role="tab" data-toggle="tab"><?php echo $_LANG['AD_CATEGORY']; ?></a>
            </li>
            <li role="presentation">
                <a href="#upr_addit_fields" aria-controls="upr_addit_fields" role="tab" data-toggle="tab"><?php echo $_LANG['AD_ADDITIONAL_FIELDS']; ?></a>
            </li>
            <li role="presentation">
                <a href="#upr_seo" aria-controls="upr_seo" role="tab" data-toggle="tab">SEO</a>
            </li>
            <li role="presentation">
                <a href="#upr_foto" aria-controls="upr_foto" role="tab" data-toggle="tab"><?php echo $_LANG['AD_FOTO']; ?></a>
            </li>
            <li role="presentation">
                <a href="#upr_access" aria-controls="upr_access" role="tab" data-toggle="tab"><?php echo $_LANG['AD_TAB_ACCESS']; ?></a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="upr_cat">
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_NAME']; ?></label>

                    <div class="col-lg-10">
                        <input type="text" class="form-control inline w750" name="title" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_TEMPLATE']; ?></label>

                    <div class="col-lg-10">
                        <input type="text" class="form-control w750" name="tpl" value="<?php echo cmsCore::getArrVal($mod, 'tpl', ''); ?>" />
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_PARENT_PARTITION'];?></label>
                    
                    <div class="col-lg-10">
                        <div class="alert alert-danger" role="alert" style="display:none;">
                            <?php echo $_LANG['AD_ANOTHER_PARENT']; ?>
                        </div>

                        <select name="parent_id" id="parent_id" class="form-control w750" onchange="if($('option:selected',this).data('nsleft')>='<?php echo cmsCore::getArrVal($mod, 'NSLeft', 0); ?>' && $('option:selected',this).data('nsright')<='<?php echo cmsCore::getArrVal($mod, 'NSRight', 0); ?>'){ $('.parent_notice').show();$('#add_mod').prop('disabled', true); } else { $('.parent_notice').hide();$('#add_mod').prop('disabled', false); }">
                            <option value="<?php echo $rootid; ?>" <?php if (!isset($mod['parent_id']) || cmsCore::getArrVal($mod, 'parent_id', '') == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_SECTION'];?></option>
                            <?php echo $category_opt; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_EDITORS_SECTION'];?></label>
                    
                    <div class="col-lg-10">
                        <select class="form-control w750" name="modgrp_id">
                            <option value="0" <?php if (cmsCore::getArrVal($mod, 'modgrp_id', 0) == 0) { echo 'selected'; }?>><?php echo $_LANG['AD_ONLY_ADMINS'];?></option>
                            <?php echo $user_group_opt; ?>
                        </select>
                        <div class="help-block w750"><?php echo $_LANG['AD_USERS_CAN_ADMIN'];?></div>
                    </div>
                </div>
                
                <?php if ($is_billing) { ?>
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_COST_ARTICLES_ADD'];?></label>
                    
                    <div class="col-lg-10">
                        <input type="text" class="form-control inline" style="width:50px" name="cost" value="<?php echo cmsCore::getArrVal($mod, 'cost', ''); ?>" /><?php echo $_LANG['BILLING_POINT10'];?>
                        <div class="help-block w750"><?php echo $_LANG['AD_COST_ARTICLES_BY_DEFAULT'];?></div>
                    </div>
                </div>
                <?php } ?>
                
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_SECTION_DESCRIPT'];?></label>
                    
                    <div class="col-lg-10">
                        <div class="w750">
                            <?php cmsCore::insertEditor('description', cmsCore::getArrVal($mod, 'description', ''), '250', '100%'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_SORT_ARTICLES'];?></label>
                    
                    <div class="col-lg-10">
                        <select id="orderby" class="form-control w750" name="orderby">
                            <?php $mod['orderby'] = cmsCore::getArrVal($mod, 'orderby', ''); ?>
                            <option value="pubdate" <?php if ($mod['orderby'] == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR'];?></option>
                            <option value="title" <?php if ($mod['orderby'] == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_TITLE'];?></option>
                            <option value="ordering" <?php if ($mod['orderby'] == 'ordering') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ORDER'];?></option>
                            <option value="hits" <?php if ($mod['orderby'] == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS'];?></option>
                        </select>
                        
                        <select id="orderto" class="form-control w750" name="orderto">
                            <?php $mod['orderto'] = cmsCore::getArrVal($mod, 'orderto', ''); ?>
                            <option value="ASC" <?php if ($mod['orderto'] == 'ASC') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT'];?></option>
                            <option value="DESC" <?php if ($mod['orderto'] == 'DESC') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT'];?></option>
                        </select>
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
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="published" name="published" value="1" <?php if ($mod['published'] || $do == 'add') { echo 'checked="checked"'; } ?> />

                                    <label for="published">
                                        <?php echo $_LANG['AD_PUBLIC_SECTION']; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="showdesc" name="showdesc" value="1" <?php if ($mod['showdesc'] || $do == 'add') { echo 'checked="checked"'; } ?> />

                                    <label for="showdesc">
                                        <?php echo $_LANG['AD_PREVIEW']; ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="showdate" name="showdate" value="1" <?php if ($mod['showdate'] || $do == 'add') { echo 'checked="checked"'; } ?> />

                                    <label for="showdate">
                                        <?php echo $_LANG['AD_CALENDAR_VIEW']; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="col-lg-6"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="showcomm" name="showcomm" value="1" <?php if ($mod['showcomm'] || $do == 'add') { echo 'checked="checked"'; } ?> />

                                    <label for="showcomm">
                                        <?php echo $_LANG['AD_HOW_MANY_COMENTS']; ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="showtags" name="showtags" value="1" <?php if ($mod['showtags'] || $do == 'add') { echo 'checked="checked"'; } ?> />

                                    <label for="showtags">
                                        <?php echo $_LANG['AD_HOW_MANY_TAGS']; ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-lg-6"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="showrss" name="showrss" value="1" <?php if ($mod['showrss'] || $do == 'add') { echo 'checked="checked"'; } ?> />

                                    <label for="showrss">
                                        <?php echo $_LANG['AD_RSS_VIEW']; ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <?php echo $_LANG['AD_HOW_MANY_COLUMNS']; ?>
                                <input class="form-control uispin" name="maxcols" type="text" style="width:50px" value="<?php echo cmsCore::getArrVal($mod, 'maxcols', 1); ?>" />
                            </div>
                            
                            <div class="col-lg-6"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="ispublic" name="is_public" value="1" <?php if ($mod['is_public']) { echo 'checked="checked"'; } ?> />

                                    <label for="ispublic">
                                        <?php echo $_LANG['AD_USERS_ARTICLES']; ?>
                                    </label>
                                </div>
                                <div class="help-block w750"><?php echo $_LANG['AD_IF_SWITCH'];?></div>
                            </div>
                            
                            <div class="col-lg-3"></div>
                            
                            <div class="col-lg-6"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div role="tabpanel" class="tab-pane fade" id="upr_addit_fields">
                <div class="form-group" id="add_field_btn">
                    <input type="button" class="btn btn-default" value="<?php echo $_LANG['AD_ADD_NEW_FIELD']; ?>" onclick="$('#add_field').show(); $('#add_field_btn').hide();" />
                </div>
                
                <div id="add_field" style="display: none;">
                    <input type="hidden" name="field_key" value="0" />
                    
                    <div class="form-group row">
                        <label class="col-lg-2"><?php echo $_LANG['AD_FIELD_TITLE']; ?></label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control w750" name="field_title" value="" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2"><?php echo $_LANG['AD_FIELD_NAME']; ?></label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control w750" name="field_name" value="" />
                            <div class="help-block w750"><?php echo $_LANG['AD_LATIN_LETTERS']; ?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2"><?php echo $_LANG['AD_FIELD_TYPE']; ?></label>
                        <div class="col-lg-10">
                            <select name="field_type" class="form-control w750">
                                <option value="text"><?php echo $_LANG['AD_TYPE_TEXT']; ?></option>
                                <option value="html"><?php echo $_LANG['AD_TYPE_HTML']; ?></option>
                                <option value="select"><?php echo $_LANG['AD_TYPE_SELECT']; ?></option>
                                <?php foreach ($fields as $field) { ?>
                                    <option value="<?php echo $field['name']; ?>"><?php echo $field['title']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row field_item field_item_text">
                        <label class="col-lg-2"><?php echo $_LANG['AD_DEFAULT_VALUE']; ?></label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control w750" name="field_text_default" value="" data-name="default"/>
                        </div>
                    </div>
                    
                    <div class="form-group row field_item field_item_html" style="display:none;">
                        <label class="col-lg-2"><?php echo $_LANG['AD_DEFAULT_VALUE']; ?></label>
                        <div class="col-lg-10">
                            <textarea class="form-control w750" name="field_html_default" style="height:100px;" data-name="default"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row field_item field_item_select" style="display:none;">
                        <label class="col-lg-2"><?php echo $_LANG['AD_VALUES_LIST']; ?></label>
                        <div class="col-lg-10">
                            <textarea class="form-control w750" name="field_select_default" style="height:100px;" data-name="default"></textarea>
                            <div class="help-block w750"><?php echo $_LANG['AD_VALUE_ON_NEW_LINE']; ?></div>
                        </div>
                    </div>

                    <?php foreach ($fields as $field) { if (!empty($field['items'])) { ?>
                        <?php foreach ($field['items'] as $item) { ?>
                        <div class="form-group row field_item field_item_<?php echo $field['name']; ?>" style="display:none;">
                            <label class="col-lg-2"><?php echo $item['title']; ?></label>
                            <div class="col-lg-10">
                                <?php
                                    switch ($item['type']) {
                                        case 'text':
                                            echo '<input type="text" class="form-control w750" name="field_'. $field['name'] .'_'. $item['name'] .'" value="'. $item['value'] .'" data-name="'. $item['name'] .'" />';
                                            break;
                                        case 'textarea':
                                            echo '<textarea class="form-control w750" name="field_'. $field['name'] .'_'. $item['name'] .'" data-name="'. $item['name'] .'">'. $item['value'] .'</textarea>';
                                            break;
                                        case 'html':
                                            echo $item['html'];
                                            break;
                                        case 'checkbox':
                                            echo '<input type="checkbox" name="field_'. $field['name'] .'_'. $item['name'] .'" value="'. $item['value'] .'" data-name="'. $item['name'] .'" />';
                                            break;
                                        case 'radio':
                                            echo '<div class="radio radio-primary">';
                                            foreach ($item['items'] as $k => $it) {
                                                echo '<div>';
                                                echo '<input type="radio" id="field_'. $field['name'] .'_'. $item['name'] .'_'. $k .'" name="field_'. $field['name'] .'_'. $item['name'] .'" value="'. $it['value'] .'" data-name="'. $item['name'] .'" />';
                                                echo '<label for="field_'. $field['name'] .'_'. $item['name'] .'_'. $k .'">'. $it['title'] .'</label>';
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                            break;
                                    }
                                ?>
                                <?php echo $item['html']; ?>
                                <?php if (!empty($item['description'])) { ?>
                                    <div class="help-block w750"><?php echo $item['description']; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    <?php } } ?>

                    <div class="form-group row">
                        <label class="col-lg-2"><?php echo $_LANG['AD_OPTIONS']; ?></label>
                        <div class="col-lg-10">
                            <div class="checkbox checkbox-primary">
                                <div>
                                    <input type="checkbox" id="field_required" name="field_required" value="1" />
                                    <label for="field_required"><?php echo $_LANG['AD_REQUIRE']; ?></label>
                                </div>
                                <div>
                                    <input type="checkbox" id="field_del_from_list" name="field_del_from_list" value="1" />
                                    <label for="field_del_from_list"><?php echo $_LANG['AD_DEL_FROM_LIST']; ?></label>
                                </div>
                                <div class="field_option field_option_text">
                                    <input type="checkbox" id="field_text_link" name="field_text_link" data-name="link" value="1" />
                                    <label for="field_text_link"><?php echo $_LANG['AD_FIELD_AS_LINK']; ?></label>
                                </div>
                                <div class="field_option field_option_select" style="display:none;">
                                    <input type="checkbox" id="field_select_link" name="field_select_link" data-name="link" value="1" />
                                    <label for="field_select_link"><?php echo $_LANG['AD_FIELD_AS_LINK']; ?></label>
                                </div>
                                <?php foreach ($fields as $field) { if (!empty($field['options'])) { ?>
                                    <?php foreach ($field['options'] as $option) { ?>
                                    <div class="field_option field_option_<?php echo $field['name']; ?>" style="display:none;">
                                        <input type="checkbox" id="field_<?php echo $field['name'] .'_'. $option['name']; ?>" name="field_<?php echo $field['name'] .'_'. $option['name']; ?>" data-name="<?php echo $option['name']; ?>" value="1" />
                                        <label for="field_<?php echo $field['name'] .'_'. $option['name']; ?>"><?php echo $option['title']; ?></label>
                                    </div>
                                    <?php } ?>
                                <?php } } ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <input type="button" class="btn btn-success" value="<?php echo $_LANG['SAVE']; ?>" onclick="add_new_field();" />
                        <input type="button" class="btn btn-default" value="<?php echo $_LANG['CANCEL']; ?>" onclick="$('#add_field').hide();reset_fields();" />
                    </div>
                </div>
                
                <hr/>
                
                <div class="form-group row">
                    <label class="col-lg-2"><?php echo $_LANG['AD_FIELDS_LIST']; ?></label>
                    
                    <div class="col-lg-10">
                        <div id="fields_list" class="uisort list-group" style="padding-right: 20px;">
                            <?php $k = 0; ?>
                            <?php foreach ($mod['fields'] as $field) { ?>
                            <?php $k++; ?>
                            <div id="field_<?php echo $k; ?>" class="row list-group-item">
                                <div class="col-lg-4">
                                    <?php echo $field['title']; ?> (<?php echo $field['name']; ?>)
                                </div>
                                <div class="col-lg-3">
                                    <?php echo $_LANG['AD_FIELD_TYPE']; ?>: <span><?php echo $field['type']; ?></span>
                                </div>
                                <div class="col-lg-3">
                                    <?php echo $_LANG['AD_REQUIRED']; ?>: <span><?php echo $field['required'] ? $_LANG['YES'] : $_LANG['NO']; ?></span>
                                </div>
                                <div class="col-lg-2 text-right">
                                    <a href="#" onclick="field_edit(<?php echo $k; ?>);return false;" class="btn btn-default"><i class="fa fa-edit"></i></a>
                                    <a href="#" onclick="field_delete(<?php echo $k; ?>);return false;" class="btn btn-default"><i class="fa fa-trash-o"></i></a>
                                </div>
                                <input type="hidden" name="fields[]" value="<?php echo $field['json']; ?>" /> </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div role="tabpanel" class="tab-pane fade" id="upr_seo">
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_PAGE_TITLE']; ?></label>

                    <div class="col-lg-10">
                        <input type="text" id="pagetitle" class="form-control w750" name="pagetitle" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'pagetitle', '')); ?>" />
                        <div class="help-block w750"><?php echo $_LANG['AD_IF_UNKNOWN_PAGETITLE']; ?></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['KEYWORDS']; ?></label>
                    
                    <div class="col-lg-10">
                        <textarea class="form-control w750" name="meta_keys" rows="4"><?php echo $this->escape(cmsCore::getArrVal($mod, 'meta_keys', ''));?></textarea>
                        <div class="help-block w750"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['DESCRIPTION']; ?></label>
                    
                    <div class="col-lg-10">
                        <textarea class="form-control w750" name="meta_desc" rows="6"><?php echo $this->escape(cmsCore::getArrVal($mod, 'meta_desc', ''));?></textarea>
                        <div class="help-block w750"><?php echo $_LANG['AD_LESS_THAN']; ?></div>
                    </div>
                </div>

                <div class="form-group row url_cat" style=" <?php if ($do == 'edit'){  ?>display:none;<?php } ?>">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_SECTION_URL'];?></label>
                    
                    <div class="col-lg-10">
                        <input type="text" class="form-control w750" name="url" value="<?php echo cmsCore::getArrVal($mod, 'url', ''); ?>" />
                        <div class="help-block w750"><?php echo $_LANG['AD_FROM_TITLE'];?></div>
                    </div>
                </div>

                <?php if ($do == 'edit') { ?>
                <div class="form-group row">
                    <label class="col-lg-2 text-right">
                        <input type="checkbox" name="update_seolink" value="1" onclick="$('.url_cat').slideToggle('fast');" />
                        <?php echo $_LANG['AD_NEW_LINK'];?>
                    </label>
                    <div class="col-lg-10">
                        <div class="help-block url_cat w750" style="display:none;"><b style="color:#F00;"><?php echo $_LANG['ATTENTION'];?>:</b> <?php echo $_LANG['AD_NO_LINKS'];?></div>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="upr_foto">
                <div class="form-group row">
                    <label class="col-lg-2 text-right"><?php echo $_LANG['AD_PHOTOALBUM_CONNECT'];?></label>
                    
                    <div class="col-lg-10">
                        <select id="album_id" class="form-control w750" name="album_id" onchange="choosePhotoAlbum()">
                            <option value="0" <?php if (empty($mod['photoalbum']['id'])) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_DONT_CONNECT'];?></option>
                            <?php echo $photo_albums_opt; ?>
                        </select>
                        <div class="help-block w750"><?php echo $_LANG['AD_PHOTO_BY_ARTICLES'];?></div>
                    </div>
                </div>

                <div id="con_photoalbum" <?php if (empty($mod['photoalbum']['id'])) { echo 'style="display:none;"'; }?>>
                    <div class="form-group row">
                        <label class="col-lg-2 text-right"><?php echo $_LANG['AD_TITLE'];?></label>
                        
                        <div class="col-lg-10">
                            <input type="text" id="album_header" class="form-control w750" name="album_header" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'header', 0); ?>" />
                            <div class="help-block w750"><?php echo $_LANG['AD_OVER_PHOTOS'];?></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 text-right"><?php echo $_LANG['AD_PHOTOS_SORT'];?></label>
                        
                        <div class="col-lg-10">
                            <select class="form-control w750" name="album_orderby">
                                <?php $mod['photoalbum']['orderby'] = cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'orderby', 0); ?>
                                <option value="title" <?php if ($mod['photoalbum']['orderby'] == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET'];?></option>
                                <option value="pubdate" <?php if ($mod['photoalbum']['orderby'] == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR'];?></option>
                                <option value="rating" <?php if ($mod['photoalbum']['orderby'] == 'rating') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_RATING'];?></option>
                                <option value="hits" <?php if ($mod['photoalbum']['orderby'] == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS'];?></option>
                            </select>
                            
                            <select class="form-control w750" name="album_orderto">
                                <?php $mod['photoalbum']['orderto'] = cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'orderto', 0); ?>
                                <option value="desc" <?php if ($mod['photoalbum']['orderto'] == 'desc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT'];?></option>
                                <option value="asc" <?php if ($mod['photoalbum']['orderto'] == 'asc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT'];?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 text-right"><?php echo $_LANG['AD_HOW_MANY_COLUMNS'];?></label>
                        
                        <div class="col-lg-10">
                            <input type="text" class="form-control uispin" name="album_maxcols" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'maxcols', 2); ?>" style="width:50px" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-2 text-right"><?php echo $_LANG['AD_HOW_MANY_PHOTO'];?></label>
                        
                        <div class="col-lg-10">
                            <input type="text" class="form-control uispin" name="album_max" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'max', 8); ?>" style="width:50px" />
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="upr_access">
                <div class="form-group row">
                    <div class="col-lg-2"></div>
                    
                    <div class="col-lg-10">
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="is_public" name="is_access" onclick="checkGroupList()" value="1" <?php echo $group_public; ?> />
                            <label for="is_public">
                                <?php echo $_LANG['AD_SHARE'];?>
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
                        <div class="help-block w750"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL'];?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" <?php if ($do == 'add') { echo 'value="'.$_LANG['AD_SAVE_SECTION'].'"'; } else { echo 'value="'.$_LANG['AD_SAVE_SECTION'].'"'; } ?> />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />
        <input type="hidden" name="do" <?php if ($do == 'add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
        <?php
            if ($do == 'edit') {
                echo '<input type="hidden" name="id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
<script type="text/javascript">
    var field_key = <?php echo $k + 1; ?>;
    var field_html = '<div id="field_%id%" class="row list-group-item"> <div class="col-lg-4">%title% (%name%)</div> <div class="col-lg-3"><?php echo $_LANG['AD_FIELD_TYPE']; ?>: <span>%type%</span></div> <div class="col-lg-3"><?php echo $_LANG['AD_REQUIRED']; ?>: <span>%req%</span></div> <div class="col-lg-2 text-right"> <a href="#" onclick="field_edit(%id%);return false;" class="btn btn-default"> <i class="fa fa-edit"></i> </a> <a href="#" onclick="field_delete(%id%);return false;" class="btn btn-default"> <i class="fa fa-trash-o"></i> </a> </div> <input type="hidden" name="fields[]" value="%json%" /> </div>';
    var fields = [];
    
    function choosePhotoAlbum() {
        id = $('select[name=album_id]').val();
        if(id != 0){
            $('#con_photoalbum').fadeIn();
        }else{
            $('#con_photoalbum').hide();
        }
    }
    
    function field_edit(id) {
        var field = JSON.parse(decodeURIComponent($('#field_'+ id +' input').val()));
        
        $('input[name=field_key]').val(id);
        
        $('.field_option').hide();
        $('.field_item').hide();

        $('.field_item_'+ field.type).show();
        $('.field_option_'+ field.type).show();
        
        $('input[name=field_title]').val(field.title);
        $('input[name=field_name]').val(field.name);
        
        $('select[name=field_type] option[value='+ field.type +']').prop('selected', true);
        $('select[name=field_type]').trigger('change');
        
        $('input[name=field_required]').prop('checked', field.required);
        $('input[name=field_del_from_list]').prop('checked', field.del_from_list);
        
        for (i in field.options) {
            $('input[name=field_'+ field.type +'_'+ field.options[i].name +']').prop('checked', field.options[i].value);
        }
        
        for (i in field.items) {
            switch (field.items[i].type) {
                case 'text':
                    $('input[name=field_'+ field.type +'_'+ field.items[i].name +']').val(field.items[i].value);
                    break
                case 'select':
                    $('select[name=field_'+ field.type +'_'+ field.items[i].name +'] option[value='+ field.items[i].value +']').prop('selected', true);
                    $('select[name=field_'+ field.type +'_'+ field.items[i].name +']').trigger('change');
                    break
                case 'textarea':
                    $('textarea[name=field_'+ field.type +'_'+ field.items[i].name +']').val(field.items[i].value);
                    break
                case 'checkbox':
                    $('input[name=field_'+ field.type +'_'+ field.items[i].name +']').prop('checked', field.items[i].value);
                    break
                case 'radio':
                    $('input[name=field_'+ field.type +'_'+ field.items[i].name +']').each(function(){
                        if ($(this).val() == field.items[i].value) {
                            $(this).prop('checked', true);
                        }
                    });
                    break
            }
        }
        
        $('#add_field').show();
        $('#add_field_btn').hide();
    }
    
    function field_delete(id) {
        $('#field_'+ id).remove();
    }
    
    function add_new_field() {
        var html = field_html, error = '';
        var id = $('input[name=field_key]').val();
        if (id == 0) {
            field_key++;
            id = field_key;
        }
        
        var field = {
            title: $('input[name=field_title]').val(),
            name: $('input[name=field_name]').val(),
            type: $('select[name=field_type]').val(),
            required: $('input[name=field_required]').prop('checked'),
            del_from_list: $('input[name=field_del_from_list]').prop('checked'),
            options: {},
            items: {}
        };
        
        if (!field.title) {
            error += '<div><?php echo $_LANG['AD_EMPTY_AF_TITLE']; ?></div>';
        }
        
        if (!field.name) {
            error += '<div><?php echo $_LANG['AD_EMPTY_AF_NAME']; ?></div>';
        }
        
        if (id == 0) {
            for (i in fields) {
                if (fields[i].name == field.name) {
                    error += '<div><?php echo $_LANG['AD_EMPTY_AF_DOUBLE']; ?></div>';
                }
            }
        }
        
        if (error) {
            adminAlert(error);
        } else {
            $('.field_option_'+ field.type +' input').each(function(){
                field.options[$(this).data('name')] = {name: $(this).data('name'), value: $(this).prop('checked')};
                //field.options.push({name: $(this).data('name'), value: $(this).prop('checked')})
            });

            $('.field_item_'+ field.type +' input[type=text]').each(function(){
                field.items[$(this).data('name')] = {type: 'text', name: $(this).data('name'), value: $(this).val()};
                //field.items.push({type: 'text', name: $(this).data('name'), value: $(this).val()})
            });

            $('.field_item_'+ field.type +' select').each(function(){
                field.items[$(this).data('name')] = {type: 'select', name: $(this).data('name'), value: $(this).val()};
                //field.items.push({type: 'select', name: $(this).data('name'), value: $(this).val()})
            });

            $('.field_item_'+ field.type +' textarea').each(function(){
                field.items[$(this).data('name')] = {type: 'textarea', name: $(this).data('name'), value: $(this).val()};
                //field.items.push({type: 'textarea', name: $(this).data('name'), value: $(this).val()})
            });

            $('.field_item_'+ field.type +' input[type=checkbox]').each(function(){
                if ($(this).prop('checked')) {
                    field.items[$(this).data('name')] = {type: 'checkbox', name: $(this).data('name'), value: $(this).val()};
                    //field.items.push({type: 'checkbox', name: $(this).data('name'), value: $(this).val()});
                }
            });

            $('.field_item_'+ field.type +' input[type=radio]').each(function(){
                if ($(this).prop('checked')) {
                    field.items[$(this).data('name')] = {type: 'radio', name: $(this).data('name'), value: $(this).val()};
                    //field.items.push({type: 'radio', name: $(this).data('name'), value: $(this).val()});
                }
            });

            fields[id] = field;

            html = html.replace(new RegExp('%id%', 'g'), id);
            html = html.replace(new RegExp('%title%', 'g'), field.title);
            html = html.replace(new RegExp('%name%', 'g'), field.name);
            html = html.replace(new RegExp('%type%', 'g'), field.type);
            html = html.replace(new RegExp('%req%', 'g'), field.required ? '<?php echo $_LANG['YES'] ?>' : '<?php echo $_LANG['NO'] ?>');
            html = html.replace(new RegExp('%json%', 'g'), encodeURIComponent(JSON.stringify(field)));

            if ($('#field_'+ id).length) {
                $('#field_'+ id).replaceWith(html);
            } else {
                $('#fields_list').append(html);
            }

            reset_fields();
        }
    }
    
    function reset_fields() {
        $('input[name=field_key]').val('0');
        
        $('#add_field').hide();
        $('#add_field_btn').show();
        
        $('.field_option').hide();
        $('.field_item').hide();
        
        $('.field_option input').each(function(){
            $(this).prop('checked', false);
        });
        
        $('input[name=field_title]').val('');
        $('input[name=field_name]').val('');
        $('select[name=field_type] option:first').attr('selected', 'selected');
        
        $('.field_item input').val('');
        $('.field_item textarea').val('');
        $('.field_item select option:first').attr('selected', 'selected');
        
        $('.field_item_text').show();
        $('.field_option_text').show();
    }
    
    $(function(){
        $('select[name=field_type]').change(function(){
            var v = $(this).val();
            
            $('.field_option').hide();
            $('.field_item').hide();
            
            $('.field_item_'+ v).show();
            $('.field_option_'+ v).show();
        });
    });
</script>
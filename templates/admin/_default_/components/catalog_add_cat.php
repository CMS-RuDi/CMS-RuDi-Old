<?php if ($opt == 'add_cat') { ?>
    <h3><?php echo $_LANG['AD_NEW_CAT']; ?></h3>
<?php } else { ?>
    <h3><?php echo $_LANG['AD_CAT_BOARD'] .': '. $mod['title']; ?></h3>
<?php } ?>

<form id="addform" name="addform" method="post" action="index.php?view=components&do=config&link=catalog" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <table class="table">
        <tr>
            <!-- главная ячейка -->
            <td valign="top">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_CAT_NAME'];?></label>
                    <input type="text" class="form-control" name="title" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ITEMS_FEATURES'];?></label>
                    <div class="help-block"><?php echo $_LANG['AD_FIELDS_NAME'];?></div>
                    <div class="help-block"><?php echo $_LANG['AD_WHAT_MAKING_AUTOSEARCH'];?></div>
                    
                    <script type="text/javascript">
                        function toggleFields(){
                            var copy = $('#copy_parent_struct').prop('checked');
                            if (copy) {
                                $('.field, .fformat, .flink').prop('disabled', true);
                            } else {
                                $('.field, .fformat, .flink').prop('disabled', false);
                            }
                        }
                        function toggleAutosearch(id){
                            fformat = $('#fformat'+id+' option:selected').val();
                            if (fformat == 'text') {
                                $('#flink'+id).prop('disabled', false).css('color', '');
                            } else {
                                $('#flink'+id).prop('disabled', true).css('color', '#CCC');
                            }
                        }
                    </script>
                    
                    <label>
                        <input type="checkbox" id="copy_parent_struct" name="copy_parent_struct" onchange="toggleFields()" value="1" />
                        <?php echo $_LANG['AD_COPY_PARENT_FEATURES'];?>
                    </label>
                    
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <thead>
                            <tr>
                                <th width="105"><?php echo $_LANG['AD_TYPE'];?></th>
                                <th><?php echo $_LANG['AD_TITLE'];?></th>
                                <th width="120"><?php echo $_LANG['AD_AUTOSEARCH'];?></th>
                            </tr>
                        </thead>
                        <?php for($f=0; $f<15; $f++) { ?>
                            <?php
                                if (isset($fstruct[$f])) {
                                    if (mb_strstr($fstruct[$f], '/~h~/')) {
                                        $ftype = 'html';
                                        $fstruct[$f] = str_replace('/~h~/', '', $fstruct[$f]);
                                    } else if (mb_strstr($fstruct[$f], '/~l~/')) {
                                        $ftype = 'link';
                                        $fstruct[$f] = str_replace('/~l~/', '', $fstruct[$f]);
                                    } else {
                                        $ftype = 'text';
                                    }

                                    if (mb_strstr($fstruct[$f], '/~m~/')) {
                                        $makelink = true;  $fstruct[$f] = str_replace('/~m~/', '', $fstruct[$f]);
                                    } else {
                                        $makelink = false;
                                    }
                                }
                            ?>
                            <tr>
                                <td style="padding-bottom:4px">
                                    <select name="fformat[]" class="form-control" id="fformat<?php echo $f;?>" style="width:100px" onchange="toggleAutosearch('<?php echo $f;?>');">
                                        <option value="text" <?php if(isset($fstruct[$f])) { if ($ftype == 'text') { echo 'selected'; } } ?>><?php echo $_LANG['AD_TEXT'];?></option>
                                        <option value="html" <?php if(isset($fstruct[$f])) { if ($ftype == 'html') { echo 'selected'; } } ?>><?php echo $_LANG['AD_HTML'];?></option>
                                        <option value="link" <?php if(isset($fstruct[$f])) { if ($ftype == 'link') { echo 'selected'; } } ?>><?php echo $_LANG['AD_LINK'];?></option>
                                    </select>
                                </td>
                                <td style="padding-bottom:4px">
                                    <input type="text" id="fstruct[]" class="form-control" style="width:99%;" name="fstruct[]" value="<?php if (isset($fstruct[$f])) { echo $this->escape(stripslashes($fstruct[$f])); } ?>" />
                                </td>
                                <td style="padding-bottom:2px">
                                    <div id="flink<?php echo $f; ?>" class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-default <?php if(isset($fstruct[$f])) { if ($makelink) { echo 'active'; } } ?>">
                                            <input type="radio" name="flink[<?php echo $f;?>]" <?php if(isset($fstruct[$f])) { if ($makelink) { echo 'checked="checked"'; } } ?> value="1"> <?php echo $_LANG['YES']; ?>
                                        </label>
                                        <label class="btn btn-default <?php if(isset($fstruct[$f])) { if (!$makelink) { echo 'active'; } } else { echo 'active'; } ?>">
                                            <input type="radio" name="flink[<?php echo $f;?>]" <?php if(isset($fstruct[$f])) { if (!$makelink) { echo 'checked="checked"'; } } else { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <script type="text/javascript">
                                toggleAutosearch('<?php echo $f;?>');
                            </script>
                        <?php } ?>
                    </table>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_MAKING_HTML_FIELDS'];?> <a href="index.php?view=filters" target="_blank"><?php echo $_LANG['AD_FILTERS'];?></a>?</label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'filters', false)) { echo 'active'; } ?>">
                            <input type="radio" name="filters" <?php if(cmsCore::getArrVal($mod, 'filters', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'filters', false)) { echo 'active'; } ?>">
                            <input type="radio" name="filters" <?php if (!cmsCore::getArrVal($mod, 'filters', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_CAT_DESCRIPTION']; ?></label>
                    <?php cmsCore::insertEditor('description', $mod['description'], '200', '100%'); ?>
                </div>
            </td>

            <!-- боковая ячейка -->
            <td width="400">
                <div class="uitabs">
                    <ul>
                        <li><a href="#tab_publish"><?php echo $_LANG['AD_TAB_PUBLISH']; ?></a></li>
                        <li><a href="#tab_items"><?php echo $_LANG['AD_ITEMS']; ?></a></li>
                        <li><a href="#tab_access"><?php echo $_LANG['AD_TAB_ACCESS']; ?></a></li>
                        <li><a href="#tab_seo">SEO</a></li>
                    </ul>
                    
                    <div id="tab_publish">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="published" id="published" value="1" <?php if ($mod['published'] || $opt == 'add_cat') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_IF_PUBLIC_CAT'];?>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <select class="form-control" style="height:200px" name="parent_id" size="8">
                                <option value="<?php echo $rootid; ?>" <?php if (cmsCore::getArrVal($mod, 'parent_id', $rootid) == $rootid) { echo 'selected'; } ?>><?php echo $_LANG['AD_CATALOG_ROOT']; ?></option>
                                <?php echo $uc_cats_opt; ?>
                            </select>
                            <select class="form-control" name="view_type">
                                <option value="list" <?php if (cmsCore::getArrVal($mod, 'view_type') == 'list') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_LIST'];?></option>
                                <option value="thumb" <?php if (cmsCore::getArrVal($mod, 'view_type') == 'thumb') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_GALERY'];?></option>
                                <option value="shop" <?php if (cmsCore::getArrVal($mod, 'view_type') == 'shop') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_SHOP'];?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_VIEW_RUBRIC'];?></label>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="showmore" value="1" <?php if (cmsCore::getArrVal($mod, 'showmore')) { echo 'checked="checked"'; } ?> />
                                    <?php echo $_LANG['AD_LINK_DETAILS'];?>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_ratings" value="1" <?php if (cmsCore::getArrVal($mod, 'is_ratings')) { echo 'checked="checked"'; } ?>/>
                                    <?php echo $_LANG['AD_ITEMS_RATING'];?>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="showtags" value="1" <?php if (cmsCore::getArrVal($mod, 'showtags')) { echo 'checked="checked"'; } ?>/>
                                    <?php echo $_LANG['AD_TAGS_VIEW'];?>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="showsort" value="1" <?php if (cmsCore::getArrVal($mod, 'showsort')) { echo 'checked="checked"'; } ?>/>
                                    <?php echo $_LANG['AD_SORT_VIEW'];?>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="showabc" value="1" <?php if (cmsCore::getArrVal($mod, 'showabc')) { echo 'checked="checked"'; } ?>/>
                                    <?php echo $_LANG['AD_ABC'];?>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab_items">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_FIELDS_QUANTITY']; ?></label>
                            <input type="number" class="form-control" name="fieldsshow" value="<?php echo cmsCore::getArrVal($mod, 'fields_show', 10); ?>"/>
                            <div class="help-block"><?php echo $_LANG['AD_HOW_MANY_FIELDS']; ?></div>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['ORDER_ARTICLES']; ?></label>
                            <select class="form-control" name="orderby">
                                <option value="title" <?php if(cmsCore::getArrVal($mod, 'orderby') == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET'];?></option>
                                <option value="pubdate" <?php if(cmsCore::getArrVal($mod, 'orderby') == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR'];?></option>
                                <option value="rating" <?php if(cmsCore::getArrVal($mod, 'orderby') == 'rating') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_RATING'];?></option>
                                <option value="hits" <?php if (cmsCore::getArrVal($mod, 'orderby') == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS']; ?></option>
                            </select>
                            <select class="form-control" name="orderto">
                                <option value="desc" <?php if(cmsCore::getArrVal($mod, 'orderto') == 'desc') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_DECREMENT']; ?></option>
                                <option value="asc" <?php if(cmsCore::getArrVal($mod, 'orderto') == 'asc') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_INCREMENT']; ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_HOW_MANY_ITEMS']; ?></label>
                            <input type="number" class="form-control" name="perpage" value="<?php echo cmsCore::getArrVal($mod, 'perpage', 20); ?>"/>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_WHATS_NEW']; ?></label>
                            <select class="form-control" name="shownew">
                                <option value="1" <?php if (cmsCore::getArrVal($mod, 'shownew', false)) { echo 'selected="selected"'; } ?>><?php echo $_LANG['YES'];?></option>
                                <option value="0" <?php if (!cmsCore::getArrVal($mod, 'shownew', false)) { echo 'selected="selected"'; } ?>><?php echo $_LANG['NO'];?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_HOW_LONG_TIME_NEW']; ?></label>
                            <table width="100%">
                                <tr>
                                    <td width="100">
                                        <input type="number" class="form-control" name="int_1" value="<?php echo (int)cmsCore::getArrVal($mod, 'newint', 0); ?>"/>
                                    </td>
                                    <td>
                                        <select class="form-control" name="int_2">
                                            <option value="HOUR"  <?php if(mb_strstr(cmsCore::getArrVal($mod, 'newint', ''), 'HOUR')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['HOUR10'];?></option>
                                            <option value="DAY" <?php if(mb_strstr(cmsCore::getArrVal($mod, 'newint', ''), 'DAY')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['DAY10'];?></option>
                                            <option value="MONTH" <?php if(mb_strstr(cmsCore::getArrVal($mod, 'newint', ''), 'MONTH')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['MONTH10'];?></option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div id="tab_access">
                        <div class="form-group">
                            <label>
                                <input name="is_public" type="checkbox" id="is_public" onclick="checkGroupList()" value="1" <?php if (cmsCore::getArrVal($mod, 'is_public', false)){ echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_USERS_CAN_ADD_ITEM']; ?>
                            </label>
                            <div class="help-block"><?php echo $_LANG['AD_IF_ENABLE'];?></div>
                            
                            <div id="grp">
                                <label><?php echo $_LANG['AD_ALLOW_GROUPS'];?></label>
                                
                                <select id="showin" class="form-control" name="showfor[]" size="6" multiple="multiple" <?php if (!cmsCore::getArrVal($mod, 'is_public', false)) { ?>disabled="disabled"<?php } ?>>
                                    <?php
                                    if (!empty($groups)) {
                                        foreach ($groups as $group) {
                                            if ($group['alias'] != 'guest') {
                                                echo '<option value="'. $group['id'] .'"';
                                                if ($opt == 'edit_cat') {
                                                    if (in_array($group['id'], $ord)) {
                                                        echo 'selected="selected"';
                                                    }
                                                }

                                                echo '>';
                                                echo $group['title'].'</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                
                                <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL'];?></div>
                            </div>
                        </div>
                        
                        <?php if ($is_billing) { ?>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_ITEM_COST'];?></label>
                            <input type="text" class="form-control" name="cost" value="<?php echo $mod['cost']; ?>" /> <?php echo $_LANG['BILLING_POINT10'];?>
                            <div class="help-block"><?php echo $_LANG['AD_DEFAULT_COST'];?></div>
                        </div>
                        <?php } ?>
                        
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="can_edit" name="can_edit" value="1" <?php if (cmsCore::getArrVal($mod, 'can_edit')) { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_ALLOW_EDIT'];?>
                            </label>
                            <div class="help-block"><?php echo $_LANG['AD_IF_ALLOW_EDIT'];?></div>
                        </div>
                    </div>
                    
                    <div id="tab_seo">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PAGE_TITLE']; ?></label>
                            <input type="text" id="pagetitle" class="form-control" name="pagetitle" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'pagetitle', '')); ?>" />
                            <div class="help-block"><?php echo $_LANG['AD_IF_UNKNOWN_PAGETITLE'];?></div>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['KEYWORDS']; ?></label>
                            <textarea id="meta_keys" class="form-control" name="meta_keys" rows="4"><?php echo $this->escape(cmsCore::getArrVal($mod, 'meta_keys', '')); ?></textarea>
                            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA'];?></div>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['DESCRIPTION']; ?></label>
                            <textarea id="meta_desc" class="form-control" name="meta_desc" rows="4"><?php echo $this->escape(cmsCore::getArrVal($mod, 'meta_desc', '')); ?></textarea>
                            <div class="help-block"><?php echo $_LANG['AD_LESS_THAN'];?></div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL'];?>" onclick="window.history.back();"/>
        
        <input name="opt" type="hidden" id="opt" <?php if ($opt == 'add_cat') { echo 'value="submit_cat"'; } else { echo 'value="update_cat"'; } ?> />
        <?php
            if ($opt == 'edit_cat') {
                echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
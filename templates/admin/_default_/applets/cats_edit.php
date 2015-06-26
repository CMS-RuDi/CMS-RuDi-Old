<form id="addform" name="addform" method="post" action="index.php">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <input type="hidden" name="view" value="cats" />

    <table class="table">
        <tr>
            <!-- главная ячейка -->
            <td valign="top">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_TITLE_PARTITION']; ?></label>
                    <input type="text" id="title" class="form-control" name="title" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />
                </div>
                    
                <div class="form-group">
                    <label><?php echo $_LANG['AD_TEMPLATE_PARTITION'];?></label>
                    <input type="text" class="form-control" name="tpl" value="<?php echo cmsCore::getArrVal($mod, 'tpl', ''); ?>" />
                </div>
                    
                <div class="form-group">
                    <label><?php echo $_LANG['AD_PARENT_PARTITION'];?></label>
                    <div class="parent_notice" style="color:red;margin:4px 0px;display:none"><?php echo $_LANG['AD_ANOTHER_PARENT']; ?></div>

                    <select name="parent_id" size="12" id="parent_id" class="form-control" onchange="if($('option:selected',this).data('nsleft')>='<?php echo cmsCore::getArrVal($mod, 'NSLeft', 0); ?>' && $('option:selected',this).data('nsright')<='<?php echo cmsCore::getArrVal($mod, 'NSRight', 0); ?>'){ $('.parent_notice').show();$('#add_mod').prop('disabled', true); } else { $('.parent_notice').hide();$('#add_mod').prop('disabled', false); }">
                        <option value="<?php echo $rootid; ?>" <?php if (!isset($mod['parent_id']) || cmsCore::getArrVal($mod, 'parent_id', '') == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_SECTION'];?></option>
                        <?php echo $category_opt; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_SECTION_DESCRIPT'];?></label>
                    <?php cmsCore::insertEditor('description', cmsCore::getArrVal($mod, 'description', ''), '250', '100%'); ?>
                </div>
            </td>

            <!-- боковая -->
            <td valign="top" style="width:500px;">
                <div class="uitabs">
                    <ul id="tabs">
                        <li><a href="#upr_publish"><span><?php echo $_LANG['AD_TAB_PUBLISH']; ?></span></a></li>
                        <li><a href="#upr_seo"><span>SEO</span></a></li>
                        <li><a href="#upr_editors"><span><?php echo $_LANG['AD_EDITORS']; ?></span></a></li>
                        <li><a href="#upr_foto"><span><?php echo $_LANG['AD_FOTO']; ?></span></a></li>
                        <li><a href="#upr_access"><span><?php echo $_LANG['AD_TAB_ACCESS']; ?></span></a></li>
                    </ul>
                        
                    <div id="upr_publish">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="published" id="published" value="1" <?php if (cmsCore::getArrVal($mod, 'published', 0) || $do=='add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_PUBLIC_SECTION'];?>
                            </label>
                        </div>
                            
                        <div class="form-group url_cat" style=" <?php if ($do == 'edit'){  ?>display:none;<?php } ?>">
                            <label><?php echo $_LANG['AD_SECTION_URL'];?></label>
                            <input type="text" class="form-control" name="url" value="<?php echo cmsCore::getArrVal($mod, 'url', ''); ?>" />
                            <div class="help-block"><?php echo $_LANG['AD_FROM_TITLE'];?></div>
                        </div>
                            
                        <?php if ($do == 'edit') {  ?>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="update_seolink" value="1" onclick="$('.url_cat').slideToggle('fast');" />
                                    <?php echo $_LANG['AD_NEW_LINK'];?>
                            </label>
                            <div class="help-block url_cat" style="display:none;"><b style="color:#F00;"><?php echo $_LANG['ATTENTION'];?>:</b> <?php echo $_LANG['AD_NO_LINKS'];?></div>
                        </div>
                        <?php } ?>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_SORT_ARTICLES'];?></label>
                            <select id="orderby" class="form-control" name="orderby">
                                <?php $mod['orderby'] = cmsCore::getArrVal($mod, 'orderby', ''); ?>
                                <option value="pubdate" <?php if ($mod['orderby'] == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR'];?></option>
                                <option value="title" <?php if ($mod['orderby'] == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_TITLE'];?></option>
                                <option value="ordering" <?php if ($mod['orderby'] == 'ordering') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ORDER'];?></option>
                                <option value="hits" <?php if ($mod['orderby'] == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS'];?></option>
                            </select>
                            <select id="orderto" class="form-control" name="orderto">
                                <?php $mod['orderto'] = cmsCore::getArrVal($mod, 'orderto', ''); ?>
                                <option value="ASC" <?php if ($mod['orderto'] == 'ASC') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT'];?></option>
                                <option value="DESC" <?php if ($mod['orderto'] == 'DESC') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT'];?></option>
                            </select>
                        </div>

                        <table width="100%">
                            <tr>
                                <td>
                                    <strong><?php echo $_LANG['AD_HOW_MANY_COLUMNS'];?></strong>
                                </td>
                                <td>
                                    <input class="form-control uispin" name="maxcols" type="text" style="width:50px" value="<?php echo cmsCore::getArrVal($mod, 'maxcols', 1); ?>" />
                                </td>
                            </tr>
                        </table>
                            
                        <div class="form-group">
                            <h4><?php echo $_LANG['AD_HOW_PUBLISH_SET'];?></h4>
                            <table class="table">
                                <tr>
                                    <td>
                                        <?php echo $_LANG['AD_PREVIEW'];?>
                                    </td>
                                    <td>
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php
                                                $act1=$act2=$chk1=$chk2='';
                                                if (cmsCore::getArrVal($mod, 'showdesc') || $do == 'add') {
                                                    $act1 = 'active';
                                                    $chk1 = 'checked="checked"';
                                                } else {
                                                    $act2 = 'active';
                                                    $chk2 = 'checked="checked"';
                                                }
                                            ?>
                                            <label class="btn btn-default <?php echo $act1; ?>">
                                                <input type="radio" name="showdesc" <?php echo $chk1; ?> value="1" /> <?php echo $_LANG['YES'];?>
                                            </label>
                                            <label class="btn btn-default <?php echo $act2; ?>">
                                                <input type="radio" name="showdesc" <?php echo $chk2; ?> value="0" /> <?php echo $_LANG['NO'];?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $_LANG['AD_CALENDAR_VIEW'];?>
                                    </td>
                                    <td>
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php
                                                $act1=$act2=$chk1=$chk2='';
                                                if (cmsCore::getArrVal($mod, 'showdate') || $do == 'add') {
                                                    $act1 = 'active';
                                                    $chk1 = 'checked="checked"';
                                                } else {
                                                    $act2 = 'active';
                                                    $chk2 = 'checked="checked"';
                                                }
                                            ?>
                                            <label class="btn btn-default <?php echo $act1; ?>">
                                                <input type="radio" name="showdate" <?php echo $chk1; ?> value="1" /> <?php echo $_LANG['YES'];?>
                                            </label>
                                            <label class="btn btn-default <?php echo $act2; ?>">
                                                <input type="radio" name="showdate" <?php echo $chk2; ?> value="0" /> <?php echo $_LANG['NO'];?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $_LANG['AD_HOW_MANY_COMENTS'];?>
                                    </td>
                                    <td>
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php
                                                $act1=$act2=$chk1=$chk2='';
                                                if (cmsCore::getArrVal($mod, 'showcomm') || $do == 'add') {
                                                    $act1 = 'active';
                                                    $chk1 = 'checked="checked"';
                                                } else {
                                                    $act2 = 'active';
                                                    $chk2 = 'checked="checked"';
                                                }
                                            ?>
                                            <label class="btn btn-default <?php echo $act1; ?>">
                                                <input type="radio" name="showcomm" <?php echo $chk1; ?> value="1" /> <?php echo $_LANG['YES'];?>
                                            </label>
                                            <label class="btn btn-default <?php echo $act2; ?>">
                                                <input type="radio" name="showcomm" <?php echo $chk2; ?> value="0" /> <?php echo $_LANG['NO'];?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $_LANG['AD_HOW_MANY_TAGS'];?>
                                    </td>
                                    <td>
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php
                                                $act1=$act2=$chk1=$chk2='';
                                                if (cmsCore::getArrVal($mod, 'showtags') || $do == 'add') {
                                                    $act1 = 'active';
                                                    $chk1 = 'checked="checked"';
                                                } else {
                                                    $act2 = 'active';
                                                    $chk2 = 'checked="checked"';
                                                }
                                            ?>
                                            <label class="btn btn-default <?php echo $act1; ?>">
                                                <input type="radio" name="showtags" <?php echo $chk1; ?> value="1" /> <?php echo $_LANG['YES'];?>
                                            </label>
                                            <label class="btn btn-default <?php echo $act2; ?>">
                                                <input type="radio" name="showtags" <?php echo $chk2; ?> value="0" /> <?php echo $_LANG['NO'];?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $_LANG['AD_RSS_VIEW'];?>
                                    </td>
                                    <td>
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php
                                                $act1=$act2=$chk1=$chk2='';
                                                if (cmsCore::getArrVal($mod, 'showrss') || $do == 'add') {
                                                    $act1 = 'active';
                                                    $chk1 = 'checked="checked"';
                                                } else {
                                                    $act2 = 'active';
                                                    $chk2 = 'checked="checked"';
                                                }
                                            ?>
                                            <label class="btn btn-default <?php echo $act1; ?>">
                                                <input type="radio" name="showrss" <?php echo $chk1; ?> value="1" /> <?php echo $_LANG['YES'];?>
                                            </label>
                                            <label class="btn btn-default <?php echo $act2; ?>">
                                                <input type="radio" name="showrss" <?php echo $chk2; ?> value="0" /> <?php echo $_LANG['NO'];?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                            
                        <?php if ($do == 'add') { ?>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_CREATE_LINK'];?></label>
                            <select class="form-control" style="width:99%" name="createmenu">
                                <option value="0" selected="selected"><?php echo $_LANG['AD_DONT_CREATE'];?></option>
                                <?php foreach ($menu_list as $menu) { ?>
                                <option value="<?php echo $menu['id']; ?>">
                                    <?php echo $menu['title']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php } ?>
                    </div>
                        
                    <div id="upr_seo">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PAGE_TITLE']; ?></label>
                            <input type="text" id="pagetitle" class="form-control" name="pagetitle" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'pagetitle', '')); ?>" />
                            <div class="help-block"><?php echo $_LANG['AD_IF_UNKNOWN_PAGETITLE']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['KEYWORDS']; ?></label>
                            <textarea class="form-control" name="meta_keys" rows="4"><?php echo $this->escape(cmsCore::getArrVal($mod, 'meta_keys', ''));?></textarea>
                            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['DESCRIPTION']; ?></label>
                            <textarea class="form-control" name="meta_desc" rows="6"><?php echo $this->escape(cmsCore::getArrVal($mod, 'meta_desc', ''));?></textarea>
                            <div class="help-block"><?php echo $_LANG['AD_LESS_THAN']; ?></div>
                        </div>
                    </div>
                        
                    <div id="upr_editors">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_USERS_ARTICLES'];?></label>
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default <?php echo $act1; ?>">
                                    <input type="radio" name="is_public" <?php if (cmsCore::getArrVal($mod, 'is_public')) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES'];?>
                                </label>
                                <label class="btn btn-default <?php echo $act2; ?>">
                                    <input type="radio" name="is_public" <?php if (!cmsCore::getArrVal($mod, 'is_public')) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO'];?>
                                </label>
                            </div>
                            <div class="help-block"><?php echo $_LANG['AD_IF_SWITCH'];?></div>
                        </div>

                        <?php if ($is_billing) { ?>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_COST_ARTICLES_ADD'];?></label>
                            <input type="text" class="form-control" style="width:50px" name="cost" value="<?php echo cmsCore::getArrVal($mod, 'cost', ''); ?>" /><?php echo $_LANG['BILLING_POINT10'];?>
                            <div class="help-block"><?php echo $_LANG['AD_COST_ARTICLES_BY_DEFAULT'];?></div>
                        </div>
                        <?php } ?>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_EDITORS_SECTION'];?></label>
                            <select class="form-control" name="modgrp_id">
                                <option value="0" <?php if (!isset($mod['modgrp_id']) || cmsCore::getArrVal($mod, 'modgrp_id', '') == 0) { echo 'selected'; }?>><?php echo $_LANG['AD_ONLY_ADMINS'];?></option>
                                <?php echo $user_group_opt; ?>
                            </select>
                            <div class="help-block"><?php echo $_LANG['AD_USERS_CAN_ADMIN'];?></div>
                        </div>
                    </div>
                        
                    <div id="upr_foto">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PHOTOALBUM_CONNECT'];?></label>
                            <select id="album_id" class="form-control" name="album_id" onchange="choosePhotoAlbum()">
                                <option value="0" <?php if (empty($mod['photoalbum']['id'])) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_DONT_CONNECT'];?></option>
                                <?php echo $photo_albums_opt; ?>
                            </select>
                            <div class="help-block"><?php echo $_LANG['AD_PHOTO_BY_ARTICLES'];?></div>
                        </div>
                            
                        <div id="con_photoalbum" <?php if (empty($mod['photoalbum']['id'])) { echo 'style="display:none;"'; }?>>
                            <div class="form-group">
                                <label><?php echo $_LANG['AD_TITLE'];?></label>
                                <input type="text" id="album_header" class="form-control" name="album_header" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'header', 0); ?>" />
                                <div class="help-block"><?php echo $_LANG['AD_OVER_PHOTOS'];?></div>
                            </div>
                                
                            <div class="form-group">
                                <label><?php echo $_LANG['AD_PHOTOS_SORT'];?></label>
                                <select class="form-control" name="album_orderby">
                                    <?php $mod['photoalbum']['orderby'] = cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'orderby', 0); ?>
                                    <option value="title" <?php if ($mod['photoalbum']['orderby'] == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET'];?></option>
                                    <option value="pubdate" <?php if ($mod['photoalbum']['orderby'] == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR'];?></option>
                                    <option value="rating" <?php if ($mod['photoalbum']['orderby'] == 'rating') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_RATING'];?></option>
                                    <option value="hits" <?php if ($mod['photoalbum']['orderby'] == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS'];?></option>
                                </select>
                                <select class="form-control" name="album_orderto">
                                    <?php $mod['photoalbum']['orderto'] = cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'orderto', 0); ?>
                                    <option value="desc" <?php if ($mod['photoalbum']['orderto'] == 'desc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DECREMENT'];?></option>
                                    <option value="asc" <?php if ($mod['photoalbum']['orderto'] == 'asc') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_INCREMENT'];?></option>
                                </select>
                            </div>
                                
                            <div class="form-group">
                                <label><?php echo $_LANG['AD_HOW_MANY_COLUMNS'];?></label>
                                <input type="text" class="form-control" name="album_maxcols" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'maxcols', 2); ?>"/>
                            </div>
                                
                            <div class="form-group">
                                <label><?php echo $_LANG['AD_HOW_MANY_PHOTO'];?></label>
                                <input type="text" class="form-control" name="album_max" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($mod, 'photoalbum'), 'max', 8); ?>"/>
                            </div>
                        </div>
                    </div>
                        
                    <div id="upr_access">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="is_public" name="is_access" onclick="checkGroupList()" value="1" <?php echo $group_public; ?> />
                                <?php echo $_LANG['AD_SHARE'];?>
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
                            <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL'];?></div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
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
    function choosePhotoAlbum() {
        id = $('select[name=album_id]').val();
        if(id != 0){
            $('#con_photoalbum').fadeIn();
        }else{
            $('#con_photoalbum').hide();
        }
    }
</script>
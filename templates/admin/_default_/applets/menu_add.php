<form id="addform" name="addform" method="post" action="index.php">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <input type="hidden" name="view" value="menu" />

    <table class="table">
        <tr>
            <td valign="top">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_MENU_POINT_TITLE']; ?></label>
                            <input type="text" id="title" class="form-control" style="width:100%" name="title" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', ''));?>" />
                            <div class="help-block"><?php echo $_LANG['AD_VIEW_IN_SITE']; ?></div>
                        </div>
                        
                        <?php if (count($langs) > 1) { ?>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_LANG_TITLES']; ?></label>
                            <?php foreach ($langs as $lang) { ?>
                                <div>
                                    <strong><?php echo $lang; ?>:</strong>
                                    <input name="titles[<?php echo $lang; ?>]" type="text" style="width:97%" value="<?php echo $this->escape(cmsCore::getArrVal($mod['titles'], $lang, '')); ?>" placeholder="<?php echo $_LANG['AD_HINT_DEFAULT']; ?>" />
                                </div>
                            <?php } ?>
                            <div class="help-block"><?php echo $_LANG['AD_LANG_TITLES_HINT']; ?></div>
                        </div>
                        <?php } ?>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PARENT_POINT']; ?></label>
                            <select id="parent_id" class="form-control" style="width:100%" name="parent_id" size="10">
                                <option value="<?php echo $rootid; ?>" <?php if (cmsCore::getArrVal($mod, 'parent_id', $rootid) == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_MENU_ROOT']; ?></option>
                                <?php echo $menu_opt; ?>
                            </select>
                            <input type="hidden" name="oldparent" value="<?php echo cmsCore::getArrVal($mod, 'parent_id', '');?>" />
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_MENU_POINT_ACTION']; ?></label>
                            <select id="linktype" class="form-control" style="width:100%" name="mode" onchange="showMenuTarget()">
                                <?php $link_type = cmsCore::getArrVal($mod, 'linktype', 'link') ?>
                                <option value="link" <?php if ($link_type == 'link') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_LINK']; ?></option>
                                <option value="content" <?php if ($link_type == 'content') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_ARTICLE']; ?></option>
                                <?php if ($video_installed) { ?> 
                                    <option value="video_cat" <?php if ($link_type == 'video_cat') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_VIDEO_PARTITION']; ?></option> 
                                <?php } ?>
                                <option value="category" <?php if ($link_type == 'category') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_PARTITION']; ?></option>
                                <option value="component" <?php if ($link_type == 'component') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_COMPONENT']; ?></option>
                                <option value="blog" <?php if ($link_type == 'blog') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_BLOG']; ?></option>
                                <option value="uccat" <?php if ($link_type == 'uccat') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_CATEGORY']; ?></option>
                                <option value="photoalbum" <?php if ($link_type == 'photoalbum') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_ALBUM']; ?></option>
                            </select>
                        </div>
                            
                        <div id="t_link" class="form-group menu_target" style="display:<?php if ($link_type == 'link' || $link_type == 'ext') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_LINK']; ?></label>
                            <input type="text" id="link" class="form-control" style="width:100%" name="link" size="50" value="<?php if ($link_type == 'link' || $link_type == 'ext') { echo cmsCore::getArrVal($mod, 'link', ''); } ?>" />
                            <div class="help-block"><?php echo $_LANG['AD_LINK_HINT']; ?> <b>http://</b></div>
                        </div>
                            
                        <div id="t_content" class="form-group menu_target" style="display:<?php if ($link_type == 'content') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_ARTICLE'] ; ?></label>
                            <select id="content" class="form-control" style="width:100%" name="content">
                                <?php echo $content_opt; ?>
                            </select>
                        </div>
                            
                        <?php if ($video_installed) { ?> 
                        <div id="t_video_cat" class="form-group menu_target" style="display:<?php if ($link_type == 'video_cat') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_PARTITION']; ?></label>
                            <select id="video_cat" class="form-control" style="width:100%" name="video_cat"> 
                                <?php echo $video_cats_opt; ?> 
                            </select>
                        </div>
                        <?php } ?>
                            
                        <div id="t_category" class="form-group menu_target" style="display:<?php if ($link_type == 'category') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_PARTITION']; ?></label>
                            <select id="category" class="form-control" style="width:100%" name="category"> 
                                <?php echo $category_opt; ?> 
                            </select>
                        </div>
                            
                        <div id="t_component" class="form-group menu_target" style="display:<?php if ($link_type == 'component') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_COMPONENT']; ?></label>
                            <select id="component" class="form-control" style="width:100%" name="component"> 
                                <?php echo $components_opt; ?> 
                            </select>
                        </div>
                            
                        <div id="t_blog" class="form-group menu_target" style="display:<?php if ($link_type == 'blog') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_BLOG']; ?></label>
                            <select id="blog" class="form-control" style="width:100%" name="blog"> 
                                <?php echo $blogs_opt; ?> 
                            </select>
                        </div>
                            
                        <div id="t_uccat" class="form-group menu_target" style="display:<?php if ($link_type == 'uccat') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_CATEGORY']; ?></label>
                            <select id="blog" class="form-control" style="width:100%" name="blog"> 
                                <?php echo $uc_cats_opt; ?> 
                            </select>
                        </div>
                            
                        <div id="t_photoalbum" class="form-group menu_target" style="display:<?php if ($link_type == 'photoalbum') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_ALBUM']; ?></label>
                            <select id="blog" class="form-control" style="width:100%" name="blog"> 
                                <?php echo $photo_albums_opt; ?> 
                            </select>
                        </div>
                    </div>
                </div>
            </td>

            <td width="400" valign="top">
                <div class="uitabs">
                    <ul id="tabs">
                        <li><a href="#upr_publish"><span><?php echo $_LANG['AD_TAB_PUBLISH']; ?></span></a></li>
                        <li><a href="#upr_access"><span><?php echo $_LANG['AD_TAB_ACCESS']; ?></span></a></li>
                        <li><a href="#upr_menu"><span><?php echo $_LANG['AD_MENU']; ?></span></a></li>
                    </ul>
                        
                    <div id="upr_publish">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="published" value="1" <?php if (cmsCore::getArrVal($mod, 'published') || $do == 'add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_MENU_POINT_PUBLIC']; ?>
                            </label>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_OPEN_POINT']; ?></label>
                            <select id="target" class="form-control" style="width:100%" name="target">
                                <option value="_self" <?php if (cmsCore::getArrVal($mod, 'target') == '_self') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_SELF']; ?></option>
                                <option value="_parent"><?php echo $_LANG['AD_PARENT'];?></option>
                                <option value="_blank" <?php if (cmsCore::getArrVal($mod, 'target') == '_blank') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_BLANK']; ?></option>
                                <option value="_top" <?php if (cmsCore::getArrVal($mod, 'target') == '_top') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_TOP']; ?></option>
                            </select>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['TEMPLATE']; ?></label>
                            <select id="template" class="form-control" style="width:100%" name="template"  >
                                <option value="0" <?php if (!cmsCore::getArrVal($mod, 'template')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DEFAULT'];?></option>
                                <?php foreach ($templates as $template) { ?>
                                    <?php if ($template == 'admin') { continue; } ?>
                                    <option value="<?php echo $template; ?>" <?php echo (cmsCore::getArrVal($mod, 'template') ? 'selected="selected"': ''); ?>><?php echo $template; ?></option>
                                <?php } ?>
                            </select>
                            <div class="help-block"><?php echo $_LANG['AD_DESIGN_CHANGE'] ;?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_ICON_PICTURE']; ?></label>
                            <input type="text" id="iconurl" class="form-control" style="width:100%" name="iconurl" size="30" value="<?php echo cmsCore::getArrVal($mod, 'iconurl', ''); ?>" />
                                
                            <a id="iconlink" style="display:block;" href="javascript:showIcons()"><?php echo $_LANG['AD_CHECK_ICON'];?></a>
                            <div id="icondiv" style="display:none; padding:6px;border:solid 1px gray;background:#FFF">
                                <div><?php echo $iconList; ?></div>
                            </div>
                                
                            <div class="help-block"><?php echo $_LANG['AD_ICON_FILENAME'] ;?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_CSS_CLASS']; ?></label>
                            <input type="text" class="form-control" style="width:100%" name="css_class" size="30" value="<?php echo cmsCore::getArrVal($mod, 'css_class', ''); ?>" />
                        </div>
                    </div>
                        
                    <div id="upr_access">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_public" id="is_public" onclick="checkAccesList()" value="1" <?php if ($do != 'edit' || !$mod['access_list']) { ?>checked="checked"<?php } ?> />
                                <?php echo $_LANG['AD_SHARE']; ?>
                            </label>
                            <div class="help-block"><?php echo $_LANG['AD_VIEW_IF_CHECK'];?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_GROUPS_VIEW']; ?></label>
                            <select id="allow_group" class="form-control" style="width: 99%" name="allow_group[]"  size="6" multiple="multiple" <?php if ($do != 'edit' || !$mod['access_list']) { ?>disabled="disabled"<?php } ?>>
                            <?php foreach($groups as $group) { ?>
                                <option value="<?php echo $group['id']; ?>"
                                <?php if ($do == 'edit' && cmsCore::getArrVal($mod, 'access_list')) {
                                    if (in_array($group['id'], $access_list)){
                                        echo 'selected="selected"';
                                    }
                                } ?>>
                                <?php echo $group['title']; ?></option>
                            <?php } ?>
                            </select>
                            <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL'];?></div>
                        </div>
                            
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="is_lax" name="is_lax" value="1" <?php if(cmsCore::getArrVal($mod, 'is_lax')) {?>checked="checked"<?php } ?> />
                                <?php echo $_LANG['AD_ONLY_CHILD_ITEM']; ?>
                            </label>
                        </div>
                    </div>
                        
                    <div id="upr_menu">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_MENU_TO_VIEW'];?></label>
                            <select class="form-control" style="width: 99%" name="menu[]" size="9" multiple="multiple">
                                <?php foreach ($menu_list as $menu) { ?>
                                <option value="<?php echo $menu['id']; ?>" <?php if (in_array($menu['id'], cmsCore::getArrVal($mod, 'menu', array()))) { echo 'selected="selected"'; }?>>
                                        <?php echo $menu['title']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL'];?></div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div>
        <input type="button" class="btn btn-primary" name="add_mod" onclick="submitItem()" value="<?php echo $_LANG['SAVE']; ?> " />
        <input type="button" class="btn btn-default" name="back"  value="<?php echo $_LANG['CANCEL'];?>" onclick="window.location.href='index.php?view=menu';" />
        <input type="hidden" name="do" <?php if ($do=='add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
        <?php
            if ($do == 'edit') {
                echo '<input type="hidden" name="id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
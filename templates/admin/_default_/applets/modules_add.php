<form id="addform" name="addform" method="post" action="index.php">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <input type="hidden" name="view" value="modules" />

    <table class="table">
        <tr><td>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $_LANG['AD_MODULE_TITLE']; ?> (<input type="checkbox" class="uittip" title="<?php echo $_LANG['AD_VIEW_TITLE'];?>" name="showtitle" <?php if ($mod['showtitle'] || $do == 'add') { echo 'checked="checked"'; } ?> value="1" />)</label>
                        <input type="text" id="title" class="form-control" style="width:100%" name="title" value="<?php echo $this->escape($mod['title']);?>" />
                        <div class="help-block"><?php echo $_LANG['AD_VIEW_IN_SITE']; ?></div>
                    </div>

                    <?php if (count($langs) > 1) { ?>
                        <label><?php echo $_LANG['AD_LANG_TITLES']; ?></label>
                        <?php foreach ($langs as $lang) { ?>
                            <div>
                                <strong><?php echo $lang; ?>:</strong>
                                <input name="titles[<?php echo $lang; ?>]" type="text" style="width:97%" value="<?php echo $this->escape($mod['titles'][$lang]); ?>" placeholder="<?php echo $_LANG['AD_HINT_DEFAULT']; ?>" />
                            </div>
                        <?php } ?>
                        <div class="help-block"><?php echo $_LANG['AD_LANG_TITLES_HINT']; ?></div>
                    <?php } ?> 

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_MODULE_NAME']; ?></label>
                        <?php if (cmsCore::getArrVal($mod, 'user', 1) == 1) { ?>
                            <input type="text" id="name" class="form-control" style="width:99%" name="name" value="<?php echo $this->escape($mod['name']);?>" />
                        <?php } else { ?>
                            <input type="text" id="name" class="form-control" style="width:99%" name="" value="<?php echo cmsCore::getArrVal($mod, 'name', ''); ?>" disabled="disabled" />
                            <input type="hidden" name="name" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'name', ''));?>" />
                        <?php } ?>
                        <div class="help-block"><?php echo $_LANG['AD_SHOW_ADMIN']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_PREFIX_CSS']; ?></label>
                        <input type="text" id="css_prefix" class="form-control" style="width:154px" name="css_prefix" value="<?php echo cmsCore::getArrVal($mod, 'css_prefix', ''); ?>" />
                    </div>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_DEFOLT_VIEW']; ?></label>
                        <select id="position" class="form-control" style="width:100%" name="position">
                            <?php
                                if ($pos){
                                    foreach($pos as $key=>$position) {
                                        if (cmsCore::getArrVal($mod, 'position', '') == $position) {
                                            echo '<option value="'. $position .'" selected>'. $position .'</option>';
                                        } else {
                                            echo '<option value="'. $position .'">'. $position .'</option>';
                                        }
                                    }
                                }
                            ?>
                        </select>

                        <div class="help-block">
                            <?php echo $_LANG['AD_POSITION_MUST_BE']; ?>
                            <?php if ($positions_img_exist) { ?>
                                <a href="#myModal" role="button" class="btn btn-sm btn-default" data-toggle="modal"><?php echo $_LANG['AD_SEE_VISUALLY']; ?></a>
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h4 class="modal-title" id="myModalLabel"><?php echo $_LANG['AD_TPL_POS']; ?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/positions.jpg" alt="<?php echo $_LANG['AD_TPL_POS']; ?>" style="width:100%;height:auto;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_MODULE_TEMPLATE']; ?></label>
                        <select id="template" class="form-control" style="width:100%" name="template">
                            <?php
                                foreach ($tpls as $tpl) {
                                    $selected = ($mod['template'] == $tpl || (!$mod['template'] && $tpl == 'module' )) ? 'selected="selected"' : '';
                                    echo '<option value="'. $tpl .'" '. $selected .'>'. $tpl .'</option>';
                                }
                            ?>
                        </select>
                        <div class="help-block"><?php echo $_LANG['AD_FOLDER_MODULES'];?></div>
                    </div>

                    <?php if ($do == 'add') { ?>
                    <div class="form-group">
                        <label><?php echo $_LANG['AD_MODULE_TYPE']; ?></label>
                        <select id="operate" class="form-control" style="width:100%" name="operate" onchange="checkDiv()" >
                            <option value="user" selected="selected"><?php echo $_LANG['AD_MODULE_TYPE_NEW'];?></option>
                            <option value="clone"><?php echo $_LANG['AD_MODULE_TYPE_COPY'];?></option>
                        </select>
                    </div>
                    <?php } ?>

                    <?php if (!isset($mod['user']) || $mod['user'] == 1 || $do == 'add') { ?>
                    <div id="user_div" class="form-group">
                        <label><?php echo $_LANG['AD_MODULE_CONTENT']; ?></label>
                        <div><?php insertPanel(); ?></div>
                        <div><?php cmsCore::insertEditor('content', $mod['content'], '250', '100%'); ?></div>
                    </div>
                    <?php } ?>

                    <div id="clone_div" class="form-group" style="display:none;">
                        <label><?php echo $_LANG['AD_MODULE_COPY']; ?></label>
                        <select id="clone_id" class="form-control" style="width:100%" name="clone_id">
                            <?php echo $modules_opt; ?>
                        </select>
                        <label>
                            <input type="checkbox" name="del_orig" value="1" />
                            <?php echo $_LANG['AD_ORIGINAL_MODULE_DELETE'];?>
                        </label>
                    </div>
                </div>
            </div>
        </td>

        <!-- боковая ячейка -->
        <td width="400" valign="top">
            <div class="uitabs">
                <ul id="tabs">
                    <li><a href="#upr_publish"><span><?php echo $_LANG['AD_TAB_PUBLISH']; ?></span></a></li>

                    <?php if ((($mod['is_external'] && $do == 'edit') || $do == 'add') && cmsCore::c('config')->cache) { ?>
                    <li><a href="#upr_cache"><span><?php echo $_LANG['AD_MODULE_CACHE']; ?></span></a></li>
                    <?php } ?>

                    <li><a href="#upr_access"><span><?php echo $_LANG['AD_TAB_ACCESS']; ?></span></a></li>
                </ul>

                <div id="upr_publish">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="published" id="published" value="1" <?php if ($mod['published'] || $do=='add') { echo 'checked="checked"'; } ?> />
                            <?php echo $_LANG['AD_MODULE_PUBLIC'];?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input name="show_all" id="show_all" type="checkbox" value="1"  onclick="checkGroupList()" <?php if ($show_all) { echo 'checked="checked"'; } ?> />
                            <?php echo $_LANG['AD_VIEW_ALL_PAGES'];?>
                        </label>
                    </div>

                    <div id="grp" class="form-group">
                        <label>
                            <span class="show_list"><?php echo $_LANG['AD_WHERE_MODULE_VIEW'];?></span>
                            <span class="hide_list"><?php echo $_LANG['AD_WHERE_MODULE_NOT_VIEW'];?></span>
                        </label>
                        <div style="height:400px;overflow: auto;border: solid 1px #999; padding:5px 10px; background: #FFF;">
                            <table class="table">
                                <tr>
                                    <td colspan="2" height="25"><strong><?php echo $_LANG['AD_MENU'];?></strong></td>
                                    <td class="show_list" align="center" width="50"><strong><?php echo $_LANG['AD_POSITION'];?></strong></td>
                                </tr>
                                <?php foreach($menu_items as $i) { ?>
                                <tr class="show_list">
                                    <td width="20" height="25">
                                        <input type="checkbox" name="showin[]" id="mid<?php echo $i['id']; ?>" value="<?php echo $i['id']; ?>" <?php if ($i['selected']){ ?>checked="checked"<?php } ?> onclick="$('#p<?php echo $i['id']; ?>').toggle()"/>
                                    </td>
                                    <td style="padding-left:<?php echo ($i['NSLevel'])*6-6; ?>px"><label for="mid<?php echo $i['id']; ?>"><?php echo $i['title']; ?></label></td>
                                    <td align="center">
                                        <select id="p<?php echo $i['id']; ?>" name="showpos[<?php echo $i['id']; ?>]" style="<?php if (!$i['selected']) { ?>display:none<?php } ?>">
                                            <?php foreach($pos as $position){ ?>
                                                <option value="<?php echo $position; ?>" <?php if ($i['position']==$position){ ?>selected="selected"<?php } ?>><?php echo $position; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php foreach($menu_items as $it) { ?>
                                <tr class="hide_list">
                                    <td width="20" height="25">
                                        <input type="checkbox" name="hidden_menu_ids[]" id="hmid<?php echo $it['id']; ?>" value="<?php echo $it['id']; ?>" <?php if (in_array($it['id'], $mod['hidden_menu_ids'])){ ?>checked="checked"<?php } ?> />
                                    </td>
                                    <td style="padding-left:<?php echo ($it['NSLevel'])*6-6; ?>px"><label for="hmid<?php echo $it['id']; ?>"><?php echo $it['title']; ?></label></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <label class="show_list">
                            <input type="checkbox" name="is_strict_bind" id="is_strict_bind" value="1" <?php if ($mod['is_strict_bind']) { echo 'checked="checked"'; } ?> />
                            <?php echo $_LANG['AD_DONT_VIEW']; ?>
                        </label>
                        <label class="hide_list">
                            <input type="checkbox" name="is_strict_bind_hidden" id="is_strict_bind_hidden" value="1" <?php if ($mod['is_strict_bind_hidden']) { echo 'checked="checked"'; } ?> />
                            <?php echo $_LANG['AD_EXCEPT_NESTED']; ?>
                        </label>
                    </div>
                </div>

                <?php if ((($mod['is_external'] && $do == 'edit') || $do == 'add') && cmsCore::c('config')->cache) { ?>
                <div id="upr_cache">
                    <div class="form-group">
                        <label><?php echo $_LANG['AD_DO_MODULE_CACHE']; ?></label>
                        <select id="cache" class="form-control" style="width:100%" name="cache">
                            <option value="0" <?php if (!cmsCore::getArrVal($mod, 'cache')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['NO']; ?></option>
                            <option value="1" <?php if (cmsCore::getArrVal($mod, 'cache')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['YES']; ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_MODULE_CACHE_PERIOD']; ?></label>
                        <table class="table">
                            <tr>
                                <td valign="top"  width="100">
                                    <input id="int_1" class="form-control" style="width:99%" name="cachetime" type="text" value="<?php echo cmsCore::getArrVal($mod, 'cachetime', 0); ?>"/>
                                </td>
                                <td valign="top" style="padding-left:5px">
                                    <select id="int_2" class="form-control" style="width:100%" name="cacheint">
                                        <option value="MINUTE"  <?php if(mb_strstr(cmsCore::getArrVal($mod, 'cacheint', 'MINUTES'), 'MINUTE')) { echo 'selected="selected"'; } ?>><?php echo cmsCore::spellCount(cmsCore::getArrVal($mod, 'cachetime', 0), $_LANG['MINUTE1'], $_LANG['MINUTE2'], $_LANG['MINUTE10'], false); ?></option>
                                        <option value="HOUR"  <?php if(mb_strstr(cmsCore::getArrVal($mod, 'cacheint', 'MINUTES'), 'HOUR')) { echo 'selected="selected"'; } ?>><?php echo cmsCore::spellCount(cmsCore::getArrVal($mod, 'cachetime', 0), $_LANG['HOUR1'], $_LANG['HOUR2'], $_LANG['HOUR10'], false); ?></option>
                                        <option value="DAY" <?php if(mb_strstr(cmsCore::getArrVal($mod, 'cacheint', 'MINUTES'), 'DAY')) { echo 'selected="selected"'; } ?>><?php echo cmsCore::spellCount(cmsCore::getArrVal($mod, 'cachetime', 0), $_LANG['DAY1'], $_LANG['DAY2'], $_LANG['DAY10'], false); ?></option>
                                        <option value="MONTH" <?php if(mb_strstr(cmsCore::getArrVal($mod, 'cacheint', 'MINUTES'), 'MONTH')) { echo 'selected="selected"'; } ?>><?php echo cmsCore::spellCount(cmsCore::getArrVal($mod, 'cachetime', 0), $_LANG['MONTH1'], $_LANG['MONTH2'], $_LANG['MONTH10'], false); ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div style="margin-top:15px">
                            <?php
                                if ($do == 'edit') {
                                    if (!empty($kb_cache)) {
                                        echo '<a href="index.php?view=cache&component=modules&target='. $mod['content'] .'&target_id='. $mod['id'] .'">'. $_LANG['AD_MODULE_CACHE_DELETE'] .'</a> ('. $kb_cache . $_LANG['SIZE_KB'] .')';
                                    } else {
                                        echo '<span style="color:gray">'. $_LANG['AD_NO_CACHE'] .'</span>';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <div id="upr_access">
                    <div class="form-group">
                        <label>
                            <input name="is_public" type="checkbox" id="is_public" onclick="checkAccesList()" value="1" <?php if ($do != 'edit' || !$mod['access_list']) { ?>checked="checked"<?php } ?> />
                            <?php echo $_LANG['AD_SHARE']; ?>
                        </label>
                        <div class="help-block"><?php echo $_LANG['AD_IF_CHECKED']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $_LANG['AD_GROUPS_VIEW']; ?></label>
                        <select style="width: 99%" name="allow_group[]" id="allow_group" size="6" multiple="multiple" <?php if ($do != 'edit' || !$mod['access_list']) { ?>disabled="disabled"<?php } ?>>

                        <?php if ($groups) {
                                foreach($groups as $group) {
                                    echo '<option value="'.$group['id'].'"';
                                    if ($do == 'edit' && $mod['access_list']) {
                                        if (in_array($group['id'], $access_list)) {
                                            echo 'selected="selected"';
                                        }
                                    }

                                    echo '>';
                                    echo $group['title'].'</option>';
                                }
                            } ?>

                        </select>
                        <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?></div>
                    </div>
                </div>
            </div>
        </td></tr>
    </table>
    <p>
        <input type="submit" id="add_mod" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" id="back" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />
        <input type="hidden" id="do" name="do" <?php if ($do == 'add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
        <?php
            if ($do == 'edit') {
                echo '<input name="id" type="hidden" value="'. $mod['id'] .'" />';
            }
        ?>
    </p>
</form>
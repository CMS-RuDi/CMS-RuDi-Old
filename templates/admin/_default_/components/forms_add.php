<?php if ($opt == 'add') { ?>
    <h3><?php echo $_LANG['AD_NEW_FORM']; ?></h3>
<?php } else { ?>
    <h3><?php echo $_LANG['AD_NEW_FORM']; ?></h3>
<?php } ?>

<div style="width:800px;">
<?php if ($opt == 'edit') { ?>
<div class="uitabs">
    <ul>
        <li><a href="#tab_form_desc"><?php echo $_LANG['AD_FORM_PROPERTIES']; ?></a></li>
        <li><a href="#tab_form_edit"><?php echo $_LANG['AD_FIELDS']; ?></a></li>
    </ul>
<?php } ?>
    <div id="tab_form_desc">
        <form id="addform" name="addform" method="post" action="index.php?view=components&do=config&link=forms">
            <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORM_NAME']; ?>:</label>
                <input type="text" id="title" class="form-control" name="title" size="30" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORM_DESTINATION']; ?>:</label>
                <select id="sendto" class="form-control" name="sendto" onChange="toggleSendTo()">
                    <option value="mail" <?php if (cmsCore::getArrVal($mod, 'sendto', 'mail') == 'mail') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_EMAIL_ADDRESS']; ?></option>
                    <option value="user" <?php if (cmsCore::getArrVal($mod, 'sendto', 'mail') == 'user') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PERSON_MESS']; ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_VIEW_FORM_TITLE']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'showtitle', false)) { echo 'active'; } ?>">
                        <input type="radio" name="showtitle" <?php if(cmsCore::getArrVal($mod, 'showtitle', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'showtitle', false)) { echo 'active'; } ?>">
                        <input type="radio" name="showtitle" <?php if (!cmsCore::getArrVal($mod, 'showtitle', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORM_ACTION']; ?>:</label>
                <input type="text" class="form-control" name="form_action" size="30" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'form_action', '')); ?>" />
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FILED_ONLY']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="float:right;">
                    <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'only_fields', false)) { echo 'active'; } ?>">
                        <input type="radio" name="only_fields" <?php if(cmsCore::getArrVal($mod, 'only_fields', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'only_fields', false)) { echo 'active'; } ?>">
                        <input type="radio" name="only_fields" <?php if (!cmsCore::getArrVal($mod, 'only_fields', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORM_TPL']; ?>:</label>
                <input type="text" class="form-control" name="tpl" size="30" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'tpl', '')); ?>" />
                <div class="help-block"><?php echo $_LANG['AD_FORM_TPL_HINT']; ?></div>
            </div>
            
            <div id="sendto_mail" class="form-group" style="display:<?php if (cmsCore::getArrVal($mod, 'sendto', 'mail') == 'mail') { echo 'block'; } else { echo 'none'; } ?>">
                <label><span class="fa fa-mail-forward"></span><?php echo $_LANG['AD_E-MAIL_ADDR']; ?>:</label>
                <input type="text" id="email" class="form-control" name="email" size="30" value="<?php echo cmsCore::getArrVal($mod, 'email', ''); ?>" />
                <div class="help-block"><?php echo $_LANG['AD_E-MAIL_ADDR_HINT']; ?></div>
            </div>
            
            <div id="sendto_user" class="form-group" style="display:<?php if (cmsCore::getArrVal($mod, 'sendto', 'mail') == 'user') { echo 'block'; } else { echo 'none'; } ?>">
                <label><span class="fa fa-male"></span><?php echo $_LANG['AD_RECIPIENT']; ?>:</label>
                <select id="user_id" class="form-control" name="user_id">
                    <?php echo $users_opt; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_FORM_EXPLANT']; ?>:</label>
                <?php cmsCore::insertEditor('description', $mod['description'], '280', '100%'); ?>
            </div>
            
            <?php if ($opt == 'add') {
                echo '<p><b>'. $_LANG['AD_NOTE'] .': </b>'. $_LANG['AD_AFTER_CREATE'] .'. </p>';
            } else {
                echo '<p><b>'. $_LANG['AD_NOTE'] .': </b>' . $_LANG['AD_TO_INSERT'];
            }
            ?>

            <div>
                <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
                <input type="hidden" id="do" name="opt" value="<?php if ($opt == 'add') { echo 'submit'; } else { echo 'update'; } ?>" />
            <?php
            if ($opt == 'edit') {
                echo '<input type="hidden" name="item_id" value="'. $mod['id'] .'" />';
            }
            ?>
            </div>
        </form>
    </div>
    
<?php
    if ($opt == 'edit') {
?>
    <div id="tab_form_edit">
        <table class="table" width="750">
            <tr>
                <td width="300" valign="top">
                    <h4 style="border-bottom:solid 1px black; font-size: 14px; margin-bottom: 10px"><b><?php if (empty($field)) { ?><?php echo $_LANG['AD_FIELD_ADD']; ?><?php } else { ?><?php echo $_LANG['AD_FIELD_EDIT']; ?><?php } ?></b></h4>
                    <form id="fieldform" name="fieldform" method="post" action="index.php?view=components&do=config&link=forms">
                        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
                        <input type="hidden" name="opt" value="<?php if (empty($field)) { ?>add_field<?php } else { ?>update_field<?php } ?>"/>
                        <input name="form_id" type="hidden" id="form_id" value="<?php echo $mod['id'] ?>"/>
                        <input name="field_id" type="hidden" value="<?php echo cmsCore::getArrVal($field, 'id', ''); ?>"/>
                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="100"><?php echo $_LANG['AD_FIELD_TYPE']; ?>:</td>
                                <td>
                                    <select name="kind" id="kind" onchange="show()">
                                        <option value="text" <?php if (cmsCore::getArrVal($field, 'kind', 'text') == 'text') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_TEXT']; ?></option>
                                        <option value="link" <?php if (cmsCore::getArrVal($field, 'kind', 'text') == 'link') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_URL']; ?></option>
                                        <option value="textarea" <?php if (cmsCore::getArrVal($field, 'kind', 'text') == 'textarea') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_MILTILINE']; ?></option>
                                        <option value="checkbox" <?php if (cmsCore::getArrVal($field, 'kind', 'text') == 'checkbox') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_YES_NO']; ?></option>
                                        <option value="radiogroup" <?php if (cmsCore::getArrVal($field, 'kind', 'text') == 'radiogroup') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_GROUP_OPTIONS'] ; ?></option>
                                        <option value="list" <?php if (cmsCore::getArrVal($field, 'kind', 'text') == 'list') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_DROP_DOWN']; ?></option>
                                        <option value="menu" <?php if (cmsCore::getArrVal($field, 'kind', 'text') == 'menu') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_TYPE_VISIBLE']; ?></option>
                                        <option value="file" <?php if (cmsCore::getArrVal($field, 'kind', 'text') == 'file') { echo 'selected="selected"'; } ?>><?php echo $_LANG['FILE']; ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $_LANG['AD_TITLE']; ?>:</td>
                                <td><input name="f_title" type="text" id="f_title" size="25" value="<?php echo $this->escape(cmsCore::getArrVal($field, 'title', '')); ?>" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $_LANG['DESCRIPTION']; ?>:</td>
                                <td><input name="f_description" type="text" id="f_description" size="25" value="<?php echo $this->escape(cmsCore::getArrVal($field, 'description', '')) ?>" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $_LANG['AD_FIELD_ORDER']; ?>:</td>
                                <td><input class="uispin" name="f_order" type="text" id="f_order" value="<?php if(empty($field)) { echo $last_order; } else { echo cmsCore::getArrVal($field, 'ordering', ''); } ?>" size="6" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $_LANG['AD_FIELD_FILLING']; ?>:</td>
                                <td><select name="mustbe" id="mustbe">
                                        <option value="1" <?php if (cmsCore::getArrVal($field, 'mustbe')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_NECESSARILY']; ?></option>
                                        <option value="0" <?php if (!cmsCore::getArrVal($field, 'mustbe')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_NOT_NECESSARILY']; ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="text_field">
                                <td><?php echo $_LANG['AD_VALUE_LINK']; ?>?</td>
                                <td>
                                    <label><input name="text_is_link"
                                                  onclick="$('#text_link_prefix').show();"
                                                  type="radio" value="1" <?php if (cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'text_is_link')) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['YES']; ?></label>
                                    <label><input name="text_is_link"
                                                  onclick="$('#text_link_prefix').hide();"
                                                  type="radio" value="0" <?php if (!cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'text_is_link')) { echo 'checked="checked"'; } ?>/> <?php echo $_LANG['NO']; ?></label>
                                </td>
                            </tr>
                            <tr id="text_link_prefix" <?php if(!cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'text_is_link')) { echo 'style="display:none"'; } ?>>
                                <td><?php echo $_LANG['AD_LINK_PREFIX']; ?>:</td>
                                <td><input name="text_link_prefix" type="text" size="25" value="<?php echo (cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'text_link_prefix', '') ? cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'text_link_prefix', '') : '/users/hobby/'); ?>" /></td>
                            </tr>
                            <tr class="text_field">
                                <td><?php echo $_LANG['AD_MAXIMUM_LENGTH']; ?>:</td>
                                <td><input class="uispin" name="text_max" type="text" size="6" value="<?php echo (isset($field['config']['max']) ? $field['config']['max'] : 300) ?>" /> <?php echo $_LANG['AD_CHARACTERS']; ?> </td>
                            </tr>
                        </table>

                        <div id="kind_text">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_text_size" type="text" id="f_text_size" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'size', 160); ?>" size="6" />  <?php echo $_LANG['AD_PX'] ; ?> </td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_link" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_link_size" type="text" id="f_text_size" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'size', 160); ?>" size="6" />  <?php echo $_LANG['AD_PX'] ; ?> </td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_textarea" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_ta_size" type="text" id="f_ta_size" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'size', 160); ?>" size="6" /> <?php echo $_LANG['AD_PX'] ; ?> </td>
                                </tr>
                                <tr>
                                    <td><?php echo $_LANG['AD_STRINGS']; ?>:</td>
                                    <td><input class="uispin" name="f_ta_rows" type="text" id="f_ta_rows" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'rows', 5); ?>" size="6" /></td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_checkbox" style="display:none">
                            <div id="div" >
                                <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="100"><?php echo $_LANG['AD_MARK']; ?>:</td>
                                        <td><select name="f_checked" id="f_checked">
                                                <option value="1" <?php if (cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'checked')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_MARKED']; ?></option>
                                                <option value="0" <?php if (!cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'checked')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_NOT_MARKED']; ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div id="kind_radiogroup" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_ELEMENTS']; ?>:<br />
                                        <small><?php echo $_LANG['AD_THROUTH']; ?> "<b>/</b>"</small> </td>
                                    <td><textarea name="f_rg_list" cols="20" rows="5" id="f_rg_list"><?php echo $this->escape(cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'items', '')) ?></textarea></td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_list" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_ELEMENTS']; ?>:<br />
                                        <small><?php echo $_LANG['AD_THROUTH']; ?> "<b>/</b>"</small> </td>
                                    <td><textarea name="f_list_list" cols="20" rows="5" id="f_list_list"><?php echo $this->escape(cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'items', '')) ?></textarea></td>
                                </tr>
                                <tr>
                                    <td><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_list_size" type="text" id="f_ta_size" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'size', 160); ?>" size="6" /> <?php echo $_LANG['AD_PX']; ?> </td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_menu" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_ELEMENTS']; ?>:<br />
                                        <small><?php echo $_LANG['AD_THROUTH']; ?> "<b>/</b>"</small> </td>
                                    <td><textarea name="f_menu_list" cols="20" rows="5" id="f_menu_list"><?php echo $this->escape(cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'items', '')) ?></textarea></td>
                                </tr>
                                <tr>
                                    <td><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_menu_size" type="text" id="f_ta_size" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'size', 160); ?>" size="6" /> <?php echo $_LANG['AD_PX']; ?> </td>
                                </tr>
                            </table>
                        </div>
                        <div id="kind_file" style="display:none">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="100"><?php echo $_LANG['AD_EXT']; ?>:<br />
                                        <small><?php echo $_LANG['AD_EXT_HINT']; ?></small></td>
                                    <td><input name="f_file_ext" type="text" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'ext', ''); ?>" size="25" /></td>
                                </tr>
                                <tr>
                                    <td><?php echo $_LANG['AD_WIDTH']; ?>:</td>
                                    <td><input class="uispin" name="f_file_size" type="text" id="f_file_size" value="<?php echo cmsCore::getArrVal(cmsCore::getArrVal($field, 'config'), 'size', 160); ?>" size="6" /> <?php echo $_LANG['AD_PX']; ?> </td>
                                </tr>
                            </table>
                        </div>

                        <p>
                            <input type="submit" name="Submit" value="<?php if(empty($field)){  echo $_LANG['AD_FIELD_ADD']; } else { echo $_LANG['AD_FIELD_SAVE']; } ?>" />
                        </p>
                    </form>

                </td>
                <td width="440" valign="top">
                    <h4 style="border-bottom:solid 1px black;font-size: 14px; margin-bottom: 5px">
                        <b><?php echo $_LANG['AD_PREVIEV']; ?> </b>
                    </h4>
                    <?php echo $form_html; ?>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".uitabs").tabs({active: 1});
            });
        </script>
    </div>
<?php
    }
?>
</div>
</div>
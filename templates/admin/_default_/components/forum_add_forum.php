<?php if ($opt == 'add_forum') { ?>
    <h3><?php echo $_LANG['AD_FORUM_NEW']; ?></h3>
<?php } else { ?>
    <h3><?php echo $mod['title'] .' '. $ostatok; ?></h3>
<?php } ?>

<form action="index.php?view=components&do=config&link=forum" method="post" name="addform" id="addform" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_TITLE']; ?>:</label>
            <input type="text" class="form-control" name="title" size="30" value="<?php echo $this->escape(cmsCore::getArrVal($mod, 'title', '')); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_DESCR']; ?>:</label>
            <textarea class="form-control" name="description" cols="35" rows="2"><?php echo cmsCore::getArrVal($mod, 'description', ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_POST']; ?>?</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_PARENTS']; ?>:</label>
            <select id="parent_id" class="form-control" name="parent_id">
                <option value="<?php echo $rootid; ?>" <?php if ($mod['parent_id'] == $rootid || !isset($mod['parent_id'])) { echo 'selected'; }?>><?php echo $_LANG['AD_FORUM_SQUARE']; ?> </option>
                <?php echo $forums_opt; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_CATEGORY']; ?>:</label>
            <select id="category_id" class="form-control" name="category_id">
                <?php echo $forum_cats_opt; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_GROUP']; ?>:</label>
            <select id="showin" class="form-control" name="access_list[]" size="6" multiple="multiple" <?php if (!$mod['access_list']) { ?>disabled="disabled"<?php } ?>>
            <?php if ($groups) {
                foreach ($groups as $group) {
                    if (!$group['is_admin']) {
                        echo '<option value="'. $group['id'] .'"';
                        if ($access_list) {
                            if (in_array($group['id'], $access_list)) {
                                echo 'selected="selected"';
                            }
                        }
                        echo '>';
                        echo $group['title'] .'</option>';
                    }
                }

            } ?>
            </select>
            
            <label><input type="checkbox" id="is_access" name="is_access" onclick="checkAccesList()" value="1" <?php if (!$mod['access_list']) { ?>checked="checked"<?php } ?> /> <?php echo $_LANG['AD_ALL_GROUPS']; ?></label>
            
            <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?>.</div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_MODERATORS']; ?>:</label>
            <select id="users_list" class="form-control" name="users_list">
                <?php echo cmsUser::getUsersList(); ?>
            </select>
            <div>
                <a class="ajaxlink" href="javascript:" onclick="addModer()">
                    <?php echo $_LANG['AD_ADD_SELECTED']; ?>
                </a>
            </div>

            <select id="moder_list" class="form-control" name="moder_list[]" size="8" multiple>
                <?php if ($moder_list) { echo $moder_list; } ?>
            </select>  <div><a class="ajaxlink" href="javascript:" onclick="deleteModer()"><?php echo $_LANG['AD_DELETE_SELECTED']; ?></a></div>
            <div class="help-block"><?php echo $_LANG['AD_FORUM_HINT']; ?>.</div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_FORUM_ICON']; ?>:</label>
            <?php if ($mod['icon']) { ?>
                <div style="text-align:center;"><img src="/upload/forum/cat_icons/<?php echo $mod['icon']; ?>" border="0" /></div>
            <?php } ?>
            <input type="file" class="form-control" name="Filedata" />
            <div class="help-block"><?php echo $_LANG['AD_FORUM_ICON_HINT']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_COST_CREATING']; ?> (<?php echo $_LANG['BILLING_POINT10']; ?>):</label>
            <?php if ($is_billing) { ?>
                <input type="text" class="form-control" name="topic_cost" value="<?php echo $mod['topic_cost']; ?>" />
            <?php } else { ?>
                <?php echo $_LANG['AD_REGUIRED']; ?> &laquo;<a href="http://www.instantcms.ru/billing/about.html"><?php echo $_LANG['AD_BILLING_USERS']; ?></a>&raquo;
            <?php } ?>
            <div class="help-block">0 &mdash; <?php echo $_LANG['AD_COST_FREE']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_PAGETITLE']; ?>:</label>
            <textarea class="form-control" name="pagetitle" rows="2"><?php echo cmsCore::getArrVal($mod, 'pagetitle', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_PAGETITLE_HINT']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METAKEYS']; ?>:</label>
            <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::getArrVal($mod, 'meta_keys', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['SEO_METADESCR']; ?>:</label>
            <textarea class="form-control" name="meta_desc" rows="4"><?php echo cmsCore::getArrVal($mod, 'meta_desc', ''); ?></textarea>
            <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
        </div>
    </div>
    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&link=forum';" />
        
        <input type="hidden" name="opt" value="<?php if ($opt == 'add_forum') { echo 'submit_forum'; } else { echo 'update_forum'; } ?>" />
        <?php
        if ($opt == 'edit_forum') {
            echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />';
        }
        ?>
    </div>
</form>
<script type="text/javascript">
    $().ready(function() {
        $("#addform").submit(function() {
              $('#moder_list').each(function(){
                  $('#moder_list option').prop("selected", true);
              });
        });
    });
    function deleteModer(){
        $('#moder_list option:selected').each(function () {
            $(this).remove();
        });
    }
    function addModer(){
        $('#users_list option:selected').each(function () {
            $(this).appendTo('#moder_list');
        });
    }
    function checkAccesList(){
        if (document.addform.is_access.checked) {
            $('select#showin').prop('disabled', true);
        } else {
            $('select#showin').prop('disabled', false);
        }
    }
</script>
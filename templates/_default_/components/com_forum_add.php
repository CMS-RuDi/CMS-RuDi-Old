<h1 class="con_heading"><?php echo $pagetitle; ?></h1>

<form action="" method="POST" name="msgform" id="msgform" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
<input type="hidden" name="gosend" value="1" />
<table width="100%" cellpadding="0" cellspacing="0"><tr><td>

<?php if ($do == 'newthread') { ?>
    <input type="hidden" name="forum_id" value="<?php echo $forum['id']; ?>" />
    <div class="forum_postinfo">
        <table width="100%" cellpadding="5">
            <tr>
                <td width="100"><?php echo $_LANG['THREAD_TITLE']; ?>:</td>
                <td width=""><input type="text" name="title" class="text-input" id="title" style="width: 350px" value="<?php echo $this->escape($thread['title']); ?>" /></td>
            </tr>
            <tr>
                <td><?php echo $_LANG['THREAD_DESCRIPTION']; ?>:</td>
                <td width=""><input type="text" name="description" class="text-input" style="width: 350px" value="<?php echo $this->escape($thread['description']); ?>" /></td>
            </tr>
        </table>
    </div>
<?php } ?>

<div class="usr_msg_bbcodebox">
    <?php echo $bb_toolbar; ?>
</div>
<?php echo $smilies; ?>
<div class="cm_editor">
    <textarea id="message" class="ajax_autogrowarea" name="message" rows="15"><?php echo $post_content; ?></textarea>
</div>

<?php if ($cfg['fa_on'] && $is_allow_attach) { ?>
    <?php cmsCore::c('page')->addHeadJS('includes/jquery/multifile/jquery.multifile.js'); ?>
    
    <script type="text/javascript">
        $(function(){ $('#upfile').MultiFile({ max: '<?php echo $cfg['fa_max']; ?>', accept:'<?php echo $cfg['fa_ext']; ?>', max:3, STRING: { remove:LANG_CANCEL, selected:LANG_FILE_SELECTED, denied:LANG_FILE_DENIED, duplicate:LANG_FILE_DUPLICATE } }); });
    </script>
    
    <input type="hidden" name="fa_count" value="1"/>
    <div class="forum_fa">
        <div class="forum_fa_title"><a href="javascript:" onclick="$('#fa_entries').toggle();"><?php echo $_LANG['ATTACH_FILES']; ?></a></div>
            <div class="forum_fa_entries" id="fa_entries">
                <div class="forum_fa_desc">
                    <div><strong><?php echo $_LANG['MAX_SIZE_FILE']; ?>:</strong> <?php echo $cfg['fa_size']; ?> <?php echo $_LANG['KBITE']; ?>.</div>
                    <div><strong><?php echo $_LANG['MUST_FILE_TYPE']; ?>:</strong> <?php echo $cfg['fa_ext']; ?></div>
                    <div><strong><?php echo $_LANG['SELECT_FILES']; ?> <?php echo $cfg['fa_max']; ?>:</strong></div>
                </div>
                <input type="file" name="fa[]" id="upfile" size="30" />
            </div>
    </div>
<?php } ?>

<?php if ($do == 'newthread' || ($do == 'editpost' && $is_first_post)) { ?>
<div class="forum_fa">
  <div class="forum_fa_title"><?php if ($thread_poll) { ?><?php echo $_LANG['EDIT_POLL']; ?><?php } else { ?><a href="javascript:" onclick="$('#pa_entries').toggle();"><?php echo $_LANG['ATTACH_POLL']; ?></a><?php } ?></div>
  <div class="forum_fa_entries" id="pa_entries" <?php if ($thread_poll) { ?>style="display: block"<?php } ?>>
        <div class="forum_fa_title" style="margin-bottom:10px"><?php echo $_LANG['POLL_PARAMS']; ?></div>
        <div style="margin-bottom:10px"><table cellspacing="0" class="forum_fa_entry" cellpadding="5">
            <tr>
                <td><?php echo $_LANG['QUESTION']; ?>: </td>
                <td><input name="poll[title]" type="text" size="30" class="text-input" value="<?php echo $this->escape($thread_poll['title']); ?>" /></td>
            </tr>
            <tr>
                <td><?php echo $_LANG['COMMENT_FOR_POLL']; ?>: </td>
                <td><input name="poll[desc]" type="text" size="30" class="text-input" value="<?php echo $this->escape($thread_poll['description']); ?>" /></td>
            </tr>
            <tr>
                <td><?php echo $_LANG['LENGTH_POLL']; ?>: </td>
                <td><input name="poll[days]" type="text" size="4" class="text-input" value="<?php echo $thread_poll['days_left']; ?>" /> <?php echo $_LANG['DAYS']; ?></td>
            </tr>
            <tr>
                <td><?php echo $_LANG['SHOW_RESULT']; ?>: </td>
                <td>
                    <select name="poll[result]" class="text-input">
                        <option value="0" <?php if (!$thread_poll['options']['result']) { ?>selected="selected"<?php } ?>><?php echo $_LANG['FOR_ALL_EVER']; ?></option>
                        <option value="1" <?php if ($thread_poll['options']['result'] == 1) { ?>selected="selected"<?php } ?>><?php echo $_LANG['ONLY_VOTERS']; ?></option>
                        <option value="2" <?php if ($thread_poll['options']['result'] == 2) { ?>selected="selected"<?php } ?>><?php echo $_LANG['ONLY_END_POLL']; ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $_LANG['CHANGE_VOTE_USER']; ?>: </td>
                <td>
                    <select name="poll[change]" class="text-input">
                        <option value="0" <?php if (!$thread_poll['options']['change']) { ?>selected="selected"<?php } ?>><?php echo $_LANG['PROHIBITING']; ?></option>
                        <option value="1" <?php if ($thread_poll['options']['change']) { ?>selected="selected"<?php } ?>><?php echo $_LANG['ALLOWING']; ?></option>
                    </select>
                </td>
            </tr>
        </table></div>
        <div class="forum_fa_title" style="margin-bottom:10px"><?php echo $_LANG['OPTIONS_ANSWER']; ?></div>
        <?php for ($i=1;$i<=13;$i++) { ?>
            <?php if ($i < 5 || $thread_poll['answers_key'][$i]) { $style = 'display:block'; } else { $style = 'display:none'; } ?>
            <div id="pa_entry<?php echo $i; ?>" style="<?php echo $style; ?>">
                <table cellspacing="0" class="forum_fa_entry" cellpadding="5">
                    <tr>
                        <td width="90"><?php echo $_LANG['OPTION']; ?> №<?php echo $i; ?>: </td>
                        <td><input name="poll[answers][]" type="text" size="30" id="pa_entry_input<?php echo $i; ?>" class="text-input" value="<?php echo $this->escape($thread_poll['answers_key'][$i]); ?>" /></td>
                        <?php if ($i >= 4) { $ostyle = 'display:block'; } else { $ostyle = 'display:none'; } ?>
                        <td>
                            <div id="pa_entry_btn<?php echo $i; ?>" style="<?php echo $ostyle; ?>">
                            <?php if ($i < 12) { ?>
                            <a href="javascript:showPaEntry(<?php echo $i+1; ?>)" title="<?php echo $_LANG['ADD_OPTION']; ?>"><img src="/images/icons/plus.gif" border="0"/></a>
                            <?php } ?>
                            <?php if ($i > 2) { ?>
                            <a href="javascript:hidePaEntry(<?php echo $i; ?>)" title="<?php echo $_LANG['HIDE_OPTION']; ?>"><img src="/images/icons/minus.gif" border="0"/></a>
                            <?php } ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        <?php } ?>
</div></div>


<script type="text/javascript">
    function showPaEntry(id){
        $('#pa_entry'+id).fadeIn();
        $('#pa_entry_btn'+(id - 1)).hide();
        $('#pa_entry_input'+id).focus();
    }
    function hidePaEntry(id){
        $('#pa_entry'+id).hide();
        $('#pa_entry_btn'+(id - 1)).fadeIn();
        $('#pa_entry_input'+(id - 1)).focus();
        $('#pa_entry_input'+id).val('');
    }
</script>


<?php } ?>
<div style="margin-top:6px;">
    <input type="button" value="<?php echo $_LANG['SEND']; ?>" onclick="$(this).prop('disabled', true);$('#msgform').submit();" style="font-size:16px"/>
    <input type="button" value="<?php echo $_LANG['CANCEL']; ?>" style="font-size:16px" onclick="window.history.go(-1)"/>
    <?php if ($do == 'newpost' && ($is_admin || $is_moder || $thread['is_mythread'])) { ?>
        <label><input type="checkbox" name="fixed" value="1" /> <?php echo $_LANG['TOPIC_FIXED_LABEL']; ?></label>
    <?php } ?>
    <?php if (($do == 'newpost' && !$is_subscribed) || $do == 'newthread') { ?>
        <label><input name="subscribe" type="checkbox" value="1" /> <?php echo $_LANG['SUBSCRIBE_THREAD']; ?></label>
    <?php } ?>
</div>
</td>
</tr></table>
</form>

<script type="text/javascript">
    $(document).ready(function(){
    <?php if ($do == 'newthread') { ?>
        $('#title').focus();
    <?php } else { ?>
        $('#message').focus();
    <?php } ?>
    });
</script>
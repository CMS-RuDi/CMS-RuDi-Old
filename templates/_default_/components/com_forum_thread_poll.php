<table class="forum_poll_table" width="100%" cellspacing="2" cellpadding="7" border="0">
<tr>
    <td class="darkBlue-LightBlue" width="100%" colspan="2">
        <div class="forum_poll_title"><?php echo $thread_poll['title']; ?></div>
        <div class="forum_poll_desc"><?php echo $thread_poll['description']; ?></div>
    </td>
</tr>
<?php if (!$user_id && ($thread_poll['options']['result'] == 1 || ($thread_poll['options']['result'] == 2 && !$thread_poll['is_closed']))) { ?>
    <tr>
        <td class="forum_poll_data" width="100%" colspan="2">
            <?php echo $_LANG['GUESTS_NOT_VOTE']; ?>
        </td>
    </tr>
<?php } else { ?>
    <tr>
        <td class="forum_poll_data" width="30%" valign="top">
        <?php if (!$thread_poll['show_result'] && $thread['closed'] && $thread_poll['options']['result'] == 1 && !$thread_poll['is_user_vote']) { ?>
            <?php echo $_LANG['YOU_IS_NOT_VOTE_IN_CLOSED']; ?>
        <?php } else if (!$thread_poll['show_result'] && is_string($thread_poll['is_user_vote']) && $thread_poll['options']['result'] == 2 && !$thread_poll['is_closed'] && !$thread['closed']) { ?>
            <?php echo $_LANG['YOU_IS_VOTE']; ?>
        <?php } else if (!$thread_poll['show_result'] && !$thread_poll['is_user_vote'] && !$thread['closed'] && !$thread_poll['is_closed']) { ?>
            <form action="/forum/vote_poll" method="post" id="forum_poll_submit_form">
                <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
                <input type="hidden" name="poll_id" value="<?php echo $thread_poll['id']; ?>" />
                <input type="hidden" name="id" id="thread_id" value="<?php echo $thread['id']; ?>" />
                <table>
                <?php foreach($thread_poll['answers'] as $answer => $num) { ?>
                    <tr>
                      <td class="mod_poll_answer">
                          <label>
                              <input name="answer" type="radio" value="<?php echo $this->escape($answer); ?>" /> <?php echo $answer; ?>
                          </label>
                      </td>
                    </tr>
                 <?php } ?>
                 </table>
                 <div class="forum_poll_submit"><input id="forum_poll_submit" type="button" value="<?php echo $_LANG['VOTING']; ?>" onclick="threadPollSubmit();"></div>
             </form>
             <script type="text/javascript" src="/includes/jquery/jquery.form.js"></script>
            <script type="text/javascript">
                var LANG_ATTENTION = '<?php echo $_LANG['ATTENTION']; ?>';
            
                function threadPollSubmit(){
                    $('#forum_poll_submit').prop('disabled', true);
                    var options = {
                        success: loadForumPoll
                    };
                    $('#forum_poll_submit_form').ajaxSubmit(options);
                }
                function loadForumPoll(result, statusText, xhr, $form){
                    if(statusText == 'success'){
                        if(result.error == false){
                            thread_id = $('#thread_id').val();
                            $.post('/forum/viewpoll'+thread_id, { }, function(data){
                                $('#thread_poll').html(data);
                            });
                        } else {
                            core.alert(result.text, LANG_ATTENTION);
                            $('#forum_poll_submit').prop('disabled', false);
                        }
                    }

                }
            </script>
        <?php } else { ?>
            <?php foreach($thread_poll['answers'] as $num) { ?>
                <?php $percent = $num/$thread_poll['vote_count']*100; ?>
                <span class="forum_poll_gauge_title"><?php echo $answer; ?> (<?php echo $num; ?>)</span>
                <?php if ($percent > 0) { ?>
                    <table class="mod_poll_gauge" width="{$percent|ceil}%"><tr><td></td></tr></table>
                <?php } else { ?>
                    <table class="mod_poll_gauge" width="5"><tr><td></td></tr></table>
                <?php } ?>
            <?php } ?>
        <?php } ?>
        </td>
        <td width="" valign="top">
            <div class="forum_poll_param"><strong><?php echo $_LANG['TOTAL_VOTES']; ?>:</strong> <?php echo $thread_poll['vote_count']; ?></div>

            <?php if (!$thread_poll['is_closed'] && !$thread['closed']) { ?>
                <div class="forum_poll_param"><strong><?php echo $_LANG['END_DATE_POLL']; ?>:</strong> <?php echo $thread_poll['fenddate']; ?></div>
                <div class="forum_poll_param"><strong><?php echo $_LANG['RESULTS']; ?>:</strong> <?php echo $thread_poll['options']['result_text']; ?></div>
                <div class="forum_poll_param"><strong><?php echo $_LANG['ANSWER_CHANGING']; ?>:</strong> <?php echo $thread_poll['options']['change_text']; ?></div>

                <?php if (!$thread_poll['is_user_vote'] && !$thread_poll['options']['result']) { ?>
                    <?php if ($do == 'thread') { ?>
                        <div class="forum_poll_param"><a class="ajaxlink" href="javascript:" onclick="forum.loadForumPoll(<?php echo $thread['id']; ?>, 1);"><?php echo $_LANG['RESULT_POLL']; ?></a></div>
                    <?php } else if ($do == 'view_poll') { ?>
                        <div class="forum_poll_param"><a class="ajaxlink" href="javascript:" onclick="forum.loadForumPoll(<?php echo $thread['id']; ?>, 0);"><?php echo $_LANG['REMOVE_RESULT']; ?></a></div>
                    <?php } ?>
                <?php } ?>

                <?php if (is_string($thread_poll['is_user_vote']) && $thread_poll['options']['change']) { ?>
                    <div class="forum_poll_param"><a class="ajaxlink" href="javascript:" onclick="forum.revotePoll(<?php echo $thread['id']; ?>);"><?php echo $_LANG['CHANGE_VOTE']; ?></a></div>
                <?php } ?>

                <?php if ($is_admin || $is_moder) { ?>
                    <div class="forum_poll_param"><a class="ajaxlink" href="javascript:" onclick="forum.deletePoll(<?php echo $thread['id']; ?>, '<?php echo cmsUser::getCsrfToken(); ?>');"><?php echo $_LANG['DELETE_POLL']; ?></a></div>
                <?php } ?>

            <?php } else { ?>
                <div class="forum_poll_param" style="color:#660000"><strong><?php echo $_LANG['POLL_FINISHED']; ?></strong></div>
            <?php } ?>

            <?php if (is_string($thread_poll['is_user_vote'])) { ?>
                <div class="forum_poll_param"><strong><?php echo $_LANG['YOUR_ANSWER']; ?>:</strong> <?php echo $thread_poll['is_user_vote']; ?></div>
            <?php } ?>

            <?php if ($user_id && !$thread['closed']) { ?>
                <div class="forum_poll_param"><a href="/forum/reply<?php echo $thread['id']; ?>.html"><?php echo $_LANG['COMMENT_POLL']; ?></a></div>
            <?php } ?>

        </td>
    </tr>
<?php } ?>
</table>
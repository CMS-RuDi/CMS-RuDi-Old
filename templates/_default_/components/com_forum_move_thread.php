<form action="/forum/movethread<?php echo $thread['id']; ?>.html" method="POST" id="movethread_form">
    <input type="hidden" name="gomove" value="1" />
    <table border="0" cellpadding="5" width="100%">
        <tr>
            <td width="170px"><?php echo $_LANG['MOVE_THREAD_IN_FORUM']; ?>:</td>
            <td>
                <select name="forum_id" style="width:220px;">
                    <?php foreach($forums as $item) { ?>
                        <?php if ($item['cat_title'] != $last_cat_title) { ?>
                        <?php if ($last_cat_title) { ?></optgroup><?php } ?>
                        <optgroup label="<?php echo $this->escape($item['cat_title']); ?>">
                        <?php } ?>
                        <option value="<?php echo $item['id']; ?>" <?php if ($item['id'] == $thread['forum_id']) { ?> selected="selected" <?php } ?>><?php echo $item['title']; ?></option>
                        <?php if ($item['sub_forums']) { ?>
                            <?php foreach($item['sub_forums'] as $sub_forum) { ?>
                                <option value="<?php echo $sub_forum['id']; ?>" <?php if ($sub_forum['id'] == $thread['forum_id']) { ?> selected="selected" <?php } ?>>--- <?php echo $sub_forum['title']; ?></option>
                            <?php } ?>
                        <?php } ?>
                        <?php $last_cat_title = $item['cat_title']; ?>
                    <?php } ?>
                    </optgroup>
                </select>
            </td>
        </tr>
    </table>
</form>
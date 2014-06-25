<div class="con_heading"><?php echo $_LANG['SITE_AWARDS']; ?></div>
<?php if ($aws) { ?>
    <table width="100%" cellspacing="2" cellpadding="3" class="usr_aw_table">
    <?php foreach($aws as $aw) { ?>
        <tr>
            <td width="32" valign="top">
                <img class="usr_aw_img" src="/images/users/awards/<?php echo $aw['imageurl']; ?>" border="0"/>
            </td>
            <td width="30%" valign="top">
                <div class="usr_aw_title"><strong><?php echo $aw['title']; ?></strong></div>
                <div class="usr_aw_desc"><?php echo $aw['description']; ?></div>

                <table border="0" cellspacing="0" cellpadding="3" class="usr_aw_dettable">
                    <?php if ($aw['p_comment']) { ?>
                        <tr>
                            <td><img src="/images/autoawards/p_comment.gif" width="16" height="16" /></td>
                            <td>
                              <?php echo $aw['p_comment']; ?>
                             <?php echo $_LANG['COMMENTS']; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($aw['p_forum']) { ?>
                        <tr>
                            <td><img src="/images/autoawards/p_forum.gif" width="16" height="16" /></td>
                            <td>
                              <?php echo $aw['p_forum']; ?>
                             <?php echo $_LANG['MESS_IN_FORUM']; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($aw['p_content']) { ?>
                        <tr>
                            <td><img src="/images/autoawards/p_forum.gif" width="16" height="16" /></td>
                            <td>
                              <?php echo $aw['p_content']; ?>
                             <?php echo $_LANG['PUBLISHED_ARTICLES']; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($aw['p_blog']) { ?>
                        <tr>
                            <td><img src="/images/autoawards/p_blog.gif" width="16" height="16" /></td>
                            <td>
                              <?php echo $aw['p_blog']; ?>
                             <?php echo $_LANG['POSTS_IN_BLOG']; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($aw['p_karma']) { ?>
                        <tr>
                            <td><img src="/images/autoawards/p_karma.gif" width="16" height="16" /></td>
                            <td>
                              <?php echo $aw['p_karma']; ?>
                             <?php echo $_LANG['KARMA_POINTS']; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($aw['p_photo']) { ?>
                        <tr>
                            <td><img src="/images/autoawards/p_photo.gif" width="16" height="16" /></td>
                            <td>
                              <?php echo $aw['p_photo']; ?>
                             <?php echo $_LANG['PHOTOS_IN_ALBUMS']; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($aw['p_privphoto']) { ?>
                        <tr>
                            <td><img src="/images/autoawards/p_privphoto.gif" width="16" height="16" /></td>
                            <td>
                              <?php echo $aw['p_privphoto']; ?>
                             <?php echo $_LANG['PHOTOS_IN_PRIVATE_ALBUM']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
            <td valign="top" class="usr_aw_who">
                <div class="usr_aw_users"><strong><?php echo $_LANG['AWARD_HAVES']; ?>:</strong></div>
                <div class="usr_aw_userslist"><?php echo $aw['uhtml']; ?></div>
            </td>

        </tr>
    <?php } ?>
    </table>
<?php } else { ?>
    <p><?php echo $_LANG['NOT_AWARDS_ON_SITE']; ?></p>
<?php } ?>
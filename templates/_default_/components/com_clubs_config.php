<div class="con_heading">
    <a href="/clubs/<?php echo $club['id']; ?>"><?php echo $club['title']; ?></a> &rarr; <?php echo $_LANG['CONFIG']; ?>
</div>

<?php cmsCore::c('page')->addHeadJS('includes/jquery/tabs/jquery.ui.min.js'); ?>
<?php cmsCore::c('page')->addHeadCSS('includes/jquery/tabs/tabs.css'); ?>

<form name="configform" id="club_config_form" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
<div id="configtabs" style="margin-top:20px" class="uitabs">
    <ul id="tabs">
        <li><a href="#about"><span><?php echo $_LANG['CLUB_DESC']; ?></span></a></li>
        <li><a href="#moders"><span><?php echo $_LANG['MODERATORS']; ?></span></a></li>
        <li><a href="#members"><span><?php echo $_LANG['MEMBERS']; ?></span></a></li>
        <?php if ($club['enabled_photos'] || $club['enabled_blogs']) { ?>
        <li><a href="#limits"><span><?php echo $_LANG['LIMITS']; ?></span></a></li>
        <?php } ?>
        <?php if ($is_admin) { ?>
        <li><a href="#vip"><span>VIP</span></a></li>
        <?php } ?>
    </ul>

    <div id="about">
        <table width="100%" border="0" cellspacing="0" cellpadding="10" style="border-bottom:solid 1px silver;margin-bottom:20px">
            <tr>
                <td colspan="2">
                    <strong><?php echo $_LANG['CLUB_NAME']; ?>: </strong>
                </td>
                <td>
                    <input name="title"  type="text" style="width:270px;" value="<?php echo $this->escape($club['title']); ?>" />
                </td>
            </tr>
            <tr>
                <td width="48">
                    <div style="padding:2px; border: solid 1px silver">
                        <img src="/images/clubs/small/<?php echo $club['f_imageurl']; ?>" border="0" alt="<?php echo $this->escape($club['title']); ?>"/>
                    </div>
                </td>
                <td width="120">
                    <label><?php echo $_LANG['UPLOAD_LOGO']; ?>:</label>
                </td>
                <td>
                    <input name="picture" type="file" id="picture" style="width:270px;" />
                </td>
            </tr>
        </table>
        <?php cmsCore::insertEditor($params['description'], $club['description'], 350, '100%'); ?>
    </div>
    
    <div id="moders">
        <table width="500" border="0" cellspacing="0" cellpadding="10" id="multiuserscfg">
            <tr>
                <td colspan="3">
                    <div class="hint"><?php echo $_LANG['MODERATE_TEXT']; ?></div>
                </td>
            </tr>
            <tr>
                <td align="center" valign="top">
                    <p><strong><?php echo $_LANG['CLUB_MODERATORS']; ?>: </strong></p>
                    <select name="moderslist[]" size="10" multiple id="moderslist" style="width:200px">
                        <?php echo $moders_list; ?>
                    </select>
                </td>
                <td align="center">
                    <div><input name="moderator_add" type="button" id="moderator_add" value="&lt;&lt;"></div>
                    <div><input name="moderator_remove" type="button" id="moderator_remove" value="&gt;&gt;" style="margin-top:4px"></div>
                </td>
                <td align="center" valign="top">
                    <p><strong><?php echo $_LANG['MY_FRIENDS_AND_CLUB_USERS']; ?>:</strong></p>
                    <select name="userslist1" size="10" multiple id="userslist1" style="width:200px">
                        <?php echo $fr_members_list; ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    
    <div id="members">
        <table width="550" border="0" cellspacing="0" cellpadding="10">
            <tr>
                <td><?php echo $_LANG['MAX_MEMBERS']; ?>:<br/><span style="color:#5F98BF"><?php echo $_LANG['MAX_MEMBERS_TEXT']; ?></span> </td>
                <td><input name="maxsize" type="text" style="width:200px"  value="<?php echo $club['maxsize']; ?>"/></td>
            </tr>
            <tr>
                <td>
                    <label><?php echo $_LANG['SELECT_CLUB_TYPE']; ?>:</label>
                </td>
                <td width="200">
                    <select name="clubtype" id="clubtype" style="width:200px" onchange="$('#minkarma').toggle();">
                        <option value="public" <?php if ($club['clubtype'] == 'public') { ?>selected="selected"<?php } ?>><?php echo $_LANG['PUBLIC']; ?> (public)</option>
                        <option value="private" <?php if ($club['clubtype'] == 'private') { ?>selected="selected"<?php } ?>><?php echo $_LANG['PRIVATE']; ?> (private)</option>
                     </select>
                </td>
            </tr>
        </table>
        
        <table width="550" border="0" cellspacing="0" id="minkarma" cellpadding="10" <?php if ($club['clubtype'] != 'public') { ?>style="display:none;"<?php } ?>>
            <tr>
                <td><?php echo $_LANG['USE_LIMITS_KARMA']; ?>: <br/><span style="color:#5F98BF"><?php echo $_LANG['USE_LIMITS_KARMA_TEXT']; ?></span></td>
                <td valign="top">
                    <input name="join_karma_limit" type="radio" value="1" <?php if ($club['join_karma_limit']) { ?>checked<?php } ?>/> <?php echo $_LANG['YES']; ?>
                    <input name="join_karma_limit" type="radio" value="0" <?php if (!$club['join_karma_limit']) { ?>checked<?php } ?>/> <?php echo $_LANG['NO']; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $_LANG['LIMITS_KARMA']; ?>: <br/><span style="color:#5F98BF"><?php echo $_LANG['LIMITS_KARMA_TEXT']; ?></span>
                </td>
                <td width="200" valign="top">
                    &ge; <input name="join_min_karma" type="text" style="width:60px" value="<?php echo $club['join_min_karma']; ?>"/> <?php echo $_LANG['POINTS']; ?>
                </td>
            </tr>
        </table>
        
        <table width="550" border="0" cellspacing="0" cellpadding="10" id="members">
            <tr>
                <td align="center" valign="top">
                    <p><strong><?php echo $_LANG['CLUB_MEMBERS']; ?>: </strong></p>
                    <select name="memberslist[]" size="10" multiple id="memberslist" style="width:200px">
                        <?php echo $members_list; ?>
                    </select>
                </td>
                <td align="center">
                    <div><input name="member_add" type="button" id="member_add" value="&lt;&lt;"></div>
                    <div><input name="member_remove" type="button" id="member_remove" value="&gt;&gt;" style="margin-top:4px"></div>
                </td>
                <td align="center" valign="top">
                    <p><strong><?php echo $_LANG['MY_FRIENDS_ARE']; ?>:</strong></p>
                    <select name="userslist2" size="10" multiple id="userslist2" style="width:200px">
                        <?php echo $friends_list; ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>

    <?php if ($club['enabled_photos'] || $club['enabled_blogs']) { ?>
	<div id="limits">
            <table width="500" border="0" cellspacing="0" cellpadding="10">
                <?php if ($club['enabled_blogs']) { ?>
                    <tr>
                        <td>
                            <label><strong><?php echo $_LANG['PREMODER_POSTS_IN_BLOGS']; ?>:</strong></label>
                        </td>
                        <td width="150">
                            <input name="blog_premod" type="radio" value="1" <?php if ($club['blog_premod']) { ?>checked<?php } ?>/> <?php echo $_LANG['YES']; ?>
                            <input name="blog_premod" type="radio" value="0" <?php if (!$club['blog_premod']) { ?>checked<?php } ?>/> <?php echo $_LANG['NO']; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($club['enabled_photos']) { ?>
                    <tr>
                        <td>
                            <label><strong><?php echo $_LANG['PREMODER_PHOTOS']; ?>:</strong></label>
                        </td>
                        <td width="150">
                            <input name="photo_premod" type="radio" value="1" <?php if ($club['photo_premod']) { ?>checked<?php } ?>/> <?php echo $_LANG['YES']; ?>
                            <input name="photo_premod" type="radio" value="0" <?php if (!$club['photo_premod']) { ?>checked<?php } ?>/> <?php echo $_LANG['NO']; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($club['enabled_blogs']) { ?>
                    <tr>
                        <td>
                            <label><?php echo $_LANG['KARMA_LIMITS_FOR_NEW_POSTS']; ?>:</label>
                        </td>
                        <td width="150">&ge; <input name="blog_min_karma" type="text" style="width:60px" value="<?php echo $club['blog_min_karma']; ?>"/> <?php echo $_LANG['POINTS']; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($club['enabled_photos']) { ?>
                    <tr>
                        <td>
                            <label><?php echo $_LANG['KARMA_LIMITS_FOR_ADD_PHOTOS']; ?>:</label>
                        </td>
                        <td width="150">
                            &ge;
                            <input name="photo_min_karma" type="text" style="width:60px" value="<?php echo $club['photo_min_karma']; ?>"/> <?php echo $_LANG['POINTS']; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($club['enabled_photos']) { ?>
                    <tr>
                        <td>
                            <label><?php echo $_LANG['KARMA_LIMITS_NEW_PHOTOALBUM']; ?>:</label>
                        </td>
                        <td width="150">
                            &ge; <input name="album_min_karma" type="text" style="width:60px" value="<?php echo $club['album_min_karma']; ?>"/> <?php echo $_LANG['POINTS']; ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } ?>

    <?php if ($is_admin) { ?>
        <div id="vip">
            <?php if (!$is_billing) { ?>
                <p><?php echo $_LANG['VIP_BILLING_INFO']; ?></p>
                <p><?php echo $_LANG['VIP_BILLING_INFO1']; ?></p>
            <?php } else { ?>
                <table width="500" border="0" cellspacing="0" cellpadding="10">
                    <tr>
                        <td>
                            <label><strong><?php echo $_LANG['VIP_CLUB']; ?>:</strong></label>
                        </td>
                        <td width="150">
                            <input name="is_vip" type="radio" value="1" <?php if ($club['is_vip']) { ?>checked<?php } ?>/> <?php echo $_LANG['YES']; ?>
                            <input name="is_vip" type="radio" value="0" <?php if (!$club['is_vip']) { ?>checked<?php } ?>/> <?php echo $_LANG['NO']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php echo $_LANG['VIP_CLUB_JOIN_COST']; ?>:</label>
                        </td>
                        <td width="150">
                            <input name="join_cost" type="text" style="width:60px" value="<?php echo $club['join_cost']; ?>"/> <?php echo $_LANG['BILLING_POINT10']; ?>
                        </td>
                    </tr>
                </table>
            <?php } ?>
	</div>
    <?php } ?>
</div>

<div style="margin: 15px 0 0;">
    <input type="submit" class="button" name="save" value="<?php echo $_LANG['SAVE']; ?>"/>
    <input type="button" class="button" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.go(-1)"/>
</div>

</form>

<script type="text/javascript">
    $(".uitabs").tabs();
    $("#club_config_form").submit(function() {
        $('#moderslist').each(function(){
            $('#moderslist option').prop("selected", true);
        });
        $('#memberslist').each(function(){
            $('#memberslist option').prop("selected", true);
        });
    });
    $().ready(function() {
        $('#moderator_remove').click(function() {
            var user = new Array;
            $('#moderslist option:selected').each(function () {
                user.push(this);
            });
            
            while (user.length){
                opt     = user.pop();
                opt2    = $(opt).clone();
                $(opt).remove().appendTo('#userslist1');
                $(opt2).remove();
            }
        });
        $('#moderator_add').click(function() {
            var user_id = new Array;
            $('#userslist1 option:selected').each(function () {
                user_id.push(this.value);
            });

            $('#userslist1 option:selected').remove().appendTo('#moderslist');

            while (user_id.length){
                id = user_id.pop();
                $('#userslist2 option[value='+id+']').remove();
            }
        });

        $('#member_remove').click(function() {
            var user = new Array;
            $('#memberslist option:selected').each(function () {
                user.push(this);
            });

            var user_id = new Array;
            $('#memberslist option:selected').each(function () {
                user_id.push(this.value);
            });

            while (user.length){
                opt     = user.pop();
                opt2    = $(opt).clone();
                $(opt).remove().appendTo('#userslist1');
                $(opt2).remove().appendTo('#userslist2');
            }

            while (user_id.length){
                id = user_id.pop();
                $('#moderslist option[value='+id+']').remove();
            }
        });

        $('#member_add').click(function() {
            var user_id = new Array;
            $('#userslist2 option:selected').each(function () {
                user_id.push(this.value);
            });
            $('#userslist2 option:selected').remove().appendTo('#memberslist');
        });

        $("#addform").submit(function() {
            $('#moderslist').each(function(){
                $('#moderslist option').prop("selected", true);
            });
            $('#memberslist').each(function(){
                $('#memberslist option').prop("selected", true);
            });
        });
    });
</script>

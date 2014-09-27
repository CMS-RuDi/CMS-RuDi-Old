<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.4                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

    $opt = cmsCore::request('opt', 'str', 'list');

    if ($opt == 'list') {
        $toolmenu = array(
            array( 'icon' => 'newaward.gif', 'title' => $_LANG['AD_NEW_AWARD'], 'link' => '?view=components&do=config&id='. $id .'&opt=add' ),
            array( 'icon' => 'listawards.gif', 'title' => $_LANG['AD_ALL_AWARDS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list' ),
            array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=edit&multiple=1');" ),
            array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=show_award&multiple=1');" ),
            array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=hide_award&multiple=1');" )
        );
        
        cpToolMenu($toolmenu);
    }

    if ($opt == 'show_award') {
        if (!isset($_REQUEST['item'])) {
            if (isset($_REQUEST['item_id'])) {
                cmsCore::c('db')->setFlag('cms_user_autoawards', $_REQUEST['item_id'], 'published', '1');
            }
            cmsCore::halt('1');
        } else {
            cmsCore::c('db')->setFlags('cms_user_autoawards', $_REQUEST['item'], 'published', '1');
            cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
            cmsCore::redirectBack();
        }
    }

    if ($opt == 'hide_award') {
        if (!isset($_REQUEST['item'])) {
            if (isset($_REQUEST['item_id'])) {
                cmsCore::c('db')->setFlag('cms_user_autoawards', $_REQUEST['item_id'], 'published', '0');
            }
            cmsCore::halt('1');
        } else {
            cmsCore::c('db')->setFlags('cms_user_autoawards', $_REQUEST['item'], 'published', '0');
            cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
            cmsCore::redirectBack();
        }
    }

    if ($opt == 'submit' || $opt == 'update') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $title       = cmsCore::request('title', 'str', $_LANG['AD_AWARD']);
        $description = cmsCore::request('description', 'str', '');
        $published   = cmsCore::request('published', 'int', 0);
        $imageurl    = preg_replace('/[^a-zA-Z0-9_\.\-]/iu', '', cmsCore::request('imageurl', 'str', ''));
        $p_comment   = cmsCore::request('p_comment', 'int', 0);
        $p_forum     = cmsCore::request('p_forum', 'int', 0);
        $p_content   = cmsCore::request('p_content', 'int', 0);
        $p_blog      = cmsCore::request('p_blog', 'int', 0);
        $p_karma     = cmsCore::request('p_karma', 'int', 0);
        $p_photo     = cmsCore::request('p_photo', 'int', 0);
        $p_privphoto = cmsCore::request('p_privphoto', 'int', 0);

        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');

        if ($opt == 'submit') {
            $sql = "INSERT INTO cms_user_autoawards (title, description, imageurl, p_comment, p_blog, p_forum, p_photo, p_privphoto, p_content, p_karma, published)
                    VALUES ('$title', '$description', '$imageurl', $p_comment, $p_blog, $p_forum, $p_photo, $p_privphoto, $p_content, $p_karma, $published)";
            cmsCore::c('db')->query($sql);

            cmsCore::redirect('?view=components&do=config&opt=list&id='.$id);
        } else {
            $item_id = cmsCore::request('item_id', 'int', 0);

            $sql = "UPDATE cms_user_autoawards
                    SET title='$title',
                        description='$description',
                        imageurl='$imageurl',
                        p_comment=$p_comment,
                        p_blog=$p_blog,
                        p_forum=$p_forum,
                        p_photo=$p_photo,
                        p_privphoto=$p_privphoto,
                        p_content=$p_content,
                        p_karma=$p_karma,
                        published=$published
                    WHERE id = '$item_id'";

            cmsCore::c('db')->query($sql);

            if (!isset($_SESSION['editlist']) || @sizeof($_SESSION['editlist'])==0) {
                cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list');
            } else {
                cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=edit');
            }
        }
    }

    if ($opt == 'delete') {
        $item_id = cmsCore::request('item_id', 'int', 0);
        $sql = "DELETE FROM cms_user_autoawards WHERE id = $item_id";
        
        cmsCore::c('db')->query($sql);
        $sql = "DELETE FROM cms_user_awards WHERE award_id = $item_id";
        
        cmsCore::c('db')->query($sql);
        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
        cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list');
    }

    if ($opt == 'list') {
        $fields = array(
            array('title' => 'id', 'field' => 'id', 'width' => '40'),
            array('title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '250', 'filter' => 15, 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%'),
            array('title' => $_LANG['DESCRIPTION'], 'field' => 'description', 'width' => '', 'filter' => 15),
            array('title' => $_LANG['AD_GIVING'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_award'),
        );


        $actions = array(
            array('title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%'),
            array('title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=delete&item_id=%id%', 'confirm' => $_LANG['AD_CONFIRM_DELETING']),
        );

        cpListTable('cms_user_autoawards', $fields, $actions);
    }

    if ($opt == 'add' || $opt == 'edit') {
        if ($opt == 'add') {
            cpAddPathway($_LANG['AD_NEW_AWARD']);
            echo '<h3>'.$_LANG['AD_NEW_AWARD'].'</h3>';
            $mod = array();
        } else {
            if (isset($_REQUEST['multiple'])) {
                if (isset($_REQUEST['item'])) {
                    $_SESSION['editlist'] = cmsCore::request('item', 'array_int', array());
                } else {
                    cmsCore::addSessionMessage($_LANG['AD_NO_SELECT_OBJECTS'], 'error');
                    cmsCore::redirectBack();
                }
            }

            $ostatok = '';

            if (isset($_SESSION['editlist'])) {
                $item_id = array_shift($_SESSION['editlist']);
                if (sizeof($_SESSION['editlist']) == 0) {
                    unset($_SESSION['editlist']);
                } else {
                    $ostatok = '('.$_LANG['AD_NEXT_IN'].sizeof($_SESSION['editlist']).')';
                }
            } else {
                $item_id = cmsCore::request('item_id', 'int', 0);
            }

            $mod = cmsCore::c('db')->get_fields('cms_user_autoawards', "id = '$item_id'", '*');
            if (!$mod) { cmsCore::error404(); }

            echo '<h3>'. $mod['title'] .' '. $ostatok .'</h3>';
            cpAddPathway($mod['title']);
        }
?>
        <style type="text/css">
            #p_input{
                border:solid 1px silver;
                text-align:center;
                margin-left:4px;
                margin-right:6px;
            }
            #p_input:hover{
                border:solid 1px gray;
                background-color:#EBEBEB;
                text-align:center;
                margin-left:4px;
                margin-right:6px;
            }
            .input-group-addon{
                width: 240px;
            }
            .input-group-addon.fa{
                width: 40px;
            }
        </style>
        <form action="index.php?view=components&do=config&id=<?php echo $id;?>" method="post" enctype="multipart/form-data" name="addform" id="addform">
            <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
            
            <div style="width:600px;">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_AWARD_TITLE']; ?></label>
                    <input type="text" class="form-control" name="title" size="45" value="<?php echo cmsCore::getArrVal($mod, 'title', '');?>"/>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_AWARD_DESCRIPTION']; ?></label>
                    <textarea class="form-control" name="description" size="45"><?php echo cmsCore::getArrVal($mod, 'description', ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ENABLE_AWARD_CONFIRM']; ?></label>
                    <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                        <label class="btn btn-default active">
                            <input type="radio" name="published" <?php if (cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default ">
                            <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_DISALLOW_TEXT']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_AWARD_IMAGE']; ?></label>
                    <div>
                    <?php
                        $awards_img = cmsUser::getAwardsImages();
                        $imageurl = cmsCore::getArrVal($mod, 'imageurl', '');
                        foreach($awards_img as $img){
                    ?>
                        <div style="float:left;margin:4px;">
                            <label>
                                <img src="/images/users/awards/<?php echo $img; ?>" /><br/>
                                <input type="radio" name="imageurl" value="<?php echo $img; ?>" <?php if ($imageurl == $img) { echo 'checked="checked"'; } ?> />
                            </label>
                        </div>
                    <?php } ?>
                        <div style="clear:both;"></div>
                    </div>
                    <div class="help-block"><?php echo $_LANG['AD_AWARD_FOLDER']; ?></div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_AWARD_CONDITION_TITLE']; ?></label>
                    
                    <div class="input-group">
                        <span class="input-group-addon fa fa-comment-o"></span>
                        <input type="number" class="form-control" name="p_comment" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_comment', 0); ?>" />
                        <span class="input-group-addon"><?php echo $_LANG['COMMENT10']; ?></span>
                    </div>
                    
                    <div class="input-group">
                        <span class="input-group-addon fa fa-comments-o"></span>
                        <input type="number" class="form-control" name="p_forum" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_forum', 0); ?>" />
                        <span class="input-group-addon"><?php echo $_LANG['AD_FORUM_MESSAGES']; ?></span>
                    </div>
                    
                    <div class="input-group">
                        <span class="input-group-addon fa fa-file-text-o"></span>
                        <input type="number" class="form-control" name="p_content" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_content', 0); ?>" />
                        <span class="input-group-addon"><?php echo $_LANG['AD_PUBLISHED_ARTICLES']; ?></span>
                    </div>
                    
                    <div class="input-group">
                        <span class="input-group-addon fa fa-pencil"></span>
                        <input type="number" class="form-control" name="p_blog" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_blog', 0); ?>" />
                        <span class="input-group-addon"><?php echo $_LANG['AD_BLOG_POSTS']; ?></span>
                    </div>
                    
                    <div class="input-group">
                        <span class="input-group-addon fa fa-heart-o"></span>
                        <input type="number" class="form-control" name="p_karma" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_karma', 0); ?>" />
                        <span class="input-group-addon"><?php echo $_LANG['AD_KARMA_POINTS']; ?></span>
                    </div>
                    
                    <div class="input-group">
                        <span class="input-group-addon fa fa-picture-o"></span>
                        <input type="number" class="form-control" name="p_photo" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_photo', 0); ?>" />
                        <span class="input-group-addon"><?php echo $_LANG['AD_PUBLIC_PHOTOS']; ?></span>
                    </div>
                    
                    <div class="input-group">
                        <span class="input-group-addon fa fa-lock"></span>
                        <input type="number" class="form-control" name="p_privphoto" min="0" value="<?php echo cmsCore::getArrVal($mod, 'p_privphoto', 0); ?>" />
                        <span class="input-group-addon"><?php echo $_LANG['AD_PRIVATE_PHOTOS']; ?></span>
                    </div>

                    <div class="help-block"><?php echo $_LANG['AD_AWARD']; ?> <?php echo $_LANG['AD_AWARD_CONDITION_TEXT']; ?></div>
                </div>
            </div>

            <div>
                <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
                <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id;?>';" />
              
                <input type="hidden" name="opt" <?php if ($opt=='add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
                <?php if ($opt == 'edit') { echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />'; } ?>
            </div>
        </form>
<?php
    }
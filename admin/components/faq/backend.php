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

$opt = cmsCore::request('opt', 'str', 'list_items');

$toolmenu = array();
if ($opt != 'config') {
    $toolmenu = array(
        array( 'icon' => 'newstuff.gif', 'title' => $_LANG['AD_NEW_QUESTION'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_item' ),
        array( 'icon' => 'newfolder.gif', 'title' => $_LANG['AD_CREATE_CATEGORY'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_cat' ),
        array( 'icon' => 'liststuff.gif', 'title' => $_LANG['AD_QUESTIONS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_items' ),
        array( 'icon' => 'folders.gif', 'title' => $_LANG['AD_CAT_QUESTION'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_cats' ),
    );

    if ($opt == 'list_items') {
        $toolmenu[] = array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=edit_item&multiple=1');" );
        $toolmenu[] = array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=show_item&multiple=1');" );
        $toolmenu[] = array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=hide_item&multiple=1');" );
        $toolmenu[] = array( 'icon' => 'delete.gif', 'title' => $_LANG['AD_DELETE_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=delete_item&multiple=1');" );
    }
    
    $toolmenu[] = array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&id='. $id .'&opt=config' );
}
if ($opt == 'config'){
    $toolmenu = array(
        array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.optform.submit();' ),
        array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components&do=config&id='. $id )
    );
}

cpToolMenu($toolmenu);

$cfg = array_merge(
    array( 'guest_enabled' => 1, 'user_link' => 1, 'publish' => 0, 'is_comment' => 1 ),
    $inCore->loadComponentConfig('faq')
);

$inCore->loadModel('faq');
$model = new cms_model_faq();

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array();
    $cfg['guest_enabled'] = cmsCore::request('guest_enabled', 'int', 0);
    $cfg['user_link']     = cmsCore::request('user_link', 'int', 0);
    $cfg['publish']       = cmsCore::request('publish', 'int', 0);
    $cfg['is_comment']    = cmsCore::request('is_comment', 'int', 0);

    $inCore->saveComponentConfig('faq', $cfg);
    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);
?>

<form action="index.php?view=components&do=config&id=<?php echo (int)$_REQUEST['id'];?>&opt=config" method="post" name="optform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:650px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_QUEST_FROM_UNREG']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'guest_enabled', false)) { echo 'active'; } ?>">
                    <input type="radio" name="guest_enabled" <?php if(cmsCore::getArrVal($cfg, 'guest_enabled', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'guest_enabled', false)) { echo 'active'; } ?>">
                    <input type="radio" name="guest_enabled" <?php if (!cmsCore::getArrVal($cfg, 'guest_enabled', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SHOW_LINK_QUEST_MEM']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'user_link', false)) { echo 'active'; } ?>">
                    <input type="radio" name="user_link" <?php if(cmsCore::getArrVal($cfg, 'user_link', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'user_link', false)) { echo 'active'; } ?>">
                    <input type="radio" name="user_link" <?php if (!cmsCore::getArrVal($cfg, 'user_link', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_POST_QUEST_NO_MODERAT']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'publish', false)) { echo 'active'; } ?>">
                    <input type="radio" name="publish" <?php if(cmsCore::getArrVal($cfg, 'publish', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'publish', false)) { echo 'active'; } ?>">
                    <input type="radio" name="publish" <?php if (!cmsCore::getArrVal($cfg, 'publish', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_POST_QUEST_NO_MODERAT_HINT']; ?>.</div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALLOW_COMMENTS']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'is_comment', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comment" <?php if(cmsCore::getArrVal($cfg, 'is_comment', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'is_comment', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comment" <?php if (!cmsCore::getArrVal($cfg, 'is_comment', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
    </div>

    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';" />
    </div>
</form>

<?php }

if ($opt == 'show_item') {
    if (!cmsCore::inRequest('item')){
        if (cmsCore::inRequest('item_id')){
            cmsCore::c('db')->setFlag('cms_faq_quests', cmsCore::request('item_id', 'int', 0), 'published', '1');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_faq_quests', cmsCore::request('item', 'array_int'), 'published', '1');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_item'){
    if (!cmsCore::inRequest('item')){
        if (cmsCore::inRequest('item_id')){
            cmsCore::c('db')->setFlag('cms_faq_quests', cmsCore::request('item_id', 'int', 0), 'published', '0');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_faq_quests', cmsCore::request('item', 'array_int'), 'published', '0');
        cmsCore::redirectBack();
    }
}

if ($opt == 'submit_item') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    $category_id = cmsCore::request('category_id', 'int');
    $published = cmsCore::request('published', 'int', 0);
    $quest = cmsCore::request('quest', 'str', '');
    $answer = cmsCore::c('db')->escape_string(cmsCore::request('answer', 'str', ''));
    $answeruser_id = $_SESSION['user']['id'];
    $user_id = cmsCore::request('user_id', 'int');

    $pubdate = cmsCore::request('pubdate', 'str', '');
    $answerdate = cmsCore::request('answerdate', 'str', '');

    $date = explode('.', $pubdate);
    $pubdate = $date[2] .'-'. $date[1] .'-'. $date[0];
    $date = explode('.', $answerdate);
    $answerdate = $date[2] .'-'. $date[1] .'-'. $date[0];

    $sql = "INSERT INTO cms_faq_quests (category_id, pubdate, published, quest, answer, user_id, answeruser_id, answerdate)
            VALUES ('". $category_id ."', '". $pubdate ."', ". $published .", '". $quest ."', '". $answer ."', ". $user_id .", ". $answeruser_id .", '". $answerdate ."')";

    cmsCore::c('db')->query($sql);
    cmsCore::redirect('?view=components&do=config&opt=list_items&id='. $id);
}

if ($opt == 'update_item') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    if (cmsCore::inRequest('item_id')) {
        $item_id = cmsCore::request('item_id', 'int');
        
        $category_id = cmsCore::request('category_id', 'int');
        $published = cmsCore::request('published', 'int', 0);
        $quest = cmsCore::request('quest', 'str', '');
        $answer = cmsCore::c('db')->escape_string(cmsCore::request('answer', 'str', ''));
        $answeruser_id = $_SESSION['user']['id'];
        $user_id = cmsCore::request('user_id', 'int');

        $pubdate = cmsCore::request('pubdate', 'str', '');
        $answerdate = cmsCore::request('answerdate', 'str', '');

        $date = explode('.', $pubdate);
        $pubdate = $date[2] .'-'. $date[1] .'-'. $date[0];
        $date = explode('.', $answerdate);
        $answerdate = $date[2] .'-'. $date[1] .'-'. $date[0];

        $sql = "UPDATE cms_faq_quests
                SET category_id = ". $category_id .",
                    quest='". $quest ."',
                    answer='". $answer ."',
                    user_id='". $user_id ."',
                    published=". $published .",
                    answeruser_id=". $answeruser_id .",
                    pubdate='". $pubdate ."',
                    answerdate='". $answerdate ."'
                WHERE id = ". $item_id ."
                LIMIT 1";
        cmsCore::c('db')->query($sql);
    }

    if (!isset($_SESSION['editlist']) || count($_SESSION['editlist']) == 0) {
        cmsCore::redirect('?view=components&do=config&opt=list_items&id='. $id);
    } else {
        cmsCore::redirect('?view=components&do=config&opt=edit_item&id='. $id);
    }
}

if ($opt == 'delete_item') {
    if (!cmsCore::inRequest('item')){
        if (cmsCore::inRequest('item_id')){
            $model->deleteQuest(cmsCore::request('item_id', 'int', 0));
        }
    } else {
        $model->deleteQuests(cmsCore::request('item', 'array_int'));
    }
    cmsCore::redirect('?view=components&do=config&opt=list_items&id='. $id);
}

if ($opt == 'show_cat') {
    if (cmsCore::inRequest('item_id')) {
        $sql = "UPDATE cms_faq_cats SET published = 1 WHERE id = ". cmsCore::request('item_id', 'int', 0);
        cmsCore::c('db')->query($sql) ;
        cmsCore::halt('1');
    }
}

if ($opt == 'hide_cat') {
    if (cmsCore::inRequest('item_id')) {
        $sql = "UPDATE cms_faq_cats SET published = 0 WHERE id = ". cmsCore::request('item_id', 'int', 0);
        cmsCore::c('db')->query($sql) ;
        cmsCore::halt('1');
    }
}

if ($opt == 'submit_cat') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    $parent_id = cmsCore::request('parent_id', 'int');
    $title = cmsCore::request('title', 'str', '');
    $published = cmsCore::request('published', 'int', 0);
    $description = cmsCore::request('description', 'str', '');

    $sql = "INSERT INTO cms_faq_cats (parent_id, title, published, description)
            VALUES (". $parent_id .", '". $title ."', ". $published .", '". $description ."')";
    cmsCore::c('db')->query($sql);
    cmsCore::redirect('?view=components&do=config&opt=list_cats&id='. $id);
}

if ($opt == 'delete_cat') {
    $cat_id = cmsCore::request('item_id', 'int', 0);
    if (!empty($cat_id)) {
        $sql = "DELETE FROM cms_faq_quests WHERE category_id = ". $cat_id;
        cmsCore::c('db')->query($sql) ;

        $sql = "DELETE FROM cms_faq_cats WHERE id = ". $cat_id ." LIMIT 1";
        cmsCore::c('db')->query($sql) ;
    }
    cmsCore::redirect('?view=components&do=config&opt=list_cats&id='. $id);
}

if ($opt == 'update_cat') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
    
    $cat_id = cmsCore::request('item_id', 'int', 0);
    if (!empty($cat_id)) {
        $parent_id = cmsCore::request('parent_id', 'int');
        $title = cmsCore::request('title', 'str', '');
        $published = cmsCore::request('published', 'int', 0);
        $description = cmsCore::request('description', 'str', '');

        $sql = "UPDATE cms_faq_cats
                SET title='". $title ."',
                    parent_id = ". $parent_id .",
                    description='". $description ."',
                    published=". $published ."
                WHERE id = ". $cat_id ."
                LIMIT 1";
        cmsCore::c('db')->query($sql) ;

        cmsCore::redirect('?view=components&do=config&opt=list_cats&id='. $id);
    }
}

if ($opt == 'list_cats') {
    cpAddPathway($_LANG['AD_CAT_QUESTION']);
    echo '<h3>'. $_LANG['AD_CAT_QUESTION'] .'</h3>';

    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'filter' => 20, 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%' ),
        array( 'title' => $_LANG['AD_ALLOW_PARENT'], 'field' => 'parent_id', 'width' => '300', 'filter' => 1, 'prc' => 'cpFaqCatById', 'filterlist' => cpGetList('cms_faq_cats') ),
        array( 'title' => $_LANG['AD_SHOW'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_cat' ),
    );

    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%' ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=delete_cat&item_id=%id%', 'confirm' => $_LANG['AD_DEL_CATEGORY_QUESTION'] ),
    );

    cpListTable('cms_faq_cats', $fields, $actions);
}

if ($opt == 'list_items') {
    echo '<h3>'. $_LANG['AD_QUESTIONS'] .'</h3>';

    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['AD_QUESTION'], 'field' => 'quest', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_item&item_id=%id%', 'filter' => 15, 'maxlen' => 80 ),
        array( 'title' => $_LANG['AD_CATEGORY'], 'field' => 'category_id', 'width' => '300', 'prc' => 'cpFaqCatById', 'filter' => 1, 'filterlist' => cpGetList('cms_faq_cats') ),
        array( 'title' => $_LANG['AD_SHOW'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_item' ),
    );

    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_item&item_id=%id%' ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=delete_item&item_id=%id%', 'confirm' => $_LANG['AD_REMOVE_QUESTION'] ),
    );

    cpListTable('cms_faq_quests', $fields, $actions, '', 'pubdate DESC');
}

if ($opt == 'add_item' || $opt == 'edit_item') {
    if ($opt == 'add_item') {
        echo '<h3>'. $_LANG['AD_ADD_QUESTION'] .'</h3>';
        cpAddPathway($_LANG['AD_ADD_QUESTION']);
        $mod = array();
    } else {
        if (cmsCore::inRequest('multiple')){
           if (cmsCore::inRequest('item')){
               $_SESSION['editlist'] = cmsCore::request('item', 'array_int');
           } else {
               echo '<p class="error">'. $_LANG['AD_NO_SELECT_OBJECTS'] .'</p>';
               return;
           }
        }

        $ostatok = '';

        if (isset($_SESSION['editlist'])) {
           $item_id = array_shift($_SESSION['editlist']);
           if (sizeof($_SESSION['editlist']) == 0) {
               unset($_SESSION['editlist']);
           } else {
               $ostatok = '('. $_LANG['AD_NEXT_IN'] .' '. sizeof($_SESSION['editlist']) .')';
           }
        } else {
            $item_id = cmsCore::request('item_id', 'int');
        }


        $sql = "SELECT *, DATE_FORMAT(pubdate, '%d.%m.%Y') as pubdate, DATE_FORMAT(answerdate, '%d.%m.%Y') as answerdate
                FROM cms_faq_quests
                WHERE id = ". $item_id ." LIMIT 1";
        $result = cmsCore::c('db')->query($sql) ;
        if (cmsCore::c('db')->num_rows($result)) {
           $mod = cmsCore::c('db')->fetch_assoc($result);
        } else {
            cmsCore::error404();
        }

        echo '<h3>'. $_LANG['AD_VIEW_QUESTION'] .'</h3>';
        cpAddPathway($_LANG['AD_VIEW_QUESTION']);
    }

    ?>
<form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="addform" id="addform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_CAT_QUESTION']; ?>:</label>
            <select class="form-control" name="category_id">
                <?php
                    echo $inCore->getListItems('cms_faq_cats', cmsCore::getArrVal($mod, 'category_id', cmsCore::request('addto', 'int', 0)));
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ASKER']; ?>:</label>
            <select class="form-control" name="user_id">
                <option value="0" <?php if (!$mod['user_id']) { echo 'selected="selected"'; } ?>>-- <?php echo $_LANG['AD_ANONYMOUS']; ?> --</option>
                <?php
                    echo $inCore->getListItems('cms_users', cmsCore::getArrVal($mod, 'user_id', cmsCore::c('user')->id), 'nickname', 'ASC', 'is_deleted=0 AND is_locked=0', 'id', 'nickname');
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_POST_QUESTION']; ?>:</label>
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
            <label class="col-sm-5" style="padding-left:0;"><?php echo $_LANG['AD_DATE_QUESTION']; ?>:</label>
            <div class="col-sm-7">
                <input type="text" id="pubdate" class="form-control" style="display:inline-block;width:auto;" name="pubdate" value="<?php if (!cmsCore::getArrVal($mod, 'pubdate', false)) { echo date('d.m.Y'); } else { echo $mod['pubdate']; } ?>" />
                <input type="hidden" name="oldpubdate" value="<?php echo cmsCore::getArrVal($mod, 'pubdate', ''); ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-5" style="padding-left:0;"><?php echo $_LANG['AD_DATE_REPLY']; ?>:</label>
            <div class="col-sm-7">
                <input type="text" id="answerdate" class="form-control" style="display:inline-block;width:auto;" name="answerdate" value="<?php if (!cmsCore::getArrVal($mod, 'answerdate', false)) { echo date('d.m.Y'); } else { echo $mod['answerdate']; } ?>" />
                <input type="hidden" name="oldanswerdate" value="<?php echo cmsCore::getArrVal($mod, 'answerdate', ''); ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_TEXT_QUESTION']; ?>:</label>
            <textarea id="quest" class="form-control" name="quest" rows="6"><?php echo cmsCore::getArrVal($mod, 'quest', ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ANSWER_QUESTION']; ?>:</label>
            <?php $inCore->insertEditor('answer', $mod['answer'], '300', '605'); ?>
        </div>
    </div>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back2" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';" />
        
        <input type="hidden" id="do" name="opt" value="<?php if ($opt == 'add_item') { echo 'submit_item'; } else { echo 'update_item'; } ?>" />
        <?php
            if ($opt == 'edit_item') {
                echo '<input type="hidden" name="item_id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
<?php
}

if ($opt == 'add_cat' || $opt == 'edit_cat') {
    if ($opt == 'add_cat') {
        echo '<h3>'. $_LANG['AD_CREATE_CATEGORY'] .'</h3>';
        cpAddPathway($_LANG['AD_CREATE_CATEGORY']);
        $mod = array();
    } else {
        if (cmsCore::inRequest('item_id')) {
            $item_id = cmsCore::request('item_id', 'int', 0);
            $sql = "SELECT * FROM cms_faq_cats WHERE id = ". $item_id ." LIMIT 1";
            $result = cmsCore::c('db')->query($sql);
            if (cmsCore::c('db')->num_rows($result)) {
                $mod = cmsCore::c('db')->fetch_assoc($result);
            } else {
                cmsCore::error404();
            }
        }

        echo '<h3>'. $_LANG['AD_CATEGORY'] .': '. $mod['title'] .'</h3>';
        cpAddPathway($_LANG['AD_CAT_QUESTION'], '?view=components&do=config&id='. $id .'&opt=list_cats');
        cpAddPathway($mod['title']);
    }
?>
<form id="addform" name="addform" method="post" action="index.php?view=components&amp;do=config&amp;id=<?php echo (int)$_REQUEST['id'];?>">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_NAME_CATEGORY']; ?>:</label>
            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', '')); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_PARENT_CATEGORY']; ?>:</label>
            <select class="form-control" name="parent_id">
                <option value="0" <?php if (!isset($mod['parent_id'])||@$mod['parent_id']==0){ echo 'selected'; } ?>>--</option>
                <?php echo $inCore->getListItems('cms_faq_cats', cmsCore::getArrVal($mod, 'parent_id', 0)); ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_POST_CATEGORY']; ?>:</label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if(cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                    <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
<?php
        if (cmsCore::getArrVal($mod, 'user', 1) == 1) {
?>
        <div class="form-group">
            <label><?php echo $_LANG['AD_DESCR_CATEGORY']; ?>:</label>
            <?php echo $inCore->insertEditor('description', $mod['description'], '260', '605'); ?>
        </div>
<?php
        }
?>
    </div>
    
    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&do=config&id=<?php echo $id; ?>';" />
        
        <input type="hidden" id="do" name="opt" value="<?php if ($opt=='add_cat') { echo 'submit_cat'; } else { echo 'update_cat'; } ?>" />
        <?php
            if ($opt == 'edit_cat') {
                echo '<input type="hidden" name="item_id" value="'. $mod['id'] .'" />';
            }
        ?>
    </div>
</form>
<?php
}
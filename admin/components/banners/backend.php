<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.5                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

function bannerCTRbyID($item) {
    if ($item['hits'] > 0) {
        $ctr = round((($item['clicks']/$item['hits']) * 100), 2);
    } else {
        $ctr = 0;
    }
    return $ctr .'%';
}

function bannerHitsbyID($item) {
    if (!$item['maxhits']) { return $item['hits']; } else { return $item['hits'] .'/'. $item['maxhits']; }
}

/******************************************************************************/

$opt = cmsCore::request('opt', 'str', 'list');

cmsCore::loadModel('banners');

if ($opt == 'list') {
    $toolmenu = array(
        array( 'icon' => 'new.gif', 'title' => $_LANG['AD_ADD_BANNER'], 'link' => '?view=components&do=config&id='. $id .'&opt=add'),
        array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=edit&multiple=1');"),
        array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=show_banner&multiple=1');"),
        array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=hide_banner&multiple=1');")
    );
    
    cpToolMenu($toolmenu);
}

if ($opt == 'show_banner') {
    if (!isset($_REQUEST['item'])) {
        if (isset($_REQUEST['item_id'])) { cmsCore::c('db')->setFlag('cms_banners', $_REQUEST['item_id'], 'published', '1'); }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_banners', $_REQUEST['item'], 'published', '1');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_banner') {
    if (!isset($_REQUEST['item'])) {
        if (isset($_REQUEST['item_id'])) { cmsCore::c('db')->setFlag('cms_banners', $_REQUEST['item_id'], 'published', '0'); }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_banners', $_REQUEST['item'], 'published', '0');
        cmsCore::redirectBack();
    }
}

if ($opt == 'submit' || $opt == 'update') {
    if(!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item_id = cmsCore::request('item_id', 'int', 0);

    $title   = cmsCore::request('title', 'str', $_LANG['AD_UNTITLED_BANNER']);
    $link    = cmsCore::request('b_link', 'str');
    $typeimg = cmsCore::request('typeimg', 'str');
    $maxhits = cmsCore::request('maxhits', 'int');
    $maxuser = 0;
    $published = cmsCore::request('published', 'int', 0);
    $position  = cmsCore::request('position', 'str');

    if (!empty($_FILES['picture']['size'])) {
        $ext = mb_strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, array('jpg','jpeg','gif','png','swf'))) {
            cmsCore::addSessionMessage($_LANG['AD_INCORRECT_FILE_TYPE'], 'error');
            cmsCore::redirectBack();
        }

        $filename   = md5(microtime()).'.'.$ext;
        $uploaddir  = PATH .'/images/banners/';
        $uploadfile = $uploaddir . $filename;

        if (cmsCore::moveUploadedFile($_FILES['picture']['tmp_name'], $uploadfile, $_FILES['picture']['error'])) {
            if ($opt == 'submit') {
                $sql = "INSERT INTO cms_banners (position, typeimg, fileurl, hits, clicks, maxhits, maxuser, user_id, pubdate, title, link, published)
                        VALUES ('$position', '$typeimg', '$filename', 0, 0, '$maxhits', '$maxuser', 1, NOW(), '$title', '$link', '$published')";
                cmsCore::c('db')->query($sql);

                cmsCore::redirect('?view=components&do=config&opt=list&id='.$id);

            } else {
                $fileurl = cmsCore::c('db')->get_field('cms_banners', "id = '$item_id'", 'fileurl');
                unlink($uploaddir.$fileurl);

                $sql = "UPDATE cms_banners SET fileurl = '$filename' WHERE id = '$item_id'";
                cmsCore::c('db')->query($sql) ;
            }

        } else {
            cmsCore::addSessionMessage($_LANG['AD_ADDING_ERROR_TEXT'].cmsCore::uploadError(), 'error');
            cmsCore::redirectBack();
        }

    } else if($opt == 'submit') {
        cmsCore::addSessionMessage($_LANG['AD_ADDING_ERROR_TEXT'], 'error');
        cmsCore::redirectBack();
    }

    if ($opt == 'update'){
        $sql = "UPDATE cms_banners
                SET position = '$position',
                    title = '$title',
                    published = '$published',
                    maxhits = '$maxhits',
                    maxuser = '$maxuser',
                    typeimg = '$typeimg',
                    link = '$link'
                WHERE id = '$item_id'";

        cmsCore::c('db')->query($sql);

        if (count(cmsCore::getArrVal($_SESSION, 'editlist', array())) == 0) {
            cmsCore::redirect('?view=components&do=config&opt=list&id='. $id);
        } else {
            cmsCore::redirect('?view=components&do=config&opt=edit&id='. $id);
        }

    }

}

if ($opt == 'delete') {
    $item_id = cmsCore::request('item_id', 'int', 0);

    $fileurl = cmsCore::c('db')->get_field('cms_banners', "id = '$item_id'", 'fileurl');
    if (!$fileurl) { cmsCore::error404(); }
    @unlink($uploaddir.$fileurl);

    cmsCore::c('db')->query("DELETE FROM cms_banners WHERE id = '$item_id'");
    cmsCore::c('db')->query("DELETE FROM cms_banner_hits WHERE banner_id = '$item_id'");

    cmsCore::redirectBack();
}

if ($opt == 'list') {
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['DATE'], 'field' => 'pubdate', 'width' => '100', 'filter' => 15, 'fdate' => '%d/%m/%Y' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'filter' => 15, 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%' ),
        array( 'title' => $_LANG['AD_POSITION'], 'field' => 'position', 'width' => '100', 'filter' => 15 ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_banner' ),
        array( 'title' => $_LANG['AD_BANNER_HITS'], 'field' => array('maxhits','hits'), 'width' => '90', 'prc' => 'bannerHitsbyID' ),
        array( 'title' => $_LANG['AD_BANNER_CLICKS'], 'field' => 'clicks', 'width' => '90' ),
        array( 'title' => $_LANG['AD_BANNER_CTR'], 'field' => array('clicks', 'hits'), 'width' => '90', 'prc' => 'bannerCTRbyID' )
    );

    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&item_id=%id%'),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_BANNER_DEL_CONFIRM'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete&item_id=%id%')
    );

    cpListTable('cms_banners', $fields, $actions, '', 'pubdate DESC');
}

if ($opt == 'add' || $opt == 'edit') {
    if ($opt == 'add') {
        echo '<h3>'. $_LANG['AD_ADD_BANNER'] .'</h3>';
        cpAddPathway($_LANG['AD_ADD_BANNER']);
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
           if (sizeof($_SESSION['editlist']) == 0) { unset($_SESSION['editlist']); } else
           { $ostatok = '('.$_LANG['AD_NEXT_IN'].sizeof($_SESSION['editlist']).')'; }
        } else { $item_id = cmsCore::request('item_id', 'int', 0); }

        $mod = cms_model_banners::getBanner($item_id);
        if (!$mod) { cmsCore::error404(); }

        echo '<h3>'. $mod['title'] .' '. $ostatok .'</h3>';
        cpAddPathway($mod['title']);
    }
    ?>
    <?php if ($opt == 'edit') { ?>
        <div style="width:550px;text-align:center;">
            <?php echo cms_model_banners::getBannerById($item_id); ?>
        </div>
    <?php } ?>

    <form action="index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="addform" id="addform">
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
        
        <div style="width:550px;">
            <div class="form-group">
                <label><?php echo $_LANG['AD_BANNER_TITLE']; ?></label>
                <input type="text" class="form-control" name="title" size="45" value="<?php echo cmsCore::getArrVal($mod, 'title', ''); ?>" />
                <div class="help-block"><?php echo $_LANG['AD_BANNER_DISPLAYED']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_BANNER_LINK']; ?></label>
                <input type="text" class="form-control" name="b_link" size="45" value="<?php echo cmsCore::getArrVal($mod, 'link', ''); ?>" />
                <div class="help-block"><?php echo $_LANG['AD_BANNER_REMINDER']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_POSITION']; ?></label>
                <select class="form-control" name="position">
                    <?php for($m=1;$m<=30;$m++){ ?>
                        <option value="banner<?php echo $m; ?>" <?php if(cmsCore::getArrVal($mod, 'position', '') == 'banner'. $m) { echo 'selected'; } ?>>banner<?php echo $m; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_BANNER_TYPE']; ?></label>
                <select class="form-control" name="typeimg">
                    <option value="image" <?php if(cmsCore::getArrVal($mod, 'typeimg', '') == 'image') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BANNER_IMAGE']; ?></option>
                    <option value="swf" <?php if(cmsCore::getArrVal($mod, 'typeimg', '') == 'swf') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BANNER_FLASH']; ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_BANNER_FILE']; ?></label>
                <?php if (cmsCore::getArrVal($mod, 'file', '')) {
                    echo '<a href="/images/photos/'. $mod['file'] .'" title="'. $_LANG['AD_BANNER_VIEW_PHOTO'] .'">'. $mod['file'] .'</a>';
                } else { ?>
                    <input name="picture" type="file" id="picture" size="30" />
                <?php } ?>
                <div class="help-block"><?php echo $_LANG['AD_BANNER_FILE_TYPES']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_BANNER_MAX_HITS']; ?> (<?php echo $_LANG['AD_HITS_LIMIT']; ?>)</label>
                <input type="number" class="form-control" name="maxhits" min="0" value="<?php echo cmsCore::getArrVal($mod, 'maxhits', ''); ?>" /> 
                <div class="help-block"><?php echo $_LANG['AD_UNLIMITED_HITS']; ?></div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_BANNER_PUBLISH']; ?></label>
                <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                    <label class="btn btn-default <?php if (cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                        <input type="radio" name="published" <?php if (cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'active'; } ?>">
                        <input type="radio" name="published" <?php if (!cmsCore::getArrVal($mod, 'published', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="help-block"><strong><?php echo $_LANG['AD_NOTE']; ?></strong> <?php echo $_LANG['AD_BANNER_NOTE']; ?></div>
        </div>
        
        <div>
            <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
            <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components&amp;do=config&amp;id=<?php echo $id; ?>';"/>
            
            <input name="opt" type="hidden" id="opt" <?php if ($opt == 'add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
            <?php
            if ($opt == 'edit') {
                echo '<input name="item_id" type="hidden" value="'. $mod['id'] .'" />';
            }
            ?>
        </div>
    </form>
 <?php
}
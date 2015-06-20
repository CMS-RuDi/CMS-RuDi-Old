<?php
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

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function iconList() {
    global $_LANG;
    if ($handle = opendir(PATH .'/images/menuicons')) {
        $n = 0;
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..'){
                $ext = explode('.', $file);
                if (!in_array($ext[count($ext)-1], array('gif', 'png', 'ico', 'jpg', 'jpeg'))) {
                    continue;
                }
                
                $dir = '/images/menuicons/';
                echo '<a style="width:20px;height:20px;display:block; float:left; padding:2px" href="javascript:selectIcon(\''. $file .'\')"><img alt="'. $file .'"src="'. $dir . $file.'" border="0" /></a>';
                $n++;
            }
        }
        closedir($handle);
    }

    if (!$n) { echo '<p>'. $_LANG['AD_EMPTY_FOLDER'] .'</p>'; }

    echo '<div align="right" style="clear:both">[<a href="javascript:selectIcon(\'\')">'. $_LANG['AD_NO_ICON'] .'</a>] [<a href="javascript:hideIcons()">'. $_LANG['CLOSE'] .'</a>]</div>';

    return;
}

function list_menu($menu) {
    $m = cmsCore::yamlToArray($menu);
    return implode(', ', $m);
}

function cpMenutypeById($item) {
    global $_LANG;

    $html   = '';
    $maxlen = 35;

    switch ($item['linktype']) {
        case 'link':
            $html = '<span id="menutype"><a target="_blank" href="'. $item['link'] .'">'. $_LANG['AD_TYPE_LINK'] .'</a></span> - '. $item['linkid'];
            break;
        case 'component':
            $html = '<span id="menutype"><a target="_blank" href="'. $item['link'] .'">'. $_LANG['AD_TYPE_COMPONENT'] .'</a></span> - '. cmsCore::c('db')->get_field('cms_components', "link='". $item['linkid'] ."'", 'title');
            break;
        case 'content':
            $html = '<span id="menutype"><a target="_blank" href="'. $item['link'] .'">'. $_LANG['AD_TYPE_ARTICLE'] .'</a></span> - '. cmsCore::c('db')->get_field('cms_content', 'id='. $item['linkid'], 'title');
            break;
        case 'category':
            $html = '<span id="menutype"><a target="_blank" href="'. $item['link'] .'">'. $_LANG['AD_TYPE_PARTITION'] .'</a></span> - '. cmsCore::c('db')->get_field('cms_category', 'id='. $item['linkid'], 'title');
            break;
        case 'video_cat':
            if (cmsCore::getInstance()->isComponentInstalled('video')) { 
                $html = '<span id="menutype"><a target="_blank" href="'. $item['link'] .'">'. $_LANG['AD_TYPE_VIDEO_PARTITION'] .'</a></span> - '. cmsCore::c('db')->get_field('cms_video_category', 'id='. $item['linkid'], 'title'); 
            } 
            break; 
        case 'uccat':
            $html = '<span id="menutype"><a target="_blank" href="'. $item['link'] .'">'. $_LANG['AD_TYPE_CATEGORY'] .'</a></span> - '. cmsCore::c('db')->get_field('cms_uc_cats', 'id='. $item['linkid'], 'title');
            break;
        case 'blog':
            $html = '<span id="menutype"><a target="_blank" href="'. $item['link'] .'">'. $_LANG['AD_TYPE_BLOG'] .'</a></span> - '. cmsCore::c('db')->get_field('cms_blogs', 'id='. $item['linkid'], 'title');
            break;
        case 'photoalbum':
            $html = '<span id="menutype"><a target="_blank" href="'. $item['link'] .'">'. $_LANG['AD_TYPE_ALBUM'] .'</a></span> - '. cmsCore::c('db')->get_field('cms_photo_albums', 'id='. $item['linkid'], 'title');
            break;
    }
    
    $clear = strip_tags($html);
    
    $r = mb_strlen($html) - mb_strlen($clear);
    
    if (mb_strlen($clear)>$maxlen) { $html = mb_substr($html, 0, $maxlen+$r) .'...'; }
    
    return $html;
}

function applet_menu() {
    $inCore = cmsCore::getInstance();

    global $_LANG;
    global $adminAccess;

    if (!cmsUser::isAdminCan('admin/menu', $adminAccess)) { cpAccessDenied(); }

    cmsCore::c('page')->setTitle($_LANG['AD_MENU']);
    cpAddPathway($_LANG['AD_MENU'], 'index.php?view=menu');

    $do = cmsCore::request('do', 'str', 'list');
    $id = cmsCore::request('id', 'int', -1);

    if ($do == 'list') {
        $toolmenu = array(
            array( 'icon' => 'new.gif', 'title' => $_LANG['AD_MENU_POINT_ADD'], 'link' => '?view=menu&do=add' ),
            array( 'icon' => 'newmenu.gif', 'title' => $_LANG['AD_MENU_ADD'], 'link' => '?view=menu&do=addmenu' ),
            array( 'icon' => 'edit.gif', 'title' => $_LANG['AD_EDIT_SELECTED'], 'link'=> "javascript:checkSel('?view=menu&do=edit&multiple=1');" ),
            array( 'icon' => 'delete.gif', 'title' => $_LANG['AD_DELETE_SELECTED'], 'link' => "javascript:checkSel('?view=menu&do=delete&multiple=1');" ),
            array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=menu&do=show&multiple=1');" ),
            array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=menu&do=hide&multiple=1');" ),
            array( 'icon' => 'help.gif', 'title' => $_LANG['AD_HELP'], 'link' => '?view=help&topic=menu' )
        );

        cpToolMenu($toolmenu);

        $fields = array(
            array( 'title' => 'Lt', 'field' => 'NSLeft', 'width' => '40' ),
            array(
                'title' => $_LANG['TITLE'],
                'field' => array('title', 'titles'), 'width'=>'',
                'link'  => '?view=menu&do=edit&id=%id%',
                'prc'   => function ($i) {
                    $i['titles'] = cmsCore::yamlToArray($i['titles']);
                    
                    // переопределяем название пункта меню в зависимости от языка
                    if (!empty($i['titles'][cmsConfig::getConfig('lang')])) {
                        $i['title'] = $i['titles'][cmsConfig::getConfig('lang')];
                    }
                    
                    return $i['title'];
                }
            ),
            array( 'title' => $_LANG['SHOW'], 'field' => 'published', 'width' => '80' ),
            array( 'title' => $_LANG['AD_ORDER'], 'field' => 'ordering', 'width' => '100' ),
            array( 'title' => $_LANG['AD_LINK'], 'field' => array('linktype', 'linkid', 'link'), 'width' => '240', 'prc' => 'cpMenutypeById' ),
            array( 'title' => $_LANG['AD_MENU'], 'field' => 'menu', 'width' => '80', 'filter' => '10', 'filterlist' => cpGetList('menu'), 'prc' => 'list_menu' ),
            array( 'title' => $_LANG['TEMPLATE'], 'field' => 'template', 'width' => '90', 'prc' => 'cpTemplateById' )
        );
        
        $actions = array(
            array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=menu&do=edit&id=%id%' ),
            array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_MENU_POINT_CONFIRM'], 'link' => '?view=menu&do=delete&id=%id%' )
        );

        cpListTable('cms_menu', $fields, $actions, 'parent_id>0', 'NSLeft, ordering');

    } else {
        $toolmenu = array(
            array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' ),
            array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => 'index.php?view=menu' )
        );

        cpToolMenu($toolmenu);
    }

    if ($do == 'move_up') {
        cmsCore::c('db')->moveNsCategory('cms_menu', $id, 'up');
        cmsCore::redirectBack();
    }

    if ($do == 'move_down') {
        cmsCore::c('db')->moveNsCategory('cms_menu', $id, 'down');
        cmsCore::redirectBack();
    }

    if ($do == 'show') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) { cmsCore::c('db')->setFlag('cms_menu', $id, 'published', '1'); }
            cmsCore::halt('1');
        } else {
            cmsCore::c('db')->setFlags('cms_menu', $_REQUEST['item'], 'published', '1');
            cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');
            cmsCore::redirectBack();
        }
    }

    if ($do == 'hide') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) { cmsCore::c('db')->setFlag('cms_menu', $id, 'published', '0'); }
            cmsCore::halt('1');
        } else {
            cmsCore::c('db')->setFlags('cms_menu', cmsCore::request('item', 'array_int', array()), 'published', '0');
            cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');
            cmsCore::redirectBack();
        }
    }

    if ($do == 'delete') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) { cmsCore::c('db')->deleteNS('cms_menu', (int)$id); }
        } else {
            cmsCore::c('db')->deleteListNS('cms_menu', cmsCore::request('item', 'array_int', array()));
        }
        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');
        cmsCore::redirectBack();
    }

    if ($do == 'update') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $id = cmsCore::request('id', 'int', 0);
        if(!$id){ cmsCore::redirectBack(); }

        $title     = cmsCore::request('title', 'str', '');
        $titles    = cmsCore::arrayToYaml(cmsCore::request('titles', 'array_str', array()));
        $menu      = cmsCore::arrayToYaml(cmsCore::request('menu', 'array_str', ''));
        $linktype  = cmsCore::request('mode', 'str', '');
        $linkid    = cmsCore::request($linktype, 'str', '');
        $link      = $inCore->getMenuLink($linktype, $linkid);
        $target    = cmsCore::request('target', 'str', '');
        $published = cmsCore::request('published', 'int', 0);
        $template  = cmsCore::request('template', 'str', '');
        $iconurl   = cmsCore::request('iconurl', 'str', '');
        $parent_id = cmsCore::request('parent_id', 'int', 0);
        $oldparent = cmsCore::request('oldparent', 'int', 0);
        $is_lax    = cmsCore::request('is_lax', 'int', 0);
        $css_class = cmsCore::request('css_class', 'str', '');

        $is_public = cmsCore::request('is_public', 'int', '');
        if (!$is_public) {
            $access_list = cmsCore::arrayToYaml(cmsCore::request('allow_group', 'array_int'));
        }

        $ns = $inCore->nestedSetsInit('cms_menu');

        if ($oldparent != $parent_id) {
            $ns->MoveNode($id, $parent_id);
        }

        $sql = "UPDATE cms_menu
                SET title='". $title ."',
                    titles='". $titles ."',
                    css_class='". $css_class ."',
                    menu='". $menu ."',
                    link='". $link ."',
                    linktype='". $linktype ."',
                    linkid='". $linkid ."',
                    target='". $target ."',
                    published='". $published ."',
                    template='". $template ."',
                    access_list='". $access_list ."',
                    is_lax='". $is_lax ."',
                    iconurl='". $iconurl ."'
                WHERE id = '". $id ."'
                LIMIT 1";
        cmsCore::c('db')->query($sql) ;

        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');

        if (!isset($_SESSION['editlist']) || count($_SESSION['editlist']) == 0) {
            cmsCore::redirect('?view=menu');
        } else {
            cmsCore::redirect('?view=menu&do=edit');
        }

    }

    if ($do == 'submit') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $title     = cmsCore::request('title', 'str', '');
        $titles    = cmsCore::arrayToYaml(cmsCore::request('titles', 'array_str', array()));
        $menu      = cmsCore::arrayToYaml(cmsCore::request('menu', 'array_str', ''));
        $linktype  = cmsCore::request('mode', 'str', '');
        $linkid    = cmsCore::request($linktype, 'str', '');
        $link      = $inCore->getMenuLink($linktype, $linkid);
        $target    = cmsCore::request('target', 'str', '');
        $published = cmsCore::request('published', 'int', 0);
        $template  = cmsCore::request('template', 'str', '');
        $iconurl   = cmsCore::request('iconurl', 'str', '');
        $parent_id = cmsCore::request('parent_id', 'int', 0);
        $css_class = cmsCore::request('css_class', 'str', '');

        $is_public = cmsCore::request('is_public', 'int', '');
        $is_lax    = cmsCore::request('is_lax', 'int', 0);
        if (!$is_public) {
            $access_list = cmsCore::arrayToYaml(cmsCore::request('allow_group', 'array_int'));
        }

        $ns = $inCore->nestedSetsInit('cms_menu');
        $myid = $ns->AddNode($parent_id);

        $sql = "UPDATE cms_menu
                        SET menu='". $menu ."',
                                title='". $title ."',
                                titles='". $titles ."',
                                css_class='". $css_class ."',
                                link='". $link ."',
                                linktype='". $linktype ."',
                                linkid='". $linkid ."',
                                target='". $target ."',
                                published='". $published ."',
                                template='". $template ."',
                                access_list='". $access_list ."',
                                is_lax='". $is_lax ."',
                                iconurl='". $iconurl ."'
                        WHERE id = '". $myid ."'";

        cmsCore::c('db')->query($sql);

        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');
        cmsCore::redirect('?view=menu');
    }

    if ($do == 'submitmenu') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $sql = "SELECT ordering as max_o FROM cms_modules ORDER BY ordering DESC LIMIT 1";
        $result = cmsCore::c('db')->query($sql) ;
        $row = cmsCore::c('db')->fetch_assoc($result);
        $maxorder = $row['max_o'] + 1;

        $menu       = cmsCore::request('menu', 'str', '');
        $title      = cmsCore::request('title', 'str', '');
        $position   = cmsCore::request('position', 'str', '');
        $published  = cmsCore::request('published', 'int', 0);
        $css_prefix = cmsCore::request('css_prefix', 'str', '');
        $is_public  = cmsCore::request('is_public', 'int', '');
        if (!$is_public) {
            $access_list = $inCore->arrayToYaml(cmsCore::request('allow_group', 'array_int'));
        }

        $cfg['menu'] = $menu;
        $cfg_str = cmsCore::arrayToYaml($cfg);

        $sql = "INSERT INTO cms_modules (position, name, title, is_external, content, ordering, showtitle, published, user, config, css_prefix, access_list)
                VALUES ('". $position ."', '". $_LANG['AD_MENU'] ."', '". $title ."', 1, 'mod_menu', ". $maxorder .", 1, ". $published .", 0, '". $cfg_str ."', '". $css_prefix ."', '". $access_list ."')";

        cmsCore::c('db')->query($sql) ;

        $newid = cmsCore::c('db')->get_last_id('cms_modules');

        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'] , 'success');

        cmsCore::redirect('?view=modules&do=edit&id='.$newid);

    }

    if ($do == 'addmenu' || $do == 'add' || $do == 'edit') {
        cmsCore::c('page')->addHeadJS('admin/js/menu.js');
        echo '<script>';
        echo cmsPage::getLangJS('AD_SPECIFY_LINK_MENU');
        echo '</script>';
    }

    if ($do == 'addmenu') {
        cmsCore::c('page')->setTitle($_LANG['AD_MENU_ADD']);
        cpAddPathway($_LANG['AD_MENU_ADD']);

        $menu_list = cpGetList('menu');

?>
<form id="addform" name="addform" action="index.php?view=menu&do=submitmenu" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
            
    <div class="panel panel-default" style="width:650px;">
        <div class="panel-body">
            <div class="form-group">
                <label><?php echo $_LANG['AD_MODULE_MENU_TITLE']; ?></label>
                <input type="text" id="title2" class="form-control" name="title" style="width:99%" value="" />
            </div>

            <div class="form-group">
                <label><?php echo $_LANG['AD_MENU_TO_VIEW']; ?></label>
                <select id="menu" class="form-control" name="menu" style="width:99%">
                    <?php foreach ($menu_list as $menu) { ?>
                        <option value="<?php echo $menu['id']; ?>">
                            <?php echo $menu['title']; ?>
                        </option>
                    <?php } ?>
                </select>
                <div class="help-block"><?php echo $_LANG['AD_TO_CREATE_NEW_POINT']; ?></div>
            </div>
                    
            <div class="form-group">
                <label><?php echo $_LANG['AD_POSITION_TO_VIEW']; ?></label>
                <?php
                    $pos = cpModulePositions(cmsConfig::getConfig('template'));
                ?>
                <select id="position" class="form-control" name="position" style="width:99%">
                    <?php
                        if ($pos){
                            foreach($pos as $key=>$position) {
                                if (cmsCore::getArrVal($mod, 'position') == $position){
                                    echo '<option value="'. $position .'" selected>'. $position .'</option>';
                                } else {
                                    echo '<option value="'. $position .'">'. $position .'</option>';
                                }
                            }
                        }
                    ?>
                </select>
                <input name="is_external" type="hidden" id="is_external" value="0" />
                <div class="help-block"><?php echo $_LANG['AD_POSITION_MUST_BE']; ?></div>
            </div>
                    
            <div class="form-group">
                <label><?php echo $_LANG['AD_MENU_PUBLIC']; ?></label>
                <label>
                    <input name="published" type="radio" value="1" checked="checked" <?php if (cmsCore::getArrVal($mod, 'published')) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['YES']; ?>
                </label>
                <label>
                    <input name="published" type="radio" value="0"  <?php if (!cmsCore::getArrVal($mod, 'published')) { echo 'checked="checked"'; } ?> /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
                    
            <div class="form-group">
                <label><?php echo $_LANG['AD_PREFIX_CSS']; ?></label>
                <input type="text" id="css_prefix" class="form-control" name="css_prefix" value="<?php echo cmsCore::getArrVal($mod, 'css_prefix'); ?>" style="width:99%" />
            </div>
                    
            <div class="form-group">
                <label><?php echo $_LANG['AD_TAB_ACCESS']; ?>:</label>
                <div class="help-block"><?php echo $_LANG['AD_GROUP_ACCESS'] ; ?></div>
                <?php
                    $groups = cmsUser::getGroups();
                    $style  = 'disabled="disabled"';
                    $public = 'checked="checked"';

                    if ($do == 'edit') {
                        if ($mod['access_list']) {
                            $public = '';
                            $style  = '';
                            $access_list = $inCore->yamlToArray($mod['access_list']);
                        }
                    }
                ?>
                <label><input name="is_public" type="checkbox" id="is_public" onclick="checkAccesList()" value="1" <?php echo $public?> /> <?php echo $_LANG['AD_SHARE']; ?></label>
                <div class="help-block"><?php echo $_LANG['AD_VIEW_IF_CHECK']; ?></div>
            </div>
                    
            <div class="form-group">
                <label><?php echo $_LANG['AD_GROUPS_VIEW']; ?></label>
                <?php
                    echo '<select class="form-control" style="width: 99%" name="allow_group[]" id="allow_group" size="6" multiple="multiple" '. $style .'>';
                    if ($groups) {
                        foreach ($groups as $group) {
                            echo '<option value="'. $group['id'] .'"';
                            if ($do == 'edit') {
                                if (in_array($group['id'], $access_list)) {
                                    echo 'selected="selected"';
                                }
                            }
                            echo '>';
                            echo $group['title'].'</option>';
                        }
                    }
                            
                    echo '</select>';
                ?>
                <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL']; ?></div>
            </div>
                    
            <div class="alert alert-info" role="alert"><?php echo $_LANG['AD_NEW_MENU_NEW_MODULE']; ?></div>
        </div>
    </div>

    <div style="margin-top:5px">
        <input class="btn btn-primary" name="save" type="submit" id="save" value="<?php echo $_LANG['AD_MENU_ADD']; ?>" />
        <input class="btn btn-default" name="back" type="button" id="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=menu';" />
    </div>
</form>
<?php
    }

    if ($do == 'add' || $do == 'edit') {
        $menu_list = cpGetList('menu');
        
        $langs = cmsCore::getDirsList('/languages');
        
        if ($do == 'add') {
            cpAddPathway($_LANG['AD_MENU_POINT_ADD']);
            $mod['menu'] = array('mainmenu');
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
                $item_id = cmsCore::request('id', 'int', 0);
            }

            $mod = cmsCore::c('db')->get_fields('cms_menu', "id = '$item_id'", '*');
            if (!$mod) { cmsCore::error404(); }

            $mod['menu']   = cmsCore::yamlToArray($mod['menu']);
            $mod['titles'] = cmsCore::yamlToArray($mod['titles']);

            cpAddPathway($_LANG['AD_MENU_POINT_EDIT'].$ostatok.' "'.$mod['title'].'"');

        }
?>
<form id="addform" name="addform" method="post" action="index.php">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <input type="hidden" name="view" value="menu" />

    <table class="table">
        <tr>
            <td valign="top">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_MENU_POINT_TITLE']; ?></label>
                            <input type="text" id="title" class="form-control" style="width:100%" name="title" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', ''));?>" />
                            <div class="help-block"><?php echo $_LANG['AD_VIEW_IN_SITE']; ?></div>
                        </div>
                        
                        <?php if (count($langs) > 1) { ?>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_LANG_TITLES']; ?></label>
                            <?php foreach ($langs as $lang) { ?>
                                <div>
                                    <strong><?php echo $lang; ?>:</strong>
                                    <input name="titles[<?php echo $lang; ?>]" type="text" style="width:97%" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod['titles'], $lang, '')); ?>" placeholder="<?php echo $_LANG['AD_HINT_DEFAULT']; ?>" />
                                </div>
                            <?php } ?>
                            <div class="help-block"><?php echo $_LANG['AD_LANG_TITLES_HINT']; ?></div>
                        </div>
                        <?php } ?>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_PARENT_POINT']; ?></label>
                            <?php
                                $rootid = cmsCore::c('db')->get_field('cms_menu', 'parent_id=0', 'id');
                            ?>
                            <select id="parent_id" class="form-control" style="width:100%" name="parent_id" size="10">
                                <option value="<?php echo $rootid; ?>" <?php if (cmsCore::getArrVal($mod, 'parent_id', $rootid) == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_MENU_ROOT']; ?></option>
                                <?php
                                    echo $inCore->getListItemsNS('cms_menu', cmsCore::getArrVal($mod, 'parent_id', 0));
                                ?>
                            </select>
                            <input type="hidden" name="oldparent" value="<?php echo cmsCore::getArrVal($mod, 'parent_id', '');?>" />
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_MENU_POINT_ACTION']; ?></label>
                            <select id="linktype" class="form-control" style="width:100%" name="mode" onchange="showMenuTarget()">
                                <?php $link_type = cmsCore::getArrVal($mod, 'linktype', 'link') ?>
                                <option value="link" <?php if ($link_type == 'link') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_LINK']; ?></option>
                                <option value="content" <?php if ($link_type == 'content') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_ARTICLE']; ?></option>
                                <?php if($inCore->isComponentInstalled('video')){ ?> 
                                    <option value="video_cat" <?php if ($link_type == 'video_cat') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_VIDEO_PARTITION']; ?></option> 
                                <?php } ?>
                                <option value="category" <?php if ($link_type == 'category') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_PARTITION']; ?></option>
                                <option value="component" <?php if ($link_type == 'component') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_COMPONENT']; ?></option>
                                <option value="blog" <?php if ($link_type == 'blog') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_BLOG']; ?></option>
                                <option value="uccat" <?php if ($link_type == 'uccat') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_CATEGORY']; ?></option>
                                <option value="photoalbum" <?php if ($link_type == 'photoalbum') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_OPEN_ALBUM']; ?></option>
                            </select>
                        </div>
                            
                        <div id="t_link" class="form-group menu_target" style="display:<?php if ($link_type == 'link' || $link_type == 'ext') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_LINK']; ?></label>
                            <input type="text" id="link" class="form-control" style="width:100%" name="link" size="50" value="<?php if ($link_type == 'link' || $link_type == 'ext') { echo cmsCore::getArrVal($mod, 'link', ''); } ?>" />
                            <div class="help-block"><?php echo $_LANG['AD_LINK_HINT']; ?> <b>http://</b></div>
                        </div>
                            
                        <div id="t_content" class="form-group menu_target" style="display:<?php if ($link_type == 'content') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_ARTICLE'] ; ?></label>
                            <select id="content" class="form-control" style="width:100%" name="content">
                                <?php
                                    echo $inCore->getListItems('cms_content', (($link_type == 'content') ? $mod['linkid'] : 0));
                                ?>
                            </select>
                        </div>
                            
                        <?php if($inCore->isComponentInstalled('video')){ ?> 
                        <div id="t_video_cat" class="form-group menu_target" style="display:<?php if ($link_type == 'video_cat') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_PARTITION']; ?></label>
                            <select id="video_cat" class="form-control" style="width:100%" name="video_cat"> 
                                <?php
                                    echo $inCore->getListItemsNS('cms_video_category', (($link_type == 'video_cat') ? $mod['linkid'] : 0));
                                ?> 
                            </select>
                        </div>
                        <?php } ?>
                            
                        <div id="t_category" class="form-group menu_target" style="display:<?php if ($link_type == 'category') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_PARTITION']; ?></label>
                            <select id="category" class="form-control" style="width:100%" name="category"> 
                                <?php
                                    echo $inCore->getListItemsNS('cms_category', (($link_type == 'category') ? $mod['linkid'] : 0));
                                ?> 
                            </select>
                        </div>
                            
                        <div id="t_component" class="form-group menu_target" style="display:<?php if ($link_type == 'component') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_COMPONENT']; ?></label>
                            <select id="component" class="form-control" style="width:100%" name="component"> 
                                <?php
                                    echo $inCore->getListItems('cms_components', (($link_type == 'component') ? $mod['linkid'] : 0), 'title', 'asc', 'internal=0', 'link');
                                ?> 
                            </select>
                        </div>
                            
                        <div id="t_blog" class="form-group menu_target" style="display:<?php if ($link_type == 'blog') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_BLOG']; ?></label>
                            <select id="blog" class="form-control" style="width:100%" name="blog"> 
                                <?php
                                    echo $inCore->getListItems('cms_blogs', (($link_type == 'blog') ? $mod['linkid'] : 0), 'title', 'asc', "owner='user'");
                                ?> 
                            </select>
                        </div>
                            
                        <div id="t_uccat" class="form-group menu_target" style="display:<?php if ($link_type == 'uccat') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_CATEGORY']; ?></label>
                            <select id="blog" class="form-control" style="width:100%" name="blog"> 
                                <?php
                                    echo $inCore->getListItems('cms_uc_cats', (($link_type == 'uccat') ? $mod['linkid'] : 0));
                                ?> 
                            </select>
                        </div>
                            
                        <div id="t_photoalbum" class="form-group menu_target" style="display:<?php if ($link_type == 'photoalbum') { echo 'block'; } else { echo 'none'; } ?>">
                            <label><?php echo $_LANG['AD_CHECK_ALBUM']; ?></label>
                            <select id="blog" class="form-control" style="width:100%" name="blog"> 
                                <?php
                                    echo $inCore->getListItems('cms_photo_albums', (($link_type == 'photoalbum') ? $mod['linkid'] : 0), 'id', 'ASC', 'NSDiffer = ""');
                                ?> 
                            </select>
                        </div>
                    </div>
                </div>
            </td>

            <td width="400" valign="top">
                <div class="uitabs">
                    <ul id="tabs">
                        <li><a href="#upr_publish"><span><?php echo $_LANG['AD_TAB_PUBLISH']; ?></span></a></li>
                        <li><a href="#upr_access"><span><?php echo $_LANG['AD_TAB_ACCESS']; ?></span></a></li>
                        <li><a href="#upr_menu"><span><?php echo $_LANG['AD_MENU']; ?></span></a></li>
                    </ul>
                        
                    <div id="upr_publish">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="published" value="1" <?php if (cmsCore::getArrVal($mod, 'published') || $do == 'add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_MENU_POINT_PUBLIC']; ?>
                            </label>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_OPEN_POINT']; ?></label>
                            <select id="target" class="form-control" style="width:100%" name="target">
                                <option value="_self" <?php if (@$mod['target']=='_self') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_SELF']; ?></option>
                                <option value="_parent"><?php echo $_LANG['AD_PARENT'];?></option>
                                <option value="_blank" <?php if (@$mod['target']=='_blank') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_BLANK']; ?></option>
                                <option value="_top" <?php if (@$mod['target']=='_top') { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_TOP']; ?></option>
                            </select>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['TEMPLATE']; ?></label>
                            <select id="template" class="form-control" style="width:100%" name="template"  >
                                <option value="0" <?php if (!cmsCore::getArrVal($mod, 'template')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_DEFAULT'];?></option>
                                <?php
                                $templates = cmsCore::getDirsList('/templates');
                                foreach ($templates as $template) {
                                    if ($template == 'admin') { continue; }
                                    echo '<option value="'. $template .'" '.(cmsCore::getArrVal($mod, 'template') ? 'selected="selected"': '').'>'.$template.'</option>';
                                }
                                ?>
                            </select>
                            <div class="help-block"><?php echo $_LANG['AD_DESIGN_CHANGE'] ;?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_ICON_PICTURE']; ?></label>
                            <input type="text" id="iconurl" class="form-control" style="width:100%" name="iconurl" size="30" value="<?php echo cmsCore::getArrVal($mod, 'iconurl', ''); ?>" />
                                
                            <a id="iconlink" style="display:block;" href="javascript:showIcons()"><?php echo $_LANG['AD_CHECK_ICON'];?></a>
                            <div id="icondiv" style="display:none; padding:6px;border:solid 1px gray;background:#FFF">
                                <div><?php iconList(); ?></div>
                            </div>
                                
                            <div class="help-block"><?php echo $_LANG['AD_ICON_FILENAME'] ;?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_CSS_CLASS']; ?></label>
                            <input type="text" class="form-control" style="width:100%" name="css_class" size="30" value="<?php echo cmsCore::getArrVal($mod, 'css_class', ''); ?>" />
                        </div>
                    </div>
                        
                    <div id="upr_access">
                        <div class="form-group">
                            <?php
                                $groups = cmsUser::getGroups();
                                $style  = 'disabled="disabled"';
                                $public = 'checked="checked"';

                                if ($do == 'edit') {
                                    if ($mod['access_list']) {
                                        $public = '';
                                        $style  = '';
                                        $access_list = $inCore->yamlToArray($mod['access_list']);
                                    }
                                }
                            ?>
                                
                            <label>
                                <input type="checkbox" name="is_public" id="is_public" onclick="checkAccesList()" value="1" <?php echo $public; ?> />
                                <?php echo $_LANG['AD_SHARE']; ?>
                            </label>
                            <div class="help-block"><?php echo $_LANG['AD_VIEW_IF_CHECK'];?></div>
                        </div>
                            
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_GROUPS_VIEW']; ?></label>
                            <?php
                                echo '<select id="allow_group" class="form-control" style="width: 99%" name="allow_group[]"  size="6" multiple="multiple" '.$style.'>';
                                if ($groups) {
                                    foreach($groups as $group) {
                                        echo '<option value="'. $group['id'] .'"';
                                        if ($do == 'edit' && cmsCore::getArrVal($mod, 'access_list')) {
                                            if (in_array($group['id'], $access_list)){
                                                echo 'selected="selected"';
                                            }
                                        }
                                        echo '>';
                                        echo $group['title'] .'</option>';
                                    }
                                }

                                echo '</select>';
                            ?>
                            <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL'];?></div>
                        </div>
                            
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="is_lax" name="is_lax" value="1" <?php if(cmsCore::getArrVal($mod, 'is_lax')) {?>checked="checked"<?php } ?> />
                                <?php echo $_LANG['AD_ONLY_CHILD_ITEM']; ?>
                            </label>
                        </div>
                    </div>
                        
                    <div id="upr_menu">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_MENU_TO_VIEW'];?></label>
                            <select class="form-control" style="width: 99%" name="menu[]" size="9" multiple="multiple">
                                <?php foreach ($menu_list as $menu) { ?>
                                <option value="<?php echo $menu['id']; ?>" <?php if (in_array($menu['id'], cmsCore::getArrVal($mod, 'menu', array()))) { echo 'selected="selected"'; }?>>
                                        <?php echo $menu['title']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL'];?></div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div>
        <input type="button" class="btn btn-primary" name="add_mod" onclick="submitItem()" value="<?php echo $_LANG['SAVE']; ?> " />
        <input type="button" class="btn btn-default" name="back"  value="<?php echo $_LANG['CANCEL'];?>" onclick="window.location.href='index.php?view=menu';" />
        <input type="hidden" name="do" <?php if ($do=='add') { echo 'value="submit"'; } else { echo 'value="update"'; } ?> />
        <?php
            if ($do == 'edit') {
                echo '<input type="hidden" name="id" value="'.$mod['id'].'" />';
            }
        ?>
    </div>
</form>
<?php
   }
}
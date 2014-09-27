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

function cpPriceInput($item) {
    $sql = "SELECT view_type FROM cms_uc_cats WHERE id = '{$item['category_id']}'";
    $rs = cmsCore::c('db')->query($sql) ;
    $show = cmsCore::c('db')->fetch_assoc($rs);

    if ($show['view_type'] == 'shop') {
        $price = number_format($item['price'], 2, '.', '');
        $html  = '<input type="text" name="price['.$item['id'].']" value="'.$price.'" id="priceinput"/>';
    } else {
        $html = '&mdash;';
    }

    return $html;
}

cmsCore::loadModel('catalog');
$model = new cms_model_catalog();

$cfg = $inCore->loadComponentConfig('catalog');
$opt = cmsCore::request('opt', 'str', 'list_cats');

define('IS_BILLING', $inCore->isComponentInstalled('billing'));
if (IS_BILLING) { cmsCore::loadClass('billing'); }

cmsCore::c('page')->addHeadJS('admin/components/catalog/js/common.js');

echo '<script>',cmsPage::getLangJS('AD_HOW_MANY_COPY'),'</script>';


$toolmenu = array(
    array( 'icon' => 'newfolder.gif', 'title' => $_LANG['AD_NEW_CAT'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_cat'),
    array( 'icon' => 'newstuff.gif', 'title' => $_LANG['ADD_ITEM'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_item'),
    array( 'icon' => 'newdiscount.gif', 'title' => $_LANG['AD_NEW_COEFFICIENT'], 'link' => '?view=components&do=config&id='. $id .'&opt=add_discount'),
    array( 'icon' => 'folders.gif', 'title' => $_LANG['AD_ALL_CAT'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_cats'),
    array( 'icon' => 'liststuff.gif', 'title' => $_LANG['AD_ALL_ITEM'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_items'),
    array( 'icon' => 'listdiscount.gif', 'title' => $_LANG['AD_ALL_COEFFICIENTS'], 'link' => '?view=components&do=config&id='. $id .'&opt=list_discount'),
    array( 'icon' => 'excel.gif', 'title' => $_LANG['AD_MS_EXCEL_IMPORT'], 'link' => '?view=components&do=config&id='. $id .'&opt=import_xls')
);

if ($opt == 'list_items') {
    $toolmenu[] = array( 'icon' => 'show.gif', 'title' => $_LANG['AD_ALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=show_item&multiple=1');");
    $toolmenu[] = array( 'icon' => 'hide.gif', 'title' => $_LANG['AD_DISALLOW_SELECTED'], 'link' => "javascript:checkSel('?view=components&do=config&id=". $id ."&opt=hide_item&multiple=1');");
    $toolmenu[] = array( 'icon' => 'saveprices.gif', 'title' => $_LANG['AD_SAVE_COSTS'], 'link' => "javascript:sendForm('index.php?view=components&do=config&id=". $id ."&opt=saveprices');");
}

$toolmenu[] = array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=components&do=config&id='. $id .'&opt=config');


cpToolMenu($toolmenu);

if ($opt == 'go_import_xls') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item['category_id'] = cmsCore::request('cat_id', 'int', 0);
    $item['user_id']     = cmsCore::request('user_id', 'int', 1);
    $item['published']   = cmsCore::request('published', 'int', 0);
    $item['is_comments'] = cmsCore::request('is_comments', 'int', 0);
    $item['tags']        = cmsCore::request('tags', 'str', '');
    $item['canmany']     = cmsCore::request('canmany', 'int', 0);
    $item['meta_keys']   = $item['tags'];
    $item['pubdate']     = date('Y-m-d H:i');
    $item['imageurl']    = '';

    $rows    = cmsCore::request('xlsrows', 'int', 0);
    $sheet   = cmsCore::request('xlslist', 'int', 1);
    $cells   = cmsCore::request('cells', 'array_str', array());
    $charset = cmsCore::request('charset', 'str', 'cp1251');

    if (empty($_FILES['xlsfile']['name'])) {
        cmsCore::addSessionMessage($_LANG['AD_NO_LOAD_EXCEL_FILE'], 'error');
        cmsCore::redirectBack();
    }

    $xls_file = PATH .'/upload/'. md5(microtime().uniqid()). '.xls';
    if (!cmsCore::moveUploadedFile($_FILES['xlsfile']['tmp_name'], $xls_file, $_FILES['xlsfile']['error'])) {
        cmsCore::addSessionMessage($_LANG['AD_NO_LOAD_EXCEL_FILE'], 'error');
        cmsCore::redirectBack();
    }

    $file = $model->uploadPhoto();
    if ($file) {
        $item['imageurl'] = $file['filename'];
    }

    cmsCore::includeFile('includes/excel/excel_reader2.php');
    $data = new Spreadsheet_Excel_Reader($xls_file, true, $charset);

    for ($r=0; $r<$rows; $r++) {
        $fields = array();
        $title  = '';
        $item['price'] = '';

        foreach ($cells as $cell_id=>$pos) {
            if (isset($pos['ignore'])) {
                $celldata = $pos['other'];
            } else {
                $celldata = ($charset == 'cp1251') ?
                iconv('cp1251', 'UTF-8', $data->val($r+$pos['row'],$pos['col'],$sheet-1)) :
                $data->val($r+$pos['row'],$pos['col'],$sheet-1);
            }

            if ($cell_id === 'title') {
                $title = $celldata;
            } else if ($cell_id === 'price') {
                $item['price'] = $celldata;
            } else {
                $fields[] = $celldata;
            }
        }

        $item['fieldsdata'] = cmsCore::c('db')->escape_string(cmsCore::arrayToYaml($fields));
        $item['title']      = cmsCore::c('db')->escape_string($title);

        if ($item['title'] && $item['fieldsdata']) {
            $model->addItem($item);
        }
    }

    unlink($xls_file);

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&opt=list_items&id='. $id);
}

if ($opt == 'saveprices') {
    $prices = cmsCore::request('price', 'array_str', array());
    if (is_array($prices)) {
        foreach ($prices as $id => $price) {
            $price = str_replace(',', '.', $price);
            $price = number_format($price, 2, '.', '');
            $sql = "UPDATE cms_uc_items SET price='$price' WHERE id = $id";
            cmsCore::c('db')->query($sql);
        }
    }
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'show_item') {
    if (!isset($_REQUEST['item'])) {
        if (isset($_REQUEST['item_id'])) {
            cmsCore::c('db')->setFlag('cms_uc_items', (int)$_REQUEST['item_id'], 'published', '1');
            cmsCore::c('db')->setFlag('cms_uc_items', (int)$_REQUEST['item_id'], 'on_moderate', '0');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_uc_items', $_REQUEST['item'], 'published', '1');
        foreach($_REQUEST['item'] as $k=>$id) {
            cmsCore::c('db')->query('UPDATE cms_uc_items SET on_moderate = 0 WHERE id='.(int)$id);
        }
        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_item') {
    if (!isset($_REQUEST['item'])) {
        if (isset($_REQUEST['item_id'])){ cmsCore::c('db')->setFlag('cms_uc_items', $_REQUEST['item_id'], 'published', '0'); }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_uc_items', $_REQUEST['item'], 'published', '0');
        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
        cmsCore::redirectBack();
    }
}

if ($opt == 'renew_item'){
    $model->renewItem(cmsCore::request('item_id', 'int', 0));
    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_items');
}

if ($opt == 'delete_item') {
    $model->deleteItem(cmsCore::request('item_id', 'int', 0));
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_items');
}

if ($opt == 'submit_discount' || $opt == 'update_discount') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item['title']    = cmsCore::request('title', 'str');
    $item['cat_id']   = cmsCore::request('cat_id', 'int');
    $item['sign']     = cmsCore::request('sign', 'str');
    $item['value']    = cmsCore::request('value', 'str');
    $item['unit']     = cmsCore::request('unit', 'str');
    $item['if_limit'] = cmsCore::request('if_limit', 'int', 0);

    if ($opt == 'update_discount') {
        $model->updateDiscount(cmsCore::request('item_id', 'int', 0), $item);
    } else {
        $model->addDiscount($item);
    }
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&opt=list_discount&id='.$id);
}

if($opt == 'delete_discount') {
    $model->deleteDiscount(cmsCore::request('item_id', 'int', 0));
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_discount');
}

if ($opt == 'show_cat') {
    $item_id = cmsCore::request('item_id', 'int');
    $sql = "UPDATE cms_uc_cats SET published = 1 WHERE id = '$item_id'";
    cmsCore::c('db')->query($sql) ;
    cmsCore::halt('1');
}

if ($opt == 'hide_cat') {
    $item_id = cmsCore::request('item_id', 'int');
    $sql = "UPDATE cms_uc_cats SET published = 0 WHERE id = '$item_id'";
    cmsCore::c('db')->query($sql) ;
    cmsCore::halt('1');
}

if ($opt == 'submit_cat' || $opt == 'update_cat') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cat['parent_id']      = cmsCore::request('parent_id', 'int');
    $cat['title']          = cmsCore::request('title', 'str', $_LANG['AD_UNTITLED']);
    $cat['description']    = cmsCore::request('description', 'html');
    $cat['description']    = cmsCore::c('db')->escape_string($cat['description']);
    $cat['published']      = cmsCore::request('published', 'int');
    $cat['view_type']      = cmsCore::request('view_type', 'str');
    $cat['fields_show']    = cmsCore::request('fieldsshow', 'int');
    $cat['showmore']       = cmsCore::request('showmore', 'int');
    $cat['perpage']        = cmsCore::request('perpage', 'int');
    $cat['showtags']       = cmsCore::request('showtags', 'int');
    $cat['showabc']        = cmsCore::request('showabc', 'int');
    $cat['showsort']       = cmsCore::request('showsort', 'int');
    $cat['is_ratings']     = cmsCore::request('is_ratings', 'int');
    $cat['filters']        = cmsCore::request('filters', 'int');
    $cat['orderby']        = cmsCore::request('orderby', 'str');
    $cat['orderto']        = cmsCore::request('orderto', 'str');
    $cat['shownew']        = cmsCore::request('shownew', 'int');
    $cat['newint']         = cmsCore::request('int_1', 'int') . ' ' . cmsCore::request('int_2', 'str');
    $cat['is_public']      = cmsCore::request('is_public', 'int', 0);
    $cat['can_edit']       = cmsCore::request('can_edit', 'int', 0);
    $cat['cost']           = cmsCore::request('cost', 'str', '');
    if (!is_numeric($cat['cost'])) { $cat['cost'] = ''; }

    if (cmsCore::request('copy_parent_struct')) {
        $fstruct = cmsCore::c('db')->get_field('cms_uc_cats', "id='{$cat['parent_id']}'", 'fieldsstruct');
    } else {
        $fstruct = cmsCore::request('fstruct', 'array', array());
        foreach ($fstruct as $key=>$value) {
            if ($value=='') { unset($fstruct[$key]); continue; }
            if ($_REQUEST['fformat'][$key]=='html') { $fstruct[$key] .= '/~h~/'; }
            if ($_REQUEST['fformat'][$key]=='link') { $fstruct[$key] .= '/~l~/'; }
            if ($_REQUEST['flink'][$key]) { $fstruct[$key] .= '/~m~/'; }
        }
        $fstruct = cmsCore::arrayToYaml($fstruct);
    }
    $cat['fieldsstruct'] = cmsCore::c('db')->escape_string($fstruct);

    if ($opt == 'submit_cat') {
        $cat_id = cmsCore::c('db')->addNsCategory('cms_uc_cats', cmsCore::callEvent('ADD_CATALOG_CAT', $cat));
    } else {
        $cat_id = cmsCore::request('item_id', 'int', 0);
        $model->updateCategory($cat_id, $cat);
    }

    if ($cat['is_public']) {
        $showfor = cmsCore::request('showfor', 'array_int', array());
        if ($showfor) {
            $model->setCategoryAccess($cat_id, $showfor);
        }
    } else {
        $model->clearCategoryAccess($cat_id);
    }

    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_cats');
}

if ($opt == 'delete_cat') {
    $model->deleteCategory(cmsCore::request('item_id', 'int', 0));
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id .'&opt=list_cats');
}

if ($opt == 'list_cats') {
    echo '<h3>'. $_LANG['AD_CATALOG_RUBRICS'] .'</h3>';

    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%' ),
        array( 'title' => $_LANG['AD_PARENT'], 'field' => 'parent_id', 'width' => '200', 'prc' => 'cpCatalogCatById' ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_cat' )
    );
    
    $actions = array(
        array( 'title' => $_LANG['AD_CONTENT_VIEW'], 'icon' => 'explore.gif', 'link' => 'javascript:openCat(%id%)' ),
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_cat&item_id=%id%' ),
        array( 'title' => $_LANG['AD_DO_COPY'], 'icon' => 'copy.gif', 'link' => "javascript:copyCat(". $id .", %id%);" ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_IF_RUBRIC_DELETE'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_cat&item_id=%id%' )
    );

    echo '<script type="text/javascript">function openCat(id){ $("#catform input").val(id); $("#catform").submit(); } </script>';
    echo '<form id="catform" method="post" action="index.php?view=components&do=config&id='. $id .'&opt=list_items"><input type="hidden" id="filter[category_id]" name="filter[category_id]" value=""></form>';

    cpListTable('cms_uc_cats', $fields, $actions, 'parent_id>0', 'NSLeft');
}

if ($opt == 'list_items') {
    cmsCore::c('page')->addHeadJS('admin/components/catalog/js/common.js');
    cpAddPathway($_LANG['AD_ITEMS']);

    if (cmsCore::inRequest('on_moderate')) {
        echo '<h3>'. $_LANG['AD_ITEMS_TO_MODERATION'] .'</h3>';
    } else {
        echo '<h3>'. $_LANG['AD_ITEMS'] .'</h3>';
    }

    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => (cmsCore::inRequest('on_moderate') ? '/catalog/item%id%.html' : '/catalog/edit%id%.html'), 'filter' => 15 ),
        array( 'title' => $_LANG['AD_IS_PUBLISHED'], 'field' => 'published', 'width' => '100', 'do' => 'opt', 'do_suffix' => '_item' ),
        array( 'title' => $_LANG['AD_CAT_BOARD'], 'field' => 'category_id', 'width' => '200', 'prc' => 'cpCatalogCatById', 'filter' => 1, 'filterlist' => cpGetList('cms_uc_cats') ),
        array( 'title' => $_LANG['PRICE'], 'field' => array('id', 'category_id', 'price'), 'width' => '150', 'prc' => 'cpPriceInput' )
    );
    
    $actions = array(
        array( 'title' => $_LANG['AD_NEW_CALENDAR_DATA'], 'icon' => 'date.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=renew_item&item_id=%id%' ),
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '/catalog/edit%id%.html' ),
        array( 'title' => $_LANG['AD_DO_COPY'], 'icon' => 'copy.gif', 'link' => "javascript:copyItem(". $id .", %id%);" ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_IF_ITEM_DELETE'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_item&item_id=%id%' )
    );

    if (cmsCore::inRequest('on_moderate')){ $where = 'on_moderate=1'; } else { $where = ''; }

    cpListTable('cms_uc_items', $fields, $actions, $where);
}

if ($opt == 'list_discount') {
    cpAddPathway($_LANG['AD_COEFFICIENTS']);
    echo '<h3>'. $_LANG['AD_COEFFICIENTS'] .'</h3>';

    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40'),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_discount&item_id=%id%'),
        array( 'title' => $_LANG['AD_CAT_BOARD'], 'field' => 'cat_id', 'width' => '200', 'prc' => 'cpCatalogCatById'),
        array( 'title' => $_LANG['AD_TYPE'], 'field' => 'sign', 'width'=> '40'),
        array( 'title' => $_LANG['AD_SIZE'], 'field' => 'value', 'width' => '80'),
        array( 'title' => $_LANG['AD_UNITS'], 'field' => 'unit', 'width' => '80'),
        array( 'title' => $_LANG['AD_LIMIT'], 'field' => 'if_limit', 'width' => '80')
    );

    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit_discount&item_id=%id%'),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_IF_COEFFICIENT_DELETE'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete_discount&item_id=%id%')
    );

    cpListTable('cms_uc_discount', $fields, $actions);
}

if ($opt == 'copy_item') {
    $item_id = cmsCore::request('item_id', 'int', 0);
    $copies  = cmsCore::request('copies', 'int', 0);
    if ($copies) {
        $model->copyItem($item_id, $copies);
    }
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_items');
}

if ($opt == 'copy_cat') {
    $item_id = cmsCore::request('item_id', 'int', 0);
    $copies  = cmsCore::request('copies', 'int', 0);
    if ($copies) {
        $model->copyCategory($item_id, $copies);
    }
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=list_cats');
}

if ($opt == 'add_item') {
    echo '<h3>'. $_LANG['ADD_ITEM'] .'</h3>';
    echo '<h4>'. $_LANG['AD_SELECT_CAT'] .':</h4>';
    cpAddPathway($_LANG['ADD_ITEM']);

    $sql = "SELECT id, title, NSLeft, NSLevel, parent_id
            FROM cms_uc_cats
            WHERE parent_id > 0
            ORDER BY NSLeft";
    $result = cmsCore::c('db')->query($sql);

    if (cmsCore::c('db')->num_rows($result)) {
        echo '<div style="padding:10px">';
            while ($cat = cmsCore::c('db')->fetch_assoc($result)) {
                echo '<div style="padding:2px;padding-left:18px;margin-left:'.(($cat['NSLevel']-1)*15).'px;background:url(/admin/images/icons/hmenu/cats.png) no-repeat">
                          <a href="/catalog/'.$cat['id'].'/add.html">'.$cat['title'].'</a>
                      </div>';
            }
        echo '</div>';
    }
}

if ($opt == 'add_cat' || $opt == 'edit_cat') {
    if ($opt == 'add_cat') {
        echo '<h3>'. $_LANG['AD_NEW_CAT'] .'</h3>';
        cpAddPathway($_LANG['AD_NEW_CAT']);
        
        $mod = array();
        $fstruct = array();
    } else {
        $item_id = cmsCore::request('item_id', 'int', 0);
        
        $mod = cmsCore::c('db')->get_fields('cms_uc_cats', "id = '$item_id'", '*');
        
        if (!$mod) { cmsCore::error404(); }
        
        $fstruct = cmsCore::yamlToArray($mod['fieldsstruct']);
        
        echo '<h3>'. $_LANG['AD_CAT_BOARD'] .': '. $mod['title'] .'</h3>';
        
        cpAddPathway($mod['title']);
    }
?>

<form id="addform" name="addform" method="post" action="index.php?view=components&do=config&id=<?php echo $id;?>" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <table class="table">
        <tr>
            <!-- главная ячейка -->
            <td valign="top">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_CAT_NAME'];?></label>
                    <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', '')); ?>" />
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_ITEMS_FEATURES'];?></label>
                    <div class="help-block"><?php echo $_LANG['AD_FIELDS_NAME'];?></div>
                    <div class="help-block"><?php echo $_LANG['AD_WHAT_MAKING_AUTOSEARCH'];?></div>
                    
                    <script type="text/javascript">
                        function toggleFields(){
                            var copy = $('#copy_parent_struct').prop('checked');
                            if (copy) {
                                $('.field, .fformat, .flink').prop('disabled', true);
                            } else {
                                $('.field, .fformat, .flink').prop('disabled', false);
                            }
                        }
                        function toggleAutosearch(id){
                            fformat = $('#fformat'+id+' option:selected').val();
                            if (fformat == 'text') {
                                $('#flink'+id).prop('disabled', false).css('color', '');
                            } else {
                                $('#flink'+id).prop('disabled', true).css('color', '#CCC');
                            }
                        }
                    </script>
                    
                    <label>
                        <input type="checkbox" id="copy_parent_struct" name="copy_parent_struct" onchange="toggleFields()" value="1" />
                        <?php echo $_LANG['AD_COPY_PARENT_FEATURES'];?>
                    </label>
                    
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <thead>
                            <tr>
                                <th width="105"><?php echo $_LANG['AD_TYPE'];?></th>
                                <th><?php echo $_LANG['AD_TITLE'];?></th>
                                <th width="120"><?php echo $_LANG['AD_AUTOSEARCH'];?></th>
                            </tr>
                        </thead>
                        <?php for($f=0; $f<15; $f++) { ?>
                            <?php
                                if (isset($fstruct[$f])) {
                                    if (mb_strstr($fstruct[$f], '/~h~/')) {
                                        $ftype = 'html';
                                        $fstruct[$f] = str_replace('/~h~/', '', $fstruct[$f]);
                                    } else if (mb_strstr($fstruct[$f], '/~l~/')) {
                                        $ftype = 'link';
                                        $fstruct[$f] = str_replace('/~l~/', '', $fstruct[$f]);
                                    } else {
                                        $ftype = 'text';
                                    }

                                    if (mb_strstr($fstruct[$f], '/~m~/')) {
                                        $makelink = true;  $fstruct[$f] = str_replace('/~m~/', '', $fstruct[$f]);
                                    } else {
                                        $makelink = false;
                                    }
                                }
                            ?>
                            <tr>
                                <td style="padding-bottom:4px">
                                    <select name="fformat[]" class="form-control" id="fformat<?php echo $f;?>" style="width:100px" onchange="toggleAutosearch('<?php echo $f;?>');">
                                        <option value="text" <?php if(isset($fstruct[$f])) { if ($ftype == 'text') { echo 'selected'; } } ?>><?php echo $_LANG['AD_TEXT'];?></option>
                                        <option value="html" <?php if(isset($fstruct[$f])) { if ($ftype == 'html') { echo 'selected'; } } ?>><?php echo $_LANG['AD_HTML'];?></option>
                                        <option value="link" <?php if(isset($fstruct[$f])) { if ($ftype == 'link') { echo 'selected'; } } ?>><?php echo $_LANG['AD_LINK'];?></option>
                                    </select>
                                </td>
                                <td style="padding-bottom:4px">
                                    <input type="text" id="fstruct[]" class="form-control" style="width:99%;" name="fstruct[]" value="<?php if (isset($fstruct[$f])) { echo htmlspecialchars(stripslashes($fstruct[$f])); }?>" />
                                </td>
                                <td style="padding-bottom:2px">
                                    <div id="flink<?php echo $f; ?>" class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-default <?php if(isset($fstruct[$f])) { if ($makelink) { echo 'active'; } } ?>">
                                            <input type="radio" name="flink[<?php echo $f;?>]" <?php if(isset($fstruct[$f])) { if ($makelink) { echo 'checked="checked"'; } } ?> value="1"> <?php echo $_LANG['YES']; ?>
                                        </label>
                                        <label class="btn btn-default <?php if(isset($fstruct[$f])) { if (!$makelink) { echo 'active'; } } else { echo 'active'; } ?>">
                                            <input type="radio" name="flink[<?php echo $f;?>]" <?php if(isset($fstruct[$f])) { if (!$makelink) { echo 'checked="checked"'; } } else { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <script type="text/javascript">
                                toggleAutosearch('<?php echo $f;?>');
                            </script>
                        <?php } ?>
                    </table>
                </div>

                <div class="form-group">
                    <label><?php echo $_LANG['AD_MAKING_HTML_FIELDS'];?> <a href="index.php?view=filters" target="_blank"><?php echo $_LANG['AD_FILTERS'];?></a>?</label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php if(cmsCore::getArrVal($mod, 'filters', false)) { echo 'active'; } ?>">
                            <input type="radio" name="filters" <?php if(cmsCore::getArrVal($mod, 'filters', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                        </label>
                        <label class="btn btn-default <?php if (!cmsCore::getArrVal($mod, 'filters', false)) { echo 'active'; } ?>">
                            <input type="radio" name="filters" <?php if (!cmsCore::getArrVal($mod, 'filters', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $_LANG['AD_CAT_DESCRIPTION'];?></label>
                    <?php $inCore->insertEditor('description', $mod['description'], '200', '100%'); ?>
                </div>
            </td>

            <!-- боковая ячейка -->
            <td width="400">
                <div class="uitabs">
                    <ul>
                        <li><a href="#tab_publish"><?php echo $_LANG['AD_TAB_PUBLISH']; ?></a></li>
                        <li><a href="#tab_items"><?php echo $_LANG['AD_ITEMS']; ?></a></li>
                        <li><a href="#tab_access"><?php echo $_LANG['AD_TAB_ACCESS']; ?></a></li>
                    </ul>
                    
                    <div id="tab_publish">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="published" id="published" value="1" <?php if ($mod['published'] || $do=='add') { echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_IF_PUBLIC_CAT'];?>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <select class="form-control" style="height:200px" name="parent_id" size="8">
                                <?php $rootid = cmsCore::c('db')->get_field('cms_uc_cats', 'parent_id=0', 'id'); ?>
                                <option value="<?php echo $rootid; ?>" <?php if (cmsCore::getArrVal($mod, 'parent_id', $rootid) == $rootid) { echo 'selected'; }?>><?php echo $_LANG['AD_CATALOG_ROOT'];?></option>
                                <?php
                                    echo $inCore->getListItemsNS('cms_uc_cats', cmsCore::getArrVal($mod, 'parent_id', 0));
                                ?>
                            </select>
                            <select class="form-control" name="view_type">
                                <option value="list" <?php if (cmsCore::getArrVal($mod, 'view_type') == 'list') {echo 'selected="selected"';} ?>><?php echo $_LANG['AD_LIST'];?></option>
                                <option value="thumb" <?php if (cmsCore::getArrVal($mod, 'view_type') == 'thumb') {echo 'selected="selected"';} ?>><?php echo $_LANG['AD_GALERY'];?></option>
                                <option value="shop" <?php if (cmsCore::getArrVal($mod, 'view_type') == 'shop') {echo 'selected="selected"';} ?>><?php echo $_LANG['AD_SHOP'];?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_VIEW_RUBRIC'];?></label>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="showmore" value="1" <?php if (cmsCore::getArrVal($mod, 'showmore')) { echo 'checked="checked"'; } ?> />
                                    <?php echo $_LANG['AD_LINK_DETAILS'];?>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_ratings" value="1" <?php if (cmsCore::getArrVal($mod, 'is_ratings')) { echo 'checked="checked"'; } ?>/>
                                    <?php echo $_LANG['AD_ITEMS_RATING'];?>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="showtags" value="1" <?php if (cmsCore::getArrVal($mod, 'showtags')) { echo 'checked="checked"'; } ?>/>
                                    <?php echo $_LANG['AD_TAGS_VIEW'];?>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="showsort" value="1" <?php if (cmsCore::getArrVal($mod, 'showsort')) { echo 'checked="checked"'; } ?>/>
                                    <?php echo $_LANG['AD_SORT_VIEW'];?>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="showabc" value="1" <?php if (cmsCore::getArrVal($mod, 'showabc')) { echo 'checked="checked"'; } ?>/>
                                    <?php echo $_LANG['AD_ABC'];?>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab_items">
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_FIELDS_QUANTITY'];?></label>
                            <input type="number" class="form-control" name="fieldsshow" value="<?php echo cmsCore::getArrVal($mod, 'fields_show', 10); ?>"/>
                            <div class="help-block"><?php echo $_LANG['AD_HOW_MANY_FIELDS'];?></div>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['ORDER_ARTICLES'];?></label>
                            <select class="form-control" name="orderby">
                                <option value="title" <?php if(cmsCore::getArrVal($mod, 'orderby') == 'title') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_ALPHABET'];?></option>
                                <option value="pubdate" <?php if(cmsCore::getArrVal($mod, 'orderby') == 'pubdate') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_CALENDAR'];?></option>
                                <option value="rating" <?php if(cmsCore::getArrVal($mod, 'orderby') == 'rating') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_RATING'];?></option>
                                <option value="hits" <?php if(cmsCore::getArrVal($mod, 'orderby') == 'hits') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_BY_VIEWS'];?></option>
                            </select>
                            <select class="form-control" name="orderto">
                                <option value="desc" <?php if(cmsCore::getArrVal($mod, 'orderto') == 'desc') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_DECREMENT'];?></option>
                                <option value="asc" <?php if(cmsCore::getArrVal($mod, 'orderto') == 'asc') { echo 'selected'; } ?>><?php echo $_LANG['AD_BY_INCREMENT'];?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_HOW_MANY_ITEMS'];?></label>
                            <input type="number" class="form-control" name="perpage" value="<?php echo cmsCore::getArrVal($mod, 'perpage', 20); ?>"/>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_WHATS_NEW'];?></label>
                            <select class="form-control" name="shownew">
                                <option value="1" <?php if (cmsCore::getArrVal($mod, 'shownew', false)) { echo 'selected="selected"'; } ?>><?php echo $_LANG['YES'];?></option>
                                <option value="0" <?php if (!cmsCore::getArrVal($mod, 'shownew', false)) { echo 'selected="selected"'; } ?>><?php echo $_LANG['NO'];?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_HOW_LONG_TIME_NEW'];?></label>
                            <table width="100%">
                                <tr>
                                    <td width="100">
                                        <input type="number" class="form-control" name="int_1" value="<?php echo (int)cmsCore::getArrVal($mod, 'newint', 0); ?>"/>
                                    </td>
                                    <td>
                                        <select class="form-control" name="int_2">
                                            <option value="HOUR"  <?php if(mb_strstr(cmsCore::getArrVal($mod, 'newint', ''), 'HOUR')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['HOUR10'];?></option>
                                            <option value="DAY" <?php if(mb_strstr(cmsCore::getArrVal($mod, 'newint', ''), 'DAY')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['DAY10'];?></option>
                                            <option value="MONTH" <?php if(mb_strstr(cmsCore::getArrVal($mod, 'newint', ''), 'MONTH')) { echo 'selected="selected"'; } ?>><?php echo $_LANG['MONTH10'];?></option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div id="tab_access">
                        <div class="form-group">
                            <?php
                                if ($opt == 'edit_cat') {
                                    $sql2 = "SELECT * FROM cms_uc_cats_access WHERE cat_id = ". $mod['id'];
                                    $result2 = cmsCore::c('db')->query($sql2);
                                    $ord = array();

                                    if (cmsCore::c('db')->num_rows($result2)) {
                                        while ($r = cmsCore::c('db')->fetch_assoc($result2)) {
                                            $ord[] = $r['group_id'];
                                        }
                                    }
                                }
                            ?>
                            <label>
                                <input name="is_public" type="checkbox" id="is_public" onclick="checkGroupList()" value="1" <?php if (cmsCore::getArrVal($mod, 'is_public', false)){ echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_USERS_CAN_ADD_ITEM']; ?>
                            </label>
                            <div class="help-block"><?php echo $_LANG['AD_IF_ENABLE'];?></div>
                            
                            <div id="grp">
                                <label><?php echo $_LANG['AD_ALLOW_GROUPS'];?></label>
                                
                                <?php
                                    echo '<select id="showin" class="form-control" name="showfor[]" size="6" multiple="multiple" '.(cmsCore::getArrVal($mod, 'is_public', false) ? '' : 'disabled="disabled"').'>';

                                    $sql    = "SELECT * FROM cms_user_groups";
                                    $result = cmsCore::c('db')->query($sql) ;

                                    if (cmsCore::c('db')->num_rows($result)) {
                                        while ($item = cmsCore::c('db')->fetch_assoc($result)) {
                                            if ($item['alias'] != 'guest') {
                                                echo '<option value="'. $item['id'] .'"';
                                                if ($opt == 'edit_cat') {
                                                    if (in_array($item['id'], $ord)) {
                                                        echo 'selected="selected"';
                                                    }
                                                }

                                                echo '>';
                                                echo $item['title'].'</option>';
                                            }
                                        }
                                    }

                                    echo '</select>';
                                ?>
                                
                                <div class="help-block"><?php echo $_LANG['AD_SELECT_MULTIPLE_CTRL'];?></div>
                            </div>
                        </div>
                        
                        <?php if (IS_BILLING) { ?>
                        <div class="form-group">
                            <label><?php echo $_LANG['AD_ITEM_COST'];?></label>
                            <input type="text" class="form-control" name="cost" value="<?php echo $mod['cost']; ?>" /> <?php echo $_LANG['BILLING_POINT10'];?>
                            <div class="help-block"><?php echo $_LANG['AD_DEFAULT_COST'];?></div>
                        </div>
                        <?php } ?>
                        
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="can_edit" name="can_edit" value="1" <?php if(@$mod['can_edit']){ echo 'checked="checked"'; } ?> />
                                <?php echo $_LANG['AD_ALLOW_EDIT'];?>
                            </label>
                            <div class="help-block"><?php echo $_LANG['AD_IF_ALLOW_EDIT'];?></div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL'];?>" onclick="window.history.back();"/>
        
        <input name="opt" type="hidden" id="opt" <?php if ($opt == 'add_cat') { echo 'value="submit_cat"'; } else { echo 'value="update_cat"'; } ?> />
        <?php
            if ($opt == 'edit_cat') {
                echo '<input name="item_id" type="hidden" value="'.$mod['id'].'" />';
            }
        ?>
    </div>
</form>

 <?php
}

if ($opt == 'add_discount' || $opt == 'edit_discount') {
    if ($opt == 'add_discount') {
        echo '<h3>'. $_LANG['AD_COEFFICIENT_ADD'] .'</h3>';
        
        cpAddPathway($_LANG['AD_COEFFICIENT_ADD']);
        
        $mod = array();
    } else {
        $item_id = cmsCore::request('item_id', 'int', 0);
        
        $mod = cmsCore::c('db')->get_fields('cms_uc_discount', "id = '$item_id'", '*');
        
        if (!$mod) { cmsCore::error404(); }

        echo '<h3>'. $mod['title'] .'</h3>';
        cpAddPathway($_LANG['AD_COEFFICIENTS']);
        cpAddPathway($mod['title']);
    }
?>
<form id="addform" name="addform" method="post" action="index.php?view=components&do=config&id=<?php echo $id;?>">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['TITLE'];?>:</label>
            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars(cmsCore::getArrVal($mod, 'title', '')); ?>"/>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_CAT_BOARD'];?>:</label>
            <select class="form-control" name="cat_id">
                <?php $rootid = 0; ?>
                <option value="<?php echo $rootid; ?>" <?php if (cmsCore::getArrVal($mod, 'cat_id', $rootid) == $rootid) { echo 'selected="selected"'; }?>><?php echo $_LANG['AD_ALL_CAT'];?></option>
                <?php
                    echo $inCore->getListItemsNS('cms_uc_cats', cmsCore::getArrVal($mod, 'cat_id', 0));
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_TYPE'];?></label>
            <select id="sign" class="form-control" name="sign" onchange="toggleDiscountLimit()">
                <option value="-1" <?php if (cmsCore::getArrVal($mod, 'sign') == -1) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PRODUCT_DISCOUNT'];?>)</option>
                <option value="1" <?php if (cmsCore::getArrVal($mod, 'sign') == 1) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PRODUCT_ALLOWANCE'];?>)</option>
                <option value="2" <?php if (cmsCore::getArrVal($mod, 'sign') == 2) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_ORDER_ALLOWANCE'];?></option>
                <option value="3" <?php if (cmsCore::getArrVal($mod, 'sign') == 3) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_ORDER_DISCOUNT'];?></option>
            </select>
        </div>
        
        <div class="if_limit form-group" <?php if(cmsCore::getArrVal($mod, 'sign') != 3){ echo 'style="display:none;"'; } ?>>
            <label><?php echo $_LANG['AD_MIN_COST'];?> (<?php echo $_LANG['CURRENCY'];?>)</label>
            <input type="number" id="value" class="form-control" name="if_limit" size="5" value="<?php echo cmsCore::getArrVal($mod, 'if_limit', 0); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_UNITS'];?>:</label>
            <select id="unit" class="form-control" name="unit" >
                <option value="%" <?php if (cmsCore::getArrVal($mod, 'unit') == '%') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_PERCENT'];?></option>
                <option value="<?php echo $_LANG['CURRENCY'];?>" <?php if (cmsCore::getArrVal($mod, 'unit') == $_LANG['CURRENCY']) { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_CURRENCY_NAME'];?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_VALUE'];?>:</label>
            <input type="text" id="value" class="form-control" name="value" size="5" value="<?php echo cmsCore::getArrVal($mod, 'value', ''); ?>" />
        </div>
    </div>

    <div>
        <input type="submit" class="btn btn-primary" name="add_mod" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back3" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';" />
        
        <input name="opt" type="hidden" id="do" <?php if ($opt=='add_discount') { echo 'value="submit_discount"'; } else { echo 'value="update_discount"'; } ?> />
        <?php
        if ($opt == 'edit_discount') {
            echo '<input name="item_id" type="hidden" value="'.$mod['id'].'" />';
        }
        ?>
    </div>
</form>
 <?php
}

if ($opt == 'saveconfig') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $cfg = array();

    $cfg['email']       = cmsCore::request('email', 'str', '');
    $cfg['delivery']    = cmsCore::request('delivery', 'str', '');
    $cfg['notice']      = cmsCore::request('notice', 'int', 0);
    $cfg['premod']      = cmsCore::request('premod', 'int', 1);
    $cfg['premod_msg']  = cmsCore::request('premod_msg', 'int', 1);
    $cfg['is_comments'] = cmsCore::request('is_comments', 'int', 0);
    $cfg['is_rss']      = cmsCore::request('is_rss', 'int', 1);
    $cfg['watermark']   = cmsCore::request('watermark', 'int', 1);
    $cfg['small_size']  = cmsCore::request('small_size', 'int', 100);
    $cfg['medium_size'] = cmsCore::request('medium_size', 'int', 250);

    $inCore->saveComponentConfig('catalog', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);
    cpCheckWritable('/images/catalog', 'folder');
    cpCheckWritable('/images/catalog/medium', 'folder');
    cpCheckWritable('/images/catalog/small', 'folder');
?>
<form action="index.php?view=components&do=config&id=<?php echo $id; ?>" method="post" name="addform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:600px;">
        <div class="form-group">
            <label><?php echo $_LANG['AD_SELLER_EMAIL']; ?></label>
            <input type="text" class="form-control" name="email" value="<?php echo cmsCore::getArrVal($cfg, 'email', ''); ?>" />
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_USER_NOTICE']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'notice', false)) { echo 'active'; } ?>">
                    <input type="radio" name="notice" <?php if(cmsCore::getArrVal($cfg, 'notice', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'notice', false)) { echo 'active'; } ?>">
                    <input type="radio" name="notice" <?php if (!cmsCore::getArrVal($cfg, 'notice', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_USERS_MODERATION']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'premod', false)) { echo 'active'; } ?>">
                    <input type="radio" name="premod" <?php if(cmsCore::getArrVal($cfg, 'premod', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'premod', false)) { echo 'active'; } ?>">
                    <input type="radio" name="premod" <?php if (!cmsCore::getArrVal($cfg, 'premod', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ABOUT_NEW_ITEM']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'premod_msg', false)) { echo 'active'; } ?>">
                    <input type="radio" name="premod_msg" <?php if(cmsCore::getArrVal($cfg, 'premod_msg', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'premod_msg', false)) { echo 'active'; } ?>">
                    <input type="radio" name="premod_msg" <?php if (!cmsCore::getArrVal($cfg, 'premod_msg', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label style="max-width:450px;"><?php echo $_LANG['AD_AUTOCOMENT']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'is_comments', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comments" <?php if(cmsCore::getArrVal($cfg, 'is_comments', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'is_comments', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_comments" <?php if (!cmsCore::getArrVal($cfg, 'is_comments', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ENABLE_WATERMARK']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                    <input type="radio" name="watermark" <?php if(cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'active'; } ?>">
                    <input type="radio" name="watermark" <?php if (!cmsCore::getArrVal($cfg, 'watermark', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_IF_PUT_IMAGE']; ?> "<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_MEDIUM_SIZE']; ?></label>
            <input type="number" class="form-control" name="medium_size" value="<?php echo cmsCore::getArrVal($cfg, 'medium_size', ''); ?>"/>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_SMALL_SIZE']; ?></label>
            <input type="number" class="form-control" name="small_size" value="<?php echo cmsCore::getArrVal($cfg, 'small_size', ''); ?>"/>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_VIEW_RSS_ICON']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default <?php if(cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_rss" <?php if(cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'checked="checked"'; } ?> value="1"> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default <?php if (!cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'active'; } ?>">
                    <input type="radio" name="is_rss" <?php if (!cmsCore::getArrVal($cfg, 'is_rss', false)) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ABOUT_DELIVERY']; ?></label>
            <textarea class="form-control" style="height:150px;" name="delivery"><?php echo cmsCore::getArrVal($cfg, 'delivery', ''); ?></textarea>
        </div>
    </div>
    <div>
        <input name="opt" type="hidden" value="saveconfig" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
    </div>
</form>
<?php
}

if ($opt == 'import_xls') {
    cpAddPathway($_LANG['AD_EXCEL_IMPORT']);
    echo '<h3>'. $_LANG['AD_EXCEL_IMPORT'] .'</h3>';

    if (cmsCore::inRequest('cat_id')) {
        $cat_id = cmsCore::request('cat_id', 'int', 0);
        $cat = cmsCore::c('db')->get_fields('cms_uc_cats', "id = '$cat_id'", '*');
        if (!$cat) { cmsCore::error404(); }
        $fstruct = cmsCore::yamlToArray($cat['fieldsstruct']);
?>
<form action="index.php?view=components&do=config&id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" name="addform">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:650px;">
        <p><strong><?php echo $_LANG['AD_CAT_BOARD']; ?>:</strong> <a href="index.php?view=components&do=config&id=<?php echo $id; ?>&opt=import_xls"><?php echo $cat['title']; ?></a></p>
        <p><?php echo $_LANG['AD_CHECK_EXCEL_FILE']; ?></p>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_EXCEL_FILE']; ?></label>
            <input type="file" class="form-control" name="xlsfile" />
            <div class="help-block"><?php echo $_LANG['AD_XLS_EXTENTION']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ENCODING']; ?></label>
            <select class="form-control" name="charset">
                <option value="cp1251" selected><?php echo $_LANG['AD_CP1251']; ?></option>
                <option value="UTF-8"><?php echo $_LANG['AD_UTF8']; ?></option>
            </select>
            <div class="help-block"><?php echo $_LANG['AD_SOFTWARE']; ?></div>
        </div>
        
        <table class="table">
            <tr>
                <td>
                    <label><?php echo $_LANG['AD_LINE_QUANTITY']; ?> (<?php echo $_LANG['AD_PIECES']; ?>)</label>
                    <div class="help-block"><?php echo $_LANG['AD_PRESCRIPTION']; ?></div>
                </td>
                <td width="100"><input type="number" class="form-control" name="xlsrows" /></td>
            </tr>
            <tr>
                <td>
                    <label><?php echo $_LANG['AD_LINE_QUANTITY']; ?> (<?php echo $_LANG['AD_LIST_NUMBER']; ?>)</label>
                </td>
                <td><input type="number" class="form-control" name="xlslist" value="1" /></td>
            </tr>
        </table>
        
        <p><?php echo $_LANG['AD_DATA_NOTE_INFO']; ?></p>
        
        <table class="table">
            <tr id="row_title">
                <td>
                    <label><?php echo $_LANG['TITLE']; ?>:</label>
                </td>
                <td width="80"><?php echo $_LANG['AD_COLUMN'];?>:</td>
                <td width="90">
                    <input type="number" id="title_col" class="form-control" onkeyup="xlsEditCol()" name="cells[title][col]" />
                </td>
                <td width="80"><?php echo $_LANG['AD_LINE'];?>:</td>
                <td width="90">
                    <input type="number" id="title_row" class="form-control" onkeyup="xlsEditRow()" name="cells[title][row]" />
                </td>
                <td width="90">
                    <label><input type="checkbox" id="ignore_title" name="cells[title][ignore]" onclick="ignoreRow('title')" value="1"/> <?php echo $_LANG['AD_TEXT']; ?>: </label>
                </td>
                <td width="200">
                    <input type="text" id="other_title" class="form-control" name="cells[title][other]"disabled="disabled" />
                </td>
            </tr>
            
            <?php
                $current = 0;
                foreach($fstruct as $key=>$value) {
                    //strip special markups
                    if (mb_strstr($value, '/~h~/')) {
                        $value=str_replace('/~h~/', '', $value);
                    } else if (mb_strstr($value, '/~l~/')) {
                        $value=str_replace('/~l~/', '', $value);
                    } else {
                        $ftype='text';
                    }
                    if (mb_strstr($value, '/~m~/')) { $value=str_replace('/~m~/', '', $value); }
                    //show field inputs
                    ?>
                        <tr id="row_<?php echo $current; ?>">
                            <td><label><?php echo stripslashes($value); ?>:</label></td>
                            <td><?php echo $_LANG['AD_COLUMN'];?>:</td>
                            <td><input type="number" class="form-control" id="<?php echo $current; ?>" name="cells[<?php echo $current; ?>][col]" /></td>
                            <td><?php echo $_LANG['AD_LINE']; ?>:</td>
                            <td><input type="number" class="form-control" name="cells[<?php echo $current; ?>][row]" /></td>
                            <td><label><input type="checkbox" id="ignore_<?php echo $current; ?>" name="cells[<?php echo $current; ?>][ignore]" onclick="ignoreRow('<?php echo $current; ?>')" value="1" /> <?php echo $_LANG['AD_TEXT']; ?>: </label></td>
                            <td><input type="text" id="other_<?php echo $current; ?>" class="form-control" name="cells[<?php echo $current; ?>][other]" disabled="disabled" /></td>
                        </tr>
                    <?php
                    $current++;
                }

                if ($cat['view_type']=='shop'){
                    ?>
                        <tr id="row_price">
                            <td width=""><label><?php echo $_LANG['PRICE'];?>:</label></td>
                            <td><?php echo $_LANG['AD_COLUMN'];?>:</td>
                            <td><input type="number" class="form-control" name="cells[price][col]" /></td>
                            <td><?php echo $_LANG['AD_LINE']; ?>:</td>
                            <td><input type="number" class="form-control" name="cells[price][row]" /></td>
                            <td><label><input type="checkbox" id="ignore_price" name="cells[price][ignore]" onclick="ignoreRow('price')" value="1" /> <?php echo $_LANG['AD_TEXT']; ?>: </label></td>
                            <td><input type="text" id="other_price" class="form-control" name="cells[price][other]" disabled="disabled" /></td>
                        </tr>
                    <?php
                }
            ?>
        </table>
        
        <p><?php echo $_LANG['AD_OTHER_PARAMETRS']; ?>:</p>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ITEM_PUBLIC']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default active">
                    <input type="radio" name="published" checked="checked" value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="published" value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_ITEM_VIEW']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ALLOW_COMENTS']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default active">
                    <input type="radio" name="is_comments" checked="checked" value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="is_comments" value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
        </div>
        
        <?php if ($cat['view_type'] == 'shop') { ?>
        <div class="form-group">
            <label><?php echo $_LANG['CAN_MANY']; ?></label>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
                <label class="btn btn-default active">
                    <input type="radio" name="canmany" checked="checked" value="1" /> <?php echo $_LANG['YES']; ?>
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="canmany" value="0" /> <?php echo $_LANG['NO']; ?>
                </label>
            </div>
            <div class="help-block"><?php echo $_LANG['AD_PRODUCT_ORDER']; ?></div>
        </div>
        <?php } ?>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_ITEMS_TAGS']; ?></label>
            <input type="text" class="form-control" name="tags" />
            <div class="help-block"><?php echo $_LANG['AD_NOT_NECESSARILY']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_IMG_FILE']; ?></label>
            <input type="file" class="form-control" name="imgfile" />
            <div class="help-block"><?php echo $_LANG['AD_NOT_NECESSARILY']; ?></div>
        </div>
        
        <div class="form-group">
            <label><?php echo $_LANG['AD_USER']; ?></label>
            <select class="form-control" name="user_id">
                <?php echo cmsUser::getUsersList(); ?>
            </select>
            <div class="help-block"><?php echo $_LANG['AD_USER_ALIAS']; ?></div>
        </div>
    </div>

    <div>
        <input name="cat_id" type="hidden" id="cat_id" value="<?php echo $cat_id; ?>" />
        <input name="opt" type="hidden" id="opt" value="go_import_xls" />
        
        <input type="submit" name="save" class="btn btn-primary" value="<?php echo $_LANG['AD_IMPORT']; ?>" />
        <input type="button" name="back" class="btn btn-default" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.go(-1);" />
    </div>
</form>
<?php
    } else {
        echo '<h4>'.$_LANG['AD_CHECK_RUBRIC'].'</h4>';

        $sql = "SELECT id, title, NSLeft, NSLevel, parent_id
                FROM cms_uc_cats
                WHERE parent_id > 0
                ORDER BY NSLeft";
        $result = cmsCore::c('db')->query($sql);

        if (cmsCore::c('db')->num_rows($result)){
            echo '<div style="padding:10px">';
                while ($cat = cmsCore::c('db')->fetch_assoc($result)) {
                    echo '<div style="padding:2px;padding-left:18px;margin-left:'.(($cat['NSLevel']-1)*15).'px;">
                            <span class="fa fa-folder"></span>
                            <a href="?view=components&do=config&id='.$id.'&opt=import_xls&cat_id='.$cat['id'].'">'.$cat['title'].'</a>
                          </div>';
                }
            echo '</div>';
        }

    }

}
<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
/******************************************************************************/
//                           InstantCMS v1.10.5                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
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
    if (!cmsCore::inRequest('item')) {
        if (cmsCore::inRequest('item_id')) {
            cmsCore::c('db')->setFlag('cms_uc_items', cmsCore::request('item_id', 'int', 0), 'published', '1');
            cmsCore::c('db')->setFlag('cms_uc_items', cmsCore::request('item_id', 'int', 0), 'on_moderate', '0');
        }
        cmsCore::halt('1');
    } else {
        $item = cmsCore::request('item', 'array_int', array());
        
        cmsCore::c('db')->setFlags('cms_uc_items', $item, 'published', '1');
        
        foreach ($item as $k => $id) {
            cmsCore::c('db')->query('UPDATE cms_uc_items SET on_moderate = 0 WHERE id='. $id);
        }
        
        cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_item') {
    if (!cmsCore::inRequest('item')) {
        if (cmsCore::inRequest('item_id')) {
            cmsCore::c('db')->setFlag('cms_uc_items', cmsCore::request('item_id', 'int', 0), 'published', '0');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_uc_items', cmsCore::request('item', 'array_int', array()), 'published', '0');
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
    $cat['pagetitle']      = cmsCore::request('pagetitle', 'str', '');
    $cat['meta_desc']      = cmsCore::request('meta_desc', 'str', '');
    $cat['meta_keys']      = cmsCore::request('meta_keys', 'str', '');
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
    cpAddPathway($_LANG['ADD_ITEM']);

    $sql = "SELECT id, title, NSLeft, NSLevel, parent_id
            FROM cms_uc_cats
            WHERE parent_id > 0
            ORDER BY NSLeft";
    $result = cmsCore::c('db')->query($sql);
    
    $cats = array();

    if (cmsCore::c('db')->num_rows($result)) {
        while ($cat = cmsCore::c('db')->fetch_assoc($result)) {
            $cats = $cat;
        }
    }
    
    cmsCore::c('page')->initTemplate('components', 'catalog_add_item')->
        assign('cats', $cats)->
        display();
}

if ($opt == 'add_cat' || $opt == 'edit_cat') {
    $ord = array();
    
    if ($opt == 'add_cat') {
        cpAddPathway($_LANG['AD_NEW_CAT']);
        
        $mod = array();
        $fstruct = array();
    } else {
        $item_id = cmsCore::request('item_id', 'int', 0);
        
        $mod = cmsCore::c('db')->get_fields('cms_uc_cats', "id = '$item_id'", '*');
        
        if (!$mod) { cmsCore::error404(); }
        
        $fstruct = cmsCore::yamlToArray($mod['fieldsstruct']);
        
        cpAddPathway($mod['title']);
        
        $result2 = cmsCore::c('db')->query("SELECT * FROM cms_uc_cats_access WHERE cat_id = ". $mod['id']);

        if (cmsCore::c('db')->num_rows($result2)) {
            while ($r = cmsCore::c('db')->fetch_assoc($result2)) {
                $ord[] = $r['group_id'];
            }
        }
    }
    
    $sql    = "SELECT * FROM cms_user_groups";
    $result = cmsCore::c('db')->query($sql) ;
    $groups = array();

    if (cmsCore::c('db')->num_rows($result)) {
        while ($item = cmsCore::c('db')->fetch_assoc($result)) {
            $groups[] = $item;
        }
    }
    
    cmsCore::c('page')->initTemplate('components', 'catalog_add_cat')->
        assign('is_billing', IS_BILLING)->
        assign('opt', $opt)->
        assign('fstruct', $fstruct)->
        assign('rootid', cmsCore::c('db')->get_field('cms_uc_cats', 'parent_id=0', 'id'))->
        assign('uc_cats_opt', $inCore->getListItemsNS('cms_uc_cats', cmsCore::getArrVal($mod, 'parent_id', 0)))->
        assign('groups', $groups)->
        assign('ord', $ord)->
        assign('mod', $mod)->
        display();
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
    
    cmsCore::c('page')->initTemplate('components', 'catalog_add_discount')->
        assign('opt', $opt)->
        assign('rootid', cmsCore::c('db')->get_field('cms_uc_cats', 'parent_id=0', 'id'))->
        assign('uc_cats_opt', $inCore->getListItemsNS('cms_uc_cats', cmsCore::getArrVal($mod, 'cat_id', 0)))->
        assign('mod', $mod)->
        display();
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
    $cfg['meta_keys']   = cmsCore::request('meta_keys', 'str', '');
    $cfg['meta_desc']   = cmsCore::request('meta_desc', 'str', '');

    $inCore->saveComponentConfig('catalog', $cfg);

    cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');
    cmsCore::redirectBack();
}

if ($opt == 'config') {
    cpAddPathway($_LANG['AD_SETTINGS']);
    cpCheckWritable('/images/catalog', 'folder');
    cpCheckWritable('/images/catalog/medium', 'folder');
    cpCheckWritable('/images/catalog/small', 'folder');
    
    cmsCore::c('page')->initTemplate('components', 'catalog_config')->
        assign('cfg', $cfg)->
        display();
}

if ($opt == 'import_xls') {
    cpAddPathway($_LANG['AD_EXCEL_IMPORT']);

    $tpl = cmsCore::c('page')->initTemplate('components', 'catalog_import_xls')->
        assign('is_cat_id', cmsCore::inRequest('cat_id'));
    
    if (cmsCore::inRequest('cat_id')) {
        $cat_id = cmsCore::request('cat_id', 'int', 0);
        $cat = cmsCore::c('db')->get_fields('cms_uc_cats', "id = '". $cat_id ."'", '*');
        if (!$cat) { cmsCore::error404(); }
        
        $tpl->assign('cat_id', $cat_id)->
            assign('cat', $cat)->
            assign('fstruct', cmsCore::yamlToArray($cat['fieldsstruct']))->
            assign('users_opt', cmsUser::getUsersList());
    } else {
        $cats = array();
        
        $sql = "SELECT id, title, NSLeft, NSLevel, parent_id
                FROM cms_uc_cats
                WHERE parent_id > 0
                ORDER BY NSLeft";
        
        $result = cmsCore::c('db')->query($sql);
        
        if (cmsCore::c('db')->num_rows($result)) {
            while ($cat = cmsCore::c('db')->fetch_assoc($result)) {
                $cats[] = $cat;
            }
        }
        
        $tpl->assign('cats', $cats);
    }

    $tpl->display();
}
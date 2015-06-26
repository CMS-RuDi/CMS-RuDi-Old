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

//============================================================================//

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
    if (!cmsCore::inRequest('item')) {
        if (cmsCore::inRequest('item_id')) {
            cmsCore::c('db')->setFlag('cms_banners', cmsCore::request('item_id', 'int', 0), 'published', '1');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_banners', cmsCore::request('item_id', 'array_int', array()), 'published', '1');
        cmsCore::redirectBack();
    }
}

if ($opt == 'hide_banner') {
    if (!cmsCore::inRequest('item')) {
        if (cmsCore::inRequest('item_id')) {
            cmsCore::c('db')->setFlag('cms_banners', cmsCore::request('item_id', 'int', 0), 'published', '0');
        }
        cmsCore::halt('1');
    } else {
        cmsCore::c('db')->setFlags('cms_banners', cmsCore::request('item_id', 'array_int', array()), 'published', '0');
        cmsCore::redirectBack();
    }
}

if ($opt == 'submit' || $opt == 'update') {
    if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

    $item_id   = cmsCore::request('item_id', 'int', 0);

    $title     = cmsCore::request('title', 'str', $_LANG['AD_UNTITLED_BANNER']);
    $link      = cmsCore::request('b_link', 'str');
    $typeimg   = cmsCore::request('typeimg', 'str');
    $maxhits   = cmsCore::request('maxhits', 'int');
    $maxuser   = 0;
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
                cmsCore::c('db')->insert('cms_banners', array(
                    'position'  => cmsCore::c('db')->escape_string($position),
                    'typeimg'   => cmsCore::c('db')->escape_string($typeimg),
                    'fileurl'   => $filename,
                    'hits'      => 0,
                    'clicks'    => 0,
                    'maxhits'   => $maxhits,
                    'maxuser'   => $maxuser,
                    'user_id'   => 1,
                    'pubdate'   => date('Y-m-d H:i:s'),
                    'title'     => cmsCore::c('db')->escape_string($title),
                    'link'      => cmsCore::c('db')->escape_string($link),
                    'published' => $published
                ));

                cmsCore::redirect('?view=components&do=config&opt=list&id='.$id);
            } else {
                $fileurl = cmsCore::c('db')->get_field('cms_banners', "id = '". $item_id ."'", 'fileurl');
                unlink($uploaddir.$fileurl);

                cmsCore::c('db')->update('cms_banners', array('fileurl' => $filename), $item_id);
                cmsCore::c('db')->query($sql) ;
            }
        } else {
            cmsCore::addSessionMessage($_LANG['AD_ADDING_ERROR_TEXT'].cmsCore::uploadError(), 'error');
            cmsCore::redirectBack();
        }

    } else if ($opt == 'submit') {
        cmsCore::addSessionMessage($_LANG['AD_ADDING_ERROR_TEXT'], 'error');
        cmsCore::redirectBack();
    }

    if ($opt == 'update') {
        cmsCore::c('db')->update('cms_banners',
            array(
                'position'  => cmsCore::c('db')->escape_string($position),
                'title'     => cmsCore::c('db')->escape_string($title),
                'published' => $published,
                'maxhits'   => $maxhits,
                'maxuser'   => $maxuser,
                'typeimg'   => cmsCore::c('db')->escape_string($typeimg),
                'link'      => cmsCore::c('db')->escape_string($link)
            )
        , $item_id);

        if (count(cmsCore::getArrVal($_SESSION, 'editlist', array())) == 0) {
            cmsCore::redirect('?view=components&do=config&opt=list&id='. $id);
        } else {
            cmsCore::redirect('?view=components&do=config&opt=edit&id='. $id);
        }
    }
}

if ($opt == 'delete') {
    $item_id = cmsCore::request('item_id', 'int', 0);

    $fileurl = cmsCore::c('db')->get_field('cms_banners', "id = '". $item_id ."'", 'fileurl');
    
    if (!$fileurl) { cmsCore::error404(); }
    
    @unlink($uploaddir.$fileurl);

    cmsCore::c('db')->delete('cms_banners', "id='". $item_id ."'", 1);
    cmsCore::c('db')->delete('cms_banner_hits', "banner_id='". $item_id ."'");

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
            if (sizeof($_SESSION['editlist']) == 0) {
               unset($_SESSION['editlist']);
            } else {
               $ostatok = '('.$_LANG['AD_NEXT_IN'].sizeof($_SESSION['editlist']).')';
            }
        } else {
            $item_id = cmsCore::request('item_id', 'int', 0);
        }

        $mod = cms_model_banners::getBanner($item_id);
        if (!$mod) { cmsCore::error404(); }

        cpAddPathway($mod['title']);
    }
    
    $tpl = cmsCore::c('page')->initTemplate('components', 'banners_add')->
            assign('id', $id)->
            assign('opt', $opt)->
            assign('mod', $mod);
        
    if ($opt == 'edit') {
        $tpl->assign('banner_html', cms_model_banners::getBannerById($item_id));
    }

    $tpl->display();
}
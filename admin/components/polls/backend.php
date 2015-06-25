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

$opt = cmsCore::request('opt', 'str', 'list');

cmsCore::loadModel('polls');
$model = new cms_model_polls();

$toolmenu = array();

if ($opt == 'list') {
    $toolmenu[] = array( 'icon' => 'new.gif', 'title' => $_LANG['AD_ADD_POLL'], 'link' => '?view=components&do=config&id='. $id .'&opt=add' );
} else {
    $toolmenu[] = array( 'icon' => 'save.gif', 'title' => $_LANG['SAVE'], 'link' => 'javascript:document.addform.submit();' );
    $toolmenu[] = array( 'icon' => 'cancel.gif', 'title' => $_LANG['CANCEL'], 'link' => '?view=components&do=config&id='. $id );
}

cpToolMenu($toolmenu);

if ($opt == 'list') {
    $fields = array(
        array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
        array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'filter' => '15', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&poll_id=%id%' ),
        array( 'title' => $_LANG['DATE'], 'field' => 'pubdate', 'width' => '110', 'prc' => array('cmsCore', 'dateFormat') )
    );
    
    $actions = array(
        array( 'title' => $_LANG['EDIT'], 'icon' => 'edit.gif', 'link' => '?view=components&do=config&id='. $id .'&opt=edit&poll_id=%id%' ),
        array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'confirm' => $_LANG['AD_DELETE_POLL'], 'link' => '?view=components&do=config&id='. $id .'&opt=delete&poll_id=%id%' )
    );
    
    cpListTable('cms_polls', $fields, $actions);
}

if ($opt == 'submit') {
    function setupAnswers($answers_title) {
        $answers = array();
        
        foreach ($answers_title as $answer) {
            if ($answer) { $answers[$answer] = 0; }
        }
        
        return cmsCore::arrayToYaml($answers);
    }
    
    $types = array(
        'title' => array( 'title', 'str', '' ),
        'answers' => array( 'answers', 'array_str', array(), 'setupAnswers' )
    );
    
    $items = cmsCore::getArrayFromRequest($types);
    
    cmsCore::c('db')->insert('cms_polls', $items);
    
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id);
}

if ($opt == 'delete') {
    $model->deletePoll(cmsCore::request('poll_id', 'int'));
    
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id);
}

if ($opt == 'update') {
    $poll_id       = cmsCore::request('poll_id', 'int');
    $item['title'] = cmsCore::request('title', 'str', '');
    $answers_title = cmsCore::request('answers', 'array_str');
    $nums          = cmsCore::request('num', 'array_int');
    
    $is_clear      = cmsCore::request('is_clear', 'int');
    
    if($is_clear){
        cmsCore::c('db')->delete('cms_polls_log', "poll_id = '$poll_id'");
    }
    
    $answers = array();
    
    foreach ($answers_title as $key => $answer) {
        if ($answer) {
            if (isset($nums[$key]) && !$is_clear) {
                $answers[$answer] = $nums[$key];
            } else {
                $answers[$answer] = 0;
            }
        }
    }
    
    $item['answers'] = cmsCore::arrayToYaml($answers);
    
    cmsCore::c('db')->update('cms_polls', $item, $poll_id);
    
    cmsCore::addSessionMessage($_LANG['AD_DO_SUCCESS'], 'success');
    cmsCore::redirect('?view=components&do=config&id='. $id);
}

if ($opt == 'add' || $opt == 'edit') {
    $answers_title = array();
    $answers_num   = array();
    $mod = array();
    
    if ($opt == 'add') {
        cpAddPathway($_LANG['AD_ADD_POLL']);
    } else {
        $mod = $model->getPoll(cmsCore::request('poll_id', 'int'));
        if (!$mod) { cmsCore::error404(); }
        
        cpAddPathway($_LANG['AD_EDIT_POLL']);

        $item = 1;
        foreach ($mod['answers'] as $answer => $num) {
            $answers_title[$item] = htmlspecialchars($answer);
            $answers_num[$item]   = $num;
            $item++;
        }
    }

    cmsCore::c('page')->initTemplate('components', 'polls_add')->
        assign('answers_title', $answers_title)->
        assign('answers_num', $answers_num)->
        assign('mod', $mod)->
        display();
}
<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.9                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/
Error_Reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

define('PATH', $_SERVER['DOCUMENT_ROOT']);

if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { die(); }

header('Content-Type: text/html; charset=utf-8');

session_start();

define('VALID_CMS', 1);

include(PATH .'/core/cms.php');

if (!cmsCore::c('user')->update()) { cmsCore::halt(); }

//==============================================================================

$do = cmsCore::request('do', array('ticket_closed', 'add_msg'), '', 'get');

if (empty($do)) { cmsCore::error404(); }

$ticket_id = cmsCore::request('ticket_id', 'int', 0, 'post');
$secret_key = cmsCore::request('secret_key', 'str', 0, 'post');

$ticket = cmsCore::c('db')->get_fields('cms_ticket', "`id`='". $ticket_id ."' AND `secret_key`='". cmsCore::c('db')->escape_string($secret_key) ."'", '*');

if (empty($ticket)) { cmsCore::error404(); }


if ($do == 'ticket_closed') {
    cmsCore::c('db')->setFlag('cms_ticket', $ticket['id'], 'status', 3);
    cmsUser::sendMessage(-1, $ticket['user_id'], $_LANG['AD_SUPPORT_CLOSE_TICKET']);
}

if ($do == 'add_msg') {
    $msg = cmsCore::request('msg', 'str', '', 'post');
    $support = cmsCore::request('support', 'str', 'Support', 'post');
    $date = date('Y-m-d H:i:s');
    
    $msg_id = cmsCore::c('db')->insert(
        'cms_ticket_msg',
        array(
            'ticket_id' => $ticket['id'],
            'msg' => cmsCore::c('db')->escape_string($msg),
            'support' => $support,
            'pubdate' => $date
        )
    );
    
    if ($msg_id) {
        cmsCore::c('db')->query("UPDATE `cms_ticket` SET `last_msg_date` = '". $date ."', `msg_count` = `msg_count` + 1 WHERE `id` = '". $ticket['id'] ."'");
        cmsUser::sendMessage(-1, $ticket['user_id'], sprintf($_LANG['AD_SUPPORT_NEW_MSG'], $ticket['title'], '/admin/index.php?view=tickets&do=view&id='. $ticket['id']));
    }
}
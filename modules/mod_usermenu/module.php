<?php
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

function mod_usermenu($module_id, $cfg){
    $inCore = cmsCore::getInstance();

    $is_billing = $inCore->isComponentInstalled('billing');
    $is_audio   = $inCore->isComponentInstalled('audio');
    $is_video   = $inCore->isComponentInstalled('video');
    $is_music   = $inCore->isComponentInstalled('music');

    cmsPage::initTemplate('modules', 'mod_usermenu')->
        assign('avatar', cmsCore::c('user')->imageurl)->
        assign('nickname', cmsCore::c('user')->nickname)->
        assign('login', cmsCore::c('user')->login)->
        assign('id', cmsCore::c('user')->id)->
        assign('newmsg', cmsCore::c('user')->getNewMsg())->
        assign('is_can_add', cmsUser::isUserCan('content/add'))->
        assign('is_admin', cmsCore::c('user')->is_admin)->
        assign('is_editor', cmsUser::userIsEditor())->
        assign('cfg', $cfg)->
        assign('users_cfg', $inCore->loadComponentConfig('users'))->

        assign('is_billing', $is_billing)->
        assign('is_audio', $is_audio)->
        assign('is_video', $is_video)->
        assign('is_music', $is_music)->
        assign('audio_count', $is_audio ? cmsCore::c('user')->audio_count : 0)->
        assign('video_count', $is_video ? cmsCore::c('user')->video_count : 0)->
        assign('music_count', $is_music ? cmsCore::c('user')->music_count : 0)->
        assign('balance', $is_billing ? cmsCore::c('user')->balance : 0)->
            
        assign('iframe_provider', cmsCore::c('config')->iframe_enable ? (!empty(cmsCore::c('config')->iframe_session_id) ? cmsCore::c('user')->iframe_provider : false) : false)->

        display();

    return true;
}
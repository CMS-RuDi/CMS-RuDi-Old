<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.3                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2013                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

function mod_usermenu($module_id, $cfg){

	$inCore = cmsCore::getInstance();
	$inUser = cmsUser::getInstance();

	$is_billing = $inCore->isComponentInstalled('billing');
	$is_audio   = $inCore->isComponentInstalled('audio');
	$is_video   = $inCore->isComponentInstalled('video');

	cmsPage::initTemplate('modules', 'mod_usermenu')->
            assign('avatar', $inUser->imageurl)->
            assign('nickname', $inUser->nickname)->
            assign('login', $inUser->login)->
            assign('id', $inUser->id)->
            assign('newmsg', cmsUser::getNewMessages($inUser->id))->
            assign('is_can_add', cmsUser::isUserCan('content/add'))->
            assign('is_admin', $inUser->is_admin)->
            assign('is_editor', cmsUser::userIsEditor())->
            assign('cfg', $inCore->loadModuleConfig($module_id))->
            assign('users_cfg', $inCore->loadComponentConfig('users'))->
            assign('is_billing', $is_billing)->
            assign('is_audio', $is_audio)->
            assign('is_video', $is_video)->
            assign('audio_count', $is_audio ? $inUser->audio_count : 0)->
            assign('video_count', $is_video ? $inUser->video_count : 0)->
            assign('balance', $is_billing ? $inUser->balance : 0)->
            display('mod_usermenu.tpl');

	return true;

}
?>
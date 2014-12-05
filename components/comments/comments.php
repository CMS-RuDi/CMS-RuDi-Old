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

	define('PATH', $_SERVER['DOCUMENT_ROOT']);
	include(PATH.'/core/ajax/ajax_core.php');

    cmsCore::loadModel('comments');
    $model = new cms_model_comments();
    // Проверяем включен ли компонент
    if(!$inCore->isComponentEnable('comments')) { cmsCore::halt(); }
	// Инициализируем права доступа для группы текущего пользователя
	$model->initAccess();

    $target    = cmsCore::request('target', 'str');
    $target_id = cmsCore::request('target_id', 'int');

	if(!$target || !$target_id) { cmsCore::halt(); }

	$model->whereTargetIs($target, $target_id);

	$inDB->orderBy('c.pubdate', 'ASC');

    $comments = $model->getComments(!($inUser->is_admin || $model->is_can_moderate), true);

	cmsPage::initTemplate('components', 'com_comments_list')->
            assign('comments_count', count($comments))->
            assign('comments', $comments)->
            assign('user_can_moderate', $model->is_can_moderate)->
            assign('user_can_delete', $model->is_can_delete)->
            assign('user_can_add', $model->is_can_add)->
            assign('is_admin', $inUser->is_admin)->
            assign('is_user', $inUser->id)->
            assign('cfg', $model->config)->
            assign('labels', $model->labels)->
            assign('target', $target)->
            assign('target_id', $target_id)->
            display();

    cmsCore::halt();

?>
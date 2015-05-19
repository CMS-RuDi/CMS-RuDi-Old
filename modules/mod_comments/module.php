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

function mod_comments($mod, $cfg) {
    $cfg = array_merge(array(
        'showrss' => 1,
        'minrate' => 0,
        'showguest' => 0
    ), $cfg);

    if (empty($cfg['targets'])) { return true; }

    cmsCore::m('comments')->initAccess();

    // Комментарии только нужного назначения
    cmsCore::m('comments')->whereTargetIn($cfg['targets']);
    
    // Если не показывать гостей, добавляем условие
    if (!$cfg['showguest']) { cmsCore::m('comments')->whereOnlyUsers(); }
    
    // Администраторам и админам показываем все комментарии
    if (!(cmsCore::c('user')->is_admin || cmsCore::m('comments')->is_can_moderate)) {
        cmsCore::m('comments')->whereIsShow();
    }
    
    // Комментарии в зависимости от рейтинга
    if ($cfg['minrate'] <> 0) {
        cmsCore::m('comments')->whereRatingOver($cfg['minrate']);
    }

    cmsCore::c('db')->orderBy('c.pubdate', 'DESC');
    cmsCore::c('db')->limitPage(1, $cfg['shownum']);

    $comments = cmsCore::m('comments')->getComments(true, false, true);
    if (!$comments) { return false; }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('comments', $comments)->
        assign('cfg', $cfg)->
        display();

    return true;
}
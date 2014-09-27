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

function mod_forum($module_id, $cfg) {
    $default_cfg = array (
        'shownum' => 4,
        'cat_id' => 0,
        'forum_id' => 0,
        'subs' => 0,
        'show_hidden' => 0,
        'show_pinned' => 0,
        'showtext' => 1,
        'showforum' => 0,
        'order' => 'pubdate'
    );
    
    $cfg = array_merge($default_cfg, $cfg);
    
    cmsCore::c('db')->addJoin('INNER JOIN cms_forums f ON f.id = t.forum_id');
    cmsCore::c('db')->addSelect('f.title as forum_title');

    if ($cfg['cat_id']) {
        cmsCore::m('forum')->whereForumCatIs($cfg['cat_id']);
    }

    if ($cfg['forum_id']) {
        if ($cfg['subs']) {
            $forum = cmsCore::m('forum')->getForum($cfg['forum_id']);
            if (!$forum) { return false; }

            cmsCore::m('forum')->whereThisAndNestedForum($forum['NSLeft'], $forum['NSRight']);
        } else {
            cmsCore::m('forum')->whereForumIs($cfg['forum_id']);
        }
    }

    if (!$cfg['show_hidden']) {
        cmsCore::m('forum')->wherePublicThreads();
    }

    if ($cfg['show_pinned']) {
        cmsCore::m('forum')->wherePinnedThreads();
    }

    cmsCore::c('db')->orderBy('t.'.$cfg['order'], 'DESC');
    cmsCore::c('db')->limit($cfg['shownum']);
    $threads = cmsCore::m('forum')->getThreads();

    cmsPage::initTemplate('modules', 'mod_forum')->
            assign('threads', $threads)->
            assign('cfg', $cfg)->
            display();

    return true;
}
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

function mod_blogs($module_id, $cfg) {
    $cfg = array_merge(
        array(
            'sort'    => 'pubdate',
            'owner'   => 'user',
            'shownum' => 5,
            'minrate' => 0,
            'blog_id' => 0,
            'showrss' => 1
        ),
        $cfg
    );

    cmsCore::c('blog')->owner = $cfg['owner'];

    if ($cfg['owner'] == 'club') {
        cmsCore::c('db')->addSelect('b.user_id as bloglink');
    }

    // получаем аватары владельцев
    cmsCore::c('db')->addSelect('up.imageurl, img.fileurl');
    cmsCore::c('db')->addJoin('LEFT JOIN cms_user_profiles up ON up.user_id = u.id');
    cmsCore::c('db')->addJoin("LEFT JOIN cms_upload_images img ON img.target_id = p.id AND img.target = 'blog_post' AND img.component = 'blogs'");

    cmsCore::c('blog')->whereOnlyPublic();

    if ($cfg['minrate']) {
        cmsCore::c('blog')->ratingGreaterThan($cfg['minrate']);
    }

    if ($cfg['blog_id']) {
        cmsCore::c('blog')->whereBlogIs($cfg['blog_id']);
    }

    cmsCore::c('db')->orderBy('p.'.$cfg['sort'], 'DESC')->groupBy('p.id');
    cmsCore::c('db')->limit($cfg['shownum']);

    $posts = cmsCore::c('blog')->getPosts(false, cmsCore::m( $cfg['owner'] == 'club' ? 'clubs' : 'blogs' ));
    if (!$posts) { return false; }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('posts', $posts)->
        assign('cfg', $cfg)->
        display();

    return true;
}
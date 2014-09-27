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

if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }
	
function search_clubs($query, $look) {
    global $_LANG;

    cmsCore::m('clubs');

    /////// поиск по клубным блогам //////////

    $sql = "SELECT con.*, cat.title cat_title, cat.id cat_id, cat.owner owner, cat.user_id, img.fileurl
                    FROM cms_blog_posts con
                    INNER JOIN cms_blogs cat ON cat.id = con.blog_id AND cat.allow_who = 'all' AND cat.owner = 'club'
                    LEFT JOIN cms_upload_images img ON img.target_id = con.id AND img.target = 'blog_post' AND img.component = 'clubs'
                    WHERE MATCH(con.title, con.content) AGAINST ('". $query ."' IN BOOLEAN MODE) AND con.published = 1 LIMIT 100";

    $result = cmsCore::c('db')->query($sql);

    if (cmsCore::c('db')->num_rows($result)) {
        while($item = cmsCore::c('db')->fetch_assoc($result)) {
            $result_array = array(
                'link' => cmsCore::m('clubs')->getPostURL($item['user_id'], $item['seolink']),
                'place' => ' &laquo;'. $item['cat_title'] .'&raquo;',
                'placelink' => cmsCore::m('clubs')->getBlogURL($item['user_id']),
                'description' => cmsCore::m('search')->getProposalWithSearchWord($item['content_html']),
                'title' => $item['title'],
                'imageurl' => $item['fileurl'],
                'pubdate' => $item['pubdate']
            );

            cmsCore::m('search')->addResult($result_array);
        }
    }

    /////// поиск по клубным фоткам //////////

    $sql = "SELECT f.*, a.title as cat, a.id as cat_id
                    FROM cms_photo_files f
                    INNER JOIN cms_photo_albums a ON a.id = f.album_id AND a.published = 1 AND a.NSDiffer != ''
                    WHERE MATCH(f.title, f.description) AGAINST ('". $query ."' IN BOOLEAN MODE) AND f.published = 1";

    $result = cmsCore::c('db')->query($sql);

    if (cmsCore::c('db')->num_rows($result)) {
        while($item = cmsCore::c('db')->fetch_assoc($result)) {
            $result_array = array(
                'link' => '/clubs/photo'. $item['id'] .'.html',
                'place' => $_LANG['CLUBS_PHOTOALBUM'] .' &laquo;'. $item['cat'] .'&raquo;',
                'placelink' => '/clubs/photoalbum'. $item['cat_id'],
                'description' => cmsCore::m('search')->getProposalWithSearchWord($item['description']),
                'title' => $item['title'],
                'imageurl' => (file_exists(PATH .'/images/photos/medium/'. $item['file']) ? '/images/photos/medium/'. $item['file'] : ''),
                'pubdate' => $item['pubdate']
            );
            
            cmsCore::m('search')->addResult($result_array);
        }
    }

    return;
}
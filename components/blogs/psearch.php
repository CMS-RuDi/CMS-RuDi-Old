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
	
function search_blogs($query, $look) {
    global $_LANG;
    
    $sql = "SELECT con.*, cat.title cat_title, cat.id cat_id, cat.owner owner, cat.user_id user_id, cat.seolink as bloglink, img.fileurl
			FROM cms_blog_posts con
			INNER JOIN cms_blogs cat ON cat.id = con.blog_id AND cat.allow_who = 'all' AND cat.owner = 'user'
                        LEFT JOIN cms_upload_images img ON img.target_id = con.id AND img.target = 'blog_post' AND img.component = 'blogs'
			WHERE MATCH(con.title, con.content) AGAINST ('". $query ."' IN BOOLEAN MODE) AND con.published = 1 LIMIT 100";
    
    $result = cmsCore::c('db')->query($sql);
    
    if (cmsCore::c('db')->num_rows($result)) {
        while($item = cmsCore::c('db')->fetch_assoc($result)) {
            $result_array = array(
                'link' => cmsCore::m('blogs')->getPostURL($item['bloglink'], $item['seolink']),
                'place' => $_LANG['BLOG'].' &laquo;'.$item['cat_title'].'&raquo;',
                'placelink' => cmsCore::m('blogs')->getBlogURL($item['bloglink']),
                'description' => cmsCore::m('search')->getProposalWithSearchWord($item['content_html']),
                'title' => $item['title'],
                'imageurl' => $item['fileurl'],
                'pubdate' => $item['pubdate']
            );

            cmsCore::m('search')->addResult($result_array);

        }
    }
	
    return;
}
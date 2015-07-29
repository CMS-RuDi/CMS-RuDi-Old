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

if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function cmsInsertTags($tagstr, $target, $item_id){
    cmsClearTags($target, $item_id);
    
    $tagstr = strip_tags(mb_strtolower(preg_replace('/\s+/u', ' ', trim($tagstr))));
    if (!$tagstr){ return true; }

    // формируем массив слов по запятой
    $tags = explode(',', $tagstr);

    foreach ($tags as $tag){
        $tag = mb_substr(trim($tag), 0, 240);
        if(mb_strlen($tag)>=3){
            $tag = cmsCore::c('db')->escape_string($tag);
            cmsCore::c('db')->query("INSERT INTO cms_tags (tag, target, item_id) VALUES ('". $tag ."', '". $target ."', ". $item_id .")");
        }
    }
    
    return true;
}

function cmsClearTags($target, $item_id){
    return cmsCore::c('db')->delete('cms_tags', "target='". $target ."' AND item_id = '". $item_id ."'");
}

function cmsTagLine($target, $item_id, $links = true, $selected = '') {
    $sql  = "SELECT tag
                    FROM cms_tags
                    WHERE target='$target' AND item_id='$item_id'
                    ORDER BY tag DESC";
    $rs = cmsCore::c('db')->query($sql);
    
    $html = '';
    $tags = cmsCore::c('db')->num_rows($rs);
    
    if ($tags) {
        $t = 1;
        while ($tag=cmsCore::c('db')->fetch_assoc($rs)) {
            if ($links) {
                if ($selected==$tag['tag']) {
                    $html .= '<a href="/search/tag/'. urlencode($tag['tag']) .'" style="font-weight:bold;text-decoration:underline" rel="tag">'. $tag['tag'] .'</a>';
                }
                else
                {
                    $html .= '<a href="/search/tag/'. urlencode($tag['tag']) .'" rel="tag">'. $tag['tag'] .'</a>';
                }
            }
            else
            {
                $html .= $tag['tag'];
            }
            if ($t < $tags) { $html .= ', '; $t++; }
        }
    }
    else
    {
        $html = '';
    }
    
    return $html;
}

function cmsTagBar($target, $item_id, $selected = '') {
    if ($tagline = cmsTagLine($target, $item_id, true, $selected)) {
        return cmsCore::c('page')->initTemplate('special/tagbar')->
                assign('$tagline', $tagline)->
                fetch();
    } else {
        return '';
    }
}

function cmsTagItemLink($target, $item_id){
    $inCore = cmsCore::getInstance();
    $link = '';
    
    switch ($target){
        case 'content':
            $today = date("Y-m-d H:i:s");
            $sql = "SELECT i.title as title, c.title as cat, i.seolink as seolink, c.seolink as cat_seolink
                        FROM cms_content i
                        INNER JOIN cms_category c ON c.id = i.category_id
                        WHERE i.id = '$item_id' AND i.published = 1  AND i.is_arhive = 0 AND i.pubdate <= '$today' AND (i.is_end=0 OR (i.is_end=1 AND i.enddate >= '$today')) LIMIT 1";
            $rs = cmsCore::c('db')->query($sql) ;
            if (cmsCore::c('db')->num_rows($rs)){
                $item = cmsCore::c('db')->fetch_assoc($rs);
                $link =  '<a href="/'.$item['cat_seolink'].'" class="tag_searchcat">'.$item['cat'].'</a> &rarr; ';
                $link .= '<a href="/'.$item['seolink'].'.html" class="tag_searchitem">'.$item['title'].'</a>';
            }
        break;
        
        case 'blogpost':
            $sql = "SELECT i.title as title, i.id as item_id, c.title as cat, c.id as cat_id, c.owner as owner, c.user_id user_id, i.seolink as seolink, c.seolink as bloglink
                        FROM cms_blog_posts i
                        LEFT JOIN cms_blogs c ON c.id = i.blog_id
                        WHERE i.id = '$item_id' AND i.published = 1";
            $rs = cmsCore::c('db')->query($sql) ;
            if (cmsCore::c('db')->num_rows($rs)){
                $item = cmsCore::c('db')->fetch_assoc($rs);
                if ($item['owner'] == 'club') { $item['cat'] = cmsCore::c('db')->get_field('cms_clubs','id='.$item['user_id'],'title'); }
                $link =  '<a href="/blogs/'.$item['bloglink'].'" class="tag_searchcat">'.$item['cat'].'</a> &rarr; ';
                $link .= '<a href="/blogs/'.$item['bloglink'].'/'.$item['seolink'].'.html" class="tag_searchitem">'.$item['title'].'</a>';
            }
        break;
        
        case 'photo':
            $sql = "SELECT i.title as title, i.id as item_id, c.title as cat, c.id as cat_id
                        FROM cms_photo_files i
                        LEFT JOIN cms_photo_albums c ON c.id = i.album_id
                        WHERE i.id = '$item_id' AND i.published = 1";
            $rs = cmsCore::c('db')->query($sql) ;
            if (cmsCore::c('db')->num_rows($rs)){
                $item = cmsCore::c('db')->fetch_assoc($rs);
                $link =  '<a href="/photos/'.$item['cat_id'].'" class="tag_searchcat">'.$item['cat'].'</a> &rarr; ';
                $link .= '<a href="/photos/photo'.$item['item_id'].'.html" class="tag_searchitem">'.$item['title'].'</a>';
            }
        break;
        
        case 'userphoto':
            $sql = "SELECT i.title as title, i.id as item_id, c.nickname as cat, c.id as cat_id, c.login as login
                        FROM cms_user_photos i
                        LEFT JOIN cms_users c ON c.id = i.user_id
                        WHERE i.id = '$item_id'";
            $rs = cmsCore::c('db')->query($sql) ;
            if (cmsCore::c('db')->num_rows($rs)){
                $item = cmsCore::c('db')->fetch_assoc($rs);
                $link =  '<a href="'.cmsUser::getProfileURL($item['login']).'" class="tag_searchcat">'.$item['cat'].'</a> &rarr; ';
                $link .= '<a href="/users/'.$item['cat_id'].'/photo'.$item['item_id'].'.html" class="tag_searchitem">'.$item['title'].'</a>';
            }
        break;
        
        case 'catalog':
            $sql = "SELECT i.title as title, i.id as item_id, c.title as cat, c.id as cat_id
                        FROM cms_uc_items i
                        LEFT JOIN cms_uc_cats c ON c.id = i.category_id
                        WHERE i.id = '$item_id' AND i.published = 1";
                        $rs = cmsCore::c('db')->query($sql) ;
                        if (cmsCore::c('db')->num_rows($rs)){
                            $item = cmsCore::c('db')->fetch_assoc($rs);
                            $link =  '<a href="/catalog/'.$item['cat_id'].'" class="tag_searchcat">'.$item['cat'].'</a> &rarr; ';
                            $link .= '<a href="/catalog/item'.$item['item_id'].'.html" class="tag_searchitem">'.$item['title'].'</a>';
                        }
        break;
        
        case 'video':
            $inCore->loadModel('video');
            $model = cms_model_video::initModel();
            $sql = "SELECT i.title as title, i.id as item_id, i.seolink as movie_seolink, c.title as cat_title, c.id as cat_id, c.seolink as cat_seolink
                        FROM cms_video_movie i
                        INNER JOIN cms_video_category c ON c.id = i.cat_id
                        WHERE i.id = '$item_id'";
            $rs = cmsCore::c('db')->query($sql) ;
            if (cmsCore::c('db')->num_rows($rs)){
                $item = cmsCore::c('db')->fetch_assoc($rs);
                $link =  '<a href="'.$model->getCatLink($item['cat_seolink'], $item['cat_id']).'" class="tag_searchcat">'.$item['cat_title'].'</a> &rarr; ';
                $link .= '<a href="'.$model->getMovieLink($item['cat_seolink'].'/'.$item['movie_seolink'], $item['item_id']).'" class="tag_searchitem">'.$item['title'].'</a>';
            }
        break;
        
        case 'shop':
            $sql = "SELECT i.title as title, i.seolink as seolink, c.title as cat, c.seolink as cat_seolink
                        FROM cms_shop_items i
                        LEFT JOIN cms_shop_cats c ON c.id = i.category_id
                        WHERE i.id = '$item_id'";
            $rs = cmsCore::c('db')->query($sql) ;
            if (cmsCore::c('db')->num_rows($rs)){
                $item = cmsCore::c('db')->fetch_assoc($rs);
                $link =  '<a href="/shop/'.$item['cat_seolink'].'" class="tag_searchcat">'.$item['cat'].'</a> &rarr; ';
                $link .= '<a href="/shop/'.$item['seolink'].'.html" class="tag_searchitem">'.$item['title'].'</a>';
            }
        break;
        
        case 'maps':
            $sql = "SELECT i.title as title, i.seolink as seolink, c.title as cat, c.seolink as cat_seolink
                        FROM cms_map_items i
                        LEFT JOIN cms_map_cats c ON c.id = i.category_id
                        WHERE i.id = '$item_id'";
            $rs = cmsCore::c('db')->query($sql) ;
            if (cmsCore::c('db')->num_rows($rs)){
                $item = cmsCore::c('db')->fetch_assoc($rs);
                $link =  '<a href="/maps/'.$item['cat_seolink'].'" class="tag_searchcat">'.$item['cat'].'</a> &rarr; ';
                $link .= '<a href="/maps/'.$item['seolink'].'.html" class="tag_searchitem">'.$item['title'].'</a>';
            }
        break;
    }
    
    return $link;
}

function cmsTagsList(){
    $html = '';
    $sql = "SELECT t.tag, COUNT(t.tag) as num
                    FROM cms_tags t
                    GROUP BY t.tag
                    ORDER BY t.tag";
    $result = cmsCore::c('db')->query($sql) ;
    if (cmsCore::c('db')->num_rows($result)>0){
        while($tag = cmsCore::c('db')->fetch_assoc($result)){
            if ($tag['tag']){
                $html .= '<a href="/search/tag/'. urlencode($tag['tag']) .'">'. $tag['tag'] .'</a> ('. $tag['num'] .') ';
            }
        }
    }
    return $html;
}
?>
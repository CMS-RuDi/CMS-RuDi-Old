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
if (!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function rss_content($item_id, $cfg) {
    if (!cmsCore::getInstance()->isComponentEnable('content')) { return false; }
    
    global $_LANG;

    $channel = array();
    $items   = array();

    if ($item_id) {
        $cat = cmsCore::c('db')->getNsCategory('cms_category', (int)$item_id);
        if (!$cat) { return false; }

        $cat = cmsCore::callEvent('GET_CONTENT_CAT', $cat);

        if (!$cat['published']) { return false; }

        if (!cmsCore::checkUserAccess('category', $cat['id']) ) {
            return false;
        }

        cmsCore::m('content')->whereThisAndNestedCats($cat['NSLeft'], $cat['NSRight']);

        $channel['title'] = $cat['title'] ;
        $channel['description'] = $cat['description'];
        $channel['link'] = HOST . cmsCore::m('content')->getCategoryURL(0, $cat['seolink']);
    } else {
        $channel['title'] = $_LANG['NEW_MATERIALS'];
        $channel['description'] = $_LANG['LAST_ARTICLES_NEWS'];
        $channel['link'] = HOST .'/content';
    }

    cmsCore::c('db')->where('con.showlatest = 1');

    cmsCore::c('db')->orderBy('con.pubdate', 'DESC');
    cmsCore::c('db')->limit($cfg['maxitems']);

    $content = cmsCore::m('content')->getArticlesList();

    if ($content) {
        foreach($content as $con){
            $con['link']     = HOST . $con['url'];
            $con['comments'] = $con['link'].'#c';
            $con['category'] = $con['cat_title'];

            if ($con['image']) {
                $con['size']  = round(filesize(PATH . $con['image']));
                $con['image'] = HOST . $con['image'];
            }

            $items[] = $con;
        }
    }

    return array(
        'channel' => $channel,
        'items' => $items
    );
}
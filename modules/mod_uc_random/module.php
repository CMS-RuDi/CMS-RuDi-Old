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

function mod_uc_random($module_id, $cfg) {
    if ($cfg['cat_id']>0) {
        if (!$cfg['subs']) {
            //select from category
            $catsql = ' AND i.category_id = '. $cfg['cat_id'];
        } else {
            //select from category and subcategories
            $rootcat = cmsCore::c('db')->get_fields('cms_uc_cats', "id='". $cfg['cat_id'] ."'", 'NSLeft, NSRight');
            
            if (!$rootcat) { return false; }
            
            $catsql = "AND (c.NSLeft >= ". $rootcat['NSLeft'] ." AND c.NSRight <= ". $rootcat['NSRight'] .")";
        }
    } else {
        $catsql = '';
    }

    $sql = "SELECT i.*, c.title as category, c.view_type as viewtype
            FROM cms_uc_items i
            LEFT JOIN cms_uc_cats c ON c.id = i.category_id
            WHERE i.published = 1 ". $catsql ."
            ORDER BY RAND()
            LIMIT ". $cfg['count'];

    $result = cmsCore::c('db')->query($sql) ;

    $items = array();
    $is_uc = false;

    if (cmsCore::c('db')->num_rows($result)) {
        $is_uc = true;
        while ($item=cmsCore::c('db')->fetch_assoc($result)) {
            if (mb_strlen($item['imageurl']) < 4) {
                $item['imageurl'] = 'nopic.jpg';
            } else if (!file_exists(PATH .'/images/catalog/small/'. $item['imageurl'])) {
                $item['imageurl'] = 'nopic.jpg';
            }

            if ($item['viewtype'] == 'shop') {
                cmsCore::includeFile('components/catalog/includes/shopcore.php');
                $item['price'] = number_format(shopDiscountPrice($item['id'], $item['category_id'], $item['price']), 2, '.', ' ');
            }

            $items[] = $item;
        }
    }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('items', $items)->
        assign('cfg', $cfg)->
        assign('is_uc', $is_uc)->
        display();

    return true;
}
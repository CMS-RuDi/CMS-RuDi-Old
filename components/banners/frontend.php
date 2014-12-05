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

function banners(){
    $inCore = cmsCore::getInstance();

    $banner_id = cmsCore::request('id', 'int', 0);

//======================================================================================================================//

    if ($inCore->do == 'view'){
        $banner = cmsCore::m('banners')->getBanner($banner_id);
        if(!$banner || !$banner['published']) { cmsCore::error404(); }

        cmsCore::m('banners')->clickBanner($banner_id);
        cmsCore::redirect($banner['link']);
    }

}
?>
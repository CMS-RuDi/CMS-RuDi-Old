<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.3                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2013                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function arhive(){
    $inCore = cmsCore::getInstance();
    
    global $_LANG;
    
    $pagetitle = $inCore->getComponentTitle();

    cmsCore::c('page')->setTitle($pagetitle);
    cmsCore::c('page')->addPathway($pagetitle, '/arhive');

 //======================================================================================================//

    if ($inCore->do == 'view' || $inCore->do == 'y'){

        if($inCore->do == 'y'){
            $pagetitle = $_LANG['ARCHIVE_MATERIALS_FROM'] . cmsCore::m('arhive')->year . $_LANG['ARHIVE_YEAR'];
            cmsCore::c('page')->addPathway(cmsCore::m('arhive')->year, '/arhive/'. cmsCore::m('arhive')->year);
            cmsCore::c('page')->setTitle($pagetitle);
            cmsCore::m('arhive')->whereYearIs();
        }

        $items = cmsCore::m('arhive')->getArhiveContent();

        cmsPage::initTemplate('components', 'com_arhive_dates')->
            assign('pagetitle', $pagetitle)->
            assign('items', $items)->
            assign('do', $inCore->do)->
            display('com_arhive_dates.tpl');

    }

//======================================================================================================//

    if ($inCore->do == 'ymd' || $inCore->do == 'ym'){
        $month_name = cmsCore::intMonthToStr(cmsCore::m('arhive')->month);
        
        cmsCore::c('page')->addPathway(cmsCore::m('arhive')->year, '/arhive/'. cmsCore::m('arhive')->year);
        cmsCore::c('page')->addPathway($month_name, '/arhive/'. cmsCore::m('arhive')->year .'/'. cmsCore::m('arhive')->month);

        if($inCore->do == 'ymd'){
            cmsCore::c('page')->addPathway(cmsCore::m('arhive')->day, '/arhive/'. cmsCore::m('arhive')->year .'/'. cmsCore::m('arhive')->month .'/'. cmsCore::m('arhive')->day);
            $pagetitle = $_LANG['ARCHIVE_MATERIALS_FROM'].cmsCore::m('arhive')->day .' '
                            .$_LANG['MONTH_'. cmsCore::m('arhive')->month] .' '. cmsCore::m('arhive')->year . $_LANG['ARHIVE_YEARS'];
            cmsCore::m('arhive')->whereDayIs();
        } else {
            $pagetitle = $_LANG['ARCHIVE_MATERIALS_FROM'] . $month_name .' '. cmsCore::m('arhive')->year . $_LANG['ARHIVE_YEARS'];
            cmsCore::m('arhive')->whereMonthIs();
        }

        cmsCore::c('page')->setTitle($pagetitle);

        cmsCore::m('arhive')->setArtticleSql();

        $items = cmsCore::m('arhive')->getArhiveContent();

        cmsPage::initTemplate('components', 'com_arhive_list')->
            assign('pagetitle', $pagetitle)->
            assign('items', $items)->
            display('com_arhive_list.tpl');

    }

}
?>
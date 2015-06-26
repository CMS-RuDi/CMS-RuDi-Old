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

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_noaccess(){
    global $_LANG;

    cmsCore::c('page')->setTitle($_LANG['ACCESS_DENIED']);
    cpAddPathway($_LANG['ACCESS_DENIED'], 'index.php?view=noaccess');
    
    cmsCore::c('page')->initTemplate('applets', 'noaccess')->
        display();
}
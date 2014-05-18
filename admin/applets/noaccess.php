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

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_noaccess(){

    global $_LANG;

    cmsCore::c('page')->setAdminTitle($_LANG['ACCESS_DENIED']);
 	cpAddPathway($_LANG['ACCESS_DENIED'], 'index.php?view=noaccess');

    echo '<h3>'.$_LANG['ACCESS_DENIED'].'</h3>';
    echo '<p>'.$_LANG['AD_ACCESS_DENIED_TEXT'].'</p>';
    echo '<p><a href="javascript:void(0)" onclick="window.history.go(-1)">'.$_LANG['BACK'].'</a></p>';

}

?>
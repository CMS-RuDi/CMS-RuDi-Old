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

function mod_rss($module_id, $cfg){

    cmsCore::includeFile('includes/rss/lastRSS.php');

    $rss = new lastRSS;

    $rss->cache_dir   = PATH.'/cache';
    $rss->cache_time  = (int)@$cfg['cachetime']*3600;
    $rss->cp          = 'UTF-8';
    $rss->items_limit = $cfg['itemslimit'];

    $rs = $rss->Get($cfg['rssurl']);
    if(!$rs){ return false; }

    cmsPage::initTemplate('modules', 'mod_rss')->
            assign('rs', $rs)->
            assign('cfg', $cfg)->
            display('mod_rss.tpl');

    return true;

}
?>
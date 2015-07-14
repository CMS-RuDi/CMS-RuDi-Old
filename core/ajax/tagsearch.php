<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/
define('PATH', $_SERVER['DOCUMENT_ROOT']);
include(PATH.'/core/ajax/ajax_core.php');

$q = mb_strtolower(cmsCore::request('q', 'str', cmsCore::request('term', 'str', '')));
$term = cmsCore::inRequest('term');

if (empty($q)) { cmsCore::halt(); }

$sql = "SELECT tag FROM cms_tags WHERE LOWER(tag) LIKE '". cmsCore::c('db')->escape_string($q) ."%' GROUP BY tag LIMIT 100";
$rs  = cmsCore::c('db')->query($sql);

$tags = array();

while ($item = cmsCore::c('db')->fetch_assoc($rs)) {
    if ($term) {
        $tags[] = $item['tag'];
    } else {
        echo $item['tag'] ."\n";
    }
}

if ($term) {
    echo json_encode($tags);
}

cmsCore::halt();
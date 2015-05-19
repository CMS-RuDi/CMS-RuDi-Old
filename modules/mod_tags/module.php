<?php
/******************************************************************************/
//                                                                            //
//                         InstantCMS v1.10.5.3                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

function mod_tags($mod, $cfg) {
    if (empty($cfg['targets'])) { return false; }

    $tl = "'". implode("','", $cfg['targets']) ."'";
    
    $cfg = array_merge(array(
        'minfreq'    => 0,
        'minlen'     => 0,
        'maxtags'    => 20,
        'colors'     => '',
        'shuffle'    => 0,
        'start_size' => 10,
        'step'       => 4,
        'end_size'   => 50
    ), $cfg);

    $sql  = "SELECT tag, COUNT(tag) as num
             FROM cms_tags WHERE target IN (". $tl .") ";
    $sql .= $cfg['minlen'] ? " AND CHAR_LENGTH(tag) >= ". $cfg['minlen'] ."\n" : "\n";
    $sql .= "GROUP BY tag \n";
    $sql .= $cfg['minfreq'] ? "HAVING num >= ". $cfg['minfreq'] ." \n" : '';
    $sql .= ($cfg['sortby'] == 'tag') ? "ORDER BY tag ASC \n" : "ORDER BY num DESC \n";
    $sql .= 'LIMIT '. $cfg['maxtags'];

    $result = cmsCore::c('db')->query($sql);
    if (!cmsCore::c('db')->num_rows($result)) { return false; }

    // массив возможных значений шрифта
    $sizes    = range($cfg['start_size'], $cfg['end_size'], $cfg['step']);
    $size_prc = ceil((100 / sizeof($sizes)));
    
    // Общее число тегов
    $summary = 0;
    while($tag = cmsCore::c('db')->fetch_assoc($result)) {
        $tag['fontsize'] = '';
        $tags[]   = $tag;
        $summary += $tag['num'];
    }
    
    // формируем размер шрифта
    foreach($tags as $key=>$value) {
        $prc = ceil(($value['num'] / $summary) * 100);
        foreach ($sizes as $k => $v) {
            if ($prc >= ($k*$size_prc)) {
                $tags[$key]['fontsize'] = $sizes[$k];
            }
        }
    }

    // перемешивать теги
    if ($cfg['shuffle']) {
        shuffle($tags);
    }

    cmsPage::initTemplate('modules', $cfg['tpl'])->
        assign('tags', $tags)->
        assign('cfg', $cfg)->
        display();

    return true;
}
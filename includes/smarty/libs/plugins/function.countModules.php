<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

function smarty_function_countModules($params, $template) {
    $mod_count = array();
    
    $pos = explode(',', $params['pos']);
    
    foreach ($pos as $p) {
        $mod_count[$p] = cmsCore::c('page')->countModules($p);
    }
    
    $template->assign('mod_count', $mod_count);
}
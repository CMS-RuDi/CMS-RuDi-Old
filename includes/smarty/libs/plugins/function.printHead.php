<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

function smarty_function_printHead($params, $template) {
    if (!isset($params['full_print']) || !in_array($params['full_print'], array('js', 'css'))) {
        $params['full_print'] = true;
    }
    $params['indent'] = isset($params['indent']) ? (int)$params['indent'] : 4;
    
    cmsCore::c('page')->printHead($params['full_print'], $params['indent']);
}
<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

function smarty_function_callEvent($params, &$smarty) {
    $event = $params['event'];
    unset($params['event']);
    
    if (isset($params['item'])) {
        $item = $params['item'];
    } else {
        $item = $params;
    }

    cmsCore::callEvent($event, $item);
}
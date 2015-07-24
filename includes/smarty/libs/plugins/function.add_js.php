<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

function smarty_function_add_js($params, &$smarty) {
    if (isset($params['prepend'])) {
        cmsCore::c('page')->prependHeadJS($params['file']);
    } else {
        cmsCore::c('page')->addHeadJS($params['file']);
    }
}
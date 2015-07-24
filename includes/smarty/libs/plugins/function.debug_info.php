<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

function smarty_function_debug_info($params, &$smarty) {
    if (cmsCore::c('config')->debug && cmsCore::c('user')->is_admin) {
        cmsCore::c('page')->initTemplate('special/debug')->
            assign('time', cmsCore::getInstance()->getGenTime())->
            assign('memory', round(@memory_get_usage()/1024/1024, 2))->
            assign('q_count', cmsCore::c('db')->q_count)->
            assign('q_dump', cmsCore::c('db')->q_dump)->
            display();
    }
}
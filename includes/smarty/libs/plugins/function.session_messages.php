<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

function smarty_function_session_messages($params, $template) {
    cmsCore::c('page')->initTemplate('special/session_messages')->
        assign('messages', cmsCore::getSessionMessages())->
        display();
}
<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

function smarty_function_printBody($params, $template) {
    if (cmsCore::c('page')->page_body) {
        ob_start();
            cmsCore::c('page')->printBody();
        
        cmsCore::c('page')->initTemplate('special/body')->
            assign('body', ob_get_clean())->
            display();
    }
}
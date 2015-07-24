<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

function smarty_function_printPathway($params, $template) {
    cmsCore::c('page')->printPathway($params['sep']);
}
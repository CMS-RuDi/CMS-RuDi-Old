<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.4                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2013 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

$_LANG['PIV_TAB']                 = 'Заголовок вкладки';
$_LANG['PIV_DOMENS']              = 'Список разрешенных доменов (через запятую)';
$_LANG['INSERT_PLAYER_CODE']      = 'Вставьте код плеера сюда и нажмите кнопку прикрепить.';
$_LANG['ATTACH']                  = 'Прикрепить';
$_LANG['VIDEOS']                  = 'Прикрепленные видео';
$_LANG['VIDEOS_INSERT_HINT_TEXT'] = 'Для вставки в текст статьи прикрепленных видео плееров напишите команду <b>{video#100}</b> где 100 это id видео плеера. Не вставляйте код плеера прямо в редактор, сперва прикрепите его к статье а потом вставьте используя вышеописанную команду. Прикреплять разрешено плеера по <b>iframe</b>, <b>object</b> или <b>ebmed</b> коду все остальные теги будут автоматически вырезаны.<br/> Разрешенные домены: ';
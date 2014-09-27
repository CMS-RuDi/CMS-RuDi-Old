<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.4                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

session_start();

include('kcaptcha.php');

$captcha = new KCAPTCHA();

$captcha_id = (int)$_GET['captcha_id'];

$_SESSION['captcha'][$captcha_id] = $captcha->getKeyString();
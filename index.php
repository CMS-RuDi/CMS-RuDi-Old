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

Error_Reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

header('Content-Type: text/html; charset=utf-8');
header('X-Powered-By: CMS RuDi');
define('PATH', $_SERVER['DOCUMENT_ROOT']);
define('VALID_CMS', 1);

// Проверяем, что система установлена
if (!file_exists(PATH .'/includes/config.inc.php')) {
    header('location:/install/');
    die();
}

session_start();

include('core/cms.php');
$inCore = cmsCore::getInstance();

// Включаем таймер
$inCore->startGenTimer();

// Загружаем нужные классы
cmsCore::loadClass('actions');

// Проверяем что директории установки и миграции удалены
if (is_dir(PATH .'/install') || is_dir(PATH .'/migrate')) {
    cmsPage::includeTemplateFile('special/installation.php');
    cmsCore::halt();
}

cmsCore::callEvent('GET_INDEX', '');

// автоматически авторизуем пользователя, если найден кукис
cmsCore::c('user')->autoLogin();

// проверяем что пользователь не удален и не забанен и загружаем его данные
if (!cmsCore::c('user')->update() && !$_SERVER['REQUEST_URI']!=='/logout') { cmsCore::halt(); }

//Если сайт выключен и пользователь не администратор,
//то показываем шаблон сообщения о том что сайт отключен
if (cmsCore::c('config')->siteoff &&
    !cmsCore::c('user')->is_admin &&
    $_SERVER['REQUEST_URI']!='/login' &&
    $_SERVER['REQUEST_URI']!='/logout'
){
    cmsPage::includeTemplateFile('special/siteoff.php');
    cmsCore::halt();
}

// Мониторинг пользователей
cmsCore::c('user')->onlineStats();

// Строим глубиномер
cmsCore::c('page')->addPathway($_LANG['PATH_HOME'], '/');

//Проверяем доступ пользователя
//При положительном результате
//Строим тело страницы (запускаем текущий компонент)
if ($inCore->checkMenuAccess()) { $inCore->proceedBody(); }

//Проверяем нужно ли показать входную страницу (splash)
if($inCore->isSplash()){
    //Показываем входную страницу
    if (!cmsCore::c('page')->showSplash()){
        //Если шаблон входной страницы не был найден,
        //показываем обычный шаблон сайта
        cmsCore::c('page')->showTemplate();
    }
} else {
    //показываем шаблон сайта
    cmsCore::c('page')->showTemplate();
}
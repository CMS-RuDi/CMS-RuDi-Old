<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.5                               //
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
define('PATH', dirname(__FILE__));
define('VALID_CMS', 1);

// Проверяем, что система установлена
if (!file_exists(PATH .'/includes/config/config.inc.json')) {
    header('location:/install/');
    die();
}

include('core/cms.php');

$ifsid = cmsCore::request('IFSID', 'str', null, 'get');
if (!empty($ifsid)) { session_id($ifsid); }

session_start();

$inCore = cmsCore::getInstance();

// Загружаем нужные классы
cmsCore::loadClass('page');
cmsCore::loadClass('actions');

cmsCore::callEvent('GET_INDEX', '');

// автоматически авторизуем пользователя, если найден кукис
cmsCore::c('user')->autoLogin();

// проверяем что пользователь не удален и не забанен и загружаем его данные
if (!cmsCore::c('user')->update() && !$_SERVER['REQUEST_URI'] !== '/logout') { cmsCore::halt(); }

if((is_dir(PATH .'/install') || is_dir(PATH .'/migrate')) && cmsCore::c('user')->is_admin) {
    cmsPage::includeTemplateFile('special/installation.php');
    cmsCore::halt();
}

//Если сайт выключен и пользователь не администратор,
//то показываем шаблон сообщения о том что сайт отключен
if (cmsCore::c('config')->siteoff &&
    !cmsCore::c('user')->is_admin &&
    $_SERVER['REQUEST_URI'] != '/login' &&
    $_SERVER['REQUEST_URI'] != '/logout'
) {
    cmsPage::includeTemplateFile('special/siteoff.php');
    cmsCore::halt();
}

// Если включена опция показа сайта только авторизованным и пользователь не
// авторизован то редиректимся на страницу авторизации
if ($inCore->component != 'registration' && $inCore->component != cmsCore::c('config')->homecom && $inCore->getUri() && cmsCore::c('config')->only_authorized && !cmsCore::c('user')->id) {
    cmsCore::redirect('/login');
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
if ($inCore->isSplash()) {
    //Показываем входную страницу
    if (!cmsCore::c('page')->showSplash()) {
        //Если шаблон входной страницы не был найден,
        //показываем обычный шаблон сайта
        cmsCore::c('page')->showTemplate();
    }
} else {
    //показываем шаблон сайта
    cmsCore::c('page')->showTemplate();
}
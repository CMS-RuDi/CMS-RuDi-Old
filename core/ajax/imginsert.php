<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

// при ajaxfileupload HTTP_X_REQUESTED_WITH не передается, устанавливем его - костыль :-) см. /core/ajax/ajax_core.php
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
if (!empty($_REQUEST['ses_id'])) { session_id($_REQUEST['ses_id']); }

define('PATH', $_SERVER['DOCUMENT_ROOT']);
include(PATH.'/core/ajax/ajax_core.php');

// загружать могут только авторизованные
if (!cmsCore::c('user')->id) { cmsCore::halt(); }

// Получаем компонент, с которого идет загрузка
$component = cmsCore::request('component', 'str', '');

// id места назначения
$target_id = cmsCore::request('target_id', 'int', 0);

// место назначения в компоненте
$target = cmsCore::request('target', 'str', '');

// Проверяем установлен и включен ли компонент
if (!$inCore->isComponentEnable($component)) { cmsCore::halt(); }

// Загружаем конфигурацию компонента
$com_cfg = $inCore->loadComponentConfig($component);

/* Будет удален в скором времени */
if (!isset($com_cfg['imgs_big_w']) && isset($com_cfg['img_w'])) {
    $com_cfg['imgs_big_w'] = $com_cfg['img_w'];
}
if (!isset($com_cfg['imgs_big_h']) && isset($com_cfg['img_h'])) {
    $com_cfg['imgs_big_h'] = $com_cfg['img_h'];
}
/* ============================= */

// Настройки по умолчанию
$cfg = array(
    'img_max'       => 50,
    'img_on'        => 1,
    'img_w'         => 900, // Будет удалено в скором времени
    'img_h'         => 900, // Будет удалено в скором времени
    'imgs_big_w'    => 900,
    'imgs_big_h'    => 900,
    'imgs_medium_w' => 600,
    'imgs_medium_h' => 600,
    'imgs_small_w'  => 150,
    'imgs_small_h'  => 150,
    'resize_type'   => 'auto',
    'mresize_type'  => 'auto',
    'sresize_type'  => 'auto',
    'imgs_quality'  => 80,
    'watermark'     => 1,
    'watermark_only_big' => false
);

foreach ($default_cfg as $k => $v) {
    if (!empty($target) && isset($com_cfg[$target .'_'. $k])) {
        $cfg[$k] = $com_cfg[$target .'_'. $k];
    } else if (isset($com_cfg[$target .'_'. $k])) {
        $cfg[$k] = $com_cfg[$k];
    }
}

// Разрешена ли загрузка
if (!$cfg['img_on']) {
    cmsCore::jsonOutput(array('error' => $_LANG['UPLOAD_IMG_IS_DISABLE'], 'msg' => ''), false);
}

// Если в модели компонента есть метод checkAccessAddImage передаем ему 
// параметры $target_id и $target для проверки прав пользователя на загрузку
// изображения
if (method_exists(cmsCore::m($component), 'checkAccessAddImage')) {
    if (!cmsCore::m($component)->checkAccessAddImage($target_id, $target)) {
        cmsCore::jsonOutput(array('error' => $_LANG['UPLOAD_IMG_IS_DISABLE'], 'msg' => ''), false);
    }
}

// Не превышен ли лимит
if (cmsCore::getTargetCount($target_id, $target, $component) >= $cfg['img_max'] && !cmsCore::c('user')->is_admin) {
    cmsCore::jsonOutput(array('error' => $_LANG['UPLOAD_IMG_LIMIT'], 'msg' => ''), false);
}

$ym = date('Y-m');
$d = date('d');
$f = mb_substr(md5(microtime(true)), 0, 2);

//Создаем необходимую структуру папок
if (!is_dir(PATH .'/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f)) {
    if (!mkdir(PATH .'/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f, 0777, true)) {
        cmsCore::jsonOutput(array('error' => sprintf($_LANG['DIR_NOT_WRITABLE'], '/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f), 'msg' => ''), false);
    }
}

if (!is_dir(PATH .'/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f)) {
    if (!mkdir(PATH .'/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f, 0777, true)) {
        cmsCore::jsonOutput(array('error' => sprintf($_LANG['DIR_NOT_WRITABLE'], '/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f), 'msg' => ''), false);
    }
}

if (!is_dir(PATH .'/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f)) {
    if (!mkdir(PATH .'/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f, 0777, true)) {
        cmsCore::jsonOutput(array('error' => sprintf($_LANG['DIR_NOT_WRITABLE'], '/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f), 'msg' => ''), false);
    }
}
//----------------------------------------------------------------------

//Выставляем опции отвечающие за нанесение водяного знака
cmsCore::c('images')->watermark  = $cfg['watermark'];
if (empty($cfg['watermark_only_big'])) {
    cmsCore::c('images')->mwatermark = $cfg['watermark'];
}

//Выставляем правило по которому будут изменяться размеры изображения
cmsCore::c('images')->resize_type  = $cfg['resize_type'];
cmsCore::c('images')->mresize_type = $cfg['mresize_type'];
cmsCore::c('images')->sresize_type = $cfg['sresize_type'];

//Выставляем пути сохранения изображения
cmsCore::c('images')->big_dir    = PATH .'/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f .'/';
cmsCore::c('images')->medium_dir = PATH .'/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f .'/';
cmsCore::c('images')->small_dir  = PATH .'/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f .'/';

//Выставляем размеры большого изображения
if (!empty($cfg['imgs_big_w']) || !empty($cfg['imgs_big_h'])) {
    cmsCore::c('images')->new_bw = $cfg['imgs_big_w'];
    cmsCore::c('images')->new_bh = $cfg['imgs_big_h'];
}

//Выставляем размеры средней копии изображения
if (!empty($cfg['imgs_medium_w']) || !empty($cfg['imgs_medium_h'])) {
    cmsCore::c('images')->new_mw = $cfg['imgs_medium_w'];
    cmsCore::c('images')->new_mh = $cfg['imgs_medium_h'];
}

//Выставляем размеры маленькой копии изображения
if (!empty($cfg['imgs_small_w']) || !empty($cfg['imgs_small_h'])) {
    cmsCore::c('images')->new_sw = $cfg['imgs_small_w'];
    cmsCore::c('images')->new_sh = $cfg['imgs_small_h'];
}

//Выставляем качество итогового изображения
cmsCore::c('images')->quality = $cfg['imgs_quality'];

$file_name = cmsCore::c('images')->resize('file', true);

if (empty($file_name) && cmsCore::uploadError()) {
    cmsCore::jsonOutput(array('error' => cmsCore::uploadError(), 'msg' => ''), false);
} else if (empty($file_name)) {
    cmsCore::jsonOutput(array('error' => 'UNKNOWN ERROR', 'msg' => ''), false);
}

if (!$file_name) {
    cmsCore::jsonOutput(array('error' => cmsCore::uploadError(), 'msg' => ''), false);
}

$fileurl = $ym .'/'. $d .'/'. $f .'/'. $file_name;

$file_id = cmsCore::registerUploadImages($target_id, $target, $fileurl, $component);

cmsCore::jsonOutput(
    array(
        'small_src' => '/upload/'. $component .'/small/'. $fileurl,
        'medium_src' => '/upload/'. $component .'/medium/'. $fileurl,
        'big_src' => '/upload/'. $component .'/big/'. $fileurl,
        'id' => $file_id,
        'msg' => '/upload/'. $component .'/big/'. $fileurl // Будет удалено в скором времени
    ),
    false
);
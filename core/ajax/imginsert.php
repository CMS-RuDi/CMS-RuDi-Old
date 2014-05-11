<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.3                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2013                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/
    // при ajaxfileupload HTTP_X_REQUESTED_WITH не передается, устанавливем его - костыль :-) см. /core/ajax/ajax_core.php
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    if (!empty($_REQUEST['ses_id'])){
        session_id($_REQUEST['ses_id']);
    }

    define('PATH', $_SERVER['DOCUMENT_ROOT']);
    include(PATH.'/core/ajax/ajax_core.php');

    // загружать могут только авторизованные
    if (!cmsCore::c('user')->id){ cmsCore::halt(); }

    // Получаем компонент, с которого идет загрузка
    $component = cmsCore::request('component', 'str', '');
    
    // Проверяем установлен ли он
    if(!$inCore->isComponentInstalled($component)) { cmsCore::halt(); }
    
    // Загружаем конфигурацию компонента
    $cfg = $inCore->loadComponentConfig($component);
    
    /* Будет удален в скором времени */
    if (!isset($cfg['imgs_big_w']) && isset($cfg['img_w'])){
        $cfg['imgs_big_w'] = $cfg['img_w'];
    }
    if (!isset($cfg['imgs_big_h']) && isset($cfg['img_h'])){
        $cfg['imgs_big_h'] = $cfg['img_h'];
    }
    /* ============================= */
    
    $cfg = array_merge(
        array(
            'img_max' => 50,
            'img_on' => 1,
            'watermark' => 1,
            'img_w' => 600, // Будет удалено в скором времени
            'img_h' => 600, // Будет удалено в скором времени
            'imgs_big_w' => 600,
            'imgs_big_h' => 600,
            'imgs_medium_w' => 300,
            'imgs_medium_h' => 300,
            'imgs_small_w' => 100,
            'imgs_small_h' => 100,
            'resize_type' => 'auto'
        ),
        $cfg
    );
    
    // проверяем не выключен ли он
    if(!$cfg['component_enabled']) { cmsCore::halt(); }

    // id места назначения
    $target_id = cmsCore::request('target_id', 'int', 0);
    
    // место назначения в компоненте
    $target = cmsCore::request('target', 'str', '');
    
    // Разрешена ли загрузка
    if (!$cfg['img_on']){
        cmsCore::jsonOutput(array('error' => $_LANG['UPLOAD_IMG_IS_DISABLE'], 'msg' => ''), false);
    }
    
    // Если в модели компонента есть метод checkAccessAddImage передаем ему 
    // параметры $target_id и $target для проверки прав пользователя на загрузку
    // изображения
    if (method_exists(cmsCore::m($component), 'checkAccessAddImage')){
        if (!cmsCore::m($component)->checkAccessAddImage($target_id, $target)){
            cmsCore::jsonOutput(array('error' => $_LANG['UPLOAD_IMG_IS_DISABLE'], 'msg' => ''), false);
        }
    }

    // Не превышен ли лимит
    if (cmsCore::getTargetCount($target_id, $target, $component) >= $cfg['img_max']){
        cmsCore::jsonOutput(array('error' => $_LANG['UPLOAD_IMG_LIMIT'], 'msg' => ''), false);
    }
        
    $ym = date('Y-m');
    $d = date('d');
    $f = mb_substr(md5(microtime(true)), 0, 2);

    //Создаем необходимую структуру папок
    if(!is_dir(PATH .'/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f)){
        if (!mkdir(PATH .'/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f, 0777, true)){
            cmsCore::jsonOutput(array('error' => sprintf($_LANG['DIR_NOT_WRITABLE'], '/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f), 'msg' => ''), false);
        }
    }

    if(!is_dir(PATH .'/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f)){
        if (!mkdir(PATH .'/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f, 0777, true)){
            cmsCore::jsonOutput(array('error' => sprintf($_LANG['DIR_NOT_WRITABLE'], '/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f), 'msg' => ''), false);
        }
    }

    if(!is_dir(PATH .'/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f)){
        if (!mkdir(PATH .'/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f, 0777, true)){
            cmsCore::jsonOutput(array('error' => sprintf($_LANG['DIR_NOT_WRITABLE'], '/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f), 'msg' => ''), false);
        }
    }
    //----------------------------------------------------------------------

    //Выставляем опции отвечающие за нанесение водяного знака
    cmsCore::c('images')->watermark = $cfg['watermark'];
    cmsCore::c('images')->mwatermark = $cfg['watermark'];

    //Выставляем правило по которому будут изменяться размеры изображения
    cmsCore::c('images')->resize_type = $cfg['resize_type'];
    if (!empty($cfg['mresize_type'])){
        cmsCore::c('images')->mresize_type = $cfg['mresize_type'];
    }
    if (!empty($cfg['sresize_type'])){
        cmsCore::c('images')->sresize_type = $cfg['sresize_type'];
    }

    //Выставляем пути сохранения изображения
    cmsCore::c('images')->big_dir    = PATH .'/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f .'/';
    cmsCore::c('images')->medium_dir = PATH .'/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f .'/';
    cmsCore::c('images')->small_dir  = PATH .'/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f .'/';

    //Выставляем размеры большого изображения
    if (!empty($cfg['imgs_big_w']) || !empty($cfg['imgs_big_h'])){
        cmsCore::c('images')->new_bw = $cfg['imgs_big_w'];
        cmsCore::c('images')->new_bh = $cfg['imgs_big_h'];
    }

    //Выставляем размеры средней копии изображения
    if (!empty($cfg['imgs_medium_w']) || !empty($cfg['imgs_medium_h'])){
        cmsCore::c('images')->new_mw = $cfg['imgs_medium_w'];
        cmsCore::c('images')->new_mh = $cfg['imgs_medium_h'];
    }

    //Выставляем размеры маленькой копии изображения
    if (!empty($cfg['imgs_small_w']) || !empty($cfg['imgs_small_h'])){
        cmsCore::c('images')->new_sw = $cfg['imgs_small_w'];
        cmsCore::c('images')->new_sh = $cfg['imgs_small_h'];
    }

    $tmp_file_name = $component .'_'. md5(microtime(true)) .'.tmp';

    if (!cmsCore::moveUploadedFile(
        $_FILES['file']['tmp_name'],
        PATH .'/cache/'. $tmp_file_name,
        $_FILES['file']['error']
    )){
        cmsCore::jsonOutput(array('error' => cmsCore::uploadError(), 'msg' => ''), false);
    }

    $file_name = cmsCore::c('images')->resize(PATH .'/cache/'. $tmp_file_name);

    unlink(PATH .'/cache/'. $tmp_file_name);

    if (!$file_name){
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
?>
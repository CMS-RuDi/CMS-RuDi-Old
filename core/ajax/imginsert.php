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
    function echoErrorAndExit($error){
        cmsCore::jsonOutput(array('error' => $error, 'msg' => ''), false);
    }

    // при ajaxfileupload HTTP_X_REQUESTED_WITH не передается, устанавливем его - костыль :-) см. /core/ajax/ajax_core.php
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    if (!empty($_REQUEST['ses_id'])){
        session_id($_REQUEST['ses_id']);
    }

    define('PATH', $_SERVER['DOCUMENT_ROOT']);
    include(PATH.'/core/ajax/ajax_core.php');

    // загружать могут только авторизованные
    if (!$inUser->id) {	cmsCore::halt(); }

    // Получаем компонент, с которого идет загрузка
    $component = cmsCore::request('component', 'str', '');
    
    // Проверяем установлен ли он
    if(!$inCore->isComponentInstalled($component)) { cmsCore::halt(); }
    
    // Загружаем конфигурацию компонента
    $cfg = array_merge(
        array(
            'img_max' => 50,
            'img_on' => 1,
            'watermark' => 1,
            'img_w' => 600, // Будет удалено в скором времени
            'img_h' => 600, // Будет удалено в скором времени
            'img_big_w' => 600,
            'img_big_h' => 600,
            'img_medium_w' => 300,
            'img_medium_h' => 300,
            'resize_type' => 'auto',
            'img_table' => 'cms_upload_images'
        ),
        $inCore->loadComponentConfig($component)
    );
    
    // проверяем не выключен ли он
    if(!$cfg['component_enabled']) { cmsCore::halt(); }

    // id места назначения
    $target_id = cmsCore::request('target_id', 'int', 0);
    
    // место назначения в компоненте
    $target = cmsCore::request('target', 'str', '');
    
    // Разрешена ли загрузка
    if (!$cfg['img_on']){ echoErrorAndExit($_LANG['UPLOAD_IMG_IS_DISABLE']); }

    // Не превышен ли лимит
    if (cmsCore::getTargetCount($target_id) >= $cfg['img_max']){ echoErrorAndExit($_LANG['UPLOAD_IMG_LIMIT']); }
    
    if (!cmsCore::inRequest('is_new_method')){
        
    // Будет удалено в скором времени, после того как везде будет внедрен новый класс обработки изображений
        // Подготавливаем класс загрузки фото
        cmsCore::loadClass('upload_photo');
        $inUploadPhoto = cmsUploadPhoto::getInstance();
        $inUploadPhoto->upload_dir    = PATH .'/upload/';
        $inUploadPhoto->dir_medium    = $component .'/';
        $inUploadPhoto->medium_size_w = $cfg['img_w'];
        $inUploadPhoto->medium_size_h = $cfg['img_h'];
        $inUploadPhoto->is_watermark  = $cfg['watermark'];
        $inUploadPhoto->only_medium   = true;
        $inUploadPhoto->input_name    = 'attach_img';
        // загружаем фото
        $file = $inUploadPhoto->uploadPhoto();

        if (!$file){ echoErrorAndExit(cmsCore::uploadError()); }

        $fileurl = '/upload/'. $component .'/'. $file['filename'];

        cmsCore::registerUploadImages($target_id, $target, $fileurl, $component);

        cmsCore::jsonOutput(array('error' => '', 'msg' => $fileurl), false);
        
    }else{
        
        $ym = date('Y-m');
        $d = date('d');
        $f = mb_substr(md5(microtime(true)), 0, 2);
        
        //Создаем необходимую структуру папок
        if(!is_dir(PATH .'/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f)){
            if (!mkdir(PATH .'/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f, 0777, true)){
                echoErrorAndExit(str_replace('%s', '/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f, $_LANG['DIR_NOT_WRITABLE']));
            }
        }
        
        if(!is_dir(PATH .'/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f)){
            if (!mkdir(PATH .'/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f, 0777, true)){
                echoErrorAndExit(str_replace('%s', '/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f, $_LANG['DIR_NOT_WRITABLE']));
            }
        }
        
        if(!is_dir(PATH .'/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f)){
            if (!mkdir(PATH .'/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f, 0777, true)){
                echoErrorAndExit(str_replace('%s', '/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f, $_LANG['DIR_NOT_WRITABLE']));
            }
        }
        //----------------------------------------------------------------------
        
        //Подключаем и инициализируем класс обработки изображений
        cmsCore::loadClass('images');
        $image = rudi_graphics::getInstance();
        
        //Выставляем опции отвечающие за нанесение водяного знака
        $image->watermark = $cfg['watermark'];
        $image->mwatermark = $cfg['watermark'];
        
        //Выставляем правило по которому будут изменяться размеры изображения
        $image->resize_type = $cfg['resize_type'];
        
        //Выставляем пути сохранения изображения
        $image->big_dir = PATH .'/upload/'. $component .'/big/'. $ym .'/'. $d .'/'. $f .'/';
        $image->medium_dir = PATH .'/upload/'. $component .'/medium/'. $ym .'/'. $d .'/'. $f .'/';
        $image->small_dir = PATH .'/upload/'. $component .'/small/'. $ym .'/'. $d .'/'. $f .'/';
        
        //Выставляем размеры большого изображения
        if (!empty($cfg['img_big_w']) || !empty($cfg['img_big_h'])){
            $image->new_bw = $cfg['img_big_w'];
            $image->new_bh = $cfg['img_big_h'];
        }
        
        //Выставляем размеры средней копии изображения
        if (!empty($cfg['img_medium_w']) || !empty($cfg['img_medium_h'])){
            $image->new_mw = $cfg['img_medium_w'];
            $image->new_mh = $cfg['img_medium_h'];
        }
        
        //Выставляем размеры маленькой копии изображения
        if (!empty($cfg['img_small_w']) || !empty($cfg['img_small_h'])){
            $image->new_sw = $cfg['img_small_w'];
            $image->new_sh = $cfg['img_small_h'];
        }
        
        $tmp_file_name = $component .'_'. md5(microtime(true)) .'.tmp';
        
        if (!cmsCore::moveUploadedFile(
            $_FILES['file']['tmp_name'],
            PATH .'/cache/'. $tmp_file_name,
            $_FILES['file']['error']
        )){
            echoErrorAndExit(cmsCore::uploadError());
        }

        $file_name = $image->resize(PATH .'/cache/'. $tmp_file_name);
        
        unlink(PATH .'/cache/'. $tmp_file_name);
        
        if (!$file_name){ echoErrorAndExit(cmsCore::uploadError()); }

        $fileurl = $ym .'/'. $d .'/'. $f .'/'. $file_name;

        $file_id = cmsCore::registerUploadImages($target_id, $target, $fileurl, $component, $cfg['img_table']);

        cmsCore::jsonOutput(
            array(
                'small_src' => '/upload/'. $component .'/small/'. $fileurl,
                'medium_src' => '/upload/'. $component .'/medium/'. $fileurl,
                'big_src' => '/upload/'. $component .'/big/'. $fileurl,
                'id' => $file_id
            ),
            false
        );
    }
?>
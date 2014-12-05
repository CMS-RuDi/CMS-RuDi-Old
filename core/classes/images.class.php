<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.9                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

/**
 * Класс обработки графики CMS RuDi
 *
 * Предназначен для изменение размеров изображений, получения информации
 * об изображении нанесение водяного знака и других операций с изображениями
 * 
 * @author DS Soft <support@ds-soft.ru>
 * @version 0.0.4
 */
class rudi_graphics {
    private static $instance;
    public $Imagick = false;
    
    private static $type = array(
        1=>'gif', 2=>'jpg', 3=>'png', 4=>'swf', 5=>'psd', 6=>'bmp', 7=>'tiff_i', 8=>'tiff_m', 9=>'jpc', 10=>'jp2', 11=>'jpx'
    );
    private $new_img,
            $old_img,
            $ext,
            $w_ratio,
            $h_ratio,
            $w,
            $h,
            $inConf;
    public $small_dir = 'small/',
           $medium_dir = 'medium/',
           $big_dir,
           $filename,
           $resize_type = 'auto',
           $mresize_type,
           $sresize_type,
           $watermark = false,
           $mwatermark = false,
           $quality = 90,
           $new_sw,
           $new_sh,
           $new_mw,
           $new_mh,
           $new_bw,
           $new_bh;
    
    private function __construct() {
        $this->Imagick = self::checkImagick();
    }
    private function __clone() {}

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public static function checkImagick() {
        return class_exists('Imagick');
    }

    public static function getImgInfo($image_file) {
        $info = getimagesize($image_file);
        
        if ($info) {
            return array(
                'width'  => $info[0],
                'height' => $info[1],
                'type'   => self::$type[$info[2]]
            );
        }
        
        return false;
    }
    
    private function uploadOrDownloadImageFile($image_file, $upload_file = false) {
        if ($upload_file === true) {
            if (!empty($_FILES[$image_file]['name'])) {
                global $_LANG;

                $max_size = str_ireplace(
                    array('M','K'),
                    array('Mb','Kb'),
                    ini_get('upload_max_filesize')
                );

                $uploadErrors = array(
                    UPLOAD_ERR_OK         => $_LANG['UPLOAD_ERR_OK'],
                    UPLOAD_ERR_INI_SIZE   => $_LANG['UPLOAD_ERR_INI_SIZE'] .' &mdash; '. $max_size,
                    UPLOAD_ERR_FORM_SIZE  => $_LANG['UPLOAD_ERR_INI_SIZE'],
                    UPLOAD_ERR_PARTIAL    => $_LANG['UPLOAD_ERR_PARTIAL'],
                    UPLOAD_ERR_NO_FILE    => $_LANG['UPLOAD_ERR_NO_FILE'],
                    UPLOAD_ERR_NO_TMP_DIR => $_LANG['UPLOAD_ERR_NO_TMP_DIR'],
                    UPLOAD_ERR_CANT_WRITE => $_LANG['UPLOAD_ERR_CANT_WRITE'],
                    UPLOAD_ERR_EXTENSION  => $_LANG['UPLOAD_ERR_EXTENSION']
                );
                
                if ($_FILES[$image_file]['error'] !== UPLOAD_ERR_OK) {
                    if (isset($uploadErrors[$_FILES[$image_file]['error']])) {
                        $_SESSION['file_upload_error'] = $uploadErrors[$_FILES[$image_file]['error']];
                    } else {
                        $_SESSION['file_upload_error'] = $_LANG['UNKNOWN_ERROR'];
                    }
                } else {
                    return $_FILES[$image_file]['tmp_name'];
                }
            }
        } else {
            if (
                (mb_substr($image_file, 0, 7) == 'http://') ||
                (mb_substr($image_file, 0, 8) == 'https://')
            ) {
                $image_file2 = PATH .'/cache/'. md5($image_file .' '. microtime(true)) .'.tmp';

                if (!cmsCore::c('curl')->saveFile($image_file, $image_file2)) {
                    if (cmsCore::c('user')->is_admin) {
                        cmsCore::addSessionMessage($_LANG['UPLOAD_ERR_NO_FILE'], 'error');
                    }
                } else {
                    return $image_file2;
                }
            } else {
                return $image_file;
            }
        }
        
        return false;
    }
    
    private function createThumb($src, $dist, $new_w, $new_h, $resize_type, $watermark) {
        if (file_exists($dist)) {
            if (!is_writable($dist)) { return false; }
            unlink($dist);
        }
        
        if (!in_array($resize_type, array('exact', 'auto', 'crop', 'portrait', 'landscape'))) {
            $resize_type = 'auto';
        }
        
        if (!in_array($watermark, array('lt', 'lc', 'lb', 'rt', 'rc', 'rb', 'tc', 'c', 'bc')) && $watermark !== false) {
            $watermark = 'rb';
        }
        
        if ((int)$new_w) {
            if (
                ($this->w < $new_w && $this->h < $new_h) || 
                ($resize_type == 'portrait' && $this->h < $new_h) || 
                ($resize_type == 'landscape' && $this->w < $new_w)
            ) {
                copy($src, $dist);
            } else {
                $new_size = $this->getNewImageSize($new_w, $new_h, $resize_type);
                
                if ($this->Imagick === true) {
                    $image = new Imagick($src);
                    
                    switch ($resize_type) {
                        case 'auto':
                            if ($this->ext == 'gif'){
                                foreach ($image as $img) {
                                    $img->resizeImage($new_w, $new_h, Imagick::FILTER_LANCZOS, 1, true);
                                    $img->setImagePage($img->getImageWidth(), $img->getImageHeight(), 0, 0);
                                }
                            } else {
                                $image->resizeImage($new_w, $new_h, Imagick::FILTER_LANCZOS, 1, true);
                            }
                            
                            break;
                        case 'portrait':
                            if ($this->ext == 'gif'){
                                foreach ($image as $img) {
                                    $img->resizeImage(0, $new_h, Imagick::FILTER_LANCZOS, 1);
                                    $img->setImagePage($img->getImageWidth(), $img->getImageHeight(), 0, 0);
                                }
                            } else {
                                $image->resizeImage(0, $new_h, Imagick::FILTER_LANCZOS, 1);
                            }
                            break;
                        case 'landscape':
                            if ($this->ext == 'gif'){
                                foreach ($image as $img) {
                                    $img->resizeImage($new_w, 0, Imagick::FILTER_LANCZOS, 1);
                                    $img->setImagePage($img->getImageWidth(), $img->getImageHeight(), 0, 0);
                                }
                            } else {
                                $image->resizeImage($new_w, 0, Imagick::FILTER_LANCZOS, 1);
                            }
                            break;
                        case 'exact':
                            if ($this->ext == 'gif'){
                                foreach ($image as $img) {
                                    $img->resizeImage($new_w, $new_h, Imagick::FILTER_LANCZOS, 1);
                                    $img->setImagePage($img->getImageWidth(), $img->getImageHeight(), 0, 0);
                                }
                            } else {
                                $image->resizeImage($new_w, $new_h, Imagick::FILTER_LANCZOS, 1);
                            }
                            break;
                        case 'crop':
                            if ($this->ext == 'gif') {
                                foreach ($image as $img) {
                                    $img->cropThumbnailImage($new_w, $new_h);
                                    $img->setImagePage($img->getImageWidth(), $img->getImageHeight(), 0, 0);
                                }
                            } else {
                                $image->cropThumbnailImage($new_w, $new_h);
                            }
                            break;
                    }
                    
                    $image->setImageCompressionQuality($this->quality);
                    
                    if ($this->ext == 'jpg') {
                        $image->setImageBackgroundColor('white');
                        $image->flattenImages();
                        $image = $image->flattenImages();
                        $image->setImageCompression(Imagick::COMPRESSION_JPEG);
                    }
                    
                    $image->setImageFormat($this->ext);
                    
                    if ($this->ext == 'gif') {
                        $image->writeimages($dist);
                    } else {
                        $image->writeimage($dist);
                    }

                    $image->destroy();
                } else {
                    $this->new_img = imagecreatetruecolor($new_size['w'], $new_size['h']);

                    imagecopyresampled($this->new_img, $this->old_img, 0, 0, 0, 0, $new_size['w'], $new_size['h'], $this->w, $this->h);

                    if ($resize_type == 'crop') {
                        $this->crop($new_size['w'], $new_size['h'], $new_w, $new_h);
                    }

                    if ($this->ext != 'gif' || $this->Imagick === true) {
                        $this->saveImage($dist, $this->ext);
                    } else {
                        $gif_resize = new gifresizer();
                        $gif_resize->resize($src, $dist, $new_w, $new_h);
                    }
                }
            }
            
            if (!empty($watermark)) {
                self::addWatermark($dist, false, $watermark);
            }
        } else if ($new_w == 'copy') {
            copy($src, $dist);
        }
    }
    
    public function resize($image_file, $upload_file = false) {
        $this->checkSizeOpt();

        $image_file = $this->uploadOrDownloadImageFile($image_file, $upload_file);
        if ($image_file === false) { return false; }
        
        if (!$size = self::getImgInfo($image_file)) {
            if (cmsCore::c('user')->is_admin) {
                cmsCore::addSessionMessage($_LANG['NOT_SUPPORTED_FORMAT'], 'error');
            }
            return false;
        }
        
        $this->ext = $size['type'];
        if ($this->ext != 'jpg' && $this->ext != 'png' && $this->ext != 'gif') {
            if (cmsCore::c('user')->is_admin) {
                cmsCore::addSessionMessage($_LANG['NOT_SUPPORTED_FORMAT'], 'error');
            }
            return false;
        }
        
        $this->filename = ($this->filename ? $this->filename : md5(microtime() .' '. $image_file) .'.'. $this->ext);
        
        if ($this->ext == 'gif' && $this->Imagick === false) {
            cmsCore::loadClass('gif_resize');
        }
        
        $this->w       = $size['width'];
        $this->h       = $size['height'];
        $this->w_ratio = $this->w / $this->h;
        $this->h_ratio = $this->h / $this->w;
        
        if ($this->Imagick === false) {
            $this->loadImage($image_file);
            if (!is_resource($this->old_img)) {
                return false;
            }
        }
        
        $this->createThumb(
            $image_file,
            $this->big_dir . $this->filename,
            $this->new_bw,
            $this->new_bh,
            $this->resize_type,
            $this->watermark
        );
        
        $this->createThumb(
            $image_file,
            (mb_strstr($this->medium_dir, PATH) ? $this->medium_dir : $this->big_dir . $this->medium_dir) . $this->filename,
            $this->new_mw,
            $this->new_mh,
            $this->mresize_type ? $this->mresize_type : $this->resize_type,
            $this->mwatermark
        );
        
        $this->createThumb(
            $image_file,
            (mb_strstr($this->small_dir, PATH) ? $this->small_dir : $this->big_dir . $this->small_dir) . $this->filename,
            $this->new_sw,
            $this->new_sh,
            $this->sresize_type ? $this->sresize_type : $this->resize_type,
            false
        );

        $filename = $this->filename;
        
        unset($this->filename);
        
        if (mb_strstr($image_file, PATH .'/cache/')) { unlink($image_file); }
        
        return $filename;
    }

    public static function addWatermark($src_img, $wm_img, $pos='rb', $q=80) {
        if ($wm_img === false) {
            $wm_img = cmsConfig::getInstance()->wmark;
        }
        
        if (!$src_img || !$wm_img) { return false; }
        
        $size_src = self::getImgInfo($src_img);
        $size_wm  = self::getImgInfo($wm_img);
        
        if (!$size_src || !$size_wm) { return false; }
        
        if (self::checkImagick() === true) {
            $image = new Imagick($src_img);
            $wm    = new Imagick($wm_img);
            
            $wm->scaleImage($size_src['width']/10, 0);
            
            list($X, $Y) = $this->getWatermarkPos($size_src['width'], $size_src['height'], $wm->getImageWidth(), $wm->getImageHeight());
            if ($X < 0 || $Y < 0) { return false; }
            
            $wm->evaluateImage(Imagick::EVALUATE_MULTIPLY, 0.8, Imagick::CHANNEL_ALPHA);
            
            if ($size_src == 'gif') {
                foreach ($image as $img) {
                    $img->compositeImage($wm, imagick::COMPOSITE_OVER, $X, $Y);
                }
                
                $image->writeimages($src_img);
            } else {
                $image->compositeImage($wm, imagick::COMPOSITE_OVER, $X, $Y);
                $image->writeimage($src_img);
            }
            
            $image->destroy();
            $wm->destroy();
        } else {
            $wimage = imagecreatefrompng($wm_img);
            if ($size_wm['type'] != 'png' || empty($wimage)) { return false; }

            list($X, $Y) = $this->getWatermarkPos($size_src['width'], $size_src['height'], $size_wm['width'], $size_wm['height']);
            if ($X < 0 || $Y < 0) { return false; }

            switch($size_src['type']) {
                case 'jpg':
                    $image = imagecreatefromjpeg($src_img);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($src_img);
                    break;
                case 'png':
                    $image = imagecreatefrompng($src_img);
                    break;
                default: break;
            }

            if (!$image) { return false; }

            imagecopyresampled($image, $wimage, $X, $Y, 0, 0, $size_wm['width'], $size_wm['height'], $size_wm['width'], $size_wm['height']);

            switch($size_src['type']) {
                case 'jpg':
                    imagejpeg($image, $src_img, $q);
                    break;
                case 'gif':
                    imagegif($image, $src_img);
                    break;
                case 'png':
                    imagepng($image, $src_img, (9 - round($q*0.09)));
                    break;
            }

            imagedestroy($image);
            imagedestroy($wimage);
        }
        
        return true;
    }
    
    private function getWatermarkPos($img_w, $img_h, $wm_w, $wm_h) {
        switch ($pos) {
            case 'lt':
                $X = 0; $Y = 0;
                break;
            case 'lb':
                $X = 0; $Y = $img_h - $wm_h;
                break;
            case 'rt':
                $X = $img_w - $wm_w; $Y = 0;
                break;
            case 'rb':
                $X = $img_w - $wm_w;
                $Y = $img_h - $wm_h;
                break;
            case 'c':
                $X = ($img_w - $wm_w)/2;
                $Y = ($img_h - $wm_h)/2;
                break;
            case 'lc':
                $X = 0; $Y = ($img_h - $wm_h)/2;
                break;
            case 'rc':
                $X = $img_w - $wm_w;
                $Y = ($img_h - $wm_h)/2;
                break;
            case 'tc':
                $X = ($img_w - $wm_w)/2; $Y = 0;
                break;
            case 'bc':
                $X = ($img_w - $wm_w)/2;
                $Y = $img_h - $wm_h;
                break;
            default:
                $X = $img_w - $wm_w;
                $Y = $img_h - $wm_h;
                break;
        }
        
        return array($X, $Y);
    }

    private function crop($w, $h, $nw, $nh){
        $X = ($w/2) - ($nw/2);
        $Y = ($h/2) - ($nh/2);
        $crop = $this->new_img;
        $this->new_img = imagecreatetruecolor($nw , $nh);
        imagecopyresampled($this->new_img, $crop , 0, 0, $X, $Y, $nw, $nh , $nw, $nh);
    }

    private function getNewImageSize($w, $h, $rt){
        switch ($rt){
            case 'auto':
                    $size = $this->getSizeAuto($w, $h);
                    $nw = $size['w']; $nh = $size['h'];
                break;
            case 'exact':
                    $nw = $w; $nh = $h;
                break;
            case 'portrait':
                    $nw = $this->w_ratio*$h; $nh = $h;
                break;
            case 'landscape':
                    $nw = $w; $nh = $this->h_ratio*$w;
                break;
            case 'crop':
                    $size = $this->getSizeCrop($w, $h);
                    $nw = $size['w']; $nh = $size['h'];
                break;
            default:
                break;
        }
        return array('w' => $nw, 'h' => $nh);
    }

    private function getSizeAuto($width, $heigth){
        if ($this->h < $this->w){
            $w = $width; $h = $this->h_ratio*$width;
        }else if ($this->w < $this->h){
            $w = $this->w_ratio*$heigth; $h = $heigth;
        }else{
            if ($heigth < $width){
                $w = $width; $h = $this->h_ratio*$width;
            }else if ($width < $heigth){
                $w = $this->w_ratio*$heigth; $h = $heigth;
            }else{
                $w = $width; $h = $heigth;
            }
        }
        return array('w' => $w, 'h' => $h);
    }

    private function getSizeCrop($width, $heigth){
        if (($this->h/$heigth) < ($this->w/$width)){
            $w = $this->w_ratio*$heigth; $h = $heigth;
        }else{
            $w = $width; $h = $this->h_ratio*$width;
        }
        return array('w' => $w, 'h' => $h);
    }

    private function saveImage($save_path, $type='jpg'){
        switch($type){
            case 'jpg':
                imagejpeg($this->new_img, $save_path, $this->quality);
                break;
            case 'gif':
                imagegif($this->new_img, $save_path);
                break;
            case 'png':
                imagepng($this->new_img, $save_path, (9 - round($this->quality*0.09)));
                break;
        }
        imagedestroy($this->new_img);
        return true;
    }

    private function loadImage($image_file){
        switch($this->ext){
            case 'jpg':
                $this->old_img = imagecreatefromjpeg($image_file);
                break;
            case 'gif':
                $this->old_img = imagecreatefromgif($image_file);
                break;
            case 'png':
                $this->old_img = imagecreatefrompng($image_file);
                break;
            default:
                $this->old_img = false; return false;
                break;
        }
        return true;
    }

    private function checkSizeOpt() {
        if (!$this->new_bw xor !$this->new_bh) {
            if ($this->new_bh) {
                $this->new_bw = $this->new_bh;
            } else {
                $this->new_bh = $this->new_bw;
            }
        }
        if (!$this->new_mw xor !$this->new_mh) {
            if ($this->new_mh) {
                $this->new_mw = $this->new_mh;
            } else {
                $this->new_mh = $this->new_mw;
            }
        }
        if (!$this->new_sw xor !$this->new_sh) {
            if ($this->new_sh) {
                $this->new_sw = $this->new_sh;
            } else {
                $this->new_sh = $this->new_sw;
            }
        }
    }
    
}
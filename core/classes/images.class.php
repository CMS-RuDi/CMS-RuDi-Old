<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.4                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2013 DS Soft (http://ds-soft.ru/)               //
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
 * @version 0.0.2
 */
class rudi_graphics{
    private static $instance;
    private static $type = array(
        1=>'gif', 2=>'jpg', 3=>'png', 4=>'swf', 5=>'psd', 6=>'bmp', 7=>'tiff_i', 8=>'tiff_m', 9=>'jpc', 10=>'jp2', 11=>'jpx'
    );
    private $new_img,
            $old_img,
            $ext,
            $w_ratio,
            $h_ratio,
            $w,
            $h;
    public $small_dir = 'small/',
            $medium_dir = 'medium/',
            $big_dir,
            $filename,
            $resize_type = 'auto',
            $mresize_type,
            $sresize_type,
            $watermark = false,
            $mwatermark = false,
            $quality = 80,
            $new_sw,
            $new_sh,
            $new_mw,
            $new_mh,
            $new_bw,
            $new_bh;

    private function __construct(){}
    private function __clone(){}

    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getImgInfo($image_file){
        $info = @getimagesize($image_file);
        if ($info){
            return array(
                'width' => $info[0],
                'height' => $info[1],
                'type' => self::$type[$info[2]]
            );
        }
        return false;
    }

    public static function getResSize($image){
        if (!is_resource($image)){
            return false;
        }
        return array('width' => imagesx($image), 'heigth' => imagesy($image));
    }

    public function resize($image_file, $upload_file=false){
        $this->checkSizeOpt();
        $m_dir = mb_strstr($this->medium_dir, PATH) ? $this->medium_dir : $this->big_dir . $this->medium_dir;
        $s_dir = mb_strstr($this->small_dir, PATH) ? $this->small_dir : $this->big_dir . $this->small_dir;
        
        if ($upload_file === true){
            if (!empty($_FILES[$image_file]['name'])){
                global $_LANG;

                $max_size = ini_get('upload_max_filesize');
                $max_size = str_ireplace(array('M','K'), array('Mb','Kb'), $max_size);

                $uploadErrors = array(
                    UPLOAD_ERR_OK => $_LANG['UPLOAD_ERR_OK'],
                    UPLOAD_ERR_INI_SIZE => $_LANG['UPLOAD_ERR_INI_SIZE'].' &mdash; '.$max_size,
                    UPLOAD_ERR_FORM_SIZE => $_LANG['UPLOAD_ERR_INI_SIZE'],
                    UPLOAD_ERR_PARTIAL => $_LANG['UPLOAD_ERR_PARTIAL'],
                    UPLOAD_ERR_NO_FILE => $_LANG['UPLOAD_ERR_NO_FILE'],
                    UPLOAD_ERR_NO_TMP_DIR => $_LANG['UPLOAD_ERR_NO_TMP_DIR'],
                    UPLOAD_ERR_CANT_WRITE => $_LANG['UPLOAD_ERR_CANT_WRITE'],
                    UPLOAD_ERR_EXTENSION => $_LANG['UPLOAD_ERR_EXTENSION']
                );
                
                if($_FILES[$image_file]['error'] !== UPLOAD_ERR_OK && isset($uploadErrors[$_FILES[$image_file]['error']])){
                    $_SESSION['file_upload_error'] = $uploadErrors[$errorCode];
                    return false;
                }
                
                $image_file = $_FILES[$image_file]['tmp_name'];
            }else{
                return false;
            }
        }else{
            if (
                (mb_substr($image_file, 0, 7) == 'http://') ||
                (mb_substr($image_file, 0, 8) == 'https://')
            ){
                $image_file2 = PATH .'/cache/'. md5($image_file .' '. microtime(true)) .'.tmp';

                if (!cmsCore::c('curl')->saveFile($image_file, $image_file2)){
                    return false;
                }
                cmsCore::cd('curl');

                $image_file = $image_file2;
            }
        }
        
        if (!$size = self::getImgInfo($image_file)){
            return false;
        }
        
        $this->ext = $size['type'];
        if ($this->ext != 'jpg' and $this->ext != 'png' and $this->ext != 'gif'){
            return false;
        }
        
        $this->filename = ($this->filename ? $this->filename : md5(time() ." ". $image_file) .'.'. $this->ext);
        
        if ($this->ext == 'gif'){
            cmsCore::loadClass('gif_resize');
            $gif_resize = new gifresizer();
        }
        
        $this->w = $size['width'];
        $this->h = $size['height'];
        $this->w_ratio = $this->w / $this->h;
        $this->h_ratio = $this->h / $this->w;
        
        $this->loadImage($image_file);
        if (!is_resource($this->old_img)){
            return false;
        }
        
        if ($this->watermark or $this->mwatermark){
            $inConf = cmsConfig::getInstance();
        }
        
        if ((int)$this->new_bw){
            if (
                ($this->w < $this->new_bw and $this->h < $this->new_bh) or 
                ($this->resize_type == 'portrait' and $this->h < $this->new_bh) or 
                ($this->resize_type == 'landscape' and $this->w < $this->new_bw)
            ){
                copy($image_file, $this->big_dir . $this->filename);
            }else{
                $new_size = $this->getNewImageSize($this->new_bw, $this->new_bh, $this->resize_type);
                $this->new_img = imagecreatetruecolor($new_size['w'], $new_size['h']);
                imagecopyresampled($this->new_img, $this->old_img, 0, 0, 0, 0, $new_size['w'], $new_size['h'], $this->w, $this->h);
                if ($this->resize_type == 'crop'){
                    $this->crop($new_size['w'], $new_size['h'], $this->new_bw, $this->new_bh);
                }
                if ($this->ext != 'gif'){
                    $this->saveImage($this->big_dir . $this->filename, $this->ext);
                }else{
                    $gif_resize->resize($image_file, $this->big_dir . $this->filename, $this->new_bw, $this->new_bh);
                }
            }
            
            if (!empty($this->watermark)){
                self::addWatermark($this->big_dir . $this->filename, PATH .'/images/'. $inConf->wmark, $this->watermark);
            }
        }else if ($this->new_bw == 'copy'){
            copy($image_file, $this->big_dir . $this->filename);
        }
        
        if ($this->new_mw){
            if ($this->w < $this->new_mw and $this->h < $this->new_mh){
                copy($image_file, $m_dir . $this->filename);
            }else{
                $rt = $this->mresize_type ? $this->mresize_type : $this->resize_type;
                if (($rt == 'portrait' and $this->h < $this->new_mh) or 
                ($rt == 'landscape' and $this->w < $this->new_mw)){
                    copy($image_file, $m_dir . $this->filename);
                }else{
                    $new_size = $this->getNewImageSize($this->new_mw, $this->new_mh, $rt);
                    $this->new_img = imagecreatetruecolor($new_size['w'], $new_size['h']);
                    imagecopyresampled($this->new_img, $this->old_img, 0, 0, 0, 0, $new_size['w'], $new_size['h'], $this->w, $this->h);
                    if ($rt == 'crop'){
                        $this->crop($new_size['w'], $new_size['h'], $this->new_mw, $this->new_mh);
                    }
                }
            }
            if ($this->ext != 'gif'){
                $this->saveImage($m_dir . $this->filename, $this->ext);
            }else{
                $gif_resize->resize($image_file, $m_dir . $this->filename, $this->new_mw, $this->new_mh);
            }
            if (!empty($this->mwatermark)){
                self::addWatermark($m_dir . $this->filename, PATH .'/images/'. $inConf->wmark, $this->mwatermark);
            }
        }
        
        if ($this->new_sw){
            if ($this->w < $this->new_sw and $this->h < $this->new_sh){
                copy($image_file, $s_dir . $this->filename);
            }else{
                $rt = $this->sresize_type ? $this->sresize_type : $this->resize_type;
                if (($rt == 'portrait' and $this->h < $this->new_sh) or 
                ($rt == 'landscape' and $this->w < $this->new_sw)){
                    copy($image_file, $s_dir . $this->filename);
                }else{
                    $new_size = $this->getNewImageSize($this->new_sw, $this->new_sh, $rt);
                    $this->new_img = imagecreatetruecolor($new_size['w'], $new_size['h']);
                    imagecopyresampled($this->new_img, $this->old_img, 0, 0, 0, 0, $new_size['w'], $new_size['h'], $this->w, $this->h);
                    if ($rt == 'crop'){
                        $this->crop($new_size['w'], $new_size['h'], $this->new_sw, $this->new_sh);
                    }
                }
            }
            if ($this->ext != 'gif'){
                $this->saveImage($s_dir . $this->filename, $this->ext);
            }else{
                $gif_resize->resize($image_file, $s_dir . $this->filename, $this->new_sw, $this->new_sh);
            }
        }
        
        $filename = $this->filename;
        unset($this->filename);
        
        if (mb_strstr($image_file, PATH .'/cache/')){
            unlink($image_file);
        }
        
        return $filename;
    }

    public static function addWatermark($src_img, $wm_img, $pos='rb', $q=80){
        if (!$src_img or !$wm_img){
            return false;
        }
        $size_src = self::getImgInfo($src_img);
        $size_wm = self::getImgInfo($wm_img);
        $wimage = @imagecreatefrompng($wm_img);
        if (!$size_src or !$size_wm or $size_wm['type'] != 'png' or !$wimage){
            return false;
        }
        switch($size_src['type']){
            case 'jpg':
                $image = @imagecreatefromjpeg($src_img);
                break;
            case 'gif':
                $image = @imagecreatefromgif($src_img);
                break;
            case 'png':
                $image = @imagecreatefrompng($src_img);
                break;
            default: break;
        }
        if (!$image){
            return false;
        }

        switch ($pos) {
            case 'lt':
                $X = 0; $Y = 0;
                break;
            case 'lb':
                $X = 0; $Y = $size_src['height'] - $size_wm['height'];
                break;
            case 'rt':
                $X = $size_src['width'] - $size_wm['width']; $Y = 0;
                break;
            case 'rb':
                $X = $size_src['width'] - $size_wm['width'];
                $Y = $size_src['height'] - $size_wm['height'];
                break;
            case 'c':
                $X = ($size_src['width'] - $size_wm['width'])/2;
                $Y = ($size_src['height'] - $size_wm['height'])/2;
                break;
            case 'lc':
                $X = 0; $Y = ($size_src['height'] - $size_wm['height'])/2;
                break;
            case 'rc':
                $X = $size_src['width'] - $size_wm['width'];
                $Y = ($size_src['height'] - $size_wm['height'])/2;
                break;
            case 'tc':
                $X = ($size_src['width'] - $size_wm['width'])/2; $Y = 0;
                break;
            case 'bc':
                $X = ($size_src['width'] - $size_wm['width'])/2;
                $Y = $size_src['height'] - $size_wm['height'];
                break;
            default:
                $X = $size_src['width'] - $size_wm['width'];
                $Y = $size_src['height'] - $size_wm['height'];
                break;
        }
        
        if ($X<0 or $Y<0){
            return false;
        }
        
        imagecopyresampled($image, $wimage, $X, $Y, 0, 0, $size_wm['width'], $size_wm['height'], $size_wm['width'], $size_wm['height']);
        switch($size_src['type']){
            case 'jpg':
                @imagejpeg($image, $src_img, $q);
                break;
            case 'gif':
                @imagegif($image, $src_img);
                break;
            case 'png':
                @imagepng($image, $src_img, (9 - round($q*0.09)));
                break;
        }
        imagedestroy($image);
        imagedestroy($wimage);
        return true;
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
                @imagejpeg($this->new_img, $save_path, $this->quality);
                break;
            case 'gif':
                @imagegif($this->new_img, $save_path);
                break;
            case 'png':
                @imagepng($this->new_img, $save_path, (9 - round($this->quality*0.09)));
                break;
        }
        @imagedestroy($this->new_img);
        return true;
    }

    private function loadImage($image_file){
        switch($this->ext){
            case 'jpg':
                $this->old_img = @imagecreatefromjpeg($image_file);
                break;
            case 'gif':
                $this->old_img = @imagecreatefromgif($image_file);
                break;
            case 'png':
                $this->old_img = @imagecreatefrompng($image_file);
                break;
            default:
                $this->old_img = false; return false;
                break;
        }
        return true;
    }

    private function checkSizeOpt(){
        if (!$this->new_bw xor !$this->new_bh){
            if ($this->new_bh){
                $this->new_bw = $this->new_bh;
            }else{
                $this->new_bh = $this->new_bw;
            }
        }
        if (!$this->new_mw xor !$this->new_mh){
            if ($this->new_mh){
                $this->new_mw = $this->new_mh;
            }else{
                $this->new_mh = $this->new_mw;
            }
        }
        if (!$this->new_sw xor !$this->new_sh){
            if ($this->new_sh){
                $this->new_sw = $this->new_sh;
            }else{
                $this->new_sh = $this->new_sw;
            }
        }
    }
}
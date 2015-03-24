<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_captcha extends cmsPlugin {
    public function __construct() {
        $this->info = array(
            'plugin'      => 'p_captcha',
            'title'       => 'Captcha.ru',
            'description' => 'PHP Captcha с сайта Captcha.ru',
            'author'      => 'Kruglov Sergei',
            'version'     => '2.0',
            'type'        => 'captcha'
        );
        
        $this->events = array(
            'INSERT_CAPTCHA',
            'CHECK_CAPTCHA'
        );
        
        parent::__construct();
    }

    public function execute($event='', $item=array()) {
        if ($event == 'INSERT_CAPTCHA') { return $this->insert_captcha(); }
        if ($event == 'CHECK_CAPTCHA') { return $this->check_captcha(); }
        
        return false;
    }
    
// ==================================================================== //
    
    private function insert_captcha() {
        cmsCore::c('page')->addHead('<script type="text/javascript"> function reloadCaptcha(captcha_id) { $(\'img#captcha\' + captcha_id).attr(\'src\', \'/plugins/p_captcha/codegen/cms_codegen.php?captcha_id=\'+ captcha_id +\'&rand=\' + Math.random()); } </script>');

        return cmsPage::initTemplate('plugins', 'p_captcha')->assign('captcha_id', $this->getCaptchaId())->fetch();
    }
    
    private function check_captcha() {
        $captcha_code = cmsCore::request('captcha_code', 'str', '');
        $captcha_id   = cmsCore::request('captcha_id', 'int', 0);
        
        if (empty($captcha_code) || empty($_SESSION['captcha'][$captcha_id])) {
            return false;
        }
        
        $result = $_SESSION['captcha'][$captcha_id] == $captcha_code;
        
        unset($_SESSION['captcha'][$captcha_id]);

        return $result;
    }
    
    private function getCaptchaId() {
        if (empty($_SESSION['captcha']) || !is_array($_SESSION['captcha'])) {
            $_SESSION['captcha'] = array();
        }
        
        $_SESSION['captcha'][] = '';
        
        end($_SESSION['captcha']);
        
        $key = key($_SESSION['captcha']);
        
        reset($_SESSION['captcha']);
        
        return $key;
    }
}
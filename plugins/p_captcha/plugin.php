<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.7                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2013 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_captcha extends cmsPlugin {
    public function __construct() {
        parent::__construct();

        $this->info['plugin']      = 'p_captcha';
        $this->info['title']       = 'Captcha.ru';
        $this->info['description'] = 'PHP Captcha с сайта Captcha.ru';
        $this->info['author']      = 'Kruglov Sergei';
        $this->info['version']     = '2.0';
        $this->info['type']        = 'captcha';

        $this->events[]            = 'INSERT_CAPTCHA';
        $this->events[]            = 'CHECK_CAPTCHA';
    }

// ==================================================================== //

    public function install() {
        return parent::install();
    }

// ==================================================================== //

    public function upgrade() {
        return parent::upgrade();
    }

// ==================================================================== //

    public function execute($event='', $item=array()) {
        parent::execute();
        
        if ($event == 'INSERT_CAPTCHA') { return $this->insert_captcha(); }
        if ($event == 'CHECK_CAPTCHA') { return $this->check_captcha(); }
        
        return false;
    }
    
// ==================================================================== //
    
    private function insert_captcha() {
        cmsCore::c('page')->addHead('<script type="text/javascript"> function reloadCaptcha(captcha_id) { $(\'img#captcha\' + captcha_id).attr(\'src\', \'/plugins/p_captcha/codegen/cms_codegen.php?captcha_id=\'+ captcha_id +\'&rand=\' + Math.random()); } </script>');

        ob_start();
            cmsPage::initTemplate('plugins', 'p_captcha')->
                assign('captcha_id', $this->getCaptchaId())->
                display('p_captcha');
        return ob_get_clean();
    }
    
    private function check_captcha() {
        $captcha_code = cmsCore::request('captcha_code', 'str', '');
        $captcha_id   = cmsCore::request('captcha_id', 'int', 0);
        
        if (
            empty($captcha_code) ||
            empty($captcha_id) ||
            empty($_SESSION['captcha'][$captcha_id])
        ) {
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
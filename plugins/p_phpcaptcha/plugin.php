<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.9                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_phpcaptcha extends cmsPlugin {
    public function __construct() {
        parent::__construct();
        
        $this->info = array(
            'plugin'      => 'p_phpcaptcha',
            'title'       => 'PHPCaptcha.Org',
            'description' => 'PHP Captcha с сайта https://www.phpcaptcha.org',
            'author'      => 'DS Soft',
            'version'     => '0.0.1',
            'type'        => 'captcha'
        );
        
        $this->config = array(
            
        );
        
        $this->events = array( 'INSERT_CAPTCHA', 'CHECK_CAPTCHA');
    }

    public function execute($event='', $item=array()) {
        parent::execute();
        
        if ($event == 'INSERT_CAPTCHA') { return $this->insert_captcha(); }
        if ($event == 'CHECK_CAPTCHA') { return $this->check_captcha(); }

        return false;
    }
    
    private function insert_captcha() {
        require_once PATH .'/plugins/p_phpcaptcha/securimage/securimage.php';
        
        return Securimage::getCaptchaHtml();
    }
    
    private function check_captcha() {
        require_once PATH .'/plugins/p_phpcaptcha/securimage/securimage.php';
        
        $image = new Securimage();
        
        return $image->check(cmsCore::request('captcha_code', 'str', '')) == true;
    }
}
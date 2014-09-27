<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.7                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2013 DS Soft (http://ds-soft.ru/)               //
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
    
    public function getConfigFields() {
        global $_LANG;
        
        return array(
            array(
                'type' => 'fieldset',
                'title' => $_LANG['PRC_KEYS'],
                'fields' => array(
                    array(
                        'type' => 'text',
                        'title' => $_LANG['PRC_DOMENS'],
                        'description' => $_LANG['PRC_DOMENS_HINT'],
                        'name' => 'rpc_domens'
                    ),
                    array(
                        'type' => 'text',
                        'title' => $_LANG['PRC_PUBLIC_KEY'],
                        'name' => 'rpc_public_key'
                    ),
                    array(
                        'type' => 'text',
                        'title' => $_LANG['PRC_PRIVATE_KEY'],
                        'name' => 'rpc_private_key'
                    ),
                )
            ),
            array(
                'type' => 'fieldset',
                'title' => $_LANG['PRC_APPEARANCE'],
                'fields' => array(
                    array(
                        'type' => 'select',
                        'title' => $_LANG['PRC_THEME'],
                        'name' => 'rpc_theme',
                        'options' => array(
                            array( 'title' => 'Red', 'value' => 'red' ),
                            array( 'title' => 'White', 'value' => 'white' ),
                            array( 'title' => 'Blackglass', 'value' => 'blackglass' ),
                            array( 'title' => 'Clean', 'value' => 'clean' ),
                        )
                    ),
                    array(
                        'type' => 'select',
                        'title' => $_LANG['PRC_LANG'],
                        'name' => 'rpc_lang',
                        'options' => array(
                            array( 'title' => $_LANG['PRC_LANG_RU'], 'value' => 'ru' ),
                            array( 'title' => $_LANG['PRC_LANG_EN'], 'value' => 'en' ),
                            array( 'title' => $_LANG['PRC_LANG_NL'], 'value' => 'nl' ),
                            array( 'title' => $_LANG['PRC_LANG_FR'], 'value' => 'fr' ),
                            array( 'title' => $_LANG['PRC_LANG_DE'], 'value' => 'de' ),
                            array( 'title' => $_LANG['PRC_LANG_PT'], 'value' => 'pt' ),
                            array( 'title' => $_LANG['PRC_LANG_ES'], 'value' => 'es' ),
                            array( 'title' => $_LANG['PRC_LANG_TR'], 'value' => 'tr' )
                        )
                    )
                )
            )
        );
    }

    public function install() {
        return parent::install();
    }

    public function upgrade() {
        return parent::upgrade();
    }
    
    public function uninstall() {
        return parent::uninstall();
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
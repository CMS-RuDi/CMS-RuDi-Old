<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.8                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_recaptcha extends cmsPlugin {
    public function __construct() {
        parent::__construct();
        
        $this->info = array(
            'plugin'      => 'p_recaptcha',
            'title'       => 'reCaptcha',
            'description' => 'reCaptcha капча от гугла https://www.google.com/recaptcha/',
            'author'      => 'DS Soft',
            'version'     => '0.0.2',
            'type'        => 'captcha'
        );
        
        $this->config = array(
            'rpc_domens'      => '',
            'rpc_public_key'  => '',
            'rpc_private_key' => '',
            'rpc_theme'       => 'blackglass',
            'rpc_lang'        => 'ru'
        );
        
        $this->events = array( 'INSERT_CAPTCHA', 'CHECK_CAPTCHA' );
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

    public function execute($event='', $item=array()) {
        parent::execute();
        
        if (!empty($this->config['rpc_public_key']) && !empty($this->config['rpc_private_key'])) {
            cmsCore::includeFile('plugins/p_recaptcha/recaptcha/recaptchalib.php');

            if ($event == 'INSERT_CAPTCHA') { return $this->insert_captcha(); }
            if ($event == 'CHECK_CAPTCHA') { return $this->check_captcha(); }
        }

        return false;
    }
    
    private function insert_captcha() {
        if (!empty($this->config['rpc_domens'])) {
            $id = array_search(cmsCore::getHost(), explode(',', $this->config['rpc_domens']));
            $public_keys = explode(',', $this->config['rpc_public_key']);
            $public_key = $public_keys[$id];
        } else {
            $public_key = $this->config['rpc_public_key'];
        }
        
        cmsCore::c('page')->addHeadJS('http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
        cmsCore::c('page')->addHead('<script type="text/javascript">function createGoogleRecaptcha(){Recaptcha.create("'. $public_key .'", "google_recaptcha", { theme: "'. $this->config['rpc_theme'] .'", lang:"'. $this->config['rpc_lang'] .'", callback: Recaptcha.focus_response_field });} function setTimeoutRecaptcha() { setTimeout(function () { try { createGoogleRecaptcha(); } catch(e) { setTimeoutRecaptcha(); } }, 300); } setTimeoutRecaptcha();</script>');
         
        return '<div id="google_recaptcha"></div>';
    }
    
    private function check_captcha() {
        if (!empty($this->config['rpc_domens'])) {
            $id = array_search(cmsCore::getHost(), explode(',', $this->config['rpc_domens']));
            $private_keys = explode(',', $this->config['rpc_private_key']);
            $private_key = $private_keys[$id];
        } else {
            $private_key = $this->config['rpc_private_key'];
        }
        
        $resp = recaptcha_check_answer(
            $private_key,
            $_SERVER['REMOTE_ADDR'],
            cmsCore::request('recaptcha_challenge_field', 'str', ''),
            cmsCore::request('recaptcha_response_field', 'str', '')
        );

        if ($resp->is_valid) {
            return true;
        } else {
            return false;
        }
    }
}
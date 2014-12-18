<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.9                                //
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
            'version'     => '0.0.3',
            'type'        => 'captcha'
        );
        
        $this->config = array(
            'rpc_public_key'  => '',
            'rpc_private_key' => '',
            'rpc_theme'       => 'light',
            'rpc_lang'        => 'ru',
            'rpc_type'        => 'image'
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
                        'type'  => 'text',
                        'title' => $_LANG['PRC_PUBLIC_KEY'],
                        'name'  => 'rpc_public_key'
                    ),
                    array(
                        'type'  => 'text',
                        'title' => $_LANG['PRC_PRIVATE_KEY'],
                        'name'  => 'rpc_private_key'
                    ),
                )
            ),
            array(
                'type' => 'fieldset',
                'title' => $_LANG['PRC_APPEARANCE'],
                'fields' => array(
                    array(
                        'type' => 'select',
                        'title' => $_LANG['PRC_TYPE'],
                        'name' => 'rpc_type',
                        'options' => array(
                            array( 'title' => $_LANG['PRC_TYPE_IMAGE'],  'value' => 'image' ),
                            array( 'title' => $_LANG['PRC_TYPE_AUDIO'], 'value' => 'audio' )
                        )
                    ),
                    array(
                        'type' => 'select',
                        'title' => $_LANG['PRC_THEME'],
                        'name' => 'rpc_theme',
                        'options' => array(
                            array( 'title' => $_LANG['PRC_THEME_DARK'],  'value' => 'dark' ),
                            array( 'title' => $_LANG['PRC_THEME_LIGHT'], 'value' => 'light' )
                        )
                    ),
                    array(
                        'type' => 'select',
                        'title' => $_LANG['PRC_LANG'],
                        'name' => 'rpc_lang',
                        'options' => array(
                            array( 'title' => $_LANG['PRC_LANG_RU'], 'value' => 'ru' ),
                            array( 'title' => $_LANG['PRC_LANG_EN-GB'], 'value' => 'en-GB' ),
                            array( 'title' => $_LANG['PRC_LANG_EN'], 'value' => 'en' ),
                            array( 'title' => $_LANG['PRC_LANG_AR'], 'value' => 'ar' ),
                            array( 'title' => $_LANG['PRC_LANG_BG'], 'value' => 'bg' ),
                            array( 'title' => $_LANG['PRC_LANG_CA'], 'value' => 'ca' ),
                            array( 'title' => $_LANG['PRC_LANG_ZH-CN'], 'value' => 'zh-CN' ),
                            array( 'title' => $_LANG['PRC_LANG_ZH-TW'], 'value' => 'zh-TW' ),
                            array( 'title' => $_LANG['PRC_LANG_HR'], 'value' => 'hr' ),
                            array( 'title' => $_LANG['PRC_LANG_CS'], 'value' => 'cs' ),
                            array( 'title' => $_LANG['PRC_LANG_DA'], 'value' => 'da' ),
                            array( 'title' => $_LANG['PRC_LANG_NL'], 'value' => 'nl' ),
                            array( 'title' => $_LANG['PRC_LANG_FIL'], 'value' => 'fil' ),
                            array( 'title' => $_LANG['PRC_LANG_FI'], 'value' => 'fi' ),
                            array( 'title' => $_LANG['PRC_LANG_FR'], 'value' => 'fr' ),
                            array( 'title' => $_LANG['PRC_LANG_FR-CA'], 'value' => 'fr-CA' ),
                            array( 'title' => $_LANG['PRC_LANG_DE'], 'value' => 'de' ),
                            array( 'title' => $_LANG['PRC_LANG_DE-AT'], 'value' => 'de-AT' ),
                            array( 'title' => $_LANG['PRC_LANG_DE-CH'], 'value' => 'de-CH' ),
                            array( 'title' => $_LANG['PRC_LANG_EL'], 'value' => 'el' ),
                            array( 'title' => $_LANG['PRC_LANG_IW'], 'value' => 'iw' ),
                            array( 'title' => $_LANG['PRC_LANG_HI'], 'value' => 'hi' ),
                            array( 'title' => $_LANG['PRC_LANG_HU'], 'value' => 'hu' ),
                            array( 'title' => $_LANG['PRC_LANG_ID'], 'value' => 'id' ),
                            array( 'title' => $_LANG['PRC_LANG_IT'], 'value' => 'it' ),
                            array( 'title' => $_LANG['PRC_LANG_JA'], 'value' => 'ja' ),
                            array( 'title' => $_LANG['PRC_LANG_KO'], 'value' => 'ko' ),
                            array( 'title' => $_LANG['PRC_LANG_LV'], 'value' => 'lv' ),
                            array( 'title' => $_LANG['PRC_LANG_LT'], 'value' => 'lt' ),
                            array( 'title' => $_LANG['PRC_LANG_NO'], 'value' => 'no' ),
                            array( 'title' => $_LANG['PRC_LANG_FA'], 'value' => 'fa' ),
                            array( 'title' => $_LANG['PRC_LANG_PL'], 'value' => 'pl' ),
                            array( 'title' => $_LANG['PRC_LANG_PT'], 'value' => 'pt' ),
                            array( 'title' => $_LANG['PRC_LANG_PT-BR'], 'value' => 'pt-BR' ),
                            array( 'title' => $_LANG['PRC_LANG_PT-PT'], 'value' => 'pt-PT' ),
                            array( 'title' => $_LANG['PRC_LANG_RO'], 'value' => 'ro' ),
                            array( 'title' => $_LANG['PRC_LANG_SR'], 'value' => 'sr' ),
                            array( 'title' => $_LANG['PRC_LANG_SK'], 'value' => 'sk' ),
                            array( 'title' => $_LANG['PRC_LANG_SL'], 'value' => 'sl' ),
                            array( 'title' => $_LANG['PRC_LANG_ES'], 'value' => 'es' ),
                            array( 'title' => $_LANG['PRC_LANG_ES-419'], 'value' => 'es-419' ),
                            array( 'title' => $_LANG['PRC_LANG_SV'], 'value' => 'sv' ),
                            array( 'title' => $_LANG['PRC_LANG_TH'], 'value' => 'th' ),
                            array( 'title' => $_LANG['PRC_LANG_TR'], 'value' => 'tr' ),
                            array( 'title' => $_LANG['PRC_LANG_UK'], 'value' => 'uk' ),
                            array( 'title' => $_LANG['PRC_LANG_VI'], 'value' => 'vi' )
                        )
                    )
                )
            )
        );
    }

    public function execute($event='', $item=array()) {
        parent::execute();
        
        if (!empty($this->config['rpc_public_key']) && !empty($this->config['rpc_private_key'])) {
            if ($event == 'INSERT_CAPTCHA') { return $this->insert_captcha(); }
            if ($event == 'CHECK_CAPTCHA') { return $this->check_captcha(); }
        }

        return false;
    }
    
    private function insert_captcha() {
        if (!cmsCore::isAjax()) {
            cmsCore::c('page')->addHeadJS('https://www.google.com/recaptcha/api.js?hl='. $this->config['rpc_lang']);
        } else {
            cmsCore::c('page')->addHead('<script type="text/javascript">if (!document.getElementById("rescript")) { var rescript = document.createElement("script"); rescript.src = "https://www.google.com/recaptcha/api.js?hl='. $this->config['rpc_lang'] .'"; rescript.id = "rescript"; document.documentElement.children[0].appendChild(rescript); }</script>');
        }
        
        return cmsPage::initTemplate('plugins', 'p_recaptcha')->
                assign('config', $this->config)->
                assign('captcha_id', $this->getCaptchaId())->
                fetch();
    }
    
    private function check_captcha() {
        $captcha_id   = cmsCore::request('captcha_id', 'int', 0);
        $captcha_code = cmsCore::request('captcha_code'. $captcha_id, 'str', '');
        
        
        if (empty($captcha_code)) { return false; }
        
        $result = cmsCore::c('curl')->jsonGet('https://www.google.com/recaptcha/api/siteverify?secret='. $this->config['rpc_private_key'] .'&response='. $captcha_code .'&remoteip='. cmsCore::c('user')->ip, true);
        
        return $result['success'];
    }
    
    private function getCaptchaId() {
        if (!isset($_SESSION['reCaptchaID'])) {
            $_SESSION['reCaptchaID'] = 0;
        }
        
        $_SESSION['reCaptchaID']++;

        return $_SESSION['reCaptchaID'];
    }
}
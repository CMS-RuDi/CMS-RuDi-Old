<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.5                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

class p_loginza extends cmsPlugin {
    public $info = array(
        'plugin'      => 'p_loginza',
        'title'       => 'Авторизация Loginza',
        'description' => 'Позволяет посетителям авторизоваться на сайте, используя аккаунты популярных социальных сетей',
        'author'      => 'InstantCMS Team',
        'version'     => '1.10.4'
    );
    
    public $config = array(
        'PL_PROVIDERS' => 'vkontakte,facebook,mailruapi,google,yandex,openid,twitter,webmoney,rambler,flickr,mailru,loginza,myopenid,lastfm,verisign,aol,steam',
        'PL_LANG'      => 'ru'
    );
    
    public $events = array(
        'LOGINZA_BUTTON',
        'LOGINZA_AUTH'
    );
    
    public function getConfigFields() {
        global $_LANG;
        return array(
            array(
                'type' => 'textarea',
                'title' => $_LANG['PL_PROVIDERS'],
                'name' => 'PL_PROVIDERS'
            ),
            array(
                'type' => 'select',
                'title' => $_LANG['PL_LANG'],
                'name' => 'PL_LANG',
                'options' => array(
                    'title' => 'RU', 'value' => 'ru',
                    'title' => 'UK', 'value' => 'uk',
                    'title' => 'BE', 'value' => 'be',
                    'title' => 'FR', 'value' => 'fr',
                    'title' => 'EN', 'value' => 'en'
                )
            )
        );
    }

    /**
     * Процедура установки плагина
     * @return bool
     */
    public function install() {
        if (!cmsCore::c('db')->isFieldExists('cms_users', 'openid')) {
            cmsCore::c('db')->query("ALTER TABLE `cms_users` ADD `openid` VARCHAR( 250 ) NULL, ADD INDEX ( `openid` )");
        }
        return parent::install();
    }

    /**
     * Процедура обновления плагина
     * @return bool
     */
    public function upgrade() {
        cmsCore::c('db')->query("UPDATE `cms_users` SET `openid` = MD5(openid) WHERE `openid` IS NOT NULL");
        return parent::upgrade();
    }

    /**
     * Обработка событий
     * @param string $event
     * @param mixed $item
     * @return mixed
     */
    public function execute($event='', $item=array()) {
        if (cmsCore::m('registration')->config['reg_type'] == 'invite') {
            return true;
        }

        switch ($event) {
            case 'LOGINZA_BUTTON':  $item = $this->showLoginzaButton(); break;
            case 'LOGINZA_AUTH':    $item = $this->loginzaAuth(); break;
        }

        return true;
    }

    private function showLoginzaButton() {
        global $_LANG;
        
        $token_url  = urlencode(HOST . '/plugins/p_loginza/auth.php');

        $html  = '<div class="lf_title">'. $_LANG['PL_LOGIN_LOGINZA'] .'</div><p style="margin:15px 0">'. $_LANG['PL_LOGIN_LOGINZA_INFO'] .'</p><p><script src="https://loginza.ru/js/widget.js" type="text/javascript"></script>
                <a href="https://loginza.ru/api/widget?token_url='. $token_url .'&providers_set='. $this->config['PL_PROVIDERS'] .'&lang='. $this->config['PL_LANG'] .'" class="loginza">
                    <img src="https://loginza.ru/img/sign_in_button_gray.gif" alt="'.$_LANG['PL_LOGIN_LOGINZA_DO'].'"/>
                </a></p>';

        echo $html;

        return;
    }

    private function loginzaAuth() {
        $token = cmsCore::request('token', 'str', '');
        if (!$token) { cmsCore::error404(); }

        // получение профиля
        $profile = cmsCore::c('curl')->request('get', 'https://loginza.ru/api/authinfo?token=?token='. $token)->json();
        
        ob_start();
        print_r($profile);
        nl2br(ob_get_clean());
        exit;
        
        // проверка на ошибки
        if (!is_object($profile) || !empty($profile->error_message) || !empty($profile->error_type)) {
            cmsCore::error404();
        }

        // ищем такого пользователя
        $user_id = $this->getUserByIdentity($profile->identity);

        // если пользователя нет, создаем
        if (!$user_id) {
            $user_id = $this->createUser($profile);
        }

        // если пользователь уже был или успешно создан, авторизуем
        if ($user_id) {
            $user = cmsCore::c('db')->get_fields('cms_users', "id = '". $user_id ."'", 'login, password');
            if (!$user) { cmsCore::error404(); }

            $back_url = cmsCore::c('user')->signInUser($user['login'], $user['password'], 1, 1);

            cmsCore::redirect($back_url);
        }

        // если авторизация не удалась, редиректим на сообщение об ошибке
        cmsCore::redirect('/auth/error.html');
    }

    private function createUser($profile) {
        $inCore = cmsCore::getInstance();
        
        cmsCore::loadClass('actions');
        
        $nickname = $email = $birthdate = '';
        
        $advanced = array();
        // для вконтакте поолучаем большой аватар, статус и город
        if (strstr($profile->identity, '//vk.com')) {
            $vk = $this->callVk($profile->uid);
            if ($vk) {
                $advanced = array(
                    'city' => $vk->city->title,
                    'status' => $vk->status,
                    'photo' => $vk->photo_max_orig
                );
            }
        }  

        if (!empty($profile->name->full_name)) {
            // указано полное имя
            $nickname   = $profile->name->full_name;
        } else if (!empty($profile->name->first_name)) {
            // указано имя и фамилия по-отдельности
            $nickname   = $profile->name->first_name;
            if (!empty($profile->name->last_name)) { $nickname .= ' '. $profile->name->last_name; }
        } else if (preg_match('/^(http:\/\/)([a-zA-Z0-9\-_]+)\.([a-zA-Z0-9\-_]+)\.([a-zA-Z]{2,6})([\/]?)$/i', $profile->identity)) {
            // не указано имя, но передан идентификатор в виде домена
            $nickname = parse_url($profile->identity, PHP_URL_HOST);
        }
        
        $nickname = cmsCore::strClear($nickname); 
        $login    = substr(str_replace('-', '', cmsCore::strToURL($nickname)), 0, 15);
        
        if (!$nickname || !$login) {
            // не указано вообще ничего
            $max = cmsCore::c('db')->get_fields('cms_users', 'id>0', 'id', 'id DESC');
            $nickname = $login = 'user' . ($max['id'] + 1);
        }

        // генерируем пароль 
        $pass = md5(substr(md5(microtime().uniqid()), 0, 8));
       
        if (!empty($profile->email)) {
            $email = cmsCore::strClear($profile->email);
            $already_email = cmsCore::c('db')->get_field('cms_users', "email='{$email}' AND is_deleted=0", 'email');
            
            if ($already_email == $email) {
                cmsCore::redirect('/auth/error.html');
            }
        }
        
        if (!empty($profile->dob)) {
            $birthdate = cmsCore::strClear($profile->dob);
        }
        
        // проверяем занятость логина
        if (cmsCore::c('db')->get_field('cms_users', "login='{$login}' AND is_deleted=0", 'login') == $login) {
            // если логин занят, добавляем к нему ID
            $max = cmsCore::c('db')->get_fields('cms_users', 'id>0', 'id', 'id DESC');
            $login .= ($max['id']+1);
        }

        $user_array = cmsCore::callEvent('USER_BEFORE_REGISTER', array(
            'status'=>(!empty($advanced['status']) ? $advanced['status'] : ''),
            'status_date' => date('Y-m-d H:i:s'),
            'login' => $login,
            'nickname' => $nickname,
            'password' => $pass,
            'email' => $email,
            'birthdate' => $birthdate,
            'group_id' => cmsCore::m('registration')->config['default_gid'],
            'regdate' => date('Y-m-d H:i:s'),
            'logdate' => date('Y-m-d H:i:s'),
            'invited_by' => 0,
            'openid' => md5($profile->identity),
        ));
        $user_array['id'] = $user_id = cmsCore::c('db')->insert('cms_users', $user_array);

        // создаем профиль пользователя
        if ($user_id) {
            $filename = 'nopic.jpg';

            // если есть аватар, пробуем скачать
            if (!empty($profile->photo) || !empty($advanced['photo'])) {
                $photo_path = PATH .'/images/users/avatars/'. md5(session_id()) .'.jpg';
                cmsCore::c('curl')->saveFile((!empty($advanced['photo']) ? $advanced['photo'] : $profile->photo), $photo_path);
                
                if ($photo_path) {
                    cmsCore::includeGraphics();

                    $uploaddir 		= PATH .'/images/users/avatars/';
                    $filename 		= md5($photo_path . '-' . $user_id . '-' . time()).'.jpg';
                    $uploadavatar 	= $uploaddir . $filename;
                    $uploadthumb 	= $uploaddir . 'small/' . $filename;

                    $cfg = $inCore->loadComponentConfig('users');

                    @img_resize($photo_path, $uploadavatar, $cfg['medw'], $cfg['medh']);
                    @img_resize($photo_path, $uploadthumb, $cfg['smallw'], $cfg['smallw']);

                    @unlink($photo_path);
                }
            }
            
            cmsCore::c('user')->loadUserGeo();

            cmsCore::c('db')->insert('cms_user_profiles', array(
                'city' => (!empty($advanced['city']) ? $advanced['city'] : cmsCore::c('user')->city),
                'user_id' => $user_id,
                'imageurl' => $filename,
                'gender' => (!empty($profile->gender) ? strtolower($profile->gender) : 'm')
            ));

            cmsCore::callEvent('USER_REGISTER', $user_array);

            cmsActions::log('add_user', array(
                    'object' => '',
                    'user_id' => $user_id,
                    'object_url' => '',
                    'object_id' => $user_id,
                    'target' => '',
                    'target_url' => '',
                    'target_id' => 0,
                    'description' => ''
            ));

            if (cmsCore::m('registration')->config['send_greetmsg']){ cmsCore::m('registration')->sendGreetsMessage($user_id); }

            return $user_id;
        }

        return false;
    }

    private function getUserByIdentity($identity) {
        return cmsCore::c('db')->get_field('cms_users', "openid='". md5($identity) ."'", 'id');
    }
    
    private function callVk($uid) {
        $r = cmsCore::c('curl')->request('get', 'https://api.vk.com/method/users.get', array(
            'v'        => '5.21',
            'user_ids' => $uid,
            'fields'   => 'city,photo_max_orig,status'
        ))->json(false);
        
        return $r ? current($r->response) : false;
    }
}
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

    @set_time_limit(0);

    session_start();

    header('Content-Type: text/html; charset=utf-8');
    define('VALID_CMS', 1);

    define('PATH', $_SERVER['DOCUMENT_ROOT']);

    include(PATH .'/core/cms.php');
    
    if (file_exists(PATH .'/includes/config.inc.php')) {
        cmsCore::c('config');
        include PATH .'/includes/config.inc.php';
        cmsConfig::saveToFile($_CFG);
        unlink(PATH .'/includes/config.inc.php');
        cmsCore::redirect('/migrate');
    }
    
    $inCore = cmsCore::getInstance();

    // принудительно включаем дебаг
    cmsCore::c('config')->debug = 1;
    
    // Подключаем вспомогательный класс для миграции БД
    include PATH .'/migrate/migrate.class.php';
    // Выставляем необходимые опции для миграции
    $migrateDB = new migrateDB(array(
        'create_fields' => array(
            array( 'table' => 'cms_modules_bind', 'name' => 'tpl', 'type' => 'VARCHAR(64)' ),
            
            array( 'table' => 'cms_category', 'name' => 'pagetitle', 'type' => 'VARCHAR(255)' ),
            array( 'table' => 'cms_category', 'name' => 'meta_desc', 'type' => 'VARCHAR(1024)' ),
            array( 'table' => 'cms_category', 'name' => 'meta_keys', 'type' => 'VARCHAR(1024)' ),
            
            array( 'table' => 'cms_content', 'name' => 'images', 'type' => 'longtext' ),
            array( 'table' => 'cms_content', 'name' => 'slidecfg', 'type' => 'varchar(64)' ),
            
            array( 'table' => 'cms_upload_images', 'name' => 'title', 'type' => 'VARCHAR(255)' ),
            array( 'table' => 'cms_upload_images', 'name' => 'description', 'type' => 'VARCHAR(1024)' ),
            array( 'table' => 'cms_upload_images', 'name' => 'user_id', 'type' => 'INT(11)' ),
            array( 'table' => 'cms_upload_images', 'name' => 'pubdate', 'type' => 'DATETIME', 'default' => "'0000-00-00 00:00:00'" ),
            
            array( 'table' => 'cms_clubs', 'name' => 'seolink', 'type' => 'VARCHAR(200)' ),
            
            array( 'table' => 'cms_user_groups_access', 'name' => 'hide_for_guest', 'type' => 'TINYINT(1) UNSIGNED', 'default' => "'0'" ),
            array( 'table' => 'cms_users', 'name' => 'music_count',     'type' => 'INT(11)',     'default' => "'0'" ),
            array( 'table' => 'cms_users', 'name' => 'timezone',        'type' => 'VARCHAR(32)', 'default' => "''" ),
            array( 'table' => 'cms_users', 'name' => 'iframe_provider', 'type' => 'VARCHAR(8)',  'default' => "''" ),
            array( 'table' => 'cms_users', 'name' => 'iframe_user_id',  'type' => 'VARCHAR(32)', 'default' => "''" ),
            
            array( 'table' => 'cms_menu', 'name' => 'is_lax',    'type' => 'tinyint(1)',  'default' => "'0'" ),
            array( 'table' => 'cms_menu', 'name' => 'css_class', 'type' => 'VARCHAR(15)', 'default' => "''" ),
            array( 'table' => 'cms_menu', 'name' => 'titles',    'type' => 'TINYTEXT ',   'default' => "''" ),
            
            array( 'table' => 'cms_modules', 'name' => 'hidden_menu_ids',       'type' => 'VARCHAR(300)', 'default' => "''" ),
            array( 'table' => 'cms_modules', 'name' => 'is_strict_bind_hidden', 'type' => 'tinyint(1)',   'default' => "0" ),
            array( 'table' => 'cms_modules', 'name' => 'titles',                'type' => 'TINYTEXT ',    'default' => "''" ),
            
            array( 'table' => 'cms_search', 'name' => 'imageurl', 'type' => 'VARCHAR(150)', 'default' => "''" ),
            
            array( 'table' => 'cms_board_cats',  'name' => 'pagetitle', 'type' => 'VARCHAR(200)', 'default' => "''" ),
            array( 'table' => 'cms_board_cats',  'name' => 'meta_keys', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_board_cats',  'name' => 'meta_desc', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_board_items', 'name' => 'pagetitle', 'type' => 'VARCHAR(200)', 'default' => "''" ),
            array( 'table' => 'cms_board_items', 'name' => 'meta_keys', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_board_items', 'name' => 'meta_desc', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            
            array( 'table' => 'cms_blog_posts', 'name' => 'hits', 'type' => 'INT(11) UNSIGNED', 'default' => "'0'" ),
            
            array( 'table' => 'cms_forums',     'name' => 'pagetitle', 'type' => 'VARCHAR(200)', 'default' => "''" ),
            array( 'table' => 'cms_forums',     'name' => 'meta_keys', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_forums',     'name' => 'meta_desc', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_forum_cats', 'name' => 'pagetitle', 'type' => 'VARCHAR(200)', 'default' => "''" ),
            array( 'table' => 'cms_forum_cats', 'name' => 'meta_keys', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_forum_cats', 'name' => 'meta_desc', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            
            array( 'table' => 'cms_photo_albums', 'name' => 'pagetitle', 'type' => 'VARCHAR(200)', 'default' => "''" ),
            array( 'table' => 'cms_photo_albums', 'name' => 'meta_keys', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_photo_albums', 'name' => 'meta_desc', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            
            array( 'table' => 'cms_photo_files', 'name' => 'pagetitle', 'type' => 'VARCHAR(200)', 'default' => "''" ),
            array( 'table' => 'cms_photo_files', 'name' => 'meta_keys', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_photo_files', 'name' => 'meta_desc', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            
            array( 'table' => 'cms_blogs', 'name' => 'pagetitle', 'type' => 'VARCHAR(200)', 'default' => "''" ),
            array( 'table' => 'cms_blogs', 'name' => 'meta_keys', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_blogs', 'name' => 'meta_desc', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            
            array( 'table' => 'cms_blog_posts', 'name' => 'pagetitle', 'type' => 'VARCHAR(200)', 'default' => "''" ),
            array( 'table' => 'cms_blog_posts', 'name' => 'meta_keys', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_blog_posts', 'name' => 'meta_desc', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            
            array( 'table' => 'cms_uc_cats', 'name' => 'pagetitle', 'type' => 'VARCHAR(200)', 'default' => "''" ),
            array( 'table' => 'cms_uc_cats', 'name' => 'meta_keys', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_uc_cats', 'name' => 'meta_desc', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            
            array( 'table' => 'cms_clubs', 'name' => 'pagetitle', 'type' => 'VARCHAR(200)', 'default' => "''" ),
            array( 'table' => 'cms_clubs', 'name' => 'meta_keys', 'type' => 'VARCHAR(250)', 'default' => "''" ),
            array( 'table' => 'cms_clubs', 'name' => 'meta_desc', 'type' => 'VARCHAR(250)', 'default' => "''" )
        ),

        'change_fields' => array(
            array( 'table' => 'cms_plugins', 'name' => 'plugin_type', 'new_name' => 'type', 'type' => 'VARCHAR(10)' ),
            array( 'table' => 'cms_banner_hits', 'name' => 'pubdate', 'type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP' ),
            array( 'table' => 'cms_menu', 'name' => 'menu', 'type' => 'TINYTEXT' ),
        ),
        
        'drop_indexes' => array(
            array( 'table' => 'cms_ratings',       'names' => array( 'user_id', 'item_id', 'ip' ) ),
            array( 'table' => 'cms_ratings_total', 'names' => array( 'target', 'item_id' ) ),
            array( 'table' => 'cms_upload_images', 'names' => array( 'user_id' ) ),
            array( 'table' => 'cms_banner_hits',   'names' => array( 'ip' ) ),
            array( 'table' => 'cms_banner_hits',   'names' => array( 'banner_id' ) ),
            array( 'table' => 'cms_upload_images', 'names' => array( 'target_id' ) )
        ),
        
        'create_indexes' => array(
            array(
                'table' => 'cms_ratings',
                'indexes'  => array(
                    array( 'name' => 'user_id', 'fields' => array( 'user_id', 'target' ) ),
                    array( 'name' => 'item_id', 'fields' => array( 'item_id', 'target', 'user_id' ) ),
                    array( 'name' => 'ip',      'fields' => array( 'item_id', 'target', 'ip' ) )
                )
            ),
            array(
                'table' => 'cms_ratings_total',
                'indexes' => array(
                    array( 'name' => 'item_id', 'fields' => array( 'item_id', 'target' ) )
                )
            ),
            array(
                'table' => 'cms_upload_images',
                'indexes' => array(
                    array( 'name' => 'user_id', 'fields' => array( 'user_id' ) )
                )
            ),
            array(
                'table' => 'cms_banner_hits',
                'indexes' => array(
                    array( 'name' => 'banner_id', 'fields' => array( 'banner_id' ) )
                )
            ),
            array(
                'table' => 'cms_banner_hits',
                'indexes' => array(
                    array( 'name' => 'ip', 'unique' => true, 'fields' => array( 'ip', 'banner_id' ) )
                )
            ),
            array(
                'table' => 'cms_upload_images',
                'indexes' => array(
                    array( 'name' => 'target_id', 'fields' => array( 'target_id', 'target', 'component' ) )
                )
            )
        ),
        
        'inserts' => array(
            array( 'table' => 'cms_plugins', 'where' => "plugin='p_content_imgs'", 'insert_array' => array('plugin' => 'p_content_imgs', 'title' => 'Прикрепленные к статьям фотографии', 'description' => 'Плагин добавляет в конце статьи карусель (слайдер) с прикрепленными фотографиями.', 'author' => 'DS Soft', 'version' => '0.0.1', 'type' => 'plugin', 'published' => 1, 'config' => '---\nslider: jCarousel__1\n'), 'msg' => 'Установлен и включен плагин p_content_imgs', 'after' => "INSERT INTO `cms_event_hooks` SET `event`='GET_ARTICLE', `plugin_id`='%id%'" ),
            array( 'table' => 'cms_plugins', 'where' => "plugin='p_captcha'", 'insert_array' => array('plugin' => 'p_captcha', 'title' => 'Captcha.ru', 'description' => 'PHP Captcha с сайта Captcha.ru', 'author' => 'Kruglov Sergei', 'version' => '2.0', 'type' => 'captcha', 'published' => 1, 'config' => '---\n'), 'msg' => 'Установлен и включен плагин p_captcha', 'after' => array("INSERT INTO `cms_event_hooks` SET `event`='INSERT_CAPTCHA', `plugin_id`='%id%'", "INSERT INTO `cms_event_hooks` SET `event`='CHECK_CAPTCHA', `plugin_id`='%id%';") ),
            array( 'table' => 'cms_plugins', 'where' => "plugin='p_recaptcha'", 'insert_array' => array('plugin' => 'p_recaptcha', 'title' => 'reCaptcha', 'description' => 'reCaptcha капча от гугла https://www.google.com/recaptcha/', 'author' => 'DS Soft', 'version' => '0.0.1', 'type' => 'captcha', 'published' => 0, 'config' => '---\nPRC_DOMENS:\nPRC_PUBLIC_KEY:\nPRC_PRIVATE_KEY:\nPRC_THEME: blackglass\nPRC_LANG: ru\n'), 'msg' => 'Установлен плагин p_recaptcha', 'after' => array("INSERT INTO `cms_event_hooks` SET `event`='INSERT_CAPTCHA', `plugin_id`='%id%'", "INSERT INTO `cms_event_hooks` SET `event`='CHECK_CAPTCHA', `plugin_id`='%id%';") ),
            array( 'table' => 'cms_components', 'where' => "link='sitemap'", 'insert_array' => array('title' => 'Карта сайта', 'link' => 'sitemap', 'internal' => 0, 'author' => 'DS Soft', 'published' => 1, 'version' => '2.2.0', 'system' => 1, 'config' => '---\nperpage: 100\nhtml_map_enable: 1\nblogs:\n  published: 1\n  mode: all\n  regen_time: 24\nfaq:\n  published: 1\n  mode: all\n  regen_time: 24\nboard:\n  published: 1\n  mode: all\n  regen_time: 24\ncontent:\n  published: 1\n  mode: all\n  regen_time: 24\nclubs:\n  published: 1\n  mode: all\n  regen_time: 24\nusers:\n  published: 1\n  mode: all\n  regen_time: 24\ncatalog:\n  published: 1\n  mode: all\n  regen_time: 24\nforum:\n  published: 1\n  mode: all\n  regen_time: 24\nphotos:\n  published: 1\n  mode: all\n  regen_time: 24\n'), 'msg' => 'Установлен компонент <b>Карта сайта</b> sitemap.' ),
            array( 'table' => 'cms_cron_jobs', 'where' => "job_name='generateSitemaps'", 'insert_array' => array( 'job_name' => 'generateSitemaps', 'job_interval' => 12, 'job_run_date' => '2014-10-14 07:24:07', 'component' => 'sitemap', 'model_method' => 'generateSitemaps', 'is_enabled' => 1, 'is_new' => 0, 'comment' => 'Генерирует карту сайта.' ), 'msg' => 'Добавлена задача CRON generateSitemaps' )
        ),
        
        'queries' => array(
            array( 'sql' => "UPDATE `cms_components` SET `title`='Карта сайта', `internal`='0', `author`='DS Soft', `version`='2.2.0', `system`='1' WHERE `link`='sitemap' LIMIT 1" ),
            array( 'sql' => "UPDATE `cms_plugins` SET `plugin`='p_ckeditor', `title`='CKEditor 4.4.5', `description`='Визуальный редактор', `author`='Plugin - DS SOFT. CKEditor - Frederico Knabben', `version`='0.0.3', `type`='wysiwyg', `config`='---\ninline:\nadmin_skin: moono\nuser_skin: moono\n'  WHERE `plugin` = 'p_fckeditor'", 'msg' => 'Плагин p_fckeditor удален вместо него установлен плагин p_ckeditor.' ),
            array( 'sql' => "UPDATE `cms_modules_bind` SET `tpl` = '". cmsCore::c('config')->template ."' WHERE `tpl` = ''" ),
            array( 'sql' => "UPDATE cms_user_groups_access SET hide_for_guest=1 WHERE id=4 or id=5 or id=6 or id=9 or id=12 or id=14 or id=15" ),
            array( 'sql' => "UPDATE cms_components SET internal = '0' WHERE link = 'comments'" ),
            array( 'sql' => "CREATE TABLE IF NOT EXISTS `cms_ticket` ( `id` int(11) NOT NULL AUTO_INCREMENT, `cat_id` int(11) NOT NULL DEFAULT '0', `title` varchar(256) NOT NULL, `msg` text NOT NULL, `msg_count` int(11) NOT NULL, `last_msg_date` datetime NOT NULL, `status` tinyint(4) NOT NULL DEFAULT '0', `priority` tinyint(4) NOT NULL DEFAULT '0', `secret_key` varchar(256) NOT NULL, `pubdate` datetime NOT NULL, `user_id` int(11) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" ),
            array( 'sql' => "CREATE TABLE IF NOT EXISTS `cms_ticket_cat` ( `id` int(11) NOT NULL AUTO_INCREMENT, `title` varchar(256) NOT NULL, `module` varchar(32) NOT NULL, `server` varchar(128) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" ),
            array( 'sql' => "CREATE TABLE IF NOT EXISTS `cms_ticket_msg` ( `id` int(11) NOT NULL AUTO_INCREMENT, `ticket_id` int(11) NOT NULL, `msg` text NOT NULL, `pubdate` datetime NOT NULL, `support` varchar(128) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" ),
            array( 'sql' => "UPDATE `cms_plugins` SET  `version`='0.0.3' WHERE `plugin` = 'p_ckeditor'" ),
            array( 'sql' => "UPDATE `cms_plugins` SET  `version`='1.12' WHERE `plugin` = 'p_hidetext'" ),
            array( 'sql' => "CREATE TABLE IF NOT EXISTS `cms_cron_logs` ( `id` int(11) NOT NULL AUTO_INCREMENT, `cron_id` int(11) DEFAULT NULL, `msg` longtext DEFAULT NULL, `run_date` datetime DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE = INNODB AUTO_INCREMENT=1 CHARACTER SET utf8 COLLATE utf8_general_ci", 'msg' => 'Таблица cms_cron_logs создана' )
        ),
        
        'com_cfgs' => array(
            array(
                'name' => 'content',
                'unset_keys' => array( 'img_table' ),
                'merge_cfgs' => array(
                    'imgs_big_w'    => 1024,
                    'imgs_big_h'    => 1024,
                    'imgs_medium_w' => 600,
                    'imgs_medium_h' => 600,
                    'imgs_small_w'  => 200,
                    'imgs_small_h'  => 200,
                    'resize_type'   => 'auto',
                    'mresize_type'  => 'auto',
                    'sresize_type'  => 'auto',
                    'watermark_only_big' => 0,
                    'imgs_quality' => 80
                )
            )
        )
    ));
    
    $version_prev = 'CMS RuDi 0.0.x или Instant CMS v1.10.3+';
    $version_next = 'CMS RuDi 0.1.0';

// ========================================================================== //
// ========================================================================== //
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>CMS RuDi - Миграция базы данных</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<style type="text/css">
    body { font-family:Arial; font-size:14px; }

    a { color: #0099CC; }
    a:hover { color: #375E93; }
    h2 { color: #375E93; }

    #wrapper { padding:10px 30px; }
    #wrapper p{ line-height: 20px; }

    .migrate p {
       line-height:16px;
       padding-left:20px;
       margin:2px;
       margin-left:20px;
       background:url(/admin/images/actions/on.gif) no-repeat;
    }
    .migrate p.info {
       font-size: 16px;
       background: none;
       color: #C00;
    }
    .important {
       margin:20px;
       margin-left:0px;
       border:solid 1px silver;
       padding:15px;
       padding-left:65px;
       background:url(important.png) no-repeat 15px 15px;
    }
    .nextlink {
       margin-top:15px;
       font-size:18px;
    }
</style>
<div id="wrapper" class="migrate">
<?php
    echo "<h2>Миграция InstantCMS {$version_prev} &rarr; {$version_next}</h2>";

    if(!cmsCore::inRequest('go')){
        echo '<h3><a href="/migrate/index.php?go=1">начать миграцию...</a></h3>';
        exit;
    }

// ========================================================================== //
    $step = cmsCore::request('go', 'int', 0);

    echo '<h3>Шаг № '.$step.'</h3>';

// ========================================================================== //

    if ($step == 1) {
        // Добавляем новые поля
        $migrateDB->createFields();
        // Изменияем тип или название имеющихся полей
        $migrateDB->changeFields();
        // Удаляем не нужные индексы
        $migrateDB->dropIndexes();
        // Создаем новые индексы
        $migrateDB->createIndexes();
        // Вставляем данные в БД
        $migrateDB->inserts();
        // Выполняем произвольные запросы в БД
        $migrateDB->query();
        // Изменяем настройки компонентов если требуется
        $migrateDB->setComCfgs();
        // Изменяем настройки модулей если требуется
        $migrateDB->setModCfgs();
        
        if ($migrateDB->checkCreateField('is_lax')) {
            $result = cmsCore::c('db')->query("SELECT id, menu FROM cms_menu");
            if (cmsCore::c('db')->num_rows($result)) {
                while ($item = cmsCore::c('db')->fetch_assoc($result)) {
                    $item['menu'] = cmsCore::arrayToYaml(array($item['menu']));
                    cmsCore::c('db')->query("UPDATE cms_menu SET menu = '". $item['menu'] ."' WHERE id = '". $item['id'] ."'");
                }
            }
            echo '<p>Поддержка множественного выбора меню для показа выполнена</p>';
        }
        
        if (cmsCore::c('db')->get_field('cms_plugins', "plugin = 'p_loginza'", 'version') != '1.10.4') { 
            cmsCore::c('db')->query("UPDATE `cms_users` SET `openid` = MD5(openid) WHERE `openid` IS NOT NULL"); 
            cmsCore::c('db')->query("UPDATE `cms_plugins` SET `version` = '1.10.4' WHERE `plugin` = 'p_loginza'"); 
            echo '<p>Плагин "Авторизация Loginza" обновлен.</p>'; 
        }
        
        // --------- Обновляем настройки плагинов для CMS RuDi v0.0.9 ----------
        // ---------- p_auto_forum ----------
        $plg_config = cmsCore::c('db')->get_field('cms_plugins', "plugin='p_auto_forum'", 'config');
        $plg_config = cmsCore::yamlToArray($plg_config);
        if (!isset($plg_config['no_create_thread_cats'])) {
            $plg_nconfig = array(
                'delete_thread' => isset($plg_config['AF_DELETE_THREAD']) ? $plg_config['AF_DELETE_THREAD'] : 0,
                'link_thread'   => isset($plg_config['AF_LINK_THREAD']) ? $plg_config['AF_LINK_THREAD'] : 1,
                'forum_id'      => isset($plg_config['AF_ADDTREADFORUM_ID']) ? $plg_config['AF_ADDTREADFORUM_ID'] : 1,
                'no_create_thread_cats' => array_map('trim', explode(',', isset($plg_config['AF_NOCREATETREAD']) ? $plg_config['AF_NOCREATETREAD'] : ''))
            );
            $plg_nconfig = cmsCore::arrayToYaml($plg_nconfig);
            cmsCore::c('db')->query("UPDATE cms_plugins SET config='". cmsCore::c('db')->escape_string($plg_nconfig) ."' WHERE plugin='p_auto_forum' LIMIT 1");
        }
        // ========== /p_auto_forum =========
        // ---------- p_ckeditor ----------
        $plg_config = cmsCore::c('db')->get_field('cms_plugins', "plugin='p_ckeditor'", 'config');
        $plg_config = cmsCore::yamlToArray($plg_config);
        if (!isset($plg_config['admin_skin'])) {
            $plg_nconfig = array(
                'inline'     => $plg_config['PCK_INLINE'],
                'admin_skin' => $plg_config['PCK_ADMIN_SKIN'],
                'user_skin'  => $plg_config['PCK_USER_SKIN']
            );
            $plg_nconfig = cmsCore::arrayToYaml($plg_nconfig);
            cmsCore::c('db')->query("UPDATE cms_plugins SET config='". cmsCore::c('db')->escape_string($plg_nconfig) ."' WHERE plugin='p_ckeditor' LIMIT 1");
        }
        // ========== /p_ckeditor =========
        // ---------- p_content_imgs ----------
        $plg_config = cmsCore::c('db')->get_field('cms_plugins', "plugin='p_content_imgs'", 'config');
        $plg_config = cmsCore::yamlToArray($plg_config);
        if (!isset($plg_config['slider'])) {
            $plg_nconfig = array(
                'slider' => $plg_config['PCI_SLIDER'] .'__'. $plg_config['PCI_SLIDER_OPT']
            );
            $plg_nconfig = cmsCore::arrayToYaml($plg_nconfig);
            cmsCore::c('db')->query("UPDATE cms_plugins SET config='". cmsCore::c('db')->escape_string($plg_nconfig) ."' WHERE plugin='p_content_imgs' LIMIT 1");
        }
        // ========== /p_content_imgs =========
        //======================================================================
        
        // --------------- Добавляем всем модулям настройку tpl ----------------
        $results = cmsCore::c('db')->query('SELECT id, content, config FROM cms_modules WHERE is_external = 1');
        while ($mod = cmsCore::c('db')->fetch_assoc($results)) {
            $mod['config'] = cmsCore::yamlToArray($mod['config']);
            
            if (empty($mod['config']['tpl'])) {
                $mod['config']['tpl'] = $mod['content'];
            }
            
            $mod['config'] = cmsCore::arrayToYaml($mod['config']);
            
            cmsCore::c('db')->update(
                'cms_modules',
                array(
                    'config' => cmsCore::c('db')->escape_string($mod['config'])
                ),
                $mod['id']
            );
        }
        //======================================================================
        
        $last_user_menu = cmsCore::c('db')->get_fields('cms_modules', "title = 'Меню пользователя' AND content = 'mod_usermenu'", '*');
        if (!empty($last_user_menu)) {
            cmsCore::c('db')->query("UPDATE `cms_modules` SET 
                    `position` = '". $last_user_menu['position'] ."',
                    `name` = 'Меню',
                    `title` = 'Меню пользователя',
                    `is_external` = 1,
                    `content` = 'mod_menu',
                    `ordering` = ". $last_user_menu['ordering'] .",
                    `showtitle` = ". $last_user_menu['showtitle'] .",
                    `published` = ". $last_user_menu['published'] .",
                    `user` = 0,
                    `config` = '---\nmenu: usermenu\nshow_home: 0\ntpl: mod_menu.tpl\nis_sub_menu: 0\n',
                    `original` = 1,
                    `css_prefix` = 'user_menu_',
                    `access_list` = '---\n- 1\n- 7\n- 9\n- 2\n',
                    `hidden_menu_ids` = '',
                    `cache` = 0,
                    `cachetime` = 1,
                    `cacheint` = 'HOUR',
                    `template` = 'module.tpl',
                    `is_strict_bind` = 0,
                    `is_strict_bind_hidden` = 0,
                    `author` = 'InstantCMS team',
                    `version` = '1.0' WHERE `id` = ". $last_user_menu['id'] ." LIMIT 1");
            cmsCore::c('db')->delete('cms_modules', "content = 'mod_usermenu'");

            cmsCore::c('db')->query("INSERT INTO `cms_modules` (`position`, `name`, `title`, `is_external`, `content`, `ordering`, `showtitle`, `published`, `user`, `config`, `original`, `css_prefix`, `access_list`, `hidden_menu_ids`, `cache`, `cachetime`, `cacheint`, `template`, `is_strict_bind`, `is_strict_bind_hidden`, `author`, `version`) VALUES ('header', 'Меню', 'Меню авторизации', 1, 'mod_menu', 35, 0, 1, 0, '---\nmenu: authmenu\nshow_home: 0\ntpl: mod_menu.tpl\nis_sub_menu: 0\n', 1, 'user_menu_', '---\n- 8\n', '', 0, 1, 'HOUR', 'module.tpl', 0, 0, 'InstantCMS team', '1.0');");
            $aid = cmsCore::c('db')->get_last_id('cms_modules');
            cmsCore::c('db')->query("INSERT INTO `cms_modules_bind` (`module_id`, `menu_id`, `position`) VALUES (". $aid .", 0, 'header');");
            
            echo '<p>Существующий модуль "Меню пользователя" удален.</p>';
            echo '<p>Новый модуль "Меню авторизации" добавлен. </p>';
            echo '<p>Новый модуль "Меню пользователя" добавлен. Добавлять/изменять/удалять пункты меню пользователя теперь можно в админке в общем списке меню.</p>';

            // создаем пункты меню
            // авторизация
            cmsCore::c('db')->addNsCategory( 'cms_menu', array( 'parent_id' => 1, 'menu' => '---\n- authmenu\n', 'title' => 'Войти', 'css_class' => 'login', 'link' => '/login', 'linkid' => '/login', 'published' => 1 ) );
            
            cmsCore::c('db')->addNsCategory( 'cms_menu', array( 'parent_id' => 1, 'menu' => '---\n- authmenu\n', 'title' => 'Регистрация', 'css_class' => 'register', 'link' => '/registration', 'linkid' => '/registration', 'published' => 1 ) );

            // меню пользователя
            $user_menu = array(
                array(
                    'title'     => '{user.nickname}',
                    'css_class' => 'my_profile',
                    'link'      => '/users/{user.login}'
                ),
                array(
                    'title'     => 'Сообщения {user.new_msg_count}',
                    'css_class' => 'my_messages',
                    'link'      => '/users/{user.id}/messages.html'
                ),
                array(
                    'title'     => 'Мой блог',
                    'css_class' => 'my_blog',
                    'link'      => '/blogs/my_blog.html'
                ),
                array(
                    'title'     => 'Фото',
                    'css_class' => 'my_photos',
                    'link'      => '/users/{user.id}/photoalbum.html'
                ),
                array(
                    'title'     => 'Статьи',
                    'css_class' => 'my_content',
                    'link'      => '/content/my.html'
                ),
                array(
                    'title'     => 'Админка',
                    'css_class' => 'admin',
                    'link'      => '/admin/',
                    'access_list' => '---\n- 2\n'
                ),
                array(
                    'title'     => 'Выход',
                    'css_class' => 'logout',
                    'link'      => '/logout'
                )
            );
            
            foreach ($user_menu as $m) {
                $id = cmsCore::c('db')->addNsCategory( 'cms_menu', array( 'parent_id' => 1, 'menu' => '---\n- usermenu\n', 'title' => $m['title'], 'css_class' => $m['css_class'], 'link' => $m['link'], 'linkid' => $m['link'], 'access_list' => (empty($m['access_list']) ? '' : $m['access_list']), 'published' => 1 ) );
                
                if ($m['css_class'] == 'my_photos') {
                    cmsCore::c('db')->addNsCategory( 'cms_menu', array( 'parent_id' => $id, 'menu' => '---\n- usermenu\n', 'title' => 'Добавить фото', 'css_class' => 'add_photos', 'link' => '/users/addphoto.html', 'linkid' => '/users/addphoto.html', 'published' => 1 ) );
                }
                
                if ($m['css_class'] == 'my_content') {
                    cmsCore::c('db')->addNsCategory( 'cms_menu', array( 'parent_id' => $id, 'menu' => '---\n- usermenu\n', 'title' => 'Написать', 'css_class' => 'add_content', 'link' => '/content/add.html', 'linkid' => '/content/add.html', 'published' => 1 ) );
                }
            }
        }
        
        cmsUser::registerGroupAccessType('comments/target_author_delete', 'Удаление неугодных комментариев к своим публикациям', 1);
        echo '<p>В права доступа добавлено новое правило "Удаление неугодных комментариев к своим публикациям"</p>';
        
        // Оптимизируем таблицы в БД
        cmsDatabase::optimizeTables();
        
        echo '<h3><a href="/migrate/index.php?go=2">Шаг № 2. Работа с файлами и папками...</a></h3>';
    }

// ========================================================================== //
    if ($step == 2) {
        $dir_m = PATH .'/images/photos/medium';
        $dir_s = PATH .'/images/photos/small';
        
        $new_dir_m = PATH .'/images/content/medium';
        $new_dir_s = PATH .'/images/content/small';
        
        $pdir = opendir($dir_m);

        while ($nextfile = readdir($pdir)) {
            if (
                    ($nextfile != '.') &&
                    ($nextfile != '..') &&
                    !is_dir($dir_m .'/'. $nextfile) &&
                    (preg_match('#article([0-9]+)#is', $nextfile, $match)))
            {
                if (cmsCore::c('db')->get_field('cms_content', "id='". $match[1] ."'", 'id')) {
                    $id = ceil($match[1]/100);
                    
                    mkdir($new_dir_m .'/'. $id, 0777, true);
                    mkdir($new_dir_s .'/'. $id, 0777, true);
                    
                    copy($dir_m .'/'. $nextfile, $new_dir_m .'/'. $id .'/'. $nextfile);
                    copy($dir_s .'/'. $nextfile, $new_dir_s .'/'. $id .'/'. $nextfile);
                }
                
                unlink($dir_m .'/'. $nextfile);
                unlink($dir_s .'/'. $nextfile);
            }
        }
        
        echo '<p>Все фотографии статей перенесены в новое местоположение;</p>';
        
        echo '<div style="margin:15px 0px;font-weight:bold">Миграция завершена. Удалите папку /migrate/ прежде чем продолжить!</div>';
        echo '<div class="nextlink"><a href="/">Перейти на сайт</a></div>';
    }
// ========================================================================== //

    echo '</div></body></html>';
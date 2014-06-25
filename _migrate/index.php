<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.4                               //
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

    include(PATH.'/core/cms.php');
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
            array( 'table' => 'cms_upload_images', 'name' => 'pubdate', 'type' => 'DATETIME', 'default' => '0000-00-00 00:00:00' ),
            array( 'table' => 'cms_clubs', 'name' => 'seolink', 'type' => 'VARCHAR(200)' ),
            array( 'table' => 'cms_user_groups_access', 'name' => 'hide_for_guest', 'type' => 'TINYINT(1) UNSIGNED', 'default' => '0' ),
            array( 'table' => 'cms_users', 'name' => 'music_count', 'type' => 'INT(11)', 'default' => '0' ),
            array( 'table' => 'cms_menu', 'name' => 'is_lax', 'type' => 'tinyint(1)', 'default' => '0' ),
            array( 'table' => 'cms_menu', 'name' => 'css_class', 'type' => 'VARCHAR(15)', 'default' => '' )
        ),
        
        'drop_indexes' => array(
            array( 'table' => 'cms_ratings',       'names' => array( 'user_id', 'item_id', 'ip' ) ),
            array( 'table' => 'cms_ratings_total', 'names' => array( 'target', 'item_id' ) ),
            array( 'table' => 'cms_upload_images', 'names' => array( 'user_id' ) ),
            array( 'table' => 'cms_banner_hits', 'names' => array( 'ip' ) ),
            array( 'table' => 'cms_banner_hits', 'names' => array( 'banner_id' ) )
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
                    array( 'unique' => true, 'fields' => array( 'ip', 'banner_id' ) )
                )
            )
        ),
        
        'inserts' => array(
            array( 'table' => 'cms_plugins', 'where' => "plugin='p_content_imgs'", 'insert_array' => array('plugin' => 'p_content_imgs', 'title' => 'Прикрепленные к статьям фотографии', 'description' => 'Плагин добавляет в конце статьи карусель (слайдер) с прикрепленными фотографиями. Вставляет в текст статьи фотографии в тех местах где прописана конструкция вида {img#123}', 'author' => 'DS Soft', 'version' => '0.0.1', 'plugin_type' => 'plugin', 'published' => 1, 'config' => '---\nPCI_SLIDER: jCarousel\nPCI_SLIDER_OPT: 2\nPCI_INSERT_IMAGES: 1\nPCI_DELETE_ERRORS: 1\n'), 'msg' => 'Установлен и включен плагин p_content_imgs', 'after' => "INSERT INTO `cms_event_hooks` SET `event`='GET_ARTICLE', `plugin_id`='%id%'" )
        ),
        
        'queries' => array(
            array('sql' => "UPDATE `cms_modules_bind` SET `tpl` = '". cmsCore::c('config')->template ."' WHERE `tpl` = ''"),
            array('sql' => "UPDATE cms_user_groups_access SET hide_for_guest=1 WHERE id=4 or id=5 or id=6 or id=9 or id=12 or id=14 or id=15"),
            array('sql' => "UPDATE cms_components SET internal = '0' WHERE link = 'comments'"),
            array('sql' => "ALTER TABLE `cms_banner_hits` CHANGE `pubdate` `pubdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP"),
            array('sql' => "ALTER TABLE `cms_menu` CHANGE `menu` `menu` TINYTEXT NOT NULL")
        ),
        
        'com_cfgs' => array(
            array(
                'name' => 'content',
                'unset_keys' => array( 'img_table' ),
                'merge_cfgs' => array(
                    'imgs_big_w'    => 300,
                    'imgs_big_h'    => 300,
                    'imgs_medium_w' => 200,
                    'imgs_medium_h' => 200,
                    'imgs_small_w'  => 100,
                    'imgs_small_h'  => 100,
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
    $version_next = 'CMS RuDi 0.0.6';

// ========================================================================== //
// ========================================================================== //
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>InstantCMS - Миграция базы данных</title>
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
        
        // Оптимизируем таблицы в БД
        cmsDatabase::optimizeTables();
        
        echo '<h3><a href="/migrate/index.php?go=2">Шаг № 2. Работа с файлами и папками...</a></h3>';
    }

// ========================================================================== //
    if ($step == 2){
        $dir_m = PATH .'/images/photos/medium';
        $dir_s = PATH .'/images/photos/small';
        
        $new_dir_m = PATH .'/images/content/medium';
        $new_dir_s = PATH .'/images/content/small';
        
        $pdir = opendir($dir_m);

        while ($nextfile = readdir($pdir)){
            if (
                    ($nextfile != '.') &&
                    ($nextfile != '..') &&
                    !is_dir($dir_m .'/'. $nextfile) &&
                    (preg_match('#article([0-9]+)#is', $nextfile, $match)))
            {
                if (cmsCore::c('db')->get_field('cms_content', "id='". $match[1] ."'", 'id')){
                    
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
<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.3                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2013                    //
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

    cmsCore::c('user');
    cmsCore::c('cron');
    cmsCore::c('actions');
    cmsCore::c('page');

    // принудительно включаем дебаг
    cmsCore::c('config')->debug = 1;

    $version_prev = 'CMS Rudi 0.0.2 или Instant CMS v1.10.3';
    $version_next = 'CMS Rudi 0.0.3';

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
    echo "<h2>Миграция базы данных InstantCMS {$version_prev} &rarr; {$version_next}</h2>";

    if(!cmsCore::inRequest('go')){
        echo '<h3><a href="/migrate/index.php?go=1">начать миграцию...</a></h3>';
        exit;
    }

// ========================================================================== //
// ========================================================================== //
    $step = cmsCore::request('go', 'int', 0);

    echo '<h3>Шаг № '.$step.'</h3>';

// ========================================================================== //
// ========================================================================== //

    if($step == 1){
        /* Работа с полями таблиц*/
        $CREATE_FIELDS = array(
            array( 'table' => 'cms_modules_bind', 'name' => 'tpl', 'type' => 'VARCHAR(64)', 'default' => '_default_' ),
            
            array( 'table' => 'cms_category', 'name' => 'pagetitle', 'type' => 'VARCHAR(255)' ),
            array( 'table' => 'cms_category', 'name' => 'meta_desc', 'type' => 'VARCHAR(1024)' ),
            array( 'table' => 'cms_category', 'name' => 'meta_keys', 'type' => 'VARCHAR(1024)' ),
            array( 'table' => 'cms_content', 'name' => 'images', 'type' => 'longtext' ),
            
            array( 'table' => 'cms_upload_images', 'name' => 'title', 'type' => 'VARCHAR(255)' ),
            array( 'table' => 'cms_upload_images', 'name' => 'description', 'type' => 'VARCHAR(1024)' ),
            array( 'table' => 'cms_upload_images', 'name' => 'user_id', 'type' => 'int(11)' ),
            array( 'table' => 'cms_upload_images', 'name' => 'pubdate', 'type' => 'DATETIME', 'default' => '0000-00-00 00:00:00' ),
            
            array( 'table' => 'cms_clubs', 'name' => 'seolink', 'type' => 'VARCHAR(200)' ),
            array( 'table' => 'cms_user_groups_access', 'name' => 'hide_for_guest', 'type' => 'TINYINT( 1 ) UNSIGNED', 'default' => '0' ),
        );
        
        if (!empty($CREATE_FIELDS)){
            foreach ($CREATE_FIELDS as $create_field){
                if (!cmsCore::c('db')->isFieldExists($create_field['name'])){
                    cmsCore::c('db')->query("ALTER TABLE `". $create_field['table'] ."` ADD `". $create_field['name'] ."` ". $create_field['type'] ." NOT NULL". (isset($create_field['default']) ? " DEFAULT ". $create_field['default'] : ""));
                    echo '<p>Поле "'. $create_field['name'] .'" добавлено в таблицу "'. $create_field['table'] .'";</p>';
                }
            }
        }
        /*====================================================================*/
        
        /*--------------------- Работа с индексами таблиц --------------------*/
        $DROP_INDEXES = array(
            array( 'table' => 'cms_ratings',       'names' => array( 'user_id', 'item_id', 'ip' ) ),
            array( 'table' => 'cms_ratings_total', 'names' => array( 'target', 'item_id' ) ),
            array( 'table' => 'cms_upload_images', 'names' => array( 'user_id' ) )
        );
        
        $CREATE_INDEXES = array(
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
        );
        
        if (!empty($DROP_INDEXES)){
            foreach ($DROP_INDEXES as $drop_index){
                $table = cmsCore::c('db')->fetch_assoc(cmsCore::c('db')->query('SHOW CREATE TABLE `'. $drop_index['table'] .'`'));
                foreach ($drop_index['names'] as $index){
                    if (preg_match('#KEY[\s]+`'. $index .'`#is', $table['Create Table'], $m)){
                        cmsCore::c('db')->query('ALTER TABLE `'. $drop_index['table'] .'` DROP INDEX `'. $index .'`');
                    }
                }
            }
        }
        
        if (!empty($CREATE_INDEXES)){
            foreach ($CREATE_INDEXES as $create_index){
                foreach ($create_index['indexes'] as $index){
                    cmsCore::c('db')->query('ALTER TABLE `'. $create_index['table'] .'` ADD INDEX `'. $index['name'] .'` (`'. implode('`,`', $index['fields']) .'`)');
                }
            }
        }
        /*====================================================================*/
        
        
        /*--------------------------------------------------------------------*/
        cmsCore::c('db')->query("UPDATE `cms_modules_bind` SET `tpl` = '". cmsCore::c('config')->template ."' WHERE `tpl` = ''");
        cmsCore::c('db')->query("UPDATE cms_user_groups_access SET hide_for_guest=1 WHERE id=4 or id=5 or id=6 or id=9 or id=12 or id=14 or id=15");
        
//        $items = cmsCore::c('db')->get_table('cms_clubs');
//        foreach ($items as $item){
//            cmsCore::c('db')->query("UPDATE `cms_clubs` SET `seolink`='". cmsCore::strToURL($item['title']) ."' WHERE id='". $item['id'] ."'");
//        }
//        echo '<p>---- Сгенерированы seolink для всех клубов;</p>';
        
        if (!cmsCore::c('db')->get_field('cms_plugins', "plugin='p_content_imgs'", 'id')){
            $id = cmsCore::c('db')->insert('cms_plugins', array('plugin' => 'p_content_imgs', 'title' => 'Прикрепленные к статьям фотографии', 'description' => 'Плагин добавляет в конце статьи карусель (слайдер) с прикрепленными фотографиями. Вставляет в текст статьи фотографии в тех местах где прописана конструкция вида {img#123}', 'author' => 'DS Soft', 'version' => '0.0.1', 'plugin_type' => 'plugin', 'published' => 1, 'config' => '---\nPCI_SLIDER: jCarousel\nPCI_SLIDER_OPT: 2\nPCI_INSERT_IMAGES: 1\nPCI_DELETE_ERRORS: 1\n'));
            cmsCore::c('db')->insert('cms_event_hooks', array('event' => 'GET_ARTICLE', 'plugin_id' => $id));
            echo '<p>Установлен и включен плагин p_content_imgs;</p>';
        }
        /*====================================================================*/
        
        
        /*----------------- Работа с настройками компонентов -----------------*/
        $COM_CFGS = array(
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
                )
            )
        );
        
        if (!empty($COM_CFGS)){
            foreach ($COM_CFGS as $com){
                $com_cfg = $inCore->loadComponentConfig($com['name']);
                if (!empty($com['unset_keys'])){
                    foreach ($com['unset_keys'] as $key){
                        if (isset($com_cfg[$key])){ unset($com_cfg[$key]); }
                    }
                }
                if (!empty($com['merge_cfgs'])){
                    $com_cfg = array_merge($com_cfg, $com['merge_cfgs']);
                }
                $inCore->saveComponentConfig($com['name'], $com_cfg);
            }
        }
        /*====================================================================*/
        
        
        cmsDatabase::optimizeTables();
        

        echo '<div style="margin:15px 0px;font-weight:bold">Миграция завершена. Удалите папку /migrate/ прежде чем продолжить!</div>';
        echo '<div class="nextlink"><a href="/">Перейти на сайт</a></div>';
    }
// ========================================================================== //
// ========================================================================== //

    echo '</div></body></html>';

?>
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

    cmsCore::loadClass('user');
    cmsCore::loadClass('cron');
    cmsCore::loadClass('actions');
    cmsCore::loadClass('page');

    $inConf = cmsConfig::getInstance();
    $inDB   = cmsDatabase::getInstance();

    // принудительно включаем дебаг
    $inConf->debug = 1;

    $version_prev = 'CMS Dudi 0.0.1 (Instant CMS v1.10.3 DS Soft Mod v 0.0.1)';
    $version_next = 'CMS Dudi 0.0.2 (Instant CMS v1.10.3 DS Soft Mod v 0.0.2)';

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
        if (!$inDB->isTableExists('cms_content_images')){
            $inDB->query("CREATE TABLE IF NOT EXISTS `cms_content_images` (`id` int(11) NOT NULL AUTO_INCREMENT, `target_id` int(11) NOT NULL DEFAULT '0', `session_id` varchar(50) NOT NULL, `fileurl` varchar(250) NOT NULL, `target` varchar(25) NOT NULL DEFAULT '', `title` varchar(256) NOT NULL, `description` text NOT NULL, PRIMARY KEY (`id`), KEY `target_id` (`target_id`), KEY `session_id` (`session_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
            echo '<p>Таблица cms_content_images создана;</p>';
        }

        if (!$inDB->isFieldExists('cms_modules_bind', 'tpl')){
            $inDB->query("ALTER TABLE `cms_modules_bind` ADD `tpl` VARCHAR(64) NOT NULL DEFAULT '_default_' AFTER `position`");
            $inDB->query("UPDATE `cms_modules_bind` SET `tpl`='". $inConf->template ."' WHERE 1=1");
            echo '<p>Поле tpl добавлено в таблицу cms_modules_bind;</p>';
        }

        if (!$inDB->isFieldExists('cms_category', 'pagetitle')){
            $inDB->query("ALTER TABLE `cms_category`  ADD `pagetitle` VARCHAR(255) NOT NULL AFTER `description`,  ADD `meta_desc` VARCHAR(1024) NOT NULL AFTER `pagetitle`,  ADD `meta_keys` VARCHAR(1024) NOT NULL AFTER `meta_desc`");
            echo '<p>Поля pagetitle, meta_desc, meta_keys добавлены в таблицу cms_category;</p>';
        }

        if (!$inDB->isFieldExists('cms_user_groups_access', 'hide_for_guest')){
            $inDB->query("ALTER TABLE `cms_user_groups_access` ADD `hide_for_guest` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `access_name`");
            $inDB->query("UPDATE cms_user_groups_access SET hide_for_guest=1 WHERE id=4 or id=5 or id=6 or id=9 or id=12 or id=14 or id=15");
            echo '<p>Поле hide_for_guest добавлено в таблицу cms_user_groups_access;</p>';
        }
        
        if (!$inDB->isFieldExists('cms_clubs', 'seolink')){ 
            $inDB->query("ALTER TABLE `cms_clubs` ADD `seolink` VARCHAR(200) NOT NULL AFTER `join_cost`, ADD INDEX (`seolink`)"); 
            echo '<p>Поле seolink добавлено в таблицу cms_clubs;</p>';
            
            $items = $inDB->get_table('cms_clubs');
            foreach ($items as $item){
                $inDB->query("UPDATE `cms_clubs` SET `seolink`='". cmsCore::strToURL($item['title']) ."' WHERE id='". $item['id'] ."'");
            }
            echo '<p>---- Сгенерированы seolink для всех клубов;</p>';
        } 

        if (!$inDB->get_field('cms_plugins', "plugin='p_content_imgs'", 'id')){
            $id = $inDB->insert('cms_plugins', array('plugin' => 'p_content_imgs', 'title' => 'Прикрепленные к статьям фотографии', 'description' => 'Плагин добавляет в конце статьи карусель (слайдер) с прикрепленными фотографиями. Вставляет в текст статьи фотографии в тех местах где прописана конструкция вида {img#123}', 'author' => 'DS Soft', 'version' => '0.0.1', 'plugin_type' => 'plugin', 'published' => 1, 'config' => '---\nPCI_SLIDER: jCarousel\nPCI_SLIDER_OPT: 2\nPCI_INSERT_IMAGES: 1\nPCI_DELETE_ERRORS: 1\n'));
            $inDB->insert('cms_event_hooks', array('event' => 'GET_ARTICLE', 'plugin_id' => $id));
            echo '<p>Установлен и включен плагин p_content_imgs;</p>';
        }
        
        $inDB->query("ALTER TABLE `cms_ratings` DROP INDEX `user_id`");
        $inDB->query("ALTER TABLE `cms_ratings` DROP INDEX `item_id`");
        $inDB->query("ALTER TABLE `cms_ratings` ADD INDEX `item_id` (`item_id`,`target`,`user_id`)");
        $inDB->query("ALTER TABLE `cms_ratings` ADD INDEX `ip` (`item_id`,`target`,`ip`)");
        $inDB->query("ALTER TABLE `cms_ratings` ADD INDEX `user_id` (`user_id`,`target`)");
        
        $inDB->query("ALTER TABLE `cms_ratings_total` DROP INDEX `item_id`");
        $inDB->query("ALTER TABLE `cms_ratings_total` DROP INDEX `target`");
        $inDB->query("ALTER TABLE `cms_ratings_total` ADD INDEX `item_id` (`item_id`,`target`)");
        
        $con_cfg = $inCore->loadComponentConfig('content');
        $con_cfg['img_table'] = 'cms_content_images';
        $con_cfg['imgs_big_w'] = 300;
        $con_cfg['imgs_big_h'] = 300;
        $con_cfg['imgs_medium_w'] = 200;
        $con_cfg['imgs_medium_h'] = 200;
        $con_cfg['imgs_small_w'] = 100;
        $con_cfg['imgs_small_h'] = 100;
        $con_cfg['resize_type'] = 'auto';
        $con_cfg['mresize_type'] = 'auto';
        $con_cfg['sresize_type'] = 'auto';
        $inCore->saveComponentConfig('content', $con_cfg);
        
        cmsDatabase::optimizeTables();

        echo '<div style="margin:15px 0px;font-weight:bold">Миграция завершена. Удалите папку /migrate/ прежде чем продолжить!</div>';
        echo '<div class="nextlink"><a href="/">Перейти на сайт</a></div>';
    }
// ========================================================================== //
// ========================================================================== //

    echo '</div></body></html>';

?>
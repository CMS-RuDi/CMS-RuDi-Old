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

    $version_prev = '1.10.2';
    $version_next = '1.10.3';

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

        // ========================================================================== //
        // ========================================================================== //

		if(!$inDB->get_field('cms_cron_jobs', "job_name='clearOnlineUsers'", 'id')){
			cmsCron::registerJob('clearOnlineUsers', array(
											'interval' => 0,
											'component' => '',
											'model_method' => '',
											'comment' => 'Удаляет просроченные данные об online пользователях',
											'custom_file' => '',
											'enabled' => 1,
											'class_name' => 'user|cmsUser',
											'class_method' => 'clearOnlineUsers'
									  ));
            echo '<p>Задача для CRON "clearOnlineUsers" добавлена в систему.</p>';
		}

        // ========================================================================== //
        // ========================================================================== //

        if (!$inDB->isFieldExists('cms_forms', 'tpl')){
            $inDB->query("ALTER TABLE `cms_forms` ADD `tpl` VARCHAR( 20 ) NOT NULL DEFAULT 'form' AFTER `form_action`");
            echo '<p>Поле tpl добавлено в таблицу cms_forms.</p>';
        }

        // ========================================================================== //
        // ========================================================================== //

        if (!$inDB->isFieldExists('cms_banlist', 'cause')){
            $inDB->query("ALTER TABLE `cms_banlist` ADD `cause` TEXT NOT NULL AFTER `autodelete`");
            $inDB->query("ALTER TABLE `cms_banlist` CHANGE `bandate` `bandate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
            echo '<p>Поле cause добавлено в таблицу cms_banlist.</p>';
        }

        // ========================================================================== //
        // ========================================================================== //

        if (!$inDB->isFieldExists('cms_board_items', 'more_images')){
            $inDB->query("ALTER TABLE `cms_board_items` CHANGE `hits` `hits` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'");
            $inDB->query("ALTER TABLE `cms_board_items` CHANGE `pubdate` `pubdate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
            $inDB->query("ALTER TABLE `cms_board_items` ADD `more_images` TEXT NOT NULL AFTER `file`");
            echo '<p>Поле more_images добавлено в таблицу cms_board_items.</p>';
        }

        // ========================================================================== //
        // ========================================================================== //

        if (!$inDB->isTableExists('cms_tag_targets')){
            $inDB->query("CREATE TABLE `cms_tag_targets` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `target` varchar(32) NOT NULL,
                          `component` varchar(32) NOT NULL,
                          `title` varchar(70) NOT NULL,
                          PRIMARY KEY (`id`),
                          UNIQUE KEY `target` (`target`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
            $inDB->query("INSERT INTO `cms_tag_targets` (`id`, `target`, `component`, `title`) VALUES
                        (1, 'content', 'content', 'Статьи'),
                        (2, 'blogpost', 'blogs', 'Записи блогов'),
                        (3, 'photo', 'photos', 'Фотографии галереи'),
                        (4, 'userphoto', 'users', 'Фотографии пользователей'),
                        (5, 'catalog', 'catalog', 'Записи каталога');");
            if ($inCore->isComponentInstalled('shop')){
                $inDB->query("INSERT INTO `cms_tag_targets` (`target`, `component`, `title`) VALUES
                            ('shop', 'shop', 'InstantShop');");
            }
            if ($inCore->isComponentInstalled('maps')){
                $inDB->query("INSERT INTO `cms_tag_targets` (`target`, `component`, `title`) VALUES
                            ('maps', 'maps', 'InstantMaps');");
            }
            if ($inCore->isComponentInstalled('video')){
                $inDB->query("INSERT INTO `cms_tag_targets` (`target`, `component`, `title`) VALUES
                            ('video', 'video', 'InstantVideo');");
            }
            echo '<p>Таблица cms_tag_targets создана и заполнена.</p>';
        }

        $inDB->query("ALTER TABLE `cms_polls` CHANGE `pubdate` `pubdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $inDB->query("UPDATE `cms_uc_items` SET `imageurl` = IF(imageurl <> '', CONCAT(imageurl, '.jpg'), '')");

        cmsUser::registerGroupAccessType('forum/add_post', 'Отвечать в темах на форуме');
        cmsUser::registerGroupAccessType('forum/add_thread', 'Создавать новые темы на форуме');
        echo '<p>Добавлены новые права доступа групп: "Отвечать в темах на форуме" и "Создавать новые темы на форуме"</p>';

        // ========================================================================== //
        // ========================================================================== //

        if (!$inDB->isTableExists('cms_geo_cities')){

            $inDB->importFromFile(PATH.'/migrate/geo.sql');

            echo '<p>Таблицы cms_geo_cities, cms_geo_countries, cms_geo_regions созданы и заполнены.</p>';

        }

        // ========================================================================== //
        // ========================================================================== //

        if(!$inDB->rows_count('cms_components', "link='geo'", 1)){

            $inDB->query("INSERT INTO `cms_components` (`title`, `link`, `config`, `internal`, `author`, `published`, `version`, `system`) VALUES ('Геолокация', 'geo', '', 1, 'InstantCMS team', 1, '1.10', 1);");

            echo '<p>Компонент "Геолокация" установлен.</p>';

            // что хитрые проверки на наличие индекса не делать, создадим индекс тут
            $inDB->query("ALTER TABLE `cms_modules` ADD FULLTEXT (`content`)");

        }
        // ========================================================================== //
        // ========================================================================== //

        cmsDatabase::optimizeTables();
		echo '<div style="margin:15px 0px;font-weight:bold">Миграция завершена. Удалите папку /migrate/ прежде чем продолжить!</div>';
		echo '<div style="margin:15px 0px;">Так же удалите файлы и каталоги на хостинге:</div>';
		echo '<ul>';
        echo '<li>/templates/_default_/special/images/regcomplete.png</li>';
        echo '<li>/templates/_default_/modules/mod_hmenu.tpl</li>';
        echo '<li>/modules/mod_clock/</li>';
        echo '<li>/languages/ru/modules/mod_arhive.php</li>';
        echo '<li>/languages/ru/modules/mod_usersearch.php</li>';
        echo '<li>/languages/ru/admin/applet_components.php</li>';
        echo '<li>/languages/ru/admin/applet_plugins.php</li>';
        echo '<li>/languages/ru/admin/com_actions.php</li>';
        echo '<li>/includes/spyc/spyc.yaml</li>';
        echo '<li>/includes/letters/ - ПЕРЕНЕСЕНА в /languages/ru/letters/</li>';
        echo '<li>/includes/jquery/tabs/tab.png</li>';
        echo '<li>/includes/jquery/superfish/</li>';
        echo '<li>/includes/jquery/lightbox/</li>';
        echo '<li>/includes/jquery/datepicker</li>';
        echo '<li>/includes/jquery/jquery.blockUI.js</li>';
        echo '<li>/images/icons/download.gif</li>';
        echo '<li>/images/swf/</li>';
        echo '<li>/core/js/pagesel.js</li>';
        echo '<li>/core/auth</li>';
        echo '<li>/core/messages</li>';
        echo '<li>/core/splash</li>';
        echo '<li>/admin/modules/mod_tags/backend.php</li>';
        echo '<li>/admin/modules/mod_respect/backend.php</li>';
        echo '<li>/admin/modules/mod_polls/backend.php</li>';
        echo '<li>/admin/modules/mod_hmenu</li>';
        echo '<li>/admin/modules/mod_swftags</li>';
        echo '<li>/admin/js/config.js</li>';
		echo '</ul>';
		echo '<div class="nextlink"><a href="/">Перейти на сайт</a></div>';

	}
// ========================================================================== //
// ========================================================================== //

    echo '</div></body></html>';

?>
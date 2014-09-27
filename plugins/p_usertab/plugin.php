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

class p_usertab extends cmsPlugin {

// ==================================================================== //

    public function __construct(){

        parent::__construct();

        // Информация о плагине
        $this->info['plugin']      = 'p_usertab';
        $this->info['title']       = 'Demo Profile Plugin';
        $this->info['description'] = 'Пример плагина - Добавляет вкладку "Статьи" в профили всех пользователей';
        $this->info['author']      = 'InstantCMS Team';
        $this->info['version']     = '1.10.3';

        // Настройки по-умолчанию
        $this->config['PU_LIMIT'] = 10;

        // События, которые будут отлавливаться плагином
        $this->events[] = 'USER_PROFILE';

    }

// ==================================================================== //

    /**
     * Процедура установки плагина
     * @return bool
     */
    public function install(){

        return parent::install();

    }

// ==================================================================== //

    /**
     * Процедура обновления плагина
     * @return bool
     */
    public function upgrade(){

        return parent::upgrade();

    }

// ==================================================================== //

    /**
     * Обработка событий
     * @param string $event
     * @param array $user
     * @return html
     */
    public function execute($event='', $user=array()){

        global $_LANG;
        $this->info['tab']       = $_LANG['PU_TAB_NAME']; //-- Заголовок закладки в профиле
        // Загружать вкладку по ajax
        $this->info['ajax_link'] = '/plugins/'.__CLASS__.'/get.php?user_id='.$user['id'];

        parent::execute();

        return '';

    }

    public function viewTab($user_id){

		$inDB = cmsDatabase::getInstance();

		cmsCore::loadModel('content');
		$model = new cms_model_content();

		$model->whereUserIs($user_id);

		$total = $model->getArticlesCount();

		$inDB->orderBy('con.pubdate', 'DESC');
		$inDB->limitPage(1, (int)$this->config['PU_LIMIT']);

		$content_list = $total ?
						$model->getArticlesList() :
						array(); $inDB->resetConditions();

        ob_start();

        cmsPage::initTemplate('plugins', 'p_usertab')->
                assign('total', $total)->
                assign('articles', $content_list)->
                display();

        return ob_get_clean();

    }
// ==================================================================== //

}

?>

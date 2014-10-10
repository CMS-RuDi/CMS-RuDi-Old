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
    public function __construct() {
        parent::__construct();

        // Информация о плагине
        $this->info = array(
            'plugin'      => 'p_usertab',
            'title'       => 'Demo Profile Plugin',
            'description' => 'Пример плагина - Добавляет вкладку "Статьи" в профили всех пользователей',
            'author'      => 'InstantCMS Team',
            'version'     => '1.10.3'
        );

        // Настройки по-умолчанию
        $this->config = array(
            'PU_LIMIT' => 10
        );

        // События, которые будут отлавливаться плагином
        $this->events = array( 'USER_PROFILE' );
    }

    /**
     * Процедура установки плагина
     * @return bool
     */
    public function install() {
        return parent::install();
    }

    /**
     * Процедура обновления плагина
     * @return bool
     */
    public function upgrade() {
        return parent::upgrade();
    }
    
    /**
     * Процедура удаления плагина
     * @return bool
     */
    public function uninstall() {
        return parent::uninstall();
    }

    /**
     * Обработка событий
     * @param string $event
     * @param array $user
     * @return html
     */
    public function execute($event='', $user=array()) {
        global $_LANG;
        $this->info['tab'] = $_LANG['PU_TAB_NAME']; //-- Заголовок закладки в профиле
        //
        // Загружать вкладку по ajax
        $this->info['ajax_link'] = '/plugins/'.__CLASS__.'/get.php?user_id='. $user['id'];

        parent::execute();

        return '';
    }

    public function viewTab($user_id) {
        cmsCore::m('content')->whereUserIs($user_id);
        
        $total = cmsCore::m('content')->getArticlesCount();

        cmsCore::c('db')->orderBy('con.pubdate', 'DESC');
        cmsCore::c('db')->limitPage(1, (int)$this->config['PU_LIMIT']);
        
        $content_list = $total ? cmsCore::m('content')->getArticlesList() : array();
        
        cmsCore::c('db')->resetConditions();

        return cmsPage::initTemplate('plugins', 'p_usertab')->
                assign('total', $total)->
                assign('articles', $content_list)->
                fetch();
    }
}
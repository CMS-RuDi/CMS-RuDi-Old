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

class p_ping extends cmsPlugin {
    public function __construct() {
        // Информация о плагине
        $this->info = array(
            'plugin'      => 'p_ping',
            'title'       => 'Пинг поисковых систем',
            'description' => 'Пингует Яндекс и Гугл при добавлении статей, объявлений и постов в блоги', 
            'author'      => 'InstantCMS Team', 
            'version'     => '1.10'
        );

        // Настройки по-умолчанию
        $this->config = array(
            'Yandex HOST' => 'ping.blogs.yandex.ru',
            'Yandex PATH' => '/RPC2',
            'Google HOST' => 'blogsearch.google.com',
            'Google PATH' => '/ping/RPC2'
        );

        // События, которые будут отлавливаться плагином
        $this->events = array(
            'ADD_POST_DONE', 'ADD_ARTICLE_DONE', 'ADD_BOARD_DONE'
        );
        
        parent::__construct();
    }
    
    public function getConfigFields() {
        return array(
            array( 'type' => 'text', 'title' => 'Yandex HOST', 'name' => 'Yandex HOST' ),
            array( 'type' => 'text', 'title' => 'Yandex PATH', 'name' => 'Yandex PATH' ),
            array( 'type' => 'text', 'title' => 'Google HOST', 'name' => 'Google HOST' ),
            array( 'type' => 'text', 'title' => 'Google PATH', 'name' => 'Google PATH' ),
        );
    }

    /**
     * Обработка событий
     * @param string $event
     * @param mixed $item
     * @return mixed
     */
    public function execute($event='', $item=array()) {
        switch ($event) {
            case 'ADD_POST_DONE':
                $pageURL = HOST . $item['seolink'];
                $feedURL = HOST . '/rss/blogs/all/feed.rss';
                $this->ping($pageURL, $feedURL);
            break;

            case 'ADD_ARTICLE_DONE':
                $pageURL = HOST .'/'. $item['seolink'] .'.html';
                $feedURL = HOST . '/rss/content/all/feed.rss';
                $this->ping($pageURL, $feedURL);

            case 'ADD_BOARD_DONE':
                $pageURL = HOST . '/board/read'.$item['id'].'.html';
                $feedURL = HOST . '/rss/board/all/feed.rss';
                $this->ping($pageURL, $feedURL);

            break;
        }

        return $item;
    }

    private function ping($pageURL, $feedURL) {
        $inConf = cmsConfig::getInstance();
        $inUser = cmsUser::getInstance();
        global $_LANG;

        require_once(PATH .'/plugins/p_ping/IXR_Library.php');

        $siteName = $inConf->sitename;
        $siteURL  = HOST .'/';

        $result   = array();

        //
        // Яндекс.Блоги
        //
        if ($this->config['Yandex HOST']) {
            $pingClient = new IXR_Client($this->config['Yandex HOST'], $this->config['Yandex PATH']);

            // Посылаем запрос
            if ($pingClient->query('weblogUpdates.ping', $siteName, $siteURL, $pageURL, $feedURL)) {
                $result[] = $_LANG['P_PING_YANDEX'];
            }

            unset($pingClient);
        }

        //
        // Google
        //
        if ($this->config['Google HOST']) {
            $pingClient = new IXR_Client($this->config['Google HOST'], $this->config['Google PATH']);

            // Посылаем запрос
            if ($pingClient->query('weblogUpdates.extendedPing', $siteName, $siteURL, $pageURL, $feedURL)) {
                $result[] = $_LANG['P_PING_GOOGLE'];
            }
            
            unset($pingClient);
        }

        if ($inUser->is_admin && $result) {
            cmsCore::addSessionMessage(implode(', ', $result), 'info');
        }

        return;
    }
}
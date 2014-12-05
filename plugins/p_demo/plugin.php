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

class p_demo extends cmsPlugin {
    public function __construct() {
        parent::__construct();

        // Информация о плагине
        $this->info = array(
            'plugin'      => 'p_demo',
            'title'       => 'Demo Plugin',
            'description' => 'Пример плагина - Добавляет текст в конец каждой статьи на сайте',
            'author'      => 'InstantCMS Team',
            'version'     => '1.0'
        );

        // Настройки по-умолчанию
        $this->config = array(
            'text'    => 'Added By Plugin From Parameter',
            'color'   => 'blue',
            'counter' => 1
        );

        // События, которые будут отлавливаться плагином
        $this->events = array(
            'GET_ARTICLE'
        );
    }
    
    public function getConfigFields() {
        return array(
            array(
                'type' => 'text',
                'title' => 'Text',
                'name' => 'text'
            ),
            array(
                'type' => 'text',
                'title' => 'Color',
                'name' => 'color'
            ),
            array(
                'type' => 'number',
                'title' => 'Counter',
                'name' => 'counter'
            )
        );
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
     * @param mixed $item
     * @return mixed
     */
    public function execute($event='', $item=array()) {
        parent::execute();

        switch ($event) {
            case 'GET_ARTICLE': $item = $this->eventGetArticle($item); break;
        }

        return $item;
    }

    private function eventGetArticle($item) {
        $item['content'] .= '<p style="color:'.$this->config['color'].'"><strong>'.$this->config['text'].' - '.$this->config['counter'].'</strong></p>';

        $this->config['counter'] += 1;

        $this->saveConfig();

        return $item;
    }
}
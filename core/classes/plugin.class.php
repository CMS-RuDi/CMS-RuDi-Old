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

class cmsPlugin {
    protected $inDB;
    protected $inCore;
    protected $inPage;

    public $info; // Информация о плагине
    public $events; // События отлавливаемые плагином
    public $config; // Массив настроек плагина

    public function __construct() {
        $this->inCore = cmsCore::getInstance();
        $this->inDB   = cmsCore::c('db');
        $this->inPage = cmsCore::c('page');
        $this->config = array_merge($this->config, $this->inCore->loadPluginConfig(get_called_class()));
    }

    public function __clone() {}
    
    /**
     * Массив с элементами формы настроек плагина
     * @return boolean|array
     */
    public function getConfigFields() {
        return false;
    }

    /**
     * Процедура установки плагина
     * @return boolean
     */
    public function install() {
        return $this->inCore->installPlugin($this->info, $this->events, $this->config);
    }

    /**
     * Процедура обновления плагина
     * @return boolean
     */
    public function upgrade() {
        return $this->inCore->upgradePlugin($this->info, $this->events, $this->config);
    }
    
    /**
     * Процедура удаления плагина
     * @return boolean
     */
    public function uninstall() {
        return $this->inCore->removePlugin($this->info);
    }

    /**
     * Обработка событий
     * @param string $event идентификатор события
     * @param array $item входные данные
     * @return mixed в зависимости от события возвращает измененые данные или html
     * код
     */
    public function execute($event='', $item=array()) {}

    /**
     * Сохраняет настройки плагина
     */
    public function saveConfig() {
        $this->inCore->savePluginConfig( $this->info['plugin'], $this->config );
    }
}
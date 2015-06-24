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

if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

class cmsAdmin extends cmsCore {
    private static $applet;
    
    protected function __construct($install_mode=false) {
        parent::__construct($install_mode);
        
        if (self::inRequest('default_template')) {
            self::c('config')->admin_template = 'admin/_default_';
        }
        
        self::c('config')->admin_template_dir = PATH .'/templates/'. self::c('config')->admin_template .'/';
        self::c('config')->admin_default_template_dir = PATH .'/templates/admin/_default_/';
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function getApplet() {
        if (empty(self::$applet)) {
            self::$applet = self::request('view', 'str', 'main');
            if (!preg_match('/^[a-z0-9_]+$/i', self::$applet)) {
                self::error404();
            }
        }
        return self::$applet;
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Устанавливает плагин и делает его привязку к событиям
     * Возвращает ID установленного плагина
     * @param array $plugin
     * @param array $events
     * @param array $config
     * @return int
     */
    public function installPlugin($plugin, $events, $config) {
        if (empty($plugin['type'])) { $plugin['type'] = 'plugin'; }

        $config_yaml = self::arrayToYaml($config);
        if (!$config_yaml) { $config_yaml = ''; }
        $plugin['config'] = self::c('db')->escape_string($config_yaml);

        //добавляем плагин в базу
        $plugin_id = self::c('db')->insert('cms_plugins', $plugin);

        //возвращаем ложь, если плагин не установился
        if (!$plugin_id) { return false; }

        //добавляем хуки событий для плагина
        foreach($events as $event) {
            self::c('db')->insert('cms_event_hooks', array('event'=>$event, 'plugin_id'=>$plugin_id));
        }

        //возращаем ID установленного плагина
        return $plugin_id;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Делает апгрейд установленного плагина
     * @param array $plugin
     * @param array $events
     * @param array $config
     * @return bool
     */
    public function upgradePlugin($plugin, $events, $config) {
        //находим ID установленной версии
        $plugin_id = $this->getPluginId($plugin['plugin']);

        //если плагин еще не был установлен, выходим
        if (!$plugin_id) { return false; }

        //загружаем текущие настройки плагина
        $old_config = $this->loadPluginConfig($plugin['plugin']);

        //удаляем настройки, которые больше не нужны
        foreach($old_config as $param=>$value){
            if ( !isset($config[$param]) ){
                unset($old_config[$param]);
            }
        }

        //добавляем настройки, которых раньше не было
        foreach($config as $param=>$value){
            if ( !isset($old_config[$param]) ){
                $old_config[$param] = $value;
            }
        }

        //конвертируем массив настроек в YAML
        $plugin['config'] = self::c('db')->escape_string(self::arrayToYaml($old_config));

        //обновляем плагин в базе
        self::c('db')->update('cms_plugins', $plugin, $plugin_id);

        //добавляем новые хуки событий для плагина
        foreach($events as $event){
            if (!$this->isPluginHook($plugin_id, $event)){
                self::c('db')->insert('cms_event_hooks', array('event'=>$event, 'plugin_id'=>$plugin_id));
            }
        }

        //плагин успешно обновлен
        return true;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Удаляет установленный плагин
     * @param array $plugin
     * @return bool
     */
    public function removePlugin($plugin) {
        //находим ID установленной версии
        $plugin_id = $this->getPluginId($plugin['plugin']);
        
        //если плагин не был установлен, выходим
        if (!$plugin_id) { return false; }

        //удаляем плагин из базы
        self::c('db')->delete('cms_plugins', "id = '$plugin_id'");

        //Удаляем хуки событий плагина
        self::c('db')->delete('cms_event_hooks', "plugin_id = '$plugin_id'");

        //плагин успешно удален
        return true;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает список плагинов, имеющихся на диске, но не установленных
     * @return array
     */
    public function getNewPlugins() {
        $new_plugins    = array();
        $all_plugins    = $this->getPluginsDirs();

        if (!$all_plugins) { return false; }

        foreach($all_plugins as $plugin){
            $installed = self::c('db')->rows_count('cms_plugins', "plugin='{$plugin}'", 1);
            if (!$installed){
                $new_plugins[] = $plugin;
            }
        }

        if (!$new_plugins) { return false; }

        return $new_plugins;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает список плагинов, версия которых изменилась в большую сторону
     * @return array
     */
    public function getUpdatedPlugins() {
        $upd_plugins    = array();
        $all_plugins    = $this->getPluginsDirs();

        if (!$all_plugins) { return false; }

        foreach($all_plugins as $plugin){
            $plugin_obj = $this->loadPlugin($plugin);
            $version    = $this->getPluginVersion($plugin);
            if ($version){
                if (version_compare($plugin_obj->info['version'], $version) > 0){
                    $upd_plugins[] = $plugin;
                }
            }
        }

        if (!$upd_plugins) { return false; }

        return $upd_plugins;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает список папок с плагинами
     * @return array
     */
    public static function getPluginsDirs(){
        return self::getDirsList('/plugins');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает ID плагина по названию
     * @param string $plugin
     * @return int
     */
    public function getPluginId($plugin){
        return self::c('db')->get_field('cms_plugins', "plugin='{$plugin}'", 'id');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает название плагина по ID
     * @param int $plugin_id
     * @return string
     */
    public function getPluginById($plugin_id){
        return self::c('db')->get_field('cms_plugins', "id='{$plugin_id}'", 'plugin');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает версию плагина по названию
     * @param string $plugin
     * @return float
     */
    public function getPluginVersion($plugin){
        return self::c('db')->get_field('cms_plugins', "plugin='{$plugin}'", 'version');
    }
    
    /**
     * Возвращает кофигурацию плагина в виде массива из БД
     * @param string $plugin
     * @return float
     */
    public function loadPluginConfig($plugin_name){
        $config = self::c('db')->get_field('cms_plugins', "plugin='". $plugin_name ."'", 'config');
        
        if (!empty($config)) { $config = self::yamlToArray($config); }
        
        return $config;
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Устанавливает компонент
     * Возвращает ID установленного плагина
     * @param array $component
     * @param array $config
     * @return int
     */
    public function installComponent($component, $config) {
        $config_yaml = self::arrayToYaml($config);
        if (!$config_yaml) { $config_yaml = ''; }
		$component['config'] = self::c('db')->escape_string($config_yaml);

        //добавляем компонент в базу
        $component_id = self::c('db')->insert('cms_components', $component);

        //возращаем ID установленного компонента
        return $component_id ? $component_id : false;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Делает апгрейд установленного компонента
     * @param array $component
     * @param array $config
     * @return bool
     */
    public function upgradeComponent($component, $config){
        //находим ID установленной версии
        $component_id = $this->getComponentId( $component['link'] );

        //если компонент еще не был установлен, выходим
        if (!$component_id) { return false; }

        //загружаем текущие настройки компонента
        $old_config = $this->loadComponentConfig( $component['link'] );

        //удаляем настройки, которые больше не нужны
        foreach($old_config as $param=>$value){
            if ( !isset($config[$param]) ){
                unset($old_config[$param]);
            }
        }

        //добавляем настройки, которых раньше не было
        foreach($config as $param=>$value){
            if ( !isset($old_config[$param]) ){
                $old_config[$param] = $value;
            }
        }

        //конвертируем массив настроек в YAML
        $component['config'] = self::c('db')->escape_string(self::arrayToYaml($old_config));

        //обновляем компонент в базе
        return self::c('db')->update('cms_components', $component, $component_id);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Удаляет установленный компонент
     * @param int $component_id
     * @return bool
     */
    public function removeComponent($component_id) {
        //если компонент не был установлен, выходим
        if (!$component_id) { return false; }

        //определяем название компонента по id
        $component = $this->getComponentById($component_id);

        //удаляем зависимые модули компонента
        if ($this->loadComponentInstaller($component)){
            $_component = call_user_func('info_component_'.$component);
            if (isset($_component['modules'])){
                if (is_array($_component['modules'])){
                    foreach($_component['modules'] as $module=>$title){
                        $module_id = $this->getModuleId($module);
                        if ($module_id) { $this->removeModule($module_id); }
                    }
                }
            }
        }

        //удаляем компонент из базы, но только если он не системный
        self::c('db')->delete('cms_components', "id = '$component_id' AND system = 0");

        //компонент успешно удален
        return true;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает список компонентов, имеющихся на диске, но не установленных
     * @return array
     */
    public function getNewComponents() {
        $new_components = array();
        $all_components = self::getComponentsDirs();

        if (!$all_components) { return false; }

        foreach($all_components as $component){

            $installer_file = PATH . '/components/' . $component . '/install.php';

            if (file_exists($installer_file)){

                if (!$this->isComponentInstalled($component)){
                    $new_components[] = $component;
                }

            }

        }

        if (!$new_components) { return false; }

        return $new_components;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает список компонентов, версия которых изменилась в большую сторону
     * @return array
     */
    public function getUpdatedComponents() {
        $upd_components = array();

        foreach($this->components as $component){
            if($this->loadComponentInstaller($component['link'])){
                $version    = $component['version'];
                $_component = call_user_func('info_component_'.$component['link']);
                if ($version){
                    if (version_compare($_component['version'], $version) > 0){
                        $upd_components[] = $component['link'];
                    }
                }
            }
        }

        if (!$upd_components) { return false; }

        return $upd_components;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает список папок с компонентами
     * @return array
     */
    public static function getComponentsDirs(){
        return self::getDirsList('/components');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает ID компонента по названию
     * @param string $component
     * @return int
     */
    public function getComponentId($component){
        $component_id = 0;

        foreach ($this->components as $inst_component){
           if($inst_component['link'] == $component){
                  $component_id = $inst_component['id']; break;
           }
        }

        return $component_id;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает название компонента по ID
     * @param int $component_id
     * @return string
     */
    public function getComponentById($component_id){
        $link = '';

        foreach ($this->components as $inst_component){
           if($inst_component['id'] == $component_id){
                  $link = $inst_component['link']; break;
           }
        }

        return $link;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает версию компонента по названию
     * @param string $component
     * @return float
     */
    public function getComponentVersion($component){
        $version = '';

        foreach ($this->components as $inst_component){
           if($inst_component['link'] == $component){
                  $version = $inst_component['version']; break;
           }
        }

        return $version;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function loadComponentInstaller($component){
        return $this->includeFile('components/'.$component.'/install.php');;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Устанавливает модуль
     * Возвращает ID установленного модуля
     * @param array $module
     * @param array $config
     * @return int
     */
    public function installModule($module, $config) {
        $config_yaml = self::arrayToYaml($config);
        if (!$config_yaml) { $config_yaml = ''; }
		$module['config'] = self::c('db')->escape_string($config_yaml);
        // Помечаем, что модуль внешний
        $module['is_external'] = 1;
        // переходной костыль
        // в модулях теперь нужно вместо, например
        // $_module['link'] = 'mod_actions';
        // писать
        // $_module['content'] = 'mod_actions';
        if (isset($module['link'])) {
            $module['content'] = $module['link'];
        }

        //возращаем ID установленного модуля
        return self::c('db')->insert('cms_modules', $module);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Делает апгрейд установленного модуля
     * @param array $component
     * @param array $config
     * @return bool
     */
    public function upgradeModule($module, $config) {
        // удалить в следующем обновлении
        if (isset($module['link'])) {
            $module['content'] = $module['link'];
        }

        //находим ID установленной версии
        $module_id = $this->getModuleId($module['content']);

        //если модуль еще не был установлен, выходим
        if (!$module_id) { return false; }

        //загружаем текущие настройки модуля
        $old_config = $this->loadModuleConfig( $module_id );

        //удаляем настройки, которые больше не нужны
        foreach($old_config as $param=>$value){
            if ( !isset($config[$param]) ){
                unset($old_config[$param]);
            }
        }

        //добавляем настройки, которых раньше не было
        foreach($config as $param=>$value){
            if ( !isset($old_config[$param]) ){
                $old_config[$param] = $value;
            }
        }

        //конвертируем массив настроек в YAML
        $module['config'] = self::c('db')->escape_string(self::arrayToYaml($old_config));

        unset($module['position']);

        //обновляем модуль в базе
        self::c('db')->update('cms_modules', $module, $module_id);
        
        self::c('db')->query("UPDATE cms_modules SET version = '". $module['version'] ."' WHERE content='". $module['content'] ."' AND user=0");

        //модуль успешно обновлен
        return true;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Удаляет установленный модуль
     * @param mixed $module_id
     * @return bool
     */
    public function removeModule($module_id) {
        if (is_array($module_id)) {
            foreach ($module_id as $id) {
                $this->removeModule((int)$id);
            }
            
            return true;
        }

        $module = $this->getModuleById($module_id);
        if ($this->loadModuleInstaller($module)) {
            if (function_exists('remove_module_'. $module)) {
            	call_user_func('remove_module_'. $module);
            }
        }

        if (self::c('db')->delete('cms_modules', "id = '". $module_id ."'", 1)) {
            self::c('db')->delete('cms_modules_bind', "module_id='". $module_id ."'");
        }
        
        return false;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает список модулей, имеющихся на диске, но не установленных
     * @return array
     */
    public function getNewModules() {
        $new_modules = array();
        $all_modules = self::getModulesDirs();

        if (!$all_modules) { return false; }

        foreach($all_modules as $module){

            $installer_file = PATH . '/modules/' . $module . '/install.php';

            if (file_exists($installer_file)){

                $installed = self::c('db')->rows_count('cms_modules', "content='{$module}' AND user=0", 1);
                if (!$installed){
                    $new_modules[] = $module;
                }

            }

        }

        if (!$new_modules) { return false; }

        return $new_modules;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает список модулей, версия которых изменилась в большую сторону
     * @return array
     */
    public function getUpdatedModules() {
        $upd_modules = array();
        $all_modules = self::c('db')->get_table('cms_modules', 'user=0');

        if (!$all_modules) { return false; }

        foreach($all_modules as $module){
            if($this->loadModuleInstaller($module['content'])){
                $version = $module['version'];
                $_module = call_user_func('info_module_'.$module['content']);
                if ($version){
                    if (version_compare($_module['version'], $version) > 0){
                        $upd_modules[] = $module['content'];
                    }
                }
            }
        }

        if (!$upd_modules) { return false; }

        return $upd_modules;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает список папок с модулями
     * @return array
     */
    public static function getModulesDirs() {
        return self::getDirsList('/modules');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает ID модуля по названию
     * @param string $component
     * @return int
     */
    public function getModuleId($module){
        return self::c('db')->get_field('cms_modules', "content='{$module}' AND user=0", 'id');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает название модуля по ID
     * @param int $component_id
     * @return string
     */
    public function getModuleById($module_id){
        return self::c('db')->get_field('cms_modules', "id='{$module_id}' AND user=0", 'content');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Возвращает версию модуля по названию
     * @param string $component
     * @return float
     */
    public function getModuleVersion($module){
        return self::c('db')->get_field('cms_modules', "content='{$module}' AND user=0", 'version');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function loadModuleInstaller($module){
        return self::includeFile('modules/'.$module.'/install.php');;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Проверяет привязку плагина к событию
     * @param int $plugin_id
     * @param string $event
     * @return bool
     */
    public function isPluginHook($plugin_id, $event) {
        return self::c('db')->rows_count('cms_event_hooks', "plugin_id='{$plugin_id}' AND event='{$event}'");
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function getModuleTemplates() {
        $tpl_dir = is_dir(TEMPLATE_DIR . 'modules') ? TEMPLATE_DIR . 'modules' : PATH .'/templates/_default_/modules';
        $pdir    = opendir($tpl_dir);

        $templates  = array();

        while ($nextfile = readdir($pdir)){
            if (
                ($nextfile != '.')  &&
                ($nextfile != '..') &&
                !is_dir($tpl_dir.'/'.$nextfile) &&
                ($nextfile!='.svn') &&
                (mb_substr($nextfile, 0, 6)=='module')
           ) {
                $templates[$nextfile] = $nextfile;
            }
        }

        if (!sizeof($templates)){ return false; }

        return $templates;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    /** 
     * Возвращает прямую ссылку на пункт меню по его типу и опции 
     * @param string $linktype 
     * @param string $linkid 
     * @return string 
     */ 
    public function getMenuLink($linktype, $linkid){ 
        $menulink = ''; 

        if ($linktype=='component'){ 
            $menulink = '/'.$linkid; 
        } 

        if ($linktype=='link'){ 
            $menulink = $linkid; 
        } 

        if ($linktype=='category' || $linktype=='content'){ 
            switch($linktype){ 
                case 'category': $menulink = self::m('content')->getCategoryURL(null, self::c('db')->get_field('cms_category', "id='{$linkid}'", 'seolink')); break; 
                case 'content':  $menulink = self::m('content')->getArticleURL(null, self::c('db')->get_field('cms_content', "id='{$linkid}'", 'seolink')); break; 
            } 
        } 

        if ($linktype=='blog'){ 
            $menulink = self::m('blogs')->getBlogURL(self::c('db')->get_field('cms_blogs', "id='{$linkid}'", 'seolink')); 
        } 

        if ($linktype=='video_cat'){ 
            $this->loadModel('video'); 
            $model = cms_model_video::initModel(); 
            $cat = self::c('db')->get_fields('cms_video_category', "id='{$linkid}'", 'id, seolink'); 
            $menulink = $model->getCatLink($cat['seolink'], $cat['id']); 
        } 

        if ($linktype=='uccat'){ 
            $menulink = '/catalog/'. $linkid; 
        } 

        if ($linktype=='photoalbum'){ 
            $menulink = '/photos/'. $linkid; 
        } 

        return $menulink; 
    } 
}

//---------------------------- DEPRECATED FUNCTIONS ----------------------------
function dbShowList($table, $list){
    cmsCore::c('db')->setFlags($table, $list, 'published', '1');
}
function dbHideList($table, $list){
    cmsCore::c('db')->setFlags($table, $list, 'published', '0');
}
function dbShow($table, $id){
    cmsCore::c('db')->setFlag($table, $id, 'published', '1');
}
function dbHide($table, $id){
    cmsCore::c('db')->setFlag($table, $id, 'published', '0');
}
//==============================================================================
<?php

class migrateDB {
    public $calls = array(
        'create_fields' => array(), 'change_fields' => array()
    );
    private $cfg;

    public function __construct($cfg) {
        $this->cfg = $cfg;
    }
    
    /**
     * Проверяет было ли создано поле ранее
     * @param string $field
     * @return boolean
     */
    public function checkCreateField($field) {
        if (empty($field)) { return false; }
        return in_array($field, $this->calls['create_fields']);
    }

    /**
     * Создает новое поле в таблице БД
     */
    public function createFields() {
        if (!empty($this->cfg['create_fields'])) {
            foreach ($this->cfg['create_fields'] as $create_field) {
                if (!cmsCore::c('db')->isFieldExists($create_field['table'], $create_field['name'])) {
                    cmsCore::c('db')->query("ALTER TABLE `". $create_field['table'] ."` ADD `". $create_field['name'] ."` ". $create_field['type'] ." NOT NULL". (isset($create_field['default']) ? " DEFAULT ". $create_field['default'] : ""));
                    if (!empty($create_field['msg'])) {
                        echo '<p>'. $create_field['msg'] .';</p>';
                    } else {
                        echo '<p>Поле "'. $create_field['name'] .'" добавлено в таблицу "'. $create_field['table'] .'";</p>';
                    }
                    $this->calls['create_fields'][] = $create_field['name'];
                }
            }
        }
    }
    
    /**
     * Изменяет тип поля таблицы БД
     */
    public function changeFields() {
        if (!empty($this->cfg['change_fields'])) {
            foreach ($this->cfg['change_fields'] as $change_field) {
                if (cmsCore::c('db')->isFieldExists($change_field['table'], $change_field['name'])) {
                    cmsCore::c('db')->query("ALTER TABLE `". $change_field['table'] ."` CHANGE `". $change_field['name'] ."` `". (isset($change_field['new_name']) ? $change_field['new_name'] : $change_field['name']) ."` ". $change_field['type'] ." NOT NULL". (isset($change_field['default']) ? " DEFAULT ". $change_field['default'] : ""));
                    if (!empty($change_field['msg'])) {
                        echo '<p>'. $change_field['msg'] .';</p>';
                    } else {
                        echo '<p>Поле "'. $change_field['name'] .'" в таблице "'. $change_field['table'] .' измененно";</p>';
                    }
                    $this->calls['change_fields'][] = $change_field['name'];
                }
            }
        }
    }
    
    /**
     * Удаляет индексы таблицы БД
     */
    public function dropIndexes() {
        if (!empty($this->cfg['drop_indexes'])) {
            foreach ($this->cfg['drop_indexes'] as $drop_index) {
                $table = cmsCore::c('db')->fetch_assoc(cmsCore::c('db')->query('SHOW CREATE TABLE `'. $drop_index['table'] .'`'));
                foreach ($drop_index['names'] as $index) {
                    if (preg_match('#KEY[\s]+`'. $index .'`#is', $table['Create Table'], $m)) {
                        cmsCore::c('db')->query('ALTER TABLE `'. $drop_index['table'] .'` DROP INDEX `'. $index .'`');
                        if (!empty($drop_index['msg'])) {
                            echo '<p>'. $drop_index['msg'] .';</p>';
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Создает индексы в таблице БД
     */
    public function createIndexes() {
        if (!empty($this->cfg['create_indexes'])) {
            foreach ($this->cfg['create_indexes'] as $create_index) {
                foreach ($create_index['indexes'] as $index) {
                    cmsCore::c('db')->query('ALTER TABLE `'. $create_index['table'] .'` ADD '. (isset($index['unique']) ? 'UNIQUE' : 'INDEX') .' '. (!empty($index['name']) ? '`'. $index['name'] .'`' : '') .' (`'. implode('`,`', $index['fields']) .'`)');
                }
                if (!empty($create_index['msg'])) {
                    echo '<p>'. $create_index['msg'] .';</p>';
                }
            }
        }
    }
    
    /**
     * Вставляет новые строки в БД
     */
    public function inserts() {
        if (!empty($this->cfg['inserts'])) {
            foreach ($this->cfg['inserts'] as $insert) {
                if (!empty($insert['where'])) {
                    if (cmsCore::c('db')->get_field($insert['table'], $insert['where'], 'id')) {
                        continue;
                    }
                }
                
                $id = cmsCore::c('db')->insert($insert['table'], $insert['insert_array']);
                
                if (!empty($insert['after'])) {
                    if (is_array($insert['after'])) {
                        foreach ($insert['after'] as $sql) {
                            cmsCore::c('db')->query(str_replace('%id%', $id, $sql));
                        }
                    } else {
                        cmsCore::c('db')->query(str_replace('%id%', $id, $insert['after']));
                    }
                }
                
                if (!empty($insert['msg'])) {
                    echo '<p>'. $insert['msg'] .';</p>';
                }
            }
        }
    }

    /**
     * Выполняет произвольные запросы в БД
     */
    public function query() {
        if (!empty($this->cfg['queries'])) {
            foreach ($this->cfg['queries'] as $query) {
                cmsCore::c('db')->query($query['sql']);
                if (!empty($query['msg'])) {
                    echo '<p>'. $query['msg'] .';</p>';
                }
            }
        }
    }
    
    /**
     * Обновляет конфигурацию компонентов
     */
    public function setComCfgs() {
        if (!empty($this->cfg['com_cfgs'])) {
            $inCore = cmsCore::getInstance();
            foreach ($this->cfg['com_cfgs'] as $com) {
                $com_cfg = $inCore->loadComponentConfig($com['name']);
                if (!empty($com['unset_keys'])) {
                    foreach ($com['unset_keys'] as $key) {
                        unset($com_cfg[$key]);
                    }
                }
                if (!empty($com['merge_cfgs'])) {
                    $com_cfg = array_merge($com['merge_cfgs'], $com_cfg);
                }
                $inCore->saveComponentConfig($com['name'], $com_cfg);
            }
        }
    }
    
    /**
     * Обновляет конфигурацию модулей
     */
    public function setModCfgs() {
        if (!empty($this->cfg['mod_cfgs'])) {
            foreach ($this->cfg['mod_cfgs'] as $mod) {
                $results = cmsCore::c('db')->query("SELECT id,config FROM cms_modules WHERE content='". $mod['name'] ."'");
                
                if (!cmsCore::c('db')->num_rows($results)) { continue; }
                
                while ($module = cmsCore::c('db')->fetch_assoc($results)) {
                    if (!empty($module['config'])) {
                        $module['config'] = cmsCore::yamlToArray($module['config']);
                        
                        if (!empty($mod['unset_keys'])) {
                            foreach ($mod['unset_keys'] as $key) {
                                unset($module['config'][$key]);
                            }
                        }
                    } else {
                        $module['config'] = array();
                    }
                    
                    if (!empty($mod['merge_cfgs'])) {
                        $module['config'] = array_merge($mod['merge_cfgs'], $module['config']);
                    }
                    
                    $module['config'] = cmsCore::arrayToYaml($module['config']);
                    cmsCore::c('db')->update(
                        'cms_modules',
                        array(
                            'config' => cmsCore::c('db')->escape_string($module['config'])
                        ),
                        $module['id']
                    );
                }
            }
        }
    }
    
}
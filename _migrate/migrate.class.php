<?php

class migrateDB{
    public $calls = array(
        'create_fields' => array()
    );
    private $cfg;

    public function __construct($cfg) {
        $this->cfg = $cfg;
    }
    
    public function checkCreateField($field) {
        if (empty($field)) { return false; }
        return in_array($field, $this->calls['create_fields']);
    }

    public function createFields() {
        if (!empty($this->cfg['create_fields'])) {
            foreach ($this->cfg['create_fields'] as $create_field) {
                if (!cmsCore::c('db')->isFieldExists($create_field['table'], $create_field['name'])) {
                    cmsCore::c('db')->query("ALTER TABLE `". $create_field['table'] ."` ADD `". $create_field['name'] ."` ". $create_field['type'] ." NOT NULL". (isset($create_field['default']) ? " DEFAULT '". $create_field['default'] ."'" : ""));
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
                    cmsCore::c('db')->query(str_replace('%id%', $id, $insert['after']));
                }
                
                if (!empty($insert['msg'])) {
                    echo '<p>'. $insert['msg'] .';</p>';
                }
            }
        }
    }

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
    
    public function setComCfgs() {
        if (!empty($this->cfg['com_cfgs'])) {
            $inCore = cmsCore::getInstance();
            foreach ($this->cfg['com_cfgs'] as $com) {
                $com_cfg = $inCore->loadComponentConfig($com['name']);
                if (!empty($com['unset_keys'])) {
                    foreach ($com['unset_keys'] as $key) {
                        if (isset($com_cfg[$key])){ unset($com_cfg[$key]); }
                    }
                }
                if (!empty($com['merge_cfgs'])) {
                    $com_cfg = array_merge($com_cfg, $com['merge_cfgs']);
                }
                $inCore->saveComponentConfig($com['name'], $com_cfg);
            }
        }
    }
    
}
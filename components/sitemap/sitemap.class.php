<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

abstract class cms_rudi_sitemap {
    public $config = array(); // Массив с настройками компонента, обязательно содержит параметры: component - ссылка компонента и regen_time - период регенерации карты

    protected $num = 0; // Количество ссылок записанных в файл
    protected $max_num = 49000; // Максимальное количество ссылок в одном файле
    protected $open_file = null; // Ссылка на открытый файл
    protected $page = 1; // Номер текущего файла
    
    public function __construct() {}

    /**
     * Должен возвращать массив дополнительных настроек компонента
     * @return array array(
     *          ...
     *          array(
     *              'type' => '' // Тип поля (btn_yes_no - кнопка да нет (1,0), text - произвольный текст, number - произвольное целое число, select - выпадающий список (для такого типа должны быть переданы еще и options), checkbox - чекбокс, radio - группа радио кнопок (для такого типа должны быть переданы еще и options))
     *              'title' => '', // Название поля
     *              'description' => '', // Описание поля
     *              'name' => '', // name название опции
     *              'options' => array( // опции для типа select и radio
     *                  'title' => '', // Название
     *                  'value' => '' // Значение
     *              )
     *          )
     *          ...
     * )
     */
    public abstract function getConfig();
    
    // ------------------ Методы для генерации html карты ----------------------

    /**
     * Возвращает массив с идентификаторами и названиями разделов компонента,
     * используется при показе html карты
     * @param integer $target_id ID раздела чьи под разделы нужно вернуть
     * @param string $target Идентификатор раздела чьи под разделы нужно вернуть
     * @return array array(// Массив разделов
     *      ...
     *      array(// данные одного раздела
     *           'target_id' => '0', // ID раздела
     *           'target' => '', // Идентификатор раздела (требуется если в компоненте несколько разделов)
     *           'title' => '', // Название раздела
     *           'link' => '' // Ссылка на раздел
     *      )
     *      ...
     *  )
     */
    public abstract function getSections($target_id=0, $target='');
    
    /**
     * Возвращает данные раздела
     * @param integer $target_id ID раздела
     * @param string $target Идентификатор раздела
     * @return array array( // Данные раздела
     *      'target_id' => '0', // ID раздела
     *      'target' => '', // Идентификатор раздела
     *      'title' => '',// Название раздела
     *      'link' => '',// Ссылка на раздел
     * )
     */
    public abstract function getSection($target_id=0, $target='');

    /**
     * Возвращает массив материалов раздела компонента
     * @param integer $target_id ID раздела
     * @param string $target Идентификатор раздела
     * @return array array(
     *          ...
     *          array(
     *              'id' => '', // ID материала
     *              'title' => '', // Название материала
     *              'link' => '', // Ссылка на материал
     *          )
     *          ...
     * )
     */
    public abstract function getSectionItems($target_id, $target='');
    
    /**
     * Возвращает количество материалов в разделе
     * @param integer $target_id ID раздела
     * @param string $target Идентификатор раздела
     * @return integer Количество материалов в разделе
     */
    public abstract function getSectionItemsCount($target_id=0, $target='');

    //==========================================================================
    
    // ------------------- Методы для генерации xml карты ----------------------

    /**
     * Определяет необходимо ли генерировать карту и если необходимо то удаляет
     * все файлы карт компонента и открывает первый для записи.
     * @return boolean
     */
    public function generateMap() {
        if (file_exists(PATH .'/upload/sitemaps/'. $this->config['component'] .'_1.xml')) {
            clearstatcache();
            
            $edit_time = filemtime(PATH .'/upload/sitemaps/'. $this->config['component'] .'_1.xml');
            $time = (time() - $edit_time)/3600;
            
            if ($time <= $this->config['regen_time']) {
                return false;
            }
            
            $this->deleteAllFiles();
        }
        
        $this->openFile();
    }
    
    /**
     * Удаляет все файлы карты для текущего компонента
     * @param string $match регулярка для выборки нужных файлов, используется если
     * в добавок к стандарной карте создается дополнительная карта, например как
     * карта для google video
     */
    protected function deleteAllFiles($match='') {
        $files = $this->getAllMapFiles();
        
        foreach ($files as $file) {
            unlink(PATH .'/upload/sitemaps/'. $file);
        }
    }
    
    protected function getAllMapFiles($match='') {
        $match = empty($match) ? $this->config['component'] .'_[0-9]+' : $match;
        
        $dir = PATH .'/upload/sitemaps';
        $pdir = opendir($dir);
        
        $files = array();
        
        while ($nextfile = readdir($pdir)){
            $match = array();
            if (($nextfile != '.') && ($nextfile != '..') && !is_dir($dir .'/'. $nextfile) && preg_match('#^'. $match .'$#is', $nextfile)) {
                $files[] = $nextfile;
            }
        }
        
        closedir($pdir);
        
        return $files;
    }

    /**
     * Открывает файл карты, если файл не существует пытается его создать
     */
    protected function openFile() {
        $this->open_file = fopen(PATH .'/upload/sitemaps/'. $this->config['component'] .'_'. $this->page .'.xml', 'w');
        fwrite($this->open_file, '<?xml version="1.0" encoding="UTF-8"?>'. "\n" .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' ."\n");
    }
    
    /**
     * Закрывает файл карты
     */
    protected function closeFile() {
        if (!empty($this->open_file)) {
            fwrite($this->open_file, '</urlset>');
            fclose($this->open_file);
            $this->open_file = null;
        }
    }
    
    /**
     * Создает следующий файл карты
     */
    protected function createNewPage() {
        $this->closeFile();
        $this->page++;
        $this->openFile();
    }
    
    /**
     * Записывает ссылку в файл карты
     * @param array $item
     * @return boolean
     */
    protected function writeMapItem($item) {
        if (empty($item) || empty($item['loc'])) { return false; }
        
        $this->num++;
        
        if ($this->num > $this->max_num*$this->page) {
            $this->createNewPage();
        }
            
        if (empty($item['lastmod']) || !preg_match('#^[0-9]{4}-[0-9]{2}-[0-9]{2}$#is', $item['lastmod']) || $item['lastmod'] == '0000-00-00') {
            $item['lastmod'] = date('Y-m-d');
        }
        
        if (empty($item['changefreq']) || !in_array($item['changefreq'], array('hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'))) {
            $item['changefreq'] = 'monthly';
        }
        
        if (empty($item['priority'])) {
            $item['priority'] = '0.5';
        }
        
        fwrite($this->open_file, '	<url>'. "\n" .'		<loc>'. $item['loc'] .'</loc>'. "\n" .'		<lastmod>'. $item['lastmod'] .'</lastmod>'. "\n" .'		<changefreq>'. $item['changefreq'] .'</changefreq>'. "\n" .'		<priority>'. $item['priority'] .'</priority>'. "\n" .'	</url>' ."\n");
        
        return true;
    }
    
    /**
     * Возвращает массив с названиями файлов карт для текущего компонента
     * @return array
     */
    public function getMapFiles() {
        if ($this->num > 0) {
            $files = array();
            
            $num = $this->num - $this->max_num*($this->page-1);

            if ($num <= 0) {
                unlink(PATH .'/upload/sitemaps/'. $this->config['component'] .'_'. $this->page .'.xml');

                if ($this->page == 1) {
                    return $files;
                }

                $this->page--;
            }

            for ($i=1; $i <= $this->page; $i++) {
                $files[] = $this->config['component'] .'_'. $i .'.xml';
            }
        } else {
            $files = $this->getAllMapFiles();
        }

        return $files;
    }
    //==========================================================================
}
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

class cms_model_sitemap {
    public $config;
    private $components;
    private $objects;


    public function __construct() {
        $this->config = cmsCore::getInstance()->loadComponentConfig('sitemap');
        
        $this->getComponentsWithSupportSitemap();
        
        include_once(PATH .'/components/sitemap/sitemap.class.php');
    }
    
    /**
     * Формирует массив компонентов, у которых есть поддержка генерации карты
     * формирует $this->components
     * @return bool
     */
    private function getComponentsWithSupportSitemap() {
        // если уже получали, возвращаемся
        if ($this->components && is_array($this->components)) { return true; }

        // Получаем список компонентов
        // в каждой директории компонента ищем файл sitemap.php
        $rs = cmsCore::c('db')->query('SELECT link, title FROM cms_components WHERE internal = 0 AND published = 1 ORDER BY title ASC') ;
        if (!cmsCore::c('db')->num_rows($rs)) { return false; }

        while ($component = cmsCore::c('db')->fetch_assoc($rs)) {
            if (!file_exists(PATH .'/components/'. $component['link'] .'/sitemap.php')) {
                continue;
            }
            
            $component['title'] = str_replace('InstantShop', 'Каталог товаров', $component['title']);
            $component['title'] = str_replace('InstantMaps', 'Каталог объектов на карте', $component['title']);
            
            $this->components[$component['link']] = $component;
        }

        return true;
    }
    
    /**
     * Возвращает все поддерживаемые компоненты или только те для которых 
     * включена генерация карты.
     * @param boolean $all Опция определяющая возвращать все поддерживаемые компоненты
     * или только те для которых включена генерация карты
     * @return array
     */
    public function getComponents($all=false) {
        $components = $this->components;
        
        if ($all === false) {
            foreach ($components as $com) {
                if (empty($this->config[$com['link']]['published'])) {
                    unset($components[$com['link']]);
                }
            }
        }
        
        return $components;
    }
    
    /**
     * Возвращает компоненты для которых включена генерация HTML карты
     * @return array
     */
    public function getHtmlMapComponents() {
        $components = $this->getComponents();
        
        foreach ($components as $com) {
            if ($this->config[$com['link']]['mode'] == 'xml_map') {
                unlink($components[$com['link']]);
            }
        }
        
        return $components;
    }
    
    /**
     * Возвращает компоненты для которых включена генерация XML карты
     * @return array
     */
    public function getXmlMapComponents() {
        $components = $this->getComponents();
        
        foreach ($components as $com) {
            if ($this->config[$com['link']]['mode'] == 'html_map') {
                unlink($components[$com['link']]);
            }
        }
        
        return $components;
    }
    
    /**
     * Возвращает данные раздела компонента
     * @param string $component Идентификатор компонента
     * @param integer $target_id ID раздела
     * @param string $target Идентификатор раздела
     * @return boolean|array Данные раздела (false если раздел отсутствует)
     */
    public function getComponentSection($component, $target_id=0, $target='') {
        $obj = $this->getSitemapClass($component);
        
        if (empty($obj)) { return false; }
        
        return $obj->getSection($target_id, $target);
    }
    
    /**
     * Возвращает список разделов компонента
     * @param string $component Идентификатор компонента
     * @param integer $target_id ID раздела
     * @param string $target Идентификатор раздела
     * @param integer $page Номер страницы
     * @return boolean|array Массив данных разделов (false если разделы отсутствуют)
     */
    public function getComponentSections($component, $target_id=0, $target='', $page=1) {
        $obj = $this->getSitemapClass($component);
        
        if (empty($obj)) { return false; }

        if ($this->config['perpage'] > 0) {
            cmsCore::c('db')->limitPage($page, $this->config['perpage']);
        } else {
            cmsCore::c('db')->limit = '';
        }
        
        if ($page == 1) {
            $sections = $obj->getSections($target_id, $target);
        }
        
        $sections_items = $obj->getSectionItems($target_id, $target);
        if (!empty($sections_items)) {
            $sections_items['total'] = $obj->getSectionItemsCount($target_id, $target);
        }
        
        return array_merge(
            is_array($sections) ? $sections : array(),
            is_array($sections_items) ? $sections_items : array()
        );
    }
    
    /**
     * Возвращает объект класса генерации карты компонента
     * @param string $component Идентификатор компонента
     * @return boolean|object
     */
    public function getSitemapClass($component) {
        if (!cmsCore::includeFile('components/'. $component .'/sitemap.php')) {
            return false;
        }

        $class = $component .'_sitemap';

        if (!class_exists($class)) { return false; }
        
        cmsCore::loadLanguage('components/'. $component);

        $this->objects[$component] = new $class();
        
        $cfg = $this->config[$component];
        $cfg['component'] = $component;
        $this->objects[$component]->config = $cfg;
        
        return $this->objects[$component];
    }
    
    /**
     * Генерирует xml карту сайта
     * @return boolean
     */
    public function generateSitemaps() {
        ignore_user_abort(true);
        set_time_limit(0);

        $map_files = array();
        
        $components = $this->getXmlMapComponents();
        
        foreach ($components as $com) {
            $obj = $this->getSitemapClass($com['link']);
            
            if (empty($obj)) { continue; }

            $obj->generateMap();
            
            $files = $obj->getMapFiles();
            
            $map_files = array_merge($map_files, $files);
        }
        
        if (!empty($map_files)) {
            $date = date('Y-m-d');
            $sitemapindex = fopen(PATH .'/sitemap.xml', 'w');
            fwrite($sitemapindex, '<?xml version="1.0" encoding="UTF-8"?>'. "\n" .'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'. "\n");
            
            foreach ($map_files as $file) {
                fwrite($sitemapindex, '	<sitemap>'. "\n" .'		<loc>'. cmsCore::c('config')->host .'/upload/sitemaps/'. $file .'</loc>'. "\n" .'		<lastmod>'. $date .'</lastmod>'. "\n" .'	</sitemap>'. "\n");
            }
            
            fwrite($sitemapindex, '</sitemapindex>');
            fclose($sitemapindex);
        }
        
        return true;
    }
}
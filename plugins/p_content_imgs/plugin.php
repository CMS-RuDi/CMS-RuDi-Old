<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.8                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_content_imgs extends cmsPlugin {
    public function __construct() {
        parent::__construct();
        
        $this->info = array(
            'plugin'      => 'p_content_imgs',
            'title'       => 'Прикрепленные к статьям фотографии',
            'description' => 'Плагин добавляет в конце статьи карусель (слайдер) с прикрепленными фотографиями.',
            'author'      => 'DS Soft',
            'version'     => '0.0.3'
        );
        
        $this->config = array(
            'slider' => 'jCarousel__1',
        );
        
        $this->events = array(
            'GET_ARTICLE',
            'GET_SLIDER_OPTS'
        );
    }
    
    public function getConfigFields() {
        global $_LANG;
        
        return array(
            array(
                'type' => 'select',
                'title' => $_LANG['PCI_SLIDER'],
                'name' => 'slider',
                'options' => array(
                    'title' => $_LANG['PCI_JCAROUSEL_1'], 'value' => 'jCarousel__1',
                    'title' => $_LANG['PCI_JCAROUSEL_2'], 'value' => 'jCarousel__2',
                    'title' => $_LANG['PCI_JCAROUSEL_3'], 'value' => 'jCarousel__3',
                )
            )
        );
    }

    public function execute($event='', $item=array()) {
        parent::execute();
        
        if (empty($this->config['PCI_SLIDER'])) {
            $this->config['slider'] = 'jCarousel__1';
        }

        $item = $this->eventGetArticle($item);
        
        return $item;
    }
    
    private function eventGetArticle($item) {
        if (!empty($item['images']) && $item['slidecfg'] != '-1') {
            if (!empty($item['slidecfg'])) {
                list($this->config['slider_name'], $this->config['slider_mode']) = explode('__', $item['slidecfg']);
            } else {
                list($this->config['slider_name'], $this->config['slider_mode']) = explode('__', $this->config['slider']);
            }
            $item['content'] = $item['content'] .' '. $this->insertSlider($item['images'], $item['title']);
        }
        
        return $item;
    }
    
    private function insertSlider($images, $title) {
        return cmsPage::initTemplate('plugins', 'p_content_imgs_'. $this->config['slider_name'])->
            assign('images', $images)->
            assign('title', $title)->
            assign('slider_mode', $this->config['slider_mode'])->
            fetch();
    }
}
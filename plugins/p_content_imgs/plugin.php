<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.2                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2013 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_content_imgs extends cmsPlugin {

// ==================================================================== //

    public function __construct(){

        parent::__construct();

        // Информация о плагине

        $this->info['plugin']           = 'p_content_imgs';
        $this->info['title']            = 'Прикрепленные к статьям фотографии';
        $this->info['description']      = 'Плагин добавляет в конце статьи карусель (слайдер) с прикрепленными фотографиями. Вставляет в текст статьи фотографии в тех местах где прописана конструкция вида {img#123}';
        $this->info['author']           = 'DS Soft';
        $this->info['version']          = '0.0.1';

        // Настройки по-умолчанию

        $this->config['PCI_SLIDER']         = 'jCarousel';
        $this->config['PCI_SLIDER_OPT']     = '1';
        $this->config['PCI_INSERT_IMAGES']  = '1';
        $this->config['PCI_DELETE_ERRORS']  = '1';

        // События, которые будут отлавливаться плагином

        $this->events[]                 = 'GET_ARTICLE';

    }

// ==================================================================== //

    /**
     * Процедура установки плагина
     * @return bool
     */
    public function install(){
        return parent::install();
    }

// ==================================================================== //

    /**
     * Процедура обновления плагина
     * @return bool
     */
    public function upgrade(){
        return parent::upgrade();
    }

// ==================================================================== //

    /**
     * Обработка событий
     * @param string $event
     * @param mixed $item
     * @return mixed
     */
    public function execute($event='', $item=array()){
        parent::execute();
        if (empty($this->config['PCI_SLIDER'])){
            $this->config['PCI_SLIDER'] = 'jCarousel';
        }

        switch ($event){
            case 'GET_ARTICLE': $item = $this->eventGetArticle($item); break;
        }

        return $item;
    }
    
    private function eventGetArticle($item){
        $match = array(); $images = array();
        
        if ($this->config['PCI_INSERT_IMAGES'] == 1){
            $item['description'] = $this->insertImages($item['images'], $item['description'], $item['title']);
            $item['content'] = $this->insertImages($item['images'], $item['content'], $item['title']);
        }
        
        $item['content'] = $item['content'] .' '. $this->insertSlider($item['images'], $item['title']);

        return $item;
    }
    
    private function insertSlider($images, $title){
        if (!file_exists(PATH .'/templates/_default_/plugins/p_content_imgs_'. $this->config['PCI_SLIDER'] .'.tpl')){
            $this->config['PCI_SLIDER'] = 'jCarousel';
        }
        
        ob_start();
            cmsPage::initTemplate('plugins', 'p_content_imgs_'. $this->config['PCI_SLIDER'] .'.tpl')->
                assign('images', $images)->
                assign('title', $title)->
                assign('slide_opt', $this->config['PCI_SLIDER_OPT'])->
                display('p_content_imgs_'. $this->config['PCI_SLIDER'] .'.tpl');
        return ob_get_clean();
    }
    
    private function insertImages($images, $text, $title){
        preg_match_all('#\{img\#([0-9]+)\}#is', $text, $match);
        foreach ($match[1] as $k=>$v){
            if (!empty($images[$v])){
                $text = preg_replace('#\{img\#'. $v .'\}#is', '<div style="text-align:center"><img src="'. $images[$v]['medium_src'] .'" alt="'. (empty($images[$v]['title']) ? $title : $images[$v]['title']) .'" /></div>', $text, 1);
            }else if ($this->config['PCI_DELETE_ERRORS'] == 1){
                $text = preg_replace('#\{img\#'. $v .'\}#is', '', $text, 1);
            }
        }

        preg_match_all('#\{img-(small|medium|big)\#([0-9]+)\}#is', $text, $match);
        foreach ($match[2] as $k=>$v){
            if (!empty($images[$v])){
                $text = preg_replace('#\{img-'. $match[1][$k] .'\#'. $v .'\}#is', '<div style="text-align:center"><img class="photo_thumb_img" src="'. $images[$v][$match[1][$k].'_src'] .'" alt="'. (empty($images[$v]['title']) ? $title : $images[$v]['title']) .'" /></div>', $text, 1);
            }else if ($this->config['PCI_DELETE_ERRORS'] == 1){
                $text = preg_replace('#\{img\#'. $v .'\}#is', '', $text, 1);
            }
        }
        
        return $text;
    }
}

?>
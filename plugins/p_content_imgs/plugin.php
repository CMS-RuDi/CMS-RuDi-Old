<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.3                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2013 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_content_imgs extends cmsPlugin {

    public function __construct(){

        parent::__construct();

        $this->info['plugin']           = 'p_content_imgs';
        $this->info['title']            = 'Прикрепленные к статьям фотографии';
        $this->info['description']      = 'Плагин добавляет в конце статьи карусель (слайдер) с прикрепленными фотографиями.';
        $this->info['author']           = 'DS Soft';
        $this->info['version']          = '0.0.1';

        $this->config['PCI_SLIDER']        = 'jCarousel';
        $this->config['PCI_SLIDER_OPT']    = '1';
        $this->config['PCI_INSERT_IMAGES'] = '1';
        $this->config['PCI_DELETE_ERRORS'] = '1';

        $this->events[]                 = 'GET_ARTICLE';

    }

    public function install(){
        return parent::install();
    }

    public function upgrade(){
        return parent::upgrade();
    }

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
        
        if (!empty($item['images'])){
            $item['content'] = $item['content'] .' '. $this->insertSlider($item['images'], $item['title']);
        }
        
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

}

?>
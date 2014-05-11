<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.3                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2013 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_insert_video extends cmsPlugin {

    public function __construct(){

        parent::__construct();

        $this->info['plugin']           = 'p_insert_video';
        $this->info['title']            = 'Прикрепленные к статьям Видео материалов';
        $this->info['description']      = 'На страницу добавления редактирования статьи плагин встраивает возможность прикреплять видео материалы к статье. После прикрепления видео в текст статью нужно прописать команду {video#100} где 100 это id прикрепленного видео, при просмотре статьи эта команда будет заменена на сам код видео плеера.';
        $this->info['author']           = 'DS Soft';
        $this->info['version']          = '0.0.1';

        $this->config['PIV_DOMENS']     = 'yotube.com,vk.com,vkontakte.ru,rutube.ru';

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
<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class p_insert_slider extends cmsPlugin
{
    public $info = array(
        'plugin'      => 'p_insert_slider',
        'title'       => 'Слайдер фотографий',
        'description' => 'Выводит слайдер с фотографиями',
        'author'      => 'DS Soft',
        'version'     => '0.0.1'
    );
    
    public $config = array(
        'slider_tpl' => 'p_insert_slider',
    );
    
    public $events = array(
        'INSERT_SLIDER'
    );
    
    private $ajax = false;
    private $tpl;
    private $target_id = 0;
    private $component = '';
    private $target = '';
    private $images = false;
    
    public function getConfigFields()
    {
        global $_LANG;
        
        $tpls = cmsCore::getDirFilesList('/templates/'. cmsCore::c('config')->template .'/plugins/p_insert_slider');
        
        if (cmsCore::c('config')->template != '_default_') {
            $tpls_2 = cmsCore::getDirFilesList('/templates/_default_/plugins/p_insert_slider');

            foreach ($tpls_2 as $v) {
                if (!in_array($v, $tpls)) {
                    $tpls[] = $v;
                }
            }
        }
        
        asort($tpls);
        
        $cfg = array(
            'type'  => 'select',
            'title' => $_LANG['PIS_SLIDER_TPL'],
            'name'  => 'slider_tpl',
            'options' => array()
        );
        
        foreach ($tpls as $v) {
            $cfg['options'][] = array(
                'title' => $v,
                'value' => $v
            );
        }

        return array(
            $cfg
        );
    }

    public function execute($event = '', $item = array())
    {
        if (empty($this->config['slider_tpl'])) {
            $this->config['slider_tpl'] = 'p_insert_slider';
        }

        if ($event == 'INSERT_SLIDER') {
            $this->checkSettings($item);
            
            if ($this->ajax) {
                $this->insertAjax();
            }
            else
            {
                $this->insertSlider();
            }
        }
        
        return $item;
    }
    
    private function checkSettings($cfg = array())
    {
        $this->tpl = pathinfo(!empty($cfg['tpl']) ? $cfg['tpl'] : $this->config['slider_tpl'], PATHINFO_FILENAME);
        
        $this->target_id = cmsCore::getArrVal($cfg, 'target_id', 0);
        $this->component = cmsCore::getArrVal($cfg, 'component', '');
        $this->target    = cmsCore::getArrVal($cfg, 'target', '');
        
        if ($cfg['ajax']) {
            $this->ajax = true;
            return;
        }
        
        if (isset($cfg['images']))
        {
            $this->images = $cfg['images'];
            return;
        }
        
        if (!empty($this->target_id) && !empty($this->component))
        {
            $this->images = cmsCore::getUploadImages($this->target_id, $this->target, $this->component);
        }
    }
    
    private function insertAjax()
    {
        if (!empty($this->target_id) && !empty($this->component)) {
            echo '<div id="photo_slider_'. $this->component .'_'. $this->target .'_'. $this->target_id .'"></div><script type="text/javascript">$(function(){$("#photo_slider_'. $this->component .'_'. $this->target .'_'. $this->target_id .'").load("/plugins/p_insert_slider/ajax.php", {"tpl":"'. $this->tpl .'", target":"'. $this->target .'", "component":"'. $this->component .'", "target_id":"'. $this->target_id .'"});});</script>';
        }
    }

    private function insertSlider()
    {
        cmsPage::initTemplate('plugins/p_insert_slider/'. $this->tpl)->
            assign('images', $this->images)->
            display();
    }
}
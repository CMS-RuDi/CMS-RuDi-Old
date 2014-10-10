<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.4                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

class p_morecontent extends cmsPlugin {
    public function __construct(){
        parent::__construct();
        
        // Информация о плагине
        $this->info = array(
            'plugin' => 'p_morecontent',
            'title' => 'Похожие статьи',
            'description' => 'Добавляет в конец каждой статьи список похожих статей.',
            'author' => 'Maximov & InstantCMS Team',
            'version' => '1.10.3'
        );
        
        // Настройки по-умолчанию
        $this->config = array(
            'P_LIMIT'  => 5,
            'P_UNSORT' => 1
        );
        
        // События, которые будут отлавливаться плагином
        $this->events = array( 'GET_ARTICLE' );
    }
    
    public function getConfigFields() {
        global $_LANG;
        return array(
            array(
                'type' => 'number',
                'title' => $_LANG['P_LIMIT'],
                'name' => 'P_LIMIT'
            ),
            array(
                'type' => 'btn_yes_no',
                'title' => $_LANG['P_UNSORT'],
                'name' => 'P_UNSORT'
            )
        );
    }

    /**
     * Обработка событий
     * @param string $event
     * @param mixed $item
     * @return html
     */
    public function execute($event='', $item=array()) {
        parent::execute();
        switch ($event) {
            case 'GET_ARTICLE': $item = $this->eventGetArticle($item); break;
        }
        return $item;
    }

    private function eventGetArticle($item) {
        global $_LANG;

        $item_id = $item['id'];
        $tag_arr = explode(', ', cmsTagLine('content', $item_id, false));
        $id_target = array();

        // Получаем id назначения таких же тегов, не более пяти на каждый
        foreach ($tag_arr as $tag) {
            $sql = "SELECT item_id FROM cms_tags WHERE tag = '$tag' AND item_id<>'$item_id' AND target='content' LIMIT 5";
            $rs = cmsCore::c('db')->query($sql) ;
            if (cmsCore::c('db')->num_rows($rs)) {
                while ($tagitem = cmsCore::c('db')->fetch_assoc($rs)) {
                    $id_target[]= $tagitem['item_id'];
                }
            }
        }

        if (count($id_target)) {
            $id_target	= array_unique($id_target);
            $id_target 	= array_slice($id_target, 0, $this->config['P_LIMIT']);
            if ($this->config['P_UNSORT']) { shuffle($id_target); }

            $morecontent = '';
            foreach ($id_target as $n) {
                $con = cmsTagItemLink('content', $n);
                if ($con) {
                    $morecontent .= '<p>'. $con ."</p>";
                }
            }
            if ($morecontent) { $item['content'] .= '<h4>'. $_LANG['P_SIMILAR_ARTICLES'] .':</h4>'. $morecontent; }
        }
        
        return $item;
    }
}
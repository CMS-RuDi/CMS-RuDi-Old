<?php
/******************************************************************************/
//                           InstantCMS v1.10.5                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
/******************************************************************************/

class p_new_msg extends cmsPlugin {
    public $info = array(
        'plugin'      => 'p_new_msg',
        'title'       => 'Анимация при новом сообщении',
        'description' => 'Анимация при новом сообщении',
        'author'      => 'InstantCMS Team',
        'version'     => '1.0'
    );
    
    public $events = array(
        'PRINT_PAGE_HEAD'
    );

    public function execute($event='', $item=array()) {
        switch ($event) {
            case 'PRINT_PAGE_HEAD': return $this->animateNewMsg($item);
        }
        
        return $item;
    }

    private function animateNewMsg($page_head) {
        if (!cmsCore::c('user')->id || !cmsCore::c('user')->new_msg_count) {
            return $page_head;
        }
        
        ob_start(); ?>
            <script type="text/javascript">
                $(function() {
                    function an () {
                        $('.my_messages a').fadeOut().addClass('has_new').fadeIn();
                        setTimeout(an, 3000);
                    }
                    an();
                });
            </script>
        <?php
        
        $page_head[] = ob_get_clean();
        
        return $page_head;
    }
}

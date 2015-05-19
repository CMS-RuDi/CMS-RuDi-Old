<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.5                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

class p_demo_route extends cmsPlugin {
    public $info = array(
        'plugin'      => 'p_demo_route',
        'title'       => 'Demo Plugin',
        'description' => 'Пример плагина - для роутера /users/get_demo.html',
        'author'      => 'InstantCMS Team',
        'version'     => '1.10'
    );
    
    public $events = array(
        'GET_ROUTE_USERS',
        'GET_USERS_ACTION_GET_DEMO'
    );

    /**
     * Процедура установки плагина
     * @return bool
     */
    public function install(){
        return parent::install();
    }

    /**
     * Процедура обновления плагина
     * @return bool
     */
    public function upgrade(){
        return parent::upgrade();
    }
    
    /**
     * Процедура обновления плагина
     * @return bool
     */
    public function uninstall(){
        return parent::uninstall();
    }

    /**
     * Обработка событий
     * @param string $event
     * @param mixed $data
     * @return mixed
     */
    public function execute($event='', $data=array()) {
        switch ($event) {
            case 'GET_ROUTE_USERS': $data = $this->eventGetRoutes($data); break;
            case 'GET_USERS_ACTION_GET_DEMO': $data = $this->eventGetAction(); break;
        }

        return $data;
    }

    private function eventGetRoutes($routes) {
        // формируем массив по аналогии с router.php
        $add_routes[] = array(
            '_uri' => '/^users\/get_demo.html$/i',
            'do'   => 'get_demo'
        );

        // перебираем массив $add_routes, занося каждый в начало входного массива $routes
        foreach($add_routes as $route){
            array_unshift($routes, $route);
        }

        return $routes;
    }

    private function eventGetAction() {
        echo 'DEMO PLUGIN TEXT';
        return true;
    }
}
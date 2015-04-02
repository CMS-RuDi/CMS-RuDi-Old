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

class cmsPage {
    public $title      = '';

    public $page_head  = array();
    public $page_js    = array();
    public $page_css   = array();
    public $page_meta  = array();
    public $page_keys  = '';
    public $page_desc  = '';
    public $page_img   = '';
    public $page_body  = '';

    private $js  = array();
    private $css = array();
    private $page_lang = array();
    private $pathway   = array();
    private $is_ajax   = false;

    private $modules;
    private $tpl_info = array(
        '_default_' => array( 'author' => 'InstantCMS Team', 'renderer' => 'phpTpl', 'ext' => 'php' )
    );
    private $default_tpl_info = array( 'author' => 'InstantCMS Team', 'renderer' => 'smartyTpl', 'ext' => 'tpl' );

    private static $instance;

    private function __construct() {
        $this->site_cfg  = cmsCore::c('config');
        
        $this->title     = $this->homeTitle();
        $this->page_keys = $this->site_cfg->keywords;
        $this->page_desc = $this->site_cfg->metadesc;
        
        $this->getTplInfo();
        
        $this->combineJsCssPrepare();
        
        global $_LANG;
        $this->addPathway($_LANG['PATH_HOME'], '/');
    }

    private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    /**
     * Формирует информацию о текущем шаблоне
     * для этого ищет в корне шаблона файл system.php
     * а в нем определенный массив с параметрами шаблона
     */
    public function getTplInfo($tpl=false) {
        $tpl = empty($tpl) ? cmsCore::c('config')->template : $tpl;
        
        if (!isset($this->tpl_info[$tpl])) {
            $info_file = PATH .'/templates/'. $tpl .'/system.php';
        
            if (file_exists($info_file)) {
                include $info_file;
            }
            
            if (empty($info)) {
                $info = $this->default_tpl_info;
            }

            $this->tpl_info[$tpl] = $info;
        }
        
        return $this->tpl_info[$tpl];
    }
    
    private function combineJsCssPrepare() {
        if (!defined('VALID_CMS_ADMIN')) {
            if ($this->site_cfg->combine_js_enable) {
                $this->js = explode("\n", $this->site_cfg->combine_js);
                foreach ($this->js as $k => $src) {
                    $src = $this->checkLink(trim($src));
                    if (mb_substr($src, 0, 1) != '/') {
                        unset($this->js[$k]);
                    } else if (!file_exists(PATH . $src)) {
                        unset($this->js[$k]);
                    } else {
                        $this->js[$k] = $src;
                    }
                }
            }

            if ($this->site_cfg->combine_css_enable) {
                $this->css = explode("\n", $this->site_cfg->combine_css);
                foreach ($this->css as $k => $src) {
                    $src = $this->checkLink(trim($src));
                    if (mb_substr($src, 0, 1) != '/') {
                        unset($this->css[$k]);
                    } else if (!file_exists(PATH . $src)) {
                        unset($this->css[$k]);
                    } else {
                        $this->css[$k] = $src;
                    }
                }
            }
        }
    }

    /**
     * Возвращает информацию о шаблоне
     * @return array
     */
    public function getCurrentTplInfo() {
        return $this->tpl_info[cmsCore::c('config')->template];
    }

    /**
     * Производит инициализацию класса шаблонизатора
     * @return obj $tpl_info['renderer']
     */
    public static function initTemplate($tpl_folder, $tpl_file, $data=null) {
        $thisObj = self::getInstance();

        // чтобы не перезаписать
        $tpl_info = $thisObj->tpl_info[cmsCore::c('config')->template];

        // имя файла без расширения (для совместимости)
        $file_name = pathinfo($tpl_file, PATHINFO_FILENAME);
        
        // есть ли файл в текущем шаблоне
        $is_exists_tpl_file = file_exists(cmsCore::c('config')->template_dir . $tpl_folder .'/'. $file_name .'.'. $tpl_info['ext']);

        // если нет, считаем что файл лежит в дефолтном, используем оригинальное имя с расширением
        // если есть формируем полное имя файла с учетом параметров шаблона
        if (!$is_exists_tpl_file) {
            $tpl_info = $thisObj->tpl_info['_default_'];
            $tpl_folder = cmsCore::c('config')->default_template_dir . $tpl_folder;
        } else {
            $tpl_folder = cmsCore::c('config')->template_dir . $tpl_folder;
        }
        $tpl_file = $file_name .'.'. $tpl_info['ext'];

        // загружаем шаблонизатор текущего шаблона
        if (!cmsCore::includeFile('core/tpl_classes/'. $tpl_info['renderer'] .'.php') ||
            !class_exists($tpl_info['renderer'])
        ) {
            global $_LANG;
            cmsCore::halt(sprintf($_LANG['TEMPLATE_CLASS_NOTFOUND'], $tpl_info['renderer']));
        }
        
        $render_class = new $tpl_info['renderer']($tpl_folder, $tpl_file);
        
        if (!empty($data)) {
            if (is_array($data)) {
                foreach ($data as $k=>$v) {
                    $render_class->assign($k, $v);
                }
            } else {
                $render_class->assign('data', $data);
            }
        }
        
        return $render_class;
    }
    
    /**
     * Выставляет значение указывающее что запрос пришел по ajax
     * @return \cmsPage
     */
    public function setRequestIsAjax() {
        $this->is_ajax = true;
        return $this;
    }

    /**
     * Добавляет указанный тег в <head> страницы
     * @param string $tag
     * @return $this
     */
    public function addHead($tag) {
        if (!in_array($tag, $this->page_head)) {
            if ($this->is_ajax) { echo $tag; } else { $this->page_head[] = $tag; }
        }
        return $this;
    }
    
    /**
     * Проверяет относительная или абсолютная ссылка, если относительная добавляет
     * впереди слеш
     * @param string $src
     * @return string
     */
    private function checkLink($src) {
        if (
            mb_substr($src, 0, 7) != 'http://' &&
            mb_substr($src, 0, 8) != 'https://' &&
            mb_substr($src, 0, 2) != '//'
        ) {
            $src = '/'. $src;
        } else if (mb_substr($src, 0, 2) == '//') {
            $src = 'http:'. $src;
        }
        return $src;
    }
    
    /**
     * Добавляет тег <script> с указанным путем
     * @param string $src - Первый слеш не требуется
     * @param boolean $prepend  - Добавлять или нет элемент в начало массива
     * @return $this
     */
    public function addHeadJS($src, $prepend=false) {
        $src = $this->checkLink($src);
        
        if (in_array($src, $this->js)) {
            return $this;
        }

        if (!in_array($src, $this->page_js)) {
            if ($prepend) {
                array_unshift($this->page_js, $src);
            } else {
                $this->page_js[] = $src;
            }

            if ($this->is_ajax) { echo '<script type="text/javascript" src="'. $src .'"></script>'; }
        }

        return $this;
    }
    
    public function prependHeadJS($src) {
        return $this->addHeadJS($src, true);
    } 
    
    /**
     * Добавляет тег <link> с указанным путем к CSS-файлу
     * @param string $src - Первый слеш не требуется
     * @return $this
     */
    public function addHeadCSS($src, $prepend=false) {
        $src = $this->checkLink($src);
        
        if (in_array($src, $this->css)) {
            return $this;
        }

        if (!in_array($src, $this->page_css)) {
            if ($prepend) {
                array_unshift($this->page_css, $src);
            } else {
                $this->page_css[] = $src;
            }

            if ($this->is_ajax) { echo '<link href="'. $src .'" rel="stylesheet" type="text/css" />'; }
        }

        return $this;
    }
    
    /**
     * Добавляет тег <meta> с указанными данными
     * @param string $property
     * @param string $content
     * @param string $name
     * @return \cmsPage
     */
    public function addHeadMeta($property='', $content='', $name='', $html=false) {
        if (!empty($property) or !empty($name)) {
            $content = $html ? '<![CDATA[ '. $content .' ]]>' : str_replace('"',' ',$content);

            $meta = '<meta '. (!empty($property) ? 'property="'. $property .'"' : 'name="'. $name .'"') .' content="'. $content .'"/>';

            if (!in_array($meta, $this->page_meta)) {
                $this->page_meta[] = $meta;
            }
        }

        return $this;
    }
    
    /**
     * Добавляет теги <meta> с указанными данными переданными в массиве
     * @param array $metas
     * @return \cmsPage
     */
    public function addHeadMetas($metas) {
        foreach ($metas as $meta) {
            $this->addHeadMeta($meta['property'], $meta['content'], $meta['name'], $meta['html']);
        }

        return $this;
    }
    
    /**
     * Возвращает заголовок главной страницы
     * @return string
     */
    public function homeTitle() {
        return !empty($this->site_cfg->hometitle) ? $this->site_cfg->hometitle : $this->site_cfg->sitename;
    }
    
    /**
     * Устанавливает заголовок страницы в админке
     * @param string
     * @return $this
     */
    public function setAdminTitle($title='') {
        if (defined('VALID_CMS_ADMIN')) {
            global $_LANG;
            
            $title = strip_tags($title);
            
            $this->title = $_LANG['AD_ADMIN_PANEL'] .' v '. CMS_RUDI_V;
            
            if (!empty($title)) {
                $this->title = $title .' - '. $this->title ;
            }
        }
        return $this;
    }
    
    /**
     * Устанавливает заголовок страницы
     * @param string
     * @return $this
     */
    public function setTitle($title) {
        if (cmsCore::getInstance()->menuId() == 1 || empty($title)) {
            return $this;
        }

        $this->title = strip_tags($title) . ($this->site_cfg->title_and_sitename ? ' — '. $this->site_cfg->sitename : '');

        return $this;
    }
    
    /**
     * Устанавливает ключевые слова страницы
     * @param string
     * @return $this
     */
    public function setKeywords($keywords) {
        if (cmsCore::getInstance()->menuId() == 1 || empty($keywords)) {
            return $this;
        }
        
        $this->page_keys = trim(strip_tags($keywords));
        
        return $this;
    }
    
    /**
     * Устанавливает описание страницы
     * @param string
     * @return $this
     */
    public function setDescription($text) {
        if (cmsCore::getInstance()->menuId() == 1 || empty($text)) {
            return $this;
        }
        
        $this->page_desc = trim(strip_tags($text));
        
        return $this;
    }
    
    /**
     * Печатает название сайта из конфига
     * @return true
     */
    public static function printSitename() {
        echo cmsConfig::getConfig('sitename');
    }
    
    /**
     * Печатает головную область страницы в админке
     */
    public function printAdminHead() {
        if (defined('VALID_CMS_ADMIN')) {
            self::displayLangJS(array('AD_NO_SELECT_OBJECTS','AD_SWITCH_EDITOR','CANCEL','CONTINUE','CLOSE','ATTENTION'));
            
            // Заголовок страницы
            echo '<title>', htmlspecialchars($this->title), '</title>',"\n";

            // Ключевые слова
            echo '<meta name="keywords" content="', htmlspecialchars($this->site_cfg->keywords), '" />',"\n";

            // Описание
            echo '<meta name="description" content="',htmlspecialchars($this->site_cfg->metadesc),'" />',"\n";

            // CSS
            $this->page_css = cmsCore::callEvent('PRINT_ADMIN_PAGE_CSS', $this->page_css);
            foreach ($this->page_css as $value) {
                echo '<link href="'. $value .'" rel="stylesheet" type="text/css" />',"\n";
            }

            // JS
            $this->page_js = cmsCore::callEvent('PRINT_ADMIN_PAGE_JS', $this->page_js);
            foreach ($this->page_js as $value) {
                echo '<script type="text/javascript" src="'. $value .'"></script>',"\n";
            }

            // Оставшиеся теги
            $this->page_head = cmsCore::callEvent('PRINT_ADMIN_PAGE_HEAD', $this->page_head);
            foreach($this->page_head as $value) { echo $value,"\n"; }
        }
    }
    
    /**
     * Печатает головную область страницы
     * @param boolean|string $full_print - определяет слудует ли выводить js и css теги. Возможные значение: 'true' - выводятся оба, 'css' - выводятся css теги js нет, 'js' - выводятся js теги css нет)
     * @param integer $indent $name - величина отступа (в пробелах) тегов от левого края введен чисто для декоративных целей
     */
    public function printHead($full_print=true, $indent=4) {
        $indent_str = str_repeat(' ', $indent);
        
        $this->addHeadJsLang(array('SEND','CONTINUE','CLOSE','SAVE','CANCEL','ATTENTION','CONFIRM','LOADING','ERROR', 'ADD','SELECT_CITY','SELECT'));

        // Если есть пагинация и страница больше первой, добавляем "страница №"
        if ($this->site_cfg->title_and_page) {
            $page = cmsCore::request('page', 'int', 1);
            if ($page > 1) {
                global $_LANG;
                $this->title = $this->title .' — '. $_LANG['PAGE'] .' №'. $page;
            }
        }

        // Заголовок страницы
        echo '<title>', htmlspecialchars($this->title), '</title>',"\n";

        //Ключевые слова
        echo $indent_str,'<meta name="keywords" content="', htmlspecialchars($this->page_keys), '" />',"\n";

        //Описание
        echo $indent_str,'<meta name="description" content="',htmlspecialchars($this->page_desc),'" />',"\n";
        
        // Изображение
        if ($this->page_img) {
            echo $indent_str,'<link rel="image_src" href="',htmlspecialchars($this->page_img),'" />',"\n";
        }

        //CSS
        if ($full_print === true || $full_print === 'css') {
            $this->printHeadCSS($indent);
        }

        //Meta
        $this->page_meta = cmsCore::callEvent('PRINT_PAGE_META', $this->page_meta);
        foreach ($this->page_meta as $value){ echo $indent_str,$value,"\n"; }

        //JS
        if ($full_print === true || $full_print === 'js') {
            $this->printHeadJS($indent);
        }

        //Оставшиеся теги
        $this->page_head = cmsCore::callEvent('PRINT_PAGE_HEAD', $this->page_head);
        foreach($this->page_head as $value) { echo $indent_str,$value,"\n"; }

        // LANG переменные
        echo $indent_str,'<script type="text/javascript">'; foreach($this->page_lang as $value) { echo $value; } echo '</script>',"\n";
    }
    
    /**
     * Печатает теги <script>
     * @param integer $indent $name - величина отступа (в пробелах) тегов от левого края введен чисто для декоративных целей
     */
    public function printHeadJS($indent=4) {
        $indent_str = str_repeat(' ', $indent);
        
        $this->page_js = cmsCore::callEvent('PRINT_PAGE_JS', $this->page_js);
        
        if (!empty($this->js)) {
            $key = md5(implode('.', $this->js));
            
            $cachetime = cmsCore::c('cache')->get('class_page', $key, 'js', 24*3600);
            
            $update = false;
            
            if (empty($cachetime) || !file_exists(PATH .'/upload/'. $key .'.js')) {
                $update = true;
            }
            
            if ($update === false && (time()-$cachetime['time']) > 3600) {
                $filetimes = array();
                foreach ($this->js as $k => $src) {
                    $filetimes[md5($src)] = filemtime(PATH . $src);
                    if ($filetimes[md5($src)] != cmsCore::getArrVal($cachetime, md5($src))) {
                        $update = true;
                    }
                }
                $filetimes['time'] = time();
                cmsCore::c('cache')->set($filetimes, 'class_page', $key, 'js');
            }

            if ($update === true) {
                $data = '';
                foreach ($this->js as $src) {
                    $data .= "\n\n". '/* '. $src .' */'. "\n" . file_get_contents(PATH . $src);
                }

                file_put_contents(PATH .'/upload/'. $key .'.js', trim($data));

                unset($data);
            }
            
            $this->addHeadJS('upload/'. $key .'.js', true);
        }
        
        foreach ($this->page_js as $value) {
            echo $indent_str,'<script type="text/javascript" src="'. $value .'"></script>',"\n";
        }
    }
    
    /**
     * Печатает теги <style>
     * @param integer $indent $name - величина отступа (в пробелах) тегов от левого края введен чисто для декоративных целей
     */
    public function printHeadCSS($indent=4) {
        $indent_str = str_repeat(' ', $indent);
        
        $this->page_css = cmsCore::callEvent('PRINT_PAGE_CSS', $this->page_css);
        
        if (!empty($this->css)) {
            $key = md5(implode('.', $this->css));
            
            $cachetime = cmsCore::c('cache')->get('class_page', $key, 'css', 24*3600);
            
            $update = false;
            
            if (empty($cachetime) || !file_exists(PATH .'/upload/'. $key .'.css')) {
                $update = true;
            }
            
            if ($update === false && (time()-$cachetime['time']) > 3600) {
                $filetimes = array();
                foreach ($this->css as $k => $src) {
                    $filetimes[md5($src)] = filemtime(PATH . $src);
                    if ($filetimes[md5($src)] != cmsCore::getArrVal($cachetime, md5($src))) {
                        $update = true;
                    }
                }
                $filetimes['time'] = time();
                cmsCore::c('cache')->set($filetimes, 'class_page', $key, 'css');
            }

            if ($update === true) {
                $data = '';
                foreach ($this->css as $src) {
                    $data .= "\n\n". '/* '. $src .' */'. "\n" . preg_replace('#[\s]+#is', ' ', file_get_contents(PATH . $src));
                }

                file_put_contents(PATH .'/upload/'. $key .'.css', trim($data));

                unset($data);
            }
            
            $this->addHeadJS('upload/'. $key .'.css', true);
        }
        
        foreach ($this->page_css as $value){
            echo $indent_str,'<link href="'. $value .'" rel="stylesheet" type="text/css" />',"\n";
        }
    }
    
    /**
     * Выводит тело страницы (результат работы компонента)
     */
    public function printBody(){
        if (cmsConfig::getConfig('slight')){
            $searchquery = cmsUser::sessionGet('searchquery');
            if ($searchquery && cmsCore::getInstance()->component != 'search'){
                $this->page_body = preg_replace('/('.preg_quote($searchquery).')/iu',
                    '<strong class="search_match">$1</strong>',
                    $this->page_body
                );
                cmsUser::sessionDel('searchquery');
            }
        }

        $this->page_body = cmsCore::callEvent('PRINT_PAGE_BODY', $this->page_body);

        echo $this->page_body;
    }
    
    /**
     * Печатает глубиномер
     * @param string $separator
     */
    public function printPathway($separator='&rarr;') {
        $inCore = cmsCore::getInstance();

        //Проверяем, на главной мы или нет
        if (($inCore->menuId()==1 && !$this->site_cfg->index_pw) || !$this->site_cfg->show_pw) { return false; }

        $count = count($this->pathway);

        if (!$this->site_cfg->last_item_pw){
            unset($this->pathway[$count-1]); $count --;
        } elseif ($this->site_cfg->last_item_pw == 2) {
            $this->pathway[$count-1]['is_last'] = true;
        }

        if ($this->pathway){
            $this->initTemplate('special', 'pathway')->
                assign('pathway', $this->pathway)->
                assign('separator', $separator)->
                assign('count', $count)->
                display();
        }
    }
    
    /**
     * Добавляет звено к глубиномеру
     * @param string $title
     * @param string $link
     * @return $this
     */
    public function addPathway($title, $link=''){
        //Если ссылка не указана, берем текущий URI
        if (empty($link)) { $link = htmlspecialchars($_SERVER['REQUEST_URI']); }

        //Проверяем, есть ли уже в глубиномере такое звено
        $already = false;
        foreach($this->pathway as $pathway){
            if ($pathway['link'] == $link){
                $already = true;
            }
        }

        //Если такого звена еще нет, добавляем его
        if(!$already){
            // проверяем нет ли на ссылку пункта меню, если есть, меняем заголовок
            $title = ($menu_title = cmsCore::getInstance()->getLinkInMenu($link)) ? cmsUser::stringReplaceUserProperties($menu_title, true) : $title;
            $this->pathway[] = array('title'=>$title, 'link'=>$link);
        }

        return $this;
    }
    
    /**
     * Выводит на экран шаблон сайта
     * Какой именно шаблон выводить определяют константы TEMPLATE и TEMPLATE_DIR
     * Эти константы задаются в файле /core/cms.php
     */
    public function showTemplate() {
        // Инициализируем нужные объекты
        $inCore = cmsCore::getInstance();
        $inUser = cmsCore::c('user');
        $inPage = $this;
        $inConf = $this->site_cfg;
        $inDB   = cmsCore::c('db');
        $tpl_cfgs = cmsCore::getTplCfg();

        // Формируем модули заранее
        $this->loadModulesForMenuItem();

        global $_LANG;

        if (file_exists(TEMPLATE_DIR. 'template.php')) {
            require(TEMPLATE_DIR. 'template.php');
            return;
        }

        cmsCore::halt($_LANG['TEMPLATE'].' "'.TEMPLATE.'" '.$_LANG['NOT_FOUND']);
    }
    
    /**
     * Подключает файл из папки с шаблоном
     * Если в папке текущего шаблона такой файл не найден, ищет в дефолтном
     * @param string $file например "special/error404.html"
     * @param array $data массив значений, доступных в шаблоне
     * @return <type>
     */
    public static function includeTemplateFile($file, $data=array()) {
        $inCore = cmsCore::getInstance();
        $inUser = cmsCore::c('user');
        $inPage = cmsCore::c('page');
        $inDB   = cmsCore::c('db');
        
        extract($data);
        global $_LANG;

        if (file_exists(cmsCore::c('config')->template_dir . $file)) {
            include(cmsCore::c('config')->template_dir . $file);
            return true;
        }

        if (file_exists(cmsCore::c('config')->default_template_dir . $file)) {
            include(cmsCore::c('config')->default_template_dir . $file);
            return true;
        }

        return false;
    }
    
    /**
     * Показывает Splash страницу
     * @return bool
     */
    public static function showSplash(){
        if (self::includeTemplateFile('splash/splash.php')){
            cmsCore::setCookie('splash', md5('splash'), time()+60*60*24*30);
            return true;
        }

        return false;
    }
    
    /**
     * Проверяет, нужно ли показывать сплеш-страницу (приветствие)
     * @return bool
     */
    public static function isSplash() {
        if (cmsCore::c('config')->splash) {
            return !cmsCore::getCookie('splash');
        } else {
            return false;
        }
    }
    
    /**
     * Загружает все модули для данного пункта меню и шаблона
     * @return bool
     */
    private function loadModulesForMenuItem() {
        if (isset($this->modules)) { return true; }

        $this->modules = array();

        $inCore = cmsCore::getInstance();

        $is_strict = $inCore->isMenuIdStrict();
        if (!$is_strict) {
            $strict_sql = "AND (m.is_strict_bind = 0)";
        } else {
            $strict_sql = '';
        }

        $menuid = $inCore->menuId();

        $sql = "SELECT m.*, mb.position as mb_position
                FROM cms_modules m
                INNER JOIN cms_modules_bind mb ON mb.module_id = m.id AND mb.menu_id IN (". $menuid .", 0) AND mb.tpl = '". $this->site_cfg->template ."'
                WHERE m.published = 1 ". $strict_sql ."
                ORDER BY m.ordering ASC";

        $result = cmsCore::c('db')->query($sql);

        if (!cmsCore::c('db')->num_rows($result)) {
            return true;
        }

        while ($mod = cmsCore::c('db')->fetch_assoc($result)) {
            if (!cmsCore::checkContentAccess($mod['access_list'], false)) { continue; }
            
            // не показывать модуль на определенных пунктах меню
            if ($mod['hidden_menu_ids']) {
                $mod['hidden_menu_ids'] = cmsCore::yamlToArray($mod['hidden_menu_ids']);
                if (in_array($menuid, $mod['hidden_menu_ids'])) {
                    if ($is_strict || !$mod['is_strict_bind_hidden']) {
                        continue;
                    }
                }
            }

            // формируем html модуля
            $m = $this->renderModule($mod);
            if (!$m) { continue; }

            // список модулей на позицию
            $this->modules[$mod['mb_position']][] = $m;
        }

        return true;
    }
    
    /**
     * Возвращает кол-во модулей на позицию
     * @return int
     */
    public function countModules($position){
        $this->loadModulesForMenuItem();

        if(!isset($this->modules[$position])){ return 0; }

        return count($this->modules[$position]);
    }
    
    /**
     * Формирует модуль
     * @param array $mod
     * @return html
     */
    private function renderModule($mod){
        $inCore = cmsCore::getInstance();

        // флаг показа модуля
        $callback = true;

        // html код модуля
        $html = '';
        
        $mod['titles'] = cmsCore::yamlToArray($mod['titles']);
        // переопределяем название в зависимости от языка
        if (!empty($mod['titles'][cmsCore::c('config')->lang])) {
            $mod['title'] = $mod['titles'][cmsCore::c('config')->lang];
        } 

        // для php модулей загружаем файл локализации
        if (!$mod['user']){ cmsCore::loadLanguage('modules/'.$mod['content']); }

        // Собственный модуль, созданный в админке
        if (!$mod['is_external']){
            $mod['body'] = cmsCore::processFilters($mod['content']);
        }else{ // Отдельный модуль
            if (cmsCore::includeFile('modules/'. $mod['content'] .'/module.php')) {
                // Если есть кеш, берем тело модуля из него
                if ($mod['cache'] && $this->site_cfg->cache && empty($mod['cache_enable'])) {
                    $mod['body'] = cmsCore::c('cache')->get('modules', $mod['id'], $mod['content'], array($mod['cachetime'], $mod['cacheint']));
                }
                
                if (empty($mod['body']) || !empty($mod['cache_enable'])) {
                    $cfg = cmsCore::yamlToArray($mod['config']);
                    
                    // переходный костыль для указания шаблона
                    if (empty($cfg['tpl'])) {
                        $cfg['tpl'] = $mod['content'];
                    }
                    
                    $inCore->cacheModuleConfig($mod['id'], $cfg);

                    ob_start();
                        $callback = call_user_func($mod['content'], $mod, $cfg);
                    $mod['body'] = ob_get_clean();

                    if ($mod['cache'] && $this->site_cfg->cache && empty($mod['cache_enable'])) {
                        cmsCore::c('cache')->set($mod['body'], 'modules', $mod['id'], $mod['content']);
                    }
                } else {
                    $callback = true;
                }
            }
        }

        // выводим модуль в шаблоне если модуль вернул true
        if ($callback) {
            $module_tpl = file_exists(TEMPLATE_DIR .'modules/'. $mod['template']) ? $mod['template'] : 'module';
            
            $cfglink = (cmsConfig::getConfig('fastcfg') && cmsUser::getInstance()->is_admin) ? true : false;

            $html = self::initTemplate('modules', $module_tpl)->
                    assign('cfglink', $cfglink)->
                    assign('mod', $mod)->
                    fetch();
        }

        return $html;
    }
    
    /**
     * Выводит модули для указанной позиции и текущего пункта меню
     * @param string $position
     * @param string $code
     * @return html
     */
    public function printModules($position, $code=''){
        $this->loadModulesForMenuItem();

        if(!isset($this->modules[$position])){ return; }

        foreach ($this->modules[$position] as $html) {
            echo $html .' '. $code;
        }

        return;
    }
    
    /**
     * Печатает модуль по id или по названию
     * @param mixed $id
     * @return html
     */
    public function printModule($id){
        if(is_numeric($id)){
            $where = "id = '$id'";
        } else {
            $where = "MATCH(content) AGAINST ('{$id}' IN BOOLEAN MODE)";
        }

        $mod = cmsCore::c('db')->get_fields('cms_modules', $where, '*');
        if(!$mod){ return false; }

        if (!cmsCore::checkContentAccess($mod['access_list'])){ return false; }

        // формируем html модуля
        $m = $this->renderModule($mod);
        if(!$m){ return false; }

        echo $m;

        return true;
    }
    
    /**
     * Возвращает html-код каптчи
     * @return html
     */
    public static function getCaptcha() {
        $captcha_code = cmsCore::callEvent('INSERT_CAPTCHA', false);
        
        if ($captcha_code === false) {
            global $_LANG;
            return $_LANG['INSERT_CAPTCHA_ERROR'];
        }
        
        return $captcha_code;
    }
    
    /**
     * Валидация каптчи
     * @return bool
     */
    public static function checkCaptchaCode(){
        return cmsCore::checkCaptchaCode();
    }
    
    /**
     * Разбивает текст на слова и делает каждое слово ссылкой, добавляя в его начало $link
     * @param string $link
     * @param string $text
     * @return html
     */
    public static function getMetaSearchLink($link, $text){
        if(!$text) { return ''; }

        $text = html_entity_decode(trim(trim(strip_tags($text)), '.'));

        foreach(explode(',', $text) as $value){

            $v = trim(str_replace(array("\r","\n"), '', $value));
            $worlds[] = '<a href="'.$link.urlencode($v).'">'.$v.'</a>';

        }

        return implode(', ', $worlds);
    }
    
    /**
     * Возвращает html-код панели для вставки BBCode
     * @param string $field_id
     * @param bool $images
     * @param string $placekind
     * @return html
     */
    public static function getBBCodeToolbar($field_id, $images=0, $component='forum', $target='post', $target_id=0){
        // Поддержка плагинов панели ббкодов (ее замены)
        $p_toolbar = cmsCore::callEvent(
            'REPLACE_BBCODE_BUTTONS',
            array(
                'html' => '',
                'field_id' => $field_id,
                'images' => $images,
                'component' => $component,
                'target' => $target,
                'target_id' => $target_id
            )
        );

        if($p_toolbar['html']){ return cmsCore::callEvent('GET_BBCODE_BUTTON', $p_toolbar['html']); }

        $inPage = self::getInstance();

        $inPage->addHeadJS('core/js/smiles.js');
        if($images){
            $inPage->addHeadJS('includes/jquery/upload/ajaxfileupload.js');
        }

        ob_start();
        self::includeTemplateFile(
            'special/bbcode_panel.php',
            array(
                'field_id' => $field_id,
                'images' => $images,
                'component' => $component,
                'target' => $target,
                'target_id' => $target_id
            )
        );

        return cmsCore::callEvent('GET_BBCODE_BUTTON', ob_get_clean());
    }
    
    /**
     * Возвращает html-код панели со смайлами
     * @param string $for_field_id
     * @return html
     */
    public static function getSmilesPanel($for_field_id){
        $p_html = cmsCore::callEvent('REPLACE_SMILES', array('html' => '', 'for_field_id'=>$for_field_id));
        if($p_html['html']){ return $p_html['html']; }

        $html = '<div class="usr_msg_smilebox" id="smilespanel-'.$for_field_id.'" style="display:none">';
        if ($handle = opendir(PATH.'/images/smilies')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != '.' && $file != '..' && mb_strstr($file, '.gif')){
                 $tag = str_replace('.gif', '', $file);
                 $dir = '/images/smilies/';

                 $html .= '<a href="javascript:addSmile(\''.$tag.'\', \''.$for_field_id.'\');"><img src="'.$dir.$file.'" border="0" /></a> ';
                }
            }

            closedir($handle);
        }
        $html .= '</div>';
        return $html;
    }
    
    /**
     * Подключает JS и CSS для Ajax загрузки файлов
     */
    public function initAjaxUpload($script = 'plupload', $options = array(), $files = false){
        switch ($script) {
            case 'plupload':
                $this->addHeadJS('includes/plupload/plupload.full.min.js');
                $this->addHeadCSS('includes/jqueryui/css/smoothness/jquery-ui.min.css');
                if (file_exists(PATH .'/includes/plupload/langs/'. $this->site_cfg->lang .'.js')){
                    $this->addHeadJS('includes/plupload/langs/'. $this->site_cfg->lang .'.js');
                }
                
                $options = array_merge(
                    array(
                        'url'           => '/core/ajax/imginsert.php',
                        'del_url'       => '/core/ajax/imgdelete.php',
                        'extensions'    => 'jpg,gif,png',
                        'max_file_size' => '10',
                        
                        'type'         => 'images',
                        'component'    => 'content',
                        'target'       => '',
                        'target_id'    => '0',
                        'insertEditor' => false,
                        'editorName'   => 'content',
                        'title'        => true,
                        'description'  => true
                    ),
                    $options
                );
                
                $options['ses_id'] = session_id();
                
                ob_start();
                    self::includeTemplateFile('special/ajaxFileUpload.php', array('options' => $options, 'files' => $files));
                return ob_get_clean();

                break;
        }

        return false;
    }
    
    /**
     * Подключает JS и CSS для автокомплита
     */
    public function initAutocomplete(){
        $this->addHeadJS('includes/jquery/autocomplete/jquery.autocomplete.min.js');
        $this->addHeadCSS('includes/jquery/autocomplete/jquery.autocomplete.css');
        return $this;
    }

    /**
     * Возвращает JS-код инициализации автокомплита для указанного поля ввода и скрипта
     * @param string $script
     * @param string $field_id
     * @param bool $multiple параметр больше не используется, строка убрана  $multiple = $multiple ? 'true' : 'false';
     * @return js
     */
    public function getAutocompleteJS($script, $field_id='tags'){
        return '$("#'.$field_id.'").autocomplete({
                        url: "/core/ajax/'.$script.'.php",
                        useDelimiter: true,
                        queryParamName: "q",
                        lineSeparator: "\n",
                        cellSeparator: "|",
                        minChars: 2,
                        maxItemsToShow: 10,
                        delay: 400
                    }
                );';
    }
    
    /**
     * Возвращает код панели для постраничной навигации
     * @param int $total
     * @param int $page
     * @param int $perpage
     * @param string $link
     * @param array $params
     * @return html
     */
    public static function getPagebar($total, $page, $perpage, $link, $params=array()) {
        if (empty($total)) { return; }

        $pagebar = cmsCore::callEvent('GET_PAGEBAR', array($total, $page, $perpage, $link, $params));

        if (!is_array($pagebar) && $pagebar) { return $pagebar; }

        global $_LANG;

        $total_pages = ceil($total / $perpage);

        if ($total_pages == 1) { return; }

        //configure for the starting links per page

        //used in the loop
        $max_links = ceil(cmsCore::c('config')->max_pagebar_links/2) + 1;
        $current = 1;

        //if page is above max link
        if ($page > $max_links) {
            //start of loop
            $current = (($current + $page) - $max_links);
        }

        //if page is not page one
        if ($page >= 1) {
            //top of the loop extends
            $max_links = $max_links + ($page-1);
        }

        //if the top page is visible then reset the top of the loop to the $total_pages
        if ($max_links > $total_pages) {
            $max_links = $total_pages + 1;
        }

        $href = $link;
        if (is_array($params)) { 
            foreach($params as $param => $value) {
                $href = str_replace('%'.$param.'%', $value, $href);
            } 
        }
        
        return self::initTemplate('special', 'pagebar')->
            assign('LANG', $_LANG)->
            assign('pagebar', $pagebar)->
            assign('total_pages', $total_pages)->
            assign('max_links', $max_links)->
            assign('page', $page)->
            assign('current', $current)->
            assign('href', $href)->
            fetch();
    }
    
    /**
     * Возвращает строку js с определенной языковой переменной
     * @param string $key ключ нужной ячейки массива $_LANG
     * @return string
     */
    public static function getLangJS($key){
        global $_LANG;

        if(!isset($_LANG[$key])){ return; }

        $value = htmlspecialchars($_LANG[$key]);

        return "var LANG_{$key} = '{$value}'; ";
    }
    
    /**
     * Печатает строки js с языковыми переменными
     * @param array $keys массив ключей нужных ячеек массива $_LANG
     */
    public static function displayLangJS($keys){
        if(!is_array($keys)){ return; }

        echo '<script type="text/javascript">';
        foreach ($keys as $key) {
            echo self::getLangJS($key);
        }
        echo '</script>';

        return;
    }
    
    /**
     * Добавляет в хидер js языковую переменную
     * @param array|string $key
     * @return \cmsPage
     */
    public function addHeadJsLang($key){
        if (is_array($key)) {
            array_map(array($this, __FUNCTION__), $key);
        } else {
            $this->page_lang[$key] = self::getLangJS($key);
        }
        return $this;
    }
}
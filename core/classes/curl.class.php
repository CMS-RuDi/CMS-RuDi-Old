<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

/**
 * Класс обертка для методов CURL
 * 
 * @author DS Soft <support@ds-soft.ru>
 * @version 2.0.0
 */
class class_curl {
    private $server = false;
    private $config = array();
    private $ch = null;
    
    private $url;
    private $method = 'get';
    private $multipart = false;
    private $ajax = false;
    private $request_headers = array();
    private $cookies = array();
    private $curl_options = array();

    public $response = '';
    public $response_header_raw = '';
    public $response_header = array();
    public $response_encoding = false;
    public $meta_tags = array();
    public $code     = false;
    public $info     = false;
    public $error    = false;
    public $errno    = false;
    
    public function __construct($cfg=array()) {
        $this->setConfig($cfg);
    }
    
    /**
     * Выставляет настройки
     * @param array $cfg
     * @return \clas_curl
     */
    public function setConfig($cfg=array()) {
        $this->config = array_merge(self::getDefaultConfig(), $cfg);
        return $this;
    }
    
    /**
     * Возвращает массив с дефолтовыми настройками
     * @return array
     */
    public static function getDefaultConfig() {
        return array(
            'server'           => false,
            'server_key'       => false,
            'default_encoding' => 'utf-8',
            'encoding'         => false,
            'ssl'              => true,
            'proxy'            => '',
            'proxy_type'       => CURLPROXY_HTTP,
            'proxy_user'       => '',
            'proxy_password'   => '',
            'interface'        => false,
            'connecttimeout'   => 30,
            'timeout'          => 60,
            'useragent'        => 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:25.0) Gecko/20100101 Firefox/25.0'
        );
    }
    
    /**
     * Указываем что нужно выставить ajax заголовок при запросе
     * @return \clas_curl
     */
    public function ajax() {
        $this->ajax = true;
        return $this;
    }
    
    /**
     * Устанавливает значение куки
     * @param string $key ключ
     * @param string $val значение
     * @return \clas_curl
     */
    public function setCookie($key, $val) {
        $this->cookies[$key] = $val;
        return $this;
    }
    
    /**
     * Устанавливает значение кук из массива
     * @param array $cookies массив с куками
     * @return \clas_curl
     */
    public function setCookies($cookies) {
        foreach ($cookies as $key => $val) {
            $this->cookies[$key] = $val;
        }
        return $this;
    }
    
    /**
     * Возвращает массив с куками
     * @return \clas_curl
     */
    public function getCookies() {
        return $this->cookies;
    }
    
    /**
     * Удаляет все куки
     * @return \clas_curl
     */
    public function clearCookies() {
        $this->cookies = array();
        return $this;
    }

    /**
     * Выставляет необходимые опции и выполняет запрос
     * @param string $method
     * @param string $url ссылка
     * @param array $query_data массив параметров
     * @param array $headers массив хидеров
     * @param string $file ссылка на файл для отправки методом PUT
     * @return \clas_curl
     */
    public function request($method, $url, $query_data=array(), $headers=array(), $file=false) {
        $this->clear();
        
        $this->url = $url;
        
        $this->setRequestMethod($method);
        $this->setRequestOptions($query_data, $headers);
        
        if ($this->method == 'put' && !empty($file) && file_exists($file)) {
            $this->set_option(CURLOPT_PUT, true);

            $this->multipart = true;
            
            $fp = fopen($file, 'r');
            $this->set_option(CURLOPT_INFILE, $fp)->
                set_option(CURLOPT_INFILESIZE, filesize($file))->
                set_option(CURLOPT_UPLOAD, true);
        }
        
        if (!empty($this->config['server']) && $this->multipart === false) {
            $this->requestFromServer();
        } else {
            $this->exec();
        }
        
        if ($this->method == 'put' && $this->multipart === true) {
            fclose($fp);
        }
        
        $this->reset();

        return $this;
    }
    
    /**
     * Выставляет необходимые опции для метода запроса
     * @param string $method
     */
    private function setRequestMethod($method) {
        $this->method = empty($method) ? $this->method : $method;
        
        switch ($this->method) {
            case 'head':
                $this->set_option(CURLOPT_NOBODY, true);
                break;
            case 'get':
                $this->set_option(CURLOPT_HTTPGET, true);
                break;
            case 'post':
                $this->set_option(CURLOPT_POST, true);
                break;
        }

        $this->set_option(CURLOPT_CUSTOMREQUEST, strtoupper($this->method));
    }
    
    /**
     * Устанавливает опции запроса: постдата, хидеры, куки, прокси, интерфей и др.
     * @param array $query_data
     * @param array $headers
     */
    private function setRequestOptions($query_data, $headers) {
        $this->request_headers = array();
        
        if ($this->ajax === true) {
            $this->request_headers[] = 'X-Requested-With: XMLHttpRequest';
        }
        
        if (!empty($headers)) {
            $this->request_headers = array_merge($this->request_headers, $headers);
        }
        
        if ($this->method == 'get' && !empty($query_data)) {
            $this->url .= strstr($this->url, '?') ? '&' : '?';
            $this->url .= http_build_query($query_data);
        } else if (!empty($query_data)) {
            $this->set_option(CURLOPT_POSTFIELDS, $this->preparePostData($query_data));
        }
        
        $this->set_option(CURLOPT_URL, $this->url)->
            set_option(CURLOPT_USERAGENT, $this->config['useragent'])->
            set_option(CURLOPT_HEADER, false)->
            set_option(CURLOPT_CONNECTTIMEOUT, $this->config['connecttimeout'])->
            set_option(CURLOPT_TIMEOUT, $this->config['timeout'])->
            set_option(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1)->
            set_option(CURLOPT_FOLLOWLOCATION, true)->
            set_option(CURLOPT_MAXREDIRS, 5)->
            set_option(CURLOPT_AUTOREFERER, true)->
            set_option(CURLOPT_RETURNTRANSFER, true)->
            set_option(CURLINFO_HEADER_OUT, true)->
            set_option(CURLOPT_HEADERFUNCTION, array($this, 'setResponseHeaders'))->
            set_option(CURLOPT_ENCODING , 'gzip, deflate');
        
        if (!empty($this->request_headers)) {
            $this->set_option(CURLOPT_HTTPHEADER, $this->request_headers);
        }
        
        if (!empty($this->cookies)) {
            $cookies = array();
            foreach ($this->cookies as $k => $v) {
                $cookies[] = $k .'='. $v;
            }
            $this->set_option(CURLOPT_COOKIE, implode('; ', $cookies));
        }
        
        $this->setProxy();
        $this->setInterface();
    }
    
    /**
     * Выставляет опции прокси
     */
    private function setProxy() {
        if (!empty($this->config['proxy'])) {
            $this->set_option(CURLOPT_PROXY, $this->config['proxy']);
            $this->set_option(CURLOPT_PROXYTYPE, $this->config['proxy_type']);
            if (!empty($this->config['proxy_user']) && !empty($this->config['proxy_password'])) {
                $this->set_option(CURLOPT_PROXYUSERPWD, $this->config['proxy_user'] .':'. $this->config['proxy_password']);
            }
        }
    }

    /**
     * Выставляет опции интерфейса
     * @return boolean
     */
    private function setInterface() {
        if (!empty($this->config['interface']) && empty($this->config['server'])) {
            curl_setopt($this->ch, CURLOPT_INTERFACE, $this->config['interface']);
        }
    }
    
    /**
     * Подготавливает данные для post запроса
     * @param array $post_data
     * @return string|array
     */
    private function preparePostData($post_data=false) {
        if (empty($post_data)) { return ''; }
        
        if (!is_array($post_data)) {
            return $post_data;
        }
        
        foreach ($post_data as $v) {
            if (substr($v, 0, 1) == '@') {
                $this->multipart = true;
                return $post_data;
            }
        }
        
        return http_build_query($post_data);
    }
    
    /**
     * Выставляет опции для запроса к ssl url
     * @param string $url
     */
    private function checkSSLurl($url) {
        if (strtolower(parse_url($url, PHP_URL_SCHEME)) == 'https' && $this->config['ssl'] === true) {
            $this->set_option(CURLOPT_SSL_VERIFYHOST, 2);
            $this->set_option(CURLOPT_SSL_VERIFYPEER, true);
            $this->set_option(CURLOPT_CAINFO, __DIR__ . DIRECTORY_SEPARATOR . 'cacert.pem');
            $this->set_option(CURLOPT_CAPATH, __DIR__);
        } else {
            $this->set_option(CURLOPT_SSL_VERIFYHOST, 1);
            $this->set_option(CURLOPT_SSL_VERIFYPEER, false);
        }
    }
    
    /**
     * Добавляет опцию для курла в массив опций для дальнейшей инициализации 
     * методом curl_setopt_array
     * @param mixed $option
     * @param mixed $value
     * @return \clas_curl
     */
    public function set_option($option, $value) {
        if (!isset($this->curl_options[$option])) {
            $this->curl_options[$option] = $value;
        }
        return $this;
    }
    
    /**
     * Выполняет запрос
     */
    private function exec() {
        $this->checkSSLurl($this->url);
        
        $this->ch = curl_init();
        
        foreach ($this->curl_options as $k => $v) {
            curl_setopt($this->ch, $k, $v);
        }
        
        $this->response = curl_exec($this->ch);
        $this->code     = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $this->info     = curl_getinfo($this->ch);
        $this->error    = curl_error($this->ch);
        $this->errno    = curl_errno($this->ch);
        
        curl_close($this->ch);
        
        $this->processHeaders();
        $this->processHeaderCharset();
        
        if ($this->config['encoding'] === true && $this->response_encoding != $this->config['default_encoding']) {
            $this->response = iconv($this->response_encoding, $this->config['default_encoding'], $this->response);
        } else if ($this->config['encoding'] !== false) {
            $this->response = iconv($this->config['encoding'], $this->config['default_encoding'], $this->response);
        }
    }
    
    /**
     * Сохраняет полученный хидер в переменную response_header_raw
     * @param type $ch
     * @param string $header
     * @return integer
     */
    private function setResponseHeaders($ch, $header) {
        $this->response_header_raw .= $header;
        return strlen($header);
    }
    
    /**
     * Создает ассоциативный массив из данных хидера
     */
    private function processHeaders(){
        $headers = explode("\r\n", $this->response_header_raw);
        
        foreach ($headers as $header) {
            $header = trim($header);
            
            if (strpos($header, ':') !== false) {
                list($key, $value) = explode(':', $header, 2);
                
                $key = strtolower(trim($key));
                
                if ($key == 'set-cookie') {
                    $this->processCookies($value);
                }
                
                $this->response_header[$key][] = trim($value);
            } else if (!empty($header)) {
                $this->response_header[0][] = $header;
            }
        }
    }
    
    /**
     * Заносит в массив куки выставленные в хидере
     * @param string $string
     */
    private function processCookies($string) {
        list($cookie) = explode(';', $string);
        
        $cookie = explode('=', $cookie);
        
        $cookie[0] = trim($cookie[0]);
        $cookie[1] = strtolower(trim($cookie[1]));
        
        if ($cookie[1] == 'deleted') {
            unset($this->cookies[$cookie[0]]);
        } else {
            $this->cookies[$cookie[0]] = $cookie[1];
        }
    }
    
    /**
     * Получаем кодировку полученных данных из хидера
     */
    private function processHeaderCharset() {
        if (preg_match('#charset=(.+?)$#is', $this->info['content_type'], $charset)) {
            $this->response_encoding = strtolower(trim($charset[1]));
        } else {
            $this->processBodyCharset();
        }
    }
    
    /**
     * Получаем кодировку полученных данных из мета тега
     */
    private function processBodyCharset() {
        if (preg_match("#<meta\b[^<>]*?\bcontent=['\"]?text/html;\s*charset=([^>\s\"']+)['\"]?#is", $this->response, $charset)) {
            $this->response_encoding = strtolower(trim($charset[1]));
        }
    }
    
    private function clear() {
        $this->method = 'get';
        $this->response = '';
        $this->response_header_raw = '';
        $this->response_header = array();
        $this->response_encoding = false;
        $this->meta_tags = array();
        $this->code = false;
        $this->info = false;
        $this->error = false;
        $this->errno = false;
    }
    
    private function reset() {
        $this->multipart = false;
        $this->ajax = false;
        $this->curl_options = array();
    }
    
    /**
     * Выполняет запрос с помошью такого же класса установленного на удаленном сервере
     */
    private function requestFromServer() {
        $config = $this->config;
        unset($config['server']);
        unset($config['server_key']);
        
        $data = base64_encode(
            json_encode(array(
                'url'          => $this->url,
                'curl_options' => $this->curl_options,
                'config'       => $config,
                'cookies'      => $this->cookies
            ))
        );
        
        $signature = md5(
            $this->config['server_key'] .'-'. $data .'-'. $this->config['server_key']
        );
        
        $inCurl = new self();
        $inCurl->exec('post', $this->config['server'], array(
            'requestData' => $data,
            'signature'   => $signature
        ));
        
        if (!empty($inCurl->response)) {
            $request = json_decode($inCurl->response, true);
            
            $rSignature = $request['signature'];

            $request = json_decode(base64_decode($request['requestData']), true);
            
            $signature = md5(
                $this->config['server_key'] .'-'. $request['requestData'] .'-'. $this->config['server_key']
            );
            
            if ($signature == $rSignature) {
                foreach ($request as $k => $v) {
                    $this->{$k} = $v;
                }
            } else {
                $this->error = 'Invalid signature';
            }
        } else {
            $this->error = 'Invalid response';
        }
    }
    
    /**
     * Получает данные для запроса, проверяет хеш если хеш совпадает выполняет
     * запрос и возвращает данные
     */
    public function startServer() {
        if ($this->server !== true) { exit; }
        
        $requestData = $_POST['requestData'];
        $rSignature  = $_POST['signature'];
        
        $signature   = md5($this->config['server_key'] .'-'. $requestData .'-'. $this->config['server_key']);
        
        if ($rSignature == $signature) {
            $this->config = array_merge($this->config, $requestData['config']);
            unset($requestData['config']);
            
            foreach ($requestData as $k => $v) {
                $this->{$k} = $v;
            }
            
            $this->exec();
            
            $data = base64_encode(
                json_encode(array(
                    'response'            => $this->response,
                    'response_header_raw' => $this->response_header_raw,
                    'response_header'     => $this->response_header,
                    'response_encoding'   => $this->response_encoding,
                    'code'                => $this->code,
                    'info'                => $this->info,
                    'error'               => $this->error,
                    'errno'               => $this->errno
                ))
            );

            $signature = md5(
                $this->config['server_key'] .'-'. $data .'-'. $this->config['server_key']
            );
            
            echo json_encode(array(
                'requestData' => $data,
                'signature'   => $signature
            ));
        }
        
        exit;
    }
    
    /**
     * Возвращает массив или объект из json данных полученных в результате запроса
     * @param boolean $assoc возвращать в виде ассоциативного массива или объекта
     */
    public function json($assoc=true) {
        return json_decode($this->response, $assoc);
    }
    
    /**
     * Возвращает xml объект из данных результа запроса
     * @return object
     */
    public function xml() {
        return simplexml_load_string($this->response);
    }
    
    /**
     * Парсит из тела мета теги и заполняет ими meta_tags
     */
    public function meta() {
        if (!preg_match_all('#<meta(.+?)>#is', $this->response, $matches)) {
            return false;
        }
        
        foreach ($matches[1] as $val) {
            preg_match_all('#([a-z0-9\-\_]+)="([^"]+)"#is', trim($val), $match);
            
            $name = ''; $item = array();
            foreach ($match[1] as $k => $v) {
                if ($v == 'name' || $v == 'property' || $v == 'itemprop') {
                    $name = $match[2][$k];
                } else {
                    $item[$v] = html_entity_decode($match[2][$k], ENT_COMPAT, $this->config['default_encoding']);
                }
            }
            
            if (!empty($name)) {
                if (!isset($this->meta_tags[$name])) {
                    $this->meta_tags[$name] = array();
                }
                $this->meta_tags[$name][] = $item;
            } else {
                $this->meta_tags[] = $item;
            }
        }
        
        return true;
    }
    
    /**
     * Сохраняет удаленный файл $src в локальный файл $dist
     * @param string $src
     * @param string $dist
     * @param array $header
     * @return boolean
     */
    public function saveFile($src, $dist, $header=array()) {
        $file = fopen($dist, 'w');
        if (!$file) { return false; }
        
        $ch = curl_init();
        if (!$ch) { return false; }

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:25.0) Gecko/20100101 Firefox/25.0');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $src);
        curl_setopt($ch, CURLOPT_FILE, $file);

        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $result = curl_exec($ch);
        
        fclose($file);

        curl_close($ch);
        
        return $result;
    }
}
<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.7                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2013 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

/**
 * Класс обертка для методов CURL
 * 
 * @author DS Soft <support@ds-soft.ru>
 * @version 1.2.4
 */
class miniCurl {
    public static $Server = false;
    
    private $key = '';
    private $ch=null;
    
    private $result_encoding = '';
    private $current_url = '';
    public $error = '';
    public $info = '';
    public $config = array();
    public $cookies = array();
    private $result = '';
    
    
    public $header = false;
    public $result_headers = array();
    public $meta_tags = array();
    public $result_head = '';
    public $result_body = '';
    public $postdata;
    
    public function __construct($cfg=array()) {
        if (!function_exists('curl_setopt') || !function_exists('curl_init')) {
            return self::echoError('Библиотека CURL не установлена');
        }
        
        $this->reInit($cfg);
        
        return $this;
    }
    
    public function __destruct() {
        if ($this->ch) {
            curl_close($this->ch);
            $this->ch = null;
        }
    }
    
    public function reInit($cfg=array()) {
        if ($this->ch) {
            curl_close($this->ch);
            $this->ch = null;
        }
        
        $this->error = '';
        $this->info = '';
        $this->cookies = array();
        $this->result_encoding = '';
        $this->current_url = '';
        $this->header = false;
        $this->result = '';
        $this->result_headers = array();
        $this->meta_tags = array();
        $this->result_head = '';
        $this->result_body = '';
        $this->config = array_merge(self::getDefaultConfig(), $cfg);
        
        $this->ch = curl_init();
        if (!$this->ch) {
            $this->error = curl_error($this->ch);
            return;
        }

        $this->set_option(CURLOPT_USERAGENT,      $this->config['user_agent']);
        $this->set_option(CURLOPT_FOLLOWLOCATION, $this->config['follow_location']);
        $this->set_option(CURLOPT_HEADER,         $this->config['header']);
        $this->set_option(CURLOPT_HTTP_VERSION,   $this->config['http_version']);
        $this->set_option(CURLOPT_RETURNTRANSFER, $this->config['return_transfer']);
        $this->set_option(CURLOPT_CONNECTTIMEOUT, $this->config['connect_timeout']);
        $this->set_option(CURLOPT_AUTOREFERER,    $this->config['auto_referer']);
    }
    
    private static function getDefaultConfig() {
        return array(
            'header' => true,
            'return_transfer' => true,
            'follow_location' => true,
            'auto_referer' => true,
            'connect_timeout' => 60,
            'user_agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:25.0) Gecko/20100101 Firefox/25.0',
            'verify_host' => false,
            'verify_peer' => false,
            'meta' => false,
            'encoding' => false,
            'default_encoding' => 'utf-8',
            'proxy' =>'',
            'proxy_type' => CURLPROXY_HTTP,
            'proxy_user' => '',
            'proxy_password' => '',
            'externalIp' => false,
            'http_version' => CURL_HTTP_VERSION_1_1
        );
    }
    
    public function set_option($option, $value) {
        if (!curl_setopt($this->ch, $option, $value)) {
            $this->error = curl_error($this->ch);
            return false;
        }
        return true;
    }
    
    private function setProxy() {
        if (!empty($this->config['proxy'])) {
            $this->set_option(CURLOPT_PROXY, $this->config['proxy']);
            $this->set_option(CURLOPT_PROXYTYPE, $this->config['proxy_type']);
            if (!empty($this->config['proxy_user']) && !empty($this->config['proxy_password'])) {
                $this->set_option(CURLOPT_PROXYUSERPWD, $this->config['proxy_user'] .':'. $this->config['proxy_password']);
            }
        }
    }

    private function setInterface() {
        if (!empty($this->config['interface'])) {
            curl_setopt($this->ch, CURLOPT_INTERFACE, $this->config['interface']);
        }
        return true;
    }
    
    private function https(){
        $scheme = parse_url($this->current_url,PHP_URL_SCHEME);
        $scheme = strtolower($scheme);
        if ($scheme == 'https') {
            $this->set_option(CURLOPT_SSL_VERIFYHOST, $this->config['verify_host']);
            $this->set_option(CURLOPT_SSL_VERIFYPEER, $this->config['verify_peer']);
        }
    }
    
    private static function echoError($msg) {
        if (class_exists('cmsCore')) {
            cmsCore::addsessionmessage($msg, 'error');
        } else {
            echo $msg;
        }
        return false;
    }
    
    private function getPostRawData($post_data=false) {
        $multipart = false; $raw_data = array();
        
        if (!empty($post_data)) {
            if (is_array($post_data)) {
                foreach ($post_data as $k => $v) {
                    if (mb_substr($v, 0, 1) == '@') {
                        $multipart = true;
                        break;
                    }
                    $raw_data[] = $k .'='. urlencode($v);
                }
            } else {
                return $post_data;
            }
        } else {
            return '';
        }
        
        return $multipart === false ? implode('&', $raw_data) : $post_data;
    }
    
    public function getCurrentUrl() {
        return $this->current_url;
    }
    
    public function get($url, $header = array(), $type = '') {
        $this->current_url = $url;
        
        if (!$this->current_url) { 
            return self::echoError('Не указан URL');
        }
        
        $this->header = empty($header) ? '' : $header;
        $this->set_option(CURLOPT_URL, $this->current_url);
        $this->set_option(CURLOPT_POST, false);
        $this->set_option(CURLOPT_HTTPGET, true);
        
        $this->https();
        
        return $this->exec($type);
    }
    
    public function saveFile($src, $dist, $header = '') {
        $file = fopen($dist, 'w');
        if (!$file) {
            self::echoError('Не возможно открыть(создать файл) '. $dist);
            return false;
        }
        
        $this->set_option(CURLOPT_HEADER, false);
        $this->set_option(CURLOPT_URL, $src);
        $this->set_option(CURLOPT_FILE, $file);
        
        if (!empty($header)) { $this->set_option(CURLOPT_HTTPHEADER, $header); }
        
        if ($this->cookies) {
            $cookies = array();
            foreach ($this->cookies as $k => $v) {
                $cookies[] = $k .'='. $v;
            }
            $this->set_option(CURLOPT_COOKIE, implode('; ', $cookies));
        }
        
        $this->setProxy();
        $this->setInterface();

        $result = curl_exec($this->ch);
        
        fclose($file);
        
        if ($result === false) {
            self::echoError('Не удалось получить файл '. $src);
            $this->error = curl_error($this->ch);
            return false;
        }
        
        $this->info = curl_getinfo($this->ch);
        
        return true;
    }

    public function post($url, $postdata = null, $header = array(), $type = '') {
        $this->current_url = $url;
        $this->postdata = $this->getPostRawData($postdata);
        
        if (empty($this->current_url)) {
            return self::echoError('Не указан URL');
        }
        
        $this->header = empty($header) ? '' : $header;;
        $this->set_option(CURLOPT_URL, $this->current_url);
        $this->set_option(CURLOPT_POST, true);
        $this->set_option(CURLOPT_POSTFIELDS, $this->postdata);
        
        $this->https();
        return $this->exec($type);
    }
    
    public function put($url, $file = false, $data = null, $header = ''){
        $this->current_url = $url;
        
        if (!$this->current_url) {
            return self::echoError('Не указан URL');
        }

        $this->set_option(CURLOPT_POST, false);
        $this->set_option(CURLOPT_POSTFIELDS, $data);
        
        $this->header = $header;
        $this->set_option(CURLOPT_URL, $this->current_url);
        $this->set_option(CURLOPT_PUT, true);

        if (!empty($file) && file_exists($file)) {
            $fp = fopen($file, 'r');

            $this->set_option(CURLOPT_INFILE, $fp);
            $this->set_option(CURLOPT_INFILESIZE, filesize($file));
            $this->set_option(CURLOPT_UPLOAD, true);
        }

        return $this->exec();
    }
    
    public function delete($url, $header = ''){
        $this->current_url = $url;
        
        if (!$this->current_url) {
            return self::echoError('Не указан URL');
        }
        
        $this->header = $header;
        $this->set_option(CURLOPT_URL, $this->current_url);
        $this->set_option(CURLOPT_CUSTOMREQUEST, 'DELETE');
        
        return $this->exec();
    }
    
    private function exec($type) {
        if (mb_strstr($type, 'ajax')) {
            if (empty($this->header) || !is_array($this->header)) {
                $this->header = array();
            }
            $this->header[] = 'X-Requested-With: XMLHttpRequest';
        }
        
        if ($this->header) {
            $this->set_option(CURLOPT_HTTPHEADER, $this->header);
        }
        
        if ($this->cookies) {
            $cookies = array();
            foreach ($this->cookies as $k => $v) {
                $cookies[] = $k .'='. $v;
            }
            $this->set_option(CURLOPT_COOKIE, implode('; ', $cookies));
        }
        
        $this->setProxy();
        $this->setInterface();
        
        $this->result = curl_exec($this->ch);
        
        if ($this->result === false) {
            $this->error = curl_error($this->ch);
            return false;
        }
        
        $this->info = curl_getinfo($this->ch);
        $this->processHeaders();
        $this->processBody();

        if (mb_strstr($type, 'json')) {
            return json_decode(
                $this->result_body,
                mb_strstr($type, 'json:array') ? true : false
            );
        }

        if (mb_strstr($type, 'xml')) {
            return simplexml_load_string($this->result_body);
        }
        
        return true;
    }
    
    private function processHeaders(){
        if ($this->config['header']) {
            $this->result_headers = array();
            $this->result_headers[0] = substr($this->result,0,$this->info['header_size']);
            $headers = explode("\r\n",$this->result_headers[0]);
            foreach ($headers as $header) {
                if (strpos($header,":") !== false) {
                    list($key,$value) = explode(":",$header,2);
                    $key = trim($key);
                    $key = strtolower($key);
                    switch ($key) {
                        case 'set-cookie':
                            $this->processCookies($value);
                            break;
                        case 'content-type':
                            if ($this->config['encoding'] === true) $this->processContentType($value);
                            break;
                    }
                    $this->result_headers[$key] = trim($value);
                }
            }
        }
    }
    
    private function processCookies($string) {
        $cookie	= explode(';',$string);
        $cookie = explode('=',$cookie[0]);
        
        $cookie[0] = trim($cookie[0]);
        $cookie[1] = trim($cookie[1]);
        
        if ($cookie[1] == 'DELETED' || $cookie[1] == 'deleted') {
            unset($this->cookies[$cookie[0]]);
        } else {
            $this->cookies[$cookie[0]] = $cookie[1];
        }
    }
    
    private function processContentType($string) {
        $pos = strpos($string,'charset');
        if ($pos !== false) {
            $endpos = strpos($string,';',$pos);
            if ($endpos === false) {
                $charset = substr($string,$pos);
            } else {
                $length = $endpos - $pos;
                $charset = substr($string,$pos,$length);
            }
            list(,$this->result_encoding) = explode('=',$charset,2);
        }
        return true;
    }
    
    private function processBody() {
        $html = array();
        
        if ($this->config['header']) {
            $this->result_body = substr($this->result, $this->info['header_size']);
        } else {
            $this->result_body = $this->result;
        }
            
        if ($this->config['meta'] === true && preg_match('#<head[^>]{0,}>(.+?)</head.+?<body[^>]{0,}>(.*)$#is', $this->result_body, $html)) {
            $this->result_head = $html[1];
            $this->result_body = $html[2];
        }
        
        if (!$this->result_encoding && $this->config['encoding'] === true) {
            if (preg_match("#<meta\b[^<>]*?\bcontent=['\"]?text/html;\s*charset=([^>\s\"']+)['\"]?#is", (empty($this->result_head) ? $this->result_body : $this->result_head), $match)) {
                $this->result_encoding = strtoupper($match[1]);
            }
        }
        
        if ($this->config['encoding'] !== false) { $this->processEncoding(); }
        if ($this->config['meta'] === true) { $this->processMetaSearch(); }
    }
    
    private function processEncoding() {
        if ($this->config['encoding'] !== true) {
            $this->result_encoding = $this->config['encoding'];
        }
        if ($this->result_encoding != $this->default_encoding) {
            $this->result_body = iconv($this->result_encoding, $this->default_encoding, $this->result_body);
            if ($this->config['meta'] === true) {
                $this->result_head = iconv($this->result_encoding, $this->default_encoding, $this->result_head);
            }
        }
        if ($this->config['meta'] === true) {
            $this->result_body = preg_replace('#\s+#is', ' ', $this->result_body);
            $this->result_head = preg_replace('#\s+#is', ' ', $this->result_head);
        }
    }
    
    private function processMetaSearch() {
        $this->meta_tags = array();
        if (!empty($this->result_head)) {
            preg_match_all('#<meta(.+?)>#is', $this->result_head, $matches);
            foreach ($matches[1] as $val) {
                preg_match_all('#([a-z0-9\-\_]+)="([^"]+)"#is', trim($val), $match);
                $name = ''; $item = array();
                foreach ($match[1] as $k => $v) {
                    if ($v == 'name' || $v == 'property' || $v == 'itemprop') {
                        $name = $match[2][$k];
                    } else {
                        $item[$v] = $match[2][$k];
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
        }
    }
    
    public function startServer() {
        if (!empty($this->key) && $this->key != md_5($_POST['key'])) {
            header("HTTP/1.0 404 Not Found");
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            exit;
        }
        
        $request = $_POST['request'];
        $request = base64_decode($request);
        $request = json_decode($request, true);
        
        $this->reInit($request['config']);
        $this->cookies = $request['cookies'];
        
        switch ($request['type']) {
            case 'get':
                    $this->get($request['url'], $request['header']);
                break;
            case 'ajaxGet':
                    $this->ajaxGet($request['url'], $request['header']);
                break;
            case 'post':
                    $this->post($request['url'], $request['postdata'], $request['header']);
                break;
            case 'ajaxPost':
                    $this->ajaxPost($request['url'], $request['postdata'], $request['header']);
                break;
            default:
                    header("HTTP/1.0 404 Not Found");
                    header("HTTP/1.1 404 Not Found");
                    header("Status: 404 Not Found");
                    exit;
                break;
        }
        
        $data = array(
            'error' => $this->error,
            'info' => $this->info,
            'cookies' => $this->cookies,
            'result_headers' => $this->result_headers,
            'meta_tags' => $this->meta_tags,
            'result_head' => $this->result_head,
            'result_body' => $this->result_body
            
        );

        $data = json_encode($data);
        $data = base64_encode($data);
        echo $data;
    }
    
    public function getServer($sUrl, $type, $url, $postdata=false, $header=false) {
        $data = array(
            'type' => $type,
            'url' => $url,
            'postdata' => $postdata,
            'header' => $header,
            'cookies' => $this->cookies,
            'config' => $this->config
        );
        $data = json_encode($data);
        $data = base64_encode($data);
        
        $config = $this->config;
        $this->config['meta'] = false;
        $this->config['encoding'] = false;

        $this->post($sUrl, array('request' => $data));
        $this->config = $config;
        
        if (!empty($this->result_body)) {
            $request = $this->result_body;
            $request = base64_decode($request);
            $request = json_decode($request, true);

            $this->error = $request['error'];
            $this->info = $request['info'];
            $this->cookies = $request['cookies'];
            $this->result_headers = $request['result_headers'];
            $this->meta_tags = $request['meta_tags'];
            $this->result_head = $request['result_head'];
            $this->result_body = $request['result_body'];
        }
    }
    
    //==========================================================================
    public function ajaxGet($url, $header = array()) {
        return $this->get($url, $header, 'ajax');
    }
    
    public function jsonGet($url, $assoc=false, $header = array()) {
        return $this->get($url, $header, 'json'. ($assoc ? ':array' : ''));
    }
    
    public function ajaxJsonGet($url, $assoc=false, $header = array()) {
        return $this->get($url, $header, 'ajax json'. ($assoc ? ':array' : ''));
    }

    public function xmlGet($url, $header = array()) {
        return $this->get($url, $header, 'xml');
    }
    
    public function ajaxXmlGet($url, $header = array()) {
        return $this->get($url, $header, 'ajax xml');
    }
    //==========================================================================
    public function ajaxPost($url, $postdata = null, $header = array()){
        return $this->post($url, $postdata, $header, 'ajax');
    }
    
    public function jsonPost($url, $assoc = false, $postdata = null, $header = array()){
        return $this->post($url, $postdata, $header, 'json'. ($assoc ? ':array' : ''));
    }
    
    public function ajaxJsonPost($url, $assoc = false, $postdata = null, $header = array()) {
        return $this->post($url, $postdata, $header, 'ajax json'. ($assoc ? ':array' : ''));
    }
    
    public function xmlPost($url, $postdata = null, $header = array()){
        return $this->post($url, $postdata, $header, 'xml');
    }
    
    public function ajaxXmlPost($url, $postdata, $header = array()) {
        return $this->post($url, $postdata, $header, 'ajax xml');
    }
}

if (miniCurl::$Server === true && !empty($_POST['request'])) {
    $inCurl = new miniCurl();
    $inCurl->startServer();
}
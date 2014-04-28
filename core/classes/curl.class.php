<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.2                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2013 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

/**
 * Класс обертка для методов CURL
 * 
 * @author DS Soft <support@ds-soft.ru>
 * @version 1.2.0
 */
class miniCurl{
    public static $Server = false;
    
    private $key = '';
    private $ch=null;
    
    public $error = '';
    public $info = '';
    public $config = array();
    public $cookies = array();
    
    private $result_encoding = '';
    private $current_url = '';
    
    public $header = false;
    
    private $result = '';
    
    public $result_headers = array();
    public $meta_tags = array();
    public $result_head = '';
    public $result_body = '';
    
    public function __construct($cfg=array()){
        if (!function_exists('curl_setopt') || !function_exists('curl_init')){
            return self::echoError('Библиотека CURL не установлена');
        }
        $this->config = array_merge(self::getDefaultConfig(), $cfg);
        $this->ch = curl_init();
        if (!$this->ch){
            $this->error = curl_error($this->ch);
            return;
        }
        $this->set_option(CURLOPT_HEADER, $this->config['header']);
        $this->set_option(CURLOPT_RETURNTRANSFER, $this->config['return_transfer']);
        $this->set_option(CURLOPT_FOLLOWLOCATION, $this->config['follow_location']);
        $this->set_option(CURLOPT_CONNECTTIMEOUT, $this->config['connect_timeout']);
        $this->set_option(CURLOPT_USERAGENT, $this->config['user_agent']);
    }
    
    public function __destruct(){
        if ($this->ch){
            curl_close($this->ch);
            $this->ch = null;
        }
    }
    
    public function reInit($cfg=array()){
        if ($this->ch){
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
        if (!$this->ch){
            $this->error = curl_error($this->ch);
            return;
        }
        $this->set_option(CURLOPT_HEADER, $this->config['header']);
        $this->set_option(CURLOPT_RETURNTRANSFER, $this->config['return_transfer']);
        $this->set_option(CURLOPT_FOLLOWLOCATION, $this->config['follow_location']);
        $this->set_option(CURLOPT_CONNECTTIMEOUT, $this->config['connect_timeout']);
        $this->set_option(CURLOPT_USERAGENT, $this->config['user_agent']);
    }
    
    private static function getDefaultConfig(){
        return array(
            'header' => 1,
            'return_transfer' => 1,
            'follow_location' => 1,
            'connect_timeout' => 60,
            'user_agent' => 'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20100101 Firefox/12.0',
            'verify_host' => 0,
            'verify_peer' => 0,
            'meta' => false,
            'encoding' => false,
            'default_encoding' => 'utf-8',
            'proxy' =>'',
            'proxy_type' => CURLPROXY_HTTP,
            'proxy_user' => '',
            'proxy_password' => '',
            'externalIp' => false
        );
    }
    
    public function set_option($option, $value){
        if (!curl_setopt($this->ch, $option, $value)){
            $this->error = curl_error($this->ch);
            return false;
        }
        return true;
    }
    
    private function setProxy(){
        if (!empty($this->config['proxy'])){
            $this->set_option(CURLOPT_PROXY, $this->config['proxy']);
            $this->set_option(CURLOPT_PROXYTYPE, $this->config['proxy_type']);
            if (!empty($this->config['proxy_user']) and !empty($this->config['proxy_password'])){
                $this->set_option(CURLOPT_PROXYUSERPWD, $this->config['proxy_user'] .':'. $this->config['proxy_password']);
            }
        }
    }

    private function setInterface(){
        if (!empty($this->config['interface'])){
            curl_setopt($this->ch, CURLOPT_INTERFACE, $this->config['interface']);
        }
        return true;
    }
    
    private function https(){
        $scheme = parse_url($this->current_url,PHP_URL_SCHEME);
        $scheme = strtolower($scheme);
        if ($scheme == 'https'){
            $this->set_option(CURLOPT_SSL_VERIFYHOST, $this->config['verify_host']);
            $this->set_option(CURLOPT_SSL_VERIFYPEER, $this->config['verify_peer']);
        }
    }
    
    private static function echoError($msg){
        if (class_exists('cmsCore')){
            cmsCore::addsessionmessage($msg, 'error');
        }else{
            echo $msg;
        }
        return false;
    }
    
    public function get($url, $header = ''){
        $this->current_url = $url;
        if (!$this->current_url) return self::echoError('Не указан URL');
        $this->header = $header;
        $this->set_option(CURLOPT_URL, $this->current_url);
        $this->set_option(CURLOPT_POST, 0);
        $this->https();
        return $this->exec();
    }
    
    public function ajaxGet($url, $header = array()){
        $header[] = 'X-Requested-With: XMLHttpRequest';
        return $this->get($url, $header);
    }
    
    public function jsonGet($url, $assoc=false, $header = array()){
        if ($this->get($url, $header)){
            return json_decode($this->result_body, $assoc);
        }
        return false;
    }
    
    public function saveFile($src, $dist, $header = ''){
        $this->get($src, $header);
        
        if (!empty($this->result_body)){
            if (!$file = fopen($dist, 'w')){
                self::echoError('Не возможно открыть(создать файл) '. $dist);
            }else{
                if (fwrite($file, $this->result_body) === false){
                    self::echoError('Не возможно произвести запись в файл '. $dist);
                }else{
                    fclose($file);
                    return true;
                }
            }
        }else{
            self::echoError('Не уалось получить файл '. $src);
        }
        
        return false;
    }

    public function post($url, $postdata = array('bla' => 'bla'), $header = ''){
        $this->current_url = $url;
        if (!$this->current_url) return self::echoError('Не указан URL');
        $this->header = $header;
        $this->set_option(CURLOPT_URL, $this->current_url);
        $this->set_option(CURLOPT_POST, 1);
        $this->set_option(CURLOPT_POSTFIELDS, $postdata);
        $this->https();
        return $this->exec();
    }
    
    public function ajaxPost($url, $postdata = null, $header = array()){
        $header[] = 'X-Requested-With: XMLHttpRequest';
        return $this->post($url, $postdata, $header);
    }
    
    public function jsonPost($url, $assoc=false, $postdata = null, $header = array()){
        if ($this->post($url, $postdata, $header)){
            return json_decode($this->result_body, $assoc);
        }
        return false;
    }
    
    public function put($url, $postdata = array('bla' => 'bla'), $header = ''){
        $this->current_url	= $url;
        if (!$this->current_url) return self::echoError('Не указан URL');
        $this->header = $header;
        $this->set_option(CURLOPT_URL, $this->url);
        $this->set_option(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->set_option(CURLOPT_POSTFIELDS, $postdata);
        return $this->exec();
    }
    
    public function delete($url, $header = ''){
        $this->current_url = $url;
        if (!$this->current_url) return self::echoError('Не указан URL');
        $this->header = $header;
        $this->set_option(CURLOPT_URL, $this->current_url);
        $this->set_option(CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $this->exec();
    }
    
    private function exec(){
        if ($this->header){
            $this->set_option(CURLOPT_HTTPHEADER, $this->header);
        }
        if ($this->cookies){
            $cookies = array();
            foreach ($this->cookies as $k => $v){
                $cookies[] = $k .'='. $v;
            }
            $this->set_option(CURLOPT_COOKIE, implode('; ', $cookies));
        }
        $this->setProxy();
        $this->setInterface();
        
        $this->result = curl_exec($this->ch);
        if ($this->result == false){
            $this->error = curl_error($this->ch);
            return false;
        }
        $this->info = curl_getinfo($this->ch);
        $this->processHeaders();
        $this->processBody();
        return true;
    }
    
    private function processHeaders(){
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
                $this->result_headers[$key] = $value;
            }
        }
    }
    
    private function processCookies($string){
        $cookie	= explode(';',$string);
        $cookie = explode('=',$cookie[0]);
        
        $cookie[0] = trim($cookie[0]);
        $cookie[1] = trim($cookie[1]);
        
        if ($cookie[1] == 'DELETED' or $cookie[1] == 'deleted'){
            unset($this->cookies[$cookie[0]]);
        }else{
            $this->cookies[$cookie[0]] = $cookie[1];
        }
    }
    
    private function processContentType($string){
        $pos = strpos($string,'charset');
        if ($pos !== false) {
            $endpos = strpos($string,';',$pos);
            if ($endpos === false) {
                $charset = substr($string,$pos);
            }else{
                $length = $endpos - $pos;
                $charset = substr($string,$pos,$length);
            }
            list(,$this->result_encoding) = explode('=',$charset,2);
        }
        return true;
    }
    
    private function processBody(){
        if ($this->config['meta'] === true and preg_match('#<hea[^>]+>(.*)</head.+?<bod[^>]+>(.*)</body>#is', $this->result, $html)){
            $this->result_head = $html[1];
            $this->result_body = $html[2];
        }else{
            $this->result_body = substr($this->result, $this->info['header_size']);
        }
        if (!$this->result_encoding and $this->config['encoding'] === true){
            if (preg_match("#<meta\b[^<>]*?\bcontent=['\"]?text/html;\s*charset=([^>\s\"']+)['\"]?#is", (empty($this->result_head) ? $this->result_body : $this->result_head), $match)) {
                $this->result_encoding = strtoupper($match[1]);
            }
        }
        if ($this->config['encoding'] !== false) $this->processEncoding();
        if ($this->config['meta'] === true) $this->processMetaSearch();
    }
    
    private function processEncoding(){
        if ($this->config['encoding'] !== true){
            $this->result_encoding = $this->config['encoding'];
        }
        if ($this->result_encoding != $this->default_encoding){
            $this->result_body = iconv($this->result_encoding, $this->default_encoding, $this->result_body);
            if ($this->config['meta'] === true){
                $this->result_head = iconv($this->result_encoding, $this->default_encoding, $this->result_head);
            }
        }
        if ($this->config['meta'] === true){
            $this->result_body = preg_replace('#\s+#is', ' ', $this->result_body);
            $this->result_head = preg_replace('#\s+#is', ' ', $this->result_head);
        }
    }
    
    private function processMetaSearch(){
        $this->meta_tags = array();
        if (!empty($this->result_head)){
            preg_match_all('#<meta(.+?)>#is', $this->result_head, $matches);
            foreach ($matches[1] as $val){
                preg_match_all('#([a-z0-9\-\_]+)="([^"]+)"#is', trim($val), $match);
                $name = ''; $item = array();
                foreach($match[1] as $k=>$v){
                    if ($v == 'name' or $v == 'property' or $v == 'itemprop'){
                        $name = $match[2][$k];
                    }else{
                        $item[$v] = $match[2][$k];
                    }
                }
                if (!empty($name)){
                    if (!isset($this->meta_tags[$name])){
                        $this->meta_tags[$name] = array();
                    }
                    $this->meta_tags[$name][] = $item;
                }else{
                    $this->meta_tags[] = $item;
                }
            }
        }
    }
    
    public function startServer(){
        if (!empty($this->key) and $this->key != md_5($_POST['key'])){
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
        
        switch ($request['type']){
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
    
    public function getServer($sUrl, $type, $url, $postdata=false, $header=false){
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
        
        if (!empty($this->result_body)){
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
}

//if (miniCurl::$Server === true and !empty($_POST['request'])){
//    $inCurl = new miniCurl();
//    $inCurl->startServer();
//}

?>
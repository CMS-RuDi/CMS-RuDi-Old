<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class rudiCache {
    private $cacheTypes = array('file', 'memcached');
    private $cache_class;
    
    public function __construct() {
        $cache_class = cmsCore::c('config')->cache_type;
        
        if (empty($cache_class) || !in_array($cache_class, $this->cacheTypes)) {
            $cache_class = 'file';
        }
        
        if ($cache_class == 'memcached' && !class_exists('Memcached')) {
            $cache_class = 'file';
        }
        
        $cache_class = 'rudiCache_'. $cache_class;
        
        $this->cache_class = new $cache_class();
    }
    
    /**
     * Возвращает закэшированные данные, если таковые имеются и время жизни кэша
     * не прошло, иначе возвращает false
     * @param string $component
     * @param string|int $target_id
     * @param string $target
     * @param int|array $cachetime
     * @return mixed
     */
    public function get($component, $target_id, $target='', $cachetime=false) {
        return $this->cache_class->get($component, $target_id, $target, $this->getCacheTime($cachetime));
    }
    
    /**
     * Сохраняет данные в кэш
     * @param mixed $data
     * @param string $component
     * @param string|int $target_id
     * @param string $target
     * @return boolean
     */
    public function set($data, $component, $target_id, $target='', $cachetime=false) {
        return $this->cache_class->set($data, $component, $target_id, $target, $this->getCacheTime($cachetime));
    }
    
    /**
     * Удаляет закэшированные данные для определнного источника
     * @param string $component
     * @param string|int $target_id
     * @param string $target
     * @return boolean
     */
    public function remove($component, $target_id, $target='') {
        return $this->cache_class->remove($component, $target_id, $target);
    }
    
    /**
     * Удаляет все закэшированные данные или группу таких данных
     * @param string $component
     * @param string $target
     * @return boolean
     */
    public function clear($component=false, $target=false, $only_old=false) {
        return $this->cache_class->clear($component, $target, $only_old);
    }
    
    private function getCacheTime($cachetime) {
        $cachetime = $cachetime ? $cachetime : cmsCore::c('config')->cache_time;
        
        if (is_array($cachetime)) {
            switch ($cachetime[1]) {
                case 'MINUTE':
                    $cachetime = $cachetime[0]*60;
                case 'HOUR':
                    $cachetime = $cachetime[0]*3600;
                case 'DAY':
                    $cachetime = $cachetime[0]*3600*24;
                case 'MONTH':
                    $cachetime = $cachetime[0]*3600*24*30;
                default :
                    $cachetime = 1800;
            }
        }
        
        return $cachetime;
    }
}

class rudiCache_file {
    public function set($data, $component, $target_id, $target='', $cachetime=false) {
        if (!file_exists(PATH .'/cache/'. $component .'/'. (!empty($target) ? $target .'/' : ''))) {
            mkdir(PATH .'/cache/'. $component .'/'. (!empty($target) ? $target .'/' : ''), 0777, true);
        }
        
        file_put_contents(PATH .'/cache/'. $component .'/'. (!empty($target) ? $target .'/' : '') . md5($target_id) .'.cache', serialize(array('data' => $data, 'time' => $cachetime)));
    }
    
    public function get($component, $target_id, $target='', $cachetime=false) {
        $filename = PATH .'/cache/'. $component .'/'. (!empty($target) ? $target .'/' : '') . md5($target_id) .'.cache';
        
        if (file_exists($filename)) {
            $time = filemtime($filename);
            
            if (time()-$time <= $cachetime) {
                $data = unserialize(file_get_contents($filename));
                return $data['data'];
            } else {
                $this->remove($component, $target_id, $target);
            }
        }
        
        return false;
    }
    
    public function remove($component, $target_id, $target='') {
        $filename = PATH .'/cache/'. $component .'/'. (!empty($target) ? $target .'/' : '') . md5($target_id) .'.cache';
        
        if (file_exists($filename)) {
            return unlink($filename);
        }
        
        return true;
    }
    
    public function clear($component=false, $target=false, $only_old=false) {
        $dir = PATH .'/cache/'. (!empty($component) ? $component .'/'. (!empty($target) ? $target .'/' : '') : '');
        return $this->clearDir($dir, $only_old);
    }
    
    private function clearDir($dir, $only_old) {
        $handle = opendir($dir);
        
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path = $dir .'/'. $file;
                
                if (is_dir($path)) {
                    $this->clearDir($path, $only_old);
                    if ($only_old === false) {
                        rmdir($path);
                    }
                } else {
                    if ($only_old === false) {
                        unlink($path);
                    } else {
                        $data = unserialize(file_get_contents($path));
                        $time = filemtime($path);
                        
                        if (time()-$time > $data['time']) {
                            unlink($path);
                        }
                    }
                }
            }
        }
        
        closedir($handle);

        return true;
    }
}

class rudiCache_memcached {
    private $memcached;
    
    public function __construct() {
        $this->memcached = new Memcached();
        $this->memcached->addServer(cmsCore::c('config')->memcached_host, cmsCore::c('config')->memcached_port);
    }
    
    public function set($data, $component, $target_id, $target, $cachetime) {
        $key = cmsCore::strToURL(cmsCore::c('config')->host) .'_'. $component .'_'. (!empty($target) ? $target .'_' : '') . md5($target_id);
        
        if ($this->memcached->get($key) !== false) {
            $this->memcached->replace($key, $data, $cachetime);
        } else {
            $this->memcached->set($key, $data, $cachetime);
        }
    }
    
    public function get($component, $target_id, $target, $cachetime) {
        $key = cmsCore::strToURL(cmsCore::c('config')->host) .'_'. $component .'_'. (!empty($target) ? $target .'_' : '') . md5($target_id);
        
        $data = $this->memcached->get($key);
        
        if ($data !== false) {
            return $data;
        }
        
        return false;
    }
    
    public function remove($component, $target_id, $target='') {
        $key = cmsCore::strToURL(cmsCore::c('config')->host) .'_'. $component .'_'. (!empty($target) ? $target .'_' : '') . md5($target_id);
        
        return $this->memcached->delete($key);
    }
    
    public function clear($component=false, $target=false, $only_old=false) {
        if ($only_old) { return true; }
        
        $prefix = cmsCore::strToURL(cmsCore::c('config')->host) .'_'. $component .'_'. (!empty($target) ? $target .'_' : '');
        $prefix_length = strlen($prefix);
        
        $results = $this->memcached->fetchAll();
        foreach ($results as $result) {
            if (substr($result['key'], 0, $prefix_length) == $prefix) {
                $this->memcached->delete($result['key']);
            }
        }
        
        return true;
    }
}
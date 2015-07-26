<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

class phpTpl
{
    private $tpl_vars = array();
    private $tpl_file;
    private static $cycle_vars;

    public function __construct($tpl_file, $template)
    {
        $this->tpl_file = $tpl_file;
        $this->tpl_vars['template'] = $template;
    }

    /**
     * Показывает файл шаблона
     */
    public function display()
    {
        global $_LANG;
        
        $is_ajax  = cmsCore::isAjax();
        $user_id  = cmsCore::c('user')->id;
        $is_admin = cmsCore::c('user')->is_admin;
        
        $inConf   = cmsCore::c('config')->getConfig();
        
        extract($this->tpl_vars);

        include($this->tpl_file);
    }
    
    /**
     * Возвращает результат выполнения файла шаблона без вывода в браузер
     */
    public function fetch()
    {
        ob_start();
            $this->display();
        return ob_get_clean();
    }
    
    /**
     * Добавляет переменную в набор
     */
    public function assign($tpl_var, $value)
    {
        if (is_array($tpl_var)) {
            foreach ($tpl_var as $key => $val) {
                if ($key) {
                    $this->tpl_vars[$key] = $val;
                }
            }
        }
        else
        {
            if ($tpl_var) {
                $this->tpl_vars[$tpl_var] = $value;
            }
        }

        return $this;
    }
    
    ////////////////////////////////////////////////////////////////////////////
    
    public function truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
    {
        if ($length == 0) { return ''; }

        if (mb_strlen($string) > $length) {
            $length -= min($length, mb_strlen($etc));
            
            if (!$break_words && !$middle) {
                $string = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($string, 0, $length+1));
            }
            
            if (!$middle) {
                return mb_substr($string, 0, $length) . $etc;
            }
            else
            {
                return mb_substr($string, 0, $length/2) . $etc . mb_substr($string, -$length/2);
            }
        }
        else
        {
            return $string;
        }
    }
    
    public function rating($rating)
    {
        if ($rating == 0) {
            $html = '<span style="color:gray;">0</span>';
	}
        elseif ($rating > 0)
        {
            $html = '<span style="color:green">+'.$rating.'</span>';
	}
        else
        {
            $html = '<span style="color:red">'.$rating.'</span>';
	}
        
	return $html;
    }
    
    public function escape($string, $esc_type = 'html', $char_set = 'UTF-8')
    {
        switch ($esc_type) {
            case 'html':
                return htmlspecialchars($string, ENT_QUOTES, $char_set);
            case 'htmlall':
                return htmlentities($string, ENT_QUOTES, $char_set);
            case 'url':
                return rawurlencode($string);
            case 'urlpathinfo':
                return str_replace('%2F','/',rawurlencode($string));
            case 'quotes':
                // escape unescaped single quotes
                return preg_replace("%(?<!\\\\)'%u", "\\'", $string);
            case 'hex':
                // escape every character into hex
                $return = '';
                for ($x=0; $x < mb_strlen($string); $x++) {
                    $return .= '%' . bin2hex($string[$x]);
                }
                return $return;
            case 'hexentity':
                $return = '';
                for ($x=0; $x < mb_strlen($string); $x++) {
                    $return .= '&#x' . bin2hex($string[$x]) . ';';
                }
                return $return;
            case 'decentity':
                $return = '';
                for ($x=0; $x < mb_strlen($string); $x++) {
                    $return .= '&#' . ord($string[$x]) . ';';
                }
                return $return;
            case 'javascript':
                // escape quotes and backslashes, newlines, etc.
                return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));
            case 'mail':
                // safe way to display e-mail address on a web page
                return str_replace(array('@', '.'),array(' [AT] ', ' [DOT] '), $string);
            case 'nonstd':
                // escape non-standard chars, such as ms document quotes
                $_res = '';
                for ($_i = 0, $_len = mb_strlen($string); $_i < $_len; $_i++) {
                    $_ord = ord(mb_substr($string, $_i, 1));
                    // non-standard char, escape it
                    if ($_ord >= 126) {
                        $_res .= '&#' . $_ord . ';';
                    }
                    else
                    {
                        $_res .= mb_substr($string, $_i, 1);
                    }
                }
               return $_res;

            default:
                return $string;
        }
    }
    
    public function spellcount($num, $one, $two, $many, $is_full = true)
    {
        return cmsCore::spellCount($num, $one, $two, $many, $is_full);
    }
    
    public function cycle($params)
    {
        $name    = (empty($params['name']))    ? 'default'                : $params['name'];
        $print   = (isset($params['print']))   ? (bool)$params['print']   : true;
        $advance = (isset($params['advance'])) ? (bool)$params['advance'] : true;
        $reset   = (isset($params['reset']))   ? (bool)$params['reset']   : false;

        if (!in_array('values', array_keys($params))) {
            if (!isset(self::$cycle_vars[$name]['values'])) {
                cmsCore::addSessionMessage("cycle: missing 'values' parameter", 'error');
                return;
            }
        }
        else
        {
            if (isset(self::$cycle_vars[$name]['values']) && self::$cycle_vars[$name]['values'] != $params['values'] ) {
                self::$cycle_vars[$name]['index'] = 0;
            }
            
            self::$cycle_vars[$name]['values'] = $params['values'];
        }

        if (isset($params['delimiter'])) {
            self::$cycle_vars[$name]['delimiter'] = $params['delimiter'];
        }
        elseif (!isset(self::$cycle_vars[$name]['delimiter']))
        {
            self::$cycle_vars[$name]['delimiter'] = ',';       
        }

        if (is_array(self::$cycle_vars[$name]['values'])) {
            $cycle_array = self::$cycle_vars[$name]['values'];
        }
        else
        {
            $cycle_array = explode(self::$cycle_vars[$name]['delimiter'],self::$cycle_vars[$name]['values']);
        }

        if (!isset(self::$cycle_vars[$name]['index']) || $reset ) {
            self::$cycle_vars[$name]['index'] = 0;
        }

        if ($print) {
            $retval = $cycle_array[self::$cycle_vars[$name]['index']];
        }
        else
        {
            $retval = null;
        }

        if ($advance) {
            if ( self::$cycle_vars[$name]['index'] >= count($cycle_array) -1 ) {
                self::$cycle_vars[$name]['index'] = 0;
            }
            else
            {
                self::$cycle_vars[$name]['index']++;
            }
        }

        return $retval;
    }
    
    public function strip_tags($string, $replace_with_space = true)
    {
        if ($replace_with_space) {
            return preg_replace('!<[^>]*?>!', ' ', $string);
        }
        else
        {
            return strip_tags($string);
        }
    }
    
    public function NoSpam($email, $filterLevel = 'normal')
    {
        $email = strrev($email);
        $email = preg_replace('[\.]', '/', $email, 1);
        $email = preg_replace('[@]', '/', $email, 1);

        if ($filterLevel == 'low') {
            $email = strrev($email);
        }

        return $email;
    }
    
    public function __set($name, $value) {
        cmsCore::c('page')->{$name} = $value;
    }
    
    public function __get($name) {
        return cmsCore::c('page')->{$name};
    }
    
    public function __call($name, $arguments) {
        return call_user_func_array(array(cmsCore::c('page'), $name), $arguments);
    }
}
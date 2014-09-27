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
 * Класс генерации форм для CMS RuDi
 *
 * Предназначен для генерации форм для настройки плагинов, модулей или любой
 * другой формы из простого массива данных
 * 
 * @author DS Soft <support@ds-soft.ru>
 * @version 0.0.2
 */
class rudi_form_generate {
    private $fields = array(
        'tabs'     => array( 'class' => 'rudi_form_tab',      'count' => 0 ),
        'fieldset' => array( 'class' => 'rudi_form_fieldset', 'count' => 0 ),
        'text'     => array( 'class' => 'rudi_form_text',     'count' => 0 ),
        'number'   => array( 'class' => 'rudi_form_number',   'count' => 0 ),
        'textarea' => array( 'class' => 'rudi_form_textarea', 'count' => 0 ),
        'select'   => array( 'class' => 'rudi_form_select',   'count' => 0 ),
        'checkbox' => array( 'class' => 'rudi_form_checkbox', 'count' => 0 ),
        'radio'    => array( 'class' => 'rudi_form_radio',    'count' => 0 ),
        'hr' => 1, 'btn_yes_no' => 1, 'img_size' => 1
    );
    private $values = array();
    
    public function requestForm($fields) {
        $data = array();
        
        foreach ($fields as $field) {
            if ($field['type'] == 'fieldset') {
                if (!empty($field['fields'])) {
                    foreach ($field['fields'] as $f) {
                        $data = $this->requestField($f, $data);
                    }
                }
            } else if ($field['type'] == 'tabs') {
                foreach ($field['tabs'] as $tab) {
                    foreach ($tab['fields'] as $f) {
                        if ($f['type'] == 'fieldset') {
                            if (!empty($f['fields'])) {
                                foreach ($f['fields'] as $f2) {
                                    $data = $this->requestField($f2, $data);
                                }
                            }
                        } else {
                            $data = $this->requestField($f, $data);
                        }
                    }
                }
            } else {
                $data = $this->requestField($field, $data);
            }
        }
        
        return $data;
    }
    
    private function requestField($field, $data) {
        if ($field['type'] == 'img_size') {
            $name = array($field['nameX'], $field['nameY']);
        } else {
            $name = array($field['name']);
        }
        
        if (!empty($name)) {
            foreach ($name as $n) {
                $rtype = 'str';
                if (mb_strstr($n, '[')) {
                    $n = str_replace('[]', '', preg_replace('#\[(.+?)\]#is', '', $n));
                    $rtype = 'array_str';
                }

                $data[$n] = cmsCore::request($n, $rtype, '');
            }
        }

        return $data;
    }

    public function generateForm($fields, $values=array(), $tpl='rudiFormGen.php') {
        ob_start();
            cmsPage::includeTemplateFile(
                'special/'. $tpl,
                array(
                    'data' => $this->getFormFields($fields, $values)
                )
            );
        return ob_get_clean();
    }
    
    public function getFormFields($fields, $values=array()) {
        $this->values = is_array($values) ? $values : array();
        
        $data = array();
        
        foreach ($fields as $field) {
            if (isset($this->fields[$field['type']])) {
                $this->fields[$field['type']]['count']++;

                $item = $this->{$field['type']}($field);
                
                if (!empty($item)) { $data[] = $item; }
            }
        }
        
        return $data;
    }
    
    private function tabs($field) {
        if (empty($field['tabs'])) { return false; }
        
        $data = array(
            'type' => $field['type'],
            'before' => '<div id="'. $this->fields['tabs']['class'] .'_'. $this->fields['tabs']['count'] .'"><ul>',
            'after' => '</div><script type="text/javascript">$(document).ready(function () { $("#'. $this->fields['tabs']['class'] .'_'. $this->fields['tabs']['count'] .'").tabs({}); });</script>',
            'fields' => array()
        );
        
        $id = 0;
        
        foreach ($field['tabs'] as $tab) {
            if (!empty($tab['fields'])) {
                $id++;

                $dat = array();
                
                foreach ($tab['fields'] as $f) {
                    $item = $this->{$f['type']}($f);
                    if (!empty($item)) { $dat[] = $item; }
                }
                
                if (!empty($dat)) {
                    $data['before'] .= '<li><a href="#'. $this->fields['tabs']['class'] .'_'. $this->fields['tabs']['count'] .'_'. $id .'">'. $tab['title'] .'</a></li>';
                    
                    $data['fields'][$id]['before'] = '<div id="'. $this->fields['tabs']['class'] .'_'. $this->fields['tabs']['count'] .'_'. $id .'">';
                    $data['fields'][$id]['after'] = '</div>';
                    
                    $data['fields'][$id]['fields'] = $dat;
                }
            }
        }
        
        $data['before'] .= '</ul>';
        
        return $data;
    }

    private function fieldset($field) {
        if (empty($field['fields'])) { return false; }
        
        $data = array(
            'type' => $field['type'],
            'before' => '<fieldset id="'. $this->fields['fieldset']['class'] .'_'. ($this->fields['fieldset']['count']++) .'" class="'. $this->fields['fieldset']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .'>',
            'after' => '</fieldset>',
            'fields' => array()
        );
        
        if (!empty($field['title'])) {
            $data['before'] .= "\n" .'<legend>'. $field['title'] .'</legend>';
        }
        
        foreach ($field['fields'] as $f) {
            if (isset($this->fields[$f['type']])) {
                $this->fields[$f['type']]['count']++;
                
                $item = $this->{$f['type']}($f);
                
                if (!empty($item)) {
                    $data['fields'][] = $item;
                }
            }
        }
        
        return $data;
    }
    
    private function text($field) {
        if (empty($field['name'])) { return false; }
        
        return array(
            'type' => $field['type'],
            'title' => $field['title'],
            'description' => cmsCore::getArrVal($field, 'description', ''),
            'html' => '<input type="text" id="'. $this->fields['text']['class'] .'_'. $this->fields['text']['count'] .'" class="form-control '. $this->fields['text']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $field['name'] .'" value="'. htmlspecialchars($this->getValue($field)) .'" />'
        );
    }
    
    private function number($field) {
        if (empty($field['name'])) { return false; }
        
        return array(
            'type' => $field['type'],
            'title' => $field['title'],
            'description' => cmsCore::getArrVal($field, 'description', ''),
            'html' => '<input type="number" id="'. $this->fields['number']['class'] .'_'. $this->fields['number']['count'] .'" class="form-control '. $this->fields['number']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $field['name'] .'" value="'. htmlspecialchars($this->getValue($field)) .'" />'
        );
    }
    
    private function textarea($field) {
        if (empty($field['name'])) { return false; }
        
        return array(
            'type' => $field['type'],
            'title' => $field['title'],
            'description' => cmsCore::getArrVal($field, 'description', ''),
            'html' => '<textarea id="'. $this->fields['textarea']['class'] .'_'. $this->fields['textarea']['count'] .'" class="form-control '. $this->fields['textarea']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $field['name'] .'">'. $this->getValue($field) .'</textarea>');
    }
    
    private function select($field) {
        if (empty($field['name']) || empty($field['options'])) { return false; }
        
        $html = '<select id="'. $this->fields['select']['class'] .'_'. $this->fields['select']['count'] .'" class="form-control '. $this->fields['select']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $field['name'] .'">' ."\n";
        
        $selected = $this->getValue($field);
        if (!is_array($selected)) { $selected = array($selected); }
        
        foreach ($field['options'] as $option) {
            if (isset($option['optgroup'])) {
                $html .= '    <optgroup label="'. htmlspecialchars($option['title']) .'"'. $this->getOtherAttributes($option) .'>'. "\n";
                
                foreach ($option['options'] as $opt) {
                    $html .= '        <option value="'. htmlspecialchars($opt['value']) .'"'. (in_array($opt['value'],$selected) || isset($opt['selected']) ? ' selected="selected"' : '') .''. $this->getOtherAttributes($opt) .'>'. $opt['title'] .'</option>'. "\n";
                }
                
                $html .= '    </optgroup>'. "\n";
            } else {
                $html .= '    <option value="'. htmlspecialchars($option['value']) .'"'. (in_array($option['value'],$selected) || isset($option['selected']) ? ' selected="selected"' : '') .''. $this->getOtherAttributes($option) .'>'. $option['title'] .'</option>'. "\n";
            }
        }
        
        $html .= '</select>';
        
        return array(
            'type' => $field['type'],
            'title' => $field['title'],
            'description' => cmsCore::getArrVal($field, 'description', ''),
            'html' => $html
        );
    }
    
    private function checkbox($field) {
        if (empty($field['name'])) { return false; }
        
        return array(
            'type' => $field['type'],
            'title' => '<label for="'. $this->fields['checkbox']['class'] .'_'. $this->fields['checkbox']['count'] .'">'. $field['title'] .'</label>',
            'description' => cmsCore::getArrVal($field, 'description', ''),
            'html' => '<input type="checkbox" id="'. $this->fields['checkbox']['class'] .'_'. $this->fields['checkbox']['count'] .'" class="'. $this->fields['checkbox']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $field['name'] .'" value="'. htmlspecialchars($this->getValue($field)) .'"'. ($this->getValue($field, true) || isset($field['checked']) ? 'checked="checked"' : '') .' />'
        );
    }
    
    private function radio($field) {
        if (empty($field['name']) || empty($field['options'])) { return false; }
        
        $data = array(
            'type' => $field['type'],
            'title' => $field['title'],
            'description' => cmsCore::getArrVal($field, 'description', ''),
            'options' => array(),
            'html' => ''
        );
        
        $count = $this->fields['radio']['count'];
        
        foreach ($field['options'] as $option) {
            $checked = '';
            if ((!$this->getValue($field, true) && isset($option['checked'])) || $this->getValue($field) == $option['value']) {
                $checked = ' checked="checked"';
            }
            
            $data['options'][] = array(
                'id' => $this->fields['radio']['class'] .'_'. $count,
                'title' => $option['title'],
                'html' => '<input type="radio" id="'. $this->fields['radio']['class'] .'_'. $count .'" class="'. $this->fields['radio']['class'] .' '. cmsCore::getArrVal($option, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($option, 'style', false)) .''. $this->getOtherAttributes($option) .' name="'. $field['name'] .'" value="'. htmlspecialchars($this->getValue($option)) .'"'. $checked .' />'
            );
            
            $count++;
        }
        
        return $data;
    }
    
    private function btn_yes_no($field) {
        if (empty($field['name'])) { return false; }
        
        global $_LANG;
        
        $val = $this->getValue($field);
        
        return array(
            'type' => $field['type'],
            'title' => $field['title'],
            'description' => cmsCore::getArrVal($field, 'description', ''),
            'html' => '<div class="btn-group" data-toggle="buttons" style="vertical-align:top;"><label class="btn btn-default'. ($val ? ' active' : '') .'"><input type="radio" name="'. $field['name'] .'" checked="checked" value="1"'. ($val ? ' checked="checked"' : '') .' /> '. $_LANG['YES'] .'</label> <label class="btn btn-default'. (!$val ? ' active' : '') .'"><input type="radio" name="'. $field['name'] .'" value="0"'. (!$val ? ' checked="checked"' : '') .' /> '. $_LANG['NO'] .' </label></div>'
        );
    }
    
    private function img_size($field) {
        if (empty($field['nameX']) || empty($field['nameY'])) { return false; }
        
        return array(
            'type' => $field['type'],
            'title' => $field['title'],
            'html' => '<table width="100%"><tr><td><input type="number" class="form-control" name="'. $field['nameX'] .'" value="'. (float)$this->getValue($field, false, 'nameX', 'valueX') .'" /></td><td><span style="padding:5px;">x</span></td><td><input type="number" class="form-control" name="'. $field['nameY'] .'" value="'. (float)$this->getValue($field, false, 'nameY', 'valueY') .'" /></td></tr></table>'
        );
    }

    private function hr($field) {
        return array( 'html' => '<hr/>' );
    }
    
    //==========================================================================
    
    private function getStyle($style=false) {
        if (empty($style)) { return ''; }
        
        if (is_array($style)) {
            $tmp = array();
            
            foreach ($style as $k=>$v) {
                $tmp[] = $k .':'. $v;
            }
            
            $style = implode(';', $tmp);
        }
        
        return ' style="'. (string)$style .'"';
    }
    
    private function getOtherAttributes($field) {
        $tmp = array();
            
        foreach ($field as $k=>$v) {
            if (!in_array($k, array('title', 'fields', 'type', 'class', 'style', 'name', 'value', 'selected', 'checked')) && !is_array($v)) {
                $tmp[] = $k .'="'. (mb_substr($v, 0, 2) == 'on' ? $v : htmlspecialchars($v)) .'"';
            }
        }
        
        return (empty($tmp) ? '' : ' '. implode(' ', $tmp));
    }
    
    private function getValue($field, $return_isset=false, $name='name', $value='value') {
        $matches = array();
        
        $field[$name] = str_replace('[]', '', $field[$name]);
        
        if (preg_match_all('#\[(.+?)\]#is', $field[$name], $matches)) {
            
            $field[$name] = preg_replace('#\[(.+?)\]#is', '', $field[$name]);
            
            if (isset($this->values[$field[$name]])) {
                $val = $this->values[$field[$name]];
                $error = false;
                
                foreach ($matches[1] as $name) {
                    if (!isset($val[$name])) {
                        $error = true;
                    } else {
                        $val = $val[$name];
                    }
                }
                
                if ($error === false) {
                    return $return_isset ? true : $val;
                }
            }
        } else if (isset($this->values[$field[$name]])) {
            return $return_isset ? true : $this->values[$field[$name]];
        }
        
        return $return_isset ? false : cmsCore::getArrVal($field, $value, '');
    }
    
}
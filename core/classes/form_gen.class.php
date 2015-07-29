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
 * Класс генерации форм для CMS RuDi
 *
 * Предназначен для генерации форм для настройки плагинов, модулей или любой
 * другой формы из простого массива данных
 * 
 * @author DS Soft <support@ds-soft.ru>
 * @version 0.0.3
 */
class rudi_form_generate
{
    private $fields = array(
        'tabs'       => array( 'class' => 'rudi_form_tab',      'count' => 0 ),
        'fieldset'   => array( 'class' => 'rudi_form_fieldset', 'count' => 0 ),
        'text'       => array( 'class' => 'rudi_form_text',     'count' => 0 ),
        'number'     => array( 'class' => 'rudi_form_number',   'count' => 0 ),
        'textarea'   => array( 'class' => 'rudi_form_textarea', 'count' => 0 ),
        'select'     => array( 'class' => 'rudi_form_select',   'count' => 0 ),
        'checkbox'   => array( 'class' => 'rudi_form_checkbox', 'count' => 0 ),
        'radio'      => array( 'class' => 'rudi_form_radio',    'count' => 0 ),
        'hr'         => 1,
        'btn_yes_no' => 1,
        'img_size'   => 1,
        'ns_list'    => 1,
        'dir_list'   => 1,
        'file_list'  => 1
    );
    private $values = array();
    private $name_prefix = '';

    public function requestForm($fields, $name_prefix = '')
    {
        $data = array();
        $this->name_prefix = empty($name_prefix) ? '' : $name_prefix .'_';
        
        foreach ($fields as $field) {
            if ($field['type'] == 'fieldset') {
                if (!empty($field['fields'])) {
                    foreach ($field['fields'] as $f) {
                        $this->requestField($f, $data);
                    }
                }
            }
            elseif ($field['type'] == 'tabs')
            {
                foreach ($field['tabs'] as $tab) {
                    foreach ($tab['fields'] as $f) {
                        if ($f['type'] == 'fieldset') {
                            if (!empty($f['fields'])) {
                                foreach ($f['fields'] as $f2) {
                                    $this->requestField($f2, $data);
                                }
                            }
                        }
                        else
                        {
                            $this->requestField($f, $data);
                        }
                    }
                }
            }
            else
            {
                $this->requestField($field, $data);
            }
        }
        
        $this->name_prefix = '';
        
        return $data;
    }
    
    private function requestField($field, &$data)
    {
        if ($field['type'] == 'img_size') {
            $name = array($field['nameX'], $field['nameY']);
        }
        else
        {
            $name = array($field['name']);
        }
        
        if (!empty($name)) {
            foreach ($name as $n) {
                $rtype = 'str';
                if (mb_strstr($n, '[')) {
                    $n = str_replace('[]', '', preg_replace('#\[(.+?)\]#is', '', $n));
                    $rtype = 'array_str';
                }

                $data[$n] = cmsCore::request($this->name_prefix . $n, isset($field['request_type']) ? $field['request_type'] : $rtype, '');
            }
        }
    }

    public function generateForm($fields, $values = array(), $tpl = 'rudiFormGen', $name_prefix = '', $insert_token = true)
    {
        return cmsCore::c('page')->initTemplate('special/'. $tpl)->
                assign('data', $this->getFormFields($fields, $values, $name_prefix))->
                assign('insert_token', $insert_token)->
                fetch();
    }
    
    public function getFormFields($fields, $values = array(), $name_prefix = '')
    {
        $this->name_prefix = empty($name_prefix) ? '' : $name_prefix .'_';

        $this->values = is_array($values) ? $values : array();
        
        $data = array();
        
        foreach ($fields as $field) {
            if (isset($this->fields[$field['type']])) {
                $this->fields[$field['type']]['count']++;

                $item = $this->{$field['type']}($field);
                
                if (!empty($item)) { $data[] = $item; }
            }
        }
        
        $this->name_prefix = '';
        
        return $data;
    }
    
    /**
     * Генерирует html код табов
     * @param array $field
     * @return boolean|array 
     */
    private function tabs($field)
    {
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
                    $data['before'] .= '<li><a href="#'. $this->fields['tabs']['class'] .'_'. $this->fields['tabs']['count'] .'_'. $id .'">'. $this->getTD($tab, 'title', 'tab', $id) .'</a></li>';
                    
                    $data['fields'][$id]['before'] = '<div id="'. $this->fields['tabs']['class'] .'_'. $this->fields['tabs']['count'] .'_'. $id .'">';
                    $data['fields'][$id]['after'] = '</div>';
                    
                    $data['fields'][$id]['fields'] = $dat;
                }
            }
        }
        
        $data['before'] .= '</ul>';
        
        return $data;
    }

    /**
     * Генерирует html код <fieldset>
     * @param array $field
     * @return boolean|array
     */
    private function fieldset($field)
    {
        if (empty($field['fields'])) { return false; }
        
        $data = array(
            'type'   => $field['type'],
            'before' => '<fieldset id="'. $this->fields['fieldset']['class'] .'_'. ($this->fields['fieldset']['count']++) .'" class="'. $this->fields['fieldset']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .'>',
            'after'  => '</fieldset>',
            'fields' => array()
        );
        
        if (!empty($field['title'])) {
            $data['before'] .= "\n" .'<legend>'. $this->getTD($field, 'title', 'fieldset', $this->fields['fieldset']['count']-1) .'</legend>';
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
    
    /**
     * Генерирует html код поля <input type="text"/>
     * @param array $field
     * @return boolean|array
     */
    private function text($field)
    {
        if (empty($field['name'])) { return false; }
        
        return array(
            'type'  => $field['type'],
            'title' => $this->getTD($field, 'title', 'text', $field['name']),
            'description' => $this->getTD($field, 'description', 'text', $field['name']),
            'html' => '<input type="text" id="'. $this->fields['text']['class'] .'_'. $this->fields['text']['count'] .'" class="form-control '. $this->fields['text']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $this->name_prefix . $field['name'] .'" value="'. htmlspecialchars($this->getValue($field)) .'" />'
        );
    }
    
    /**
     * Генерирует html код поля <input type="number"/>
     * @param array $field
     * @return boolean|array
     */
    private function number($field)
    {
        if (empty($field['name'])) { return false; }
        
        return array(
            'type' => $field['type'],
            'title' => $this->getTD($field, 'title', 'number', $field['name']),
            'description' => $this->getTD($field, 'description', 'number', $field['name']),
            'html' => '<input type="number" id="'. $this->fields['number']['class'] .'_'. $this->fields['number']['count'] .'" class="form-control '. $this->fields['number']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $this->name_prefix . $field['name'] .'" value="'. htmlspecialchars($this->getValue($field)) .'" />'
        );
    }
    
    /**
     * Генерирует html код поля <textarea>
     * @param array $field
     * @return boolean|array
     */
    private function textarea($field)
    {
        if (empty($field['name'])) { return false; }
        
        return array(
            'type' => $field['type'],
            'title' => $this->getTD($field, 'title', 'textarea', $field['name']),
            'description' => $this->getTD($field, 'description', 'textarea', $field['name']),
            'html' => '<textarea id="'. $this->fields['textarea']['class'] .'_'. $this->fields['textarea']['count'] .'" class="form-control '. $this->fields['textarea']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $this->name_prefix . $field['name'] .'">'. $this->getValue($field) .'</textarea>');
    }
    
    /**
     * Генерирует html код поля <select>
     * @param array $field
     * @return boolean|array
     */
    private function select($field)
    {
        if (empty($field['name']) || empty($field['options'])) {
            return false;
        }
        
        $html = '<select id="'. $this->fields['select']['class'] .'_'. $this->fields['select']['count'] .'" class="form-control '. $this->fields['select']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $this->name_prefix . $field['name'] .'">' ."\n";
        
        $selected = $this->getValue($field);
        
        if (!is_array($selected)) {
            $selected = array($selected);
        }
        
        $optgroup_c = $option_c = 0;
        
        foreach ($field['options'] as $option) {
            if (isset($option['optgroup'])) {
                $html .= '    <optgroup label="'. htmlspecialchars($this->getTD($option, 'title', 'select_optgroup_'. $field['name'], $optgroup_c)) .'"'. $this->getOtherAttributes($option) .'>'. "\n";
                
                foreach ($option['options'] as $opt) {
                    $html .= '        <option value="'. htmlspecialchars($opt['value']) .'"'. (in_array($opt['value'],$selected) || isset($opt['selected']) ? ' selected="selected"' : '') .''. $this->getOtherAttributes($opt) .'>'. $this->getTD($opt, 'title', 'select_option_'. $field['name'], $option_c) .'</option>'. "\n";
                    $option_c++;
                }
                
                $html .= '    </optgroup>'. "\n";
                
                $optgroup_c++;
            }
            else
            {
                $html .= '    <option value="'. htmlspecialchars($option['value']) .'"'. (in_array($option['value'],$selected) || isset($option['selected']) ? ' selected="selected"' : '') .''. $this->getOtherAttributes($option) .'>'. $this->getTD($option, 'title', 'select_option_'. $field['name'], $option_c) .'</option>'. "\n";
                $option_c++;
            }
        }
        
        $html .= '</select>';
        
        return array(
            'type' => $field['type'],
            'title' => $this->getTD($field, 'title', 'select', $field['name']),
            'description' => $this->getTD($field, 'description', 'select', $field['name']),
            'html' => $html
        );
    }
    
    /**
     * Генерирует html код поля checkbox
     * @param array $field
     * @return boolean|array
     */
    private function checkbox($field)
    {
        if (empty($field['name'])) {
            return false;
        }
        
        return array(
            'type' => $field['type'],
            'title' => '<label for="'. $this->fields['checkbox']['class'] .'_'. $this->fields['checkbox']['count'] .'">'. $this->getTD($field, 'title', 'checkbox', $field['name']) .'</label>',
            'description' => $this->getTD($field, 'description', 'checkbox', $field['name']),
            'html' => '<input type="checkbox" id="'. $this->fields['checkbox']['class'] .'_'. $this->fields['checkbox']['count'] .'" class="'. $this->fields['checkbox']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $this->name_prefix . $field['name'] .'" value="'. htmlspecialchars($this->getValue($field)) .'"'. ($this->getValue($field, true) || isset($field['checked']) ? 'checked="checked"' : '') .' />'
        );
    }
    
    /**
     * Генерирует html код поля радио группы опций или одной радио кнопки
     * @param array $field
     * @return boolean|array
     */
    private function radio($field)
    {
        if (empty($field['name']) || empty($field['options'])) {
            return false;
        }
        
        $data = array(
            'type' => $field['type'],
            'title' => $this->getTD($field, 'title', 'radio', $field['name']),
            'description' => $this->getTD($field, 'description', 'radio', $field['name']),
            'options' => array(),
            'html' => ''
        );
        
        $count = $this->fields['radio']['count'];
        
        $option_c = 0;
        
        foreach ($field['options'] as $option) {
            $checked = '';
            if ((!$this->getValue($field, true) && isset($option['checked'])) || $this->getValue($field) == $option['value']) {
                $checked = ' checked="checked"';
            }
            
            $data['options'][] = array(
                'id' => $this->fields['radio']['class'] .'_'. $count,
                'title' => $this->getTD($option, 'title', 'radio_option_'. $field['name'], $option_c),
                'html' => '<input type="radio" id="'. $this->fields['radio']['class'] .'_'. $count .'" class="'. $this->fields['radio']['class'] .' '. cmsCore::getArrVal($option, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($option, 'style', false)) .''. $this->getOtherAttributes($option) .' name="'. $this->name_prefix . $field['name'] .'" value="'. htmlspecialchars($this->getValue($option)) .'"'. $checked .' />'
            );
            
            $count++;
            $option_c++;
        }
        
        return $data;
    }
    
    /**
     * Генерирует html код поля с кнопками ДА/НЕТ
     * @param array $field
     * @return boolean|array
     */
    private function btn_yes_no($field)
    {
        if (empty($field['name'])) {
            return false;
        }
        
        global $_LANG;
        
        $val = $this->getValue($field);
        
        return array(
            'type' => $field['type'],
            'title' => $this->getTD($field, 'title', 'btn_yes_no', $field['name']),
            'description' => $this->getTD($field, 'description', 'btn_yes_no', $field['name']),
            'html' => '<div class="btn-group" data-toggle="buttons" style="vertical-align:top;"><label class="btn btn-default'. ($val ? ' active' : '') .'"><input type="radio" name="'. $this->name_prefix . $field['name'] .'" value="1"'. ($val ? ' checked="checked"' : '') .' /> '. $_LANG['YES'] .'</label> <label class="btn btn-default'. (!$val ? ' active' : '') .'"><input type="radio" name="'. $this->name_prefix . $field['name'] .'" value="0"'. (!$val ? ' checked="checked"' : '') .' /> '. $_LANG['NO'] .' </label></div>'
        );
    }
    
    /**
     * Генерирует html код поля для выбора ширины и высоты изображения
     * @param array $field
     * @return boolean|array
     */
    private function img_size($field)
    {
        if (empty($field['nameX']) || empty($field['nameY'])) {
            return false;
        }
        
        return array(
            'type' => $field['type'],
            'title' => $this->getTD($field, 'title', 'img_size', $field['name']),
            'html' => '<table width="100%"><tr><td><input type="number" class="form-control" name="'. $this->name_prefix . $field['nameX'] .'" value="'. (float)$this->getValue($field, false, 'nameX', 'valueX') .'" /></td><td><span style="padding:5px;">x</span></td><td><input type="number" class="form-control" name="'. $this->name_prefix . $field['nameY'] .'" value="'. (float)$this->getValue($field, false, 'nameY', 'valueY') .'" /></td></tr></table>'
        );
    }
    
    /**
     * Генерирует html код поля <select> из таблицы в базе данных с вложенными 
     * множествами
     * @param array $field Массив параметров, вдобавок к стандартным (name, title...)
     * можно указать еще и параметры для метода ядра getListItemsNS (table,differ,
     * need_field,rootid,no_padding - указание параметра table обязательно остальные нет)
     * @return boolean|array
     */
    private function ns_list($field)
    {
        if (empty($field['name']) || empty($field['table'])) {
            return false;
        }
        
        $this->fields['select']['count']++;
        
        $selected = $this->getValue($field);
        
        $attr_field = $field;
        unset($attr_field['table'], $attr_field['differ'], $attr_field['need_field'], $attr_field['rootid'], $attr_field['no_padding']);
        
        $html = '<select id="'. $this->fields['select']['class'] .'_'. $this->fields['select']['count'] .'" class="form-control '. $this->fields['select']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $this->name_prefix . $field['name'] .'">' ."\n";
        
        $html .= cmsCore::getInstance()->getListItemsNS(
            $field['table'],
            $selected,
            cmsCore::getArrVal($field, 'differ', ''),
            cmsCore::getArrVal($field, 'need_field', ''),
            cmsCore::getArrVal($field, 'rootid', 0),
            cmsCore::getArrVal($field, 'no_padding')
        );
        
        $html .= '</select>';
        
        return array(
            'type' => $field['type'],
            'title' => $this->getTD($field, 'title', 'ns_list', $field['name']),
            'description' => $this->getTD($field, 'description', 'ns_list', $field['name']),
            'html' => $html
        );
    }
    
    private function genList($field, $type = 'dirs')
    {
        if (empty($field['name']) || empty($field['path'])) {
            return false;
        }
        
        $field['path'] = rtrim($field['path'], '/');
        
        if (!file_exists(PATH . $field['path']) || !is_dir(PATH . $field['path'])) {
            return false;
        }
        
        $this->fields['select']['count']++;
        
        $selected = $this->getValue($field);
        if (!is_array($selected)) {
            $selected = array($selected);
        }
        
        $attr_field = $field;
        unset($attr_field['path']);
        
        $html = '<select id="'. $this->fields['select']['class'] .'_'. $this->fields['select']['count'] .'" class="form-control '. $this->fields['select']['class'] .' '. cmsCore::getArrVal($field, 'class', '') .'"'. $this->getStyle(cmsCore::getArrVal($field, 'style', false)) .''. $this->getOtherAttributes($field) .' name="'. $this->name_prefix . $field['name'] .'">' ."\n";
        
        if ($type == 'dirs') {
            $items = cmsCore::getDirsList($field['path']);
        }
        elseif ($type == 'files')
        {
            $items = cmsCore::getDirFilesList($field['path']);
        }
        
        if (!empty($items)) {
            foreach ($items as $item) {
                $html .= '    <option value="'. htmlspecialchars($item) .'"'. (in_array($item, $selected) ? ' selected="selected"' : '') .'>'. $item .'</option>'. "\n";
            }
        }
        
        $html .= '</select>';
    }
    
    /**
     * Генерирует html код поля <select> из списка названий директорий в указанной папке
     * @param array $field Массив параметров, вдобавок к стандартным (name, title...)
     * обязательно должен быть указан параметр path содержащую ссылку на папку 
     * относительно корня сайта
     * @return boolean|array
     */
    private function dir_list($field)
    {
        $html = $this->genList($field);
        
        return array(
            'type' => $field['type'],
            'title' => $this->getTD($field, 'title', 'dir_list', $field['name']),
            'description' => $this->getTD($field, 'description', 'dir_list', $field['name']),
            'html' => $html
        );
    }
    
    private function file_list($field)
    {
        $html = $this->genList($field, 'files');
        
        return array(
            'type' => $field['type'],
            'title' => $this->getTD($field, 'title', 'file_list', $field['name']),
            'description' => $this->getTD($field, 'description', 'file_list', $field['name']),
            'html' => $html
        );
    }

    /**
     * Вставляет горизонтальную линию
     * @param array $field
     * @return array
     */
    private function hr($field)
    {
        return array( 'html' => '<hr/>' );
    }
    
    //==========================================================================
    
    /**
     * Возвращает значение атрибута style при наличии
     * @param mixed $style
     * @return string
     */
    private function getStyle($style = false)
    {
        if (empty($style)) {
            return '';
        }
        
        if (is_array($style)) {
            $tmp = array();
            
            foreach ($style as $k=>$v) {
                $tmp[] = $k .':'. $v;
            }
            
            $style = implode(';', $tmp);
        }
        
        return ' style="'. (string)$style .'"';
    }
    
    /**
     * Возвращает строку из атрибутов элемента
     * @param array $field
     * @return string
     */
    private function getOtherAttributes($field)
    {
        $tmp = array();
            
        foreach ($field as $k=>$v) {
            if (!in_array($k, array('title', 'fields', 'type', 'class', 'style', 'name', 'value', 'selected', 'checked')) && !is_array($v)) {
                $v = is_bool($v) ? (int)$v : $v;
                $tmp[] = $k .'="'. (mb_substr($v, 0, 2) == 'on' ? $v : htmlspecialchars($v)) .'"';
            }
        }
        
        return (empty($tmp) ? '' : ' '. implode(' ', $tmp));
    }
    
    /**
     * Возвращает текущее значение параметра или дефолтовое, или же проверяет 
     * присвоено ли значение
     * @param array $field
     * @param boolean $return_isset Флаг указывающий возвращать значение или наличие в 
     * @param string $name
     * @param string $value
     * @return mixed
     */
    private function getValue($field, $return_isset = false, $name = 'name', $value = 'value')
    {
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
                    }
                    else
                    {
                        $val = $val[$name];
                    }
                }
                
                if ($error === false) {
                    return $return_isset ? true : $val;
                }
            }
        }
        elseif (isset($this->values[$field[$name]]))
        {
            return $return_isset ? true : $this->values[$field[$name]];
        }
        
        return $return_isset ? false : cmsCore::getArrVal($field, $value, '');
    }
    
    /**
     * Возвращает название или описание поля
     * @global array $_LANG
     * @param array $var
     * @param string $key
     * @param string $type
     * @param string $name
     * @return string
     */
    private function getTD($var, $key, $type, $name)
    {
        global $_LANG;
        
        if (!empty($var[$key])) {
            return $var[$key];
        }
        
        elseif ((!empty($_LANG[mb_strtoupper($type .'_'. $name)]) && $key == 'title') || (!empty($_LANG[mb_strtoupper($type .'_'. $name .'_desc')]) && $key == 'description'))
        {
            return $key == 'title' ? $_LANG[mb_strtoupper($type .'_'. $name)] : $_LANG[mb_strtoupper($type .'_'. $name .'_desc')];
        }
        elseif (!is_numeric($name) && $key != 'description')
        {
            return mb_strtoupper($name);
        }
        elseif ($key != 'description')
        {
            return mb_strtoupper($type .'_'. $name);
        }
    }
}
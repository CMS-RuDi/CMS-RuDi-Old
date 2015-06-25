<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                          LICENSED BY GNU/GPL v2                            //
//                                                                            //
/******************************************************************************/

defined('VALID_CMS_ADMIN') or die();

function cpAccessDenied() {
    cmsCore::redirect('/admin/index.php?view=noaccess');
}

function cpWarning($text) {
    global $_LANG;
    return '<div id="warning"><span>'. $_LANG['ATTENTION'] .': </span>'. $text .'</div>';
}

function cpWritable($file) { //relative path with starting "/"
    if (is_writable(PATH . $file)) {
        return true;
    } else {
        return @chmod(PATH . $file, 0777);
    }
}

function cpCheckWritable($file, $type='file') {
    global $_LANG;
    if (!cpWritable($file)) {
        if ($type == 'file') {
            echo cpWarning(sprintf($_LANG['FILE_NOT_WRITABLE'], $file));
        } else {
            echo cpWarning(sprintf($_LANG['DIR_NOT_WRITABLE'], $file));
        }
    }
}

/////////////////////////// PAGE GENERATION ////////////////////////////////////////////////////////////////
function cpMenu($type='main') {
    global $_LANG;
    global $adminAccess;
    
    $items = array();

    if ($type == 'main') {
        $inCore = cmsCore::getInstance();

        if (cmsUser::isAdminCan('admin/menu', $adminAccess)) {
            $items['admin/menu'] = array(
                'class'    => 'fa-list admin_menu',
                'title'    => $_LANG['AD_MENU'],
                'items'    => array(
                    'AD_MENU_POINT_ADD' => array(
                        'class' => 'fa-plus-circle admin_menu_mpa',
                        'link'  => 'index.php?view=menu&do=add',
                        'title' => $_LANG['AD_MENU_POINT_ADD']
                    ),
                    'AD_MENU_ADD' => array(
                        'class' => 'fa-plus-circle admin_menu_ma',
                        'link'  => 'index.php?view=menu&do=addmenu',
                        'title' => $_LANG['AD_MENU_ADD']
                    ),
                    'AD_SHOW_ALL' => array(
                        'class' => 'fa-ellipsis-h admin_menu_sa',
                        'link'  => 'index.php?view=menu',
                        'title' => $_LANG['AD_SHOW_ALL']
                    ),
                )
            );
        }

        if (cmsUser::isAdminCan('admin/modules', $adminAccess)) {
            $items['admin/modules'] = array(
                'class'    => 'fa-th admin_modules',
                'title'    => $_LANG['AD_MODULES'],
                'items'    => array(
                    'AD_MODULES_SETUP' => array(
                        'class' => 'fa-cube admin_modules_ms',
                        'link'  => 'index.php?view=install&do=module',
                        'title' => $_LANG['AD_MODULES_SETUP']
                    ),
                    'AD_MODULE_ADD' => array(
                        'class' => 'fa-plus-circle admin_modules_ma',
                        'link'  => 'index.php?view=modules&do=add',
                        'title' => $_LANG['AD_MODULE_ADD']
                    ),
                    'AD_SHOW_ALL' => array(
                        'class' => 'fa-plus-circle admin_modules_sa',
                        'link'  => 'index.php?view=modules',
                        'title' => $_LANG['AD_SHOW_ALL']
                    )
                )
            );
        }

        if (cmsUser::isAdminCan('admin/content', $adminAccess)) {
            $items['admin/content'] = array(
                'class'    => 'fa-folder-open admin_content',
                'title'    => $_LANG['AD_ARTICLE_SITE'],
                'items'    => array(
                    'AD_ARTICLES' => array(
                        'class' => 'fa-file-text admin_content_a',
                        'link'  => 'index.php?view=tree',
                        'title' => $_LANG['AD_ARTICLES']
                    ),
                    'AD_ARTICLES_ARCHIVE' => array(
                        'class' => 'fa-archive admin_content_aa',
                        'link'  => 'index.php?view=arhive',
                        'title' => $_LANG['AD_ARTICLES_ARCHIVE']
                    ),
                    'AD_CREATE_SECTION' => array(
                        'class' => 'fa-plus-circle admin_content_cs',
                        'link'  => 'index.php?view=cats&do=add',
                        'title' => $_LANG['AD_CREATE_SECTION']
                    ),
                    'AD_CREATE_ARTICLE' => array(
                        'class' => 'fa-plus-circle admin_content_ca',
                        'link'  => 'index.php?view=content&do=add',
                        'title' => $_LANG['AD_CREATE_ARTICLE']
                    )
                )
            );
        }

        if (cmsUser::isAdminCan('admin/components', $adminAccess)) {
            $items['admin/components'] = array(
                'class'    => 'fa-cubes admin_components',
                'title'    => $_LANG['AD_COMPONENTS'],
                'items'    => array(
                    'AD_INSTALL_COMPONENTS' => array(
                        'class' => 'fa-cube admin_components_ic',
                        'link'  => 'index.php?view=install&do=component',
                        'title' => $_LANG['AD_INSTALL_COMPONENTS']
                    )
                )
            );

            $components   = $inCore->getAllComponents();
            $showed_count = 0;
            $total_count  = count($components);

            if ($total_count) {
                foreach ($components as $com) {
                    if ($com['published'] && (file_exists('components/'. $com['link'] .'/backend.php')) && cmsUser::isAdminCan('admin/com_'. $com['link'], $adminAccess)) {
                        cmsCore::loadLanguage('components/'. $com['link']);
                        $items['admin/components']['items'][$com['link']] = array(
                            'class' => 'admin_components_'. $com['link'],
                            'icon'  => '/admin/images/components/'. $com['link'] .'.png',
                            'link'  => 'index.php?view=components&do=config&link='. $com['link'],
                            'title' => isset($_LANG['COM_TITLE_'. mb_strtoupper($com['link'])]) ? $_LANG['COM_TITLE_'. mb_strtoupper($com['link'])] : $com['title']
                        );
                    }
                }
            }

            if ($total_count != $showed_count && cmsCore::c('user')->id == 1) {
                $items['admin/components']['items']['AD_SHOW_ALL'] = array(
                    'class' => 'fa-ellipsis-h admin_components_sa',
                    'link'  => 'index.php?view=components',
                    'title' => $_LANG['AD_SHOW_ALL']
                );
            }
        }

        if (cmsUser::isAdminCan('admin/plugins', $adminAccess)) {
            $items['admin/plugins'] = array(
                'class'    => 'fa-puzzle-piece admin_plugins',
                'title'    => $_LANG['AD_ADDITIONS'],
                'items'    => array(
                    'AD_INSTALL_PLUGINS' => array(
                        'class' => 'fa-cube admin_plugins_ip',
                        'link'  => 'index.php?view=install&do=plugin',
                        'title' => $_LANG['AD_INSTALL_PLUGINS']
                    ),
                    'AD_PLUGINS' => array(
                        'class' => 'fa-puzzle-piece admin_plugins_p',
                        'link'  => 'index.php?view=plugins',
                        'title' => $_LANG['AD_PLUGINS']
                    )
                )
            );

            if (cmsUser::isAdminCan('admin/filters', $adminAccess)) {
                $items['admin/plugins']['items']['AD_FILTERS'] = array(
                    'class' => 'fa-puzzle-piece admin_plugins_f',
                    'link'  => 'index.php?view=filters',
                    'title' => $_LANG['AD_FILTERS']
                );
            }
        }

        if (cmsUser::isAdminCan('admin/users', $adminAccess)) {
            $items['admin/users'] = array(
                'class'    => 'fa-users admin_users',
                'title'    => $_LANG['AD_USERS'],
                'items'    => array(
                    'AD_USERS' => array(
                        'class' => 'fa-user admin_users_u',
                        'link'  => 'index.php?view=users',
                        'title' => $_LANG['AD_USERS']
                    ),
                    'AD_BANLIST' => array(
                        'class' => 'fa-ban admin_users_b',
                        'link'  => 'index.php?view=userbanlist',
                        'title' => $_LANG['AD_BANLIST']
                    ),
                    'AD_USERS_GROUP' => array(
                        'class' => 'fa-users admin_users_ug',
                        'link'  => 'index.php?view=usergroups',
                        'title' => $_LANG['AD_USERS_GROUP']
                    ),
                    'AD_USER_ADD' => array(
                        'class' => 'fa-plus-circle admin_users_ua',
                        'link'  => 'index.php?view=users&do=add',
                        'title' => $_LANG['AD_USER_ADD']
                    ),
                    'AD_CREATE_GROUP' => array(
                        'class' => 'fa-plus-circle admin_users_cg',
                        'link'  => 'index.php?view=usergroups&do=add',
                        'title' => $_LANG['AD_CREATE_GROUP']
                    ),
                    'AD_PROFILE_SETTINGS' => array(
                        'class' => 'fa-cogs admin_users_ps',
                        'link'  => 'index.php?view=components&do=config&link=users',
                        'title' => $_LANG['AD_PROFILE_SETTINGS']
                    )
                )
            );
        }

        if (cmsUser::isAdminCan('admin/config', $adminAccess)) {
        $items['admin/config'] = array(
            'class'    => 'fa-cogs admin_config',
            'title'    => $_LANG['AD_SETTINGS'],
            'items'    => array(
                'AD_SITE_SETTING' => array(
                    'class' => 'fa-cogs admin_config_ss',
                    'link'  => 'index.php?view=config',
                    'title' => $_LANG['AD_SITE_SETTING']
                ),
                'AD_TEMPLATES_SETTING' => array(
                    'class' => 'fa-columns admin_config_ts',
                    'link'  => 'index.php?view=templates',
                    'title' => $_LANG['AD_TEMPLATES_SETTING']
                ),
                'AD_ROBOTS_TXT' => array(
                    'class' => 'fa-edit admin_config_rt',
                    'link'  => 'index.php?view=robots',
                    'title' => robots.txt
                ),
                'AD_CHECKING_TREES' => array(
                    'class' => 'fa-sitemap admin_config_ct',
                    'link'  => 'index.php?view=repairnested',
                    'title' => $_LANG['AD_CHECKING_TREES']
                ),
                'AD_CRON_MISSION' => array(
                    'class' => 'fa-clock-o admin_config_cm',
                    'link'  => 'index.php?view=cron',
                    'title' => $_LANG['AD_CRON_MISSION']
                ),
                'AD_PHP_INFO' => array(
                    'class' => 'fa-info-circle admin_config_pi',
                    'link'  => 'index.php?view=phpinfo',
                    'title' => $_LANG['AD_PHP_INFO']
                ),
                'AD_CHECK_SYSTEM' => array(
                    'class' => 'fa-pie-chart admin_config_cs',
                    'link'  => 'index.php?view=checksystem',
                    'title' => $_LANG['AD_CHECK_SYSTEM']
                ),
                'AD_CLEAR_SYS_CACHE' => array(
                    'class' => 'fa-trash-o admin_config_csc',
                    'link'  => 'index.php?view=clearcache',
                    'title' => $_LANG['AD_CLEAR_SYS_CACHE']
                )
            )
        );
    }
    }
    
    if ($type == 'user') {
        $items['admin/help'] = array(
            'class'    => 'fa-question-circle admin_help',
            'title'    => $_LANG['AD_HELP'],
            'items'    => array(
                'AD_DOCS' => array(
                    'class' => 'fa-question admin_help_d',
                    'link'  => 'http://cmsrudi.ru/docs',
                    'target' => '_blank',
                    'title' => $_LANG['AD_DOCS']
                ),
                'AD_TICKETS' => array(
                    'class' => 'fa-ticket admin_help_t',
                    'link'  => '/admin/index.php?view=tickets',
                    'title' => $_LANG['AD_TICKETS']
                )
            )
        );
        
        $items['AD_OPEN_SITE'] = array(
            'class' => 'fa-external-link admin_open_site',
            'link'  => '/',
            'target' => '_blank',
            'title' => $_LANG['AD_OPEN_SITE']
        );
        
        $items['admin/user_menu'] = array(
            'class'    => 'fa-user admin_user_menu',
            'title'    => cmsCore::c('db')->get_field('cms_users', 'id='. cmsCore::c('user')->id, 'nickname'),
            'items'    => array(
                'ip' => array(
                    'class' => 'fa-info-circle admin_user_menu_ip',
                    'link'  => 'javascript:void(return false);',
                    'title' => 'IP '. cmsCore::c('user')->ip
                ),
                'TEMPLATE_MY_PROFILE' => array(
                    'class' => 'fa-user admin_user_menu_tmp',
                    'link'  => cmsUser::getProfileURL(cmsCore::c('user')->login),
                    'title' => $_LANG['TEMPLATE_MY_PROFILE']
                )
            )
        );
        
        $new_messages = cmsCore::c('user')->getNewMsg();
        if ($new_messages['total']) {
            $items['admin/user_menu']['items']['AD_NEW_MSG'] = array(
                'class' => 'fa-envelope-o admin_user_menu_nm',
                'link'  => '/users/'. cmsCore::c('user')->id .'/messages.html',
                'title' => $_LANG['AD_NEW_MSG'] .' ('. $new_messages['total'] .')'
            );
        }
        
        $items['admin/user_menu']['items']['AD_EXIT'] = array(
            'class' => 'fa-power-off admin_exit',
            'link'  => '/logout',
            'title' => $_LANG['AD_EXIT']
        );
    }
    
    cmsCore::c('page')->initTemplate('special', 'menu')->
        assign('items', $items)->
        display();
}

function cpToolMenu($toolmenu_list, $opt=false, $optname='opt') {
    if (!empty($toolmenu_list)) {
        $active_menu = false;
        $active_sub_menu = false;
        $actived = false;
        
        $opt = cmsCore::request($optname, 'str', $opt);
        
        foreach ($toolmenu_list as $key => $toolmenu) {
            if (empty($toolmenu)) {
                $toolmenu_list[$key] = array(
                    'icon' => 'images/toolmenu/separator.png'
                );
                continue;
            }
            
            if ($active_menu === false) {
                if (mb_strstr($toolmenu['link'], $optname .'='. $opt)) {
                    $active_menu = true;
                }
            }
            
            if (!empty($toolmenu['items'])) {
                foreach ($toolmenu['items'] as $k => $item) {
                    if ($active_menu === false) {
                        if (mb_strstr($item['link'], $optname .'='. $opt)) {
                            $active_menu = $active_sub_menu = true;
                        }
                    }
                    
                    if ($active_sub_menu === true) {
                        $toolmenu_list[$key]['items'][$k]['active'] = true;
                        $active_sub_menu = false;
                    }
                    
                    $toolmenu_list[$key]['items'][$k]['icon'] = 'images/toolmenu/'. $item['icon'];
                    $toolmenu_list[$key]['items'][$k]['class'] .= ' uittip';
                }
            }
            
            if ($active_menu === true && $actived === false) {
                $toolmenu_list[$key]['active'] = true;
                $actived = true;
            }
            
            $toolmenu_list[$key]['icon'] = 'images/toolmenu/'. $toolmenu['icon'];
            $toolmenu_list[$key]['class'] .= ' uittip';
        }
        
        cmsCore::c('page')->initTemplate('special', 'toolmenu')->
            assign('items', $toolmenu_list)->
            assign('without_title', true)->
            display();
    }
}

function cpProceedBody() {
    ob_start();
    
    $file = cmsAdmin::getApplet() .'.php';

    if (!file_exists(PATH .'/admin/applets/'. $file)) {
        cmsCore::error404();
    }

    cmsCore::loadLanguage('admin/applets/applet_'. cmsAdmin::getApplet());
    
    include('applets/'. $file);

    call_user_func('applet_'. cmsAdmin::getApplet());

    cmsCore::c('page')->page_body = ob_get_clean();
} 

//////////////////////////////////////////////// PATHWAY ///////////////////////////////////////////////////////
function cpAddPathway($title, $link) {
    return cmsCore::c('page')->addPathway($title, $link);
}

function cpModulePositions($template) {
    $pos = array();

    $posfile = PATH .'/templates/'. $template .'/positions.txt';

    if (file_exists($posfile)) {
        $file = fopen($posfile, 'r');
        while (!feof($file)) {
            $str = fgets($file);
            $str = str_replace("\n", '', $str);
            $str = str_replace("\r", '', $str);
            if (!mb_strstr($str, '#') && mb_strlen($str)>1) {
                $pos[] = $str;
            }
        }
        fclose($file);
        return $pos;
    } else {
        return false;
    }
}

function cpAddParam($query, $param, $value) {
    if (is_array($param)) {
        if (is_array($value)) {
            if (count($value) != count($param)) {
                return $query;
            }
            foreach ($param as $k => $v) {
                if (isset($value[$k])) {
                    $query = cpAddParam($query, $v, $value[$k]);
                }
            }
        } else {
            foreach ($param as $k => $v) {
                $query = cpAddParam($query, $v, $value);
            }
        }
        
        return $query;
    }
    
    $new_query = array();
    mb_parse_str($query, $params);

    foreach($params as $key => $val) {
        if ($key != 'nofilter') {
            $new_query[$key] = $key .'='. ($key == $param ? $value : $val);
        }
    }
    
    if (!isset($new_query[$param])) {
        $new_query[$param] = $param .'='. $value;
    }

    return implode('&', $new_query);
}

function cpListTable($table, $_fields, $_actions, $where='', $orderby='title', $perpage=60) {
    global $_LANG;
    
    $page = cmsCore::request('page', 'int', 1);

    $sql = 'SELECT *';
    $is_actions = sizeof($_actions);

    foreach($_fields as $key => $value) {
        if (isset($_fields[$key]['fdate'])) {
            $sql .= ", DATE_FORMAT(".$_fields[$key]['field'].", '".$_fields[$key]['fdate']."') as `".$_fields[$key]['field']."`" ;
        }
    }

    $sql .= ' FROM '. $table;

    if (isset($_SESSION['filter_table']) && $_SESSION['filter_table']!=$table) {
        unset($_SESSION['filter']);
    }

    if (cmsCore::inRequest('nofilter')) {
        unset($_SESSION['filter']);
        cmsCore::redirect('/admin/index.php?'. cpAddParam($_SERVER['QUERY_STRING'], 'page', 1));
    }

    $filter = false;
    
    if (cmsCore::inRequest('filter')) {
        $filter = cmsCore::request('filter', 'array_str', '');
        
        if (isset($_SESSION['filter']) && $_SESSION['filter'] != $filter) {
            $page = 1;
        }
        
        $_SESSION['filter'] = $filter;
    } else if (isset($_SESSION['filter'])) {
        $filter = $_SESSION['filter'];
    }

    if ($filter) {
        $f = 0;
        foreach ($filter as $key => $value) {
            if (!empty($filter[$key]) && $filter[$key] != -100) {
                if (!is_numeric($filter[$key])) {
                    cmsCore::c('db')->where($key ." LIKE '%" . $filter[$key] ."%'");
                } else {
                    cmsCore::c('db')->where($key ." = '". $filter[$key] ."'");
                }
                $f++;
            }
        }
        if (!isset($_SESSION['filter'])) { $_SESSION['filter'] = $filter; }
    }
    
    if (mb_strlen($where) <= 3) {
        $where = '1=1';
    }

    //Выставляем сортировку выборки данных в БД
    $sort = cmsCore::request('sort', 'str', '');
    $so = 'asc';
    if (empty($sort)) {
        if (!empty($orderby)) {
            $orderby = trim($orderby);
            
            if (mb_strstr($orderby, ' ')) {
                $sorta = explode(' ', $orderby);
                $sort = $sorta[0];
                
                foreach ($sorta as $s) {
                    $s = mb_strtolower(trim($s,' ,'));
                    if ($s == 'asc' || $s == 'desc') {
                        $so = $s;
                        break;
                    }
                }
            } else {
                $sort = $orderby;
            }
            
            cmsCore::c('db')->order_by = 'ORDER BY '. $orderby;
        } else {
            foreach($_fields as $key => $value) {
                if ($_fields[$key]['field'] == 'ordering' && $sort != 'NSLeft') {
                    $sort = 'ordering';
                    cmsCore::c('db')->orderBy($sort, $so);
                }
            }
        }
    } else {
        $so = cmsCore::request('so', array('asc', 'desc'), $so);
        cmsCore::c('db')->orderBy($sort, $so);
    }

    $total = cmsCore::c('db')->rows_count($table, $where .' '. cmsCore::c('db')->where);
    cmsCore::c('db')->limitPage($page, $perpage);

    $result = cmsCore::c('db')->query(
        $sql . "\n" .'WHERE '. $where .' '. cmsCore::c('db')->where . "\n" . (empty(cmsCore::c('db')->order_by) ? '' : cmsCore::c('db')->order_by) .' LIMIT '. cmsCore::c('db')->limit
    );

    $_SESSION['filter_table'] = $table;

    if (cmsCore::c('db')->error()) {
        unset($_SESSION['filter']);
        cmsCore::redirect('/admin/index.php?'. $_SERVER['QUERY_STRING']);
    }
    
    $lt_form_field = cmsCore::c('page')->initTemplate('special', 'list_table_filter')->fetch();

    $filters = 0; $f_html = '';
    //Find and render filters
    foreach($_fields as $key => $value) {
        if (isset($_fields[$key]['filter'])) {
            $f_html .= str_replace('%title%', $_fields[$key]['title'], $lt_form_field);
            
            $initval = false;
            if (isset($filter[$_fields[$key]['field']])) {
                $initval =  $filter[$_fields[$key]['field']];
            }

            $inputname = 'filter['.$_fields[$key]['field'].']';
            if (!isset($_fields[$key]['filterlist'])) {
                $f_html .= str_replace('%field%', '<input type="text" class="form-control" name="'. $inputname .'" size="'. $_fields[$key]['filter'] .'" value="'. ($initval === false ? '' : $initval) .'" />', $f_html);
            } else {
                $f_html .= str_replace('%field%', cpBuildList($inputname, $_fields[$key]['filterlist'], $initval), $f_html);
            }

            $filters += 1;
            $_SERVER['QUERY_STRING'] = str_replace('filter['.$_fields[$key]['field'].']=', '', $_SERVER['QUERY_STRING']);
        }
    }
    
    foreach($_fields as $key => $value) {
        if (!is_array($_fields[$key]['field'])) {
            $_fields[$key]['sort_link'] = 'index.php?'. cpAddParam($_SERVER['QUERY_STRING'], array( 0 => 'sort', 1 => 'so'), array( 0 => $_fields[$key]['field'], 1 => ($so == 'asc' ? 'desc' : 'asc')));
        }
    }
    
    $items = $actions = array();
    
    if (cmsCore::c('db')->num_rows($result)) {
        while ($item = cmsCore::c('db')->fetch_assoc($result)) {
            $itms = array();
            
            foreach($_fields as $key => $value) {
                $it = array();
                
                if (isset($_fields[$key]['link'])) {
                    $it['type'] = 'link';

                    $it['link'] = str_replace('%id%', $item['id'], $_fields[$key]['link']);

                    if (isset($_fields[$key]['prc'])) {
                        // функция обработки под названием $_fields[$key]['prc']
                        // какие параметры передать функции - один ключ или произвольный массив ключей
                        if (is_array($_fields[$key]['field'])) {
                            foreach ($_fields[$key]['field'] as $func_field) {
                                $in_func_array[$func_field] = $item[$func_field];
                            }

                            $it['title'] = call_user_func($_fields[$key]['prc'], $in_func_array);
                        } else {
                            $it['title'] = call_user_func($_fields[$key]['prc'], $item[$_fields[$key]['field']]);
                        }
                    } else {
                        $it['title'] = $item[$_fields[$key]['field']];
                        if (isset($_fields[$key]['maxlen'])) {
                            if (mb_strlen($it['title']) > $_fields[$key]['maxlen']) {
                                $it['title'] = mb_substr($it['title'], 0, $_fields[$key]['maxlen']).'...';
                            }
                        }
                    }

                    //nested sets otstup
                    if (isset($item['NSLevel']) && ($_fields[$key]['field']=='title' || (is_array($_fields[$key]['field']) && in_array('title', $_fields[$key]['field'])))) {
                        $otstup = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', ($item['NSLevel']-1));
                        if ($item['NSLevel']-1 > 0) { $otstup .=  ' &raquo; '; }
                    } else {
                        $otstup = '';
                    }

                    $it['otstup'] = $otstup;

                    if ($table == 'cms_components') {
                        if (!function_exists('cpComponentHasConfig') || !cpComponentHasConfig($item['link'])) {
                            unset($it['link']);
                        }

                        $it['icon'] = '/admin/images/components/'. $item['link'] .'.png';
                    }
                } else {
                    if ($_fields[$key]['field'] != 'ordering') {
                        if ($_fields[$key]['field'] == 'published' || isset($_fields[$key]['published'])) {
                            $it['type'] = 'published';

                            if (isset($_fields[$key]['do'])) {
                                $do = $_fields[$key]['do'];
                            } else {
                                $do = 'do';
                            }

                            if (isset($_fields[$key]['do_suffix'])) {
                                $dos = $_fields[$key]['do_suffix'];
                                $ids = 'item_id';
                            } else {
                                $dos = '';
                                $ids = 'id';
                            }

                            if ($item[$_fields[$key]['field']]) {
                                $qs = cpAddParam($_SERVER['QUERY_STRING'], $do, 'hide'.$dos);
                                $qs = cpAddParam($qs, $ids, $item['id']);
                                $qs2 = cpAddParam($_SERVER['QUERY_STRING'], $do, 'show'.$dos);
                                $qs2 = cpAddParam($qs2, $ids, $item['id']);

                                $it['link'] = "javascript:pub(".$item['id'].", '".$qs."', '".$qs2."', 'off', 'on');";
                                $it['icon'] = 'images/actions/on.gif';
                                $it['title'] = $_LANG['HIDE'];
                            } else {
                                $qs = cpAddParam($_SERVER['QUERY_STRING'], $do, 'show'.$dos);
                                $qs = cpAddParam($qs, $ids, $item['id']);
                                $qs2 = cpAddParam($_SERVER['QUERY_STRING'], $do, 'hide'.$dos);
                                $qs2 = cpAddParam($qs2, $ids, $item['id']);

                                $it['link']  = "javascript:pub(".$item['id'].", '".$qs."', '".$qs2."', 'on', 'off');";
                                $it['icon']  = 'images/actions/off.gif';
                                $it['title'] = $_LANG['SHOW'];
                            }
                        } else {
                            $it['type'] = 'default';

                            if (isset($_fields[$key]['prc'])) {
                                // функция обработки под названием $_fields[$key]['prc']
                                // какие параметры передать функции - один ключ или произвольный массив ключей
                                if (is_array($_fields[$key]['field'])) {
                                    foreach ($_fields[$key]['field'] as $func_field) {
                                        $in_func_array[$func_field] = $item[$func_field];
                                    }
                                    $it['title'] = call_user_func($_fields[$key]['prc'], $in_func_array);
                                } else {
                                    $it['title'] = call_user_func($_fields[$key]['prc'], $item[$_fields[$key]['field']]);
                                }
                                if (is_array($it['title']) && isset($it['title']['link'])) {
                                    $it['title'] = str_replace('%id%', $item['id'], $it['title']['link']);
                                }
                            } else {
                                $it['title'] = $item[$_fields[$key]['field']];
                                if (isset($_fields[$key]['maxlen'])) {
                                    if (mb_strlen($it['title']) > $_fields[$key]['maxlen']) {
                                        $it['title'] = mb_substr($it['title'], 0, $_fields[$key]['maxlen']).'...';
                                    }
                                }
                            }

                            //nested sets otstup
                            if (isset($item['NSLevel']) && ($_fields[$key]['field'] == 'title' || (is_array($_fields[$key]['field']) && in_array('title', $_fields[$key]['field'])))) {
                                $otstup = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', ($item['NSLevel']-1));
                                if ($item['NSLevel']-1 > 0) { $otstup .=  ' &raquo; '; }
                            } else { $otstup = ''; }

                            $it['otstup'] = $otstup;
                        }
                    } else {
                        $it['type'] = 'ordering';

                        if (isset($_fields[$key]['do'])) {
                            $do = 'do=config&id='.(int)$_REQUEST['id'].'&'.$_fields[$key]['do'];
                        } else {
                            $do = 'do';
                        }

                        if (isset($_fields[$key]['do_suffix'])) {
                            $dos = $_fields[$key]['do_suffix'];
                            $ids = 'item_id';
                        } else {
                            $dos = '';
                            $ids = 'id';
                        }

                        $it['link_down']  = '?view='. cmsAdmin::getApplet() .'&'. $do .'=move_down&co='. $item[$_fields[$key]['field']] .'&'. $ids .'='. $item['id'];

                        $it['link_up']  = '?view='. cmsAdmin::getApplet() .'&'. $do .'=move_up&co='. $item[$_fields[$key]['field']] .'&'. $ids .'='. $item['id'];

                        $it['value'] = $item['ordering'];
                    }
                }

                $itms[] = $it;
            }

            if ($is_actions) {
                $actions[$item['id']] = array();

                foreach($_actions as $action) {
                    if (isset($action['condition'])) {
                        $print = $action['condition']($item);
                    } else {
                        $print = true;
                    }

                    if ($print) {
                        $icon   = $action['icon'];
                        $title  = $action['title'];
                        $link   = $action['link'];

                        foreach($item as $f=>$v) {
                            $link  = str_replace('%'.$f.'%', $v, $link);
                            if (isset($action['confirm'])) {
                                $action['confirm'] = str_replace('%'.$f.'%', $v, $action['confirm']);
                            }
                        }

                        $acts = array(
                            'link'   => $link,
                            'title'  => $title,
                            'icon'   => 'images/actions/'. $icon,
                            'target' => isset($action['target']) ? $action['target'] : false
                        );

                        if (isset($action['confirm'])) {
                            $acts['link'] = "javascript:jsmsg('". $action['confirm'] ."', '". $link ."');";
                        }

                        $actions[$item['id']][] = $acts;
                    }
                }
            }

            $items[$item['id']] = $itms;
        }
        
        $link = '?view='. cmsAdmin::getApplet();
        
        if ($sort) {
            $link .= '&sort='.$sort;
            if (cmsCore::inRequest('so')) {
                $link .= '&so='.cmsCore::request('so');
            }
        }
    }

    cmsCore::c('page')->initTemplate('special', 'list_table')->
        assign('applet', cmsAdmin::getApplet())->
        assign('filters', $filters)->
        assign('f_html', $f_html)->
        assign('f', $f)->
        assign('query_str', $_SERVER['QUERY_STRING'])->
        assign('sort', $sort)->
        assign('so', $so)->
        assign('actions', $actions)->
        assign('table', $table)->
        assign('fields', $_fields)->
        assign('items', $items)->
        display();
    
    echo cmsPage::getPagebar($total, $page, $perpage, $_SERVER['PHP_SELF'] .'?'. cpAddParam($_SERVER['QUERY_STRING'], 'page', '%page%'));
}

////////////////////////////// LIST TABLE PROCESSORS ///////////////////////////

function cpForumCatById($id) {
    $title = cmsCore::c('db')->get_field('cms_forum_cats', 'id='. $id, 'title');
    
    if ($title) {
        return '<a href="index.php?view=components&do=config&id='. (int)$_REQUEST['id'] .'&opt=edit_cat&item_id='. $id .'">'. $title .'</a> ('. $id .')';
    } else {
        return '--';
    }
}

function cpFaqCatById($id) {
    $title = cmsCore::c('db')->get_field('cms_faq_cats', 'id='. $id, 'title');

    if ($title) {
        return '<a href="index.php?view=components&do=config&id='. (int)$_REQUEST['id'] .'&opt=edit_cat&item_id='. $id .'">'. $title .'</a>';
    } else {
        return '--';
    }
}

function cpCatalogCatById($id){
    $cat = cmsCore::c('db')->get_fields('cms_uc_cats', 'id='. $id, 'title, parent_id');

    if ($cat) {
        if ($cat['parent_id']) {
            return '<a href="index.php?view=components&do=config&id='. (int)$_REQUEST['id'] .'&opt=edit_cat&item_id='. $id .'">'. $cat['title'] .'</a> ('. $id .')';
        } else {
            return $cat['title'];
        }
    } else {
        return '--';
    }
}

function cpBoardCatById($id) {
    $title = cmsCore::c('db')->get_field('cms_board_cats', 'id='. $id, 'title');

    if ($title) {
        return '<a href="index.php?view=components&do=config&id='. (int)$_REQUEST['id'] .'&opt=edit_cat&item_id='. $id .'">'. $title .'</a> ('. $id .')';
    } else {
        return '--';
    }
}

function cpGroupById($id) {
    if (isset($GLOBALS['groups'][$id])) {
        $title = $GLOBALS['groups'][$id];
    } else {
        $title = cmsUser::getGroupTitle($id);
        $GLOBALS['groups'][$id] = $title;
    }

    return '<a href="index.php?view=usergroups&do=edit&id='. $id .'">'. $title .'</a>';
}

function cpCatById($id) {
    $cat = cmsCore::c('db')->get_fields('cms_category', 'id='. $id, 'title, parent_id');

    if ($cat) {
        if ($cat['parent_id']){
            return '<a href="index.php?view=cats&do=edit&id='. $id .'">'. $cat['title'] .'</a> ('. $id .')';
        } else {
            return $cat['title'];
        }
    } else {
        return '--';
    }
}

function cpModuleById($id) {
    return cmsCore::c('db')->get_field('cms_modules', 'id='. $id .' AND is_external=1', 'content');
}

function cpModuleTitleById($id) {
    return cmsCore::c('db')->get_field('cms_modules', 'id='. $id, 'name');
}

function cpTemplateById($template_id) {
    global $_LANG;
    if ($template_id) {
        return $template_id;
    } else {
        return '<span style="color:silver">'. $_LANG['AD_AS_SITE'] .'</span>';
    }
}

function cpUserNick($user_id=0) {
    global $_LANG;
    if ($user_id) {
        return cmsCore::c('db')->get_field('cms_users', 'id='. $user_id, 'nickname');
    } else {
        return '<em style="color:gray">'. $_LANG['AD_NOT_DEFINED'] .'</em>';
    }
}

function cpYesNo($option) {
    global $_LANG;
    if ($option) {
        return '<span style="color:green">'. $_LANG['YES'] .'</span>';
    } else {
        return '<span style="color:red">'. $_LANG['NO'] .'</span>';
    }
}

/////////////////////////////////// DATABASE ///////////////////////////////////

function dbMoveUp($table, $id, $current_ord) {
    $id = (int)$id;
    $current_ord = (int)$current_ord;
    
    $sql = "UPDATE ". $table ." SET ordering = ordering + 1 WHERE ordering = ". $current_ord-1 ." LIMIT 1";
    cmsCore::c('db')->query($sql) ;
    
    $sql = "UPDATE ". $table ." SET ordering = ordering - 1 WHERE id = ". $id ." LIMIT 1";
    cmsCore::c('db')->query($sql) ;
}

function dbMoveDown($table, $id, $current_ord) {
    $id = (int)$id;
    $current_ord = (int)$current_ord;
    
    $sql = "UPDATE ". $table ." SET ordering = ordering - 1 WHERE ordering = ". $current_ord+1 ." LIMIT 1";
    cmsCore::c('db')->query($sql) ;
    
    $sql = "UPDATE ". $table ." SET ordering = ordering + 1 WHERE id = ". $id ." LIMIT 1";
    cmsCore::c('db')->query($sql) ;
}

function dbDelete($table, $id) {
    $inCore = cmsCore::getInstance();
    $id = (int)$id;
    
    $sql = "DELETE FROM ". $table ." WHERE id = ". $id ." LIMIT 1";
    cmsCore::c('db')->query($sql) ;
    
    if ($table=='cms_content') {
        cmsClearTags('content', $id);
        $inCore->deleteRatings('content', $id);
        $inCore->deleteComments('article', $id);
        cmsCore::c('db')->query("DELETE FROM cms_tags WHERE target='content' AND item_id=". $id);
    }
    
    if ($table=='cms_modules') {
        cmsCore::c('db')->query("DELETE FROM cms_modules_bind WHERE module_id=". $id);
    }
}

function dbDeleteList($table, $list) {
    if (is_array($list)) {
        $sql = "DELETE FROM ". $table ." WHERE ";
        $item = 0;
        
        foreach($list as $key => $value) {
            $item ++;
            $value = (int)$value;
            $sql .= 'id = '. $value;
            
            if ($item < sizeof($list)) { $sql .= ' OR '; }
            
            if ($table == 'cms_content') {
                cmsClearTags('content', $value);
                
                cmsCore::c('db')->delete('cms_comments', "target='article' AND target_id=". $value);
                cmsCore::c('db')->delete('cms_ratings', "target='content' AND item_id=". $value);
                cmsCore::c('db')->delete('cms_tags', "target='content' AND item_id=". $value);
            }
            
            if ($table=='cms_modules') {
                cmsCore::c('db')->delete('cms_modules_bind', "module_id=". $value);
            }
        }
        $sql .= ' LIMIT '. sizeof($list);
        cmsCore::c('db')->query($sql) ;
    }
}

///////////////////////////////// HTML GENERATORS //////////////////////////////

function insertPanel() {
    global $_LANG;
    $p_html = cmsCore::callEvent('REPLACE_PANEL', array('html' => ''));

    if ($p_html['html']) { return $p_html['html']; }

    $inCore = cmsCore::getInstance();
    
    if ($inCore->isComponentInstalled('banners')) {
        $inCore->loadModel('banners');
    }
    
    $tpl = cmsCore::c('page')->initTemplate('special', 'panel')->
        assign('bannersInstalled', $inCore->isComponentInstalled('banners'))->
        assign('forms_options', $inCore->getListItems('cms_forms'));
    
    if ($inCore->isComponentInstalled('banners')) {
        $tpl->assign('banners_options', cms_model_banners::getBannersListHTML());
    }
    
    $tpl->display();
}

////////////////////////////////////////////////////////////////////////////////

function cpBuildList($attr_name, $list, $selected_id=false) {
    global $_LANG;
    $html = '';

    $html .= '<select id="'. $attr_name .'" class="form-control" style="margin-right:10px;margin-left:5px;" name="'. $attr_name .'">' . "\n";

    $html .= '<option value="-100">-- '. $_LANG['AD_ALL'] .' --</option>'."\n";

    foreach($list as $key => $value) {
        if ($selected_id === false || $selected_id != $list[$key]['id']) {
            $sel = '';
        } else {
            $sel = 'selected="selected"';
        }

        $html .= '<option value="'. $list[$key]['id'] .'" '. $sel .'>'. $list[$key]['title'] .'</option>' . "\n";
    }

    $html .= '</select>' . "\n";

    return $html;
}

function cpGetList($listtype, $field_name='title') {
    global $_LANG;
    $list = array();

    // Позиции для модулей
    if ($listtype == 'positions') {
        $pos = cpModulePositions(cmsConfig::getConfig('template'));

        foreach($pos as $p) {
            $list[] = array( 'title' => $p, 'id' => $p);
        }

        return $list;
    }
    // Типы меню
    if ($listtype == 'menu') {
        $list[] = array( 'title' => $_LANG['AD_MAIN_MENU'], 'id' => 'mainmenu' );
        $list[] = array( 'title' => $_LANG['AD_USER_MENU'], 'id' => 'usermenu' );
        $list[] = array( 'title' => $_LANG['AD_AUTH_MENU'], 'id' => 'authmenu' );

        for ($m=1; $m<=20; $m++) {
            $list[] = array( 'title' => $_LANG['AD_SUBMENU'] .' '. $m, 'id' => 'menu'. $m );
        }

        return $list;
    }

    //...или записи из таблицы
    $sql  = "SELECT id, ". $field_name ." FROM ". $listtype ." ORDER BY ". $field_name ." ASC";
    $result = cmsCore::c('db')->query($sql) ;

    if (cmsCore::c('db')->num_rows($result)>0) {
        while($item = cmsCore::c('db')->fetch_assoc($result)) {
            $list[] = array(
                'title' => $item[$field_name],
                'id' => $item['id']
            );
        }
    }

    return $list;
}

function getFullAwardsList() {
    $awards = array();

    $rs = cmsCore::c('db')->query("SELECT title FROM cms_user_awards GROUP BY title");

    if (cmsCore::c('db')->num_rows($rs)) {
        while($aw = cmsCore::c('db')->fetch_assoc($rs)) {
            $awards[] = $aw;
        }
    }

    $rs = cmsCore::c('db')->query("SELECT title FROM cms_user_autoawards GROUP BY title");

    if (cmsCore::c('db')->num_rows($rs)) {
        while($aw = cmsCore::c('db')->fetch_assoc($rs)) {
            if (!in_array(array('title' => $aw['title']), $awards)) {
                $awards[] = $aw;
            }
        }
    }

    return $awards;
}
/**
 * Рекурсивно удаляет директорию
 * @param string $directory
 * @param bool $is_clear Если TRUE, то директория будет очищена, но не удалена
 * @return bool
 */
function files_remove_directory($directory, $is_clear=false) {
    if (substr($directory,-1) == '/') {
        $directory = substr($directory,0,-1);
    }

    if (!file_exists($directory) || !is_dir($directory) || !is_readable($directory)) {
        return false;
    }

    $handle = opendir($directory);

    while (false !== ($node = readdir($handle))) {
        if ($node != '.' && $node != '..') {
            $path = $directory.'/'.$node;

            if (is_dir($path)) {
                if (!files_remove_directory($path)) { return false; }
            } else {
                if (!@unlink($path)) { return false; }
            }
        }
    }

    closedir($handle);

    if ($is_clear == false) {
        if (!@rmdir($directory)) {
            return false;
        }
    }

    return true;
}
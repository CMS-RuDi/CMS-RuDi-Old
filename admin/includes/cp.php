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
                'dropdown' => true,
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
                'dropdown' => true,
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
                'dropdown' => true,
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
                'dropdown' => true,
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
                'dropdown' => true,
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
                'dropdown' => true,
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
            'dropdown' => true,
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
            'dropdown' => true,
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
            'dropdown' => true,
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
        
        $items['admin/exit']['items']['AD_EXIT'] = array(
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
        
        $opt = cmsCore::request($optname, 'str', $opt);
        
        foreach ($toolmenu_list as $key => $toolmenu) {
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
                }
            }
        }
        
        $html .= '<nav class="navbar navbar-default" role="navigation"><ul class="nav nav-tabs">';

        foreach ($toolmenu_list as $toolmenu) {
            if (empty($toolmenu)) {
                $html .= '<div class="toolmenuseparator"></div>'; continue;
            }

            if ($active_menu === false) {
                if (mb_strstr($toolmenu['link'], $optname .'='. $opt)) {
                    $active_menu = true;
                }
            }
            
            $sub_menu = '';
            
            if (!empty($toolmenu['items'])) {
                $sub_menu .= '<ul class="dropdown-menu" role="menu">';
                        
                foreach ($toolmenu['items'] as $item) {
                    if ($active_menu === false) {
                        if (mb_strstr($item['link'], $optname .'='. $opt)) {
                            $active_menu = $active_sub_menu = true;
                        }
                    }
                    
                    $sub_menu .= '<li class="'. ($active_sub_menu === true ? 'active' : '') .'"><a href="'. $item['link'] .'" class="uittip" title="'. htmlspecialchars($item['title']) .'" '. (!empty($item['target']) ? 'target="'. $item['target'] .'"' : '') .'><img src="images/toolmenu/'. $item['icon'] .'" /> '. $item['title'] .'</a></li>';

                    $active_sub_menu = false;
                }
                
                $sub_menu .= '</ul>';
            }
            
            $target = isset($toolmenu['target']) ? 'target="'. $toolmenu['target'].'"' : '';
            
            $html .= '<li class="'. ($active_menu === true ? 'active' : '') .''. (!empty($toolmenu['items']) ? ' dropdown' : '') .'"><a href="'. $toolmenu['link'] .'" class="uittip '. (!empty($toolmenu['items']) ? ' dropdown-toggle" data-toggle="dropdown"' : '"') .' title="'. htmlspecialchars($toolmenu['title']) .'" '. (!empty($toolmenu['target']) ? 'target="'. $toolmenu['target'] .'"' : '') .'><img src="images/toolmenu/'. $toolmenu['icon'] .'" /></a>'. (!empty($toolmenu['items']) ? $sub_menu : '') .'</li>';
            
            if ($active_menu === true) {
                $active_menu = $active_sub_menu = null;
            }
        }

        $html .= '</ul></nav>';
    }
    
    

    return;
}

function cpProceedBody(){
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

function cpBody(){
    echo $GLOBALS['cp_page_body'];
    return;
}

//////////////////////////////////////////////// PATHWAY ///////////////////////////////////////////////////////
function cpAddPathway($title, $link){
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

    $filters = 0; $f_html = '';
    //Find and render filters
    foreach($_fields as $key => $value) {
        if (isset($_fields[$key]['filter'])) {
            $f_html .= '<div class="form-group"><label>'. $_fields[$key]['title'] .': </label>';
            
            $initval = false;
            if (isset($filter[$_fields[$key]['field']])) {
                $initval =  $filter[$_fields[$key]['field']];
            }

            $inputname = 'filter['.$_fields[$key]['field'].']';
            if (!isset($_fields[$key]['filterlist'])) {
                $f_html .= '<input type="text" class="form-control" style="margin-right:10px;margin-left:5px;" name="'. $inputname .'" size="'. $_fields[$key]['filter'] .'" value="'. ($initval === false ? '' : $initval) .'" />';
            } else {
                $f_html .= cpBuildList($inputname, $_fields[$key]['filterlist'], $initval);
            }
            
            $f_html .= '</div>';

            $filters += 1;
            $_SERVER['QUERY_STRING'] = str_replace('filter['.$_fields[$key]['field'].']=', '', $_SERVER['QUERY_STRING']);
        }
    }
    
    //draw filters
    if ($filters > 0) {
        echo '<div class="panel panel-default"><div class="panel-body" style="padding:0;">';
        echo '<form class="form-inline navbar-form navbar-left" name="filterform" action="index.php?'. $_SERVER['QUERY_STRING'] .'" method="POST" role="search">';
        echo '';
        echo $f_html;
        echo '<button type="submit" class="btn btn-default">'. $_LANG['AD_FILTER'] .'</button>';
        if (!empty($f)) {
            echo '<button onclick="window.location.href=\'index.php?'. $_SERVER['QUERY_STRING'] .'&nofilter\'; return false;" class="btn btn-default" style="margin-left:10px;">'. $_LANG['AD_ALL'] .'</button>';
        }
        echo '</form>';
        echo '</div></div>';
    }

    if (cmsCore::c('db')->num_rows($result)) {
        //DRAW LIST TABLE
        echo '<form name="selform" action="index.php?view='.cmsAdmin::getApplet().'&do=saveorder" method="post">';
            echo '<table class="table table-striped tablesorter">';
                //TABLE HEADING
                echo '<thead>'."\n";
                    echo '<tr>'."\n";
                        echo '<th width="20" class="lt_header" style="vertical-align:middle;"><a class="lt_header_link" href="javascript:invert();" title="'.$_LANG['AD_INVERT_SELECTION'].'">#</a></th>'. "\n";
                        foreach($_fields as $key => $value) {
                            echo '<th width="'.$_fields[$key]['width'].'" class="lt_header'. (is_array($_fields[$key]['field']) ? '' : ' header') .' '. ($_fields[$key]['field'] == $sort ? ( $so == 'asc' ? 'headerSortDown' : 'headerSortUp' ) : '') .'" style="vertical-align:middle;">';
                                if (!is_array($_fields[$key]['field'])) {
                                    echo '<a href="index.php?'. cpAddParam($_SERVER['QUERY_STRING'], array( 0 => 'sort', 1 => 'so'), array( 0 => $_fields[$key]['field'], 1 => ($so == 'asc' ? 'desc' : 'asc'))) .'">'. $_fields[$key]['title'] .'</a>';
                                } else {
                                    echo $_fields[$key]['title'];
                                }
                            echo '</th>'. "\n";
                        }
                        if ($is_actions) {
                            echo '<th width="80" class="lt_header" style="vertical-align:middle;">'.$_LANG['AD_ACTIONS'].'</th>'. "\n";
                        }
                    echo '</tr>'."\n";
                echo '</thead><tbody>'."\n";
                //TABLE BODY
                    $r = 0;
                    while ($item = cmsCore::c('db')->fetch_assoc($result)) {
                        $r++;
                        if ($r % 2) { $row_class = 'lt_row1'; } else { $row_class = 'lt_row2'; }
                        echo '<tr id="'. $row_class .'">'."\n";
                        echo '<td class="'.$row_class.'" align="center" valign="middle"><input type="checkbox" name="item[]" value="'.$item['id'].'" /></td>'. "\n";
                        foreach($_fields as $key => $value) {
                            if (isset($_fields[$key]['link'])) {
                                $link = str_replace('%id%', $item['id'], $_fields[$key]['link']);
                                if (isset($_fields[$key]['prc'])) {
                                    // функция обработки под названием $_fields[$key]['prc']
                                    // какие параметры передать функции - один ключ или произвольный массив ключей
                                    if (is_array($_fields[$key]['field'])) {
                                        foreach ($_fields[$key]['field'] as $func_field) {
                                            $in_func_array[$func_field] = $item[$func_field];
                                        }
                                        
                                        $data = call_user_func($_fields[$key]['prc'], $in_func_array);
                                    } else {
                                        $data = call_user_func($_fields[$key]['prc'], $item[$_fields[$key]['field']]);
                                    }
                                } else {
                                    $data = $item[$_fields[$key]['field']];
                                    if (isset($_fields[$key]['maxlen'])) {
                                        if (mb_strlen($data) > $_fields[$key]['maxlen']) {
                                            $data = mb_substr($data, 0, $_fields[$key]['maxlen']).'...';
                                        }
                                    }
                                }

                                //nested sets otstup
                                if (isset($item['NSLevel']) && ($_fields[$key]['field']=='title' || (is_array($_fields[$key]['field']) && in_array('title', $_fields[$key]['field'])))) {
                                    $otstup = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', ($item['NSLevel']-1));
                                    if ($item['NSLevel']-1 > 0) { $otstup .=  ' &raquo; '; }
                                } else { $otstup = ''; }

                                if ($table != 'cms_components') {
                                    echo '<td class="'.$row_class.'" valign="middle">'.$otstup.'<a class="lt_link" href="'.$link.'">'.$data.'</a></td>'. "\n";
                                } else {
                                    $data = function_exists('cpComponentHasConfig') && cpComponentHasConfig($item['link']) ?
                                    '<a class="lt_link" href="'.$link.'">'.$data.'</a>' :
                                    $data;

                                    echo '<td class="'.$row_class.'" valign="middle">
                                        <span class="lt_link" style="padding:1px; padding-left:24px; background:url(/admin/images/components/'.$item['link'].'.png) no-repeat">'.$data.'</span>
                                  </td>'. "\n";
                                }
                            } else {
                                if ($_fields[$key]['field'] != 'ordering') {
                                    if ($_fields[$key]['field'] == 'published') {
                                        if (isset($_fields[$key]['do'])) { $do = $_fields[$key]['do']; } else { $do = 'do'; }
                                        if (isset($_fields[$key]['do_suffix'])) { $dos = $_fields[$key]['do_suffix']; $ids = 'item_id'; } else { $dos = ''; $ids = 'id'; }
                                        if ($item['published']) {
                                            $qs = cpAddParam($_SERVER['QUERY_STRING'], $do, 'hide'.$dos);
                                            $qs = cpAddParam($qs, $ids, $item['id']);
                                            $qs2 = cpAddParam($_SERVER['QUERY_STRING'], $do, 'show'.$dos);
                                            $qs2 = cpAddParam($qs2, $ids, $item['id']);
                                            $qs = "pub(".$item['id'].", '".$qs."', '".$qs2."', 'off', 'on');";
                                            echo '<td class="'.$row_class.'" valign="middle">
                                                    <a title="'.$_LANG['HIDE'].'" class="uittip" id="publink'.$item['id'].'" href="javascript:'.$qs.'"><img id="pub'.$item['id'].'" src="images/actions/on.gif" border="0"/></a>
                                                  </td>'. "\n";
                                        } else {
                                            $qs = cpAddParam($_SERVER['QUERY_STRING'], $do, 'show'.$dos);
                                            $qs = cpAddParam($qs, $ids, $item['id']);
                                            $qs2 = cpAddParam($_SERVER['QUERY_STRING'], $do, 'hide'.$dos);
                                            $qs2 = cpAddParam($qs2, $ids, $item['id']);
                                            $qs = "pub(".$item['id'].", '".$qs."', '".$qs2."', 'on', 'off');";
                                            echo '<td class="'.$row_class.'" valign="middle">
                                                    <a title="'.$_LANG['SHOW'].'" class="uittip" id="publink'.$item['id'].'" href="javascript:'.$qs.'"><img id="pub'.$item['id'].'" src="images/actions/off.gif" border="0"/></a>
                                                  </td>'. "\n";
                                        }
                                    } else {
                                        if (isset($_fields[$key]['prc'])) {
                                            // функция обработки под названием $_fields[$key]['prc']
                                            // какие параметры передать функции - один ключ или произвольный массив ключей
                                            if (is_array($_fields[$key]['field'])) {
                                                foreach ($_fields[$key]['field'] as $func_field) {
                                                    $in_func_array[$func_field] = $item[$func_field];
                                                }
                                                $data = call_user_func($_fields[$key]['prc'], $in_func_array);
                                            } else {
                                                $data = call_user_func($_fields[$key]['prc'], $item[$_fields[$key]['field']]);
                                            }
                                            if (is_array($data) && isset($data['link'])) {
                                                $data = str_replace('%id%', $item['id'], $data['link']);
                                            }
                                        } else {
                                            $data = $item[$_fields[$key]['field']];
                                            if (isset($_fields[$key]['maxlen'])) {
                                                if (mb_strlen($data)>$_fields[$key]['maxlen']) {
                                                    $data = mb_substr($data, 0, $_fields[$key]['maxlen']).'...';
                                                }
                                            }
                                        }

                                        //nested sets otstup
                                        if (isset($item['NSLevel']) && ($_fields[$key]['field'] == 'title' || (is_array($_fields[$key]['field']) && in_array('title', $_fields[$key]['field'])))) {
                                            $otstup = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', ($item['NSLevel']-1));
                                            if ($item['NSLevel']-1 > 0) { $otstup .=  ' &raquo; '; }
                                        } else { $otstup = ''; }

                                        echo '<td class="'.$row_class.'" valign="middle">'.$otstup.$data.'</td>'. "\n";
                                    }
                                } else {
                                    if (isset($_fields[$key]['do'])) { $do = 'do=config&id='.(int)$_REQUEST['id'].'&'.$_fields[$key]['do']; } else { $do = 'do'; }
                                    if (isset($_fields[$key]['do_suffix'])) { $dos = $_fields[$key]['do_suffix']; $ids = 'item_id'; } else { $dos = ''; $ids = 'id'; }
                                    echo '<td class="'.$row_class.'" valign="middle">
                                            <a title="'.$_LANG['AD_DOWN'].'" href="?view='.cmsAdmin::getApplet().'&'.$do.'=move_down&co='.$item[$_fields[$key]['field']].'&'.$ids.'='.$item['id'].'"><img src="images/actions/down.gif" border="0"/></a>';
                                    if ($table != 'cms_menu' && $table != 'cms_category'){
                                        echo '<input class="lt_input" type="text" size="4" name="ordering[]" value="'.$item['ordering'].'" />';
                                        echo '<input name="ids[]" type="hidden" value="'.$item['id'].'" />';
                                    } else {
                                        echo '<input class="lt_input" type="text" size="4" name="ordering[]" value="'.$item['ordering'].'" disabled/>';
                                    }

                                    echo '<a title="'.$_LANG['AD_UP'].'" href="?view='.cmsAdmin::getApplet().'&'.$do.'=move_up&co='.$item[$_fields[$key]['field']].'&'.$ids.'='.$item['id'].'"><img src="images/actions/top.gif" border="0"/></a>'.
                                                        '</td>'. "\n";
                                }
                            }
                        }

                        if ($is_actions) {
                            echo '<td width="110" class="'.$row_class.'" align="right" valign="middle"><div style="padding-right:8px">';
                            
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
                                        $link = str_replace('%'.$f.'%', $v, $link);
                                    }
                                    
                                    if (!isset($action['confirm'])) {
                                        echo '<a href="'. $link .'" class="uittip" title="'. $title .'"'. (isset($action['target']) ? ' target="'. $action['target'] .'"' : '') .'><img hspace="2" src="images/actions/'. $icon .'" border="0" alt="'. $title .'"/></a>';
                                    } else {
                                        echo '<a href="#" class="uittip" onclick="jsmsg(\''. $action['confirm'] .'\', \''. $link .'\')"  title="'. $title .'"><img hspace="2" src="images/actions/'. $icon .'" border="0" alt="'. $title .'"/></a>';
                                    }
                                }
                            }

                            echo '</div></td>'. "\n";
                        }

                        echo '</tr>'."\n";
                    }
                    
        echo '</tbody></table></form>';
        echo '<style>tr#lt_row2{ background:#eeeeee !important; } tr#lt_row1:hover td,tr#lt_row2:hover{ background:#cccccc !important; }</style>';
        echo '<script type="text/javascript">trClickChecked();</script>';

        $link = '?view='. cmsAdmin::getApplet();
        
        if ($sort) {
            $link .= '&sort='.$sort;
            if (cmsCore::inRequest('so')) { $link .= '&so='.cmsCore::request('so'); }
        }

        echo cmsPage::getPagebar($total, $page, $perpage, $_SERVER['PHP_SELF'] .'?'. cpAddParam($_SERVER['QUERY_STRING'], 'page', '%page%'));
    } else {
        echo '<p class="cp_message">'.$_LANG['OBJECTS_NOT_FOUND'].'</p>';
    }
}

//////////////////////////////////////// LIST TABLE PROCESSORS ///////////////////////////////////////////////////////////////////

function cpForumCatById($id){
	$result = cmsCore::c('db')->query("SELECT title FROM cms_forum_cats WHERE id = $id") ;

	if (cmsCore::c('db')->num_rows($result)) {
		$cat = cmsCore::c('db')->fetch_assoc($result);
		return '<a href="index.php?view=components&do=config&id='.(int)$_REQUEST['id'].'&opt=edit_cat&item_id='.$id.'">'.$cat['title'].'</a> ('.$id.')';
	} else { return '--'; }

}

function cpFaqCatById($id){
	$result = cmsCore::c('db')->query("SELECT title FROM cms_faq_cats WHERE id = $id") ;

	if (cmsCore::c('db')->num_rows($result)) {
		$cat = cmsCore::c('db')->fetch_assoc($result);
		return '<a href="index.php?view=components&do=config&id='.(int)$_REQUEST['id'].'&opt=edit_cat&item_id='.$id.'">'.$cat['title'].'</a>';
	} else { return '--'; }

}

function cpCatalogCatById($id){
	$result = cmsCore::c('db')->query("SELECT title, parent_id FROM cms_uc_cats WHERE id = $id") ;

	if (cmsCore::c('db')->num_rows($result)) {
		$cat = cmsCore::c('db')->fetch_assoc($result);
        if ($cat['parent_id']){
            return '<a href="index.php?view=components&do=config&id='.(int)$_REQUEST['id'].'&opt=edit_cat&item_id='.$id.'">'.$cat['title'].'</a> ('.$id.')';
        } else {
            return $cat['title'];
        }
	} else { return '--'; }

}

function cpBoardCatById($id){
	$result = cmsCore::c('db')->query("SELECT title FROM cms_board_cats WHERE id = $id") ;

	if (cmsCore::c('db')->num_rows($result)) {
		$cat = cmsCore::c('db')->fetch_assoc($result);
		return '<a href="index.php?view=components&do=config&id='.(int)$_REQUEST['id'].'&opt=edit_cat&item_id='.$id.'">'.$cat['title'].'</a> ('.$id.')';
	} else { return '--'; }

}

function cpGroupById($id){

    if(isset($GLOBALS['groups'][$id])){
        $title = $GLOBALS['groups'][$id];
    } else {
        $title = cmsUser::getGroupTitle($id);
        $GLOBALS['groups'][$id] = $title;
    }

	return '<a href="index.php?view=usergroups&do=edit&id='.$id.'">'.$title.'</a>';

}

function cpCatById($id){
	$result = cmsCore::c('db')->query("SELECT title, parent_id FROM cms_category WHERE id = $id") ;

	if (cmsCore::c('db')->num_rows($result)) {
		$cat = cmsCore::c('db')->fetch_assoc($result);
        if ($cat['parent_id']){
            return '<a href="index.php?view=cats&do=edit&id='.$id.'">'.$cat['title'].'</a> ('.$id.')';
        } else {
            return $cat['title'];
        }
	} else { return '--'; }

}

function cpModuleById($id){
	$sql = "SELECT content FROM cms_modules WHERE id = $id AND is_external = 1";
	$result = cmsCore::c('db')->query($sql);
	if (cmsCore::c('db')->num_rows($result)) { $mod = cmsCore::c('db')->fetch_assoc($result); return $mod['content']; }
	else { return false; }
}

function cpModuleTitleById($id){
	$sql = "SELECT name FROM cms_modules WHERE id = $id";
	$result = cmsCore::c('db')->query($sql);
	if (cmsCore::c('db')->num_rows($result)) { $mod = cmsCore::c('db')->fetch_assoc($result); return $mod['name']; }
	else { return false; }
}

function cpTemplateById($template_id){
    global $_LANG;
	if ($template_id) { return $template_id; } else { return '<span style="color:silver">'.$_LANG['AD_AS_SITE'].'</span>'; }

}

function cpUserNick($user_id=0){
    global $_LANG;
	if ($user_id){
		$sql = "SELECT nickname FROM cms_users WHERE id = $user_id";
		$result = cmsCore::c('db')->query($sql);
		if (cmsCore::c('db')->num_rows($result)) { $usr = cmsCore::c('db')->fetch_assoc($result); return $usr['nickname']; }
		else { return false; }
	} else {
		return '<em style="color:gray">'.$_LANG['AD_NOT_DEFINED'].'</em>';
	}
}

function cpYesNo($option){
    global $_LANG;
	if ($option) { return '<span style="color:green">'.$_LANG['YES'].'</span>'; } else { return '<span style="color:red">'.$_LANG['NO'].'</span>'; }
}

//////////////////////////////////////////////// DATABASE //////////////////////////////////////////////////////////
function dbMoveUp($table, $id, $current_ord){
    $id = (int)$id;
    $current_ord = (int)$current_ord;
	$sql = "UPDATE $table SET ordering = ordering + 1 WHERE ordering = ($current_ord-1) LIMIT 1";
	cmsCore::c('db')->query($sql) ;
	$sql = "UPDATE $table SET ordering = ordering - 1 WHERE id = $id LIMIT 1";
	cmsCore::c('db')->query($sql) ;
}
function dbMoveDown($table, $id, $current_ord){
    $id = (int)$id;
    $current_ord = (int)$current_ord;
    $sql = "UPDATE $table SET ordering = ordering - 1 WHERE ordering = ($current_ord+1) LIMIT 1";
    cmsCore::c('db')->query($sql) ;
    $sql = "UPDATE $table SET ordering = ordering + 1 WHERE id = $id LIMIT 1";
    cmsCore::c('db')->query($sql) ;
}

function dbDelete($table, $id){
    $inCore = cmsCore::getInstance();
    $id = (int)$id;
	$sql = "DELETE FROM $table WHERE id = $id LIMIT 1";
	cmsCore::c('db')->query($sql) ;
	if ($table=='cms_content'){
		cmsClearTags('content', $id);
        $inCore->deleteRatings('content', $id);
        $inCore->deleteComments('article', $id);
		cmsCore::c('db')->query("DELETE FROM cms_tags WHERE target='content' AND item_id=$id");
	}
	if ($table=='cms_modules'){
		cmsCore::c('db')->query("DELETE FROM cms_modules_bind WHERE module_id=$id");
	}
}
function dbDeleteList($table, $list){
	if (is_array($list)){
		$sql = "DELETE FROM $table WHERE ";
		$item = 0;
		foreach($list as $key => $value){
			$item ++;
            $value = (int)$value;
			$sql .= 'id = '.$value;
			if ($item<sizeof($list)) { $sql .= ' OR '; }
			if ($table=='cms_content'){
				cmsClearTags('content', $value);
				cmsCore::c('db')->query("DELETE FROM cms_comments WHERE target='article' AND target_id=$value");
				cmsCore::c('db')->query("DELETE FROM cms_ratings WHERE target='content' AND item_id=$value");
				cmsCore::c('db')->query("DELETE FROM cms_tags WHERE target='content' AND item_id=$value");
			}
			if ($table=='cms_modules'){
				cmsCore::c('db')->query("DELETE FROM cms_modules_bind WHERE module_id=$value");
			}
		}
		$sql .= ' LIMIT '.sizeof($list);
		cmsCore::c('db')->query($sql) ;
	}
}

///////////////////////////////////////////// HTML GENERATORS ////////////////////////////////////////////////
function insertPanel() {
    global $_LANG;
    $p_html = cmsCore::callEvent('REPLACE_PANEL', array('html' => ''));

    if ($p_html['html']) { return $p_html['html']; }

    $inCore=cmsCore::getInstance();

    $submit_btn = '<input type="button" class="btn btn-default" style="width:100px" value="'. $_LANG['AD_INSERT'] .'" onClick="insertTag(document.addform.ins.options[document.addform.ins.selectedIndex].value)">';

echo '<table border="0" class="table" style="margin:0;"><tr><td style="border:0;">';
	echo '<table border="0" class="table" style="margin:0;">';
	echo '<tr>';
		echo '<td width="120">';
			echo '<label>'. $_LANG['AD_INSERT'] .':</label> ';
		echo '</td>';
		echo '<td width="">';
			echo '<select id="ins" style="width:99%" class="form-control" name="ins" onChange="showIns()">
					<option value="frm" selected="selected">'.$_LANG['AD_FORM'].'</option>
					<option value="include">'.$_LANG['FILE'].'</option>
					<option value="filelink">'.$_LANG['AD_LINK_DOWNLOAD_FILE'].'</option>';
                    if ($inCore->isComponentInstalled('banners')) {
                        echo '<option value="banpos">'. $_LANG['AD_BANNER_POSITION'] .'</option>';
                    }
		    echo   '<option value="pagebreak">-- '. $_LANG['AD_PAGEBREAK'] .' --</option>
					<option value="pagetitle">-- '. $_LANG['AD_PAGETITLE'] .' --</option>
				  </select>';
		echo '</td>';
        echo '<td width="100">&nbsp;</td>';
	echo '</tr>';
	echo '<tr id="frm">';
		echo '<td width="120">
                    <label>'. $_LANG['AD_FORM'] .':</label>
              </td>';
        echo '<td>
                    <select class="form-control" style="width:99%" name="fm">'. $inCore->getListItems('cms_forms') .'</select>
              </td>';
        echo '<td width="100">'. $submit_btn .'</td>';
    echo '</tr>';
	echo '<tr id="include">';
		echo '<td width="120">
                    <label>'. $_LANG['FILE'] .':</label>
              </td>';
        echo '<td style="vertical-align: middle;">
                    /includes/myphp/<input type="text" class="form-control" style="width:300px;display:inline-block;" name="i" value="myscript.php" />
              </td>';
        echo '<td width="100">'. $submit_btn .'</td>';
    echo '</tr>';
	echo '<tr id="filelink">';
		echo '<td width="120">
                    <label>'. $_LANG['FILE'] .':</label>
              </td>';
        echo '<td>
                    <input type="text" class="form-control" name="fl" value="/files/myfile.rar" />
              </td>';
        echo '<td width="100">'. $submit_btn .'</td>';
    echo '</tr>';
    if ($inCore->isComponentInstalled('banners')){
        $inCore->loadModel('banners');
        echo '<tr id="banpos">';
            echo '<td width="120">
                        <label>'. $_LANG['AD_POSITION'] .':</label>
                  </td>';
            echo '<td>
                        <select class="form-control" style="width:99%" name="ban">'. cms_model_banners::getBannersListHTML() .'</select>
                  </td>';
            echo '<td width="100">'. $submit_btn .'</td>';
        echo '</tr>';
    }
	echo '<tr id="pagebreak">';
		echo '<td width="120">
                    <label>'. $_LANG['TAG'] .':</label>
              </td>';
        echo '<td>
                    {pagebreak}
              </td>';
        echo '<td width="100">'. $submit_btn .'</td>';
    echo '</tr>';
	echo '<tr id="pagetitle">';
		echo '<td width="120">
                    <label>'. $_LANG['AD_TITLE'] .':</label>
              </td>';
        echo '<td>
                    <input type="text" class="form-control" style="width:99%" name="ptitle" />
              </td>';
        echo '<td width="100">'. $submit_btn .'</td>';
    echo '</tr>';


	echo '</table>';

   echo '</td></tr></table>';

   echo '<script type="text/javascript">showIns();</script>';

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function cpBuildList($attr_name, $list, $selected_id=false){
    global $_LANG;
    $html = '';

    $html .= '<select id="'. $attr_name .'" class="form-control" style="margin-right:10px;margin-left:5px;" name="'. $attr_name .'">' . "\n";

    $html .= '<option value="-100">-- '. $_LANG['AD_ALL'] .' --</option>'."\n";

    foreach($list as $key=>$value) {
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

function getFullAwardsList(){
    $awards = array();

    $rs = cmsCore::c('db')->query("SELECT title FROM cms_user_awards GROUP BY title");

    if (cmsCore::c('db')->num_rows($rs)){
        while($aw = cmsCore::c('db')->fetch_assoc($rs)){
            $awards[] = $aw;
        }
    }

    $rs = cmsCore::c('db')->query("SELECT title FROM cms_user_autoawards GROUP BY title");

    if (cmsCore::c('db')->num_rows($rs)){
        while($aw = cmsCore::c('db')->fetch_assoc($rs)){
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
function files_remove_directory($directory, $is_clear=false){
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
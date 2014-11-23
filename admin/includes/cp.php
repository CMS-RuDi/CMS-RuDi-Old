<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.4                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

defined('VALID_CMS_ADMIN') or die();

function cpAccessDenied(){
    cmsCore::redirect('/admin/index.php?view=noaccess');
}

function cpWarning($text){
    global $_LANG;
    return '<div id="warning"><span>'.$_LANG['ATTENTION'].': </span>'.$text.'</div>';
}

function cpWritable($file){ //relative path with starting "/"
    if (is_writable(PATH.$file)){
        return true;
    } else {
        return @chmod(PATH.$file, 0777);
    }
}

function cpCheckWritable($file, $type='file'){
    global $_LANG;
	if (!cpWritable($file)){
		if ($type=='file'){
			echo cpWarning(sprintf($_LANG['FILE_NOT_WRITABLE'], $file));
		} else {
			echo cpWarning(sprintf($_LANG['DIR_NOT_WRITABLE'], $file));
		}
	}
}

/////////////////////////// PAGE GENERATION ////////////////////////////////////////////////////////////////
function cpHead(){
    /* Костыль скоро будет удален */
    if (!empty($GLOBALS['cp_page_title'])) {
        cmsCore::c('page')->setAdminTitle($GLOBALS['cp_page_title']);
    }
    foreach($GLOBALS['cp_page_head'] as $key=>$value) {
        cmsCore::c('page')->addHead($value);
        unset ($GLOBALS['cp_page_head'][$key]);
    }
    /******************************/
    
    cmsCore::c('page')->printAdminHead();

    return;
}

function cpMenu(){
    global $_LANG;
    global $adminAccess;

    $inCore = cmsCore::getInstance();

    ob_start(); ?>

    <nav class="navbar navbar-default navbar-collapse" role="navigation" style="margin-bottom:0;">
        <ul class="nav navbar-nav">
            <?php if (cmsUser::isAdminCan('admin/menu', $adminAccess)) { ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="index.php?view=menu">
                    <i class="fa fa-list"></i>
                    <?php echo $_LANG['AD_MENU']; ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a class="fa fa-plus-circle" href="index.php?view=menu&do=add">
                            <?php echo $_LANG['AD_MENU_POINT_ADD']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-plus-circle" href="index.php?view=menu&do=addmenu">
                            <?php echo $_LANG['AD_MENU_ADD']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-ellipsis-h" href="index.php?view=menu">
                            <?php echo $_LANG['AD_SHOW_ALL']; ?>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <?php if (cmsUser::isAdminCan('admin/modules', $adminAccess)) { ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="index.php?view=modules">
                    <i class="fa fa-th"></i>
                    <?php echo $_LANG['AD_MODULES']; ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a class="fa fa-cube" href="index.php?view=install&do=module">
                            <?php echo $_LANG['AD_MODULES_SETUP']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-plus-circle" href="index.php?view=modules&do=add">
                            <?php echo $_LANG['AD_MODULE_ADD']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-ellipsis-h" href="index.php?view=modules">
                            <?php echo $_LANG['AD_SHOW_ALL']; ?>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <?php if (cmsUser::isAdminCan('admin/content', $adminAccess)) { ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="index.php?view=tree">
                    <i class="fa fa-folder-open"></i>
                    <?php echo $_LANG['AD_ARTICLE_SITE']; ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a class="fa fa-file-text" href="index.php?view=tree">
                            <?php echo $_LANG['AD_ARTICLES']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-archive" href="index.php?view=arhive">
                            <?php echo $_LANG['AD_ARTICLES_ARCHIVE']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-plus-circle" href="index.php?view=cats&do=add">
                            <?php echo $_LANG['AD_CREATE_SECTION']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-plus-circle" href="index.php?view=content&do=add">
                            <?php echo $_LANG['AD_CREATE_ARTICLE']; ?>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <?php if (cmsUser::isAdminCan('admin/components', $adminAccess)) { ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="index.php?view=components">
                    <i class="fa fa-cubes"></i>
                    <?php echo $_LANG['AD_COMPONENTS']; ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a class="fa fa-cube" href="index.php?view=install&do=component">
                            <?php echo $_LANG['AD_INSTALL_COMPONENTS']; ?>
                        </a>
                    </li>
                <?php
                    $components   = $inCore->getAllComponents();
                    $showed_count = 0;
                    $total_count  = count($components);

                    if ($total_count) {
                        foreach ($components as $com) {
                            if ($com['published'] && (file_exists('components/'. $com['link'] .'/backend.php')) && cmsUser::isAdminCan('admin/com_'. $com['link'], $adminAccess)) { ?>

                                <li>
                                    <a class="fa" href="index.php?view=components&do=config&link=<?php echo $com['link']; ?>">
                                        <img src="/admin/images/components/<?php echo $com['link']; ?>.png" class="com_icon" />
                                        <?php echo $com['title']; ?>
                                    </a>
                                </li>

                <?php
                                $showed_count++;
                            }
                        }
                    }

                    if ($total_count != $showed_count && cmsCore::c('user')->id == 1) {
                ?>
                        <li>
                            <a class="fa fa-ellipsis-h" href="index.php?view=components">
                                <?php echo $_LANG['AD_SHOW_ALL']; ?>
                            </a>
                        </li>
                <?php
                    }
                ?>
                </ul>
            </li>
            <?php } ?>

            <?php if (cmsUser::isAdminCan('admin/plugins', $adminAccess)) { ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-puzzle-piece"></i>
                    <?php echo $_LANG['AD_ADDITIONS']; ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a class="fa fa-cube" href="index.php?view=install&do=plugin">
                            <?php echo $_LANG['AD_INSTALL_PLUGINS']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-puzzle-piece" href="index.php?view=plugins">
                            <?php echo $_LANG['AD_PLUGINS']; ?>
                        </a>
                    </li>
                    <?php if (cmsUser::isAdminCan('admin/filters', $adminAccess)) { ?>
                        <li>
                            <a class="fa fa-filter" href="index.php?view=filters">
                                <?php echo $_LANG['AD_FILTERS']; ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>

            <?php if (cmsUser::isAdminCan('admin/users', $adminAccess)) { ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="index.php?view=users">
                    <i class="fa fa-users"></i>
                    <?php echo $_LANG['AD_USERS']; ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a class="fa fa-user" href="index.php?view=users">
                            <?php echo $_LANG['AD_USERS']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-ban" href="index.php?view=userbanlist">
                            <?php echo $_LANG['AD_BANLIST']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-users" href="index.php?view=usergroups">
                            <?php echo $_LANG['AD_USERS_GROUP']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-plus-circle" href="index.php?view=users&do=add">
                            <?php echo $_LANG['AD_USER_ADD']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-plus-circle" href="index.php?view=usergroups&do=add">
                            <?php echo $_LANG['AD_CREATE_GROUP']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-cogs" href="index.php?view=components&do=config&link=users">
                            <?php echo $_LANG['AD_PROFILE_SETTINGS']; ?>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <?php if (cmsUser::isAdminCan('admin/config', $adminAccess)) { ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="index.php?view=config">
                    <i class="fa fa-cogs"></i>
                    <?php echo $_LANG['AD_SETTINGS']; ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a class="fa fa-cogs" href="index.php?view=config">
                            <?php echo $_LANG['AD_SITE_SETTING']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-sitemap" href="index.php?view=repairnested">
                            <?php echo $_LANG['AD_CHECKING_TREES']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-clock-o" href="index.php?view=cron">
                            <?php echo $_LANG['AD_CRON_MISSION']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-info-circle" href="index.php?view=phpinfo">
                            <?php echo $_LANG['AD_PHP_INFO']; ?>
                        </a>
                    </li>
                    <li>
                        <a class="fa fa-trash-o" href="index.php?view=clearcache">
                            <?php echo $_LANG['AD_CLEAR_SYS_CACHE']; ?>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </nav>
    <?php echo ob_get_clean();

    return;
}

function cpToolMenu($toolmenu_list, $opt=false, $optname='opt') {
    if ($toolmenu_list) {
        $opt = cmsCore::request($optname, 'str', $opt);
        
        echo '<nav class="navbar navbar-default" role="navigation"><ul class="nav nav-tabs">';

        foreach ($toolmenu_list as $toolmenu) {
            if (!$toolmenu) {
                echo '<div class="toolmenuseparator"></div>'; continue;
            }

            $sel = '';
            if (!empty($opt)) {
                if (mb_strstr($toolmenu['link'], $optname .'='. $opt)) {
                    $sel = ' class="active"';
                    unset($opt);
                }
            }
            
            $target = isset($toolmenu['target']) ? 'target="'. $toolmenu['target'].'"' : '';
            
            echo '<li'. $sel .'><a href="'. $toolmenu['link'] .'" class="uittip" title="'. $toolmenu['title'] .'" '. $target .'><img src="images/toolmenu/'. $toolmenu['icon'] .'" /></a></li>';
        }

        echo '</ul></nav>';
    }

    return;
}

function cpProceedBody(){
    ob_start();
    
    $file = $GLOBALS['applet'] .'.php';

    if (!file_exists(PATH .'/admin/applets/'. $file)){
        cmsCore::error404();
    }

    cmsCore::loadLanguage('admin/applets/applet_'. $GLOBALS['applet']);
    
    include('applets/'. $file);

    call_user_func('applet_'. $GLOBALS['applet']);

    $GLOBALS['cp_page_body'] = ob_get_clean();
}

function cpBody(){
    echo $GLOBALS['cp_page_body'];
    return;
}

//////////////////////////////////////////////// PATHWAY ///////////////////////////////////////////////////////
function cpPathway() {
    if (count($GLOBALS['cp_pathway']) <= 1) { return; }
    
    echo '<ol class="breadcrumb">';
	foreach($GLOBALS['cp_pathway'] as $key => $value) {
            echo '<li'. ($key == count($GLOBALS['cp_pathway']) ? ' class="active"' : '') .'><a href="'. $GLOBALS['cp_pathway'][$key]['link'] .'">'. $GLOBALS['cp_pathway'][$key]['title'] .'</a></li>';
	}
    echo '</ol>';
}

function cpAddPathway($title, $link){
	$already = false;
    if (empty($link)) { $link = htmlspecialchars($_SERVER['REQUEST_URI']); }

	foreach($GLOBALS['cp_pathway'] as $key => $val){
	 if ($GLOBALS['cp_pathway'][$key]['title'] == $title || $GLOBALS['cp_pathway'][$key]['link'] == $link){
	 	$already = true;
	 }
	}

	if(!$already){
		$next = sizeof($GLOBALS['cp_pathway']);
		$GLOBALS['cp_pathway'][$next]['title'] = $title;
		$GLOBALS['cp_pathway'][$next]['link'] = $link;
	}

	return true;
}

function cpModulePositions($template){

	$pos = array();

	$posfile = PATH.'/templates/'.$template.'/positions.txt';

	if(file_exists($posfile)){
		$file = fopen($posfile, 'r');
		while(!feof($file)){
			$str = fgets($file);
			$str = str_replace("\n", '', $str);
			$str = str_replace("\r", '', $str);
			if (!mb_strstr($str, '#') && mb_strlen($str)>1){
				$pos[] = $str;
			}
		}
		fclose($file);
		return $pos;
	} else {
		return false;
	}

}

function cpAddParam($query, $param, $value){
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

function cpListTable($table, $_fields, $_actions, $where='', $orderby='title', $perpage=22) {
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
        echo '<form name="selform" action="index.php?view='.$GLOBALS['applet'].'&do=saveorder" method="post">';
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
                                $data = $item[$_fields[$key]['field']];

                                if (isset($_fields[$key]['maxlen'])) {
                                    if (mb_strlen($data)>$_fields[$key]['maxlen']) {
                                        $data = mb_substr($data, 0, $_fields[$key]['maxlen']).'...';
                                    }
                                }

                                //nested sets otstup
                                if (isset($item['NSLevel']) && $_fields[$key]['field']=='title') {
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
                                        if (isset($item['NSLevel']) && $_fields[$key]['field']=='title') {
                                            $otstup = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', ($item['NSLevel']-1));
                                            if ($item['NSLevel']-1 > 0) { $otstup .=  ' &raquo; '; }
                                        } else { $otstup = ''; }

                                        echo '<td class="'.$row_class.'" valign="middle">'.$otstup.$data.'</td>'. "\n";
                                    }
                                } else {
                                    if (isset($_fields[$key]['do'])) { $do = 'do=config&id='.(int)$_REQUEST['id'].'&'.$_fields[$key]['do']; } else { $do = 'do'; }
                                    if (isset($_fields[$key]['do_suffix'])) { $dos = $_fields[$key]['do_suffix']; $ids = 'item_id'; } else { $dos = ''; $ids = 'id'; }
                                    echo '<td class="'.$row_class.'" valign="middle">
                                            <a title="'.$_LANG['AD_DOWN'].'" href="?view='.$GLOBALS['applet'].'&'.$do.'=move_down&co='.$item[$_fields[$key]['field']].'&'.$ids.'='.$item['id'].'"><img src="images/actions/down.gif" border="0"/></a>';
                                    if ($table != 'cms_menu' && $table != 'cms_category'){
                                        echo '<input class="lt_input" type="text" size="4" name="ordering[]" value="'.$item['ordering'].'" />';
                                        echo '<input name="ids[]" type="hidden" value="'.$item['id'].'" />';
                                    } else {
                                        echo '<input class="lt_input" type="text" size="4" name="ordering[]" value="'.$item['ordering'].'" disabled/>';
                                    }

                                    echo '<a title="'.$_LANG['AD_UP'].'" href="?view='.$GLOBALS['applet'].'&'.$do.'=move_up&co='.$item[$_fields[$key]['field']].'&'.$ids.'='.$item['id'].'"><img src="images/actions/top.gif" border="0"/></a>'.
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

        $link = '?view='. $GLOBALS['applet'];
        
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
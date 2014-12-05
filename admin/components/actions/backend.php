<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }
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
    cmsCore::loadClass('actions');
    $opt = cmsCore::request('opt', 'str', 'list');

    $act_components = cmsActions::getActionsComponents();
    $act_component  = cmsCore::request('act_component', 'str', '');

    if ($opt != 'config') {
?>
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav nav-tabs">
            <li>
                <a class="uittip" href="?view=components&do=config&id=<?php echo $id; ?>&opt=config" title="<?php echo $_LANG['AD_CONFIG']; ?>"><img src="images/toolmenu/config.gif" /></a>
            </li>
            <li>
                <form id="filter_form" class="navbar-form navbar-left" action="?view=components&do=config&id=<?php echo $id; ?>" method="post">
                    <?php echo $_LANG['AD_ACTIONS_FROM']; ?>:
                    <select class="form-control" style="width:215px" name="act_component" onchange="$('#filter_form').submit()">
                        <option value="" <?php if(!$act_component){ ?>selected="selected"<?php } ?>><?php echo $_LANG['AD_ACTIONS_FROM_ALL_COM']; ?></option>
                        <?php foreach($act_components as $act_com) {
                            if ($act_com['link'] == $act_component) {
                                echo '<option value="'. $act_com['link'] .'" selected="selected">'. $act_com['title'] .'</option>';
                            } else {
                                echo '<option value="'. $act_com['link'] .'">'. $act_com['title'] .'</option>';
                            }
                        } ?>
                    </select>
                </form>
            </li>
        </ul>
    </nav>
<?php
    }

    if ($opt == 'list') {
        $page    = cmsCore::request('page', 'int', 1);
        $perpage = 15;
        
        cmsCore::c('actions')->showTargets(true);
        
        if ($act_component) {
            cmsCore::c('db')->where("a.component = '". $act_component ."'");
        }

        $total = cmsCore::c('actions')->getCountActions();

        cmsCore::c('db')->limitPage($page, $perpage);

        $actions = cmsCore::c('actions')->getActionsLog();

        $pagebar = cmsPage::getPagebar($total, $page, $perpage, '?view=components&do=config&id='.$id.'&opt=list&page=%page%');

        $tpl_file   = 'admin/actions.php';
        $tpl_dir    = file_exists(TEMPLATE_DIR.$tpl_file) ? TEMPLATE_DIR : DEFAULT_TEMPLATE_DIR;

        include($tpl_dir.$tpl_file);
    }

    if ($opt == 'saveconfig') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        $cfg = array();

        $cfg['show_target'] = cmsCore::request('show_target', 'int', 1);
        $cfg['perpage']     = cmsCore::request('perpage', 'int', 10);
        $cfg['perpage_tab'] = cmsCore::request('perpage_tab', 'int', 15);
        $cfg['is_all']      = cmsCore::request('is_all', 'int', 0);
       	$cfg['act_type']    = cmsCore::request('act_type', 'array_str', array());
        $cfg['meta_keys']   = cmsCore::request('meta_keys', 'str', '');
        $cfg['meta_desc']   = cmsCore::request('meta_desc', 'str', '');

        $inCore->saveComponentConfig('actions', $cfg);

        cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'], 'success');

        cmsCore::redirect('?view=components&do=config&id='.$id.'&opt=config');
    }

    if ($opt == 'config') {
        cpAddPathway($_LANG['AD_SETTINGS'], '?view=components&do=config&id='.$id.'&opt=config');
        
        echo '<h3>'. $_LANG['AD_SETTINGS'] .': '. $com['title'] .'</h3>';

        $sql = "SELECT * FROM cms_actions ORDER BY title LIMIT 100";

        $result = cmsCore::c('db')->query($sql);
?>
    <div style="width:650px;">
	<form action="index.php?view=components&do=config&id=<?php echo $id;?>&opt=saveconfig" method="post" name="optform" target="_self" id="form1">
            <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_SHOW_TARGET']; ?>:</label>
                <div class="btn-group" data-toggle="buttons" style="vertical-align:top;float:right;">
                    <label class="btn btn-default <?php if (cmsCore::m('actions')->config['show_target']) { echo 'active'; } ?>">
                        <input type="radio" name="show_target" <?php if (cmsCore::m('actions')->config['show_target']) { echo 'checked="checked"'; } ?> value="1" /> <?php echo $_LANG['YES']; ?>
                    </label>
                    <label class="btn btn-default <?php if (!cmsCore::m('actions')->config['show_target']) { echo 'active'; } ?>">
                        <input type="radio" name="show_target" <?php if (!cmsCore::m('actions')->config['show_target']) { echo 'checked="checked"'; } ?> value="0" /> <?php echo $_LANG['NO']; ?>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_COUNT_ACTIONS_PAGE']; ?>:</label>
                <input class="form-control" name="perpage" size=5 value="<?php echo cmsCore::m('actions')->config['perpage'];?>" />
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_COUNT_ACTIONS_TAB']; ?>:</label>
                <input class="form-control" name="perpage_tab" size=5 value="<?php echo cmsCore::m('actions')->config['perpage_tab'];?>" />
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_ACTIONS_TYPE']; ?>:</label>
                <div class="param-links">
                    <label for="is_all"><input type="checkbox" id="is_all" name="is_all" value="1" <?php if(cmsCore::m('actions')->config['is_all']) {?>checked="checked" <?php }?> /> <a href="javascript:void(0);" onclick="$('#act_list label input:checkbox, #is_all').prop('checked', true);"><?php echo $_LANG['SELECT_ALL']; ?></a></label> |
                    <a href="javascript:void(0);" onclick="$('#act_list label input:checkbox, #is_all').prop('checked', false);"><?php echo $_LANG['REMOVE_ALL']; ?></a>
                </div>
                <div id="act_list" style="margin-left:30px;">
                    <?php
                        if (cmsCore::c('db')->num_rows($result)){
                            while($option = cmsCore::c('db')->fetch_assoc($result)){
                                echo '<label><input type="checkbox" id="act_type_'. $option['name'] .'" name="act_type['. $option['name'] .']" value="'. $option['id'] .'" '.(in_array($option['id'], cmsCore::getArrVal(cmsCore::m('actions')->config, 'act_type', array())) ? 'checked="checked"' : '') .' />'. $option['title'] .'</label><br/>';
                            }
                        }
                    ?>
                </div>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_ROOT_METAKEYS']; ?>:</label>
                <div class="help-block"><?php echo $_LANG['AD_FROM_COMMA']; ?></div>
                <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::m('actions')->config['meta_keys'] ?></textarea>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_ROOT_METADESC']; ?>:</label>
                <div class="help-block"><?php echo $_LANG['SEO_METADESCR_HINT']; ?></div>
                <textarea class="form-control" name="meta_keys" rows="2"><?php echo cmsCore::m('actions')->config['meta_desc'] ?></textarea>
            </div>
            
            <p>
                <input type="submit" id="save" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
                <input type="button" id="back" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=components';"/>
            </p>
        </form>
    </div>
<?php
    }
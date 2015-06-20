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

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_arhive() {
    $inCore = cmsCore::getInstance();
    
    global $_LANG;
    
    cmsCore::c('page')->setTitle($_LANG['AD_ARTICLES_ARCHIVE']);
    
    $cfg = $inCore->loadComponentConfig('content');
    $cfg_arhive = $inCore->loadComponentConfig('arhive');

    cpAddPathway($_LANG['AD_ARTICLE_SITE'], 'index.php?view=tree');
    cpAddPathway($_LANG['AD_ARTICLES_ARCHIVE'], 'index.php?view=arhive');

    $do = cmsCore::request('do', 'str', 'list');
    $id = cmsCore::request('id', 'int', -1);

    if ($do == 'saveconfig') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }
        
        $cfg['source'] = cmsCore::request('source', 'str', '');
        $inCore->saveComponentConfig('arhive', $cfg);
        
        cmsCore::addSessionMessage($_LANG['AD_CONFIG_SAVE_SUCCESS'] , 'success');
        cmsCore::redirect('?view=arhive&do=config');
    }
    
    if ($do == 'config') {
        $toolmenu = array(
            array( 'icon' => 'folders.gif', 'title' => $_LANG['AD_LIST_OF_ARTICLES'], 'link' => '?view=arhive' )
        );
        
        cpToolMenu($toolmenu);
        cpAddPathway($_LANG['AD_SETTINGS'], 'index.php?view=arhive&do=config');
?>
<form action="index.php?view=arhive&do=saveconfig" method="post" name="optform" target="_self" id="form1">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div style="width:550px">
        <div class="form-group">
            <label><?php echo $_LANG['AD_SOURCE_MATERIALS']; ?></label>
            <select class="form-control" name="source">
                <option value="content" <?php if ($cfg_arhive['source'] == 'content') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_ARTICLE_SITE']; ?></option>
                <option value="arhive" <?php if ($cfg_arhive['source'] == 'arhive') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_ARTICLES_ARCHIVE']; ?></option>
                <option value="both" <?php if ($cfg_arhive['source'] == 'both') { echo 'selected="selected"'; } ?>><?php echo $_LANG['AD_CATALOG_AND_ARCHIVE']; ?></option>
            </select>
        </div>
    </div>
    
    <div>
        <input type="hidden" name="opt" value="saveconfig" />
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=arhive';" />
    </div>
</form>
<?php
    }

    if ($do == 'list') {
        $toolmenu = array(
            array( 'icon' => 'config.gif', 'title' => $_LANG['AD_SETTINGS'], 'link' => '?view=arhive&do=config' ),
            array( 'icon' => 'delete.gif', 'title' => $_LANG['AD_DELETE_SELECTED'], 'link' => "javascript:checkSel('?view=arhive&do=delete&multiple=1');" )
        );

        cpToolMenu($toolmenu);

        //TABLE COLUMNS
        $fields = array(
            array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
            array( 'title' => $_LANG['AD_CREATE'], 'field' => 'pubdate', 'width' => '80', 'filter' => 15, 'fdate' => '%d/%m/%Y' ),
            array( 'title' => $_LANG['TITLE'], 'field' => 'title', 'width' => '', 'link' => '?view=content&do=edit&id=%id%', 'filter' => 15 ),
            array( 'title' => $_LANG['AD_PARTITION'], 'field' => 'category_id', 'width' => '150', 'filter' => 1, 'prc' => 'cpCatById', 'filterlist' => cpGetList('cms_category') )
        );

        //ACTIONS
        $actions = array(
            array( 'title' => $_LANG['AD_TO_ARTICLES_CATALOG'], 'icon' => 'arhive_off.gif', 'link' => '?view=arhive&do=arhive_off&id=%id%' ),
            array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'link' => '?view=content&do=delete&id=%id%', 'confirm' => $_LANG['AD_DELETE_MATERIALS'] )
        );

        //Print table
        cpListTable('cms_content', $fields, $actions, 'is_arhive=1');
    }
    
    if ($do == 'arhive_off') {
        if (cmsCore::inRequest('id')) {
            cmsCore::c('db')->setFlag('cms_content', $id, 'is_arhive', '0');
            cmsCore::redirect('?view=arhive');
        }
    }

    if ($do == 'delete') {
        if (!cmsCore::inRequest('item')) {
            if ($id >= 0) {
                cmsCore::m('content')->deleteArticle($id, $cfg['af_delete']);
            }
        } else {
            cmsCore::m('content')->deleteArticles(cmsCore::request('item', 'array_int'), $cfg['af_delete']);
        }
        cmsCore::redirect('?view=arhive');
    }
}
<?php

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function applet_robots() {
    global $_LANG;
    global $adminAccess;
    
    if (!cmsUser::isAdminCan('admin/robots', $adminAccess)) { cpAccessDenied(); }
    
    cmsCore::c('page')->setAdminTitle($_LANG['ROBOTS_TITLE']);

    cpAddPathway($_LANG['ROBOTS_TITLE']);

    $do = cmsCore::request('do', array('edit', 'save'), 'edit');

    if (!file_exists(PATH .'/robots.txt')) {
        $fp = fopen(PATH .'/robots.txt', 'w');
        fwrite($fp, str_replace(array('%domen%', '%host%'), array(str_replace(array('https://', 'http://'), '', cmsCore::c('config')->host), cmsCore::c('config')->host), file_get_contents(PATH .'/includes/default_robots.txt')));
        fclose ($fp);
        chmod(PATH .'/robots.txt', 0777);
    }

    if ($do == 'save') {
        if (!cmsUser::checkCsrfToken()) { cmsCore::error404(); }

        $data = cmsCore::request('robots', 'str');
        
        $fp = fopen(PATH .'/robots.txt', 'w');
        fwrite($fp, stripcslashes($data) ."\n");
        fclose ($fp);
    }

    $robots = file_get_contents(PATH .'/robots.txt');
?>
<form action="" method="post">
    <div style="width:650px;">
        <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
        
        <div class="form-group">
            <label><?php echo $_LANG['ROBOTS_TXT_DATA']; ?></label>
            <textarea name="robots" class="form-control" style="height: 400px;"><?php echo $robots; ?></textarea>
            <div class="help-block"><?php echo $_LANG['ROBOTS_TXT_INFO']; ?></div>
        </div>
        
        <input type="hidden" name="do" value="save" />
        
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SAVE']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.history.back();" />
    </div>
</form>
<?php
}
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
if(!defined('VALID_CMS_ADMIN')) { die(); }

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html> 
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $_LANG['AD_ADMIN_PANEL']; ?></title>
        <link href="/includes/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/includes/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/includes/bootstrap/js/bootstrap.min.js"></script>
    </head>

    <body>
        <div class="container" style="width:400px;bottom:0;height:270px;left:0;margin:auto;position:absolute;right:0;top:0;">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $_LANG['AD_AUTH']; ?></h3>
                </div>
                <div class="panel-body">
                    <form action="/login" method="post" role="form">
                        <input type="hidden" name="is_admin" value="1" />
                        
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" style="width:36px;"><span class="fa fa-user"></span></span>
                                <input type="text" class="form-control" style="width:300px;" placeholder="<?php echo $_LANG['AD_AUTH_LOGIN']; ?>" required="true"  autofocus="true" name="login" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" style="width:36px;"><span class="fa fa-lock"></span></span>
                                <input type="password" class="form-control" style="width:300px;" placeholder="<?php echo $_LANG['AD_AUTH_PASS']; ?>" required="true" name="pass" />
                            </div>
                        </div>

                        <div class="form-group" style="text-align:right;">
                            <button type="submit" class="btn btn-labeled btn-success">
                                <span class="btn-label"><i class="fa fa-check"></i></span><?php echo $_LANG['AD_DO_AUTH']; ?>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                    <div style="text-align:center;">
                        &copy; <a href="http://cmsrudi.ru/">CMS RuDi</a>, <?php echo date('Y'); ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
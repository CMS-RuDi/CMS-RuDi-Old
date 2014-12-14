<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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

cmsCore::c('page')->addHeadCSS('admin/css/styles.css?17');
cmsCore::c('page')->addHeadCSS('admin/js/hmenu/hmenu.css');
cmsCore::c('page')->addHeadCSS('includes/jquery/tablesorter/style.css');
cmsCore::c('page')->addHeadCSS('includes/jqueryui/css/smoothness/jquery-ui.min.css');

cmsCore::c('page')->addHeadJS('admin/js/admin.js');
cmsCore::c('page')->addHeadJS('includes/jquery/jquery.columnfilters.js');
cmsCore::c('page')->addHeadJS('includes/jquery/tablesorter/jquery.tablesorter.min.js');
cmsCore::c('page')->addHeadJS('includes/jquery/jquery.preload.js');
cmsCore::c('page')->addHeadJS('includes/jqueryui/jquery-ui.min.js');
cmsCore::c('page')->addHeadJS('includes/jqueryui/init-ui.js');
cmsCore::c('page')->addHeadJS('includes/jqueryui/i18n/jquery.ui.datepicker-'. cmsConfig::getConfig('lang') .'.min.js');
cmsCore::c('page')->addHeadJS('includes/jquery/jquery.form.js');
cmsCore::c('page')->addHeadJS('admin/js/hltable.js');

cmsCore::c('page')->addHeadCSS('includes/bootstrap/css/bootstrap.min.css');
cmsCore::c('page')->addHeadCSS('includes/bootstrap/css/bootstrap-theme.min.css');
cmsCore::c('page')->addHeadJS('includes/bootstrap/js/bootstrap.min.js');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <?php cpHead(); ?>
        
        <style type="text/css">
            .hoverRow { color:#FF3300; background-color:#CFFFFF;}
            .clickedRow { color:#009900; background-color:#FFFFCC;}
        </style>
    </head>

    <body>
        <div class="main_body">
            <div id="container">
                <div id="header" style="height:50px">
                    <nav class="navbar navbar-inverse navbar-collapse" role="navigation" style="margin-bottom:0;">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="/admin/">
                                    <i class="fa fa-home"></i>
                                    CMS RuDi v<?php echo CMS_RUDI_V .': '. $_LANG['AD_ADMIN_PANEL']; ?>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-question-circle"></i>
                                    <?php echo $_LANG['AD_HELP']; ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a class="fa fa-question" href="http://cmsrudi.ru/docs" target="_blank">
                                            <?php echo $_LANG['AD_DOCS']; ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="fa fa-ticket" href="/admin/index.php?view=tickets" >
                                            <?php echo $_LANG['AD_TICKETS']; ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="fa fa-external-link" href="/" target="_blank">
                                    <?php echo $_LANG['AD_OPEN_SITE']; ?>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-user"></i>
                                    <?php echo cmsCore::c('db')->get_field('cms_users', 'id='.cmsCore::c('user')->id, 'nickname'); ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#" onclick="return false;">
                                            <i class="fa fa-info-circle"></i>
                                            IP: <?php echo cmsCore::c('user')->ip; ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo cmsUser::getProfileURL(cmsCore::c('user')->login); ?>" target="_blank" title="<?php echo $_LANG['AD_GO_PROFILE']; ?>">
                                            <i class="fa fa-user"></i>
                                            <?php echo $_LANG['TEMPLATE_MY_PROFILE']; ?>
                                        </a>
                                        <?php
                                            $new_messages = cmsCore::c('user')->getNewMsg();
                                            if ($new_messages['total']) {
                                                echo '<a href="/users/'. cmsCore::c('user')->id .'/messages.html" class="fa fa-envelope-o">'. $_LANG['AD_NEW_MSG'] .' ('. $new_messages['total'] .')</a>';
                                            }
                                        ?>
                                    </li>
                                    <li>
                                        <a class="fa fa-power-off" href="/logout">
                                            <?php echo $_LANG['AD_EXIT']; ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
                
                <?php
                    cpMenu();
                    cpPathway();
                ?>
                
                <?php $messages = cmsCore::getSessionMessages(true);
                if ($messages) { ?>
                    <?php
                        foreach($messages as $msg){
                            $type = $msg['type'] == 'error' ? 'danger' : $msg['type'];
                            ?>
                            <div class="alert alert-<?php echo $type; ?> alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $_LANG['CLOSE']; ?></span></button>
                                <?php echo $msg['msg']; ?>
                            </div>
                        <?php
                        }
                    ?>
                <?php } ?>
                
                <div id="body" style="padding:0px 10px 10px 10px;">
                    <?php cpBody(); ?>
                </div>
            </div>
            
            <div class="well" style="text-align:center;">
                &copy; <a href="http://cmsrudi.ru/"><strong>CMS RuDi</strong></a><strong> v<?php echo CMS_RUDI_V; ?>, 2014</strong>
            </div>
        </div>
        
        <div class="modal fade" id="modalMsgBox" tabindex="-1" role="dialog" aria-labelledby="modalMsgBoxLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalMsgBoxLabel">Modal title</h4>
                    </div>
                    <div class="modal-body" id="modalMsgBoxBody"></div>
                    <div class="modal-footer">
                        <button type="button" id="modalMsgBoxCancel" class="btn btn-default" data-dismiss="modal"><?php echo $_LANG['CLOSE']; ?></button>
                        <button type="button" id="modalMsgBoxOk" class="btn btn-primary"><?php echo $_LANG['CONTINUE']; ?></button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
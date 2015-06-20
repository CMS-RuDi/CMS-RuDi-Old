<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function cpGetTicketCats() {
    $cats = cmsUser::sessionGet('ticket_cat');
    
    if (empty($cats)) {
        $result = cmsCore::c('db')->query('SELECT id,title FROM cms_ticket_cat');
        
        $cats = array(
            0 => array( 'id' => 0, 'title' => 'CMS RuDi' )
        );
        
        while($cat = cmsCore::c('db')->fetch_assoc($result)) {
            $cats[$cat['id']] = array( 'id' => $cat['id'], 'title' => $cat['title'] );
        }
        
        cmsUser::sessionPut('ticket_cat', $cats);
    }

    return $cats;
}

function cpTicketCategory($cat_id) {
    $cats = cpGetTicketCats();
    return $cats[$cat_id]['title'];
}

function cpTicketStatus($status) {
    global $_LANG;
    return '<span class="ticket_status_'. $status .'">'. $_LANG['AD_TICKET_STATUS_'. $status] .'</span>';
}

function cpCheckTicketClose($item) {
    return $item['status'] != 3 ? true : false;
}

function cpGetTicketStatusList() {
    global $_LANG;
    return array(
        array( 'id' => 1, 'title' => $_LANG['AD_TICKET_STATUS_1'] ),
        array( 'id' => 2, 'title' => $_LANG['AD_TICKET_STATUS_2'] ),
        array( 'id' => 3, 'title' => $_LANG['AD_TICKET_STATUS_3'] )
    );
}

function cpTicketPriority($priority) {
    global $_LANG;
    return '<span class="ticket_priority_'. $priority .'">'. $_LANG['AD_TICKET_PRIORITY_'. $priority] .'</span>';
}

function cpGetTicketPriorityList() {
    global $_LANG;
    return array(
        array( 'id' => 0, 'title' => $_LANG['AD_TICKET_PRIORITY_0'] ),
        array( 'id' => 1, 'title' => $_LANG['AD_TICKET_PRIORITY_1'] ),
        array( 'id' => 2, 'title' => $_LANG['AD_TICKET_PRIORITY_2'] ),
        array( 'id' => 3, 'title' => $_LANG['AD_TICKET_PRIORITY_3'] )
    );
}

function cpTicketAuthor($user_id) {
    if ($user_id == cmsCore::c('user')->id) {
        return cmsCore::c('user')->nickname;
    } else {
        return cmsCore::c('db')->get_field('cms_users', 'id='. $user_id, 'nickname');
    }
}

//==============================================================================

function applet_tickets() {
    global $adminAccess;

    if (!cmsUser::isAdminCan('admin/tickets', $adminAccess)) { cpAccessDenied(); }
    
    global $_LANG;
    
    cmsUser::sessionDel('ticket_cat');

    $do = cmsCore::request('do', 'str', 'list');
    $super_user = cmsCore::c('user')->id == 1;
    
    $toolmenu = array(
        array( 'icon' => 'new.gif', 'title' => $_LANG['AD_TICKET_CREATE'], 'link' => '?view=tickets&do=add' ),
        array( 'icon' => 'liststuff.gif', 'title' => $_LANG['AD_TICKET_LIST'], 'link' => '?view=tickets&do=list' )
    );

    cpToolMenu($toolmenu, 'list', 'do');
    
    cmsCore::c('page')->setTitle($_LANG['AD_TICKETS']);
    cpAddPathway($_LANG['AD_TICKETS'], 'index.php?view=tickets');
    
    if ($do == 'list') {
        $fields = array(
            array( 'title' => 'id', 'field' => 'id', 'width' => '40' ),
            array( 'title' => $_LANG['AD_TICKET_STATUS'], 'field' => 'status', 'width' => '100', 'filter' => 1, 'prc' => 'cpTicketStatus', 'filterlist' => cpGetTicketStatusList() ),
            array( 'title' => $_LANG['AD_TICKET_DATE'], 'field' => 'pubdate', 'width' => '80' ),
            array( 'title' => $_LANG['AD_TICKET_TITLE'], 'field' => 'title', 'width' => '', 'filter' => 32, 'link' => 'index.php?view=tickets&do=view&id=%id%' ),
            array( 'title' => $_LANG['AD_TICKET_LAST_MSG_DATE'], 'field' => 'last_msg_date', 'width' => '80' ),
            array( 'title' => $_LANG['AD_TICKET_CAT'], 'field' => 'cat_id', 'width' => '150', 'filter' => 1, 'prc' => 'cpTicketCategory', 'filter' => 1, 'filterlist' => cpGetTicketCats() ),
            array( 'title' => $_LANG['AD_TICKET_PRIORITY'], 'field' => 'priority', 'width' => '100', 'filter' => 1, 'prc' => 'cpTicketPriority', 'filterlist' => cpGetTicketPriorityList() )
        );
        
        if ($super_user) {
            $fields[] = array( 'title' => $_LANG['AD_TICKET_USER'], 'field' => 'user_id', 'width' => '110', 'prc' => 'cpTicketAuthor' );
        }

        $actions = array(
            array( 'title' => $_LANG['AD_TICKET_CLOSE'], 'icon' => 'off.gif', 'link' => '?view=tickets&do=close_ticket&id=%id%', 'condition' => 'cpCheckTicketClose' ),
            array( 'title' => $_LANG['DELETE'], 'icon' => 'delete.gif', 'link' => '?view=tickets&do=delete&id=%id%', 'confirm' => $_LANG['AD_TICKET_DELETE'] )
        );

        cpListTable('cms_ticket', $fields, $actions, $super_user ? '' : 'user_id='. cmsCore::c('user')->id, 'last_msg_date DESC', 30);
    }
    
    if ($do == 'delete') {
        $id = cmsCore::request('id', 'int', 0);
        $item = cmsCore::c('db')->get_fields('cms_ticket', 'id='. $id, '*');
        
        if (!empty($item)) {
            $server = cmsCore::c('db')->get_field('cms_ticket_cat', 'id='. $item['cat_id'], 'server');
            if (empty($server)) { $server = 'http://ds-soft.ru/tickets.api.php'; }
            
            //Удаляем сам тиккет
            cmsCore::c('db')->delete('cms_ticket', 'id='. $item['id']);
            
            //Удаляем все сообщения тиккета
            cmsCore::c('db')->delete('cms_ticket_msg', 'ticket_id='. $item['id']);
            
            //Удаляем все прикрепленные изображения тиккета
            cmsCore::deleteUploadImages($item['id'], 'ticket');
            
            if ($item['status'] != '3') {
                //Отправляем сообщение на сервер техподдержки что тикет удален
                cmsCore::c('curl')->ajax()->request('post', $server .'?do=ticket_deleted', array( 'ticket_id' => $item['id'], 'ticket_secret_key' => $item['secret_key'], 'host' => cmsCore::c('config')->host ));
            }
            
            cmsCore::addSessionMessage($_LANG['AD_TICKET_DELETE_SUCCESS'], 'success');
        } else {
            cmsCore::addSessionMessage($_LANG['AD_TICKET_ERROR'], 'error');
        }
        
        cmsCore::redirect('index.php?view=tickets');
    }
    
    if ($do == 'close_ticket') {
        $id = cmsCore::request('id', 'int', 0);
        $item = cmsCore::c('db')->get_fields('cms_ticket', 'id='. $id, '*');
        
        if (!empty($item)) {
            cmsCore::c('db')->setFlag('cms_ticket', $item['id'], 'status', '3');
            
            $server = cmsCore::c('db')->get_field('cms_ticket_cat', 'id='. $item['cat_id'], 'server');
            if (empty($server)) { $server = 'http://ds-soft.ru/tickets.api.php'; }
            
            //Отправляем сообщение на сервер техподдержки что тикет закрыт
            cmsCore::c('curl')->ajax()->request('post', $server .'?do=ticket_closed', array( 'ticket_id' => $item['id'], 'ticket_secret_key' => $item['secret_key'], 'host' => cmsCore::c('config')->host ));
            
            cmsCore::addSessionMessage($_LANG['AD_TICKET_CLOSE_SUCCESS'], 'success');
        } else {
            cmsCore::addSessionMessage($_LANG['AD_TICKET_ERROR'], 'error');
        }
        
        cmsCore::redirect('index.php?view=tickets');
    }
    
    if ($do == 'add') {
        cpAddPathway($_LANG['AD_TICKET_CREATE'], 'index.php?view=tickets&do=add');
        $cats = cpGetTicketCats();
?>
<form action="index.php?view=tickets&do=submit" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    
    <div class="panel panel-default" style="width:650px;">
        <div class="panel-body">
            <div class="form-group">
                <label><?php echo $_LANG['AD_TICKET_CAT']; ?></label>
                <select class="form-control" name="cat_id">
                    <?php foreach ($cats as $cat) { ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_TICKET_PRIORITY']; ?></label>
                <select class="form-control" name="priority">
                    <option value="0"><?php echo $_LANG['AD_TICKET_PRIORITY_0']; ?></option>
                    <option value="1"><?php echo $_LANG['AD_TICKET_PRIORITY_1']; ?></option>
                    <option value="2"><?php echo $_LANG['AD_TICKET_PRIORITY_2']; ?></option>
                    <option value="3"><?php echo $_LANG['AD_TICKET_PRIORITY_3']; ?></option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_TICKET_TITLE']; ?></label>
                <input type="text" class="form-control" name="title" value="" required="true" maxlength="256" />
            </div>
            
            <div class="form-group">
                <label><?php echo $_LANG['AD_TICKET_MSG']; ?></label>
                <textarea class="form-control" name="msg" style="height: 200px;"></textarea>
            </div>
        </div>
    </div>
    
    <div style="margin-top:5px">
        <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['AD_TICKET_SUBMIT']; ?>" />
        <input type="button" class="btn btn-default" name="back" value="<?php echo $_LANG['CANCEL']; ?>" onclick="window.location.href='index.php?view=tickets';" />
    </div>
</form>
<?php
    }
    
    if ($do == 'submit') {
        $cats = cpGetTicketCats();
        
        $item = array(
            'cat_id' => cmsCore::request('cat_id', 'int', 0),
            'priority' => cmsCore::request('priority', array(0,1,2,3), 0),
            'title' => cmsCore::request('title', 'str', ''),
            'msg' => cmsCore::request('msg', 'str', '')
        );
        
        if (!isset($cats[$item['cat_id']])) { $item['cat_id'] = 0; }
        
        if (!empty($item['title']) && !empty($item['msg'])) {
            $item['msg'] = cmsCore::c('db')->escape_string($item['msg']);
            $item['msg_count'] = 1;
            $item['pubdate'] = date('Y-m-d H:i:s');
            $item['last_msg_date'] = $item['pubdate'];
            $item['user_id'] = cmsCore::c('user')->id;
            
            $item['id'] = cmsCore::c('db')->insert('cms_ticket', $item);

            cmsCore::addSessionMessage($_LANG['AD_TICKET_CREATED'], 'success');
            
            $do = 'send';
        } else {
            cmsCore::addSessionMessage($_LANG['AD_TICKET_ERROR_2'], 'error');
            cmsCore::redirect('index.php?view=tickets&do=add');
        }
    }
    
    if ($do == 'send') {
        if (empty($item)) {
            $id = cmsCore::request('id', 'int', 0);
            $item = cmsCore::c('db')->get_fields('cms_ticket', 'id='. $id, '*');
        }
        
        if (!empty($item)) {
            $cat = cmsCore::c('db')->get_fields('cms_ticket_cat', 'id='. $item['cat_id'], '*');
            $server = !empty($cat['server']) ? $cat['server'] : 'http://ds-soft.ru/tickets.api.php';

            $ticket = array(
                'ticket_id' => $item['id'],
                'cat_id' => $item['cat_id'],
                'priority' => $item['priority'],
                'title' => $item['title'],
                'msg' => $item['msg'],
                'host' => cmsCore::c('config')->host,
                'module' => $cat['module']
            );
            
            if ($ticket['cat_id'] > 0 && !empty($cat['module'])) {
                $ticket['module'] = $cat['module'];
            }

            //Отправляем тикет на сервер техподдержки
            $result = cmsCore::c('curl')->ajax()->request('post', $server .'?do=add_ticket', $ticket)->json();
            
            if (!empty($result['error'])) {
                cmsCore::clearSessionMessages();
                cmsCore::addSessionMessage($result['error'], 'error');
                cmsCore::c('db')->delete('cms_ticket', 'id='. $item['id']);
            } else if (isset($result['secret_key'])) {
                cmsCore::c('db')->update(
                    'cms_ticket',
                    array('status' => 1, 'secret_key' => $result['secret_key']),
                    $item['id']
                );

                cmsCore::addSessionMessage($_LANG['AD_TICKET_SENDED'], 'success');
            } else {
                cmsCore::addSessionMessage($_LANG['AD_TICKET_UNKNOWN_ERROR'], 'error');
                cmsCore::c('db')->delete('cms_ticket', 'id='. $item['id']);
            }
        } else {
            cmsCore::addSessionMessage($_LANG['AD_TICKET_ERROR'], 'error');
        }
        
        cmsCore::redirect('index.php?view=tickets');
    }
    
    if ($do == 'view') {
        $id = cmsCore::request('id', 'int', 0);
        $item = cmsCore::c('db')->get_fields('cms_ticket', 'id='. $id, '*');
        
        if (empty($item) || ($item['user_id'] != cmsCore::c('user')->id && !$super_user)) {
            cmsCore::addSessionMessage($_LANG['AD_TICKET_ERROR'], 'error');
            cmsCore::redirect('index.php?view=tickets');
        }
        
        cpAddPathway($item['title'], 'index.php?view=tickets&do=view&id='. $item['id']);
        
        if ($item['msg_count'] > 1) {
            $item['msgs'] = array();
            $results = cmsCore::c('db')->query("SELECT * FROM cms_ticket_msg WHERE ticket_id=". $item['id'] ." ORDER BY pubdate ASC");
            if (cmsCore::c('db')->num_rows($results)) {
                while($msg = cmsCore::c('db')->fetch_assoc($results)) {
                    $msg['pubdate'] = cmsCore::dateFormat($msg['pubdate']);
                    $item['msgs'][] = $msg;
                }
            }
        }
        
        if ($item['status'] != 3) {
            switch($item['priority']) {
                case 0: $class = 'info'; break;
                case 1: $class = 'success'; break;
                case 2: $class = 'primary'; break;
                case 3: $class = 'danger'; break;
            }
        }else {
            $class = 'default';
        }
?>
<div class="panel panel-<?php echo $class; ?>" style="width:650px;">
    <div class="panel-heading">
        <h4>Тема: <?php echo $item['title']; ?></h4>
        <div><?php echo $item['msg']; ?></div>
    </div>
    
    <div class="panel-body">
        <?php if (!empty($item['msgs'])) {
            foreach ($item['msgs'] as $msg) {
        ?>
            <div style="text-align: <?php if (!empty($msg['support'])) { echo 'right'; } else { echo 'left'; } ?>;">
                <span>
                    <i class="fa fa-calendar-o"></i>
                    <?php echo $msg['pubdate']; ?>
                </span>
                <?php if (!empty($msg['support'])) { ?>
                    <span>
                        <i class="fa fa-user"></i>
                        <?php echo $msg['support']; ?>
                    </span>
                <?php } ?>
            </div>
            <div class="alert alert-warning" style="margin-<?php if (!empty($msg['support'])) { echo 'left'; } else { echo 'right'; } ?>: 50px;">
                <?php echo $msg['msg']; ?>
            </div>
        <?php } } ?>
    </div>
    
    <div class="panel-footer">
        <?php if ($item['msg_count'] > 1 && $item['status'] != 3) { ?>
            <form id="ticket_msg_add" action="index.php?view=tickets&do=submit_msg" method="post">
                <div class="form-group">
                    <label><?php echo $_LANG['AD_TICKET_MSG']; ?></label>
                    <textarea class="form-control" name="msg" style="height: 200px;"></textarea>
                </div>

                <div style="margin-top:5px">
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
                    <input type="submit" class="btn btn-primary" name="save" value="<?php echo $_LANG['SEND']; ?>" />
                    <input type="button" class="btn btn-warning" value="<?php echo $_LANG['AD_TICKET_CLOSE']; ?>" onclick="window.location.href='index.php?view=tickets&do=close_ticket&id=<?php echo $item['id']; ?>';return false;" />
                    <input type="button" class="btn btn-danger" value="<?php echo $_LANG['DELETE']; ?>" onclick="jsmsg('<?php echo $_LANG['AD_TICKET_DELETE']; ?>', '?view=tickets&do=delete&id=<?php echo $item['id']; ?>');" />
                    <input type="button" class="btn btn-default" value="<?php echo $_LANG['BACK']; ?>" onclick="window.location.href='index.php?view=tickets';return false;" />
                </div>
            </form>
        <?php } else { ?>
            <div>
                <?php if ($item['status'] != 3) { ?>
                    <input type="button" class="btn btn-warning" value="<?php echo $_LANG['AD_TICKET_CLOSE']; ?>" onclick="window.location.href='index.php?view=tickets&do=close_ticket&id=<?php echo $item['id']; ?>';return false;" />
                <?php } ?>
                <input type="button" class="btn btn-danger" value="<?php echo $_LANG['DELETE']; ?>" onclick="jsmsg('<?php echo $_LANG['AD_TICKET_DELETE']; ?>', '?view=tickets&do=delete&id=<?php echo $item['id']; ?>');" />
                <input type="button" class="btn btn-default" value="<?php echo $_LANG['BACK']; ?>" onclick="window.location.href='index.php?view=tickets';return false;" />
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('body').animate({ scrollTop: $('#ticket_msg_add').offset().top }, 1100);
    });
</script>
<?php
    }
    
    if ($do == 'submit_msg') {
        $id = cmsCore::request('id', 'int', 0);
        $item = cmsCore::c('db')->get_fields('cms_ticket', 'id='. $id, '*');
        
        if (empty($item) || ($item['user_id'] != cmsCore::c('user')->id && !$super_user)) {
            cmsCore::addSessionMessage($_LANG['AD_TICKET_ERROR'], 'error');
            cmsCore::redirect('index.php?view=tickets');
        }
        
        $msg = cmsCore::request('msg', 'str', '');
        $date = date('Y-m-d H:i:s');
        
        cmsCore::c('db')->insert('cms_ticket_msg', array('msg' => cmsCore::c('db')->escape_string($msg), 'ticket_id' => $item['id'], 'pubdate' => $date));
        
        cmsCore::c('db')->query("UPDATE `cms_tickets` SET `last_msg_date` = '". $date ."', `msg_count` = `msg_count`+1 WHERE `id` = '". $item['id'] ."'");
        
        $server = cmsCore::c('db')->get_field('cms_ticket_cat', 'id='. $item['cat_id'], 'server');
        if (empty($server)) { $server = 'http://ds-soft.ru/tickets.api.php'; }
        
        //Отправляем тикет на сервер техподдержки
        $result = cmsCore::c('curl')->ajax()->request('post', $server .'?do=add_ticket_msg', array( 'msg' => $msg, 'ticket_id' => $item['id'], 'secret_key' => $item['secret_key'], 'host' => cmsCore::c('config')->host ))->json();

        if (!empty($result['error'])) {
            cmsCore::addSessionMessage($result['error'], 'error');
        } else {
            cmsCore::addSessionMessage($_LANG['AD_TICKET_MSG_SENDED'], 'success');
        }
        
        cmsCore::redirect('index.php?view=tickets&do=view&id='. $item['id']);
    }
}
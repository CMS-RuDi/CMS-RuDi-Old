<div class="bar" style="padding:10px 10px;margin-top: 10px;">
    <div class="form-group">
        <label><?php echo $_LANG['VIDEOS']; ?>:</label>
        <div class="help-block">
            <?php echo $_LANG['VIDEOS_HINT']; ?> <b><?php echo $cfg['PIV_DOMENS']; ?></b>
        </div>
    </div>
    
    <div class="form-group">
        <?php echo $_LANG['INSERT_PLAYER_CODE']; ?>
 
        <div class="form-group">
            <textarea class="form-control" name="piv_video_code" style="height:150px;"></textarea>
        </div>
        
        <div class="form-group text-right">
            <input type="button" class="btn btn-primary" onclick="attachVideoCode();" value="<?php echo $_LANG['ATTACH']; ?>" />
        </div>
    </div>
    
    <div class="piv_video_list">
        <?php if (!empty($videos)) { ?>
            <?php foreach ($videos as $video) { ?>
                <div class="video_elm" id="PIV_<?php echo $video['id']; ?>">
                    <div class="ajax_del_action">
                        <b>{video#<?php echo $video['id']; ?>}</b>
                        <a href="#" onclick="deleteInsertedVideo(<?php echo $video['id']; ?>); return false;" title="<?php echo $_LANG['DELETE']; ?>"></a>
                    </div>
                    <div>
                        <?php echo $video['code']; ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    function deleteInsertedVideo(id){
        $.post('/plugins/p_insert_video/ajax/ajax.php', 'do=delete&target=<?php echo $target; ?>&target_id=<?php echo $target_id; ?>&video_id='+id, function (msg){ if (msg == 'OK'){ $('#PIV_'+id).remove(); }else{ core.alert(msg); } });
    }
    function attachVideoCode(){
        $.post('/plugins/p_insert_video/ajax/ajax.php', 'do=insert&target=<?php echo $target; ?>&target_id=<?php echo $target_id; ?>&code='+encodeURIComponent($('textarea[name=piv_video_code]').val()), function (msg){ var resp = JSON.parse(msg); if (resp.error){ core.alert(resp.error); }else{ $('.piv_video_list').append('<div id="PIV_'+ resp.id +'" class="video_elm"><div class="ajax_del_action"><b>{video#'+ resp.id +'}</b><a href="#" onclick="deleteInsertedVideo('+ resp.id +'); return false;" title="<?php echo $_LANG['DELETE']; ?>"></a></div><div>'+ resp.code +'</div></div>'); $('textarea[name=piv_video_code]').val(''); $('.piv_video_list').scrollTop(document.getElementsByClassName('piv_video_list')[0].scrollHeight); } });
    }
</script>
<style type="text/css">
    .piv_video_list{
        position: relative;
        max-height: 500px;
        overflow-x: hidden;
        overflow-y: auto;
    }
    .ajax_del_action a {
        display: block;
        position: absolute;
        background: url('/images/icons/delete.gif');
        right: 0px;
        top: 0px;
        width: 16px;
        height: 16px;
    }
    .piv_video_list iframe, .piv_video_list object, .piv_video_list embed{
        max-width: 350px;
        max-height: 200px;
    }
    .ajax_del_action b{
        color: red;
    }
    .piv_video_list .video_elm{
        position: relative;
        float: left;
        width: 350px;
        margin: 0px 10px 10px 0px;
    }
</style>
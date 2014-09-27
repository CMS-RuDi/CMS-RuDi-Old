<div class="bar" style="padding:10px 10px;margin-top: 10px;">
    <div>
        <strong>{$LANG.VIDEOS}:</strong>
        <div class="hinttext">
            {$LANG.VIDEOS_HINT} <b>{$cfg.PIV_DOMENS}</b>
        </div>
    </div>
    <div style="margin-top:10px;">
        {$LANG.INSERT_PLAYER_CODE}
        <textarea name="piv_video_code" style="width:97%"></textarea>
        <div align="right">
            <input type="button" class="button" onclick="attachVideoCode();" value="{$LANG.ATTACH}" />
        </div>
    </div>
    <div class="piv_video_list">
        {if $videos}
            {foreach item=video from=$videos}
                <div class="video_elm" id="PIV_{$video.id}">
                    <div class="ajax_del_action">
                        <b>{literal}{{/literal}video#{$video.id}{literal}}{/literal}</b>
                        <a href="#" onclick="deleteInsertedVideo({$video.id}); return false;" title="{$LANG.DELETE}"></a>
                    </div>
                    <div>
                        {$video.code}
                    </div>
                </div>
            {/foreach}
        {/if}
    </div>
</div>
            
            

{literal}
<script type="text/javascript">
    function deleteInsertedVideo(id){
        $.post('/plugins/p_insert_video/ajax/ajax.php', 'do=delete&target={/literal}{$target}{literal}&target_id={/literal}{$target_id}{literal}&video_id='+id, function (msg){ if (msg == 'OK'){ $('#PIV_'+id).remove(); }else{ core.alert(msg); } });
    }
    function attachVideoCode(){
        $.post('/plugins/p_insert_video/ajax/ajax.php', 'do=insert&target={/literal}{$target}{literal}&target_id={/literal}{$target_id}{literal}&code='+encodeURIComponent($('textarea[name=piv_video_code]').val()), function (msg){ var resp = JSON.parse(msg); if (resp.error){ core.alert(resp.error); }else{ $('.piv_video_list').append('<div id="PIV_'+ resp.id +'" class="video_elm"><div class="ajax_del_action"><b>{video#'+ resp.id +'}</b><a href="#" onclick="deleteInsertedVideo('+ resp.id +'); return false;" title="{/literal}{$LANG.DELETE}{literal}"></a></div><div>'+ resp.code +'</div></div>'); $('textarea[name=piv_video_code]').val(''); $('.piv_video_list').scrollTop(document.getElementsByClassName('piv_video_list')[0].scrollHeight); } });
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
{/literal}
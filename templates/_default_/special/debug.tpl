<div class="debug_info">
    <div class="debug_time">
        {$LANG.DEBUG_TIME_GEN_PAGE} {$time|number_format:4} {$LANG.DEBUG_SEC}
    </div>
    <div class="debug_memory">
        {$LANG.DEBUG_MEMORY} {$memory} {$LANG.SIZE_MB}
    </div>
    <div class="debug_query_count">
        <a href="#debug_query_show" class="ajaxlink debug_query_dump">{$LANG.DEBUG_QUERY_DB} {$q_count}</a>
    </div>
    <div id="debug_query_dump">
        <div id="debug_query_show">
            {foreach from=$q_dump item=sql}
            <div class="query">
                <div><b>{$sql.time|number_format:6}</b></div>
                <div class="src">{$sql.src}</div>
                {$sql.sql|nl2br}
            </div>
            {/foreach}
        </div>
    </div>
</div>
<script>
    $(function() {
        $('.debug_query_dump').colorbox({
            inline: true,
            width:"70%",
            maxHeight: "100%",
            transition:"none"
        });
    });
</script>
{if $actions}
{include file='actions/friends.tpl'}

{include file='actions/tab.tpl'}

{else}
    <p>{$LANG.FEED_DESC}</p>
    <p>{$LANG.FEED_EMPTY_TEXT}</p>
{/if}
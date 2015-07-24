{if $messages}
<div class="sess_messages" id="sess_messages">
    {foreach from=$messages item=message}
        <div class="message_{$message.type}">{$message.msg}</div>
    {/foreach}
</div>
{/if}
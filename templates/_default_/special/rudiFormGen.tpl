<div class="rudi_form">
    {if $insert_token}
        <input type="hidden" name="csrf_token" value="{csrf_token}" />
    {/if}
    {foreach from=$data item=dat}
        {include file='special/rudiFormGen_field.tpl' field=$dat}
    {/foreach}
</div>
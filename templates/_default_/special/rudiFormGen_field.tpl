{if $field.before}{$field.before}{/if}
    
{if $field.fields}
    {foreach from=$field.fields item=f}
        {include file='special/rudiFormGen_field.tpl' field=$f}
    {/foreach}
{else}
    <div class="form-group">
        {if $field.title}
            {if $field.type == 'radio'}
                <div><label>{$field.title}</label></div>
            {else}
                <label {if $field.type == 'btn_yes_no'}style="width:450px"{/if}>{$field.title}</label>
            {/if}
        {/if}

        {if $field.html}
            {$field.html}
        {/if}

        {if $field.options}
            {foreach from=$field.options item=option}
                <label style="margin-right:10px;">{$option.html} {$option.title}</label>
            {/foreach}
        {/if}

        {if $field.description}
            <div class="help-block">{$field.description}</div>
        {/if}
    </div>
{/if}

{if $field.after}{$field.after}{/if}
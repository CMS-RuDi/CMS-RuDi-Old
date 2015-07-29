{if $formObj.form.showtitle}
    <h3 class="userform_title">{$formObj.form.title}</h3>
{/if}

{if $formObj.form.description}
    <p>{$formObj.form.description}</p>
{/if}

{if !$formObj.form.only_fields}
    <form name="userform" enctype="multipart/form-data" action="{$formObj.form.form_action}" method="POST">
    <input type="hidden" name="form_id" value="{$formObj.form.id}">
    <input type="hidden" name="csrf_token" value="{csrf_token}" />
{/if}
    <table class="userform_table" cellpadding="3">
    {foreach from=$formObj.form_fields item=form_field}
        <tr>
            <td class="userform_fieldtitle">
        	{if $formObj.is_admin}
            	[<font color="gray">{$form_field.ordering}</font>]
        	{/if}
                {$form_field.title}
                {if $form_field.mustbe}
                    <span class="mustbe">*</span>
                {/if}

                {if $formObj.is_admin}
                    <span class="edit_links">
                    <a href="?view=components&do=config&id={$id}&opt=del_field&form_id={$formObj.form.id}&item_id={$form_field.id}" title="{$LANG.DELETE}"><img src="/admin/images/actions/delete.gif" border="0" /></a>
                    <a href="?view=components&do=config&id={$id}&opt=edit&item_id={$formObj.form.id}&field_id={$form_field.id}" title="{$LANG.EDIT_FIELD}"><img src="/admin/images/actions/edit.gif" border="0" /></a>
                    <a href="?view=components&do=config&id={$id}&opt=up_field&form_id={$formObj.form.id}&item_id={$form_field.id}" title="{$LANG.FIELD_MOVE_UP}"><img src="/admin/images/actions/top.gif" border="0" /></a>
                    <a href="?view=components&do=config&id={$id}&opt=down_field&form_id={$formObj.form.id}&item_id={$form_field.id}" title="{$LANG.FIELD_MOVE_DOWN}"><img src="/admin/images/actions/down.gif" border="0" /></a>
                    </span>
                {/if}
            </td>
        </tr>
        <tr><td>{$form_field.field}</td></tr>
    {/foreach}
    
    {if !$formObj.is_admin && !$formObj.form.only_fields}
        <tr>
             <td>{captcha}</td>
        </tr>
    {/if}
    
    {if !$formObj.form.only_fields}
        <tr><td><div style="margin-top:10px">
            <input type="submit" value="{$LANG.SEND}" />
        </div></td></tr>
    {/if}
    </table>
{if !$formObj.form.only_fields}
    </form>
{/if}

{if $formObj.is_admin}
<script type="text/javascript">
    $(function(){
        $('td.userform_fieldtitle').hover(
            function() {
                $(this).find('span.edit_links').fadeIn();
            },
            function() {
                $(this).find('span.edit_links').hide();
            }
        );
    });
</script>
{/if}
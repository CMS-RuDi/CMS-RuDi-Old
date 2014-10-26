{if $cfg.view_type == 'table'}
  {foreach key=aid item=user from=$users}
    <div class="mod_new_user">
        <div class="mod_new_user_avatar"><a href="{profile_url login=$user.login}"><img border="0" class="usr_img_small" src="{$user.avatar}" /></a></div>
        <div class="mod_new_user_link"><a href="{profile_url login=$user.login}">{$user.nickname}</a></div>
    </div>
  {/foreach}
{/if}

{if $cfg.view_type == 'hr_table'}
    {assign var="col" value="1"}
    <table cellspacing="5" border="0" width="100%">
          {foreach key=aid item=user from=$users}
            {if $col==1} <tr> {/if}
                    <td width="" class="new_user_avatar" align="center" valign="middle"><a href="{profile_url login=$user.login}" class="new_user_link" title="{$user.nickname|escape:'html'}"><img border="0" class="usr_img_small" src="{$user.avatar}" /></a><div class="mod_new_user_link"><a href="{profile_url login=$user.login}">{$user.nickname}</a></div>
                    </td>
            {if $col==$cfg.maxcool} </tr> {assign var="col" value="1"} {else} {math equation="x + 1" x=$col assign="col"} {/if}
          {/foreach}
    </table>
{/if}

{if $cfg.view_type == 'list'}
    {assign var="now" value="0"}
        {foreach key=aid item=user from=$users}
            <a href="{profile_url login=$user.login}" class="new_user_link">{$user.nickname}</a>
            {math equation="x + 1" x=$now assign="now"}
            {if $now==$total}{else} ,{/if}
        {/foreach}
        <p><strong>{$LANG.LASTREG_TOTAL}:</strong> {$total_all}</p>
{/if}
{assign var="col" value="1"}
<table cellpadding="2" cellspacing="0" border="0" width="100%">
{foreach key=tid item=photo from=$photos}
    {if $col==1} <tr> {/if}
    <td align="center" valign="middle" width="{math equation="100/x" x=$cfg.maxcols}%" class="mod_lp_photo">
            <a href="/users/{$photo.uid}/photo{$photo.id}.html" title="{$photo.title|escape:'html'}">
                <img class="photo_thumb_img" src="/images/users/photos/small/{$photo.file}" alt="{$photo.title|escape:'html'}" border="0" />
            </a>
            {if $cfg.is_full}
            <br /><a href="/users/{$photo.uid}/photo{$photo.id}.html" title="{$photo.title|escape:'html'}">{$photo.title|truncate:18}</a>
            <div class="mod_lp_albumlink"><a href="/users/{$photo.login}/photos/private{$photo.album_id}.html" title="{$photo.album_title|escape:'html'}">{$photo.album_title|truncate:18}</a>
                <div class="mod_lp_details">
                <table cellpadding="2" cellspacing="0" align="center" border="0"><tr>
                    <td><img src="/templates/{$template}/images/icons/calendar.png" border="0"/></td>
                    <td>{$photo.pubdate}</td>
                    <td><img src="/templates/{$template}/images/icons/comment-small.png" border="0"/></td>
                    <td><a href="/users/{$photo.uid}/photo{$photo.id}.html#c" title="{$photo.comments|spellcount:$LANG.COMMENT1:$LANG.COMMENT2:$LANG.COMMENT10}">{$photo.comments}</a></td>
                </tr></table>
                </div>
            </div>
            {/if}
    </td>
{if $col==$cfg.maxcols} </tr> {assign var="col" value="1"} {else} {math equation="x + 1" x=$col assign="col"} {/if}
{/foreach}
{if $col>1}
    <td colspan="{math equation="x - y + 1" x=$col y=$cfg.maxcols}">&nbsp;</td></tr>
{/if}
</table>

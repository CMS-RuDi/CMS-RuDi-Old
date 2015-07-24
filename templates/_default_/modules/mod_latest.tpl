{if $cfg.is_pag}
    {add_js file='modules/mod_latest/ajax/mod_latest.js'}
{/if}

{if !$is_ajax}<div id="module_ajax_{$module_id}">{/if}

{foreach key=aid item=article from=$articles}
	<div class="mod_latest_entry">
        {if $article.image_small}
            <div class="mod_latest_image">
                <img src="{$article.image_small}" border="0" width="32" height="32" alt="{$article.title|escape:'html'}"/>
            </div>
        {/if}
	    <a class="mod_latest_title" href="{$article.url}">{$article.title}</a>
		{if $cfg.showdate}
            <div class="mod_latest_date">
                {$article.fpubdate} - <a href="{profile_url login=$article.user_login}">{$article.author}</a>{if $cfg.showcom} - <a href="{$article.url}" title="{$article.comments|spellcount:$LANG.COMMENT1:$LANG.COMMENT2:$LANG.COMMENT10}" class="mod_latest_comments">{$article.comments}</a>{/if} - <span class="mod_latest_hits">{$article.hits}</span>
            </div>
        {/if}
        {if $cfg.showdesc}
            <div class="mod_latest_desc" style="overflow:hidden">
                {$article.description|strip_tags|truncate:200}
            </div>
        {/if}
	</div>
{/foreach}
{if $cfg.showrss}
	<div class="mod_latest_rss">
		<a href="/rss/content/{if $cfg.cat_id}{$cfg.cat_id}{else}all{/if}/feed.rss">{$LANG.LATEST_RSS}</a>
	</div>
{/if}
{if $cfg.is_pag && $pagebar_module}
    <div class="mod_latest_pagebar">{$pagebar_module}</div>
{/if}
{if !$is_ajax}</div>{/if}
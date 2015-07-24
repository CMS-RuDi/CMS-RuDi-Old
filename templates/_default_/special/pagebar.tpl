<div class="pagebar">
    <span class="pagebar_title"><strong>{$LANG.PAGES}: </strong></span>
    
    {if $page > 1}
        <a href="{$href|replace:'%page%':1}" class="pagebar_page">{$LANG.FIRST}</a>
        <a href="{$href|replace:'%page%':($page-1)}" class="pagebar_page">{$LANG.PREVIOUS}</a>
    {/if}
    
    {for $i=$current to $max_links-1}
        {if $i == $page}
            <span class="pagebar_current">{$i}</span>
        {else}
            <a href="{$href|replace:'%page%':$i}" class="pagebar_page">{$i}</a>
        {/if}
    {/for}
    
    {if $page >= 1 && $page != $total_pages}
        <a href="{$href|replace:'%page%':($page+1)}" class="pagebar_page">{$LANG.NEXT}</a>
        <a href="{$href|replace:'%page%':$total_pages}" class="pagebar_page">{$LANG.LAST}</a>
    {/if}
</div>
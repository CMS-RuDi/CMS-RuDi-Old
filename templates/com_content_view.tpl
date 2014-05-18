{if !$is_homepage}
    {if $cat.showrss}
<h1 class="con_heading">{$pagetitle} <i class="fa fa-rss"></i></h1>
    {else}
        <h1 class="con_heading">{$pagetitle}</h1>
    {/if}

    {if $cat.description}
        <blockquote>{$cat.description}</blockquote>
    {/if}
{/if}

{if $subcats}
	<div class="categorylist">
		{foreach key=tid item=subcat from=$subcats}
            <div class="subcat">
                <h3><a href="{$subcat.url}"><i class="fa fa-file-text"></i> {$subcat.title}</a> ({$subcat.content_count})</h3>
                <blockquote>{$subcat.description|truncate:100}</blockquote>
            </div>
		{/foreach}
	</div>
{/if}

{if $cat_photos}
<div class="panel">
    {if $cat_photos.album.title}
	<div class="p-title">
        <h3>{$cat_photos.album.title}</h3>
	</div>
    {/if}

		<div class="video-carousel">
			<a href="#" class="carousel-left"><i class="fa fa-chevron-left"></i></a>
			<a href="#" class="carousel-right"><i class="fa fa-chevron-right"></i></a>
				<div class="inner-carousel">
        {foreach key=tid item=con from=$cat_photos.photos}
<div class="item">
	<a  style="display:block;max-height:150px !important;overflow:hidden !important;" href="/images/photos/medium/{$con.file}" title="{$con.title|escape:'html'}" class="lightbox-enabled" rel="lightbox-galery"><img src="/images/photos/medium/{$con.file}" alt="{$con.title|escape:'html'}" class="item-photo" /></a>
	<h3><a href="/photos/photo{$con.id}.html" title="{$con.title|escape:'html'}">{$con.title|truncate:15}</a></h3>
</div>
        {/foreach}
				</div>
        </div>

</div>
{/if}

{if $articles}
	{assign var="col" value="1"}
	
	{if $cat.maxcols==1}
	{assign var="banner1" value="1"}
	<div class="panel">
		<div class="blog-list style-1">
		{foreach key=tid item=article from=$articles}
<div class="item">
	<div class="item-header rubrika">
<a href="{$article.url}" >{if $article.image}<img src="{$article.image}" border="0" alt="{$article.title|escape:'html'}" class="item-photo " />{else}<img src="/templates/newsmag/img/noimg.jpg" border="0" alt="{$article.title|escape:'html'}" class="item-photo" />{/if}</a>
	</div>
	<div class="item-content">
		{if $cat.showtags && $article.tagline}{if $article.tagline}<span style="display:block;text-transform:uppercase;padding-bottom:10px;"><i class="fa fa-tags"></i> {$article.tagline}</span>{/if}{/if}
		<h3 style="text-transform:uppercase;"><a href="{$article.url}" >{$article.title}</a></h3>
		<p>{$article.description|truncate:200}</p>
	</div>
	<div class="item-footer">
		<span class="right">
			{if $showdate}<span><i class="fa fa-clock-o"></i> {$article.fpubdate}</span>{/if}
			<span>{if $cat.showcomm}<i class="fa fa-comment"></i> {$article.comments} {/if} <i class="fa fa-eye"></i> {$article.hits}</span>
		</span>
	</div>
</div>	
	{if $banner1==1} 
						<div style="text-align:center;display:block;padding-top:20px;">
						{php}cmsCore::c('page')->printModules('banner_inner');{/php}
							
						</div>
	{/if}
	{math equation="x + 1" x=$banner1 assign="banner1"}	
		{/foreach}
		</div>
	</div>
	{else}

	<div class="panel">
	<div class="blog-list style-2">
		{foreach key=tid item=article from=$articles}
<div class="item">
	<div class="item-header rubrika">
<a href="{$article.url}" >{if $article.image}<img src="{$article.image}" border="0" alt="{$article.title|escape:'html'}" class="item-photo " />{else}<img src="/templates/newsmag/img/noimg.jpg" border="0" alt="{$article.title|escape:'html'}" class="item-photo" />{/if}</a>
	</div>
	<div class="item-content">
		<h3 style="text-transform:uppercase;"><a href="{$article.url}" >{$article.title}</a></h3>
		<p>{$article.description|truncate:200}</p>
		<p>{if $cat.showtags && $article.tagline}{if $article.tagline}<span style="display:block;text-transform:uppercase;padding-bottom:10px;"><i class="fa fa-tags"></i> {$article.tagline}</span>{/if}{/if}
		</p>
	</div>
	<div class="item-footer">
		<span class="right">
			{if $showdate}<span><i class="fa fa-clock-o"></i> {$article.fpubdate}</span>{/if}
			<span>{if $cat.showcomm}<i class="fa fa-comment"></i> {$article.comments} {/if} <i class="fa fa-eye"></i> {$article.hits}</span>
		</span>
	</div>
</div>	

		{/foreach}
	</div>
	</div>	
	{/if}
<div class="panel">
	{$pagebar}
</div>
{/if}
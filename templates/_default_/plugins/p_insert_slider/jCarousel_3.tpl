{add_js file='includes/sliders/jCarousel/jquery.jcarousel.min.js'}
{add_js file='includes/sliders/jCarousel/js/jcarousel.responsive.js'}
{add_css file='includes/sliders/jCarousel/css/jcarousel.responsive.css'}
{literal}
<style type="text/css">
    .jcarousel img {
        border: #000000 2px solid;
        min-height: 250px;
        max-height: 250px;
    }
</style>
{/literal}

<div class="jcarousel-wrapper">
    <div class="jcarousel">
        <ul>
            {foreach item=image from=$images}
                <li>
                    <a href="{$image.big_src}" class="photobox" title="{$image.title|escape:html|default:''}">
                        <img src="{$image.medium_src}" alt="{if $image.title}{$image.title|escape:html}{/if}" />
                    </a>
                </li>
            {/foreach}
        </ul>
    </div>

    <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
    <a href="#" class="jcarousel-control-next">&rsaquo;</a>

    <p class="jcarousel-pagination"></p>
</div>
{add_js file='includes/sliders/jCarousel/jquery.jcarousel.min.js'}
{add_js file='includes/sliders/jCarousel/js/jcarousel.basic.js'}
{add_css file='includes/sliders/jCarousel/css/jcarousel.basic.css'}

<div class="jcarousel-wrapper">
    <div class="jcarousel">
        <ul>
            {foreach item=image from=$images}
                <li>
                    <img src="{$image.big_src}" width="600" height="400" alt="{if $image.title}{$image.title|escape:'html'}{/if}" />
                </li>
            {/foreach}
        </ul>
    </div>

    <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
    <a href="#" class="jcarousel-control-next">&rsaquo;</a>

    <p class="jcarousel-pagination"></p>
</div>
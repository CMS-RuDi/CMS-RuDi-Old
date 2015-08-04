{add_js file='includes/sliders/jCarousel/jquery.jcarousel.min.js'}
{add_js file='includes/sliders/jCarousel/js/jcarousel.connected-carousels.js'}
{add_css file='includes/sliders/jCarousel/css/jcarousel.connected-carousels.css'}

<div class="connected-carousels">
    <div class="stage">
        <div class="carousel carousel-stage">
            <ul>
                {foreach item=image from=$images}
                    <li>
                        <img src="{$image.big_src}" width="600" height="400" alt="{if $image.title}{$image.title|escape:'html'}{/if}" />
                    </li>
                {/foreach}
            </ul>
        </div>

        <a href="#" class="prev prev-stage"><span>&lsaquo;</span></a>
        <a href="#" class="next next-stage"><span>&rsaquo;</span></a>
    </div>

    <div class="navigation">
        <a href="#" class="prev prev-navigation">&lsaquo;</a>
        <a href="#" class="next next-navigation">&rsaquo;</a>
        <div class="carousel carousel-navigation">
            <ul>
                {foreach item=image from=$images}
                    <li>
                        <img src="{$image.small_src}" width="50" height="50" alt="" />
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
</div>
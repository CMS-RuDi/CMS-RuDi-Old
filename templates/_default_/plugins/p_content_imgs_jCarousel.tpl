{add_js file='includes/sliders/jCarousel/jquery.jcarousel.min.js'}

{if $slide_opt == 1 || $slide_opt == 3}
    {if $slide_opt == 1}
        {add_js file='includes/sliders/jCarousel/js/jcarousel.basic.js'}
        {add_css file='includes/sliders/jCarousel/css/jcarousel.basic.css'}
    {/if}
    {if $slide_opt == 3}
        {add_js file='includes/sliders/jCarousel/js/jcarousel.responsive.js'}
        {add_css file='includes/sliders/jCarousel/css/jcarousel.responsive.css'}
    {/if}

    <div class="jcarousel-wrapper">
        <div class="jcarousel">
            <ul>
                {foreach item=image from=$images}
                    {if $slide_opt == 3}
                        <li><a href="{$image.big_src}" class="photobox"><img src="{$image.medium_src}" alt="{if $image.title}{$image.title|escape:html}{else}{$title|escape:html} {$image.id}{/if}"></a></li>
                    {/if}
                    {if $slide_opt == 2}
                        <li><img src="{$image.big_src}" width="600" height="400" alt="{if $image.title}{$image.title|escape:html}{else}{$title|escape:html} {$image.id}{/if}"></li>
                    {/if}
                {/foreach}
            </ul>
        </div>

        <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
        <a href="#" class="jcarousel-control-next">&rsaquo;</a>

        <p class="jcarousel-pagination"></p>
    </div>
{/if}

{if $slide_opt == 2}
    {add_js file='includes/sliders/jCarousel/js/jcarousel.connected-carousels.js'}
    {add_css file='includes/sliders/jCarousel/css/jcarousel.connected-carousels.css'}

    <div class="connected-carousels">
        <div class="stage">
            <div class="carousel carousel-stage">
                <ul>
                    {foreach item=image from=$images}
                        <li><img src="{$image.big_src}" width="600" height="400" alt="{if $image.title}{$image.title|escape:html}{else}{$title|escape:html} {$image.id}{/if}"></li>
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
                        <li><img src="{$image.small_src}" width="50" height="50" alt=""></li>
                    {/foreach}
                </ul>
            </div>
        </div>
    </div>
{/if}
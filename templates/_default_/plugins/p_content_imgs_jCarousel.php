<?php
    cmsCore::c('page')->addHeadJS('includes/sliders/jCarousel/jquery.jcarousel.min.js');
    
    if ($slide_opt == 1 || $slide_opt == 3) {
        if ($slide_opt == 1) {
            cmsCore::c('page')->addHeadJS('includes/sliders/jCarousel/js/jcarousel.basic.js');
            cmsCore::c('page')->addHeadCSS('includes/sliders/jCarousel/css/jcarousel.basic.css');
        }
        
        if ($slide_opt == 3) {
            cmsCore::c('page')->addHeadJS('includes/sliders/jCarousel/js/jcarousel.responsive.js');
            cmsCore::c('page')->addHeadCSS('includes/sliders/jCarousel/css/jcarousel.responsive.css');
            echo '<style type="text/css">.jcarousel img { border: #000000 2px solid; width: 240px; min-height: 250px; max-height: 250px; }</style>';
        }
        ?>
        <div class="jcarousel-wrapper">
            <div class="jcarousel">
                <ul>
                    <?php foreach ($images as $image) { ?>
                        <?php if ($slide_opt == 3) { ?>
                    <li><a href="<?php echo $image['big_src']; ?>" class="photobox" title="<?php echo $this->escape(cmsCore::getArrVal($image, 'title', '')); ?>"><img src="<?php echo $image['medium_src']; ?>" alt="<?php echo $this->escape(cmsCore::getArrVal($image, 'title', $title .' '. $image['id'])); ?>"></a></li>
                        <?php } ?>
                        
                        <?php if ($slide_opt == 1) { ?>
                            <li><img src="<?php echo $image['big_src']; ?>" width="600" height="400" alt="<?php echo $this->escape(cmsCore::getArrVal($image, 'title', $title .' '. $image['id']), 'html'); ?>"></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>

            <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
            <a href="#" class="jcarousel-control-next">&rsaquo;</a>

            <p class="jcarousel-pagination"></p>
        </div>
<?php        
    }
    
    if ($slide_opt == 2) {
        cmsCore::c('page')->addHeadJS('includes/sliders/jCarousel/js/jcarousel.connected-carousels.js');
        cmsCore::c('page')->addHeadCSS('includes/sliders/jCarousel/css/jcarousel.connected-carousels.css');
?>
        <div class="connected-carousels">
            <div class="stage">
                <div class="carousel carousel-stage">
                    <ul>
                        <?php foreach ($images as $image) { ?>
                            <li><img src="<?php echo $image['big_src']; ?>" width="600" height="400" alt="<?php echo $this->escape(cmsCore::getArrVal($image, 'title', $title .' '. $image['id']), 'html'); ?>"></li>
                        <?php } ?>
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
                        <?php foreach ($images as $image) { ?>
                            <li><img src="<?php echo $image['small_src']; ?>" width="50" height="50" alt=""></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
<?php
    }
?>
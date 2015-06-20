<?php
function applet_login () {
    cmsCore::c('page')->initTemplate('applets', 'login')->
        display();
    cmsCore::halt();
}
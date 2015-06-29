<?php
function applet_login () {
    if (cmsCore::c('user')->is_admin) {
        cmsCore::redirect('/admin');
    } elseif (cmsCore::c('user')->id) {
        cpAccessDenied();
    }
    
    cmsCore::c('page')->initTemplate('applets', 'login')->
        display();
    cmsCore::halt();
}
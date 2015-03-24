function conPage(page, module_id){
    $('div#module_ajax_'+module_id).css({ opacity:0.4, filter:'alpha(opacity=40)' });
    $.post('/modules/mod_latest/ajax/latest.php', { 'module_id': module_id, 'page':page }, function(data) {
        $('div#module_ajax_'+module_id).html(data);
        $('div#module_ajax_'+module_id).css({ opacity:1.0, filter:'alpha(opacity=100)' });
    });
}
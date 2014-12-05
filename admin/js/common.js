var core_alert_fn_ok=false;
var core_alert_fn_cancel=false;

function jsmsg(msg, link){
    adminAlert(msg, false, {ok: function(){ window.location.href = link; }, cancel_text: LANG_CANCEL});
    return false;
}

function adminAlert(msg, title, option){
    title = title ? title : LANG_ATTENTION;
    
    if (core_alert_fn_ok !== false) {
        $('#modalMsgBoxOk').unbind('click', core_alert_fn_ok);
        core_alert_fn_ok = false;
    }
    
    if (core_alert_fn_cancel !== false) {
        $('#modalMsgBoxCancel').unbind('click', core_alert_fn_cancel);
        core_alert_fn_cancel = false;
    }
    
    if (!option) {
        option = {'type':false, 'ok':false, 'cancel':false, 'ok_text':false, 'cancel_text':false};
    }
    
    if (option.type) {
        option.type = option.type === 'error' ? 'danger' : option.type;
        msg = '<div class="alert alert-'+ option.type +'" role="alert">'+ msg +'</div>';
    }
    
    if (option.ok) {
        core_alert_fn_ok = option.ok;
        $('#modalMsgBoxOk').click(core_alert_fn_ok);
        $('#modalMsgBoxOk').show();
    } else {
        $('#modalMsgBoxOk').hide();
    }
    
    if (option.cancel) {
        core_alert_fn_cancel = option.cancel;
        $('#modalMsgBoxCancel').click(core_alert_fn_cancel);
    }
    
    $('#modalMsgBox').modal({backdrop:'static'});
    
    $('#modalMsgBoxOk').html(option.ok_text ? option.ok_text : LANG_CONTINUE);
    $('#modalMsgBoxCancel').html(option.cancel_text ? option.cancel_text : LANG_CLOSE);
    $('#modalMsgBoxLabel').html(title);
    $('#modalMsgBoxBody').html(msg);
    $('#modalMsgBox').modal('show');
}

function deleteDialog(){
    $( "#dialog-confirm" ).remove();
}

function checked(){
    var c = 0;
    for (var i=0; i<document.selform.length; i++){
        if(document.selform.elements[i].name == 'item[]'){
            if(document.selform.elements[i].checked){
                c = c + 1;
            }
        }
    }
    return c;
}

function checkSel(link){
    var ch = 0;
    for (var i=0; i<document.selform.length; i++){
        if(document.selform.elements[i].name == 'item[]'){
            if(document.selform.elements[i].checked){
                ch++;
            }
        }
    }

    if (ch>0){
        document.selform.action = link;
        document.selform.submit();
    } else {
        adminAlert(LANG_AD_NO_SELECT_OBJECTS);
    }
}

function sendForm(link){
    document.selform.action = link;
    document.selform.submit();
}

function invert(){
    for (var i=0; i<document.selform.length; i++){
        if(document.selform.elements[i].name == 'item[]'){
            document.selform.elements[i].checked = !document.selform.elements[i].checked;
        }
    }
}

function install(href){
    $('div.update_process').show();
    $('div.update_go').hide();
    window.location.href=href;
}

function activateListTable(){
    $('table.tablesorter').tablesorter({headers: {0: {sorter: false}}});

    var browser = navigator.userAgent;
    var msie = false;
    if( browser.indexOf("MSIE") != -1 ) {
        msie = true;
        var re = /.+(MSIE)\s(\d\d?)(\.?\d?).+/i;
        version = browser.replace(re, "$2");
    }
    if (!msie || $version != '6'){
        $('table.tablesorter').columnFilters();
    }
}

function pub(id, qs, qs2, action, action2){
    old_img = $('img#pub'+id).attr('src');
    $('img#pub'+id).attr('src', 'images/actions/loader.gif');
    $('a#publink'+id).attr('href', '');
    $.ajax({
        type: "GET",
        url: "index.php",
        data: qs,
        success: function(msg){
            if(msg){
                $('img#pub'+id).attr('src', 'images/actions/'+action+'.gif');
                $('a#publink'+id).attr('href', 'javascript:pub('+id+', "'+qs2+'", "'+qs+'", "'+action2+'", "'+action+'");');
            } else {
                $('img#pub'+id).attr('src', old_img);
            }
        }
    });
}

function showIns(){
    document.getElementById('frm').style.display = 'none';
    document.getElementById('filelink').style.display = 'none';
    document.getElementById('include').style.display = 'none';
    document.getElementById('banpos').style.display = 'none';
    document.getElementById('pagebreak').style.display = 'none';
    document.getElementById('pagetitle').style.display = 'none';

    needDiv = document.addform.ins.options[document.addform.ins.selectedIndex].value;

    document.getElementById(needDiv).style.display = "table-row";
}

function insertTag(kind){
    var text = '';

    if (kind=='material'){
        text = '{МАТЕРИАЛ=' + document.addform.m.options[document.addform.m.selectedIndex].text + '}';
    }
    if (kind=='photo'){
        text = '{ФОТО=' + document.addform.f.options[document.addform.f.selectedIndex].text + '}';
    }
    if (kind=='album'){
        text = '{АЛЬБОМ=' + document.addform.a.options[document.addform.a.selectedIndex].text + '}';
    }
    if (kind=='frm'){
        text = '{ФОРМА=' + document.addform.fm.options[document.addform.fm.selectedIndex].text + '}';
    }
    if (kind=='blank'){
        text = '{БЛАНК=' + document.addform.b.options[document.addform.b.selectedIndex].text + '}';
    }
    if (kind=='include'){
        text = '{ФАЙЛ=' + document.addform.i.value + '}';
    }
    if (kind=='filelink'){
        text = '{СКАЧАТЬ=' + document.addform.fl.value + '}';
    }
    if (kind=='banpos'){
        text = '{БАННЕР=' + document.addform.ban.value + '}';
    }
    if (kind=='pagebreak'){
        text = '{pagebreak}';
    }
    if (kind=='pagetitle'){
        text = '{СТРАНИЦА=' + document.addform.ptitle.value + '}';
    }

    wysiwygInsertHtml(text);
}

function InsertPagebreak() {
    wysiwygInsertHtml('{pagebreak}');
}

function checkGroupList() {
    if ($('#is_public').prop('checked')) {
        $('select#showin').prop('disabled', true);
    } else {
        $('select#showin').prop('disabled', false);
    }
}

function trClickChecked(table_class,input_name) {
    table_class = table_class ? table_class : 'tablesorter';
    input_name = input_name ? input_name : 'item[]';
    
    $('.'+ table_class +' tr').bind('click', {i_name:input_name}, function(e){
        if ($.inArray(e.target.tagName, ['INPUT', 'input', 'A', 'a', 'TEXTAREA', 'textarea']) === -1) {
            var elms = this.getElementsByTagName("input");
            for (i=0; i < elms.length; i++) {
                if (elms[i].name == e.data.i_name) {
                    elms[i].checked = elms[i].checked ? false : true; break;
                }
            }
        }
    });
}
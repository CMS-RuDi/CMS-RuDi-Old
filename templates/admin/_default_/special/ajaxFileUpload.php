<div id="files_list">
<?php if (!empty($files)){ ?>
    <?php foreach ($files as $file){ ?>

        <div class="ajax_file" id="ajax_file<?php echo $file['id']; ?>">
            <?php if ($options['type'] == 'images') { ?>
            <div class="ajax_img_tmb">
                <img src="<?php echo $file['small_src']; ?>" />
                <?php if ($options['insertEditor']) { ?>
                <div class="ajax_insertEditor">
                    <a href="#" title="small" onclick="ajaxInsertImg('<?php echo $file['small_src']; ?>'); return false;">S</a>
                    <a href="#" title="medium" onclick="ajaxInsertImg('<?php echo $file['medium_src']; ?>'); return false;">M</a>
                    <a href="#" title="big" onclick="ajaxInsertImg('<?php echo $file['big_src']; ?>'); return false;">B</a>
                </div>
                <?php } ?>
            </div>
            <?php } else if (!empty($file['data'])) { ?>
            <div class="ajax_file_data">
                <?php echo $file['data']; ?>
            </div>
            <?php } else { ?>
            <div class="ajax_img_tmb">
                <img src="" />
            </div>
            <?php } ?>
                    
            <div class="ajax_file_id">#<?php echo $file['id']; ?></div>
            
            <div class="ajax_del_action">
                <a href="#" onclick="deleteAjaxFile(<?php echo $file['id']; ?>); return false;" title="<?php echo $_LANG['DELETE']; ?>"></a>
            </div>
            
            <div>
                <input type="text" name="ajax_file_title[<?php echo $file['id']; ?>]" value="<?php echo $file['title']; ?>" placeholder="<?php echo $_LANG['TITLE']; ?>" />
            </div>
            
            <div>
                <textarea name="ajax_file_description[<?php echo $file['id']; ?>]" placeholder="<?php echo $_LANG['DESCRIPTION']; ?>"><?php echo $file['description']; ?></textarea>
            </div>
        </div>

    <?php } ?>
<?php } ?>
</div>
<div style="clear:both;"></div>

<div id="pluploader">
    <div id="fileslist"><?php echo $_LANG['AJAX_FILE_UPLOAD_NOT_SUPPORTED']; ?></div>
    <div><?php echo $_LANG['AD_FILE_TYPES'] .': '. $options['extensions']; ?></div>
    <a class="ui-button ui-state-default ui-button-text-icon-primary" id="pluploader_browse">
        <span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>
        <span class="ui-button-text"><?php echo $_LANG['UPLOAD_IMGS']; ?></span>
    </a>
</div>

<style type="text/css">
    #fileslist{
        color:#CC0000;
        padding:10px;
    }
    #files_list{
        position: relative;
        max-height: 500px;
        overflow-x: hidden;
        overflow-y: auto;
    }
    .ajax_file{
        position: relative;
        float: left;
        width: 100px;
        margin: 10px;
    }
    .ajax_file_id{
        position:absolute;
        left:0px;
        top:0px;
        color:#000000;
        background:#ffffff;
        padding:0px 1px 0px 1px;
    }
    .ajax_del_action a{
        display: block;
        position: absolute;
        background: url('/images/icons/delete.gif');
        right: 0px;
        top: 0px;
        width: 16px;
        height: 16px;
    }
    .ajax_file img{
        width: 100px;
        height: 100px;
    }
    .ajax_file input, .ajax_file textarea{
        width:98%;
    }
    .ajax_img_tmb, .ajax_file_data{
        position: relative;
    }
    .ajax_insertEditor{
        position: absolute;
        left: 0px;
        bottom: 0px;
    }
    .ajax_insertEditor a{
        display: inline-block;
        margin-right: 5px;
        background-color: #000000;
        padding: 0px 5px 0px 5px;
        color: #ffffff;
    }
</style>

<script type="text/javascript">
    var uploader = new plupload.Uploader({
        runtimes: "html5,flash,silverlight,html4",
        browse_button: "pluploader_browse",
        container: document.getElementById("pluploader"),
        url: "<?php echo $options['url']; ?>",
        
        filters : {
            max_file_size : "<?php echo $options['max_file_size']; ?>mb",
            mime_types: [ {title : "Supported files", extensions : "<?php echo $options['extensions']; ?>"} ] 
        },
                
        multipart_params: {
            "component"    : "<?php echo $options['component']; ?>",
            "target"       : "<?php echo $options['target']; ?>",
            "target_id"    : "<?php echo $options['target_id']; ?>",
            "ses_id"       : "<?php echo $options['ses_id']; ?>"
        },
        
        flash_swf_url : "/includes/jquery/plupload/Moxie.swf",
        silverlight_xap_url : "/includes/jquery/plupload/Moxie.xap",
        
        init: {
            PostInit: function() {
                document.getElementById("fileslist").innerHTML = "";
            },
            
            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    $('#fileslist').append('<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>');
                    $('#fileslist').scrollTop(document.getElementById('fileslist').scrollHeight);
                    //document.getElementById('fileslist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                });
                uploader.start();
            },
 
            UploadProgress: function(up, file) {
                $('#'+file.id+' b').html('<span>'+ file.percent +'%</span>');
                //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
            },

            FileUploaded: function(up, file, resp){
                var response = JSON.parse(resp.response);
                if (response.error){
                    alert(response.error);
                }else{
                    var html = '<div class="ajax_file" id="ajax_file'+ response.id +'">';

                    <?php if ($options['type'] == 'images') { ?>
                    html += '<div class="ajax_img_tmb"><img src="'+ response.small_src +'" /><?php if ($options['insertEditor']){ ?><div class="ajax_insertEditor"><a href="#" title="small" onclick="ajaxInsertImg(\''+ response.small_src +'\'); return false;">S</a><a href="#" title="medium" onclick="ajaxInsertImg(\''+ response.medium_src +'\'); return false;">M</a><a href="#" title="big" onclick="ajaxInsertImg(\''+ response.big_src +'\'); return false;">B</a></div><?php } ?></div><div class="ajax_file_id">#'+ response.id +'</div>';
                    <?php } else { ?>

                    if (response.data) {
                        html += '<div class="ajax_file_data">'+ response.data +'</div>';
                    }else{
                        html += '<div class="ajax_img_tmb"><img src="/includes/plupload/img/file.png" /></div>';
                    }

                    <?php } ?>

                    html += '<div class="ajax_del_action"><a href="#" onclick="deleteAjaxFile('+ response.id +'); return false;" title="<?php echo $_LANG['DELETE']; ?>"></a></div>';

                    <?php if ($options['title'] === true) { ?>
                    html += '<div><input type="text" name="ajax_file_title['+ response.id +']" value="" placeholder="<?php echo $_LANG['TITLE']; ?>" /></div>';
                    <?php } ?>
                    <?php if ($options['description'] === true) { ?>
                    html += '<div><textarea name="ajax_file_description['+ response.id +']" placeholder="<?php echo $_LANG['DESCRIPTION']; ?>"></textarea></div>';
                    <?php } ?>

                    html += '</div>';

                    $('#files_list').append(html);
                }
                $('#'+file.id).hide("slow", function() {
                    $(this).remove();
                });
            },
            
            Error: function(up, err) {
                alert("<?php echo $_LANG['ERROR']; ?> #" + err.code + ": " + err.message);
            }
        }
    });
    
    uploader.init();
    
    function deleteAjaxFile(file_id){
        $.post(
            "<?php echo $options['del_url']; ?>",
            "file_id="+file_id,
            function(msg){
                if (msg == 'OK'){
                    $("#ajax_file"+ file_id).hide("300", function() {
                        $(this).remove();
                    });
                }else{
                    alert(msg);
                }
            }
        );
    }
    <?php if ($options['insertEditor']){ ?>
    function ajaxInsertImg(src){
        wysiwygInsertHtml('<img src="'+ src +'" alt="" />', '<?php echo $options['insertEditor']; ?>', '<?php echo $options['editorName']; ?>');
        return false;
    }
    <?php } ?>
</script>
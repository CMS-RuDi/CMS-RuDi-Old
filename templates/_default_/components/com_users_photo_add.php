<h1 class="con_heading"><?php echo $_LANG['ADD_PHOTOS']; ?></h1>
<?php if ($total_no_pub) { ?>
<p class="usr_photos_add_limit"><?php echo $_LANG['NO_PUBLISHED_PHOTO']; ?>: <a href="/users/<?php echo $user_login; ?>/photos/submit"><?php echo $this->spellcount($total_no_pub, $_LANG['PHOTO'], $_LANG['PHOTO2'], $_LANG['PHOTO10']); ?></a></p>
<?php } ?>
<?php if (!$stop_photo) { ?>
	<?php if ($uload_type == 'multi') { ?>
<?php cmsCore::c('page')->addHeadJS('includes/swfupload/swfupload.js'); ?>
<?php cmsCore::c('page')->addHeadJS('includes/swfupload/swfupload.queue.js'); ?>
<?php cmsCore::c('page')->addHeadJS('includes/swfupload/fileprogress.js'); ?>
<?php cmsCore::c('page')->addHeadJS('includes/swfupload/handlers.js'); ?>
<?php cmsCore::c('page')->addHeadCSS('includes/swfupload/swfupload.css'); ?>

<script type="text/javascript">
    var swfu;
    var uploadedCount = 0;

    window.onload = function() {
        var settings = {
            flash_url : "/includes/swfupload/swfupload.swf",
            upload_url: "/users/photos/upload",
            post_params: {"PHPSESSID" : "<?php echo $sess_id; ?>"},
            file_size_limit : "20 MB",
            file_types : "*.jpg;*.png;*.gif;*.jpeg;*.JPG;*.PNG;*.GIF;*.JPEG",
            file_types_description : "<?php echo $_LANG['ALL_PHOTOS']; ?>",
    
            file_upload_limit : <?php if ($max_limit) { ?><?php echo $max_files; ?><?php } else { ?>100<?php } ?>,
    
            file_queue_limit : 0,
            custom_settings : {
                progressTarget : "fsUploadProgress",
                cancelButtonId : "btnCancel"
            },
            debug: false,

            // Button settings
            button_image_url: "/includes/swfupload/uploadbtn199x36.png",
            button_width: "199",
            button_height: "36",
            button_placeholder_id: "spanButtonPlaceHolder",

            // The event handler functions are defined in handlers.js
            file_queued_handler : fileQueued,
            file_queue_error_handler : fileQueueError,
            file_dialog_complete_handler : fileDialogComplete,
            upload_start_handler : uploadStart,
            upload_progress_handler : uploadProgress,
            upload_error_handler : uploadError,
            upload_success_handler : uploadSuccess,
            upload_complete_handler : uploadComplete,
            queue_complete_handler : queueComplete	// Queue plugin event
        };

        swfu = new SWFUpload(settings);
    };

    function queueComplete(numFilesUploaded) {
        if (numFilesUploaded>0){
            uploadedCount += numFilesUploaded;
            $('#divStatus').show();
            $('#continue').show();
            $("#files_count").html(uploadedCount);
        }
    }
</script>

<form id="usr_photos_upload_form" action="" method="post" enctype="multipart/form-data">
    <?php if ($max_limit) { ?>
    <p class="usr_photos_add_limit"><?php echo $_LANG['YOU_CAN_UPLOAD']; ?> <strong><?php echo $max_files; ?></strong> <?php echo $_LANG['PHOTO_SHORT']; ?></p>
    <?php } ?>

        <div class="fieldset flash" id="fsUploadProgress" style="display:none">
            <span class="legend"><?php echo $_LANG['UPLOAD_QUEUE']; ?></span>
        </div>

        <div>
            <span id="spanButtonPlaceHolder"></span>
            <input id="btnCancel" type="button" value="<?php echo $_LANG['CANCEL']; ?>" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 36px;" />
        </div>

        <div id="divStatus" style="display:none">
            <?php echo $_LANG['UPLOADED']; ?> <span id="files_count"><strong>0</strong></span> <?php echo $_LANG['PHOTO_SHORT']; ?>.
            <a href="/users/<?php echo $user_login; ?>/photos/submit" id="continue"><?php echo $_LANG['CONTINUE']; ?></a>
        </div>

</form>
        <p class="usr_photos_add_st"><?php echo $_LANG['TEXT_TO_NO_FLASH']; ?> <a href="/users/addphotosingle.html"><?php echo $_LANG['PHOTO_ST_UPLOAD']; ?>.</a></p>
        <?php } else if ($uload_type == 'single') { ?>
        <?php if ($max_limit) { ?>
         <p class="usr_photos_add_limit"><?php echo $_LANG['YOU_CAN_UPLOAD']; ?> <strong><?php echo $max_files; ?></strong> <?php echo $_LANG['PHOTO_SHORT']; ?></p>
        <?php } ?>

        <form id="usr_photos_upload_form" enctype="multipart/form-data" action="/users/photos/upload" method="POST">
            <p><?php echo $_LANG['SELECT_UPLOAD_FILE']; ?>: </p>
            <input name="Filedata" type="file" id="picture" size="30" />
            <input name="upload" type="hidden" value="1"/>
            <div style="margin-top:5px">
                <strong><?php echo $_LANG['TYPE_FILE']; ?>:</strong> gif, jpg, jpeg, png
            </div>

            <p>
                <input type="submit" value="<?php echo $_LANG['UPLOAD']; ?>">
                <input type="button" onclick="window.history.go(-1);" value="<?php echo $_LANG['CANCEL']; ?>"/>
            </p>
        </form>
		<p class="usr_photos_add_st"><?php echo $_LANG['TEXT_TO_TO_FLASH']; ?> <a href="/users/addphoto.html"><?php echo $_LANG['PHOTO_FL_UPLOAD']; ?>.</a></p>
    <?php } ?>
<?php } else { ?>
<p class="usr_photos_add_limit"><?php echo $_LANG['FOR_ADD_PHOTO_TEXT']; ?></p>
<?php } ?>
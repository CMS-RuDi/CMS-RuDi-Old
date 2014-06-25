<h3 style="border-bottom: solid 1px gray">
	<strong><?php echo $_LANG['STEP_2']; ?></strong>: <?php echo $_LANG['FILE_UPLOAD']; ?>
</h3>
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
            flash_url: "/includes/swfupload/swfupload.swf",
            upload_url: "<?php echo $upload_url; ?>",
            post_params: {"sess_id" : "<?php echo $sess_id; ?>", "album_id" : <?php echo $album['id']; ?>},
            file_size_limit: "20 MB",
            file_types: "*.jpg;*.png;*.gif;*.jpeg;*.JPG;*.PNG;*.GIF;*.JPEG",
            file_types_description: "<?php echo $_LANG['PHOTO']; ?>",
    
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
            window.location.href = '<?php echo $upload_complete_url; ?>';
        }
    }

    
</script>

<form id="usr_photos_upload_form" action="" method="post" enctype="multipart/form-data">

    <?php if ($max_limit) { ?>
    <p class="usr_photos_add_limit"><?php echo $_LANG['YOU_CAN_UPLOAD']; ?> <strong><?php echo $max_files; ?></strong> <?php echo $_LANG['PHOTO']; ?></p>
    <?php } ?>
        <div class="fieldset flash" id="fsUploadProgress" style="display:none">
            <span class="legend"><?php echo $_LANG['UPLOAD_QUEUE']; ?></span>
        </div>
        <div>
            <span id="spanButtonPlaceHolder"></span>
            <input id="btnCancel" type="button" value="<?php echo $_LANG['CANCEL']; ?>" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 36px;" />
        </div>

</form>

<?php } else if ($uload_type == 'single') { ?>
        <?php if ($max_limit) { ?>
         <p class="usr_photos_add_limit"><?php echo $_LANG['YOU_CAN_UPLOAD']; ?> <strong><?php echo $max_files; ?></strong> <?php echo $_LANG['PHOTO']; ?></p>
        <?php } ?>

        <form enctype="multipart/form-data" action="<?php echo $upload_url; ?>" method="POST">

            <p><?php echo $_LANG['SELECT_FILE_TO_UPLOAD']; ?>: </p>
                    <input name="Filedata" type="file" id="picture" size="30" />
                    <input name="upload" type="hidden" value="1"/>
                    <input name="album_id" type="hidden" value="<?php echo $album['id']; ?>"/>
                    <input name="sess_id" type="hidden" value="<?php echo $sess_id; ?>"/>

            <div style="margin:5px 0">
                <strong><?php echo $_LANG['ALLOW_FILE_TYPE']; ?>:</strong> gif, jpg, jpeg, png
            </div>

            <p>
                <input type="submit" value="<?php echo $_LANG['LOAD']; ?>">
                <input type="button" onclick="window.history.go(-1);" value="<?php echo $_LANG['CANCEL']; ?>"/>
            </p>
        </form>
<?php } ?>
<?php } else { ?>
    <p class="usr_photos_add_limit"><?php echo $_LANG['MAX_UPLOAD_IN_DAY']; ?></p>
<?php } ?>
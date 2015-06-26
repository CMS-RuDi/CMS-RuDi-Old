<p>
    <?php echo $_LANG['AD_TIME_LAST_CHECK']; ?>
    <b><?php echo (isset($data['date']) ? $data['date'] : $_LANG['AD_NEVER']); ?></b>,
    <?php echo $_LANG['AD_IMG']; ?>:
    <b><?php echo (isset($data['img']) ? $data['img'] : ''); ?></b>
</p>
        
<?php if (!empty($data)) { ?>
    <div class="uitabs">
        <ul id="tabs">
            <li><a href="#tab1"><span><?php echo $_LANG['AD_MODIFY_FILES']; ?></span></a></li>
            <li><a href="#tab2"><span><?php echo $_LANG['AD_NEW_FILES']; ?></span></a></li>
            <li><a href="#tab3"><span><?php echo $_LANG['AD_DELETED_FILES']; ?></span></a></li>
        </ul>

        <div id="tab1">
            <?php if (!empty($data['modified_files'])) { ?>
                <?php foreach ($data['modified_files'] as $path) { ?>
                    <div><?php echo $path; ?></div>
                <?php } ?>
            <?php } else { ?>
                <p><?php $_LANG['AD_MODIFY_FILES_NOT_FOUND']; ?></p>
            <?php } ?>
        </div>

        <div id="tab2">
            <?php if (!empty($data['new_files'])) { ?>
                <?php foreach ($data['new_files'] as $path) { ?>
                    <div><?php echo $path; ?></div>
                <?php } ?>
            <?php } else { ?>
                <p><?php echo $_LANG['AD_NEW_FILES_NOT_FOUND']; ?></p>
            <?php } ?>
        </div>

        <div id="tab3">
            <?php if (!empty($data['old_files'])) { ?>
                <?php foreach ($data['old_files'] as $path) { ?>
                    <div><?php echo $path; ?></div>
                <?php } ?>
            <?php } else { ?>
                <p><?php echo $_LANG['AD_DELETED_FILES_NOT_FOUND']; ?></p>
            <?php } ?>
        </div>

    </div>
<?php } else { ?>
    <p><?php echo $_LANG['AD_LAST_CHECK_RESULT_NOT_FOUND']; ?></p>
<?php } ?>
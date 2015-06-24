<h3><?php echo $title; ?></h3>

<?php if (empty($items)) { ?>
    <p><?php echo $text1; ?></p>
    <p><?php echo $text2; ?></p>
<?php } else { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo $text3; ?>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo $addon_type; ?></th>
                        <th width="150"><?php echo $_LANG['AD_VERSION']; ?></th>
                        <th width="150"><?php echo $_LANG['AD_AUTHOR']; ?></th>
                        <th width="250"><?php echo $_LANG['AD_FOLDER']; ?></th>
                        <th width="100"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $key => $item) { ?>
                    <tr>
                        <td><?php echo $key; ?></td>
                        <td>
                            <strong><?php echo $item['title']; ?></strong>
                            <div class="help-block"><?php echo $item['description']; ?></div>
                        </td>
                        <td><?php echo $item['version']; ?></td>
                        <td><?php echo $item['author']; ?></td>
                        <td><?php echo $item['folder']; ?></td>
                        <td><a href="index.php?view=install&do=<?php echo $action; ?>&id=<?php echo $item['link']; ?>" class="btn btn-primary"><?php echo $action_name; ?></a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>

<p><a class="btn btn-default" href="javascript:window.history.go(-1);"><?php echo $_LANG['BACK']; ?></a></p>
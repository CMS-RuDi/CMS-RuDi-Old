<div class="panel panel-default">
    <div class="panel-heading"><?php echo $_LANG['AD_TEMPLATES_LIST']; ?></div>
    <div class="panel-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo $_LANG['AD_TEMPLATE']; ?></th>
                    <th width="200"><?php echo $_LANG['AD_TEMPLATE_AUTHOR']; ?></th>
                    <th width="200"><?php echo $_LANG['AD_TEMPLATE_RENDERER']; ?></th>
                    <th width="200"><?php echo $_LANG['AD_TEMPLATE_EXT']; ?></th>
                    <th width="100"></th>
                    <th width="100"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($templates as $template) { ?>
                <?php if ($template == 'admin') { continue; } ?>
                <?php $tpl_info = $this->getTplInfo($template); ?>
                <tr>
                    <td><strong><?php echo $template; ?></strong></td>
                    <td><?php echo $tpl_info['author']; ?></td>
                    <td><?php echo $tpl_info['renderer']; ?></td>
                    <td><?php echo $tpl_info['ext']; ?></td>
                    <td>
                        <?php if (file_exists(PATH .'/templates/'. $template .'/positions.jpg')) { ?>
                            <a href="#<?php echo $template; ?>" role="button" class="btn btn-sm btn-default" data-toggle="modal"><?php echo $_LANG['AD_TPL_POS']; ?></a>
                            <div class="modal fade" id="<?php echo $template; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $template; ?>Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title" id="<?php echo $template; ?>Label"><?php echo $_LANG['AD_TPL_POS']; ?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <img src="/templates/<?php echo $template; ?>/positions.jpg" alt="<?php echo $_LANG['AD_TPL_POS']; ?>" style="width:100%;height:auto;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </td>
                    <td>
                    <?php if (file_exists(PATH .'/templates/'. $template .'/config.php')) { ?>
                        <a href="/admin/index.php?view=templates&do=config&template=<?php echo $template; ?>" class="btn btn-sm btn-primary"><?php echo $_LANG['AD_CONFIG']; ?></a>
                    <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
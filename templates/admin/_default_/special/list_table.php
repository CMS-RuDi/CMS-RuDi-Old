<?php if ($filters > 0) { ?>
    <div class="panel panel-default">
        <div class="panel-body" style="padding:0;">
            <form class="form-inline navbar-form navbar-left" name="filterform" action="index.php?<?php echo $query_str; ?>" method="POST" role="search">
                <?php echo $f_html; ?>
                <button type="submit" class="btn btn-default"><?php echo $_LANG['AD_FILTER']; ?></button>
                <?php if (!empty($f)) { ?>
                    <button onclick="window.location.href='index.php?<?php echo $query_str; ?>&nofilter'; return false;" class="btn btn-default" style="margin-left:10px;"><?php echo $_LANG['AD_ALL']; ?></button>
                <?php } ?>
            </form>
        </div>
    </div>
<?php } ?>

<?php if (!empty($items)) { ?>
<form name="selform" action="index.php?view=<?php echo $applet; ?>&do=saveorder" method="post">
    <table class="table table-striped tablesorter">
        <thead>
            <tr>
                <th width="20" class="lt_header" style="vertical-align:middle;">
                    <a class="lt_header_link" href="javascript:invert();" title="<?php echo $_LANG['AD_INVERT_SELECTION']; ?>">#</a>
                </th>
                <?php foreach($fields as $key => $value) { ?>
                    <th width="<?php echo $value['width']; ?>" class="lt_header<?php echo (isset($value['sort_link']) ? ' header' : '') ?> <?php echo ($value['field'] == $sort ? ( $so == 'asc' ? 'headerSortDown' : 'headerSortUp' ) : ''); ?>" style="vertical-align:middle;">
                        <?php if (isset($value['sort_link'])) { ?>
                            <a href="<?php echo $value['sort_link']; ?>"><?php echo $value['title']; ?></a>
                        <?php } else { ?>
                            <?php echo $value['title']; ?>
                        <?php } ?>
                    </th>
                <?php } ?>
                <?php if ($actions) { ?>
                    <th width="80" class="lt_header" style="vertical-align:middle;">
                        <?php echo $_LANG['AD_ACTIONS']; ?>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $id => $item) { ?>
            <tr>
                <td align="center" valign="middle">
                    <input type="checkbox" name="item[]" value="<?php echo $id; ?>" />
                </td>
                <?php foreach ($item as $it) { ?>
                    <td valign="middle">
                    <?php if ($it['type'] == 'link') { ?>
                        <?php if ($table != 'cms_components') { ?>
                            <?php echo $it['otstup']; ?><a class="lt_link" href="<?php echo $it['link']; ?>"><?php echo $it['title']; ?></a>
                        <?php } else { ?>
                            <span class="lt_link" style="padding:1px; padding-left:24px; background:url(<?php echo $item['icon']; ?>) no-repeat;">
                                <?php if (!empty($it['link'])) { ?>
                                    <a class="lt_link" href="<?php echo $it['link']; ?>">
                                        <?php echo $it['title']; ?>
                                    </a>
                                <?php } else { ?>
                                    <?php echo $it['title']; ?>
                                <?php } ?>
                            </span>
                        <?php } ?>
                    <?php } else if ($it['type'] == 'published') { ?>
                        <a title="<?php echo $it['title']; ?>" class="uittip" id="publink<?php echo $id; ?>" href="<?php echo $it['link']; ?>"><img id="pub<?php echo $id; ?>" src="<?php echo $it['icon']; ?>" border="0" /></a>
                    <?php } else if ($it['type'] == 'default') { ?>
                        <?php echo $it['otstup'] .' '. $it['title']; ?>
                    <?php } else if ($it['type'] == 'ordering') { ?>
                        <a title="<?php echo $_LANG['AD_DOWN']; ?>" href="<?php echo $it['link_down']; ?>"><img src="images/actions/down.gif" border="0" /></a>

                        <?php if ($table != 'cms_menu' && $table != 'cms_category') { ?>
                            <input class="lt_input" type="text" size="4" name="ordering[]" value="<?php echo $it['value']; ?>" />
                            <input name="ids[]" type="hidden" value="<?php echo $id; ?>" />
                        <?php } else { ?>
                            <input class="lt_input" type="text" size="4" name="ordering[]" value="<?php echo $it['value']; ?>" disabled />
                        <?php } ?>

                        <a title="<?php echo $_LANG['AD_UP']; ?>" href="<?php echo $it['link_up']; ?>"><img src="images/actions/top.gif" border="0" /></a>
                    <?php } ?>
                    </td>
                <?php } ?>
                    
                <?php if ($actions) { ?>
                    <td width="110" align="right" valign="middle">
                        <div style="padding-right:8px">
                            <?php foreach ($actions[$id] as $action) { ?>
                                <a href="<?php echo $action['link']; ?>" class="uittip" title="<?php echo $this->escape($action['title']); ?>" <?php if (isset($action['target'])) { ?>target="<?php echo $action['target']; ?>"<?php } ?>><img hspace="2" src="<?php echo $action['icon']; ?>" border="0" alt="<?php echo $this->escape($action['title']); ?>" /></a>
                            <?php } ?>
                        </div>
                    </td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</form>
<?php } else { ?>
<p class="cp_message"><?php echo $_LANG['OBJECTS_NOT_FOUND']; ?></p>
<?php } ?>

<script type="text/javascript">trClickChecked();</script>
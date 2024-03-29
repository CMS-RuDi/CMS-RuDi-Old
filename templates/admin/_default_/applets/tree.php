<?php if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); } ?>

<table class="table" style="margin-top:2px">
    <tr>
        <td valign="top" width="240" style="<?php if ($hide_cats) { ?>display:none;<?php } ?>" id="cats_cell">
            <ul class="list-group">
                <li class="list-group-item fa fa-plus-circle">
                    <a href="?view=cats&do=add" style="color:#09C"><?php echo $_LANG['AD_CREATE_SECTION']; ?></a>
                </li>
                <li class="list-group-item fa fa-folder-open">
                    <?php if (!$only_hidden) { ?>
                        <a href="<?php echo $base_uri .'&orderby=pubdate&orderto=desc&only_hidden=1'; ?>" style="font-weight:bold"><?php echo $_LANG['ON_MODERATE']; ?></a>
                    <?php } else { $current_cat = $_LANG['ON_MODERATE']; echo $current_cat; } ?>
                </li>
                <li class="list-group-item fa fa-folder-open">
                    <?php if ($category_id || $only_hidden) { ?>
                        <a href="<?php echo $base_uri; ?>" style="font-weight:bold"><?php echo $_LANG['AD_PAGE_ALL']; ?></a>
                    <?php } else { $current_cat = $_LANG['AD_PAGE_ALL']; echo $current_cat; } ?>
                </li>
                <li class="list-group-item fa fa-folder-open">
                    <?php if ($category_id != 1) { ?>
                        <a href="<?php echo $base_uri .'&cat_id=1'; ?>" style="font-weight:bold"><?php echo $_LANG['AD_ROOT_CATEGORY']; ?></a>
                    <?php } else { $current_cat = $_LANG['AD_ROOT_CATEGORY']; echo $current_cat; } ?>
                </li>
                <?php if (is_array($cats)) { ?>
                    <?php foreach($cats as $num => $cat) { ?>
                        <li class="list-group-item fa fa-folder-open" style="padding-left:<?php echo ($cat['NSLevel'])*20; ?>px;">
                        <?php if ($category_id != $cat['id']) { ?>
                            <a href="<?php echo $base_uri .'&cat_id='.$cat['id']; ?>" style="<?php if ($cat['NSLevel']==1){ echo 'font-weight:bold'; } ?>"><?php echo $cat['title']; ?></a>
                        <?php } else { ?>
                            <?php echo $cat['title']; $current_cat = $cat['title']; ?>
                        <?php } ?>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </td>

        <td valign="top" id="slide_cell" class="<?php if ($hide_cats) { ?>unslided<?php } ?>" onclick="$('#cats_cell').toggle();$(this).toggleClass('unslided');$('#filter_form input[name=hide_cats]').val(1-$('#cats_cell:visible').length)">&nbsp;

        </td>

        <td valign="top" style="padding-left:2px">
            <form class="form-inline" action="<?php echo $base_uri; ?>" method="GET" id="filter_form">
                <input type="hidden" name="view" value="tree" />
                <input type="hidden" name="do" value="tree" />
                <input type="hidden" name="cat_id" value="<?php echo $category_id; ?>" />
                <input type="hidden" name="hide_cats" value="<?php echo $hide_cats; ?>" />

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <span style="font-size:16px;color:#0099CC;font-weight:bold;">
                                <?php echo $current_cat; ?> <?php if ($category_id) { ?>[id=<?php echo $category_id; ?>]<?php } ?>
                            </span>
                            <span style="padding-left: 15px;">
                                <a class="uittip" title="<?php echo $_LANG['ADD_ARTICLE']; ?>" href="?view=content&do=add<?php if ($category_id) { ?>&to=<?php echo $category_id; } ?>">
                                    <img border="0" hspace="2" alt="<?php echo $_LANG['AD_ADD_ARTICLE']; ?>" src="images/actions/add.gif"/>
                                </a>
                                <?php if ($category_id > 1) { ?>
                                    <a class="uittip" title="<?php echo $_LANG['AD_EDIT_SECTION']; ?>" href="?view=cats&do=edit&id=<?php echo $category_id; ?>">
                                        <img border="0" hspace="2" alt="<?php echo $_LANG['AD_EDIT_SECTION']; ?>" src="images/actions/edit.gif"/>
                                    </a>
                                    <a class="uittip" title="<?php echo $_LANG['AD_CATEGORY_DELETE']; ?>" onclick="deleteCat('<?php echo $current_cat; ?>', <?php echo $category_id; ?>)" href="#">
                                        <img border="0" hspace="2" alt="<?php echo $_LANG['AD_CATEGORY_DELETE']; ?>" src="images/actions/delete.gif"/>
                                    </a>
                                <?php } ?>
                            </span>
                        </div>
                        <div class="form-group" style="margin-left:20px;">
                            <select class="form-control" style="width:140px;" name="orderby" onchange="$('#filter_form').submit()">
                                <?php if($category_id){ ?>
                                <option value="ordering" <?php if ($orderby == 'ordering') { ?>selected="selected"<?php } ?>><?php echo $_LANG['AD_BY_ORDER']; ?></option>
                                <?php } ?>
                                <option value="title" <?php if ($orderby == 'title') { ?>selected="selected"<?php } ?>><?php echo $_LANG['AD_BY_TITLE']; ?></option>
                                <option value="pubdate" <?php if ($orderby == 'pubdate') { ?>selected="selected"<?php } ?>><?php echo $_LANG['AD_BY_CALENDAR']; ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" style="width:160px;" name="orderto" onchange="$('#filter_form').submit()">
                                <option value="asc" <?php if ($orderto == 'asc') { ?>selected="selected"<?php } ?>><?php echo $_LANG['AD_BY_INCREMENT']; ?></option>
                                <option value="desc" <?php if ($orderto == 'desc') { ?>selected="selected"<?php } ?>><?php echo $_LANG['AD_BY_DECREMENT']; ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" style="width:300px;" name="title" value="<?php echo $title_part; ?>" placeholder="<?php echo $_LANG['TITLE']; ?>" />
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-default" name="filter" value="&raquo;" style="width:30px" />
                        </div>
                    </div>
                </div>
            </form>

            <form name="selform" action="index.php?view=components" method="post">
                <table id="listTable" class="table table-striped tablesorter">
                    <thead>
                        <tr>
                            <th class="lt_header" align="center" width="20">
                                <a class="lt_header_link" title="<?php echo $_LANG['AD_INVERT_SELECTION']; ?>" href="javascript:" onclick="javascript:invert()">#</a>
                            </th>
                            <th class="lt_header" width="25">id</th>
                            <th class="lt_header" width="" colspan="2"><?php echo $_LANG['TITLE']; ?></th>
                            <th class="lt_header" width="120"><?php echo $_LANG['DATE']; ?></th>
                            <th class="lt_header" width="50"><?php echo $_LANG['AD_IS_PUBLISHED']; ?></th>
                            <?php if ($category_id && count($items) > 1) { ?>
                            	<th class="lt_header" width="50"><?php echo $_LANG['AD_ORDER']; ?></th>
                                <th class="lt_header" width="34">&darr;&uarr;</th>
                            <?php } ?>
                            <th class="lt_header" align="center" width="120"><?php echo $_LANG['AD_ACTIONS']; ?></th>
                        </tr>
                    </thead>
                    <?php if ($items) { ?>
                        <tbody>
                            <?php foreach($items as $num => $item) { ?>
                                <tr id="<?php echo $item['id']; ?>" class="item_tr">
                                    <td><input type="checkbox" name="item[]" value="<?php echo $item['id']; ?>" /></td>
                                    <td><?php echo $item['id']; ?></td>
                                    <td width="16">
                                        <img src="/templates/<?php echo cmsCore::c('config')->template; ?>/images/icons/article.png" border="0"/>
                                    </td>
                                    <td>
                                        <a href="index.php?view=content&do=edit&id=<?php echo $item['id']; ?>">
                                            <?php echo $item['title']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $item['fpubdate']; ?></td>
                                    <td>
                                        <?php if ($item['published']) { ?>
                                            <a class="uittip" id="publink<?php echo $item['id']; ?>" href="javascript:pub(<?php echo $item['id']; ?>, 'view=content&do=hide&id=<?php echo $item['id']; ?>', 'view=content&do=show&id=<?php echo $item['id']; ?>', 'off', 'on');" title="<?php echo $_LANG['HIDE']; ?>">
                                                <img id="pub<?php echo $item['id']; ?>" border="0" src="images/actions/on.gif"/>
                                            </a>
                                        <?php } else { ?>
                                            <a class="uittip" id="publink<?php echo $item['id']; ?>" href="javascript:pub(<?php echo $item['id']; ?>, 'view=content&do=show&id=<?php echo $item['id']; ?>', 'view=content&do=hide&item_=<?php echo $item['id']; ?>', 'on', 'off');" title="<?php echo $_LANG['SHOW']; ?>">
                                                <img id="pub<?php echo $item['id']; ?>" border="0" src="images/actions/off.gif"/>
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <?php if ($category_id && count($items) > 1) { ?>
                                    <td class="ordering"><?php echo $item['ordering']; ?></td>
                                        <td>
                                            <?php
                                                $display_move_down = ($num < count($items)-1) ? 'inline' : 'none';
                                                $display_move_up   = ($num > 0) ? 'inline' : 'none';
                                            ?>
                                            <a class="move_item_down" href="javascript:void(0)" onclick="moveItem(<?php echo $item['id']; ?>, 1)" title="<?php echo $_LANG['AD_DOWN']; ?>" style="float:left;display:<?php echo $display_move_down; ?>"><img src="images/actions/down.gif" border="0"/></a>
                                            <a class="move_item_up" href="javascript:void(0)" onclick="moveItem(<?php echo $item['id']; ?>, -1)" title="<?php echo $_LANG['AD_UP']; ?>" style="float:right;display:<?php echo $display_move_up; ?>"><img src="images/actions/top.gif" border="0"/></a>
                                        </td>
                                    <?php } ?>
                                    <td align="right">
                                        <div style="padding-right: 8px;">
                                            <a class="uittip" title="<?php echo $_LANG['AD_VIEW_ONLINE']; ?>" href="/<?php echo $item['seolink'];?>.html">
                                                <img border="0" hspace="2" alt="<?php echo $_LANG['AD_VIEW_ONLINE']; ?>" src="images/actions/search.gif"/>
                                            </a>
                                            <a class="uittip" title="<?php echo $_LANG['EDIT']; ?>" href="?view=content&do=edit&id=<?php echo $item['id']; ?>">
                                                <img border="0" hspace="2" alt="<?php echo $_LANG['EDIT']; ?>" src="images/actions/edit.gif"/>
                                            </a>
                                            <a class="uittip" title="<?php echo $_LANG['AD_TO_ARHIVE']; ?>" href="?view=content&do=arhive_on&id=<?php echo $item['id']; ?>">
                                                <img border="0" hspace="2" alt="<?php echo $_LANG['AD_TO_ARHIVE']; ?>" src="images/actions/arhive_on.gif">
                                            </a>
                                            <a class="uittip" title="<?php echo $_LANG['DELETE']; ?>" onclick="jsmsg('<?php echo $_LANG['DELETE'].' '.$item['title']; ?>?', '?view=content&do=delete&id=<?php echo $item['id']; ?>')" href="#">
                                                <img border="0" hspace="2" alt="<?php echo $_LANG['DELETE']; ?>" src="images/actions/delete.gif"/>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    <?php } else { ?>
                        <tbody>
                            <td colspan="7" style="padding-left:5px"><div style="padding:15px;padding-left:0px"><?php echo $_LANG['AD_DONT_FIND_ARTICLES']; ?></div></td>
                        </tbody>
                    <?php } ?>
                </table>
                <?php if ($items) { ?>
                    <div style="margin-top:4px;padding-top:4px;">
                        <table class="" cellpadding="5" border="0" height="40">
                            <tr>
                                <td width="">
                                   <b><?php echo $_LANG['SELECTED_ITEMS']; ?>:</b>
                                </td>
                                <td width="" class="sel_pub">
                                    <input type="button" class="btn btn-default" name="" value="<?php echo $_LANG['EDIT']; ?>" onclick="sendContentForm('edit');" style="margin-left:5px;" />
                                    <input type="button" class="btn btn-default" name="" value="<?php echo $_LANG['AD_MOVE_TO']; ?>" onclick="$('.sel_move').toggle();$('.sel_pub').toggle();" style="margin-left:5px;" />
                                </td>
                                <td class="sel_move" style="display:none">
                                    <?php echo $_LANG['AD_MOVE_TO_CATEGORY']; ?>
                                </td>
                                <td class="sel_move" style="display:none">
                                    <select id="move_cat_id" class="form-control" style="width:250px;margin-left:5px;">
                                        <option value="1"><?php echo $_LANG['AD_ROOT_CATEGORY']; ?></option>
                                        <?php echo $category_opt; ?>
                                    </select>
                                </td>
                                <td class="sel_move" style="display:none">
                                    <input type="button" class="btn btn-default" name="" value="<?php echo $_LANG['AD_OKAY']; ?>" onclick="sendContentForm('move_to_cat', $('select#move_cat_id').val());" style="margin-left:5px;" />
                                    <input type="button" class="btn btn-default" name="" value="<?php echo $_LANG['CANCEL']; ?>" onclick="$('td.sel_move').toggle();$('td.sel_pub').toggle();" style="margin-left:5px;" /> <?php echo $_LANG['AD_CHANGE_URL']; ?>
                                </td>
                                <td class="sel_pub">
                                    <input type="button" class="btn btn-default" name="" value="<?php echo $_LANG['SHOW']; ?>" onclick="sendContentForm('show');" style="margin-left:5px;" />
                                    <input type="button" class="btn btn-default" name="" value="<?php echo $_LANG['HIDE']; ?>" onclick="sendContentForm('hide');" style="margin-left:5px;" />
                                </td>
                                <td class="sel_pub">
                                    <input type="button" class="btn btn-danger" name="" value="<?php echo $_LANG['DELETE']; ?>" onclick="sendContentForm('delete');" style="margin-left:5px;" />
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php } ?>
            </form>

            <?php
                if ($pages > 1) {
                    echo '<div class="panel panel-default"><div class="panel-body">';
                    echo cmsPage::getPagebar($total, $page, $perpage, $base_uri .'&hide_cats='. $hide_cats .'&title='. $title_part .'&orderby='. $orderby .'&orderto='. $orderto .'&cat_id='. $category_id .'&page=%page%');
                    echo '</div></div>';
                }
            ?>
        </td>
    </tr>
</table>

<script type="text/javascript"> trClickChecked(); </script>
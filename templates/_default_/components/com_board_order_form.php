<form action="<?php echo $action_url; ?>" method="POST" id="obform">
    <div class="photo_sortform">
        <table cellspacing="2" cellpadding="2" >
            <tr>
                <td><?php echo $_LANG['TYPE']; ?>: </td>
                <td>
                    <select name="obtype" id="obtype" onchange="$('form#obform').submit();">
                        <option value="all" <?php if (empty($btype)) { ?> selected <?php } ?>><?php echo $_LANG['ALL_TYPE']; ?></option>
                        <?php echo $btypes; ?>
                    </select>
                </td>
                <td><?php echo $_LANG['CITY']; ?>: </td>
                <td>
                    <?php echo $bcities; ?>
                </td>
                <td><?php echo $_LANG['ORDER']; ?>: </td>
                <td>
                    <select name="orderby" id="orderby">
                        <option value="title" <?php if ($orderby == 'title') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_TITLE']; ?></option>
                        <option value="pubdate" <?php if ($orderby == 'pubdate') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_DATE']; ?></option>
                        <option value="hits" <?php if ($orderby == 'hits') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_HITS']; ?></option>
                        <option value="obtype" <?php if ($orderby == 'obtype') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_TYPE']; ?></option>
                        <option value="user_id" <?php if ($orderby == 'user_id') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_AVTOR']; ?></option>
                    </select>
                    <select name="orderto" id="orderto">
                        <option value="desc" <?php if ($orderto == 'desc') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_DESC']; ?></option>
                        <option value="asc" <?php if ($orderto == 'asc') { ?> selected <?php } ?>><?php echo $_LANG['ORDERBY_ASC']; ?></option>
                    </select>
                    <input type="submit" value="<?php echo $_LANG['FILTER']; ?>" />
                </td>
            </tr>
        </table>
    </div>
</form>
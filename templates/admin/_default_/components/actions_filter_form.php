<nav class="navbar navbar-default" role="navigation">
    <ul class="nav nav-tabs">
        <li>
            <a class="uittip" href="?view=components&do=config&id=<?php echo $id; ?>&opt=config" title="<?php echo $_LANG['AD_CONFIG']; ?>"><img src="images/toolmenu/config.gif" /></a>
        </li>
        <li>
            <form id="filter_form" class="navbar-form navbar-left" action="?view=components&do=config&id=<?php echo $id; ?>" method="post">
                <?php echo $_LANG['AD_ACTIONS_FROM']; ?>:
                <select class="form-control" style="width:215px" name="act_component" onchange="$('#filter_form').submit()">
                    <option value="" <?php if(!$act_component){ ?>selected="selected"<?php } ?>><?php echo $_LANG['AD_ACTIONS_FROM_ALL_COM']; ?></option>
                    <?php foreach($act_components as $act_com) {
                        if ($act_com['link'] == $act_component) {
                            echo '<option value="'. $act_com['link'] .'" selected="selected">'. $act_com['title'] .'</option>';
                        } else {
                            echo '<option value="'. $act_com['link'] .'">'. $act_com['title'] .'</option>';
                        }
                    } ?>
                </select>
            </form>
        </li>
    </ul>
</nav>
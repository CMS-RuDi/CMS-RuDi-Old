<div id="mod_user_stats">
    <?php if ($cfg['show_total']) { ?>
    <div class="stat_block">
        <div class="title"><?php echo $_LANG['HOW_MUCH_US']; ?></div>
        <div class="body">
            <ul>
                <li><?php echo $this->spellcount($total_usr, $_LANG['USER'], $_LANG['USER2'], $_LANG['USER10']); ?></li>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if ($cfg['show_online']) { ?>
    <div class="stat_block">
        <div class="title"><?php echo $_LANG['STATS_WHO_ONLINE']; ?></div>
        <div class="body">
            <ul>
                <li><?php echo $this->spellcount($people['users'], $_LANG['USER'], $_LANG['USER2'], $_LANG['USER10']); ?></li>
                <li><?php echo $this->spellcount($people['guests'], $_LANG['GUEST'], $_LANG['GUEST2'], $_LANG['GUEST10']); ?></li>
                <li>
                <?php if ($usr_online) { ?>
                    <a href="/users/all.html" rel="nofollow"><?php echo $_LANG['SHOW_ALL']; ?></a>
                <?php } else { ?>
                    <a href="/users/online.html" rel="nofollow"><?php echo $_LANG['SHOW_ONLY_ONLINE']; ?></a>
                <?php } ?>
                </li>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if ($cfg['show_gender']) { ?>
    <div class="stat_block">
        <div class="title"><?php echo $_LANG['STATS_WHO']; ?></div>
        <div class="body">
            <ul>
                <li><a href="javascript:void(0)" rel=”nofollow” onclick="searchGender('m')"><?php echo $this->spellcount($gender_stats['male'], $_LANG['MALE1'], $_LANG['MALE2'], $_LANG['MALE10']); ?></a></li>
                <li><a href="javascript:void(0)" rel=”nofollow” onclick="searchGender('f')"><?php echo $this->spellcount($gender_stats['female'], $_LANG['FEMALE1'], $_LANG['FEMALE2'], $_LANG['FEMALE10']); ?></a></li>
                <li><?php echo $_LANG['UNKNOWN']; ?> &mdash; <?php echo $gender_stats['unknown']; ?></li>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if ($cfg['show_city']) { ?>
    <div class="stat_block">
        <div class="title"><?php echo $_LANG['WHERE_WE_FROM']; ?></div>
        <div class="body">
            <ul>
                <?php foreach($city_stats as $city) { ?>
                    <?php if ($city['href']) { ?>
                        <li><a href="<?php echo $city['href']; ?>" rel=”nofollow”><?php echo $city['city']; ?></a> &mdash; <?php echo $city['count']; ?></li>
                    <?php } else { ?>
                        <li><?php echo $city['city']; ?> &mdash; <?php echo $city['count']; ?></li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if ($cfg['show_bday'] && $bday) { ?>
        <div class="stat_block_bday" style="margin-top:10px;">
            <div class="title"><?php echo $_LANG['TODAY_BIRTH']; ?>:</div>
            <div class="body">
                <?php echo $bday; ?>
            </div>
        </div>
    <?php } ?>
</div>

<script type="text/javascript">
function searchGender(gender){
    $('body').append('<form id="sform" style="display:none" method="post" action="/users"><input type="hidden" name="gender" value="'+gender+'"/></form>');
    $('form#sform').submit();
}
</script>
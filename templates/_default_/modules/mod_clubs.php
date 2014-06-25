<div class="mod_clubs">
<?php foreach ($clubs as $club) { ?>
    <div class="club_entry<?php if ($club['is_vip']) { echo '_vip'; } ?>">
        <div class="image">
            <a href="/clubs/<?php echo $club['id']; ?>" title="<?php echo $this->escape($club['title']); ?>" class="<?php echo $club['clubtype']; ?>">
                <img src="/images/clubs/small/<?php echo $club['imageurl']; ?>" border="0" alt="<?php echo $this->escape($club['title']); ?>"/>
            </a>
        </div>
        <div class="data">
            <div class="title">
                <a href="/clubs/<?php echo $club['id']; ?>" class="<?php echo $club['clubtype']; ?>" <?php if ($club['clubtype'] == 'private') { ?>title="<?php echo $_LANG['PRIVATE']; ?>"<?php } ?>><?php echo $club['title']; ?></a>
            </div>
            <div class="details">
            <?php if ($club['is_vip']) { ?>
                <span class="vip"><strong><?php echo $_LANG['VIP_CLUB']; ?></strong></span>
            <?php } ?>
                <span class="rating"><strong><?php echo $_LANG['RATING']; ?></strong> &mdash; <?php echo $club['rating']; ?></span>
                <span class="members"><strong><?php echo $this->spellcount($club['members_count'], $_LANG['CLUB_USER'], $_LANG['CLUB_USER2'], $_LANG['CLUB_USER10']); ?></strong></span>
            </div>
        </div>
    </div>
<?php } ?>
</div>

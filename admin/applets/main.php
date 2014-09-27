<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.4                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

function newContent($table, $where='') {
    if ($where) { $where = ' AND '. $where; }
    $new = cmsCore::c('db')->get_field($table, "DATE_FORMAT(pubdate, '%d-%m-%Y') = DATE_FORMAT(NOW(), '%d-%m-%Y')". $where, 'COUNT(id)');
    return $new;
}

function applet_main() {
    $inCore = cmsCore::getInstance();
    
    global $_LANG;
    
    cmsCore::c('page')->setAdminTitle($_LANG['PATH_HOME']);
?>

<table class="table-condensed" width="100%">
    <tr>
        <td width="275" valign="top" style="padding-left:0px;">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $_LANG['AD_SITE_CONTENT']; ?></div>
                <ul class="list-group">
                    <?php if($inCore->isComponentEnable('content')) {
                        $new['content'] = (int)newContent('cms_content'); ?>
                        <li class="list-group-item">
                            <a href="index.php?view=tree"><?php echo $_LANG['AD_ARTICLES']; ?></a> <?php if($new['content']) { ?><span class="new_content">+<?php echo $new['content']?></span><?php } ?>

                            <a class="fa fa-plus right" href="index.php?view=content&amp;do=add" title="<?php echo $_LANG['AD_CREATE_ARTICLE']; ?>"></a>
                            <a class="fa fa-plus-square right" href="index.php?view=cats&amp;do=add" title="<?php echo $_LANG['AD_CREATE_SECTION']; ?>"></a>
                        </li>
                    <?php } ?>

                    <?php if($inCore->isComponentEnable('photos')) {
                        $new['photos'] = (int)newContent('cms_photo_files'); ?>
                        <li class="list-group-item">
                            <a href="index.php?view=components&amp;do=config&amp;link=photos"><?php echo $_LANG['AD_PHOTOGALLERY']; ?></a> <?php if($new['photos']) { ?><span class="new_content">+<?php echo $new['photos']?></span><?php } ?>

                            <a class="fa fa-plus-square right" href="index.php?view=components&amp;do=config&amp;link=photos&amp;opt=add_album" title="<?php echo $_LANG['AD_CREATE_ALBUM']; ?>"></a>
                        </li>
                    <?php } ?>

                    <?php if($inCore->isComponentEnable('video')) {
                        $new['video'] = (int)newContent('cms_video_movie'); ?>
                        <li class="list-group-item">
                            <a href="index.php?view=components&amp;do=config&amp;link=video"><?php echo $_LANG['AD_VIDEOGALLERY']; ?></a> <?php if($new['video']) { ?><span class="new_content">+<?php echo $new['video']?></span><?php } ?>

                            <a class="fa fa-plus-square right" href="index.php?view=components&amp;do=config&amp;link=video&amp;opt=add_cat" title="<?php echo $_LANG['AD_CREATE_CATEGORY']; ?>"></a>
                        </li>
                    <?php } ?>

                    <?php if($inCore->isComponentEnable('maps')) {
                        $new['maps'] = (int)newContent('cms_map_items'); ?>
                        <li class="list-group-item">
                            <a href="index.php?view=components&amp;do=config&amp;link=maps"><?php echo $_LANG['AD_GEO_CATALOG']; ?></a> <?php if($new['maps']) { ?><span class="new_content">+<?php echo $new['maps']?></span><?php } ?>

                            <a class="fa fa-plus right" href="index.php?view=components&amp;do=config&amp;link=maps&amp;opt=add_item" title="<?php echo $_LANG['AD_ADD_OBJECT']; ?>"></a>
                            <a class="fa fa-plus-square right" href="index.php?view=components&amp;do=config&amp;link=maps&amp;opt=add_cat" title="<?php echo $_LANG['AD_CREATE_CATEGORY']; ?>"></a>
                        </li>
                    <?php } ?>
                    
                    <?php if($inCore->isComponentEnable('faq')) {
                        $new['faq'] = (int)newContent('cms_faq_quests'); ?>
                        <li class="list-group-item">
                            <a href="index.php?view=components&amp;do=config&amp;link=faq"><?php echo $_LANG['AD_A&Q']; ?></a> <?php if($new['faq']) { ?><span class="new_content">+<?php echo $new['faq']?></span><?php } ?>
                            
                            <a class="fa fa-plus right" href="index.php?view=components&amp;do=config&amp;link=faq&amp;opt=add_item" title="<?php echo $_LANG['AD_CREATE_QUESTION']; ?>"></a>
                            <a class="fa fa-plus-square right" href="index.php?view=components&amp;do=config&amp;link=faq&amp;opt=add_cat" title="<?php echo $_LANG['AD_CREATE_CATEGORY']; ?>"></a>
                        </li>
                    <?php } ?>
                        
                    <?php if($inCore->isComponentEnable('board')) {
                        $new['board'] = (int)newContent('cms_board_items'); ?>
                        <li class="list-group-item">
                            <a href="index.php?view=components&amp;do=config&amp;link=board"><?php echo $_LANG['AD_BOARD']; ?></a> <?php if($new['board']) { ?><span class="new_content">+<?php echo $new['board']?></span><?php } ?>
                            
                            <a class="fa fa-plus right" href="index.php?view=components&amp;do=config&amp;link=board&amp;opt=add_item" title="<?php echo $_LANG['AD_CREATE_ADVERT']; ?>"></a>
                            <a class="fa fa-plus-square right" href="index.php?view=components&amp;do=config&amp;link=board&amp;opt=add_cat" title="<?php echo $_LANG['AD_CREATE_RUBRIC']; ?>"></a>
                        </li>
                    <?php } ?>
                        
                    <?php if($inCore->isComponentEnable('catalog')) {
                        $new['catalog'] = (int)newContent('cms_uc_items'); ?>
                        <li class="list-group-item">
                            <a href="index.php?view=components&amp;do=config&amp;link=catalog"><?php echo $_LANG['AD_CATALOG']; ?></a> <?php if($new['catalog']) { ?><span class="new_content">+<?php echo $new['catalog']?></span><?php } ?>
                            
                            <a class="fa fa-plus right" href="index.php?view=components&amp;do=config&amp;link=catalog&amp;opt=add_item" title="<?php echo $_LANG['AD_CREATE_ITEM'];?>"></a>
                            <a class="fa fa-plus-square right" href="index.php?view=components&amp;do=config&amp;link=catalog&amp;opt=add_cat" title="<?php echo $_LANG['AD_CREATE_RUBRIC'];?>"></a>
                        </li>
                    <?php } ?>
                        
                    <?php if($inCore->isComponentEnable('forum')) {
                        $new['forum'] = (int)newContent('cms_forum_posts'); ?>
                        <li class="list-group-item">
                            <a href="index.php?view=components&amp;do=config&amp;link=forum&amp;opt=list_forums"><?php echo $_LANG['AD_FORUMS']; ?></a> <?php if($new['forum']) { ?><span class="new_content">+<?php echo $new['forum']?></span><?php } ?>

                            <a class="fa fa-plus right" href="index.php?view=components&amp;do=config&amp;link=forum&amp;opt=add_forum" title="<?php echo $_LANG['AD_CREATE_FORUM']; ?>"></a>
                            <a class="fa fa-plus-square right" href="index.php?view=components&amp;do=config&amp;link=forum&amp;opt=add_cat" title="<?php echo $_LANG['AD_CREATE_CATEGORY']; ?>"></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $_LANG['AD_USERS']; ?></div>
                <ul class="list-group">
                    <li class="list-group-item fa fa-users">
                        <a href="index.php?view=users"><?php echo $_LANG['AD_FROM_USERS']; ?></a> &mdash; <?php echo cmsCore::c('db')->rows_count('cms_users', 'is_deleted=0'); ?>
                    </li>
                    <li class="list-group-item fa fa-users">
                        <?php echo $_LANG['AD_NEW_USERS_TODAY']; ?> &mdash; <?php echo (int)cmsCore::c('db')->get_field('cms_users', "DATE_FORMAT(regdate, '%d-%m-%Y') = DATE_FORMAT(NOW(), '%d-%m-%Y') AND is_deleted = 0", 'COUNT(id)'); ?>
                    </li>
                    <li class="list-group-item fa fa-users">
                        <?php echo $_LANG['AD_NEW_USERS_THEES_WEEK']; ?> &mdash; <?php echo (int)cmsCore::c('db')->get_field('cms_users', "regdate >= DATE_SUB(NOW(), INTERVAL 7 DAY)", 'COUNT(id)'); ?>
                    </li>
                    <li class="list-group-item fa fa-users">
                        <?php echo $_LANG['AD_NEW_USERS_THEES_MONTH']; ?> &mdash; <?php echo (int)cmsCore::c('db')->get_field('cms_users', "regdate >= DATE_SUB(NOW(), INTERVAL 1 MONTH)", 'COUNT(id)'); ?>
                    </li>
                </ul>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $_LANG['AD_USERS_ONLINE']; ?></div>
                <?php $people = cmsUser::getOnlineCount(); ?>
                <ul class="list-group">
                    <li class="list-group-item fa fa-user">
                        <?php echo $_LANG['AD_FROM_USERS'] .': '. $people['users']; ?>
                    </li>
                    <li class="list-group-item">
                        <?php echo $_LANG['AD_FROM_GUESTS'] .': '. $people['guests']; ?>
                    </li>
                </ul>
            </div>
        </td>
        
        <td width="" valign="top" style="">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $_LANG['AD_LATEST_EVENTS']; ?></div>
                <div class="panel-body" id="actions_box">
                    <div id="actions">
                        <?php
                            cmsCore::c('actions')->showTargets(true);
                            
                            $total = cmsCore::c('actions')->getCountActions();
                            
                            cmsCore::c('db')->limitPage(1, 10);
                            
                            $actions = cmsCore::c('actions')->getActionsLog();
                            
                            $pagebar = cmsPage::getPagebar($total, 1, 10, '#" onclick="$.post(\'/admin/ajax/getActions.php\', \'page=%page%\', function(m){ $(\'#actions\').html(m); }); return false');

                            $tpl_file   = 'admin/actions.php';
                            $tpl_dir    = file_exists(TEMPLATE_DIR . $tpl_file) ? TEMPLATE_DIR : DEFAULT_TEMPLATE_DIR;

                            include($tpl_dir . $tpl_file);
                        ?>
                    </div>
                </div>
            </div>
        </td>
        
        <td width="325" valign="top" style="">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="100" valign="top">
                        <?php
                            $new_quests  = cmsCore::c('db')->rows_count('cms_faq_quests', 'published = 0');
                            $new_content = cmsCore::c('db')->rows_count('cms_content', 'published = 0 AND is_arhive = 0');
                            $new_catalog = cmsCore::c('db')->rows_count('cms_uc_items', 'on_moderate = 1');
                        ?>
                        <?php if ($new_quests || $new_content || $new_catalog){ ?>
                            <div class="panel panel-default">
                                <div class="panel-heading"><?php echo $_LANG['AD_FROM_MODERATION']; ?></div>
                                <ul class="list-group">
                                    <?php if ($new_content){ ?>
                                    <li class="list-group-item fa fa-file-o">
                                        <a href="index.php?view=tree&orderby=pubdate&orderto=desc&only_hidden=1"><?php echo $_LANG['AD_ARTICLES']; ?></a> (<?php echo $new_content; ?>)
                                    </li>
                                    <?php } ?>
                                    <?php if ($new_quests){ ?>
                                    <li class="list-group-item fa fa-question-circle">
                                        <a href="index.php?view=components&amp;do=config&amp;link=faq&amp;opt=list_items"><?php echo $_LANG['AD_QUESTIONS']; ?></a> (<?php echo $new_quests; ?>)
                                    </li>
                                    <?php } ?>
                                    <?php if ($new_catalog){ ?>
                                    <li class="list-group-item fa fa-folder">
                                        <a href="index.php?view=components&amp;do=config&amp;link=catalog&amp;opt=list_items&amp;on_moderate=1"><?php echo $_LANG['AD_CATALOG_ITEMS']; ?></a> (<?php echo $new_catalog; ?>)
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                        
                        <?php if ($inCore->isComponentInstalled('rssfeed')){ ?>
                            <div class="panel panel-default">
                                <div class="panel-heading"><?php echo $_LANG['AD_RSS']; ?></div>
                                <ul class="list-group">
                                    <li class="list-group-item fa fa-rss">
                                        <a href="/rss/comments/all/feed.rss" id="rss_link"><?php echo $_LANG['AD_RSS_COMENT']; ?> </a>
                                    </li>
                                    <li class="list-group-item fa fa-rss">
                                        <a href="/rss/blogs/all/feed.rss" id="rss_link"><?php echo $_LANG['AD_RSS_BLOGS']; ?></a>
                                    </li>
                                    <li class="list-group-item fa fa-rss">
                                        <a href="/rss/forum/all/feed.rss" id="rss_link"><?php echo $_LANG['AD_RSS_FORUM']; ?></a>
                                    </li>
                                    <li class="list-group-item fa fa-rss">
                                        <a href="/rss/catalog/all/feed.rss" id="rss_link"><?php echo $_LANG['AD_RSS_CATALOG']; ?></a>
                                    </li>
                                    <li class="list-group-item fa fa-rss">
                                        <a href="/rss/content/all/feed.rss" id="rss_link"><?php echo $_LANG['AD_RSS_CONTENT']; ?></a>
                                    </li>
                                    <li class="list-group-item fa fa-rss">
                                        <a href="/rss/board/all/feed.rss" id="rss_link"><?php echo $_LANG['AD_RSS_ADVERTS']; ?></a>
                                    </li>
                                    <li class="list-group-item fa fa-cog">
                                        <a href="index.php?view=components&amp;do=config&amp;id=<?php echo cmsCore::c('db')->get_field('cms_components', "link='rssfeed'", 'id'); ?>" id="rss_link"><?php echo $_LANG['AD_RSS_TUNING']; ?></a>
                                    </li>
                                </ul>
                            </div>
                        <?php } ?>
                        
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php echo $_LANG['AD_ICMS_RAVE']; ?></div>
                            <ul class="list-group">
                                <li class="list-group-item fa fa-external-link">
                                    <a href="http://cmsrudi.ru/"><?php echo $_LANG['AD_CMSRD_OFFICIAL']; ?></a>
                                </li>
                                <li class="list-group-item fa fa-external-link">
                                    <a href="http://cmsrudi.ru/docs"><?php echo $_LANG['AD_CMSRD_DOCUMENTATION']; ?></a>
                                </li>
                                <li class="list-group-item fa fa-external-link">
                                    <a href="http://www.instantcms.ru/forum"><?php echo $_LANG['AD_ICMS_FORUM']; ?></a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php echo $_LANG['AD_PREMIUM']; ?></div>
                            <ul class="list-group">
                                <li class="list-group-item fa fa-usd">
                                    <a href="http://www.instantcms.ru/billing/about.html"><strong><?php echo $_LANG['AD_BILLING']; ?></strong></a> &mdash; <?php echo $_LANG['AD_GAIN']; ?>
                                </li>
                                <li class="list-group-item fa fa-map-marker">
                                    <a href="http://www.instantmaps.ru/"><strong>InstantMaps</strong></a> &mdash; <?php echo $_LANG['AD_OBJECT_TO_MAP']; ?>
                                </li>
                                <li class="list-group-item fa fa-shopping-cart">
                                    <a href="http://www.instantcms.ru/blogs/InstantSoft/professionalnyi-magazin-dlja-InstantCMS.html"><strong>InstantShop</strong></a> &mdash; <?php echo $_LANG['AD_SHOP']; ?>
                                </li>
                                <li class="list-group-item fa fa-film">
                                    <a href="http://www.instantvideo.ru/"><strong>InstantVideo</strong></a> &mdash; <?php echo $_LANG['AD_VIDEO_GALERY']; ?>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php
    return true;
}
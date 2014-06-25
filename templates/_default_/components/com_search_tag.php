<div class="photo_details">
<div id="found_search"><strong><?php echo $_LANG['SEARCH_BY_TAG']; ?>:</strong> &laquo;<?php echo $query; ?>&raquo;, <?php echo $_LANG['SEARCH_FOR']; ?> <a href="javascript:" onclick="searchOtherTag()" class="ajaxlink"><?php echo $_LANG['ANOTHER_TAG']; ?></a></div>
<div id="other_tag" style="display:none">
    <form id="sform"action="/search" method="post" enctype="multipart/form-data">
        <strong><?php echo $_LANG['SEARCH_BY_TAG']; ?>: </strong>
        <input type="hidden" name="do" value="tag" />
        <input type="text" name="query" id="query" size="40" value="" class="text-input" />
		<script type="text/javascript">
            <?php echo $autocomplete_js; ?>
        </script>
        <input type="submit" value="<?php echo $_LANG['FIND']; ?>"/> <input type="button" value="<?php echo $_LANG['CANCEL']; ?>" onclick="$('#other_tag').hide();$('#found_search').fadeIn('slow');"/>
    </form>
</div>
</div>

<?php if ($results) { ?>
<p class="usr_photos_notice"><strong><?php echo $_LANG['FOUND']; ?> <?php echo $this->spellcount($total, $_LANG['1_MATERIALS'], $_LANG['2_MATERIALS'], $_LANG['10_MATERIALS']); ?></strong></p>
    <table width="100%" cellpadding="5" cellspacing="0" border="0">
	<?php foreach($results as $item) { ?>
        <tr>
            <td class="<?php echo $item['class']; ?>">
                    <div class="tagsearch_item">
                    <table><tr>
                        <td><img src="/components/search/tagicons/<?php echo $item['target']; ?>.gif"/></td>
                        <td><?php echo $item['itemlink']; ?></td>
                    </tr></table>
                    </div>
                    <div class="tagsearch_bar"><?php echo $item['tag_bar']; ?></div>
            </td>
        </tr>
	<?php } ?>
    </table>
	<?php echo $pagebar; ?>
<?php } else { ?>
<p class="usr_photos_notice"><?php echo $_LANG['BY_TAG']; ?> <strong>"<?php echo $query; ?>"</strong> <?php echo $_LANG['NOTHING_FOUND']; ?>. <a href="<?php echo $external_link; ?>" target="_blank"><?php echo $_LANG['CONTINUE_TO_SEARCH']; ?>?</a></p>
<?php } ?>

<script type="text/javascript">
function searchOtherTag(){
    $('#found_search').hide();$('#other_tag').fadeIn('slow');$('.text-input').focus();
}
</script>
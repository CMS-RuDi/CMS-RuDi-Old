<div class="photo_details">
    <form id="sform"action="/search" method="GET" enctype="multipart/form-data" style="clear:both">
        <strong><?php echo $_LANG['SEARCH_ON_SITE']; ?>: </strong>
        
        <input type="text" name="query" id="query" size="40" value="<?php echo $this->escape($query); ?>" class="text-input" />
        
        <select name="look" style="width:100px" onchange="$('form#sform').submit();	">
            <option value="allwords" <?php if ($look == 'allwords' || $look == '') { ?> selected="selected" <?php } ?>><?php echo $_LANG['ALL_WORDS']; ?></option>
            <option value="anyword" <?php if ($look == 'anyword') { ?> selected="selected" <?php } ?>><?php echo $_LANG['ANY_WORD']; ?></option>
            <option value="phrase" <?php if ($look == 'phrase') { ?> selected="selected" <?php } ?>><?php echo $_LANG['PHRASE']; ?></option>
        </select>
        
        <input type="submit" value="<?php echo $_LANG['FIND']; ?>"/>
        
        <a href="javascript:" onclick="$('#from_search').toggle('fast');" class="ajaxlink"><?php echo $_LANG['SEARCH_PARAMS']; ?></a>
        
        <div id="from_search">
            <strong><?php echo $_LANG['WHERE_TO_FIND']; ?>:</strong>
            
            <table width="" border="0" cellspacing="0" cellpadding="3">
              <?php $col = 1; ?>
                <?php foreach($enable_components as $enable_component) { ?>
                    <?php if ($col == 1) { ?> <tr> <?php } ?>
                    <td width="">
                    <label id="l_<?php echo $enable_component['link']; ?>" <?php if (in_array($enable_component['link'], $from_component) || !$from_component) { ?>class="selected"<?php } ?>>
                            <input name="from_component[]" onclick="toggleInput('l_<?php echo $enable_component['link']; ?>')" type="checkbox" value="<?php echo $enable_component['link']; ?>" <?php if (in_array($enable_component['link'], $from_component) || !$from_component) { ?>checked="checked"<?php } ?> />
                        <?php echo $enable_component['title']; ?></label></td>
                    <?php if ($col == 5) { $col = 1; echo '</tr>'; } else { $col++; } ?>
                <?php } ?>
                <?php if ($col > 1) { ?>
                    <td colspan="<?php echo 6 - $col; ?>">&nbsp;</td></tr>
                <?php } ?>
            </table>
            
            <p><strong><?php echo $_LANG['PUBDATE']; ?>:</strong></p>
            <select name="from_pubdate" style="width:200px">
              <option value="" <?php if (!$from_pubdate) { ?>selected="selected"<?php } ?>><?php echo $_LANG['ALL']; ?></option>
              <option value="d" <?php if ($from_pubdate == 'd') { ?>selected="selected"<?php } ?>><?php echo $_LANG['F_D']; ?></option>
              <option value="w" <?php if ($from_pubdate == 'w') { ?>selected="selected"<?php } ?>><?php echo $_LANG['F_W']; ?></option>
              <option value="m" <?php if ($from_pubdate == 'm') { ?>selected="selected"<?php } ?>><?php echo $_LANG['F_M']; ?></option>
              <option value="y" <?php if ($from_pubdate == 'y') { ?>selected="selected"<?php } ?>><?php echo $_LANG['F_Y']; ?></option>
            </select>
            
            <label id="order_by_date" <?php if ($order_by_date) { ?>class="selected"<?php } ?>>
                <input name="order_by_date" onclick="toggleInput('order_by_date')" type="checkbox" value="1" <?php if ($order_by_date) { ?>checked="checked"<?php } ?> /> <?php echo $_LANG['SORT_BY_PUBDATE']; ?>
            </label>
            
            <div style="position:absolute; bottom:0; right:0; font-size:10px;">
                <a href="javascript:void(0);" onclick="$('#sform input:checkbox').prop('checked', true);$('#from_search label').addClass('selected');" class="ajaxlink"><?php echo $_LANG['SELECT_ALL']; ?></a> |
                <a href="javascript:void(0);" onclick="$('#sform input:checkbox').prop('checked', false);$('#from_search label').removeClass('selected');" class="ajaxlink"><?php echo $_LANG['REMOVE_ALL']; ?></a>
            </div>
        </div>
    </form>
</div>

<?php if ($results) { ?>
    <?php $num = 1; ?>

    <p class="usr_photos_notice"><strong><?php echo $_LANG['FOUND']; ?> <?php echo $this->spellcount($total, $_LANG['1_MATERIALS'], $_LANG['2_MATERIALS'], $_LANG['10_MATERIALS']); ?></strong></p>
    
    <?php foreach($results as $item) { ?>
	<div class="search_block">
            <?php if ($item['pubdate']) { ?>
            	<div class="search_date"><?php echo $item['pubdate']; ?></div>
            <?php } ?>

            <div class="search_result_title">
                <span><?php echo $num; ?></span>
                <a href="<?php echo $item['link']; ?>" target="_blank"><?php echo $item['s_title']; ?></a>
            </div>

            <div class="search_result_desc">
                <?php if ($item['imageurl']) { ?>
                    <img class="bd_image_small" src="<?php echo $item['imageurl']; ?>" alt="<?php echo $this->escape($item['s_title']); ?>" />
                <?php } ?>

            	<?php if ($item['description']) { ?>
                    <p><?php echo $item['description']; ?></p>
                <?php } ?>

                <div class="search_result_link"><a href="<?php echo $item['placelink']; ?>"><?php echo $item['place']; ?></a> &mdash; <span style="color:green"><?php echo $host . $item['link']; ?></span></div>
            </div>
        </div>
        <?php $num++; ?>
    <?php } ?>

    <?php echo $pagebar; ?>
<?php } else { ?>
    <?php if ($query) { ?>
        <p class="usr_photos_notice"><?php echo $_LANG['BY_QUERY']; ?> <strong>"<?php echo $query; ?>"</strong> <?php echo $_LANG['NOTHING_FOUND']; ?>. <a href="<?php echo $external_link; ?>" target="_blank"><?php echo $_LANG['FIND_EXTERNAL']; ?></a></p>
    <?php } ?>
<?php } ?>

<script type="text/javascript">
    $(function() {
        $('#query').focus();
    });
    
    function toggleInput(id) {
        $('#from_search label#'+id).toggleClass('selected');
    }
    
    function paginator(page) {
        $('#sform').append('<input type="hidden" name="page" value="'+page+'" />');
        $('#sform').submit();
    }
</script>
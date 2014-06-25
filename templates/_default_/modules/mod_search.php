<form id="search_form"action="/search" method="GET" enctype="multipart/form-data" style="clear:both">
    <strong><?php echo $_LANG['SEARCH_ON_SITE']; ?>: </strong>
    <input type="text" name="query" id="query" size="40" value="" class="text-input" />
    
    <select name="look" style="width:100px" onchange="$('#search_form').submit();	">
        <option value="allwords" selected="selected"><?php echo $_LANG['ALL_WORDS']; ?></option>
        <option value="anyword" ><?php echo $_LANG['ANY_WORD']; ?></option>
        <option value="phrase" ><?php echo $_LANG['PHRASE']; ?></option>
    </select>
    
    <input type="submit" value="<?php echo $_LANG['FIND']; ?>"/>
    <a href="javascript:" onclick="$('#from_search').fadeIn('slow');" class="ajaxlink"><?php echo $_LANG['SEARCH_PARAMS']; ?></a>
    
    <div id="from_search">
        <strong><?php echo $_LANG['WHERE_TO_FIND']; ?>:</strong>
	<table width="" border="0" cellspacing="0" cellpadding="3">
            <?php $col = 1; ?>
            <?php foreach($enable_components as $enable_component) { ?>
                <?php if ($col == 1) { ?> <tr> <?php } ?>
                <td width="">
                <label id="l_<?php echo $enable_component['link']; ?>" class="selected">
                    <input name="from_component[]" onclick="toggleInput('l_<?php echo $enable_component['link']; ?>')" type="checkbox" value="<?php echo $enable_component['link']; ?>" checked="checked" />
                    <?php echo $enable_component['title']; ?></label></td>
                <?php if ($col == 5) { echo '</tr>'; $col = 1; } else { $col++; } ?>
            <?php } ?>
            <?php if ($col > 1) { ?>
                <td colspan="<?php echo (6-$col); ?>">&nbsp;</td></tr>
            <?php } ?>
        </table>
        
        <p><strong><?php echo $_LANG['PUBDATE']; ?>:</strong></p>
        <select name="from_pubdate" style="width:200px">
            <option value="" selected="selected"><?php echo $_LANG['ALL']; ?></option>
            <option value="d" ><?php echo $_LANG['F_D']; ?></option>
            <option value="w" ><?php echo $_LANG['F_W']; ?></option>
            <option value="m" ><?php echo $_LANG['F_M']; ?></option>
            <option value="y" ><?php echo $_LANG['F_Y']; ?></option>
        </select>
        
        <label id="order_by_date" class="selected">
            <input name="order_by_date" onclick="toggleInput('order_by_date')" type="checkbox" value="1" checked="checked" />
            <?php echo $_LANG['SORT_BY_PUBDATE']; ?>
        </label>
        
        <div style="position:absolute; top:0; right:0; font-size:10px;">
            <a href="javascript:" onclick="$('#from_search').fadeOut();" class="ajaxlink"><?php echo $_LANG['HIDE']; ?></a>
        </div>
        
        <div style="position:absolute; bottom:0; right:0; font-size:10px;">
            <a href="javascript:" onclick="$('#search_form input:checkbox').prop('checked', true);$('#from_search label').addClass('selected');" class="ajaxlink"><?php echo $_LANG['SELECT_ALL']; ?></a> |
            <a href="javascript:" onclick="$('#search_form input:checkbox').prop('checked', false);$('#from_search label').removeClass('selected');" class="ajaxlink"><?php echo $_LANG['REMOVE_ALL']; ?></a>
        </div>
    </div>
</form>

<script type="text/javascript">
    function toggleInput(id){
        $('#from_search label#'+id).toggleClass('selected');
    }
</script>
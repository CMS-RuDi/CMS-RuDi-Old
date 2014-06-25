<h1 class="con_heading"><?php echo $pagetitle; ?></h1>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <table cellpadding="5">
        <tr>
            <td height="30"><span><?php echo $_LANG['CAT_BOARD']; ?>:</span></td>
            <td>
                <select name="category_id" id="category_id" class="text-input" style="width:407px" onchange="getRubric();">
                    <option value="0">-- <?php echo $_LANG['SELECT_CAT']; ?> --</option>
                    <?php echo $catslist; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td width="180">
                <span><?php echo $_LANG['TITLE']; ?>:</span>
            </td>
            <td height="35">
                <select name="obtype" id="obtype" style="width:160px">
                    <option value="0">-- <?php echo $_LANG['SELECT_CAT']; ?> --</option>
                </select>
                <input name="title" type="text" id="title" class="text-input" style="width:240px" maxlength="250"  value="<?php echo $this->escape($item['title']); ?>"/>
            </td>
        </tr>
        <tr id="from_search">
            <td></td>
            <td height="35">
                <input name="title_fake" type="text" id="title_fake" maxlength="250"  value=""/>
            </td>
        </tr>
        <tr class="proptable">
            <td>
                <span><?php echo $_LANG['CITY']; ?>:</span>
            </td>
            <td height="35" valign="top">
                <?php echo cmsCore::city_input(array('value' => $item['city'], 'name' => 'city', 'width' => '403px')); ?>
            </td>
        </tr>
        <tr id="before_form">
            <td valign="top">
                <span><?php echo $_LANG['TEXT_ADV']; ?>:</span>
            </td>
            <td height="100" valign="top">
                <textarea name="content" class="text-input" style="width:403px" rows="5" id="content"><?php echo $this->escape($item['content']); ?></textarea>
            </td>
        </tr>
        <?php if ($formsdata) { ?>
            <?php foreach($formsdata as $form) { ?>
            <tr class="cat_form">
                <td valign="top">
                    <span><?php echo $form['title']; ?>:</span>
                    <?php if ($form['description']) { ?>
                    	<div style="color:gray"><?php echo $form['description']; ?></div>
                    <?php } ?>
                </td>
                <td valign="top">
                    <?php echo $form['field']; ?>
                </td>
            </tr>
            <?php } ?>
        <?php } ?>
        <?php if ($cfg['photos'] && $cat['is_photos']) { ?>
            <tr>
                <td><span><?php echo $_LANG['PHOTO']; ?>:</span></td>
                <td><input name="Filedata" type="file" id="picture" style="width:407px;" /></td>
            </tr>
        <?php } ?>
        <?php if ($form_do == 'edit') { ?>
            <tr>
                <td height="35"><span><?php echo $_LANG['PERIOD_PUBL']; ?>:</span></td>
                <td height="35"><?php echo $item['pubdays']; ?> <?php echo $_LANG['DAYS']; ?>, <?php echo $_LANG['DAYS_TO']; ?> <?php echo $item['pubdate']; ?>.</td>
            </tr>
        <?php } else if ($cfg['srok']) { ?>
            <tr>
                <td><span><?php echo $_LANG['PERIOD_PUBL']; ?>:</span></td>
                <td>
                    <select name="pubdays" id="pubdays">
                        <option value="5">5</option>
                        <option value="10" selected="selected">10</option>
                        <option value="14">14</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select> <?php echo $_LANG['DAYS']; ?>
                </td>
            </tr>
        <?php } ?>
        <?php if ($cfg['extend'] && $form_do == 'edit' && !$item['published'] && $item['is_overdue']) { ?>
            <?php if ($cfg['srok']) { ?>
                <tr>
                    <td height="35"><span><?php echo $_LANG['ADV_EXTEND']; ?>:</span></td>
                    <td height="35">
                        <select name="pubdays" id="pubdays">
                            <option value="5">5</option>
                            <option value="10" selected="selected">10</option>
                            <option value="14">14</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                        </select>  <?php echo $_LANG['DAYS']; ?>
                    </td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td height="35"><span><?php echo $_LANG['ADV_EXTEND']; ?>:</span></td>
                    <td height="35"><?php echo $_LANG['ADV_EXTEND_SROK']; ?> <?php echo $item['pubdays']; ?> <?php echo $_LANG['DAYS']; ?></td>
                </tr>
            <?php } ?>
        <?php } ?>

        <?php if ($form_do == 'edit' && $item['is_vip']) { ?>
                <tr>
                    <td height="35"><span><?php echo $_LANG['VIP_STATUS']; ?>:</span></td>
                    <td height="35"><?php echo $_LANG['UNTIL']; ?> <?php echo $item['vipdate']; ?></td>
                </tr>
        <?php } ?>

        <?php if ($is_admin || ($is_billing && $cfg['vip_enabled'] && ($form_do == 'add' || ($form_do=='edit' && $cfg['vip_prolong'])))){ ?>
            <tr>
                <td>
                    <span><?php if ($form_do == 'add' || !$item['is_vip']) { ?><?php echo $_LANG['MARK_AS_VIP']; ?><?php } else { ?><?php echo $_LANG['EXTEND_MARK_AS_VIP']; ?><?php } ?>:</span>
                    <div style="color:gray">
                        <?php echo $_LANG['VIP_STATUS_HINT']; ?>
                    </div>
                </td>
                <td valign="top" style="padding-top:5px">
                    <select id="vipdays" name="vipdays" <?php if (!$is_admin) { ?>onchange="calculateVip()"<?php } ?>>
                        <option value="0"><?php if ($form_do=='add' || !$item['is_vip']) { ?><?php echo $_LANG['DO_NOT_DO']; ?><?php } else { ?><?php echo $_LANG['LEAVE_AS_IS']; ?><?php } ?></option>
                        <?php if ($form_do == 'edit' && $item['is_vip']) { ?>
                            <option value="-1"><?php echo $_LANG['DELETE_MARK_AS_VIP']; ?></option>
                        <?php } ?>
                        <?php for ($i=1;$i <= ($cfg['vip_max_days']+1);$i++) { ?>
                            <option value="<?php echo $i; ?>">
                                <?php echo $this->spellcount($i, $_LANG['DAY1'], $_LANG['DAY2'], $_LANG['DAY10']); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <?php if (!$is_admin) { ?>
                        <input type="hidden" id="vip_day_cost" name="vip_day_cost" value="<?php echo $cfg['vip_day_cost']; ?>" />
                        <input type="hidden" id="balance" name="balance" value="<?php echo $balance; ?>" />
                        <div id="vip_cost" style="margin-top:10px;display: none">
                            <?php echo $_LANG['BILLING_COST']; ?>: <span>0</span> <?php echo $_LANG['BILLING_POINT10']; ?>
                        </div>

                        <script type="text/javascript">
                            var LANG_BUY_ERROR = '<?php echo $_LANG['VIP_BUY_ERROR']; ?>';
                            var LANG_ERROR     = '<?php echo $_LANG['ERROR']; ?>';
                            
                                function calculateVip(){
                                    var days = $('#vipdays').val();
                                    var cost = $('#vip_day_cost').val();

                                    if (Number(days)==0){
                                        $('#vip_cost').hide().find('span').html('0');
                                    } else {
                                        var summ = days * cost;
                                        $('#vip_cost').show().find('span').html(summ);
                                    }
                                }

                                function checkBalance(){
                                    var cost    = Number($('#vip_cost span').html());
                                    var balance = Number($('#balance').val());

                                    if (balance < cost){
                                        core.alert(LANG_BUY_ERROR, LANG_ERROR);
                                        return false;
                                    } else {
                                        return true;
                                    }
                                }
                            
                        </script>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <?php if (!$is_user) { ?>
        <tr>
            <td valign="top" class="">
                <div><strong><?php echo $_LANG['SECUR_SPAM']; ?>: </strong></div>
                <div><small><?php echo $_LANG['SECUR_SPAM_TEXT']; ?></small></div>
            </td>
            <td valign="top" class="">{captcha}</td>
        </tr>
        <?php } ?>
        <tr>
            <td height="40" colspan="2" valign="middle">
                <input name="submit" type="submit" id="submit" style="margin-top:10px;font-size:18px" value="<?php echo $_LANG['SAVE_ADV']; ?>" <?php if ($is_admin || ($is_billing && $cfg['vip_enabled'])) { ?>onclick="if(!checkBalance())return false;"<?php } ?> />
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
    function getRubric(){
        $("#category_id").prop("disabled", false);
        $("#obtype").prop("disabled", true);
        var category_id = $('select[name=category_id]').val();
        if(category_id != 0){
            $.post("/components/board/ajax/get_rubric.php", {value: category_id, obtype: '<?php echo $item['obtype']; ?>'}, function(data) {
                $("#obtype").prop("disabled", false);
                $("#obtype").html(data);
            });

            <?php if ($form_do == 'add') { ?>
                $.post("/components/board/ajax/get_form.php", {value: category_id}, function(dataform) {
                    if(dataform!=1){
                        $('.cat_form').remove();
                        $("#before_form").after(dataform);
                    }else{
                        $('.cat_form').remove();
                    }
                });
            <?php } ?>
        } else {
            $("#obtype").html('<option value="0">-- <?php echo $_LANG['SELECT_CAT']; ?> --</option>');
            $("#obtype").prop("disabled", true);
            $('.cat_form').remove();
        }
    }
    $(document).ready(function() {
        $('#title').focus();
        $('#from_search').hide();
        getRubric();
    });
</script>
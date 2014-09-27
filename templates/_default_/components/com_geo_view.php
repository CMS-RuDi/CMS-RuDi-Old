<style type="text/css">
    html #popup_container .body {
        width: 333px;
    }
    #geo_window {
        height: 93px;
        margin: 10px;
    }
    .list select {
        margin: 0 0 10px;
        padding: 3px;
        width: 300px;
    }
</style>

<div id="geo_window">
    <div class="list">
        <select name="countries" onchange="geo.changeParent(this, 'regions')">
            <?php if (!empty($countries)) { foreach ($countries as $key => $val) { ?>
                <option value="<?php echo $key; ?>"<?php if ($key == $country_id) { echo ' selected="selected"'; } ?>><?php echo $this->escape($val); ?></option>
            <?php } } ?>
        </select>
    </div>

    <div class="list" <?php if (!$city_id) { ?>style="display:none"<?php } ?>>
        <select name="regions" onchange="geo.changeParent(this, 'cities')">
            <?php if (!empty($regions)) { foreach ($regions as $key => $val) { ?>
                <option value="<?php echo $key; ?>"<?php if ($key == $region_id) { echo ' selected="selected"'; } ?>><?php echo $this->escape($val); ?></option>
            <?php } } ?>
        </select>
    </div>

    <div class="list" <?php if (!$city_id) { ?>style="display:none"<?php } ?>>
        <select name="cities" onchange="geo.changeCity(this)">
            <?php if (!empty($cities)) { foreach ($cities as $key => $val) { ?>
                <option value="<?php echo $key; ?>"<?php if ($key == $city_id) { echo ' selected="selected"'; } ?>><?php echo $this->escape($val); ?></option>
            <?php } } ?>
        </select>
    </div>
</div>
<?php if ($country_id && !$city_id) { ?>
    <script type="text/javascript">
        $(function(){
            $('#geo_window select[name=countries]').trigger('change');
        });
    </script>
<?php }
<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_city_input($params, &$smarty){
    return cmsCore::city_input($params);
}

?>
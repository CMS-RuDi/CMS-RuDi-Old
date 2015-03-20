<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.5                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

function geo($do=null) {
    // Определяем местоположение пользователя
    cmsCore::c('user')->loadUserGeo();
    
    $do = isset($do) ? $do : cmsCore::getInstance()->do;

    $field_id = cmsCore::request('field_id', 'int', 0);
    $city_id  = cmsCore::strClear(urldecode(cmsCore::request('city_id', 'html', 0))); // можно передавать как id города, так и название

//========================================================================================================================//
    if ($do == 'view'){
        if (!cmsCore::isAjax()) { cmsCore::error404(); }

        $countries = cmsCore::m('geo')->getCountries();
        $regions   = array();
        $cities    = array();

        $region_id  = false;
        
        // определяем страну
        if (isset(cmsCore::c('user')->geo['country'])) {
            $country_id = cmsCore::c('db')->get_field('cms_geo_countries', "alpha2 = '". cmsCore::c('user')->geo['country'] ."'", 'id');
        } else {
            $country_id = false;
        } 

        if (!$city_id && cmsCore::c('user')->city) {
            $city_id = cmsCore::c('user')->city;
        }

        if ($city_id){

            $city_parents = cmsCore::m('geo')->getCityParents($city_id);

            if($city_parents){

                $region_id  = $city_parents['region_id'];
                $country_id = $city_parents['country_id'];

                $regions = cmsCore::m('geo')->getRegions($country_id);
                $cities  = cmsCore::m('geo')->getCities($region_id);

                $city_id = $city_parents['id'];

            } else {
                $city_id = 0;
            }

        }

        cmsPage::initTemplate('components', 'com_geo_view')->
                assign('field_id', $field_id)->
                assign('city_id', $city_id)->
                assign('country_id', $country_id)->
                assign('region_id', $region_id)->
                assign('countries', $countries)->
                assign('regions', $regions)->
                assign('cities', $cities)->
                display();

    }

//========================================================================================================================//
    if ($do == 'get'){

        if (!cmsCore::isAjax()) { cmsCore::error404(); }

        $type      = cmsCore::request('type', 'str', '');
        $parent_id = cmsCore::request('parent_id', 'int', 0);

        if (!in_array($type, array('regions', 'cities'))) { cmsCore::error404(); }
        if (!$parent_id) { cmsCore::error404(); }

        switch ( $type ){

            case 'regions': $items = cmsCore::m('geo')->getRegions( $parent_id );
                            break;

            case 'cities':  $items = cmsCore::m('geo')->getCities( $parent_id );
                            break;

            default: $items = array();

        }

        cmsCore::jsonOutput(array(
           'error' => $items ? false : true,
           'items' => $items
        ));

    }

}
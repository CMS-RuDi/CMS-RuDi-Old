<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.3                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2013                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

function geo($do){

    $inUser = cmsUser::getInstance();
    $model  = new cms_model_geo();

    // Определяем местоположение пользователя
    $inUser->loadUserGeo();

    $do = isset($do) ? $do : cmsCore::getInstance()->do;

    $field_id = cmsCore::request('field_id', 'int', 0);
    $city_id  = cmsCore::strClear(urldecode($_REQUEST['city_id'])); // можно передавать как id города, так и название

//========================================================================================================================//
    if ($do=='view'){

        if (!cmsCore::isAjax()) { cmsCore::error404(); }

        $countries = $model->getCountries();
        $regions   = array();
        $cities    = array();

        $region_id  = false;
        $country_id = false;

        if (!$city_id && $inUser->city){
            $city_id = $inUser->city;
        }

        if ($city_id){

            $city_parents = $model->getCityParents($city_id);

            if($city_parents){

                $region_id  = $city_parents['region_id'];
                $country_id = $city_parents['country_id'];

                $regions = $model->getRegions($country_id);
                $cities  = $model->getCities($region_id);

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
                display('com_geo_view.tpl');

    }

//========================================================================================================================//
    if ($do=='get'){

        if (!cmsCore::isAjax()) { cmsCore::error404(); }

        $type      = cmsCore::request('type', 'str', '');
        $parent_id = cmsCore::request('parent_id', 'int', 0);

        if (!in_array($type, array('regions', 'cities'))) { cmsCore::error404(); }
        if (!$parent_id) { cmsCore::error404(); }

        switch ( $type ){

            case 'regions': $items = $model->getRegions( $parent_id );
                            break;

            case 'cities':  $items = $model->getCities( $parent_id );
                            break;

            default: $items = array();

        }

        cmsCore::jsonOutput(array(
           'error' => $items ? false : true,
           'items' => $items
        ));

    }

}
?>

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

class cms_model_geo {

	public function __construct(){
		cmsCore::loadLanguage('components/geo');
        $this->inDB = cmsDatabase::getInstance();
    }

    public static function getDefaultConfig() {
        return array (
			  'class' => 'geo',
			  'autodetect' => 1
			);
    }

    private function getItems($table) {

        $sql = "SELECT id, name FROM {$table}
                WHERE 1=1 {$this->inDB->where}
                {$this->inDB->order_by}\n";
		$result = $this->inDB->query($sql);
        if (!$this->inDB->num_rows($result)){ return false; }

        while($data = $this->inDB->fetch_assoc($result)){
            $c[$data['id']] = $data['name'];
        }

        return $c;

    }

    public function getCountries(){

        global $_LANG;

        $this->inDB->orderBy('ordering, name');

        return array('0'=>$_LANG['GEO_SELECT_COUNTRY']) + $this->getItems('cms_geo_countries');

    }

    public function getRegions($country_id=false){

        global $_LANG;

        if ($country_id){
            $this->inDB->where("country_id = '{$country_id}'");
        }

        $this->inDB->orderBy('name');

        return array('0'=>$_LANG['GEO_SELECT_REGION']) + $this->getItems('cms_geo_regions');

    }

    public function getCities($region_id=false){

        global $_LANG;

        if ($region_id){
            $this->inDB->where("region_id = '{$region_id}'");
        }

        $this->inDB->orderBy('name');

        return array('0'=>$_LANG['GEO_SELECT_CITY']) + $this->getItems('cms_geo_cities');

    }

    public function getCityParents($city_id){

        // Совместимость
        if(!is_numeric($city_id)){
            $city = $this->getCity($city_id);
            if(!$city){ return false; }
            $city_id = $city['id'];
        }

        $sql = "SELECT i.*, r.name as region_name, c.name as country_name, r.id as region_id, c.id as country_id
                FROM cms_geo_cities i
				INNER JOIN cms_geo_regions r ON r.id = i.region_id
				INNER JOIN cms_geo_countries c ON c.id = r.country_id
                WHERE i.id = '$city_id' LIMIT 1";

        $result = $this->inDB->query($sql);

        if (!$this->inDB->num_rows($result)){ return false; }

        return $this->inDB->fetch_assoc($result);

    }

    public function getCity($id){

        return $this->inDB->get_fields('cms_geo_cities', (is_numeric($id) ? "id = '{$id}'" : "name LIKE '{$id}'"), '*');

    }

}

?>

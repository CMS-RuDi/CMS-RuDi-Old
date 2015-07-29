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

if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

class cms_model_arhive{
    public $config;
    public $year;
    public $month;
    public $day;
    
    public function __construct(){
        $this->config = cmsCore::getInstance()->loadComponentConfig('arhive');

        cmsCore::loadLanguage('components/arhive');
        $this->year  = cmsCore::request('y', 'int', 'all');
        $this->month = sprintf("%02d", cmsCore::request('m', 'int', 'all'));
        $this->day   = sprintf("%02d", cmsCore::request('d', 'int', 'all'));
        $this->setSqlParams();
    }

/* ==================================================================================================== */
    /**
     * Настройки по умолчанию для компонента
     * @return array
     */
    public static function getDefaultConfig() {
        $cfg = array (
            'source' => 'arhive'
        );

        return $cfg;
    }
/* ==================================================================================================== */
    private function setSqlParams() {

        if ($this->config['source'] != 'both'){
            if ($this->config['source']=='arhive'){
                cmsCore::c('db')->where("con.is_arhive = 1");
            } else {
                cmsCore::c('db')->where("con.is_arhive = 0");
            }
        }

        cmsCore::c('db')->where("con.published = 1 AND con.pubdate <= '". date("Y-m-d H:i:s") ."'");
        cmsCore::c('db')->groupBy("DATE_FORMAT(con.pubdate, '%M, %Y')");
        cmsCore::c('db')->orderBy('con.pubdate', 'DESC');
        cmsCore::c('db')->select = "DATE_FORMAT(con.pubdate, '%Y') as year, DATE_FORMAT(con.pubdate, '%m') as month, COUNT( con.id ) as num";

    }
/* ==================================================================================================== */
    public function whereYearIs() {
        if(is_numeric($this->year)){
            cmsCore::c('db')->where("DATE_FORMAT(con.pubdate, '%Y') LIKE '{$this->year}'");
        }
    }
    public function whereMonthIs() {
        if(is_numeric($this->year) && is_numeric($this->month)){
            $date_str = $this->year.'-'.$this->month;
            cmsCore::c('db')->where("DATE_FORMAT(con.pubdate, '%Y-%m') LIKE '{$date_str}'");
        }
    }
    public function whereDayIs() {
        if(is_numeric($this->day) && is_numeric($this->year) && is_numeric($this->month)){
            $date_str = $this->year.'-'.$this->month.'-'.$this->day;
            cmsCore::c('db')->where("DATE_FORMAT(con.pubdate, '%Y-%m-%d') LIKE '{$date_str}'");
        }
    }
    public function whereThisAndNestedCats($cat_id) {
        if(empty($cat_id)){ return false; }
        $rootcat = cmsCore::c('db')->get_fields('cms_category', "id='{$cat_id}'", 'NSLeft, NSRight');
        if(!$rootcat) { return false; }
        cmsCore::c('db')->where("cat.NSLeft >= '{$rootcat['NSLeft']}' AND cat.NSRight <= '{$rootcat['NSRight']}' AND cat.parent_id > 0");
        cmsCore::c('db')->addJoin('INNER JOIN cms_category cat ON cat.id = con.category_id');
    }
    public function setArtticleSql() {

        cmsCore::c('db')->select   = "con.*, DATE_FORMAT(con.pubdate, '%Y') as year, DATE_FORMAT(con.pubdate, '%m') as month, DATE_FORMAT(con.pubdate, '%d') as day, cat.title cat_title, cat.showdesc, cat.seolink as cat_seolink";
        cmsCore::c('db')->group_by = '';
        cmsCore::c('db')->addJoin('INNER JOIN cms_category cat ON cat.id = con.category_id');

    }
/* ==================================================================================================== */

    public function getArhiveContent(){
        $sql = "SELECT ". cmsCore::c('db')->select ."
                FROM cms_content con
				". cmsCore::c('db')->join ."
                WHERE 1=1 ". cmsCore::c('db')->where ."
                ". cmsCore::c('db')->group_by ."
                ". cmsCore::c('db')->order_by ."\n";

        if (cmsCore::c('db')->limit){
            $sql .= "LIMIT ". cmsCore::c('db')->limit;
        }

        $result = cmsCore::c('db')->query($sql);

        cmsCore::c('db')->resetConditions();

        if (!cmsCore::c('db')->num_rows($result)) { return array(); }

        while ($item = cmsCore::c('db')->fetch_assoc($result)){
            if(!isset($item['seolink'])){
                $item['fmonth'] = cmsCore::intMonthToStr($item['month']);
            } else {
                $item['link']     = cmsCore::m('content')->getArticleURL(0, $item['seolink']);
                $item['cat_link'] = cmsCore::m('content')->getCategoryURL(0, $item['cat_seolink']);
                $item['fpubdate'] = cmsCore::dateFormat($item['pubdate']);
            }
            $item['image'] = (file_exists(PATH.'/images/photos/small/article'.$item['id'].'.jpg') ?
                                'article'.$item['id'].'.jpg' : '');

            $content[] = $item;
        }

        return cmsCore::callEvent('GET_ARHIVE', $content);
    }

/* ==================================================================================================== */

}
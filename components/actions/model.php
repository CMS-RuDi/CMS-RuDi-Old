<?php
/******************************************************************************/
//                                                                            //
//                           InstantCMS v1.10.4                               //
//                        http://www.instantcms.ru/                           //
//                                                                            //
//                   written by InstantCMS Team, 2007-2014                    //
//                produced by InstantSoft, (www.instantsoft.ru)               //
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

if(!defined('VALID_CMS')) { die('ACCESS DENIED'); }

class cms_model_actions{
    public  $config = array();

/* ========================================================================== */
/* ========================================================================== */

    public function __construct(){
        $this->config = cmsCore::getInstance()->loadComponentConfig('actions');
        cmsCore::loadClass('actions');
        cmsCore::loadLanguage('components/users');
    }

/* ========================================================================== */
/* ========================================================================== */

    public static function getDefaultConfig() {
        $cfg = array(
            'show_target'=>1,
            'perpage'=>10,
            'perpage_tab'=>15,
            'action_types'=>''
        );

        return $cfg;
    }

/* ==================================================================================================== */
/* ==================================================================================================== */

    public function deleteAction($id){
        cmsActions::removeLogById($id);
        return true;
    }

/* ==================================================================================================== */
/* ==================================================================================================== */

}
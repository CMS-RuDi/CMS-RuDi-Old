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

function mod_category($module_id, $cfg){

	$inDB = cmsDatabase::getInstance();

	cmsCore::loadModel('content');
	$model = new cms_model_content();

	if (!isset($cfg['category_id'])){ $cfg['category_id'] = 0; }
	if (!isset($cfg['show_subcats'])) { $cfg['show_subcats'] = 1; }
	if (!isset($cfg['expand_all'])) { $cfg['expand_all'] = 1; }

	$rootcat = $inDB->getNsCategory('cms_category', $cfg['category_id']);
	if(!$rootcat) { return false; }

	$subcats_list = $model->getSubCats($rootcat['id'], $cfg['show_subcats'], $rootcat['NSLeft'], $rootcat['NSRight']);
	if(!$subcats_list){ return false; }

	$current_seolink = urldecode(cmsCore::request('seolink', 'str', ''));

    cmsPage::initTemplate('modules', 'mod_content_cats')->
            assign('cfg', $cfg)->
            assign('current_seolink', $current_seolink)->
            assign('subcats_list', $subcats_list)->
            display('mod_content_cats.tpl');

	return true;

}
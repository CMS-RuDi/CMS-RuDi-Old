<?php
/******************************************************************************/
//                             CMS RuDi v0.0.8                                //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
/******************************************************************************/
$com_cfg = array(
    array(
        'type' => 'tabs',
        'tabs' => array(
            array(
                'title' => $_LANG['AD_OVERALL'],
                'fields' => array(
                    array(
                        'type' => 'btn_yes_no',
                        'name' => 'is_url_cyrillic',
                        'title' => $_LANG['AD_GENERATE_CYRYLLIC_URL']
                    ),
                    array(
                        'type' => 'btn_yes_no',
                        'name' => 'readdesc',
                        'title' => $_LANG['AD_OUTPUT_ANNOUNCEMENTS']
                    ),
                    array(
                        'type' => 'btn_yes_no',
                        'name' => 'rating',
                        'title' => $_LANG['ARTICLES_RATING']
                    ),
                    array(
                        'type' => 'btn_yes_no',
                        'name' => 'autokeys',
                        'title' => $_LANG['AD_GENERATE_KEY_DESCR']
                    ),
                    array(
                        'type' => 'number',
                        'name' => 'perpage',
                        'title' => $_LANG['AD_NUMBER_PER_PAGE']
                    ),
                    array( 'type' => 'hr' ),
                    array(
                        'type' => 'btn_yes_no',
                        'name' => 'pt_show',
                        'title' => $_LANG['AD_SHOW_CONTENT']
                    ),
                    array(
                        'type' => 'btn_yes_no',
                        'name' => 'pt_disp',
                        'title' => $_LANG['AD_DEPLOY_CONTENT']
                    ),
                    array(
                        'type' => 'btn_yes_no',
                        'name' => 'pt_hide',
                        'title' => $_LANG['AD_HIDE_CONTENT']
                    )
                )
            ),
            array(
                'title' => $_LANG['AD_PHOTO_ART'],
                'fields' => array(
                    array(
                        'type' => 'btn_yes_no',
                        'name' => 'img_users',
                        'title' => $_LANG['AD_ALLOW_USERS_TO'],
                        'description' => $_LANG['AD_ALLOW_USERS_TO_HINT']
                    ),
                    array(
                        'type' => 'number',
                        'name' => 'img_big_w',
                        'title' => $_LANG['AD_PHOTO_BIG']
                    ),
                    array(
                        'type' => 'number',
                        'name' => 'img_small_w',
                        'title' => $_LANG['AD_PHOTO_SMALL']
                    ),
                    array(
                        'type' => 'btn_yes_no',
                        'name' => 'watermark',
                        'title' => $_LANG['AD_ENABLE_WATERMARK'],
                        'description' => $_LANG['AD_WATERMARK_HINT'] .' "<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"'
                    ),
                    array( 'type' => 'hr' ),
                    array(
                        'type' => 'btn_yes_no',
                        'name' => 'img_on',
                        'title' => $_LANG['AD_ALLOW_USERS_TO_MULTI'],
                        'description' => $_LANG['AD_ALLOW_USERS_TO_MULTI_HINT']
                    ),
                    array( 'type' => 'hr' ),
                    array(
                        'type' => 'img_size',
                        'nameX' => 'imgs_big_w',
                        'nameY' => 'imgs_big_h',
                        'title' => $_LANG['AD_PHOTO_BIG']
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'resize_type',
                        'title' => $_LANG['AD_PHOTO_RESIZE_TYPE'],
                        'options' => array(
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_AUTO'], 'value' => 'auto' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_EXACT'], 'value' => 'exact' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_PORTRAIT'], 'value' => 'portrait' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_LANDSCAPE'], 'value' => 'landscape' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_CROP'], 'value' => 'crop' ),
                        )
                    ),
                    array( 'type' => 'hr' ),
                    array(
                        'type' => 'img_size',
                        'nameX' => 'imgs_medium_w',
                        'nameY' => 'imgs_medium_h',
                        'title' => $_LANG['AD_PHOTO_MEDIUM']
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'mresize_type',
                        'title' => $_LANG['AD_PHOTO_RESIZE_TYPE'],
                        'options' => array(
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_AUTO'], 'value' => 'auto' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_EXACT'], 'value' => 'exact' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_PORTRAIT'], 'value' => 'portrait' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_LANDSCAPE'], 'value' => 'landscape' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_CROP'], 'value' => 'crop' ),
                        )
                    ),
                    array( 'type' => 'hr' ),
                    array(
                        'type' => 'img_size',
                        'nameX' => 'imgs_small_w',
                        'nameY' => 'imgs_small_h',
                        'title' => $_LANG['AD_PHOTO_SMALL']
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sresize_type',
                        'title' => $_LANG['AD_PHOTO_RESIZE_TYPE'],
                        'options' => array(
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_AUTO'], 'value' => 'auto' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_EXACT'], 'value' => 'exact' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_PORTRAIT'], 'value' => 'portrait' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_LANDSCAPE'], 'value' => 'landscape' ),
                            array( 'title' => $_LANG['AD_PHOTO_RESIZE_VAL_CROP'], 'value' => 'crop' ),
                        )
                    ),
                    array( 'type' => 'hr' ),
                    array(
                        'type' => 'number',
                        'name' => 'imgs_quality',
                        'title' => $_LANG['AD_IMG_QUALITY']
                    ),
                    array(
                        'type' => 'btn_yes_no',
                        'name' => 'watermark_only_big',
                        'title' => $_LANG['AD_WATERMARK_ONLY_BIG'],
                        'description' => $_LANG['AD_WATERMARK_ONLY_BIG_HINT']
                    )
                )
            ),
            array(
                'title' => 'SEO',
                'fields' => array(
                    array(
                        'type' => 'text',
                        'name' => 'pagetitle',
                        'title' => $_LANG['AD_PAGE_TITLE']
                    ),
                    array(
                        'type' => 'textarea',
                        'name' => 'meta_keys',
                        'title' => $_LANG['KEYWORDS'],
                        'description' => $_LANG['AD_FROM_COMMA']
                    ),
                    array(
                        'type' => 'textarea',
                        'name' => 'meta_desc',
                        'title' => $_LANG['DESCRIPTION'],
                        'description' => $_LANG['AD_LESS_THAN']
                    )
                )
            )
        )
    )
);
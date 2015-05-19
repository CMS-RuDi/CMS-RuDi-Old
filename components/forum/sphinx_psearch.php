<?php
/******************************************************************************/
//                                                                            //
//                             CMS RuDi v0.0.10                               //
//                            http://cmsrudi.ru/                              //
//              Copyright (c) 2014 DS Soft (http://ds-soft.ru/)               //
//                  Данный код защищен авторскими правами                     //
//                                                                            //
/******************************************************************************/

if (!defined('VALID_CMS')) { die('ACCESS DENIED'); }

function sphinx_add_result_forum($items) {
    $inCore = cmsCore::getInstance();

    global $_LANG;
    cmsCore::loadLanguage('components/forum');
    $config = $inCore->loadComponentConfig('forum');
    $search_model = cms_model_search::initModel();
    
    foreach ($items as $id => $item) {
        if (!cmsCore::checkContentAccess($item['attrs']['access_list'])) { continue; }
            
        $pages = ceil($item['attrs']['post_count'] / $config['pp_thread']);

        $result_array = array(
            'link' => '/forum/thread'. $id .'-'. $pages .'.html',
            'place' => $item['attrs']['forum'],
            'placelink' => '/forum/'. $item['attrs']['forum_id'],
            'description' => $search_model->getProposalWithSearchWord($item['attrs']['description']),
            'title' => $item['attrs']['title'],
            'pubdate' => date('Y-m-d H:i:s', $item['attrs']['pubdate'])
        );

        $search_model->addResult($result_array);
    }
    
    // Ищем в тексте постов
    
    $cl = new SphinxClient();

    $cl->SetServer('127.0.0.1', 9312);
    $cl->SetMatchMode(SPH_MATCH_EXTENDED2);
    $cl->SetLimits(0, 100);
    
    $result = $cl->Query($search_model->against, $search_model->config['Sphinx_Search']['prefix'] .'_forum_posts');
            
    if ($result !== false) {
        foreach ($result['matches'] as $id => $item) {
            $pages = ceil($item['attrs']['post_count'] / $config['pp_thread']);
            $post_page = ($pages > 1) ? postPage::getPage($item['attrs']['thread_id'], $id, $config['pp_thread']) : 1;
            
            $result_array = array(
                'link' => '/forum/thread'. $item['attrs']['thread_id'] .'-'. $post_page .'.html#'. $id,
                'place' => $_LANG['FORUM_POST'],
                'placelink' => '/forum/thread'. $item['attrs']['thread_id'] .'-'. $post_page .'.html#'. $id,
                'description' => $search_model->getProposalWithSearchWord($item['attrs']['content_html']),
                'title' => $item['attrs']['thread'],
                'imageurl' => $item['attrs']['fileurl'],
                'pubdate' => date('Y-m-d H:i:s', $item['attrs']['pubdate'])
            );

            $search_model->addResult($result_array);
        }
    }
    
    return;
}

class postPage {
    private static $post_ids = array();

    private function __construct() { }

    public static function getPage($thread_id, $post_id, $perpage) {
        $ids = self::getThreadPostIds($thread_id);

        $post_num = array_search($post_id, $ids);

        return ceil($post_num / $perpage);
    }

    private static function getThreadPostIds($thread_id) {
        if (isset(self::$post_ids[$thread_id])) { return self::$post_ids[$thread_id]; }

        $ids = array();

        $result = cmsCore::c('db')->query("SELECT id FROM cms_forum_posts WHERE thread_id = '". $thread_id ."' ORDER BY pubdate ASC");

        if (!cmsCore::c('db')->num_rows($result)) { return array(); }

        $num = 1;

        while ($data = cmsCore::c('db')->fetch_assoc($result)) {
            $ids[$num] = $data['id'];

            $num++;
        }

        return $ids;
    }
}
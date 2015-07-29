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

class cms_model_content {
    public $config;
    
    public function __construct() {
        $this->config = cmsCore::getInstance()->loadComponentConfig('content');
        cmsCore::loadLib('tags');
        cmsCore::loadLib('karma');
    }

    public static function getDefaultConfig() {
        return array (
            'seolink'         => 'cat',
            'readdesc'        => 0,
            'is_url_cyrillic' => 0,
            'rating'          => 1,
            'perpage'         => 15,
            'pt_show'         => 1,
            'pt_disp'         => 1,
            'pt_hide'         => 1,
            'autokeys'        => 1,
            'img_small_w'     => 100,
            'img_big_w'       => 200,
            'img_sqr'         => 1,
            'img_users'       => 1,
            'hide_root'       => 0,
            'watermark'       => 1,
            'pagetitle'       => '',
            'meta_keys'       => '',
            'meta_desc'       => ''
        );
    }

    public function getCommentTarget($target, $target_id) {
        $result = array();

        switch($target) {
            case 'article': 
                    $article = cmsCore::c('db')->get_fields('cms_content', "id='". $target_id ."'", 'seolink, title');
                    if (!$article) { return false; }
                    $result['link']  = $this->getArticleURL(null, $article['seolink']);
                    $result['title'] = $article['title'];
                break;
        }

        return ($result ? $result : false);
    }

    public function updateRatingHook($target, $item_id, $points) {
        if (!$item_id || abs($points) != 1) { return false; }

        switch($target) {
            case 'content':
                $sql = "UPDATE cms_content SET rating = rating + (". $points .") WHERE id = '". $item_id ."'";
                break;
        }

        cmsCore::c('db')->query($sql);

        return true;
    }

    /**
     * Возвращает подкатегории категории
     * @return array
     */
    public function getSubCats($parent_id, $recurse=false, $left_key=0, $right_key=0) {
        if ($recurse) {
            $where = "NSLeft > ". $left_key ." AND NSRight < ". $right_key;
        } else {
            $where = "parent_id = '". $parent_id ."'";
        }

        $sql = "SELECT * FROM cms_category WHERE ". $where ." AND published = 1 ORDER BY NSLeft";

        $result = cmsCore::c('db')->query($sql);

        if (!cmsCore::c('db')->num_rows($result)) { return false; }

        while ($subcat = cmsCore::c('db')->fetch_assoc($result)) {
            $subcat['content_count'] = $this->getArticleCountFromCat($subcat['NSLeft'], $subcat['NSRight']);
            $subcat['link']          = $this->getCategoryURL(null, $subcat['seolink']);

            $subcats[] = $subcat;
        }

        $subcats = cmsCore::callEvent('GET_CONTENT_SUBCATS', $subcats);

        return $subcats;
    }

    /**
     * Возвращает количество статей в категории и подкатегориях
     * @return int
     */
    public function getArticleCountFromCat($left_key, $right_key) {
        $sql = "SELECT con.id
                    FROM cms_content con
                    INNER JOIN cms_category cat ON cat.id = con.category_id AND cat.NSLeft >= '". $left_key ."' AND cat.NSRight <= '". $right_key ."'
                    WHERE con.published = 1 AND con.is_arhive = 0";

        $result = cmsCore::c('db')->query($sql);

        return cmsCore::c('db')->num_rows($result);
    }

    /**
     * Возвращает дерево категорий
     * @return array
     */
    public function getCatsTree() {
        $sql = "SELECT  cat.id as id,
                        cat.title as title,
                        cat.NSLeft as NSLeft,
                        cat.NSRight as NSRight,
                        cat.NSLevel as NSLevel,
                        cat.seolink as seolink
                FROM cms_category cat
                WHERE cat.NSLevel>0
                ORDER BY cat.NSLeft";

        $result = cmsCore::c('db')->query($sql);

        if (!cmsCore::c('db')->num_rows($result)) { return false; }

        while ($subcat = cmsCore::c('db')->fetch_assoc($result)) {
            $subcats[] = $subcat;
        }

        $subcats = cmsCore::callEvent('GET_CONTENT_CATS_TREE', $subcats);

        return $subcats;
    }

    /**
     * Возвращает категории, доступные для публикования в них
     * @return array
     */
    public function getPublicCats() {
        $inCore = cmsCore::getInstance();

        $nested_sets = $inCore->nestedSetsInit('cms_category');
        $rootid      = cmsCore::c('db')->getNsRootCatId('cms_category');

        $rs_rows = $nested_sets->SelectSubNodes($rootid);

        if ($rs_rows) {
            while ($node = cmsCore::c('db')->fetch_assoc($rs_rows)) {
                if (cmsCore::c('user')->is_admin || (cmsCore::checkUserAccess('afisha_category', $node['id']) &&
                  ($node['is_public'] ||
                  ($node['modgrp_id'] && $node['modgrp_id'] == cmsCore::c('user')->group_id && cmsUser::isUserCan('content/autoadd'))))) {
                    $subcats[] = $node;
                }
            }
        }

        $subcats = cmsCore::callEvent('GET_CONTENT_PUBCATS', $subcats);

        return $subcats;
    }

    /**
     * Условия выборки
     */
    public function whereCatIs($category_id) {
        cmsCore::c('db')->where("con.category_id = '". $category_id ."'");
    }

    public function whereUserIs($user_id) {
        cmsCore::c('db')->where("con.user_id = '". $user_id ."'");
    }
    
    public function whereThisAndNestedCats($left_key, $right_key) {
        cmsCore::c('db')->where("cat.NSLeft >= '". $left_key ."' AND cat.NSRight <= '". $right_key ."' AND cat.parent_id > 0");
    }

    /**
     * Получаем статьи по заданным параметрам
     * @return array
     */
    public function getArticlesList($only_published=true) {
        $today = date("Y-m-d H:i:s");

        if ($only_published) {
            cmsCore::c('db')->where("con.published = 1 AND con.pubdate <= '". $today ."'");
        }

        $sql = "SELECT con.*,
                    con.pubdate as fpubdate,
                    cat.title as cat_title, cat.seolink as cat_seolink,
                    cat.showdesc,
                    u.nickname as author,
                    u.login as user_login
                FROM cms_content con
                INNER JOIN cms_category cat ON cat.id = con.category_id
                LEFT JOIN cms_users u ON u.id = con.user_id
                WHERE con.is_arhive = 0
                ". cmsCore::c('db')->where ."
                ". cmsCore::c('db')->group_by ."
                ". cmsCore::c('db')->order_by. "\n";

        if (cmsCore::c('db')->limit) {
            $sql .= "LIMIT ". cmsCore::c('db')->limit;
        }

        $result = cmsCore::c('db')->query($sql);

        cmsCore::c('db')->resetConditions();

        if (!cmsCore::c('db')->num_rows($result)) { return false; }

        while ($article = cmsCore::c('db')->fetch_assoc($result)) {
            $article['fpubdate'] = cmsCore::dateFormat($article['fpubdate']);
            $article['tagline']  = cmsTagLine('content', $article['id'], true);
            $article['comments'] = cmsCore::getCommentsCount('article', $article['id']);
            $article['link']     = $this->getArticleURL(null, $article['seolink']);
            $article['cat_link'] = $this->getCategoryURL(null, $article['cat_seolink']);
            
            if (file_exists(PATH .'/images/content/medium/'. ceil($article['id']/100) .'/article'. $article['id' ] .'.jpg')) {
                $article['image'] = '/images/content/medium/'. ceil($article['id']/100) .'/article'. $article['id' ] .'.jpg';
                $article['image_small'] = '/images/content/small/'. ceil($article['id']/100) .'/article'. $article['id' ] .'.jpg';
            }
            
            if (!empty($article['images'])) { $article['images'] = json_decode($article['images'], true); }
            
            $articles[] = $article;
        }

        $articles = cmsCore::callEvent('GET_ARTICLES', $articles);

        return $articles;
    }

    /**
     * Возвращает количество статей по заданным параметрам
     * @return int
     */
    public function getArticlesCount($only_published=true) {
        $today = date('Y-m-d H:i:s');

        if ($only_published) {
            cmsCore::c('db')->where("con.published = 1 AND con.pubdate <= '". $today ."'");
        }

        $sql = "SELECT 1
                FROM cms_content con
                    INNER JOIN cms_category cat ON cat.id = con.category_id
                    WHERE con.is_arhive = 0
                    ". cmsCore::c('db')->where ."
                    ". cmsCore::c('db')->group_by ." ";

        $result = cmsCore::c('db')->query($sql);

        return cmsCore::c('db')->num_rows($result);
    }

    /**
     * Переносит просроченые статьи в архив
     * @return bool
     */
    public function moveArticlesToArchive() {
        return cmsCore::c('db')->query("UPDATE cms_content SET is_arhive = 1 WHERE is_end = 1 AND enddate < NOW()");
    }

    /**
     * Получает статью
     * @return array
     */
    public function getArticle($id_or_link)
    {
        if (is_numeric($id_or_link)) {
            $where = "con.id = '". $id_or_link ."'";
        }
        else
        {
            $where = "con.seolink = '". $id_or_link ."'";
        }
        
        $sql = "SELECT  con.*,
                        cat.title cat_title, cat.id cat_id, cat.NSLeft as leftkey, cat.NSRight as rightkey, cat.modgrp_id,
                        cat.showtags as showtags, cat.seolink as cat_seolink, cat.cost, u.nickname as author, u.login as user_login
                        FROM cms_content con
                        INNER JOIN cms_category cat ON cat.id = con.category_id
                        LEFT JOIN cms_users u ON u.id = con.user_id
                        WHERE ". $where ." LIMIT 1";
        $result = cmsCore::c('db')->query($sql);

        if (!cmsCore::c('db')->num_rows($result)) {
            return false;
        }

        $article = cmsCore::c('db')->fetch_assoc($result);
        
        if (file_exists(PATH .'/images/content/medium/'. ceil($article['id']/100) .'/article'. $article['id' ] .'.jpg')) {
            $article['image'] = '/images/content/medium/'. ceil($article['id']/100) .'/article'. $article['id'] .'.jpg';
            $article['image_small'] = '/images/content/small/'. ceil($article['id']/100) .'/article'. $article['id'] .'.jpg';
        }
        
        if (!empty($article['images'])) {
            $article['images'] = json_decode($article['images'], true);
        }
        
        if (!empty($article['categories'])) {
            $article['categories'] = explode(',', $article['categories']);
            foreach ($article['categories'] as $k => $v) {
                if ($v == $article['category_id']) {
                    unset($article['categories'][$k]);
                }
            }
        }
        
        $article['categories_str'] = !empty($article['categories']) ? implode(',', $article['categories']) : '';
        
        $article['link']     = $this->getArticleURL(null, $seolink);
        $article['cat_link'] = $this->getCategoryURL(null, $article['cat_seolink']);

        return $article;
    }

    /**
     * Изменяет порядок статей
     * @return bool
     */
    public function moveItem($item_id, $cat_id, $dir) {
        $sign = $dir>0 ? '+' : '-';

        $current = cmsCore::c('db')->get_field('cms_content', "id=". $item_id, 'ordering');

        if ($current === false) { return false; }

        if ($dir > 0) {
            //движение вверх
            //у элемента следующего за текущим нужно уменьшить порядковый номер
            $sql = "UPDATE cms_content
                    SET ordering = ordering-1
                    WHERE category_id='". $cat_id ."' AND ordering = (". $current ."+1)
                    LIMIT 1";
            cmsCore::c('db')->query($sql);
        }
        if ($dir < 0) {
            //движение вниз
            //у элемента предшествующего текущему нужно увеличить порядковый номер
            $sql = "UPDATE cms_content
                    SET ordering = ordering+1
                    WHERE category_id='". $cat_id ."' AND ordering = (". $current ."-1)
                    LIMIT 1";
            cmsCore::c('db')->query($sql);
        }

        $sql = "UPDATE cms_content
                   SET ordering = ordering ". $sign ." 1
                   WHERE id='". $item_id ."'";
        cmsCore::c('db')->query($sql);

        return true;
    }

    /**
     * Обновляет ссылки на статьи в категории и вложенных в нее
     * Подразумевается, что заголовок категории или поле url изменен заранее
     * @return bool
     */
    public function updateArticlesSeoLink($cat_id) {
        // получаем все статьи категории и вложенных в нее
        $art = $this->getNestedArticles($cat_id);
        if (!$art) { return false; }

        foreach ($art as $a) {
            $seolink = $this->getSeoLink($a);
            cmsCore::c('db')->query("UPDATE cms_content SET seolink='". $seolink ."' WHERE id = '". $a['id'] ."'");
            $this->updateContentCommentsLink($a['id']);
        }

        // Обновляем ссылки меню на статьи
        $this->updateContentMenu();

        return true;
    }

    /**
     * генерирует сеолинк для статьи
     * @param array $article Полный массив данных, включая id
     * @return str
     */
    public function getSeoLink($article) {
        $seolink = '';

        if ($this->config['seolink'] != 'root') {
            $cat = cmsCore::c('db')->getNsCategory('cms_category', $article['category_id']);
            
            $path_list = cmsCore::c('db')->getNsCategoryPath('cms_category', $cat['NSLeft'], $cat['NSRight'], 'id, title, NSLevel, seolink, url');

            if ($path_list) {
                foreach ($path_list as $pcat) {
                    $seolink .= cmsCore::strToURL(($pcat['url'] ? $pcat['url'] : $pcat['title']), $this->config['is_url_cyrillic']) . '/';
                    if ($this->config['seolink'] == 'main_cat') {
                        break;
                    }
                }
            }
        }

        $seolink .= cmsCore::strToURL(($article['url'] ? $article['url'] : $article['title']), $this->config['is_url_cyrillic']);

        if (!empty($article['id'])) {
            $where = ' AND id<>'. $article['id'];
        } else {
            $where = '';
        }

        $is_exists = cmsCore::c('db')->get_field('cms_content', "seolink='". $seolink ."'". $where, 'id');

        if ($is_exists) { $seolink .= '-'. (!empty($article['id']) ? $article['id'] : uniqid()); }

        return $seolink;
    }

    /**
     * Возвращает урл статьи
     * параметр $menuid устаревший, оставлен для совместимости
     * @return str
     */
    public static function getArticleURL($menuid, $seolink, $page = 1)
    {
        if ((is_numeric($page) && $page>1) || is_string($page)) {
            $page_section = '/page-'. $page;
        }
        else
        {
            $page_section = '';
        }

        $url = '/'. $seolink . $page_section .'.html';
        
        if (cmsCore::c('config')->com_without_name_in_url != 'content') {
            $url = '/content'. $url;
        }

        return $url;
    }

    /**
     * Возвращает урл категории
     * параметр $menuid устаревший, оставлен для совместимости
     * @return str
     */
    public static function getCategoryURL($menuid, $seolink, $page = 1, $pagetag = false)
    {
        if (!$pagetag) {
            $page_section = ($page > 1 ? '/page-'. $page : '');
        }
        else
        {
            $page_section = '/page-%page%';
        }

        $url = '/'. $seolink . $page_section;
        
        if (cmsCore::c('config')->com_without_name_in_url != 'content') {
            $url = '/content'. $url;
        }

        return $url;
    }

    /**
     * Удаляет статью
     * @return bool
     */
    public function deleteArticle($id) {
        cmsCore::callEvent('DELETE_ARTICLE', $id);

        cmsCore::c('db')->delete('cms_content', "id='". $id ."'", 1);
        cmsCore::c('db')->delete('cms_tags', "target='content' AND item_id='". $id ."'");
        cmsCore::clearAccess($id, 'material');

        cmsActions::removeObjectLog('add_article', $id);

        @unlink(PATH .'/images/content/medium/'. ceil($id/100) .'/article'. $id .'.jpg');
        @unlink(PATH .'/images/content/small/'. ceil($id/100) .'/article'. $id .'.jpg');
        
        cmsCore::deleteUploadImages($id, '', 'content');
        cmsCore::deleteRatings('content', $id);
        cmsCore::deleteComments('article', $id);

        return true;
    }

    /**
     * Удаляет список статей
     * @param array $id_list
     * @return bool
     */
    public function deleteArticles($id_list) {
        foreach ($id_list as $id) {
            $this->deleteArticle($id);
        }
        return true;
    }

    /**
     * Добавляет статью
     * @param array $article
     * @return int
     */
    public function addArticle($article) {
        $article = cmsCore::callEvent('ADD_ARTICLE', $article);

        if ($article['url']) {
            $article['url'] = cmsCore::strToURL($article['url'], $this->config['is_url_cyrillic']);
        }

        // получаем значение порядка последней статьи
        $last_ordering = (int)cmsCore::c('db')->get_field('cms_content', "category_id = '". $article['category_id'] ."' ORDER BY ordering DESC", 'ordering');
        $article['ordering'] = $last_ordering+1;

        $article['id'] = cmsCore::c('db')->insert('cms_content', $article);

        if ($article['id']) {
            $article['seolink'] = $this->getSeoLink($article);
            cmsCore::c('db')->query("UPDATE cms_content SET seolink='". $article['seolink'] ."' WHERE id = '". $article['id'] ."'");

            cmsInsertTags($article['tags'], 'content', $article['id']);

            cmsCore::callEvent('ADD_ARTICLE_SUCCESS', $article);
            
            if ($article['published']) {
                cmsCore::callEvent('ADD_ARTICLE_DONE', $article);
            }

            cmsCore::setIdUploadImage('', $article['id'], 'content');
            cmsCore::requestUploadImgTitles($article['id'], 'content');
            cmsCore::updateComImages($article['id'], 'content', '', 'cms_content', 'images');
        }

        return $article['id'] ? $article['id'] : false;
    }

    public function uploadArticeImage($id, $delete=false) {
        if ($delete) {
            @unlink(PATH .'/images/content/small/'. ceil($id/100). '/article'. $id .'.jpg');
            @unlink(PATH .'/images/content/medium/'. ceil($id/100). '/article'. $id .'.jpg');
        }

        mkdir(PATH .'/images/content/small/'. ceil($id/100), 0777, true);
        mkdir(PATH .'/images/content/medium/'. ceil($id/100), 0777, true);
        
        cmsCore::c('images')->filename   = 'article'. $id .'.jpg';
        
        cmsCore::c('images')->mwatermark = $this->config['watermark'];
        
        cmsCore::c('images')->big_dir    = PATH .'/images/content/';
        cmsCore::c('images')->medium_dir = 'medium/'. ceil($id/100) .'/';
        cmsCore::c('images')->small_dir  = 'small/'. ceil($id/100) .'/';
        
        cmsCore::c('images')->new_mw     = $this->config['img_big_w'];
        cmsCore::c('images')->new_mh     = $this->config['img_big_w'];
        cmsCore::c('images')->new_sw     = $this->config['img_small_w'];
        cmsCore::c('images')->new_sh     = $this->config['img_small_w'];
        
        cmsCore::c('images')->resize_type = $this->config['img_sqr'] ? 'exact' : 'auto';
        
        cmsCore::c('images')->resize('picture', true);
    }

    /**
     * Обновляет статью
     * @return bool
     */
    public function updateArticle($id, $article, $not_upd_seo = false) {
        $article['id'] = $id;
        
        cmsCore::setIdUploadImage('', $article['id'], 'content');
        cmsCore::requestUploadImgTitles($article['id'], 'content');
        cmsCore::updateComImages($article['id'], 'content', '', 'cms_content', 'images');

        if (!$not_upd_seo) {
            if (!empty($article['url'])) {
                $article['url'] = cmsCore::strToURL($article['url'], $this->config['is_url_cyrillic']);
            }

            $article['seolink'] = $this->getSeoLink($article);
        } else {
            unset($article['seolink']);
            unset($article['url']);
        }

        if (!$article['user_id']) {
            $article['user_id'] = cmsCore::c('user')->id;
        }

        $article = cmsCore::callEvent('UPDATE_ARTICLE', $article);

        cmsCore::c('db')->update('cms_content', $article, $id);

        if (!$not_upd_seo) {
            $this->updateContentCommentsLink($id);
        }

        cmsInsertTags($article['tags'], 'content', $id);

        return true;
    }

    /**
     * Обновляет ссылки меню на категории
     * @return bool
     */
    public function updateCatMenu() {
        return cmsCore::c('db')->query("UPDATE cms_menu m, cms_category cat SET m.link = CONCAT('/', cat.seolink) WHERE m.linkid = cat.id AND m.linktype = 'category'");
    }

    /**
     * Обновляет ссылки меню на статьи
     * @return bool
     */
    public function updateContentMenu() {
        return cmsCore::c('db')->query("UPDATE cms_menu m, cms_content con SET m.link = CONCAT('/', con.seolink, '.html') WHERE m.linkid = con.id AND m.linktype = 'content'");
    }

    /**
     * Обновляет ссылки меню на статьи
     * @return bool
     */
    public function updateContentCommentsLink($article_id) {
        // Обновляем ссылки в комменатриях
        cmsCore::c('db')->query("UPDATE cms_comments c, cms_content a SET
                                   c.target_link = CONCAT('/', a.seolink, '.html')
                                   WHERE a.id = '". $article_id ."' AND c.target = 'article' AND c.target_id = a.id");

        // Обновляем ссылки в action
        $action = cmsActions::getAction('add_comment');

        if ($action) {
            cmsCore::c('db')->query("UPDATE cms_actions_log log, cms_content a SET
                                   log.target_url = CONCAT('/', a.seolink, '.html'), log.object_url = CONCAT('/', a.seolink, '.html#c', log.object_id)
                                   WHERE a.id = '". $article_id ."' AND log.action_id='". $action['id'] ."' AND log.target_id='". $article_id ."'");
        }

        return true;
    }

    /**
     * Возвращает массив связанных статей с категорией
     * @return array
     */
    public function getNestedArticles($category_id) {
        $cat = cmsCore::c('db')->getNsCategory('cms_category', $category_id);

        $sql = "SELECT con.id, con.title, con.seolink, con.url, con.category_id
				FROM cms_content con
				JOIN cms_category cat ON cat.id = con.category_id AND cat.NSLeft >= ". $cat['NSLeft'] ." AND cat.NSRight <= ". $cat['NSRight'];

        $result = cmsCore::c('db')->query($sql);

        if (!cmsCore::c('db')->num_rows($result)) { return false; }

        $articles = array();

        while ($article = cmsCore::c('db')->fetch_assoc($result)) {
            $articles[] = $article;
        }

        return $articles ? $articles : false;
    }

    /**
     * Удаляет категорию
     * @return bool
     */
    public function deleteCategory($id, $is_with_content = false) {
        $articles = $this->getNestedArticles($id);
        $rootid   = cmsCore::c('db')->getNsRootCatId('cms_category');
        if ($articles) {
            foreach ($articles as $article) {
                // удаляем все вложенные статьи
                if ($is_with_content) {
                    $this->deleteArticle($article['id']);
                } else { // или переносим в корень и в архив
                    cmsCore::c('db')->query("UPDATE cms_content SET category_id = '". $rootid ."', is_arhive = 1, seolink = SUBSTRING_INDEX(seolink, '/', -1) WHERE id = '". $article['id'] ."'");
                }
            }
        }

        return cmsCore::c('db')->deleteNS('cms_category', $id);
    }

    /**
     * Возвращает фотографии из привязанного альбома
     * @param str $album
     * @return array
     */
    public function getCatPhotoAlbum($album) {
        if (!$album) { return array(); }

        $album = @unserialize($album);
        if (!$album || !is_array($album) || !@$album['id']) { return array(); }

        $p_a = cmsCore::c('db')->getNsCategory('cms_photo_albums', (int)$album['id']);
        if (!$p_a) { return array(); }

        $p_a['title']   = $album['header'];
        $p_a['maxcols'] = $album['maxcols'];

        cmsCore::c('photo')->whereAlbumIs((int)$album['id']);

        if (!in_array($album['orderby'], array('title','pubdate','rating','hits'))) {
            $album['orderby'] = 'pubdate';
        }
        
        if (!in_array($album['orderto'], array('asc','desc'))) {
            $album['orderto'] = 'desc';
        }
        
        cmsCore::c('db')->orderBy('f.'. $album['orderby'], $album['orderto']);

        cmsCore::c('db')->limit((int)$album['max']);

        $photos = cmsCore::c('photo')->getPhotos();
        if (!$photos) { return array(); }

        return array('album' => $p_a, 'photos' => $photos);
    }
    
    /**
     * Обновляет кеш прикрепленных изображений статьи
     * @param integer $target_id
     */
    public function updateUploadImages($target_id) {
        $item = $this->getArticle($target_id);
        if (!empty($item)) {
            cmsCore::updateComImages($target_id, 'content', '', 'cms_content', 'images');
        }
    }
    
    /**
     * Проверяет имеет ли пользователь право на прикрепление/удаление к статье фотографий
     * @param integer $target_id
     * @param string $target
     * @return boolean
     */
    public function checkAccessAddImage($target_id, $target) {
        if (!cmsUser::isUserCan('content/add')) {
            return false;
        }
            
        if ($target_id > 0) {
            $item = $this->getArticle($target_id);
            
            if (empty($item)) {
                return false;
            }
            
            if (!cmsCore::c('user')->is_admin && ($item['user_id'] != cmsCore::c('user')->id) && !($item['modgrp_id'] == cmsCore::c('user')->group_id)) {
                return false;
            }
        }
        
        return true;
    }
}
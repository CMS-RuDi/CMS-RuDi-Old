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

class cmsPhoto {
    private static $instance;
    
    private $targets = array(
        'tags'=>'photo',
        'rating'=>'photo',
        'comments_photo'=>'photo',
        'comments_album'=>'palbum',
        'actions_photo'=>'add_photo'
    );

// ============================================================================ //
// ============================================================================ //

    private function __construct(){
        cmsCore::loadLib('tags');
        cmsCore::loadLanguage('components/photos');
    }

    private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Устанавливает таргеты
     * @param array $targets
     * @return bool
     */
    public function setTargets($targets=array()) {
        $this->targets = $targets;
        return true;
    }

    public function getTarget($target='') {
        return isset($this->targets[$target]) ? $this->targets[$target] : '';
    }
// ============================================================================ //
// ============================================================================ //

    public function whereAlbumIs($album_id){
        cmsCore::c('db')->where("f.album_id = '$album_id'");
        return;
    }

    public function whereThisAndNestedCats($left_key, $right_key) {
        cmsCore::c('db')->where("a.NSLeft >= $left_key AND a.NSRight <= $right_key AND a.parent_id > 0");
    }

    public function wherePeriodIs($period){
        $today = date("d-m-Y");

        switch ($period){
            case 'today': cmsCore::c('db')->where("DATE_FORMAT(f.pubdate, '%d-%m-%Y')='$today'"); break;
            case 'week':  cmsCore::c('db')->where("DATEDIFF(NOW(), f.pubdate) <= 7"); break;
            case 'month': cmsCore::c('db')->where("DATE_SUB(NOW(), INTERVAL 1 MONTH) < f.pubdate"); break;
            default: return;
        }

        return;
    }

// ============================================================================ //
// ============================================================================ //


// ============================================================================ //
// ============================================================================ //
    /**
     * Добавляет фото
     * @param array $photo
     * @return int
     */
    public function addPhoto($photo){
        $photo_id = cmsCore::c('db')->insert('cms_photo_files', cmsCore::callEvent('ADD_PHOTO', $photo));

        if ($photo['tags']){
            cmsInsertTags($photo['tags'], $this->getTarget('tags'), $photo_id);
        }

        cmsUser::checkAwards($inUser->id);

        return $photo_id;
    }
// ============================================================================ //
// ============================================================================ //
    /**
     * Обновляет данные фото
     * @return bool
     */
    public function updatePhoto($photo, $id){
        cmsCore::c('db')->update('cms_photo_files', cmsCore::callEvent('UPDATE_PHOTO', $photo), $id);

        if ($photo['tags']){
            cmsInsertTags($photo['tags'], $this->getTarget('tags'), $id);
        }

        return true;
    }
// ============================================================================ //
// ============================================================================ //
    /**
     * Получает фото
     * @param int $photo_id
     * @param bool $is_full
     * @return array $photo
     */
    public function getPhoto($photo_id){
        $sql = "SELECT f.*, a.user_id as auser_id, a.title cat_title, a.NSLeft, a.NSRight, a.NSDiffer, a.nav album_nav, a.public, a.showtags a_tags, a.bbcode a_bbcode, a.orderby, a.orderto, u.nickname, u.login, p.gender, p.imageurl
                        FROM cms_photo_files f
                        INNER JOIN cms_photo_albums a ON a.id = f.album_id
                        LEFT JOIN cms_users u ON u.id = f.user_id
                        LEFT JOIN cms_user_profiles p ON p.user_id = u.id
                        WHERE f.id = '$photo_id' LIMIT 1";

        $result = cmsCore::c('db')->query($sql);

        if (!cmsCore::c('db')->num_rows($result)){ return false; }

        $photo = cmsCore::c('db')->fetch_assoc($result);

        $photo['pubdate'] = cmsCore::dateFormat($photo['pubdate']);

        return $photo;
    }
// ============================================================================ //
// ============================================================================ //
    /**
     * Удаляет фото
     * @param array $photo
     * @param obj $inUploadPhoto
     * @return bool
     */
    public function deletePhoto($photo, $inUploadPhoto){
        $photo = cmsCore::callEvent('DELETE_PHOTO', $photo);

        if(!$this->deletePhotoFile($photo['file'], $inUploadPhoto)){ return false; }

        cmsCore::deleteComments($this->getTarget('comments_photo'), $photo['id']);
        cmsCore::deleteRatings($this->getTarget('rating'), $photo['id']);
        cmsClearTags($this->getTarget('tags'), $photo['id']);

        cmsActions::removeObjectLog($this->getTarget('actions_photo'), $photo['id']);

cmsCore::c('db')->query("DELETE FROM cms_photo_files WHERE id = '{$photo['id']}' LIMIT 1");

        return true;
    }

// ============================================================================ //
// ============================================================================ //
    /**
     * Публикует фото
     * @param int $photo_id
     * @return bool
     */
    public function publishPhoto($photo_id){
        cmsCore::callEvent('PUBLISH_PHOTO', $photo_id);

        return cmsCore::c('db')->query("UPDATE cms_photo_files SET published = 1 WHERE id = '$photo_id'");
    }
// ============================================================================ //
// ============================================================================ //
    /**
     * Удаляет фотографии
     * @param array $photo
     * @param obj $inUploadPhoto
     * @return bool
     */
    public function deletePhotos($id_list, $inUploadPhoto){
        foreach($id_list as $key=>$id){
            $this->deletePhoto($id, $inUploadPhoto);
        }

        return true;
    }
// ============================================================================ //
// ============================================================================ //
    /**
     * Удаляет файл фото с папок загрузки
     * @return bool
     */
    public function deletePhotoFile($file='', $inUploadPhoto){
        if (!($file && is_object($inUploadPhoto))) { return false; }

        return $inUploadPhoto->deletePhotoFile($file);
    }

// ============================================================================ //
// ============================================================================ //
    /**
     * Возвращает количество альбомов для заданного $differ
     * @param str $differ
     * @return int
     */
    public function getAlbumsCount($differ){
        return (int)cmsCore::c('db')->rows_count('cms_photo_albums', "NSDiffer = '$differ' AND parent_id > 0 AND published = 1");
    }
// ============================================================================ //
// ============================================================================ //
    /**
     * Возвращает альбомы
     * @return array $albums
     */
    public function getAlbums($parent_id=0, $differ='', $recurse=false){
        if (!$parent_id) {
            $parent_where = 'a.parent_id > 0';
        }

        if ($parent_id){
            if($recurse){
                $parent = cmsCore::c('db')->getNsCategory('cms_photo_albums', $parent_id, $differ);
                $parent_where = "a.NSLeft > {$parent['NSLeft']} AND a.NSRight < '{$parent['NSRight']}'";
            } else {
                $parent_where = "a.parent_id = '$parent_id'";
            }
        }

        $sql  = "SELECT a.id, a.title, a.description, a.pubdate, a.iconurl, a.thumb1,  f.file, IFNULL(COUNT(f.id), 0) as content_count
                        FROM cms_photo_albums a
                        LEFT JOIN cms_photo_files f ON f.album_id = a.id AND f.published = 1
                        WHERE a.NSDiffer='". $differ ."' AND ". $parent_where ." AND a.published = 1
                        GROUP BY a.id
        ". cmsCore::c('db')->order_by ."\n";

        if (cmsCore::c('db')->limit){
            $sql .= "LIMIT ". cmsCore::c('db')->limit;
        }

        $result = cmsCore::c('db')->query($sql);

        $albums = array();

        cmsCore::c('db')->resetConditions();

        if (!cmsCore::c('db')->num_rows($result)){ return false; }

        while ($album = cmsCore::c('db')->fetch_assoc($result)){
            $album['file'] = ($album['iconurl'] && file_exists(PATH.'/images/photos/small/'.$album['iconurl'])) ?
            $album['iconurl'] : ($album['file'] ? $album['file'] : 'no_image.png');
            $album['pubdate'] = cmsCore::dateFormat($album['pubdate']);
            $albums[] = $album;
        }

        return cmsCore::callEvent('GET_ALBUMS', $albums);
    }

// ============================================================================ //
// ============================================================================ //
    /**
     * Удаляет альбом
     * @param int $album_id
     * @param str $differ
     * @param obj $inUploadPhoto
     * @return bool
     */
    public function deleteAlbum($album_id, $differ = '', $inUploadPhoto) {
        $album = cmsCore::c('db')->getNsCategory('cms_photo_albums', $album_id, $differ);
        if(!$album) { return false; }

        cmsCore::callEvent('DELETE_ALBUM', $album_id);

        //устанавливаем нужный альбом и все вложенные
        $this->whereThisAndNestedCats($album['NSLeft'], $album['NSRight']);

        cmsCore::c('db')->addJoin("INNER JOIN cms_photo_albums a ON a.id = f.album_id AND a.NSDiffer = '{$differ}'");

        $photos = $this->getPhotos(true);

        if ($photos){
            foreach($photos as $photo){
                $this->deletePhoto($photo, $inUploadPhoto);
            }
        }

        cmsCore::deleteComments($this->getTarget('comments_album'), $album_id);

        cmsActions::removeTargetLog($this->getTarget('actions_photo'), $album_id);

        return cmsCore::c('db')->deleteNS('cms_photo_albums', $album_id, $differ);
    }
// ============================================================================ //
// ============================================================================ //
    /**
     * Возвращает список options альбомов для заданного $differ
     * @param str $differ
     * @param str $selected
     * @return str
     */
    public function getAlbumsOption($differ, $selected=''){
        return cmsCore::getInstance()->getListItems('cms_photo_albums', $selected, 'id', 'ASC',"parent_id > 0 AND NSDiffer='$differ' AND published = 1");
    }
// ============================================================================ //
// ============================================================================ //
    /**
     * Возвращает массив фотографий по заданным условиям
     * @param bool $show_all
     * @param bool $is_rating
     * @return array $photos
     */
    public function getPhotos($show_all = false, $is_comments_count = false){
        $pub_where = ($show_all ? '1=1' : 'f.published = 1');

        $sql = "SELECT f.* ". cmsCore::c('db')->select ."
                        FROM cms_photo_files f
                        ". cmsCore::c('db')->join ."
                        WHERE ". $pub_where ."
                        ". cmsCore::c('db')->where ."
                        ". cmsCore::c('db')->group_by ."
                        ". cmsCore::c('db')->order_by ."\n";

        if (cmsCore::c('db')->limit){
            $sql .= "LIMIT ". cmsCore::c('db')->limit;
        }

        $result = cmsCore::c('db')->query($sql);

        cmsCore::c('db')->resetConditions();

        if(!cmsCore::c('db')->num_rows($result)){ return false; }

        $photos = array();

        while ($photo = cmsCore::c('db')->fetch_assoc($result)){
            if($is_comments_count){
                $photo['comments'] = cmsCore::getCommentsCount(($photo['owner']=='photos' ? 'photo' : 'club_photo'), $photo['id']);
            }
            $photo['pubdate'] = cmsCore::dateFormat($photo['pubdate'], false, false, false);
            $photos[] = $photo;
        }

        return cmsCore::callEvent('GET_PHOTOS', $photos);
    }
/* ========================================================================== */
/* ========================================================================== */
    /**
     * Возвращает количество фотографий по заданным условиям
     * @param bool $show_all
     * @return int
     */
    public function getPhotosCount($show_all = false){
        //подготовим условия
        $pub_where = ($show_all ? '1=1' : 'f.published = 1');

        $sql = "SELECT 1
                        FROM cms_photo_files f
                        ". cmsCore::c('db')->join ."
                        WHERE ". $pub_where ."
                        ". cmsCore::c('db')->where ."
                        ". cmsCore::c('db')->group_by ."\n";

        $result = cmsCore::c('db')->query($sql);

        return cmsCore::c('db')->num_rows($result);
    }
// ============================================================================ //
// ============================================================================ //

}
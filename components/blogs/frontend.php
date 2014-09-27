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

function blogs(){
    $inCore = cmsCore::getInstance();
    
    cmsCore::c('blog')->owner = 'user';

    global $_LANG;

    define('IS_BILLING', $inCore->isComponentInstalled('billing'));
    if (IS_BILLING) { cmsCore::loadClass('billing'); }

    //Получаем параметры
    $id 	 = cmsCore::request('id', 'int', 0);
    $post_id     = cmsCore::request('post_id', 'int', 0);
    $bloglink    = cmsCore::request('bloglink', 'str', '');
    $seolink     = cmsCore::request('seolink', 'str', '');
    $page        = cmsCore::request('page', 'int', 1);
    $cat_id      = cmsCore::request('cat_id', 'int', 0);
    $ownertype   = cmsCore::request('ownertype', 'str', '');
    $on_moderate = cmsCore::request('on_moderate', 'int', 0);

    $pagetitle = $inCore->getComponentTitle();

    cmsCore::c('page')->addPathway($pagetitle, '/blogs');
    cmsCore::c('page')->setTitle($pagetitle);
    cmsCore::c('page')->setDescription($pagetitle);
    cmsCore::c('page')->addHeadJsLang(array('CONFIG_BLOG','DEL_BLOG','YOU_REALY_DELETE_BLOG','NEW_CAT','RENAME_CAT','YOU_REALY_DELETE_CAT','YOU_REALY_DELETE_POST','NO_PUBLISHED'));

    ///////////////////////// МОЙ БЛОГ /////////////////////////////////////////
    if ($inCore->do == 'my_blog'){
        
        if(!cmsCore::c('user')->id){ cmsCore::error404(); }

	$my_blog = cmsCore::c('blog')->getBlogByUserId(cmsCore::c('user')->id);

        if (!$my_blog) {
            cmsCore::redirect('/blogs/createblog.html');
	} else {
            cmsCore::redirect(cmsCore::m('blogs')->getBlogURL($my_blog['seolink']));
	}

    }
    ///////////////////////// ПОСЛЕДНИЕ ПОСТЫ //////////////////////////////////
    if ($inCore->do=='view'){

	cmsCore::c('page')->addHead('<link rel="alternate" type="application/rss+xml" title="'.$_LANG['RSS_BLOGS'].'" href="'.HOST.'/rss/blogs/all/feed.rss">');

	// кроме админов в списке только с доступом для всех
	if(!cmsCore::c('user')->is_admin){
            cmsCore::c('blog')->whereOnlyPublic();
	}

	// ограничиваем по рейтингу если надо
	if(cmsCore::m('blogs')->config['list_min_rating']){
            cmsCore::c('blog')->ratingGreaterThan(cmsCore::m('blogs')->config['list_min_rating']);
	}

	// всего постов
	$total = cmsCore::c('blog')->getPostsCount(cmsCore::c('user')->is_admin);

        //устанавливаем сортировку
        cmsCore::c('db')->orderBy('p.pubdate', 'DESC');

        cmsCore::c('db')->limitPage($page, cmsCore::m('blogs')->config['perpage']);

	// сами посты
	$posts = cmsCore::c('blog')->getPosts(cmsCore::c('user')->is_admin, cmsCore::m('blogs'));
	if(!$posts && $page > 1){ cmsCore::error404(); }

	cmsPage::initTemplate('components', 'com_blog_view_posts')->
            assign('pagetitle', $pagetitle)->
            assign('ownertype', $ownertype)->
            assign('total', $total)->
            assign('posts', $posts)->
            assign('pagination', cmsPage::getPagebar($total, $page, cmsCore::m('blogs')->config['perpage'], '/blogs/latest-%page%.html'))->
            assign('cfg', cmsCore::m('blogs')->config)->
            display();
    }

    ////////// СОЗДАНИЕ БЛОГА //////////////////////////////////////////////////
    if ($inCore->do=='create'){
        //Проверяем авторизацию
        if (!cmsCore::c('user')->id){ cmsUser::goToLogin();  }

        //Если у пользователя уже есть блог, то выходим
        if (cmsCore::c('blog')->getUserBlogId(cmsCore::c('user')->id)) { cmsCore::redirectBack(); }

        cmsCore::c('page')->addPathway($_LANG['PATH_CREATING_BLOG']);
        cmsCore::c('page')->setTitle($_LANG['CREATE_BLOG']);

        if (IS_BILLING){ cmsBilling::checkBalance('blogs', 'add_blog'); }

        //Показ формы создания блога
        if (!cmsCore::inRequest('goadd')){
            cmsPage::initTemplate('components', 'com_blog_create')->
                assign('is_restrictions', (!cmsCore::c('user')->is_admin && cmsCore::m('blogs')->config['min_karma']))->
                assign('cfg', cmsCore::m('blogs')->config)->
                display();
        }

        //Сам процесс создания блога
        if (cmsCore::inRequest('goadd')){
            $title     = cmsCore::request('title', 'str');
            $allow_who = cmsCore::request('allow_who', 'str', 'all');
            $ownertype = cmsCore::request('ownertype', 'str', 'single');

            //Проверяем название
            if (mb_strlen($title)<5){
                cmsCore::addSessionMessage($_LANG['BLOG_ERR_TITLE'], 'error');
                cmsCore::redirect('/blogs/createblog.html');
            }

            //Проверяем хватает ли кармы, но только если это не админ
            if (cmsCore::m('blogs')->config['min_karma'] && !cmsCore::c('user')->is_admin){
                // если персональный блог
                if ($ownertype=='single' && (cmsCore::c('user')->karma < cmsCore::m('blogs')->config['min_karma_private'])){
                    cmsCore::addSessionMessage($_LANG['BLOG_YOU_NEED'].' <a href="/users/'.cmsCore::c('user')->id.'/karma.html">'.$_LANG['BLOG_KARMS'].'</a> '.$_LANG['FOR_CREATE_PERSON_BLOG'].' &mdash; '.cmsCore::m('blogs')->config['min_karma_private'].', '.$_LANG['BLOG_HEAVING'].' &mdash; '.cmsCore::c('user')->karma, 'error');
                    cmsCore::redirect('/blogs/createblog.html');
                }

                // если коллективный блог
                if ($ownertype=='multi' && (cmsCore::c('user')->karma < cmsCore::m('blogs')->config['min_karma_public'])){
                    cmsCore::addSessionMessage($_LANG['BLOG_YOU_NEED'].' <a href="/users/'.cmsCore::c('user')->id.'/karma.html">'.$_LANG['BLOG_KARMS'].'</a> '.$_LANG['FOR_CREATE_TEAM_BLOG'].' &mdash; '.cmsCore::m('blogs')->config['min_karma_public'].', '.$_LANG['BLOG_HEAVING'].' &mdash; '.cmsCore::c('user')->karma, 'error');
                    cmsCore::redirect('/blogs/createblog.html');
                }
            }

            //Добавляем блог в базу
            $blog_id   = cmsCore::c('blog')->addBlog(array('user_id'=>cmsCore::c('user')->id, 'title'=>$title, 'allow_who'=>$allow_who, 'ownertype'=>$ownertype, 'forall'=>1));
            $blog_link = cmsCore::c('db')->get_field('cms_blogs', "id='{$blog_id}'", 'seolink');
            //регистрируем событие
            cmsActions::log('add_blog', array(
                'object' => $title,
                'object_url' => cmsCore::m('blogs')->getBlogURL($blog_link),
                'object_id' => $blog_id,
                'target' => '',
                'target_url' => '',
                'target_id' => 0,
                'description' => ''
            ));

            if (IS_BILLING){ cmsBilling::process('blogs', 'add_blog'); }

            cmsCore::addSessionMessage($_LANG['BLOG_CREATED_TEXT'], 'info');
            cmsCore::redirect(cmsCore::m('blogs')->getBlogURL($blog_link));
        }
    }
    
    ////////// НАСТРОЙКИ БЛОГА /////////////////////////////////////////////////
    if ($inCore->do=='config'){
        if(!cmsCore::c('user')->id) { cmsCore::error404(); }

        if(!cmsCore::isAjax()) { cmsCore::error404(); }

        // получаем блог
        $blog = cmsCore::c('blog')->getBlog($id);
        if (!$blog) { cmsCore::error404(); }

        //Проверяем является пользователь хозяином блога или админом
        if ($blog['user_id'] != cmsCore::c('user')->id && !cmsCore::c('user')->is_admin ) { cmsCore::halt(); }

        //Если нет запроса на сохранение, показываем форму настроек блога
        if (!cmsCore::inRequest('goadd')){
            //Получаем список авторов блога
            $authors = cmsCore::c('blog')->getBlogAuthors($blog['id']);

            cmsPage::initTemplate('components', 'com_blog_config')->
                assign('blog', $blog)->
                assign('form_action', '/blogs/'.$blog['id'].'/editblog.html')->
                assign('authors_list', cmsUser::getAuthorsList($authors))->
                assign('users_list', cmsUser::getUsersList(false, $authors))->
                assign('is_restrictions', (!cmsCore::c('user')->is_admin && cmsCore::m('blogs')->config['min_karma']))->
                assign('cfg', cmsCore::m('blogs')->config)->
                display();

            cmsCore::jsonOutput(array('error' => false, 'html' => ob_get_clean()));
        }

        //Если пришел запрос на сохранение
        if (cmsCore::inRequest('goadd')){
            //Получаем настройки
            $title     = cmsCore::request('title', 'str');
            $allow_who = cmsCore::request('allow_who', 'str', 'all');
            $ownertype = cmsCore::request('ownertype', 'str', 'single');
            $premod    = cmsCore::request('premod', 'int', 0);
            $forall    = cmsCore::request('forall', 'int', 1);
            $showcats  = cmsCore::request('showcats', 'int', 1);
            $authors   = cmsCore::request('authorslist', 'array_int', array());

            //Проверяем настройки
            if (mb_strlen($title)<5) { $title = $blog['title']; }

            //Проверяем ограничения по карме (для смены типа блога)
            if (cmsCore::m('blogs')->config['min_karma'] && !cmsCore::c('user')->is_admin){
                // если персональный блог
                if ($ownertype=='single' && (cmsCore::c('user')->karma < cmsCore::m('blogs')->config['min_karma_private'])){
                    cmsCore::jsonOutput(array('error' => true, 'text' => $_LANG['BLOG_YOU_NEED'].' <a href="/users/'.cmsCore::c('user')->id.'/karma.html">'.$_LANG['BLOG_KARMS'].'</a> '.$_LANG['FOR_CREATE_PERSON_BLOG'].' &mdash; '.cmsCore::m('blogs')->config['min_karma_private'].', '.$_LANG['BLOG_HEAVING'].' &mdash; '.cmsCore::c('user')->karma));

                }
                
                // если коллективный блог
                if ($ownertype=='multi' && (cmsCore::c('user')->karma < cmsCore::m('blogs')->config['min_karma_public'])){
                    cmsCore::jsonOutput(array('error' => true, 'text' => $_LANG['BLOG_YOU_NEED'].' <a href="/users/'.cmsCore::c('user')->id.'/karma.html">'.$_LANG['BLOG_KARMS'].'</a> '.$_LANG['FOR_CREATE_TEAM_BLOG'].' &mdash; '.cmsCore::m('blogs')->config['min_karma_public'].', '.$_LANG['BLOG_HEAVING'].' &mdash; '.cmsCore::c('user')->karma));
                }
            }

            if(!cmsUser::checkCsrfToken()) { cmsCore::halt(); }

            //сохраняем авторов
            cmsCore::c('blog')->updateBlogAuthors($blog['id'], $authors);

            //сохраняем настройки блога
            $blog['seolink_new'] = cmsCore::c('blog')->updateBlog($blog['id'], array('title'=>$title, 'allow_who'=>$allow_who, 'showcats'=>$showcats, 'ownertype'=>$ownertype, 'premod'=>$premod, 'forall'=>$forall), cmsCore::m('blogs')->config['update_seo_link_blog']);

            $blog['seolink'] = $blog['seolink_new'] ? $blog['seolink_new'] : $blog['seolink'];

            if(stripslashes($title) != $blog['title']){
                // обновляем записи постов
                cmsActions::updateLog('add_post', array('target' => $title, 'target_url' => cmsCore::m('blogs')->getBlogURL($blog['seolink'])), 0, $blog['id']);
                // обновляем запись добавления блога
                cmsActions::updateLog('add_blog', array('object' => $title, 'object_url' => cmsCore::m('blogs')->getBlogURL($blog['seolink'])), $blog['id']);
            }

            cmsCore::jsonOutput(array('error' => false, 'redirect'  => cmsCore::m('blogs')->getBlogURL($blog['seolink'])));
        }
    }
    
    ////////// СПИСОК БЛОГОВ ///////////////////////////////////////////////////
    if ($inCore->do=='view_blogs'){
        // rss в адресной строке
        cmsCore::c('page')->addHead('<link rel="alternate" type="application/rss+xml" title="'.$_LANG['BLOGS'].'" href="'.HOST.'/rss/blogs/all/feed.rss">');
        cmsCore::c('page')->setDescription($_LANG['BLOGS'].' - '.$_LANG['PERSONALS'].', '.$_LANG['COLLECTIVES']);

        // тип блога
        if($ownertype && $ownertype != 'all'){
            cmsCore::c('blog')->whereOwnerTypeIs($ownertype);
        }

        // всего блогов
        $total = cmsCore::c('blog')->getBlogsCount();

        //устанавливаем сортировку
        cmsCore::c('db')->orderBy('b.rating', 'DESC');

        cmsCore::c('db')->limitPage($page, cmsCore::m('blogs')->config['perpage_blog']);

        //Получаем список блогов
        $blogs = cmsCore::c('blog')->getBlogs(cmsCore::m('blogs'));
        if(!$blogs && $page > 1){ cmsCore::error404(); }

        //Генерируем панель со страницами и устанавливаем заголовки страниц и глубиномера
        switch ($ownertype){
            case 'all':
                cmsCore::c('page')->setTitle($_LANG['ALL_BLOGS']);
                cmsCore::c('page')->addPathway($_LANG['ALL_BLOGS']);
                $link = '/blogs/all-%page%.html';
            break;
        
            case 'single':
                cmsCore::c('page')->setTitle($_LANG['PERSONALS']);
                cmsCore::c('page')->addPathway($_LANG['PERSONALS']);
                $link = '/blogs/single-%page%.html';
            break;
        
            case 'multi':
                cmsCore::c('page')->setTitle($_LANG['COLLECTIVES']);
                cmsCore::c('page')->addPathway($_LANG['COLLECTIVES']);
                $link = '/blogs/multi-%page%.html';
            break;
        }

        cmsPage::initTemplate('components', 'com_blog_view_all')->
            assign('cfg', cmsCore::m('blogs')->config)->
            assign('total', $total)->
            assign('ownertype', $ownertype)->
            assign('blogs', $blogs)->
            assign('pagination', cmsPage::getPagebar($total, $page, cmsCore::m('blogs')->config['perpage_blog'], $link))->
            display();
    }
    
    ////////// ПРОСМОТР БЛОГА //////////////////////////////////////////////////
    if ($inCore->do == 'blog'){
        // получаем блог
        $blog = cmsCore::c('blog')->getBlog($bloglink);

        // Совместимость со старыми ссылками на клубные блоги
        // Пробуем клубный блог получить по ссылке
        if (!$blog) {
            $blog_user_id = cmsCore::c('db')->get_field('cms_blogs', "seolink = '$bloglink' AND owner = 'club'", 'user_id');
            if($blog_user_id){
                cmsCore::redirect('/clubs/'.$blog_user_id.'_blog', '301');
            }
        }

        if (!$blog) { cmsCore::error404(); }

        // Права доступа
        $myblog = (cmsCore::c('user')->id && cmsCore::c('user')->id == $blog['user_id']); // автор блога
        $is_writer = cmsCore::c('blog')->isUserBlogWriter($blog, cmsCore::c('user')->id); // может ли пользователь писать в блог

        // Заполняем head страницы
        cmsCore::c('page')->setTitle($blog['title']);
        cmsCore::c('page')->addPathway($blog['title'], cmsCore::m('blogs')->getBlogURL($blog['seolink']));
        cmsCore::c('page')->setDescription($blog['title']);
        // rss в адресной строке
        cmsCore::c('page')->addHead('<link rel="alternate" type="application/rss+xml" title="'.htmlspecialchars(strip_tags($blog['title'])).'" href="'.HOST.'/rss/blogs/'.$blog['id'].'/feed.rss">');
        if($myblog || cmsCore::c('user')->is_admin){
            cmsCore::c('page')->addHeadJS('components/blogs/js/blog.js');
        }

        //Если доступа нет, возвращаемся и выводим сообщение об ошибке
        if (!cmsUser::checkUserContentAccess($blog['allow_who'], $blog['user_id'])){
            cmsCore::addSessionMessage($_LANG['CLOSED_BLOG'].'<br>'.$_LANG['CLOSED_BLOG_TEXT'], 'error');
            cmsCore::redirect('/blogs');
        }

        // Если показываем посты на модерации, если запрашиваем их
        if($on_moderate){
            if(!cmsCore::c('user')->is_admin && !($myblog && $blog['ownertype'] == 'multi' && $blog['premod'])){
                cmsCore::error404();
            }

            cmsCore::c('blog')->whereNotPublished();

            cmsCore::c('page')->setTitle($_LANG['POSTS_ON_MODERATE']);
            cmsCore::c('page')->addPathway($_LANG['POSTS_ON_MODERATE']);

            $blog['title'] .= ' - '.$_LANG['POSTS_ON_MODERATE'];
        }

        //Получаем html-код ссылки на автора с иконкой его пола
        $blog['author'] = cmsUser::getGenderLink($blog['user_id']);

        // посты данного блога
        cmsCore::c('blog')->whereBlogIs($blog['id']);

        // кроме админов автора в списке только с доступом для всех
        if(!cmsCore::c('user')->is_admin && !$myblog && !cmsCore::c('user')->isFriend($blog['user_id'])){
            cmsCore::c('blog')->whereOnlyPublic();
        }

        // если пришла категория
        if($cat_id){
            $all_total = cmsCore::c('blog')->getPostsCount(cmsCore::c('user')->is_admin || $myblog);
            cmsCore::c('blog')->whereCatIs($cat_id);
        }

        // всего постов
        $total = cmsCore::c('blog')->getPostsCount(cmsCore::c('user')->is_admin || $myblog);

        //устанавливаем сортировку
        cmsCore::c('db')->orderBy('p.pubdate', 'DESC');

        cmsCore::c('db')->limitPage($page, cmsCore::m('blogs')->config['perpage']);

        // сами посты
        $posts = cmsCore::c('blog')->getPosts((cmsCore::c('user')->is_admin || $myblog), cmsCore::m('blogs'));
        if(!$posts && $page > 1){ cmsCore::error404(); }

        //Если нужно, получаем список рубрик (категорий) этого блога
        $blogcats = $blog['showcats'] ? cmsCore::c('blog')->getBlogCats($blog['id']) : false;

        //Считаем количество постов, ожидающих модерации
        $on_moderate = (cmsCore::c('user')->is_admin || $myblog) && !$on_moderate ? cmsCore::c('blog')->getModerationCount($blog['id']) : false;

        // админлинки
        $blog['moderate_link'] = cmsCore::m('blogs')->getBlogURL($blog['seolink']).'/moderate.html';
        $blog['blog_link']     = cmsCore::m('blogs')->getBlogURL($blog['seolink']);
        $blog['add_post_link'] = '/blogs/'.$blog['id'].'/newpost'.($cat_id ? $cat_id : '').'.html';

        //Генерируем панель со страницами
        if ($cat_id){
            $pagination = cmsPage::getPagebar($total, $page, cmsCore::m('blogs')->config['perpage'], $blog['blog_link'].'/page-%page%/cat-'.$cat_id);
        } else {
            $pagination = cmsPage::getPagebar($total, $page, cmsCore::m('blogs')->config['perpage'], $blog['blog_link'].'/page-%page%');
        }

        cmsPage::initTemplate('components', 'com_blog_view')->
            assign('myblog', $myblog)->
            assign('is_config', true)->
            assign('is_admin', cmsCore::c('user')->is_admin)->
            assign('is_writer', $is_writer)->
            assign('on_moderate', $on_moderate)->
            assign('cat_id', $cat_id)->
            assign('blogcats', $blogcats)->
            assign('total', $total)->
            assign('all_total', (isset($all_total) ? $all_total : 0))->
            assign('blog', $blog)->assign('posts', $posts)->
            assign('pagination', $pagination)->
            display();
    }

    ////////// НОВЫЙ ПОСТ / РЕДАКТИРОВАНИЕ ПОСТА ///////////////////////////////
    if ($inCore->do == 'newpost' || $inCore->do == 'editpost'){
        if (!cmsCore::c('user')->id){ cmsUser::goToLogin();  }

        // для редактирования сначала получаем пост
        if($inCore->do == 'editpost'){
            $post = cmsCore::c('blog')->getPost($post_id);
            
            if (!$post){ cmsCore::error404(); }
            
            $id = $post['blog_id'];
            
            $post['tags'] = cmsTagLine('blogpost', $post['id'], false);
        }

        // получаем блог
        $blog = cmsCore::c('blog')->getBlog($id);
        if (!$blog) { cmsCore::error404(); }

        //Если доступа нет, возвращаемся и выводим сообщение об ошибке
        if (!cmsUser::checkUserContentAccess($blog['allow_who'], $blog['user_id'])){
            cmsCore::addSessionMessage($_LANG['CLOSED_BLOG'].'<br>'.$_LANG['CLOSED_BLOG_TEXT'], 'error');
            cmsCore::redirect('/blogs');
        }

        // Права доступа
        $myblog = (cmsCore::c('user')->id && cmsCore::c('user')->id == $blog['user_id']); // автор блога
        $is_writer = cmsCore::c('blog')->isUserBlogWriter($blog, cmsCore::c('user')->id); // может ли пользователь писать в блог
            // если не его блог, пользователь не писатель и не админ, вне зависимости от авторства показываем 404
        if (!$myblog && !$is_writer && !cmsCore::c('user')->is_admin ) { cmsCore::error404(); }
        // проверяем является ли пользователь автором, если редактируем пост
        if (($inCore->do == 'editpost') && !cmsCore::c('user')->is_admin && $post['user_id'] != cmsCore::c('user')->id) { cmsCore::error404(); }

        //Если еще не было запроса на сохранение
        if (!cmsCore::inRequest('goadd')){
            cmsCore::c('page')->addPathway($blog['title'], cmsCore::m('blogs')->getBlogURL($blog['seolink']));

            //для нового поста
            if ($inCore->do == 'newpost'){
                if (IS_BILLING){ cmsBilling::checkBalance('blogs', 'add_post'); }

                cmsCore::c('page')->addPathway($_LANG['NEW_POST']);
                cmsCore::c('page')->setTitle($_LANG['NEW_POST']);

                $post = cmsUser::sessionGet('mod');
                if ($post){
                    cmsUser::sessionDel('mod');
                } else {
                    $post['cat_id'] = $cat_id;
                    $post['comments'] = 1;

                }
            }

            //для редактирования поста
            if ($inCore->do=='editpost'){
                cmsCore::c('page')->addPathway($post['title'], cmsCore::m('blogs')->getPostURL($blog['seolink'], $post['seolink']));
                cmsCore::c('page')->addPathway($_LANG['EDIT_POST']);
                cmsCore::c('page')->setTitle($_LANG['EDIT_POST']);
            }

            cmsCore::c('page')->initAutocomplete();
            $autocomplete_js = cmsCore::c('page')->getAutocompleteJS('tagsearch', 'tags');

            //получаем рубрики блога
            $cat_list = cmsCore::getListItems('cms_blog_cats', $post['cat_id'], 'id', 'ASC', "blog_id = '{$blog['id']}'");

            //получаем код панелей bbcode и смайлов
            $bb_toolbar = cmsPage::getBBCodeToolbar('message',cmsCore::m('blogs')->config['img_on'], 'blogs', 'blog_post', $post_id);
            $smilies    = cmsPage::getSmilesPanel('message');

            $inCore->initAutoGrowText('#message');

            //показываем форму
            cmsPage::initTemplate('components', 'com_blog_edit_post')->
                assign('blog', $blog)->
                assign('pagetitle', ($inCore->do=='editpost' ? $_LANG['EDIT_POST'] : $_LANG['NEW_POST']))->
                assign('mod', $post)->
                assign('cat_list', $cat_list)->
                assign('bb_toolbar', $bb_toolbar)->
                assign('smilies', $smilies)->
                assign('is_admin', cmsCore::c('user')->is_admin)->
                assign('myblog', $myblog)->
                assign('user_can_iscomments', cmsUser::isUserCan('comments/iscomments'))->
                assign('autocomplete_js', $autocomplete_js)->
                display();
        }

        //Если есть запрос на сохранение
        if (cmsCore::inRequest('goadd')) {
            $errors = false;

            //Получаем параметры
            $mod['title']    = cmsCore::request('title', 'str');
            $mod['content']  = cmsCore::request('content', 'html');
            $mod['feel']     = cmsCore::request('feel', 'str', '');
            $mod['music']    = cmsCore::request('music', 'str', '');
            $mod['cat_id']   = cmsCore::request('cat_id', 'int');
            $mod['allow_who']= cmsCore::request('allow_who', 'str', $blog['allow_who']);
            $mod['tags']     = cmsCore::request('tags', 'str', '');
            $mod['comments'] = cmsCore::request('comments', 'int', 1);
            $mod['published']= ($myblog || !$blog['premod']) ? 1 : 0;
            $mod['blog_id']  = $blog['id'];

            //Проверяем их
            if (mb_strlen($mod['title'])<2) {  cmsCore::addSessionMessage($_LANG['POST_ERR_TITLE'], 'error'); $errors = true; }
            if (mb_strlen($mod['content'])<5) { cmsCore::addSessionMessage($_LANG['POST_ERR_TEXT'], 'error'); $errors = true; }

            // Если есть ошибки, возвращаемся назад
            if($errors){
                cmsUser::sessionPut('mod', $mod);
                cmsCore::redirectBack();
            }

            //Если нет ошибок
            //добавляем новый пост...
            if ($inCore->do=='newpost'){

                if (IS_BILLING){ cmsBilling::process('blogs', 'add_post'); }

                $mod['pubdate'] = date( 'Y-m-d H:i:s');
                $mod['user_id'] = cmsCore::c('user')->id;

                // добавляем пост, получая его id и seolink
                $added = cmsCore::c('blog')->addPost($mod);
    $mod = array_merge($mod, $added);

                if ($mod['published']) {
                    $mod['seolink'] = cmsCore::m('blogs')->getPostURL($blog['seolink'], $mod['seolink']);
                    
                    if ($blog['allow_who'] != 'nobody' && $mod['allow_who'] != 'nobody') {
                        cmsCore::callEvent('ADD_POST_DONE', $mod);

                        cmsActions::log('add_post', array(
                                'object' => $mod['title'],
                                'object_url' => $mod['seolink'],
                                'object_id' => $mod['id'],
                                'target' => $blog['title'],
                                'target_url' => cmsCore::m('blogs')->getBlogURL($blog['seolink']),
                                'target_id' => $blog['id'],
                                'description' => '',
                                'is_friends_only' => (int)($blog['allow_who'] == 'friends' || $mod['allow_who'] == 'friends')
                        ));
                    }

                    cmsCore::addSessionMessage($_LANG['POST_CREATED'], 'success');

                    cmsCore::redirect($mod['seolink']);
                }

                if (!$mod['published']) {
                    $message = str_replace('%user%', cmsUser::getProfileLink(cmsCore::c('user')->login, cmsCore::c('user')->nickname), $_LANG['MSG_POST_SUBMIT']);
                    $message = str_replace('%post%', '<a href="'.cmsCore::m('blogs')->getPostURL($blog['seolink'], $added['seolink']).'">'.$mod['title'].'</a>', $message);
                    $message = str_replace('%blog%', '<a href="'.cmsCore::m('blogs')->getBlogURL($blog['seolink']).'">'.$blog['title'].'</a>', $message);

                    cmsUser::sendMessage(USER_UPDATER, $blog['user_id'], $message);

                    cmsCore::addSessionMessage($_LANG['POST_PREMODER_TEXT'], 'info');

                    cmsCore::redirect(cmsCore::m('blogs')->getBlogURL($blog['seolink']));
                }
            }

            //...или сохраняем пост после редактирования
            if ($inCore->do=='editpost') {
                if (cmsCore::m('blogs')->config['update_date']){
                    $mod['pubdate'] = date( 'Y-m-d H:i:s');
                }

                $mod['edit_times'] = (int)$post['edit_times']+1;

                $new_post_seolink = cmsCore::c('blog')->updatePost($post['id'], $mod, cmsCore::m('blogs')->config['update_seo_link']);

                $post['seolink'] = is_string($new_post_seolink) ? $new_post_seolink : $post['seolink'];

                cmsActions::updateLog(
                    'add_post',
                    array(
                        'object' => $mod['title'],
                        'pubdate' => cmsCore::m('blogs')->config['update_date'] ? $mod['pubdate'] : $post['pubdate'],
                        'object_url' => cmsCore::m('blogs')->getPostURL($blog['seolink'], $post['seolink'])
                    ),
                    $post['id']
                );

                if (!$mod['published']) {
                    $message = str_replace('%user%', cmsUser::getProfileLink(cmsCore::c('user')->login, cmsCore::c('user')->nickname), $_LANG['MSG_POST_UPDATE']);
                    $message = str_replace('%post%', '<a href="'.cmsCore::m('blogs')->getPostURL($blog['seolink'], $post['seolink']).'">'.$mod['title'].'</a>', $message);
                    $message = str_replace('%blog%', '<a href="'.cmsCore::m('blogs')->getBlogURL($blog['seolink']).'">'.$blog['title'].'</a>', $message);

                    cmsUser::sendMessage(USER_UPDATER, $blog['user_id'], $message);

                    cmsCore::addSessionMessage($_LANG['POST_PREMODER_TEXT'], 'info');
                } else {
                    cmsCore::addSessionMessage($_LANG['POST_UPDATED'], 'success');
                }

                cmsCore::redirect(cmsCore::m('blogs')->getPostURL($blog['seolink'], $post['seolink']));
            }
        }
    }
    
    ////////// НОВАЯ РУБРИКА / РЕДАКТИРОВАНИЕ РУБРИКИ //////////////////////////
    if ($inCore->do == 'newcat' || $inCore->do == 'editcat'){
        if(!cmsCore::c('user')->id) { cmsCore::error404(); }

        if(!cmsCore::isAjax()) { cmsCore::error404(); }

        $cat = array();

        // Для редактирования сначала получаем рубрику
        if ($inCore->do == 'editcat'){
            $cat = cmsCore::c('blog')->getBlogCategory($cat_id);
            if (!$cat) { cmsCore::halt(); }
            $id = $cat['blog_id'];
        }

        // получаем блог
        $blog = cmsCore::c('blog')->getBlog($id);
        if (!$blog) { cmsCore::halt(); }

        //Проверяем является пользователь хозяином блога или админом
        if ($blog['user_id'] != cmsCore::c('user')->id && !cmsCore::c('user')->is_admin ) { cmsCore::halt(); }

        //Если нет запроса на сохранение
        if (!cmsCore::inRequest('goadd')){
            cmsPage::initTemplate('components', 'com_blog_edit_cat')->
                assign('mod', $cat)->
                assign('form_action', ($inCore->do=='newcat' ? '/blogs/'.$blog['id'].'/newcat.html' : '/blogs/editcat'.$cat['id'].'.html'))->
                display();

            cmsCore::jsonOutput(array('error' => false, 'html' => ob_get_clean()));
        }

        //Если есть запрос на сохранение
        if (cmsCore::inRequest('goadd')){
            $new_cat['title']       = cmsCore::request('title', 'str', '');
            $new_cat['description'] = cmsCore::request('description', 'str', '');
            $new_cat['blog_id']     = $blog['id'];
            if (mb_strlen($new_cat['title'])<3) { cmsCore::jsonOutput(array('error' => true, 'text' => $_LANG['CAT_ERR_TITLE'])); }

            if(!cmsUser::checkCsrfToken()) { cmsCore::halt(); }

            //новая рубрика
            if ($inCore->do=='newcat'){
                    $cat['id'] = cmsCore::c('blog')->addBlogCategory($new_cat);
                    cmsCore::addSessionMessage($_LANG['CAT_IS_ADDED'], 'success');
            }
            //редактирование рубрики
            if ($inCore->do=='editcat'){
                    cmsCore::c('blog')->updateBlogCategory($cat['id'], $new_cat);
                    cmsCore::addSessionMessage($_LANG['CAT_IS_UPDATED'], 'success');
            }

            cmsCore::jsonOutput(array('error' => false, 'redirect'  => cmsCore::m('blogs')->getBlogURL($blog['seolink'], 1, $cat['id'])));
        }

    }
    
    ///////////////////////// УДАЛЕНИЕ РУБРИКИ /////////////////////////////////
    if ($inCore->do == 'delcat'){
        if(!cmsCore::c('user')->id) { cmsCore::error404(); }

        if(!cmsCore::isAjax()) { cmsCore::error404(); }

        $cat = cmsCore::c('blog')->getBlogCategory($cat_id);
        if (!$cat) { cmsCore::halt(); }

        $blog = cmsCore::c('blog')->getBlog($cat['blog_id']);
        if (!$blog) { cmsCore::halt(); }

        if ($blog['user_id'] != cmsCore::c('user')->id && !cmsCore::c('user')->is_admin) { cmsCore::halt(); }

        if(!cmsUser::checkCsrfToken()) { cmsCore::halt(); }

        cmsCore::c('blog')->deleteBlogCategory($cat['id']);

        cmsCore::addSessionMessage($_LANG['CAT_IS_DELETED'], 'success');

        cmsCore::jsonOutput(array('error' => false, 'redirect'  => cmsCore::m('blogs')->getBlogURL($blog['seolink'])));
    }
    
    ////////////////////////// ПРОСМОТР ПОСТА //////////////////////////////////
    if($inCore->do == 'post'){
        $post = cmsCore::c('blog')->getPost($seolink);
        if (!$post){ cmsCore::error404(); }

        $blog = cmsCore::c('blog')->getBlog($post['blog_id']);
        // Совместимость со старыми ссылками на клубные посты блога
        if (!$blog) {
            $blog_user_id = cmsCore::c('db')->get_field('cms_blogs', "id = '{$post['blog_id']}' AND owner = 'club'", 'user_id');
            if($blog_user_id){
                cmsCore::redirect('/clubs/'.$blog_user_id.'_'.$post['seolink'].'.html', '301');
            }
        }

        if (!$blog) { cmsCore::error404(); }

        // Проверяем сеолинк блога и делаем редирект если он изменился
        if($bloglink != $blog['seolink']) {
            cmsCore::redirect(cmsCore::m('blogs')->getPostURL($blog['seolink'], $post['seolink']), '301');
        }

        // право просмотра блога
        if (!cmsUser::checkUserContentAccess($blog['allow_who'], $blog['user_id'])){
            cmsCore::addSessionMessage($_LANG['CLOSED_BLOG'].'<br>'.$_LANG['CLOSED_BLOG_TEXT'], 'error');
            cmsCore::redirect('/blogs');
        }

        // право просмотра самого поста
        if (!cmsUser::checkUserContentAccess($post['allow_who'], $post['user_id'])){
            cmsCore::addSessionMessage($_LANG['CLOSED_POST'].'<br>'.$_LANG['CLOSED_POST_TEXT'], 'error');
            cmsCore::redirect(cmsCore::m('blogs')->getBlogURL($blog['seolink']));
        }

        if(cmsCore::c('user')->id){
            cmsCore::c('page')->addHeadJS('components/blogs/js/blog.js');
        }
        cmsCore::c('page')->addPathway($blog['title'], cmsCore::m('blogs')->getBlogURL($blog['seolink']));
        cmsCore::c('page')->setTitle($post['title']);
        cmsCore::c('page')->addPathway($post['title']);
        cmsCore::c('page')->setDescription($post['title']);

        if ($post['cat_id']){
            $cat = cmsCore::c('blog')->getBlogCategory($post['cat_id']);
        }

        $post['tags'] = cmsTagBar('blogpost', $post['id']);

        $is_author = (cmsCore::c('user')->id && cmsCore::c('user')->id == $post['user_id']);

        cmsPage::initTemplate('components', 'com_blog_view_post')->
            assign('post', $post)->
            assign('blog', $blog)->assign('cat', $cat)->
            assign('is_author', $is_author)->
            assign('is_writer', cmsCore::c('blog')->isUserBlogWriter($blog, cmsCore::c('user')->id))->
            assign('myblog', (cmsCore::c('user')->id && cmsCore::c('user')->id == $blog['user_id']))->
            assign('is_admin', cmsCore::c('user')->is_admin)->
            assign('karma_form', cmsKarmaForm('blogpost', $post['id'], $post['rating'], $is_author))->
            assign('navigation', cmsCore::c('blog')->getPostNavigation($post['id'], $blog['id'], cmsCore::m('blogs'), $blog['seolink']))->
            display();

        if($inCore->isComponentInstalled('comments') && $post['comments']){
            cmsCore::includeComments();
            comments('blog', $post['id']);
        }
    }

    ///////////////////////// УДАЛЕНИЕ ПОСТА ///////////////////////////////////
    if ($inCore->do == 'delpost'){
        if(!cmsCore::c('user')->id) { cmsCore::error404(); }

        if(!cmsCore::isAjax()) { cmsCore::error404(); }
        
        $post = cmsCore::c('blog')->getPost($post_id);
        if (!$post){ cmsCore::halt(); }

        $blog = cmsCore::c('blog')->getBlog($post['blog_id']);
        if (!$blog) { cmsCore::halt(); }

        $myblog = (cmsCore::c('user')->id == $blog['user_id']); // автор блога
        $is_writer = cmsCore::c('blog')->isUserBlogWriter($blog, cmsCore::c('user')->id);
        
        // если не его блог, пользователь не писатель и не админ
        if (!$myblog && !$is_writer && !cmsCore::c('user')->is_admin ) { cmsCore::halt(); }
        
        // проверяем является ли пользователь автором
        if (!cmsCore::c('user')->is_admin && !$myblog && $post['user_id'] != cmsCore::c('user')->id) { cmsCore::halt(); }

        if(!cmsUser::checkCsrfToken()) { cmsCore::halt(); }

        cmsCore::c('blog')->deletePost($post['id']);

        if (cmsCore::c('user')->id != $post['user_id']){
            cmsUser::sendMessage(USER_UPDATER, $post['user_id'], $_LANG['YOUR_POST'].' <b>&laquo;'.$post['title'].'&raquo;</b> '.$_LANG['WAS_DELETED_FROM_BLOG'].' <b>&laquo;<a href="'.cmsCore::m('blogs')->getBlogURL($blog['seolink']).'">'.$blog['title'].'</a>&raquo;</b>');
        }

        cmsCore::addSessionMessage($_LANG['POST_IS_DELETED'], 'success');

        cmsCore::jsonOutput(array('error' => false, 'redirect'  => cmsCore::m('blogs')->getBlogURL($blog['seolink'])));
    }
    
    ///////////////////////// ПУБЛИКАЦИЯ ПОСТА /////////////////////////////////
    if ($inCore->do == 'publishpost'){
        if(!cmsCore::c('user')->id) { cmsCore::error404(); }

        if(!cmsCore::isAjax()) { cmsCore::error404(); }

        $post = cmsCore::c('blog')->getPost($post_id);
        if (!$post){ cmsCore::halt(); }

        $blog = cmsCore::c('blog')->getBlog($post['blog_id']);
        if (!$blog) { cmsCore::halt(); }

        // публикуют авторы блога и админы
        if ($blog['user_id'] != cmsCore::c('user')->id && !cmsCore::c('user')->is_admin) { cmsCore::halt(); }

        cmsCore::c('blog')->publishPost($post_id);

        $post['seolink'] = cmsCore::m('blogs')->getPostURL($blog['seolink'], $post['seolink']);

        if ($blog['allow_who'] == 'all' && $post['allow_who'] == 'all') { cmsCore::callEvent('ADD_POST_DONE', $post); }

        if ($blog['allow_who'] != 'nobody' && $post['allow_who'] != 'nobody'){
            cmsActions::log('add_post', array(
                    'object' => $post['title'],
                    'user_id' => $post['user_id'],
                    'object_url' => $post['seolink'],
                    'object_id' => $post['id'],
                    'target' => $blog['title'],
                    'target_url' => cmsCore::m('blogs')->getBlogURL($blog['seolink']),
                    'target_id' => $blog['id'],
                    'description' => '',
                    'is_friends_only' => (int)($blog['allow_who'] == 'friends' || $post['allow_who'] == 'friends')
            ));
        }

        cmsUser::sendMessage(USER_UPDATER, $post['user_id'], $_LANG['YOUR_POST'].' <b>&laquo;<a href="'.$post['seolink'].'">'.$post['title'].'</a>&raquo;</b> '.$_LANG['PUBLISHED_IN_BLOG'].' <b>&laquo;<a href="'.cmsCore::m('blogs')->getBlogURL($blog['seolink']).'">'.$blog['title'].'</a>&raquo;</b>');

        cmsCore::halt('ok');
    }

    ///////////////////////// УДАЛЕНИЕ БЛОГА ///////////////////////////////////
    if ($inCore->do == 'delblog'){
        if(!cmsCore::c('user')->id) { cmsCore::error404(); }

        if(!cmsCore::isAjax()) { cmsCore::error404(); }

        // получаем блог
        $blog = cmsCore::c('blog')->getBlog($id);
        if (!$blog) { cmsCore::error404(); }

        //Проверяем является пользователь хозяином блога или админом
        if ($blog['user_id'] != cmsCore::c('user')->id && !cmsCore::c('user')->is_admin ) { cmsCore::halt(); }

        if(!cmsUser::checkCsrfToken()) { cmsCore::halt(); }

        cmsCore::c('blog')->deleteBlog($blog['id']);

        cmsCore::addSessionMessage($_LANG['BLOG_IS_DELETED'], 'success');

        cmsCore::jsonOutput(array('error' => false, 'redirect'  => '/blogs'));
    }

    ////////// VIEW POPULAR POSTS //////////////////////////////////////////////
    if ($inCore->do=='best'){
        cmsCore::c('page')->setTitle($_LANG['POPULAR_IN_BLOGS']);
        cmsCore::c('page')->addPathway($_LANG['POPULAR_IN_BLOGS']);
        cmsCore::c('page')->setDescription($_LANG['POPULAR_IN_BLOGS']);

        // кроме админов в списке только с доступом для всех
        if(!cmsCore::c('user')->is_admin){
            cmsCore::c('blog')->whereOnlyPublic();
        }

        // ограничиваем по рейтингу если надо
        if(cmsCore::m('blogs')->config['list_min_rating']){
            cmsCore::c('blog')->ratingGreaterThan(cmsCore::m('blogs')->config['list_min_rating']);
        }

        // всего постов
        $total = cmsCore::c('blog')->getPostsCount(cmsCore::c('user')->is_admin);

        //устанавливаем сортировку
        cmsCore::c('db')->orderBy('p.rating', 'DESC');

        cmsCore::c('db')->limitPage($page, cmsCore::m('blogs')->config['perpage']);

        // сами посты
        $posts = cmsCore::c('blog')->getPosts(cmsCore::c('user')->is_admin, cmsCore::m('blogs'));
        if(!$posts && $page > 1){ cmsCore::error404(); }

        cmsPage::initTemplate('components', 'com_blog_view_posts')->
            assign('pagetitle', $_LANG['POPULAR_IN_BLOGS'])->
            assign('total', $total)->
            assign('ownertype', $ownertype)->
            assign('posts', $posts)->
            assign('pagination', cmsPage::getPagebar($total, $page, cmsCore::m('blogs')->config['perpage'], '/blogs/popular-%page%.html'))->
            assign('cfg', cmsCore::m('blogs')->config)->
            display();
    }

}
?>
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

class p_auto_forum extends cmsPlugin {
    public $info = array(
        'plugin'      => 'p_auto_forum',
        'title'       => 'Автофорум',
        'description' => 'Создает тему на форуме для обсуждения статьи',
        'author'      => 'InstantCMS Team',
        'version'     => '1.10'
    );
    
    public $config = array(
        'delete_thread'         => 1,
        'link_thread'           => 1,
        'forum_id'              => 0,
        'no_create_thread_cats' => 0
    );
    
    public $events = array(
        'DELETE_ARTICLE',
        'GET_ARTICLE',
        'ADD_ARTICLE_DONE',
        'UPDATE_ARTICLE'
    );
    
    public function getConfigFields() {
        global $_LANG;
        
        return array(
            array(
                'type' => 'btn_yes_no', 
                'title' => $_LANG['AF_DELETE_THREAD'],
                'name' => 'delete_thread'
            ),
            array(
                'type' => 'btn_yes_no', 
                'title' => $_LANG['AF_LINK_THREAD'],
                'name' => 'link_thread'
            ),
            array(
                'type' => 'ns_list',
                'title' => $_LANG['AF_ADDTREADFORUM_ID'],
                'name' => 'forum_id',
                'table' => 'cms_forums'
            ),
            array(
                'type' => 'ns_list',
                'title' => $_LANG['AF_NOCREATETREAD'],
                'name' => 'no_create_thread_cats[]',
                'table' => 'cms_category',
                'no_padding' => true,
                'multiple' => true
            )
        );
    }

    public function execute($event='', $article=array()) {
        switch ($event) {
            case 'DELETE_ARTICLE':   $this->deleteForum($article); break;
            case 'GET_ARTICLE':      $article = $this->getForumLink($article); break;
            case 'ADD_ARTICLE_DONE': $this->createForum($article); break;
            case 'UPDATE_ARTICLE':   $this->updateLastForumPost($article); break;
        }

        return $article;
    }

    private function updateLastForumPost($article) {
        // получаем полную статью
        $article = cmsCore::c('db')->get_fields('cms_content', "id = '". $article['id'] ."'", '*');

        $post = cmsCore::c('db')->get_fields('cms_forum_threads t, cms_forum_posts p',
                                                       "t.id = p.thread_id AND t.rel_to='content' AND t.rel_id= '". $article['id'] ."'", 'p.id', 'p.pubdate ASC');

        if ($post) {
            cmsCore::m('forum')->updatePost(
                array(
                    'content' => $this->getBbtexPost($article),
                    'content_html' => $this->getHtmlPost($article)
                ),
                $post['id']
            );
        }

        return true;
    }

    private function deleteForum($article_id) {
        if (!$this->config['delete_thread']) { return; }

        $thread = cmsCore::c('db')->get_fields('cms_forum_threads t
                                           INNER JOIN cms_forums f ON f.id = t.forum_id',
                                           "rel_to='content' AND rel_id= '". $article_id ."'",
                                           't.*, f.NSLeft, f.NSRight');

        if ($thread) {
            cmsCore::m('forum')->deleteThread($thread['id']);
            cmsCore::m('forum')->updateForumCache($thread['NSLeft'], $thread['NSRight'], true);
        }

        return true;
    }

    private function getForumLink($article) {
        global $_LANG;

        if (!$this->config['link_thread']) { return $article; }

        $forum_thread_id = cmsCore::c('db')->get_field('cms_forum_threads', "rel_to='content' AND rel_id='". $article['id'] ."'", 'id');

        if ($forum_thread_id) {
            $article['content'] .= '<div class="con_forum_link"><a href="/forum/thread'. $forum_thread_id .'.html">'. $_LANG['DISCUSS_ON_FORUM'] .'</a></div>';
        }

        return $article;
    }

    private function createForum($article) {
        $forum_id = (int)$this->config['forum_id'];

        if (!$forum_id) { return false; }

        if (!$this->checkCatForAdd($article['category_id'])) { return false; }

        // если для статьи есть уже тема, выходим
        $forum_thread_id = cmsCore::c('db')->get_field('cms_forum_threads', "rel_to='content' AND rel_id='". $article['id'] ."'", 'id');
        if ($forum_thread_id) { return false; }

        $post_html = $this->getHtmlPost($article);
        $post      = $this->getBbtexPost($article);

        // проверяем есть ли такой форум
        if (!cmsCore::m('forum')->getForum($forum_id)) { return false; } 

        $threadlastid = cmsCore::m('forum')->addThread(array(
            'forum_id' => $forum_id,
            'user_id' => $article['user_id'],
            'title' => cmsCore::c('db')->escape_string($article['title']),
            'description' => '',
            'is_hidden' => '0',
            'rel_to' => 'content',
            'hits' => 0,
            'pubdate' => date("Y-m-d H:i:s"),
            'rel_id' => $article['id']
        ));

        cmsCore::m('forum')->addPost(array(
            'thread_id' => $threadlastid,
            'user_id' => $article['user_id'],
            'content' => $post,
            'content_html' => $post_html,
            'pubdate' => date("Y-m-d H:i:s"),
            'editdate' => date("Y-m-d H:i:s")
        ));

        $forum = cmsCore::c('db')->get_fields('cms_forums', "id='". $forum_id ."'", '*');

        cmsCore::m('forum')->updateThreadPostCount($threadlastid);

        cmsUser::checkAwards($article['user_id']);

        cmsCore::m('forum')->updateForumCache($forum['NSLeft'], $forum['NSRight'], true);

        cmsActions::log(
            'add_thread',
            array(
                'object' => $article['title'],
                'user_id' => $article['user_id'],
                'object_url' => '/forum/thread'. $threadlastid .'.html',
                'object_id' => $threadlastid,
                'target' => cmsCore::c('db')->escape_string($forum['title']),
                'target_url' => '/forum/'. $forum_id,
                'target_id' => $forum_id,
                'description' => strip_tags($post_html)
            )
        );

        return true;
    }

    private function checkCatForAdd($cat_id) {
        if (!$cat_id) { return false; }

        if (empty($this->config['no_create_thread_cats'])) { return true; }

        return !(in_array($cat_id, $this->config['no_create_thread_cats']));
    }

    private function getHtmlPost($article) {
        global $_LANG;
        return cmsCore::c('db')->escape_string(sprintf($_LANG['AF_LANG_TEXT_HTML'], '<a href="'. HOST .'/'. $article['seolink'] .'.html">'. $article['title'] .'</a>'));
    }
    
    private function getBbtexPost($article) {
        global $_LANG;
        return cmsCore::c('db')->escape_string(sprintf($_LANG['AF_LANG_TEXT_HTML'], '[url='. HOST .'/'. $article['seolink'] .'.html]'. $article['title'] .'[/url]'));
    }
}
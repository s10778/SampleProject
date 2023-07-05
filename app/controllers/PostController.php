<?php

require_once(Env::CONTROLLER_PATH . 'AppController.php');

/**
 * ログインのコントローラ
 *
 * @version 1.0.0
 */
class PostController extends AppController
{
    /**
     * 新規作成画面のアクション
     *
     * @return void
     */
    public function create()
    {
        $this->viewFile = 'post/index.php';
    }
}

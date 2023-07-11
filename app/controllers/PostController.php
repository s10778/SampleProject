<?php

require_once(Env::CONTROLLER_PATH . 'AppController.php');
require_once(Env::MODEL_PATH . 'PostModel.php');
require_once(Env::MODEL_PATH . 'PostMemberModel.php');
require_once(Env::MODEL_PATH . 'PostCategoryModel.php');
require_once(Env::MODEL_PATH . 'PostCategoryIdModel.php');

/**
 * ログインのコントローラ
 *
 * @version 1.0.0
 */
class PostController extends AppController
{
    /**
	 * ポストのモデル
	 *
	 * @var PostModel
	 */
	private $postModel;

    /**
	 * ポストメンバーのモデル
	 *
	 * @var PostMemberModel
	 */
	private $postMemberModel;

    /**
	 * ポストカテゴリーのモデル
	 *
	 * @var PostCategoryModel
	 */
	private $postCategoryModel;

    /**
	 * ポストカテゴリーのモデル
	 *
	 * @var PostCategoryIdModel
	 */
	private $postCategoryIdModel;



    public function __construct()
	{
		$this->layoutFile = 'layouts/layout.php';
        $this->postModel = new PostModel();
        $this->postMemberModel = new PostMemberModel();
        $this->postCategoryModel = new PostCategoryModel();
        $this->postCategoryIdModel = new PostCategoryIdModel();
	}

    /**
     * 一覧表示画面のアクション
     *
     * @return void
     */
    public function index($id)
    {
		$data['posts'] = $this->postModel->findAllPost();
        $result = [];
        foreach ($data['posts'] as $entry) {
            $post_id = $entry['post_id'];

            if (!isset($result[$post_id])) {
                // copy all the data except category_name
                $result[$post_id] = $entry;
                unset($result[$post_id]['category_name']);
                $result[$post_id]['category_name'] = [];
            }

            // add category_name to the array
            $result[$post_id]['category_name'][] = $entry['category_name'];
        }

        $this->data['posts'] = array_values($result);
        $this->viewFile = 'post/index.php';
    }

    public function indexDetail($id)
    {
        $data = $this->postMemberModel->exportMember($id);
        $filename = 'output.csv';
        // データを一行にまとめる
        $outputData = [];
        foreach ($data as $line) {
            $outputData[] = $line['member_id'];
        }
        CsvExport::export($outputData, $filename);
    }



    /**
     * 新規作成画面のアクション
     *
     * @return void
     */
    public function create()
    {
        $_SESSION['token'] = Util::generateCsrfToken();
        $this->data['posts_categories'] = $this->postCategoryModel->findAllCategory();
        $this->viewFile = 'post/create.php';
    }

    /**
	 * データベースに保存
	 *
	 * @return void
	 */
	public function store()
	{
		if ($this->checkCSRFToken() === false) {
			//CSRFのチェックで無効な時、暫定エラー対応
			echo '無効なアクセスです';
            var_dump($_SESSION['token']);
            var_dump($_POST['token']);
			exit;
		}

		$params = array(
			'sent_date' => $_POST['sent_date'],
			'title' => $_POST['title'],
			'body' => $_POST['body'],
			'sender' => $_POST['sender'],
			'attachment' => $_POST['attachment'],
		);

        $members = [];

        //　CSVファイルでメンバーidが送られてきた時の処理
        if ($_POST['sender'] == 'CSV'){
            $csvMembers = CsvImport::import();
            foreach ($csvMembers[0] as $member) {
                $members[] = $member;
            }
        }

        // 直接メンバーidが入力されてきた時の処理
        if ($_POST['sender'] == '会員個別') {
            for ($i = 1; $i <= 10; $i++) {
                if (!empty($_POST['member'.$i])) {
                    $members[] = $_POST['member'.$i];
                }
            }
        }

        $categories = [];
        for ($i = 1; $i <= 7; $i++) {
            if (!empty($_POST['category'.$i])) {
                $categories[] = $_POST['category'.$i];
            }
        }

		try {
            // トランザクション開始
            $this->postModel->beginTransaction();
			$this->postModel->insert($params);
            // postテーブルの最後のidを取得
			$lastPostId = $this->postModel->lastInsertId();
            $this->postMemberModel->insertMember($members, $lastPostId);
            $this->postCategoryIdModel->insertCategory($categories, $lastPostId);
            // トランザクション終了
            $this->postModel->commit();
			header('Location: ./create');
			exit;
		} catch (PDOException $e) {
            // ロールバックの処理
            $this->postModel->rollBack();
			//エラーメッセージを追加
			$_SESSION['post_add_error'] = 'データ追加エラー';
			//暫定エラー対応
			Logger::getInstance()->error('post insert error:' . $e->getMessage());
		}
	}


    public function edit($id)
	{
		$_SESSION['token'] = Util::generateCsrfToken();

		$this->data['posts'] = $this->postModel->findEditPost($id);
        $this->data['posts_categories'] = $this->postCategoryModel->findAllCategory();
        $this->data['app_posts_categories'] = $this->postCategoryIdModel->findAllCategoryId();
		$this->viewFile = 'post/edit.php';
	}


    /**
	 * 編集画面用アクション
	 *
	 * @return void
	 */
	public function update($id)
	{
		if ($this->checkCSRFToken() == false) {
			//CSRFのチェックで無効な時、暫定エラー対応
			echo '無効なアクセスです';
			exit;
		}

		$params = array(
			'sent_date' => $_POST['sent_date'],
			'title' => $_POST['title'],
			'body' => $_POST['body'],
			'sender' => $_POST['sender'],
			'attachment' => $_POST['attachment'],
		);

        $members = [];

        //　CSVファイルでメンバーidが送られてきた時の処理
        if ($_POST['sender'] == 'CSV'){
            $csvMembers = CsvImport::import();
            foreach ($csvMembers[0] as $member) {
                $members[] = $member;
            }
        }

        // 直接メンバーidが入力されてきた時の処理
        if ($_POST['sender'] == '会員個別') {
            for ($i = 1; $i <= 10; $i++) {
                if (!empty($_POST['member'.$i])) {
                    $members[] = $_POST['member'.$i];
                }
            }
        }

        $categories = [];
        for ($i = 1; $i <= 7; $i++) {
            if (!empty($_POST['category'.$i])) {
                $categories[] = $_POST['category'.$i];
            }
        }

		try {
            // トランザクション開始
            $this->postModel->beginTransaction();
			$this->postModel->update($id, $params);

            $this->postMemberModel->deleteMember($id);
            $this->postMemberModel->insertMember($members, $id);
            $this->postCategoryIdModel->deleteCategory($id);
            $this->postCategoryIdModel->insertCategory($categories, $id);
            // トランザクション終了
            $this->postModel->commit();
			header('Location: /post/index');
			exit;
		} catch (PDOException $e) {
            // ロールバックの処理
            $this->postModel->rollBack();
			//エラーメッセージを追加
			$_SESSION['post_edit_error'] = 'データ追加エラー';
			//暫定エラー対応
			Logger::getInstance()->error('post edit error:' . $e->getMessage());
		}

		$this->edit($id);
	}


    public function delete($id)
    {
        if ($this->checkCSRFToken() == false) {
            //CSRFのチェックで無効な時、暫定エラー対応
            echo '無効なアクセスです';
            exit;
        }

        try {
            $this->postMemberModel->deleteMember($id);
            $this->postCategoryIdModel->deleteCategory($id);
            $this->postModel->deletePost($id);
        } catch (PDOException $e) {
            //エラーメッセージを追加
            $_SESSION['post_delete_error'] = 'データ削除エラー';
            //暫定エラー対応
            Logger::getInstance()->error('post delete error:' . $e->getMessage());
        }

        header('Location: /post/index');
        exit;
    }


}

<?php

require_once(Env::CONTROLLER_PATH . 'AppController.php');
require_once(Env::MODEL_PATH . 'BlogModel.php');
require_once(Env::MODEL_PATH . 'CategoryModel.php');
require_once(Env::MODEL_PATH . 'PostModel.php');
require_once(Env::MODEL_PATH . 'PostMemberModel.php');

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
	 * ブログのモデル
	 *
	 * @var [type]
	 */
	private $blogModel;

    /**
	 * カテゴリーのモデル
	 *
	 * @var [type]
	 */
	private $categoryModel;

    public function __construct()
	{
		$this->layoutFile = 'layouts/layout.php';
		$this->blogModel = new BlogModel();
		$this->categoryModel = new CategoryModel();
        $this->postModel = new PostModel();
        $this->postMemberModel = new PostMemberModel();
	}
    /**
     * 新規作成画面のアクション
     *
     * @return void
     */
    public function create()
    {
        $this->viewFile = 'post/index.php';
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

        for ($i = 1; $i <= 10; $i++) {
            if (!empty($_POST['member'.$i])) {
                $members[] = $_POST['member'.$i];
            }
        }

        if(isset($_FILES)) {
            $csv_file = $_FILES['member_id']['tmp_name']; // アップロードされたCSVファイルの一時的な保存場所

            // CSVファイルを読み込む
            if (($handle = fopen($csv_file, "r")) !== false) {
                while (($data = fgetcsv($handle)) !== false) {
                    // CSVの各行から値を取得して配列に追加
                    if (!empty($data)) {
                        $csvMembers[] = $data;
                    }
                }
                fclose($handle); // ファイルを閉じる
            }
        }
        foreach($csvMembers[0] as $member) {
            $members[] = $member;
        }


		try {
            // トランザクション開始
			// $this->postModel->insert($params);
            // postテーブルの最後のidを取得
            $post_id = $this->postMemberModel->getPost();
            //$post_idには ['post_id'] => 1,[0] => 1　配列が2つできてしまうため、下記コードを記載。
            $lastPostId = $post_id['post_id'];
            $this->postMemberModel->insertMember($members, $lastPostId);
            // トランザクション終了
			header('Location: ./create');
			exit;
		} catch (PDOException $e) {
            // ロールバックの処理

			//エラーメッセージを追加
			$_SESSION['post_add_error'] = 'データ追加エラー';
			//暫定エラー対応
			Logger::getInstance()->error('post insert error:' . $e->getMessage());
		}
	}

}

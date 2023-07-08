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
     * 一覧表示画面のアクション
     *
     * @return void
     */
    public function index()
    {
        $order = 'ORDER BY app_posts.updated_at DESC';
		$this->data['posts'] = $this->postModel->findAllPost($order);

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

		try {
            // トランザクション開始
            $this->postModel->beginTransaction();
			$this->postModel->insert($params);
            // postテーブルの最後のidを取得
			$lastPostId = $this->postModel->lastInsertId();
            $this->postMemberModel->insertMember($members, $lastPostId);
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
}

<?php

require_once(Env::CONTROLLER_PATH . 'AppController.php');
require_once(Env::MODEL_PATH . 'BlogModel.php');
require_once(Env::MODEL_PATH . 'CategoryModel.php');

/**
 * ブログのコントローラ
 * 
 * @author Yuji Seki
 * @version 1.0.0 
 */
class BlogController extends AppController
{

	/**
	 * ブログのモデル
	 *
	 * @var BlogModel
	 */
	private $blogModel;


	/**
	 * カテゴリーのモデル
	 *
	 * @var [type]
	 */
	private $categoryModel;

	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		$this->layoutFile = 'layouts/layout.php';
		$this->blogModel = new BlogModel();
		$this->categoryModel = new CategoryModel();
	}

	/**
	 * 一覧表示画面のアクション
	 *
	 * @return void
	 */
	public function index()
	{
		$order = 'ORDER BY blogs.category_id ASC, blogs.updated DESC';
		$this->data['blogs'] = $this->blogModel->findAllBlog();

		$this->viewFile = 'blog/index.php';
	}

	/**
	 * 一覧画面用アクション
	 *
	 * @return void
	 */
	public function admin_index()
	{

		$this->data['blogs'] = $this->blogModel->findAllBlog();

		$this->viewFile = 'blog/admin_index.php';
	}


	/**
	 * 追加画面用アクション
	 *
	 * @return void
	 */
	public function admin_add_view()
	{
		//追加エラー時の文言
		if (!empty($_SESSION['blog_add_error'])) {
			$this->data['blog_add_error'] = $_SESSION['blog_add_error'];
			//一度表示したら消すため、このタイミングで消去
			unset($_SESSION['blog_add_error']);
		}

		$this->data['categories'] = $this->categoryModel->find();

		$_SESSION['token'] = Util::generateCsrfToken();

		$this->viewFile = 'blog/admin_add.php';
	}


	/**
	 * 追加処理
	 *
	 * @return void
	 */
	public function admin_add()
	{

		if ($this->checkCSRFToken() === false) {
			//CSRFのチェックで無効な時、暫定エラー対応
			echo '無効なアクセスです';
			exit;
		}

		$params = array(
			'title' => $_POST['title'],
			'category_id' => $_POST['category_id'],
			'body' => $_POST['body'],
		);

		if( $this->validate( $params ) ){
			try {
				$this->blogModel->insert($params);

				header('Location: ./admin_index');
				exit;
			} catch (PDOException $e) {
				//エラーメッセージを追加
				$_SESSION['blog_add_error'] = 'データ追加エラー';
				//暫定エラー対応
				Logger::getInstance()->error('blog insert error:' . $e->getMessage());
			}
		}

		$this->admin_add_view();
	}


	/**
	 * 編集画面用アクション
	 *
	 * @return void
	 */
	public function admin_edit_view($id)
	{
		$_SESSION['token'] = Util::generateCsrfToken();

		$this->data['blog'] = $this->blogModel->findById($id);

		$this->data['categories'] = $this->categoryModel->find();

		$this->viewFile = 'blog/admin_edit.php';
	}

	/**
	 * 編集画面用アクション
	 *
	 * @return void
	 */
	public function admin_edit($id)
	{

		if ($this->checkCSRFToken() == false) {
			//CSRFのチェックで無効な時、暫定エラー対応
			echo '無効なアクセスです';
			exit;
		}

		$params = array(
			'title' => $_POST['title'],
			'category_id' => $_POST['category_id'],
			'body' => $_POST['body'],
		);

		if( $this->validate( $params ) ){
			try {
				$this->blogModel->update($id, $params);

				header('Location: /blog/admin_index');
				exit;
			} catch (PDOException $e) {
				//エラーメッセージを追加
				$_SESSION['blog_add_error'] = 'データ編集エラー';
				//暫定エラー対応
				Logger::getInstance()->error('blog update error:' . $e->getMessage());

			}
		}

		$this->admin_edit_view($id);
	}

	/**
	 * 削除画面用アクション
	 *
	 * @return void
	 */
	public function admin_delete($id)
	{

		if ($this->checkCSRFToken() == false) {
			//CSRFのチェックで無効な時、暫定エラー対応
			echo '無効なアクセスです';
			exit;
		}

		try {
			$this->blogModel->delete($id);
		} catch (PDOException $e) {
			//エラーメッセージを追加
			$_SESSION['blog_delete_error'] = 'データ削除エラー';
			//暫定エラー対応
			Logger::getInstance()->error('blog update error:' . $e->getMessage());
		}

		header('Location: /blog/admin_index');
		exit;
	}


	/**
	 * バリデーションの実行
	 *
	 * @return boolean
	 */
	private function validate($params){
		$ret = $this->blogModel->validate($params);
		$this->validateErrors = $this->blogModel->validateErrors;

		return $ret;
	}
}

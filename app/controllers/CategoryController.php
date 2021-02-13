<?php


require_once(Env::CONTROLLER_PATH . 'AppController.php');
require_once(Env::MODEL_PATH . 'CategoryModel.php');

/**
 * カテゴリーのコントローラ
 * 
 * @author Yuji Seki
 * @version 1.0.0 
 */
class CategoryController extends AppController
{

	/**
	 * カテゴリーのモデル
	 *
	 * @var CategoryModel
	 */
	private $categoryModel;

	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		$this->layoutFile = 'layouts/layout.php';
		$this->categoryModel = new CategoryModel();
	}


	/**
	 * 一覧画面用アクション
	 *
	 * @return void
	 */
	public function admin_index()
	{

		$this->data['categories'] = $this->categoryModel->find();

		$this->viewFile = 'category/admin_index.php';
	}


	/**
	 * 追加画面用アクション
	 *
	 * @return void
	 */
	public function admin_add_view()
	{
		//追加エラー時の文言
		if (!empty($_SESSION['category_add_error'])) {
			$this->data['category_add_error'] = $_SESSION['category_add_error'];
			//一度表示したら消すため、このタイミングで消去
			unset($_SESSION['category_add_error']);
		}

		$_SESSION['token'] = Util::generateCsrfToken();

		$this->viewFile = 'category/admin_add.php';
	}


	/**
	 * 追加処理処理用アクション
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
			'category_name' => $_POST['category_name'],
		);


		if( $this->validate( $params ) ){
			try {
				$this->categoryModel->insert($params);

				header('Location: ./admin_index');
				exit;
			} catch (PDOException $e) {
				//エラーメッセージを追加
				$_SESSION['category_add_error'] = 'データ追加エラー';
				//暫定エラー対応
				Logger::getInstance()->error('category insert error:' . $e->getMessage());
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

		$this->data['category'] = $this->categoryModel->findById($id);

		$this->viewFile = 'category/admin_edit.php';
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
			'category_name' => $_POST['category_name'],
		);

		if( $this->validate( $params ) ){
			try {
				$this->categoryModel->update($id, $params);

				header('Location: /category/admin_index');
				exit;
				
			} catch (PDOException $e) {
				//エラーメッセージを追加
				$_SESSION['category_add_error'] = 'データ編集エラー';
				//暫定エラー対応
				Logger::getInstance()->error('category update error:' . $e->getMessage());
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
			$this->categoryModel->delete($id);
		} catch (PDOException $e) {
			//エラーメッセージを追加
			$_SESSION['category_delete_error'] = 'データ削除エラー';
			//暫定エラー対応
			Logger::getInstance()->error('category update error:' . $e->getMessage());

		}

		header('Location: /category/admin_index');
		exit;
	}


	/**
	 * バリデーションの実行
	 *
	 * @return boolean
	 */
	private function validate($params){
		$ret = $this->categoryModel->validate($params);
		$this->validateErrors = $this->categoryModel->validateErrors;

		return $ret;
	}
}

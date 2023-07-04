<?php

require_once(Env::CONTROLLER_PATH . 'AppController.php');

/**
 * ブログのコントローラ
 *
 * @author Yuji Seki
 * @version 1.0.0
 */
class LoginController extends AppController
{
	public function __construct()
	{

	}

	/**
	 * 一覧表示画面のアクション
	 *
	 * @return void
	 */
	public function index()
	{
		$this->viewFile = 'login/index.php';
	}

	public function authenticate()
	{
		$login_id = $_POST['login_id'];
		$password = $_POST['password'];

		if ($login_id === Config::LOGIN_ID && $password === Config::PASSWORD) {
			$_SESSION['login_id'] = $_POST['login_id'];
			header("Location: /");
			exit;
		} else {
			$this->data['error'] = 'ログインエラー';
		}

		$this->viewFile = 'login/index.php';
	}
}

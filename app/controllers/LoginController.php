<?php

require_once(Env::CONTROLLER_PATH . 'AppController.php');

/**
 * ログインのコントローラ
 *
 * @version 1.0.0
 */
class LoginController extends AppController
{
	/**
	 * ログイン画面のアクション
	 *
	 * @return void
	 */
	public function index()
	{
		$this->viewFile = 'login/index.php';
	}

	/**
	 * ログイン認証処理
	 *
	 * @return void
	 */
	public function authenticate()
	{
		$loginId = $_POST['login_id'];
		$password = $_POST['password'];

		if ($loginId === Env::LOGIN_ID && $password === Env::PASSWORD) {
			$_SESSION['login_id'] = $_POST['login_id'];
			header("Location: /");
			exit;
		} else {
			$this->data['error'] = 'ログインエラー';
		}

		$this->viewFile = 'login/index.php';
	}
}

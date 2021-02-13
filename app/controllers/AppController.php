<?php

/**
 * コントローラーの基底クラス
 * 
 * @author Yuji Seki
 * @version 1.0.0 
 */
class AppController extends Controller
{


    /**
	 * トークン情報が一致しているかをチェック
	 *
	 * @return void
	 */
	protected function checkCSRFToken()
	{	
		if (!empty($_SESSION['token']) && $_SESSION['token'] === $_POST['token']) {
			return true;
		} else {
			return false;
		}
	}

    
	
}

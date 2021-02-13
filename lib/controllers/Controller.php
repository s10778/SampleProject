<?php


/**
 * コントローラーの基底クラス
 * 
 * @author Yuji Seki
 * @version 1.0.0
 */
class Controller
{

	/**
	 * Viewに表示するデータ
	 *
	 * @var string
	 */
	protected $data;


	/**
	 * Viewのファイルパス
	 * 
	 * @var string
	 */
	protected $viewFile = '';

	/**
	 * Layoutのファイルパス
	 *
	 * @var string
	 */
	protected $layoutFile = '';


	/**
	 * バリデーションエラー
	 *
	 * @var array
	 */
	protected $validateErrors = array();

	/**
	 * viewに表示するデータの取得
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * viewに表示するバリデーションエラーの取得
	 *
	 * @return array
	 */
	public function getValidateErrors()
	{
		return $this->validateErrors;
	}


	/**
	 * レイアウトファイルを取得
	 * 
	 * @return string
	 */
	public function getLayout()
	{
		return $this->layoutFile;
	}


	/**
	 * viewのファイルを取得
	 *
	 * @return string
	 */
	public function getView(){
		return $this->viewFile;
	}
}

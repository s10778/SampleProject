<?php


/**
 * 設定クラス
 *
 * @author Yuji Seki
 * @version 1.0.0
 */
class Config
{

	/**
	 * セッション名の設定
	 */
	CONST SESSION_NAME="SESSION_APP1";

	//---------------------------　ログ設定　-----------------------

	/**
	 * ログファイル出力フラグ true=出力あり/false=なし
	 */
	const IS_LOGFILE = true;

	/**
	 * ログレベル 0=ERROR/1=WARN/2=INFO/3=DEBUG
	 */
	const LOG_LEVEL = 3;



	/**
	 * ログファイル名
	 */
	const LOGFILE_NAME = 'app';

	/**
	 * ログファイル最大サイズ（Byte）
	 */
	const LOGFILE_MAXSIZE = 10485760;

	/**
	 * ログ保存期間（日）
	 */
	const LOGFILE_PERIOD = 30;

	const LOGIN_ID = 'admin';

	const PASSWORD = 'password123';
}

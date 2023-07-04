<?php

/**
 * 環境設定クラス
 *
 * ローカル、テスト、本番の環境毎の差がある設定を記述するファイル
 *
 * @author Yuji Seki
 * @version 1.0.0
 */
class Env
{

	/**
	 * libフォルダへのパス
	 */
	const LIB_PATH =  __DIR__ . '/../../lib/';

    /**
     * appフォルダへのパス
     */
    const APP_PATH =  __DIR__ . '/../../app/';


    /**
     * コントローラのパス
     */
    const CONTROLLER_PATH = self::APP_PATH . 'controllers/';

    /**
     * モデルのパス
     */
    const MODEL_PATH = self::APP_PATH . 'models/';

    /**
     * Viewのパス
     */
    const VIEW_PATH = self::APP_PATH . 'views/';

    /**
     * 読み込むフォルダ
     */
    const LOAD_DIR =  array(
        Env::LIB_PATH,
        Env::APP_PATH
    );

    /**
     * DBのデータソース
     */
    const DB_DATASOURCE = 'mysql';


    /**
     * DBのホスト
     */
    const DB_HOST = 'sample-db';

    /**
     * データベースのport
     */
    const DB_PORT = '3306';

    /**
     * データベースの名前
     */
    const DB_NAME = 'sample_project';

    /**
     * DBのユーザー名
     */
    const DB_USERNAME = 'sample_project';

    /**
     * DBのパスワード
     */
    const DB_PASSWORD = 'sample_project';

    /**
	 * ログファイル出力ディレクトリ
	 */
	const LOGDIR_PATH = __DIR__ . '/../../logs/';

}

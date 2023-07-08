<?php

/**
 *
 *
 *
 *
 *
 * @author Yuji Seki
 * @version 1.0.0
 */

require_once( __DIR__ .  '/../app/config/Env.php');
require_once( __DIR__ .  '/../app/config/Config.php');

require_once(Env::LIB_PATH . 'controllers/Controller.php');
require_once(Env::LIB_PATH . 'models/Model.php');
require_once(Env::LIB_PATH . 'Logger.php');
require_once(Env::LIB_PATH . 'Util.php');
require_once(Env::LIB_PATH . 'Routes.php');
require_once(Env::LIB_PATH . 'ValidateUtil.php');
require_once(Env::LIB_PATH . 'CsvImport.php');
require_once(Env::LIB_PATH . 'CsvExport.php');

//mbstring の日本語設定
mb_language("japanese");
mb_internal_encoding("UTF-8");

//タイムゾーンの設定
date_default_timezone_set('Asia/Tokyo');

session_name(Config::SESSION_NAME);
session_start();

//Routing設定
Routes::getInstance()->addRoute( '/' , 'BlogController' , 'index' );
Routes::getInstance()->addRoute( '/category/admin_index' , 'CategoryController' , 'admin_index' );
Routes::getInstance()->addRoute( '/category/admin_add' , 'CategoryController' , 'admin_add' );
Routes::getInstance()->addRoute( '/category/admin_add_view' , 'CategoryController' , 'admin_add_view' );
Routes::getInstance()->addRoute( '/category/admin_edit' , 'CategoryController' , 'admin_edit' , 'id' );
Routes::getInstance()->addRoute( '/category/admin_edit_view' , 'CategoryController' , 'admin_edit_view', 'id' );
Routes::getInstance()->addRoute( '/category/admin_delete' , 'CategoryController' , 'admin_delete' , 'id' );
Routes::getInstance()->addRoute( '/blog/admin_index' , 'BlogController' , 'admin_index' );
Routes::getInstance()->addRoute( '/blog/admin_add' , 'BlogController' , 'admin_add' );
Routes::getInstance()->addRoute( '/blog/admin_add_view' , 'BlogController' , 'admin_add_view' );
Routes::getInstance()->addRoute( '/blog/admin_edit' , 'BlogController' , 'admin_edit', 'id' );
Routes::getInstance()->addRoute( '/blog/admin_edit_view' , 'BlogController' , 'admin_edit_view', 'id' );
Routes::getInstance()->addRoute( '/blog/admin_delete' , 'BlogController' , 'admin_delete', 'id');
Routes::getInstance()->addRoute( '/login' , 'LoginController', 'index');
Routes::getInstance()->addRoute( '/login/authenticate' , 'LoginController', 'authenticate');
Routes::getInstance()->addRoute( '/post/create' , 'PostController', 'create');
Routes::getInstance()->addRoute( '/post/store' , 'PostController', 'store');
Routes::getInstance()->addRoute( '/post/index' , 'PostController', 'index');
Routes::getInstance()->addRoute( '/post/indexDetail' , 'PostController', 'indexDetail');

if (isset($_GET)) $_GET = Util::sanitize($_GET); //NULLバイト除去　ヌルバイト攻撃対策
if (isset($_POST)) $_POST = Util::sanitize($_POST); //NULLバイト除去　ヌルバイト攻撃対策


//ドキュメントルート＋リクエストのURI現在のパスを置き換えて、リクエストされたURLを取得（階層が異なる箇所に配置されても動作させるため。）
$page = str_replace(__DIR__,'',str_replace('//','/',$_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']));
//URLの値は、.htaccessにて書き換えを実施 /test は page=testになるよう設定
$controllerName = Routes::getInstance()->getController($page);

$actionName = Routes::getInstance()->getAction($page);
$param =  Routes::getInstance()->getParam($page);


Logger::getInstance()->debug( 'path:' . $page );
Logger::getInstance()->debug( 'controller:' . $controllerName );
Logger::getInstance()->debug( 'action:' .$actionName );
Logger::getInstance()->debug( 'param:' .$param );

if( !empty( $controllerName ) && !empty($actionName) ){

    //コントローラーの読み込みと、アクションの実行
    require_once( ENV::CONTROLLER_PATH. $controllerName . '.php' );

    try{
        $beforeMethod = new ReflectionMethod( $controllerName , 'beforeAction');
    }catch(Exception $e){
        //メソッドが存在しない場合
        $beforeMethod = null;
    }

    $reflectionMethod = new ReflectionMethod( $controllerName , $actionName);

    try{
        $afterMethod = new ReflectionMethod( $controllerName , 'afterAction');
    }catch(Exception $e){
        //メソッドが存在しない場合
        $afterMethod = null;
    }

    $controller = new $controllerName();

    //アクション実行前のメソッドの呼び出し
    if(!empty($beforeMethod)){
        $beforeMethod->invoke($controller);
    }
    //アクションの実行
    $reflectionMethod ->invoke( $controller , $param );
    //アクション実行後のメソッドの呼び出し
    if(!empty($afterMethod)){
        $afterMethod->invoke($controller);
    }

}else{
    //404ページを表示
    header("HTTP/1.0 404 Not Found");
    exit;
}


//Viewファイル内で、アクセスするためのデータの設定
$data = $controller -> getData();
$validateErrors = $controller -> getValidateErrors();
// テンプレート読み込み ob_startを使用することで、Viewファイルの内容を変数に格納。
$view = $controller->getView();
if(!empty($view)) {
    ob_start();
    include(Env::VIEW_PATH . $controller->getView());
    $contents = ob_get_contents();
    ob_end_clean();

    $layout = $controller->getLayout();
    if(!empty($layout)) {
        include(Env::VIEW_PATH . $controller->getLayout());
    }else{
        echo $contents;
    }
}

<?php

/**
 * ルーティング設定用クラス
 * 
 * @author Yuji Seki
 * @version 1.0.0
 */
class Routes
{


    /**
     * instance
     *
     * @var Route
     */
    private static Routes $instance;
	
    /**
     * ルーティングの指定
     * 
     * @var array
     * 
     */
    private array $routes = array();


    /**
     * インスタンスの取得
     *
     * @return Routes
     */
    public function getInstance():Routes{

        return self::$instance ?? self::$instance = new self();
        
    }

    /**
     * Singletonのクラスのため、privateに指定。
     */
    private function __construct(){

    }
	

    /**
     * ルーティングのパスを追加する
     *
     * @param string $routePath ルーティングのパス
     * @param string $controllerName コントローラ名
     * @param string $actionName アクション名
     * @return void
     */
    public function addRoute( string $routePath , string $controllerName , string $actionName  ):void{
        $this->routes[$routePath] = array('controller' => $controllerName , 'action'=> $actionName );
    }

    /**
     * ルートのコントローラー名を取得
     *
     * @param string $path
     * @return string コントローラー名。存在しない場合はfalse
     */
    public function getController( $path ):string {

        if( array_key_exists( $path , $this->routes )){
            return $this->routes[$path]['controller'];
        }

        $controller = '';
        foreach( $this->routes as $routePath => $value ){
            //前方一致の場合、最後に現れた前方一致の条件を有効に。
            if( strpos(  $path , $routePath ) === 0 ){
                $controller = $value['controller'];
            }
        }
        return $controller;
    }


    /**
     * ルートのアクション名を取得
     *
     * @param string $path　パス
     * @return string アクション名。存在しない場合はfalse
     */
    public function getAction( string $path ):string {

        
        if( array_key_exists( $path , $this->routes )){
            return $this->routes[$path]['action'];
        }


        $action = '';
        foreach( $this->routes as $routePath => $value ){
            //前方一致の場合、最後に現れた前方一致の条件を有効に。
            if( strpos(  $path , $routePath ) === 0 ){
                $action = $value['action'];
            }
        }

        return $action;
    }


    /**
     * URLのパラメータを取得
     *
     * @param string $path パス
     * @return string パラメータ
     */
    public function getParam( string $path ):string {

        $param = '';
        $rPath = '';
        foreach( $this->routes as $routePath => $value ){
            //前方一致の場合、最後に現れた前方一致の条件を有効に。
            if( strpos(  $path , $routePath ) === 0 ){
                //ルートのパス
                $rPath = $routePath;
            }
        }

        //アクセスされたパスから、ルートのパスを除外
        $replacePath = str_replace( $rPath, '' , $path );

        //スラッシュを除外して、最後の値をパラメータとして取得
        $pathes = explode( '/', $replacePath);

        $retData = '';
        foreach( $pathes as $key => $value){
            if( !empty( $value ) ){
                $retData = $value;
            }
        } 

        return $retData;
    }

}

<?php
//错误处理
class Model_Error {


    /** 
     * 页面及错误(Exception)
     * 
     * @param Exception $ex 
     * @param mixed $message
     *
     */
    public static function request_error(Exception $ex=null, $message=null){
        $code = 'error';

        if( !is_null($ex) ){
            $message = $ex->getMessage();
            if( false!==stripos($message, 'Unable to find a route to match the URI')||false!==stripos($message,'not found on this server') ){
                $code = 404;
            } 
            
            // Log the error
        //    if( $GLOBALS['__mogujie']['log_errors'] ){
          //      Kohana::$log->add(Kohana_Log::ERROR, var_export($ex,true) );
          //  }
        }
        
        if( empty($message) ){
            $message = 'unkown error';
        }
        
        View::bind_global('message', $message);
        View::bind_global('exception', $ex);
        $msg = $message.'-'.$ex;
        View::bind_global('msg', $msg);
        
        $uri = request_uri();
        crond_log("uri:{$uri}; code:{$code}; msg:{$msg}; referer:{$_SERVER['HTTP_REFERER']}", 'request_error.log');
        
        switch($code){
            case 404:
                //header("Status: 404 Not Found"); //header("HTTP/1.0 404 Not Found");
                echo Request::factory("error/404")->execute();
                break;

            default:
                echo Request::factory("error/index")->execute();
                break;
        }
        exit; 
    }//END func page_error


}
//END class

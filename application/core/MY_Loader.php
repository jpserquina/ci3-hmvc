<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

    public function __construct(){
        if(strpos($this->uri->segment(2),'backend_') === false){}
        else{
            show_error('Direct access to backend method is not allowed. Follow "backend/{method_name and remove the "backend_" in method name}"');
            exit;
        }
    }

    static $add_data = array();
    static $LOGS = array();
    public function view($view, $vars = array(), $return = FALSE){
        self::$add_data = array_merge($vars, is_array(self::$add_data) ? self::$add_data : array());
        list($path, $_view) = Modules::find($view, $this->_module, 'views/');

        if ($path != FALSE)
        {
            $this->_ci_view_paths = array($path => TRUE) + $this->_ci_view_paths;
            $view = $_view;
        }

        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array(self::$add_data), '_ci_return' => $return));
    }

    public static function DEBUG(){
        error_reporting(-1);
        ini_set('display_errors', 1);
    }
}
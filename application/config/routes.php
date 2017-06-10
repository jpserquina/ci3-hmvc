<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

/*
$route['default_controller'] = 'Welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
*/

$MODULED_VAR = defined('MODULE_NAME') ? '' : '(:any)/';
$MODULED_VAR_ = defined('MODULE_NAME') ? MODULE_NAME : '$1';

if(isset($_GET['module'])){
    $route['default_controller'] = $_GET['module'];
}
elseif(defined('MODULE_NAME')){
    $route['default_controller'] = MODULE_NAME;
}
else{
    $route['default_controller'] = 'Minisite_Controller';
}
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//CAPTCHA.PHP
$route['captcha.php'] = '$1/CaptchaPHP';
$route['(:any)/captcha.php'] = '$1/CaptchaPHP';
$route['(:any)/(:any)/captcha.php'] = '$1/CaptchaPHP';

//EXCEL.PHP
$route['Excel.php'] = '$1/ExcelPHP';
$route['(:any)/Excel.php'] = '$1/ExcelPHP';
$route['(:any)/(:any)/Excel.php'] = '$1/ExcelPHP';

//BACKEND GIFT FLAG
$route['gift_flag.php'] = '$1/BACKEND_gifting';
$route['(:any)/gift_flag.php'] = '$1/BACKEND_gifting';
$route['(:any)/(:any)/gift_flag.php'] = '$1/BACKEND_gifting';

//ASSETS
$route['(:any)/assets/(:any)/(:any)'] = 'Minisite_Controller/assets';
$route['(:any)/assets/(:any)/(:any)/(:any)'] = 'Minisite_Controller/assets';
$route['(:any)/assets/(:any)/(:any)/(:any)/(:any)'] = 'Minisite_Controller/assets';
$route['(:any)/assets/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Minisite_Controller/assets';
$route['(:any)/assets/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Minisite_Controller/assets';
$route['(:any)/assets/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Minisite_Controller/assets';

//BACKEND ROUTING
$route[$MODULED_VAR . 'backend/(:any)'] = $MODULED_VAR_.'/BACKEND';
$route[$MODULED_VAR . 'auth/backend'] = $MODULED_VAR_.'/BACKEND_loginpage';

//MAIL HTML
$route['(:any)/updates/(:any)'] = '$1/UPDATES';
$route['(:any)/updates/(:any)/images/(:any)'] = 'Minisite_Controller/assets';

//FACEBOOK CHANNEL HTML
$route['(:any)/facebook/channel.html'] = '$1/fb_channel';

//CLICK TRACKING
$route['(:any)/click_tracking'] ='$1/CLICK_TRACKING';
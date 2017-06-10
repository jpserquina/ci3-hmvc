<?php (defined('EXTENDS')) OR exit('No direct script access allowed');

/**
* This is use only for Backend.
* @author carl louis manuel
*/
/* load the MX_Loader class */
require APPPATH."third_party/MX/Controller.php";
require_once APPPATH.'core/MY_Controller_Interface.php';
class Backend_Controller extends MX_Controller implements MY_Controller_Interface{

    public $CI;
    static $CONFIG = array(),$_BACKEND_TEMPLATE;
    protected $_BACKEND_CREDENTIALS;
    const COOKIE_NAME = 'minisite_backend';

    private $URI1;
    private $URI3;

    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;

        $this->load->library('Lib_log');
        //$this->checkMinisite();

        $this->URI1 = defined('MODULE_NAME') ? MODULE_NAME : $this->uri->segment(1);
        $this->URI3 = defined('MODULE_NAME') ? $this->uri->segment(2) : $this->uri->segment(3);
    }

    private function checkMinisite(){
        if(MY_Controller::$CONFIG['MINISITE_ID'] && count($this->Minisite_minisites_model->minisite_id_exists()) == false){
            show_error('Minisite ID:'.MY_Controller::$CONFIG['MINISITE_ID'].' not found!');
        }
        elseif(!MY_Controller::$CONFIG['MINISITE_ID']){
            show_error('Please setup the Minisite ID!');
        }
    }

    /*
     * MAILER
     */
    private $UPDATE_PATH = 'mail';
    public final function UPDATES(){
        $MODULE_PATH = $this->UPDATE_PATH.'/'.$this->uri->segment(3);
        if(file_exists($PATH = APPPATH.'modules/'.$this->router->module.'/views/'.$MODULE_PATH.'.php')
            && is_dir(APPPATH.'modules/'.$this->router->module.'/assets/images/email_templates/'.strtoupper(MY_Controller::$CONFIG['lang_param']).'/')){
            $this->load->view($MODULE_PATH);
        }
        else show_404();
    }

    public final function captchaPHP(){
        $this->minisite->captchaPHP();
    }

    public final function ExcelPHP(){
        $this->minisite->ExcelPHP();
    }

    /**
     * GLOBAL FACEBOOK AUTH
     * - it fixed the fb session bug
     * @author CARL LOUIS MANUEL
     */
    public final function fboauth(){
        header('X-UA-Compatible: IE=edge');
        // $this->load->library('user_agent');
        // if($this->agent->browser() == 'Internet Explorer'):
        // echo '<h2>If you are not redirect back to page please check your Internet Explorer.</h2>';
        // echo '<h2>Internet Options > Security > Uncheck the Enable Protected Mode. And then try again.</h2>';
        // endif;

        foreach ($_SESSION as $k=>$v) {
            if(strpos($k, "FBRLH_")!==FALSE) {
                if(!setcookie($k, $v)) {
                    //what??
                } else {
                    $_COOKIE[$k]=$v;
                }
            }
        }

        try {
            $accessToken = $this->fb_fetcher->__getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $ERROR['msg'] = 'fboauth: Graph returned an error: ' . $e->getMessage() . ' CODE:' . $e->getCode();
            $ERROR['code'] = $e->getCode();
            if($e->getCode() == 100){
                echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                <html>
                <head>
                <meta http-equiv="X-UA-Compatible" content="IE=Edge">
                <script>
                    var openers = null;

                    if(window.dialogArguments){
                        openers = window.dialogArguments;
                    }
                    else{
                        if(window.opener){
                            openers = window.opener;
                        }
                    }

                    if(openers){
                        openers.windowOpenerError('.json_encode($ERROR).');
                        window.close();
                    }
                </script>
                </head></html>';
            }
            // throw new Exception('Graph returned an error: ' . $e->getMessage());
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $ERROR = 'fboauth: Facebook SDK returned an error: ' . $e->getMessage() . ' ' . $e->getCode();
            // throw new Exception('Facebook SDK returned an error: ' . $e->getMessage());
            exit;
        }

        if(isset($accessToken)){
            $now = new DateTime();
            $difference_in_seconds = $accessToken->getExpiresAt()->format('U') - $now->format('U');

            // $cookie = array(
            //     'name'   => $this->router->module.'_fb_token',
            //     'value'  => base64_encode(serialize($accessToken)),
            //     'expire' => (time() + $difference_in_seconds),
            //     'path'   => '/',
            // );
            // $this->input->set_cookie($cookie);
            $this->session->set_userdata($this->router->module.'_fb_token',base64_encode(serialize($accessToken)));

            redirect($this->input->get('redirect'));
        }
        else{
            redirect($this->input->get('redirect')
                // .'?error='.$this->fb_fetcher->_FB_REDIRECT_HELPER->getError()
                // .'&errorReason='.$this->fb_fetcher->_FB_REDIRECT_HELPER->getErrorReason()
                // .'&errorCode='.$this->fb_fetcher->_FB_REDIRECT_HELPER->getErrorCode()
                // .'&errorDescription='.$this->fb_fetcher->_FB_REDIRECT_HELPER->getErrorDescription()
            );
        }
    }

    public final function fb_channel(){
        $this->load->view('Facebook/channel');
    }

    public final function BACKEND(){
        $MODULE = Modules::run(
            (defined('MODULE_NAME') ? MODULE_NAME
                : $this->uri->segment(1)).'/backend_'.
            (defined('MODULE_NAME') ? $this->uri->segment(2) : $this->uri->segment(3)));

        if(!$MODULE && !method_exists((Modules::load($this->URI1)), 'backend_'.$this->URI3) ||
            (in_array($this->URI3, array('checklogin', 'login', 'loginpage', 'gifting')))
        ) show_404();

        /**
         * CHECK IF LOGGED IN
         */
        $this->BACKEND_login();

        $BACKEND['CLASS'] = 'loggedin';
        $BACKEND['contents'] = Modules::run($this->URI1.'/backend_'.$this->URI3);
        $BACKEND['title'] = self::$CONFIG['SITE_TITLE'];
        if(MY_Controller::$_BACKEND_TEMPLATE !== false) $this->load->view('Backend/template', $BACKEND);
        else echo $BACKEND['contents'];
    }

    private final function BACKEND_login(){
        $this->_BACKEND_CREDENTIALS = unserialize(MAINTENANCE_USERS)[$_SERVER['SERVER_NAME'] == 'localhost' ? 1 : 0];
        $COOKIE = get_cookie(self::COOKIE_NAME);
        $COOKIE_E = $this->encrypt->decode($COOKIE);
        $COOKIE_U = unserialize($COOKIE_E);
        if($COOKIE == false || !$COOKIE_E ||
            (!isset($COOKIE_U['POST']['txt_uname']) || !isset($COOKIE_U['POST']['txt_upass'])) ||
            ($COOKIE_U['POST']['txt_uname'] !== $this->_BACKEND_CREDENTIALS['uname'] || $COOKIE_U['POST']['txt_upass'] !== $this->_BACKEND_CREDENTIALS['upass'])
        ){
            $queryString = false;
            if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) $queryString = '&q='.base64_encode($_SERVER['QUERY_STRING']);
            redirect(
                (defined('MODULE_NAME') ? '' : self::$CONFIG['URL_DINAMIC_CONTENT'])
.'auth/backend/?redirect='.$this->URI3.$queryString,'refresh');
        }
    }

    public final function BACKEND_loginpage(){
        if(strpos(__METHOD__,$this->uri->segment(2)) !== false) die('No direct script access allowed');
        $_REDIRECT = $this->input->get('redirect',true);
        $_Q = $this->input->get('q',true);

        $view['str_dislay_error_messages'] = false;
        if($this->input->post() && !$this->BACKEND_checkLogin())
        {
            $view['str_dislay_error_messages'] = '<p>
            <span>Invalid credentails!</span><BR/>
            <span>Please provide a valid credentials.</span>
            </p>';
        }
        elseif($this->BACKEND_checkLogin())
        {
            $cookie = array(
                'name'   => self::COOKIE_NAME,
                'value'  => $this->encrypt->encode(serialize(array('login'=>true,'POST'=>$this->input->post()))),
                'expire' => '86500',
                'path'   => '/',
            );
            $this->input->set_cookie($cookie);
            redirect((defined('MODULE_NAME') ? '' : self::$CONFIG['URL_DINAMIC_CONTENT']).'backend/'.$_REDIRECT.($_Q ? '?'.base64_decode($_Q) : ''),'refresh');
        }
        $this->load->view('Backend/template',array('contents'=>$this->load->view('Backend/login',$view,true)));
    }

    private final function BACKEND_checkLogin()
    {
        $this->_BACKEND_CREDENTIALS = unserialize(MAINTENANCE_USERS)[$_SERVER['SERVER_NAME'] == 'localhost' ? 1 : 0];
        $_LOGIN = ($this->input->post('txt_uname') == $this->_BACKEND_CREDENTIALS['uname'] && $this->input->post('txt_upass') == $this->_BACKEND_CREDENTIALS['upass']);
        return $_LOGIN;
    }

    public final function BACKEND_gifting(){
        if(strpos(__METHOD__,$this->uri->segment(2)) !== false) die('No direct script access allowed');
        if(!$this->input->get('id')) die('No GET id');
        $aRow = $this->Minisite_model->get_minisite_by_mc_id($this->input->get('id',true));
        if(!count($aRow)) die('No id found');
        $aRowJson = json_decode($aRow['mc_value'],true);
        $aRowJson['flag'] = 1;

        /**
         * @todo: INSERT API OF GIFTING HERE
         */

        $this->Minisite_model->set_minisite_value_by_mc_id($this->input->get('id',true),$aRowJson);
    }

    public final function BACKEND_remove() {
        /* POSSIBLE SQL INJECTION VECTOR */
        if(strpos(__METHOD__,$this->uri->segment(2)) !== false) die('No direct script access allowed');
        if(!$this->input->get('id')) die('No GET id');
        $aRow = $this->Minisite_model->get_minisite_by_mc_id($this->input->get('id',true));
        if(!count($aRow)) die('No id found');
        $result = $this->Minisite_model->remove_registrant($this->input->get('id',true));
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        /*$aRowJson = json_decode($aRow['mc_value'],true);
        $aRowJson['flag'] = 1;
        $this->Minisite_model->set_minisite_value_by_mc_id($this->input->get('id',true),$aRowJson);*/
    }

    /**
     * CREATE LOCALIZATION FOR MINISITES THAT USING .PO FILE
     * @author CARL LOUIS MANUEL
     */
    public final function BACKEND_locs(){
        $this->load->model('Localization_Model');

        $this->load->library('composer');
        $lang = $data['lang'] = $this->input->get('lang') ? $this->input->get('lang') : (MY_Controller::$CONFIG['DEFAULT_LANG'] ? MY_Controller::$CONFIG['DEFAULT_LANG'] : 'en');

        // Parse a po file
        $fileHandler_EN = new Sepia\FileHandler(APPPATH.'modules/'.$this->router->module.'/localization/'.(MY_Controller::$CONFIG['DEFAULT_LANG'] ? MY_Controller::$CONFIG['DEFAULT_LANG'] : 'en').'/translation.po');
        $customPath = APPPATH.'modules/'.$this->router->module.'/localization/'.$lang.'/translation.po';
        if(!file_exists($customPath)) show_404();
        $fileHandler = new Sepia\FileHandler($customPath);

        $poParser_EN = new Sepia\PoParser($fileHandler_EN);
        $poParser = new Sepia\PoParser($fileHandler);

        $entries_EN  = $poParser_EN->parse();
        $entries  = $poParser->parse();

        if($this->uri->segment(4) == 'update' && $locs = $this->input->post('locs')){
            foreach($locs as $loc){
                $msgid = base64_decode($loc['msgid']);
                $entries[$msgid]['msgstr'] = explode('<##EOL##>',$loc['msgstr']);
                $poParser->setEntry($msgid, $entries[$msgid]);
            }
            $newentry = array();
            foreach($this->input->post('newlocs') as $keynewlocs=>$newlocs){
                $msgid = $newlocs['msgid'];
                $newentry[$msgid]['reference'] = array($newlocs['reference']);
                $newentry[$msgid]['msgid'] = explode('<##EOL##>',$newlocs['msgid']);
                $newentry[$msgid]['msgstr'] = explode('<##EOL##>',$newlocs['msgstr']);
                $poParser->setEntry($msgid, $newentry[$msgid]);
            }

            $data = $poParser->compile();

            if($this->input->post('submit') == 'submit'){
                $this->load->helper('download');
                $name = $this->router->module.'_translation_'.strtoupper($lang).'.po';
                force_download($name, $data);
            }
            elseif($this->input->post('submit') == 'test'){
                MY_Controller::$_BACKEND_TEMPLATE = false;

                $temp_file = tempnam(sys_get_temp_dir(), 'po'.rand());

                $tmp_handle = fopen($temp_file, 'r+');
                fwrite($tmp_handle, $data);

                rewind($tmp_handle);

                fclose($tmp_handle);

                $_POST['po_file'] = $temp_file;
                echo Modules::run($this->router->module);
            }
            elseif($this->input->post('submit') == 'save'){
                $this->Localization_Model->save($data);

                $rows = $this->db->affected_rows();
                if($rows){
                    echo '<script>window.opener.alertWindow("Successfully Saved!");window.opener.refresh();window.close();</script>';
                }
                else{
                    echo '<script>window.opener.alertWindow("Nothings changed.");window.close();</script>';
                }
                die();
            }
        }
        else{
            $data['entries_EN'] = $entries_EN;
            $data['entries'] = $entries;
            $entries_db = $this->Localization_Model->get();
            $locs_db = null;
            if($entries_db){
                $temp_file = tempnam(sys_get_temp_dir(), 'po'.rand());
                $tmp_handle = fopen($temp_file, 'r+');
                fwrite($tmp_handle, base64_decode($entries_db->mc_ci_l_value));
                rewind($tmp_handle);
                fclose($tmp_handle);

                $po_handler = new Sepia\FileHandler($temp_file);
                $po_db = new Sepia\PoParser($po_handler);
                $locs_db = $po_db->parse();
            }

            $data['entries_db'] = $locs_db;

            foreach(scandir(APPPATH.'modules/'.$this->router->module.'/localization') as $locs){
                if(!in_array($locs,array('.','..'))){
                    $data['locs_selection'][] = $locs;
                }
            }

            //FILTER NEW LOCS
            $newlocs = array();
            foreach($this->localization->scan_dir() as $keylocs=>$locs){
                foreach($entries_EN as $entry){
                    $msgid = '';
                    $exist = false;
                    foreach($entry['msgid'] as $_msgid) $msgid .= $_msgid;
                    if(!isset($entry['obsolete']) && $msgid == $locs['string']){
                        $exist = true;
                        break;
                    }
                }
                if(!$exist) $newlocs[] = $locs;
            }
            $data['unscanned_locs'] = $newlocs;

            $this->load->view('Backend/translation',$data);
        }
    }

    /*public final function BACKEND_global_maintenance(){
        $this->load->model('Minisite_model');
        $conf = MY_Controller::$CONFIG;
        if($this->input->method() == 'post'){
            MY_Controller::$_BACKEND_TEMPLATE = false;
            $points = $this->input->post('txt_points');
            $data['mc_value'] = json_encode(array(
                    'reg_type' => "admin",
                    'reg_confirmed' => 1,
                    'points' => intval($points)
                ));
            if ($this->Minisite_model->validate_admin_points() > 0) {
                $row = $this->Minisite_model->get_admin_points_id();
                $this->Minisite_model->update('mc_id',$data,$row->mc_id);
                $data['status'] = "update";
            } else {
                $this->Minisite_model->insert($data);
                $data['status'] = "insert";
            }

            $data['success'] = true;
            echo json_encode($data);
        }
        else{
            if($this->uri->segment(4) == 'reset'){
                $row = $this->Minisite_model->get_admin_points_id();
                $this->Minisite_model->reset_admin_points($row->mc_id);

                redirect($conf['URL_DINAMIC_CONTENT'] . 'backend/global_maintenance/', 'refresh');
            }
            $data['reset_action'] = "backend/global_maintenance/reset/";
            $data['current_registration_points'] = $this->Minisite_model->current_registration_points();
            $data['manually_added_points'] = $this->Minisite_model->manually_added_points();
            $data['button_status'] = $this->Minisite_model->validate_admin_points();
            $this->load->view('Backend/maintenance',$data);
        }
    }*/

    public final function BACKEND_maintenance(){
        $this->load->model('Minisite_model');
        $conf = MY_Controller::$CONFIG;
        if ($conf['maintenance_page'] != "advance") {
            if($this->input->method() == 'post'){
                MY_Controller::$_BACKEND_TEMPLATE = false;
                $points = $this->input->post('txt_points');
                $data['mc_value'] = json_encode(array(
                        'reg_type' => "admin",
                        'reg_confirmed' => 1,
                        'points' => intval($points)
                    ));
                if ($this->Minisite_model->validate_admin_points() > 0) {
                    $row = $this->Minisite_model->get_admin_points_id();
                    $this->Minisite_model->update('mc_id',$data,$row->mc_id);
                    $data['status'] = "update";
                } else {
                    $this->Minisite_model->insert($data);
                    $data['status'] = "insert";
                }

                $data['success'] = true;
                echo json_encode($data);
            }
            else{
                if($this->uri->segment(4) == 'reset'){
                    $row = $this->Minisite_model->get_admin_points_id();
                    $this->Minisite_model->reset_admin_points($row->mc_id);

                    redirect($conf['URL_DINAMIC_CONTENT'] . 'backend/maintenance/', 'refresh');
                }
                $data['reset_action'] = "backend/maintenance/reset/";
                $data['current_registration_points'] = $this->Minisite_model->count_total_points2();
                $data['manually_added_points'] = $this->Minisite_model->manually_added_points();
                $data['button_status'] = $this->Minisite_model->validate_admin_points();
                $this->load->view('Backend/maintenance',$data);
            }
        } else {
            if($this->input->method() == 'post'){
                MY_Controller::$_BACKEND_TEMPLATE = false;
                $points = $this->input->post('txt_points');
                $data['mc_value'] = json_encode(array(
                        'reg_type' => "admin",
                        'reg_confirmed' => 1,
                        'points' => intval($points)
                    ));
                if ($this->Minisite_model->validate_admin_points() > 0) {
                    $row = $this->Minisite_model->get_admin_points_id();
                    $this->Minisite_model->update('mc_id',$data,$row->mc_id);
                    $data['status'] = "update";
                } else {
                    $this->Minisite_model->insert($data);
                    $data['status'] = "insert";
                }

                $data['success'] = true;
                echo json_encode($data);
            }
            else{
                if($this->uri->segment(4) == 'reset'){
                    $row = $this->Minisite_model->get_admin_points_id();
                    $this->Minisite_model->reset_admin_points($row->mc_id);

                    redirect($conf['URL_DINAMIC_CONTENT'] . 'backend/maintenance/', 'refresh');
                }
                $data['reset_action'] = "backend/maintenance/reset/";
                $data['current_registration_points'] = $this->Minisite_model->count_total_points2();
                $data['manually_added_points'] = $this->Minisite_model->manually_added_points();
                $data['button_status'] = $this->Minisite_model->validate_admin_points();
                $this->load->view('Backend/maintenance',$data);
            }
        }
    }

    /* BACKEND CMS */
    public final function BACKEND_cms() {
        // --
        // -- Table structure for table `minisite_cms`
        // --
        // CREATE TABLE `minisite_cms` (
        //   `id` int(11) NOT NULL,
        //   `ms_id` int(11) NOT NULL,
        //   `title` varchar(250) NOT NULL,
        //   `created_at` varchar(30) NOT NULL,
        //   `updated_at` varchar(30) NOT NULL
        // ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

        // ALTER TABLE `minisite_cms` ADD PRIMARY KEY (`id`);
        // ALTER TABLE `minisite_cms` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

        // --
        // -- Table structure for table `minisite_cms_metakeys`
        // --

        // CREATE TABLE `minisite_cms_metakeys` (
        //   `id` int(11) NOT NULL,
        //   `mc_id` int(11) NOT NULL,
        //   `mc_key` varchar(250) NOT NULL
        // ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

        // ALTER TABLE `minisite_cms_metakeys` ADD PRIMARY KEY (`id`);
        // ALTER TABLE `minisite_cms_metakeys` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

        // --
        // -- Table structure for table `minisite_cms_metavalues`
        // --

        // CREATE TABLE `minisite_cms_metavalues` (
        //   `id` int(11) NOT NULL,
        //   `mc_key_id` int(11) NOT NULL,
        //   `mc_value` mediumtext,
        //   `lang` varchar(10) NOT NULL
        // ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

        // ALTER TABLE `minisite_cms_metavalues` ADD PRIMARY KEY (`id`);
        // ALTER TABLE `minisite_cms_metavalues` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


        // CREATE VIEW viewcmsvalues AS
        // SELECT cmk.*, cmv.id AS mc_value_id, cmv.mc_value, cmv.lang
        // FROM minisite_cms_metakeys AS cmk, minisite_cms_metavalues AS cmv
        // WHERE cmk.id = cmv.mc_key_id;

        // die("Comming Soon! ;)");
        $this->load->model('Cms_model');

        $data['isCMS'] = true;
        // $data['page_title'] = 'Updates';
        $data['news_list'] = $this->Cms_model->get_where("cms", array("ms_id" => self::$CONFIG['MINISITE_ID']));
        $data['main_content'] = 'Backend/cms/dashboard';
        $this->load->view('Backend/cms/includes/template', $data);
    }

    public function BACKEND_pages(){
        $this->load->model('Cms_model');

        $data['isCMS'] = true;
        // $data['page_title'] = 'Updates';
        $data['news_list'] = $this->Cms_model->get_where("cms", array("ms_id" => self::$CONFIG['MINISITE_ID']));
        $data['main_content'] = 'Backend/cms/cms_pages';
        $this->load->view('Backend/cms/includes/template', $data);
    }

    public function BACKEND_posts(){
        $this->load->model('Cms_model');

        $data['isCMS'] = true;
        // $data['page_title'] = 'Updates';
        $data['news_list'] = $this->Cms_model->get_where("cms", array("ms_id" => self::$CONFIG['MINISITE_ID']));
        $data['main_content'] = 'Backend/cms/cms_posts';
        $this->load->view('Backend/cms/includes/template', $data);
    }

    /*public function backend_add(){
        $data['isCMS'] = true;
        $data['page_title'] = 'Updates';
        $data['_supported_lang'] = explode(",", $this->conf['supported_lang']);
        $data['script'] = '$(\'a[data-toggle="tab"]\').on(\'show.bs.tab\', function (e) {
            mc_value = "mc_value["+$(e.currentTarget).attr("aria-controls")+"]";
        });';
        $this->load->view('godsofrome/cms/addPage', $data);
    }*/

    public function cmsSaveTitle() {
        if($this->input->is_ajax_request()) {
            MY_Controller::$_BACKEND_TEMPLATE = false;
            $ret = array();
            $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean', // |is_unique[minisite_cms.title]',
                array(
                    'required'  => 'You must provide a %s.',
                    // 'is_unique' => '%s already exists.'
                )
            );

            if ($this->form_validation->run() == FALSE)  {
                $ret["result"] = validation_errors();
            }
            else {
                $date = date("Y-m-d h:i:s A");
                $data = array(
                    'ms_id'      => self::$CONFIG['MINISITE_ID'],
                    'title'      => $this->input->post("title"),
                    'created_at' => $date,
                    'updated_at' => $date
                );

                $this->load->model('Cms_model');
                try {
                    $insert_id = $this->Cms_model->insert("cms", $data);
                    $keyData = array(
                        'mc_id'  => $insert_id,
                        'mc_key' => "description" // Set by default
                    );
                    $this->Cms_model->insert("metakeys", $keyData);

                    $ret["result"]   = "success";
                    $ret["redirect"] = self::$CONFIG['URL_DINAMIC_CONTENT'] . 'backend/cmsViewEdit?id=' . $insert_id;
                } catch (Exception $e) {
                    // $ret["result"] = "Not saved! Please try again.";
                    $ret["result"] = $e->getMessage();
                }
            }
            echo json_encode($ret);
        }
        else show_404();
    }

    public function BACKEND_cmsViewEdit() {
        $this->load->model('Cms_model');

        $update             = array("id" => $this->input->get("id"));
        $data['isCMS']      = true;
        $data['model']      = $this->Cms_model;
        $data['page_title'] = 'Updates';
        $data['_item']      = $data['model']->get_where("cms", $update)[0];
        $data['script']     = '
        $(\'a[data-toggle="tab"]\').on(\'show.bs.tab\', function (e) {
            //mc_value = "mc_value["+$(e.currentTarget).attr("aria-controls")+"]";
            create_mc_value = "mc_valuecreate["+$(e.currentTarget).attr("aria-controls").substr(0, 2)+"]";
            console.log(create_mc_value);
        });';
        
        $data['main_content'] = 'Backend/cms/cms_editPage';
        if($data['_item']) $this->load->view('Backend/cms/includes/template', $data);

        // if($data['_item']) $this->load->view('Backend/cms/editPage', $data);
        else show_404();
    }

    public function cmsUpdateTitle() {
        if($this->input->is_ajax_request()) {
            MY_Controller::$_BACKEND_TEMPLATE = false;
            $ret = array();
            $this->form_validation->set_rules('item_id', 'Page ID', 'required|xss_clean',
                array( 'required'  => 'You must provide a %s.' )
            );

            $is_unique = ""; //$this->input->post("item_old") == md5($this->input->post("title")) ? "" : "|is_unique[minisite_cms.title]";
            $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean' . $is_unique,
                array(
                    'required'  => 'You must provide a %s.',
                    // 'is_unique' => '%s already exists.'
                )
            );

            if ($this->form_validation->run() == FALSE)  {
                $ret["result"] = validation_errors();
            }
            else {
                $this->load->model('Cms_model');
                try {
                    $date = date("Y-m-d h:i:s");
                    $data = array(
                        'title'      => $this->input->post("title"),
                        'updated_at' => $date
                    );
                    $insert_id = $this->Cms_model->update("cms", $data, $this->input->post("item_id"));

                    $ret["result"] = "success";
                }
                catch(Exception $e) {
                    $ret["result"] = $e->getMessage();
                }
            }
            echo json_encode($ret);
        }
        else show_404();
    }

    public final function cmsGetFieldValue() {
        if($this->input->is_ajax_request()) {
            MY_Controller::$_BACKEND_TEMPLATE = false;
            $ret = array();
            $this->form_validation->set_rules('id', 'Key Identifier ID', 'required|trim|xss_clean',
                array( 'required'  => 'You must provide a %s.' )
            );

            $this->form_validation->set_rules('lang', 'Language', 'required|trim|xss_clean',
                array( 'required'  => 'You must provide a %s.' )
            );

            if ($this->form_validation->run() == FALSE)  {
                $ret["result"] = validation_errors();
            }
            else {
                $this->load->model('Cms_model');
                try {
                    $data          = $this->Cms_model->get_field_values($this->input->post("id"), $this->input->post("lang"));
                    $ret["data"]   = !$data ? $data : $data[0];
                    $ret["result"] = "success";
                }
                catch(Exception $e) {
                    $ret["result"] = $e->getMessage();
                }
            }
            echo json_encode($ret);
        }
        else show_404();
    }

    public function cmsSaveKey() {
        if($this->input->is_ajax_request()) {
            MY_Controller::$_BACKEND_TEMPLATE = false;

            $mc_key_id     = $this->input->post("id");
            $mc_id         = $this->input->post("mc_id");
            $key           = $this->input->post("key");
            $keyIdentifier = $this->formatKey($this->input->post("keyIdentifier"));
            $ret           = array();

            if ("" != $key && $key == md5($keyIdentifier)) {
                $ret["keyIdentifier"] = $keyIdentifier;
                $ret["key"]           = md5($keyIdentifier);
                $ret["result"]        = "success";
            }
            else {
                $this->load->model('Cms_model');
                if (!$this->Cms_model->check_key($keyIdentifier, $mc_id, $mc_key_id)) {
                    try {
                        $data = array(
                            'mc_id'  => $mc_id,
                            'mc_key' => $keyIdentifier
                        );

                        if ("" != $key) $this->Cms_model->update("metakeys", $data, $mc_key_id);
                        else $ret["insert_id"] = $this->Cms_model->insert("metakeys", $data);

                        $this->Cms_model->touch($mc_id);
                        $ret["keyIdentifier"] = $keyIdentifier;
                        $ret["key"]           = md5($keyIdentifier);
                        $ret["result"]        = "success";
                    } catch (Exception $e) {
                        $ret["result"] = $e->getMessage();
                    }
                }
                else $ret["result"] = "Field Key already exists.";
            }
            echo json_encode($ret);
        }
        else show_404();
    }

    public function cmsSaveValue() {
        if($this->input->is_ajax_request()) {
            MY_Controller::$_BACKEND_TEMPLATE = false;
            $mc_id     = $this->input->post("mc_id");
            $mc_key_id = $this->input->post("mc_key_id");
            $mc_val_id = $this->input->post("mc_val_id");
            $lang      = $this->input->post("lang");
            $mc_value  = $this->input->post("content");
            $ret       = array();

            $this->load->model('Cms_model');
            try {
                $data = array(
                    'mc_key_id' => $mc_key_id,
                    'mc_value'  => $mc_value,
                    'lang'      => $lang
                );

                if ("" != $mc_val_id) $this->Cms_model->update("metavalues", $data, $mc_val_id);
                else $ret["insert_id"] = $this->Cms_model->insert("metavalues", $data);

                $this->Cms_model->touch($mc_id);
                $ret["result"] = "success";
            } catch (Exception $e) {
                $ret["result"] = $e->getMessage();
            }
            echo json_encode($ret);
        }
        else show_404();
    }

    private function formatKey($key) {
        return str_replace(' ', '_', strtolower($key));
    }

    /**
    * PROCESS CLICK TRACKING
    * @author CARL LOUIS MANUEL
    */
    public final function CLICK_TRACKING(){
        $this->load->library('encrypt');
        $conf = MY_Controller::$CONFIG;

        if(!$code = $this->input->get('code')) show_404();

        if(!$code = json_decode(urldecode($this->encrypt->decode($code,$conf['SECRET_WORD'])))) show_404();

        if(!$link = filter_var($code->link, FILTER_VALIDATE_URL)) show_404();

        $type = json_decode($code->type);

        $this->load->model('Minisite_model');
        $click = $this->db->from($this->Minisite_model->_TABLE)
            ->where('mc_minisite',$conf['MINISITE_ID'])
            ->like('mc_value','clicktracking')->get()->row();

        if($click){
            $_data = (array) json_decode($click->mc_value)->clicktracking;
            $_data[$type->code] = $_data[$type->code] + 1;
            $data['clicktracking'] = $_data;
            $data = json_encode($data);

            $this->db->set('mc_value',$data);
            $this->db->where('mc_minisite',$conf['MINISITE_ID']);
            $this->db->like('mc_value','clicktracking');
            $this->db->update($this->Minisite_model->_TABLE,$insert);
        }
        else{
            $data['clicktracking'] = array(
                'ios'=>0,
                'android'=>0,
                'wp'=>0,
                'fb'=>0,
                'tw'=>0,
                'yt'=>0
            );

            $data['clicktracking'][$type->code] = 1;

            $insert['mc_minisite'] = $conf['MINISITE_ID'];
            $insert['mc_value'] = json_encode($data);
            $this->db->insert($this->Minisite_model->_TABLE,$insert);
        }

        redirect($link);
    }
}

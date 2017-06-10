<?php
/* load the MX_Loader class */
require APPPATH."third_party/MX/Config.php";
class MY_Config extends MX_Config{
    private $CI;

    /**
     * RETURN MERGE CONFIGS
     * @return array
     */
    public function get_instance(){
        $this->CI = & get_instance();

        $_CONFIG = $this->CI->config->load('config',true);
        $_CONFIG2 = $this->CI->config->item('mc');
        $data = $_CONFIG;
        $data2 = array_merge($_CONFIG2['locs'],$_CONFIG2['config']);
        $merge = array_merge($data2,$data);

        $merge = $this->checkconfigs($merge);

        return $merge;
    }

    /**
     * Site URL
     *
     * Returns base_url . index_page [. uri_string]
     *
     * @uses    CI_Config::_uri_string()
     *
     * @param   string|string[] $uri    URI string or an array of segments
     * @param   string  $protocol
     * @return  string
     */
    public function site_url($uri = '', $protocol = NULL)
    {
        $base_url = $this->slash_item('base_url');

        if (isset($protocol))
        {
            // For protocol-relative links
            if ($protocol === '')
            {
                $base_url = substr($base_url, strpos($base_url, '//'));
            }
            else
            {
                $base_url = $protocol.substr($base_url, strpos($base_url, '://'));
            }
        }

        if (empty($uri))
        {
            return $base_url.$this->item('index_page');
        }

        $uri = $this->_uri_string($uri);

        if ($this->item('enable_query_strings') === FALSE)
        {
            $suffix = isset($this->config['url_suffix']) ? $this->config['url_suffix'] : '';

            if ($suffix !== '')
            {
                if (($offset = strpos($uri, '?')) !== FALSE)
                {
                    $uri = substr($uri, 0, $offset).$suffix.substr($uri, $offset);
                }
                else
                {
                    $uri .= $suffix;
                }
            }

            return $base_url.$this->slash_item('index_page').$uri;
        }
        elseif (strpos($uri, '?') === FALSE)
        {
            $uri = '?'.$uri;
        }

        return $base_url.$this->item('index_page').$uri;
    }

    // -------------------------------------------------------------

    /**
     * Base URL
     *
     * Returns base_url [. uri_string]
     *
     * @uses    CI_Config::_uri_string()
     *
     * @param   string|string[] $uri    URI string or an array of segments
     * @param   string  $protocol
     * @return  string
     */
    public function base_url($uri = '', $protocol = NULL)
    {
        $base_url = $this->slash_item('base_url');

        if (isset($protocol))
        {
            // For protocol-relative links
            if ($protocol === '')
            {
                $base_url = substr($base_url, strpos($base_url, '//'));
            }
            else
            {
                $base_url = $protocol.substr($base_url, strpos($base_url, '://'));
            }
        }

        return $base_url.ltrim($this->_uri_string($uri), '/');
    }

    private function checkconfigs($data){
        //URL LINKS
        $data['URL_DINAMIC_CONTENT'] = $this->base_url($this->CI->router->module).'/';
        $data['URL_STATIC_CONTENT'] = $this->site_url($this->CI->router->module).'/';

        //SET LANG PARAM
        if($lang = $this->CI->input->get('lang',true)){
            $data['lang_param'] = preg_replace('/[^A-Za-z0-9\-\_]/', '', substr($lang,0,5));
        }
        elseif(isset($data['DEFAULT_LANG'])){
            $data['lang_param'] = preg_replace('/[^A-Za-z0-9\-\_]/', '', substr($data['DEFAULT_LANG'],0,5));
        }

        //SET SUPPORT ACCOUNTS FOR ERROR LOGS SEND TO MAIL
        $support_account = Array();
        // $support_account[] = 'CARL LOUIS MANUEL';
        if(isset($data['aConfig']['mails']['support_account']) && ($SA = $data['aConfig']['mails']['support_account']) != false){
            if(is_array($SA)) foreach($SA as $_sa) $support_account[] = $_sa;
            else foreach(explode(',',$SA) as $_sa) $support_account[] = $_sa;
            $data['SUPPORT_ACCOUNTS'] = $support_account;
        }
        elseif($data['SUPPORT_ACCOUNTS']){
            $data['SUPPORT_ACCOUNTS'] = $support_account;
        }
        else{
            $data['SUPPORT_ACCOUNTS'] = $support_account;
        }
        $data['aConfig']['mails']['support_account'] = array_unique($data['SUPPORT_ACCOUNTS']);

        //SET ENCRYPTION KEY TO SECRET WORD
        if($data['SECRET_WORD'] !== false) $this->CI->config->set_item('encryption_key',$data['SECRET_WORD']);
        elseif(!$data['SECRET_WORD']) $data['SECRET_WORD'] = $this->CI->config->item('encryption_key');


        //CHECKING OF SITE TITLE
        if($data['SITE_TITLE'] !== false) $data['FB_TITLE_NAME'] = $data['SITE_TITLE'];
        elseif($data['SITE_TITLE'] === false) $data['SITE_TITLE'] = $data['FB_TITLE_NAME'];

        //CHECKING OF MINISITE ID
        if($data['MINISITE_ID'] !== false) $data['aConfig']['main']['id'] = $data['MINISITE_ID'];
        elseif($data['MINISITE_ID'] === false && isset($data['aConfig']['main']['id'])) $data['MINISITE_ID'] = $data['aConfig']['main']['id'];
        elseif(!isset($data['aConfig']['main']['id'])) $data['MINISITE_ID'] = false;

        if($data['SHORT_URL'] == false) $data['SHORT_URL'] = $data['URL_DINAMIC_CONTENT'];

        return $data;
    }
}
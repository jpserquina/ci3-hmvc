<?php
class Minisite_Controller extends MX_Controller
{
    protected $_BACKEND_CREDENTIALS;
    private $CI;

    public function __construct(){
        $this->CI = & get_instance();
    }

    public function index(){
        show_404();
    }

    /**
     * AUTO PATH THE ASSETS TO THE assets FOLDER IN THE MODULE
     */
    public function assets(){
        $URI1 = defined('MODULE_NAME') ? MODULE_NAME : $this->CI->uri->segment(1);
        $URI_STRING = $this->CI->uri->uri_string();
        $REQUEST = APPPATH.'modules/'.$URI_STRING;

        //IF ITS BACKEND
        if((strpos($URI_STRING,'backend/assets/maintenance') || strpos($URI_STRING,'backend/assets/cms')) !== false){
            $REQUEST = APPPATH.'../'.str_replace($URI1.'/assets/backend','main_assets/backend',$URI_STRING);
        }
        elseif(strpos($URI_STRING,'minisites_global') !== false){
            $REQUEST = APPPATH.'../'.str_replace($this->CI->uri->segment(1).'/assets/minisites_global','main_assets',$URI_STRING);
        }
        elseif($this->CI->uri->segment(2) == 'updates' && $this->CI->uri->segment(4) == 'images'){
            $REQUEST = APPPATH.'modules/'.(str_replace('updates/'.$this->CI->uri->segment(3),'assets/images/email_templates/'.strtoupper($this->CI->uri->segment(3)),$URI_STRING));
        }

        if(!file_exists($REQUEST)) show_404();

        $header = 'text/html';
        $contents = file_get_contents($REQUEST);
        $pathinfo = pathinfo($REQUEST);
        $filename = $pathinfo['filename'];
        $basename = $pathinfo['basename'];
        $ext = $pathinfo['extension'];
        $to_minify = ( (strpos($basename,'.minify.'.$ext) && $this->CI->input->get('unminify') == false) || $this->CI->input->get('minify'));
        switch($ext){
            case 'js':
                $header = 'text/javascript';
                if($to_minify)
                {
                    // $contents = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $contents);
                    // $contents = str_replace(': ', ':', $contents);
                    // $contents = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $contents);
                    $contents = minisite_minify_js($contents);
                }
                break;
            case 'css':
                $header = 'text/css';
                if($to_minify)
                {
                    // $contents = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $contents);
                    // $contents = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $contents);
                    $contents = minisite_minify_css($contents);
                }
                break;
            case 'png':
                $header = 'image/png';
                break;
            case 'gif':
                $header = 'image/gif';
                break;
            case 'jpg':
                $header = 'image/jpeg';
                if($this->CI->input->get('greyscale')) $contents = $this->greyscalejpg($REQUEST);
                break;
            case 'jpeg':
                $header = 'image/jpeg';
                if($this->CI->input->get('greyscale')) $contents = $this->greyscalejpg($REQUEST);
                break;
            case 'woff2':
                $header = 'application/font-woff2';
                break; 
            case 'otf':
                $header = 'font/opentype';
                break; 
            case 'eot':
                $header = 'application/vnd.ms-fontobject';
                break; 
            case 'woff':
                $header = 'application/font-woff';
                break; 
            case 'ttf':
                $header = 'font/truetype';
                break; 
        }
        header("Content-Type: ".$header);
        echo $contents;
    }

    private function greyscalejpg($file)
    {
        $im = ImageCreateFromJpeg($file); 

        $imgw = imagesx($im);
        $imgh = imagesy($im);

        for ($i=0; $i<$imgw; $i++)
        {
                for ($j=0; $j<$imgh; $j++)
                {

                        // get the rgb value for current pixel

                        $rgb = ImageColorAt($im, $i, $j); 

                        // extract each value for r, g, b

                        $rr = ($rgb >> 16) & 0xFF;
                        $gg = ($rgb >> 8) & 0xFF;
                        $bb = $rgb & 0xFF;

                        // get the Value from the RGB value

                        $g = round(($rr + $gg + $bb) / 3);

                        // grayscale values have r=g=b=g

                        $val = imagecolorallocate($im, $g, $g, $g);

                        // set the gray value

                        imagesetpixel ($im, $i, $j, $val);
                }
        }
        return imagejpeg($im);
    }
}
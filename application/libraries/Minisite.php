<?php

/**
 * Class Minisite
 * @author carl louis manuel
 */
class Minisite
{
    private $CI;

    public function __construct(){
        $this->CI = & get_instance();
        $this->setLang();
    }

    public function setLang(){
        $CONFIG = $this->CI->config->get_instance();
        if($this->CI->router->module){
//             $cookie = array(
//                 'name'   => $this->CI->router->module.'_lang',
// //                'value'  => $this->CI->encrypt->encode(serialize(array('lang'=>$CONFIG['lang_param']))),
//                 'value'  => $CONFIG['lang_param'],
//                 'expire' => '86500',
//                 'path'   => '/',
//             );
//             $this->CI->input->set_cookie($cookie);
            $this->CI->session->set_userdata($this->CI->router->module.'_lang',$CONFIG['lang_param']);
        }
    }

    /**
     * GENERATE captcha
     * ---------------------------------------------------------
     * EX: <img src="captcha.php?v=<?= rand(); ?>"
     * UPON SHOWING THE CAPTCHA THERE IS A COOKIE GENERATED WHERE THE VALUE IS THE DIGIT OF CAPTCHA
     * TO RETURN CAPTCHA DIGIT: <?= var_dump(minisite_getCaptcha()); ?>
     * TO CHECK CAPTCHA IS EQUIVALENT TO INPUT CAPTCHA: <?= var_dump(minisite_getCaptcha($_POST['mycaptchatxt'])); //RETURNS TRUE OR FALSE ?>
     */
    public function CaptchaPHP(){
        // Adapted for The Art of Web: www.the-art-of-web.com
        // Please acknowledge use of this code by including this header.

        // initialise image with dimensions of 120 x 30 pixels
        $width = isset($_GET["width"]) ? $_GET["width"] : 0;
        $height = isset($_GET["height"]) ? $_GET["height"] : 0;
        $centerX = ($width / 2) - 40;
        $centerY = ($height / 2) - 10;
        $image = @imagecreatetruecolor(intval($width), intval($height)) or die("Cannot Initialize new GD image stream");

        // set background to white and allocate drawing colours
        $background = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
        imagefill($image, 0, 0, $background);
        $linecolor = imagecolorallocate($image, 0xCC, 0xCC, 0xCC);
        $textcolor = imagecolorallocate($image, 0x33, 0x33, 0x33);

        // draw random lines on canvas
        for($i=0; $i < 6; $i++) {
            imagesetthickness($image, rand(1,3));
            imageline($image, 0, rand(0,68), 230, rand(0,68), $linecolor);
        }

        // session_start();

        // add random digits to canvas
        $digit = '';
        $x = $centerX;
        for($i = 1; $i <= 4; $i++) {
            $digit .= ($num = rand(0, 9));
            imagechar($image, rand(20, 50), $x, rand($centerY - 5, $centerY + 5), $num, $textcolor);
            $x += 20;
        }

        // record digits in session variable
        // $_SESSION['digit'] = $digit;
        if($this->CI->input->get('type') == 'session'){
            if($this->CI->session->has_userdata('digit')) {
                $this->CI->session->unset_userdata('digit');
            }
            $this->CI->session->set_userdata('digit',$digit);
        }
        else
        {
            $cookie = array(
                'name'   => $this->CI->router->class.'_digit',
                'value'  => $digit,
                'expire' => '86500',
                'path'   => '/',
            );
            $this->CI->input->set_cookie($cookie);
        }

        // display image and clean up
        header('Content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    }

    public function ExcelPHP()
    {
        $filename = $this->CI->input->get('q') ? $this->CI->input->get('q',true) : strtoupper($this->CI->router->class).'_';

        if(!$data = $this->CI->input->post('datasend')) exit('Permission Denied!');
        header('Content-Type: text/html; charset=UTF-8');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-disposition: attachment; filename='.$filename.date('d-M-Y_G_i_s').'.xls');
        echo mb_convert_encoding($data,'HTML-ENTITIES','utf-8');
    }
}
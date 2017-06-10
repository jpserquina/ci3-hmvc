<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * Class Minisite
 * @author jameel gerona
 */
require_once APPPATH."third_party/Get_Text/streams.php";
require_once APPPATH."third_party/Get_Text/gettext.php";
class Localization {
	private $CI;
	public $locale_fetch, $locale_lang, $locale_file, $poparser;

	public function __construct(){
		$this->CI =& get_instance();
	}

	public function translate($text) {
		$CONFIG = MY_Controller::$CONFIG;
		if(!$this->CI->input->post('po_file')){
		    if ($CONFIG['SUPPORTED_LANGS'] != false && !in_array(($this->CI->input->get('lang') ? $this->CI->input->get('lang') : $this->CI->ipwrapper->getCountryFromIp()), $CONFIG['SUPPORTED_LANGS'])) $ip_lang = (isset($CONFIG['DEFAULT_LANG']) && $CONFIG['DEFAULT_LANG'] != false ? $CONFIG['DEFAULT_LANG'] : "en");
		    elseif($this->CI->input->get('lang')) $ip_lang = $this->CI->input->get('lang');
		    else $ip_lang = $this->CI->ipwrapper->getCountryFromIp();

			$this->locale_lang = $ip_lang;
			$FILE_PATH = APPPATH."modules/".$this->CI->router->class."/localization/".$this->locale_lang."/translation.mo";
			if(file_exists($FILE_PATH) == false){
				$FILE_PATH = APPPATH."modules/".$this->CI->router->class."/localization/".(
					isset($CONFIG['DEFAULT_LANG']) && $CONFIG['DEFAULT_LANG'] != false ? $CONFIG['DEFAULT_LANG'] : 'en'
					)."/translation.mo";
			}
			$this->locale_file = new FileReader($FILE_PATH);
			$this->locale_fetch = new gettext_reader($this->locale_file);
			return $this->locale_fetch->translate($text);
		}
		else return $this->translate_($text);
	}

	private function getLocsPath($lang = false){
		if(!$lang) $lang = MY_Controller::$CONFIG['lang_param'];
		return APPPATH.'modules/'.$this->CI->router->module.'/localization/'.$lang.'/translation.po';
	}
	public function translate_($text){
		if(!$this->poparser){
			$this->CI->load->library('composer');

			$locsPath = $this->CI->input->post('po_file') ? $this->CI->input->post('po_file') : $this->getLocsPath();

			if(!file_exists($locsPath)) $locsPath = $this->getLocsPath('en');

			$fileHandler = new Sepia\FileHandler($locsPath);
			$poParser = new Sepia\PoParser($fileHandler);

			$entries  = $poParser->parse();

			$pos = [];
			foreach($entries as $key=>$entry){
				if(!isset($entry['obsolete'])){
					$str = '';
					$id = '';
					foreach($entry['msgstr'] as $msgstr) $str .= $msgstr;
					foreach($entry['msgid'] as $msgid) $id .= $msgid;
					$pos[$id] = $str;
				}
			}

			$this->poparser = $pos;
		}

		return isset($this->poparser[$text]) ? $this->poparser[$text] : $text;
	}

	/*
	 * FOR XLS LOCALIZATION USE THIS
	 *
	 * ---------------------------------------------------------------------------------------------------------
	 */
	public function XLStranslate($filename,$type = 'reader',$lang = false){
		/* This Template is for localization copy this as a whole dont modify anything */
		$_PATH = APPPATH.'modules/'.$this->CI->router->module.'/texts/'.$filename;

		//FOR COUNTRY HELPER
		if($filename == 'country-list-locs.xls'){
			$__PATH = APPPATH.'third_party/assets/texts/'.$filename;
			if(file_exists($__PATH)) $_PATH = $__PATH;
		}

		if($type == 'reader'){
			require_once(APPPATH.'third_party/OldMinisite/includes/excel_reader2.php');
			$_data = new Spreadsheet_Excel_Reader($_PATH, false);
			$arr_excl_locs = array();
			$arr_locs_text_list = array();
			foreach($_data->boundsheets as $k_idx => $k_data){
				$arr_excl_locs[$k_idx] = $k_data['name'];
			}

			//check if lang param is exist to EXCEL
			if($lang && !in_array(strtoupper($lang), $arr_excl_locs)) $lang = 'en';

			foreach($arr_excl_locs as $l_idx => $l_det){
				if((!$lang ? MY_Controller::$CONFIG['lang_param'] : $lang) == strtolower($l_det)){
					$arr_locs_text_list = $_data->dumptoarray(intval($l_idx));
					break;
				}
			}

			if($filename == 'country-list-locs.xls'){
				foreach($arr_locs_text_list as $keyaltl=>$altl){
					if($altl['1'] == false && $altl['2'] == false){
						unset($arr_locs_text_list[$keyaltl]);
					}
				}
			}
			return $arr_locs_text_list;
		}
		elseif($type == 'load'){
			require_once(APPPATH.'third_party/OldMinisite/includes/classes/PHPExcel/IOFactory.php');
			$objPHPExcel = PHPExcel_IOFactory::load($_PATH);
			return $objPHPExcel;
		}
		/* End Here */
	}

	/**
	 * IT IS USE TO SCAN FILES FOR NEW LOCALIZATION
	 * @author carl louis manuel
	 */

	private $directory;
	//Pattern to match
//	private $pattern = '/(__|_e|->translate)\((\'|\")(.+?)(\'|\")\)/';
	private $pattern = '/(->translate)\((\'|\")(.+?)(\'|\")\)/';

	public function scan_dir($directory = false){

		//Default scan the curnt directory, accept string as directory path or array or directories
		//Directory path mast end with '/'
		$this->directory =
			array(APPPATH.'modules/'.$this->CI->router->module.'/controllers/',
				APPPATH.'modules/'.$this->CI->router->module.'/views/');

		if (!$directory)
			$directory = $this->directory;

		$lines = array();

		if (is_array($directory)) {
			foreach ($directory as $k => $dir) {
				$sub_lines = $this->scan_dir($dir);
				$lines = array_merge($lines, $sub_lines);
			}
			return $lines;
		}
		if (!is_dir($directory))
			return false;

		$handle = opendir($directory);
		if ($handle) {
			//Get every file or sub directory in the defined directory
			while (false !== ($file = readdir($handle))) {
				if ($file == "." || $file == "..")
					continue;
				//echo "<br><br>" . $file . "<br>";
				$file = $directory . $file;
				//If sub directory call this function recursively
				if (is_dir($file)) {
					$sub_lines = $this->scan_dir($file . '/');
					$lines = array_merge($lines, $sub_lines);
				} else {
					$file_lines = $this->parse_file($file);
					if ($file_lines)
						$lines = array_merge($lines, $file_lines);
				}
			}
			closedir($handle);
		}
		//Removes duplicate values from an array
//		return array_unique($lines);
		return ($lines);
	}

	//parse file to get lines
	function parse_file($file = false) {
		if (!$file || !is_file($file))
			return false;

		$lines = array();
		//Open the file
		$fh = fopen($file, 'r') or die('Could not open file ' . $file);
		$i = 1;
		while (!feof($fh)) {
			// read each line and trim off leading/trailing whitespace
			if ($s = trim(fgets($fh, 16384))) {
				// match the line to the pattern
				if (preg_match_all($this->pattern, $s, $matches)) {
					//$matches[0] -> full pattern
					//$matches[1] -> method __ OR _e
					//$matches[2] -> ' OR "
					//$matches[3] -> array ('text1', 'text2')
					//$matches[4] -> ' OR "
					if (!isset($matches[3]))
						continue;
					//Add the lines without duplicate values
					foreach ($matches[3] as $k => $text) {
						if (!in_array($text, $lines))
							$lines[] = array(
								'string'=>$text,
								'file'=>str_replace($this->directory,'',$file).':'.$i
							);
					}
				} else {
					// complain if the line didn't match the pattern
					error_log("Can't parse $file line $i: $s");
				}
			}
			$i++;
		}
		fclose($fh) or die('Could not close file ' . $file);
		return $lines;
	}
}
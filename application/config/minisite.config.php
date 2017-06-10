<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI = & get_instance();
/**
| MINISITE CONFIG
|
| ----------------------------------------------------------------------------
| Let's have one array container for all arrays for minisites. $config['mc']['etc'] = array() | str | obj
 */

define ('DEBUG', (ENVIRONMENT !== 'production'));
$config['mc']['config']['DEBUG'] = DEBUG;

/**
 * FOR BACKEND LOGIN
 */
define('MAINTENANCE_USERS',serialize(array(array('uname'=>'gladmin','upass'=>'gl@dm1n1!'),array('uname'=>'root','upass'=>''))));

/**
 * // NEW //
 * MINISITE_ID - AUTOMATIC CONVERT THE $config['aConfig']['main']['id'] IF THIS IS SET
 * SITE_TITLE - AUTOMATIC CONVERT THE $config['FB_TITLE_NAME'] IF THIS IS SET
 */
$config['mc']['config']['SITE_TITLE'] = false;
$config['mc']['config']['MINISITE_ID'] = false;

//define('SECRET_WORD','dmk-pre-reg');
$config['mc']['config']['SECRET_WORD'] = false;
//define('SHORT_URL','http://gmloft.co/dmk');
$config['mc']['config']['SHORT_URL'] = false;

//DEFINE HERE THE VERSION OF FB SCRIPT
$config['mc']['config']['FB_SCRIPT_VERSION'] = '2.5';

$config['mc']['config']['FB_LINK'] = 'https://www.facebook.com/';
$config['mc']['config']['TW_LINK'] = 'https://twitter.com/';
$config['mc']['config']['YT_LINK'] = 'https://www.youtube.com/';

/**
 * LOCALIZATION TO BE USE
 */
$config['mc']['config']['LOCALIZATION_TYPE'] = 'EN';
if(defined('LOCALIZATION_USE_MO')) {
    $config['mc']['config']['LOCALIZATION_TYPE'] = LOCALIZATION_USE_MO;
}


/**
 * LANG PARAMETERS DETECTION
 */
//if($CI->input->get('lang',true)){
//    $lang_param = strip_tags(trim(strtolower(xss_clean($CI->input->get('lang',true)))));
//} else {
//    $lang_param = 'en';
//}
$config['mc']['locs']['lang_param'] = 'en';


$config['mc']['config']['URL_PANDORA'] = '';
/**
 * This will automatically fill.
 */
$config['mc']['config']['URL_STATIC_CONTENT'] = '';
$config['mc']['config']['URL_DINAMIC_CONTENT'] = '';

$config['mc']['config']['FB_APP_ID'] = '';
$config['mc']['config']['FB_APP_SECRET'] = '';
$config['mc']['config']['FB_TITLE_NAME'] = '';




$config['mc']['config']['SUPPORT_ACCOUNTS'] = false;

$config['mc']['config']['SUPPORTED_LANGS'] = false;

if ( ! isset ($sPath) )
{
    $sPath = dirname (__FILE__).'/';
}

$config['mc']['config']['aConfig'] = array(
    'paths' => array
    (
        'fs_common_files' => $sPath.'../../includes',
        'fs_base_website' => false,
        'media_relative_path' => 'web_mkt/minisites/hoc-pre-reg/',
        'fs_texts_files' => $sPath.'texts/%%lang%%.php'
    ),
    'main' => array
    (
        'product_id' => false,
        'operation_id' => false,
        'description_group' => false,
        // default: USA = 223,
        'country_id' => false,
        // default: American English = 6
        'language_id' => false,
        'enable_redirection_by_user_agent' => false,
        'enable_debug' => false,
        'site_description' => false,
        'adid' => false,
        'style' => false,
        'id' => false
        //'platforms' => 'mobile'
    ),
    'mails' => array
    (
        // Comma separated email addresses
        'support_account' => false
    ),
    'localizations' => array
    (
        'options' => array
        (
            'es' => array ( 'country_id' => 195, 'language_id' => 4, 'language_iso2' => 'es', 'country_name' => 'Espa&ntilde;a'),
            'it' => array (	'country_id' => 105, 'language_id' => 5, 'language_iso2' => 'it', 'country_name' => 'Italia'),
            'us' => array ( 'country_id' => 223, 'language_id' => 6, 'language_iso2' => 'us', 'country_name' => 'United States'),
        ),
        'variable' => 'lang',
        'default' => 'us'
    ),

    'user_agents' => array
    (
        'dont_redirect' => array ( 4402, 3126 )
    ),
    'gl_overlay_hide' => array
    (
        'facebook' => true,
        'twitter' => true,
        'youtube' => true,
        'news' => true,
        'glive' => true,
        'relatedGames' => true
    )
);
$config['mc']['locs']['localeVar'] = array(
    'en' => array(
        'fb_locale' => 'en_US',
        'tw_locale' => 'en',
    ),
    'ko' => array(
        'fb_locale' => 'ko_KR',
        'tw_locale' => 'ko',
    ),
    'ja' => array(
        'fb_locale' => 'ja_JP',
        'tw_locale' => 'ja',
    ),
	'jp' => array(
        'fb_locale' => 'ja_JP',
        'tw_locale' => 'ja',
    ),
    'fr' => array(
        'fb_locale' => 'fr_FR',
        'tw_locale' => 'fr',
    ),
    'sp' => array(
        'fb_locale' => 'es_ES',
        'tw_locale' => 'es',
    ),
    'sp-latam' => array(
        'fb_locale' => 'es_LA',
        'tw_locale' => 'es',
    ),
    'ar' => array(
        'fb_locale' => 'ar_AR',
        'tw_locale' => 'ar',
    ),
    'sa' => array(
        'fb_locale' => 'ar_AR',
        'tw_locale' => 'ar',
    ),
    'ae' => array(
        'fb_locale' => 'ar_AR',
        'tw_locale' => 'ar',
    ),
    'de' => array(
        'fb_locale' => 'de_DE',
        'tw_locale' => 'nl',
    ),
    'it' => array(
        'fb_locale' => 'it_IT',
        'tw_locale' => 'it',
    ),
    'ru' => array(
        'fb_locale' => 'ru_RU',
        'tw_locale' => 'ru',
    ),
    'br' => array(
        'fb_locale' => 'pt_BR',
        'tw_locale' => 'pt',
    ),
    'id' => array(
        'fb_locale' => 'id_ID',
        'tw_locale' => 'id',
    ),
    'es' => array(
        'fb_locale' => 'es_ES',
        'tw_locale' => 'es',
    ),
    'th' => array(
        'fb_locale' => 'th_TH',
        'tw_locale' => 'th',
    ),
    'tr' => array(
        'fb_locale' => 'tr_TR',
        'tw_locale' => 'tr',
    ),
    'vn' => array(
        'fb_locale' => 'vi_VN',
        'tw_locale' => 'vn',
    ),
    'cn' => array(
        'fb_locale' => 'zh_ZH',
        'tw_locale' => 'zh',
    ),
    'tc' => array(
        'fb_locale' => 'zh_ZH',
        'tw_locale' => 'zh',
    ),
	'zh' => array(
        'fb_locale' => 'zh_ZH',
        'tw_locale' => 'zh',
    ),
);

$config['mc']['locs']['countries_flags'] = array(
    'ar'=>array(
        array('title'=>'???????','code'=>'ar'),
        array('title'=>'??????????','code'=>'br'),
        array('title'=>'???????','code'=>'zh'),
        array('title'=>'?????????','code'=>'de'),
        array('title'=>'?????????? (??????)','code'=>'en'),
        array('title'=>'????????','code'=>'fr'),
        array('title'=>'???????????','code'=>'id'),
        array('title'=>'?????????','code'=>'it'),
        array('title'=>'?????????','code'=>'jp'),
        array('title'=>'???????','code'=>'kr'),
        array('title'=>'???????','code'=>'ru'),
        array('title'=>'?????????','code'=>'es'),
        array('title'=>'???????????','code'=>'th'),
        array('title'=>'???????','code'=>'tr'),
        array('title'=>'???????????','code'=>'vn'),
    ),
    'br'=>array(
        array('title'=>'�rabe','code'=>'ar'),
        array('title'=>'Portugu�s brasileiro','code'=>'br'),
        array('title'=>'Chin�s simplificado','code'=>'zh'),
        array('title'=>'Alem�o','code'=>'de'),
        array('title'=>'Ingl�s (Reino Unido)','code'=>'en'),
        array('title'=>'Franc�s','code'=>'fr'),
        array('title'=>'Indon�sio','code'=>'id'),
        array('title'=>'Italiano','code'=>'it'),
        array('title'=>'Japon�s','code'=>'jp'),
        array('title'=>'Coreano','code'=>'kr'),
        array('title'=>'Russo','code'=>'ru'),
        array('title'=>'Espanhol','code'=>'es'),
        array('title'=>'Tailand�s','code'=>'th'),
        array('title'=>'Turco','code'=>'tr'),
        array('title'=>'Vietnamita','code'=>'vn'),
    ),
    'de'=>array(
        array('title'=>'Arabisch','code'=>'ar'),
        array('title'=>'Portugiesisch (Brasilien)','code'=>'br'),
        array('title'=>'Chinesisch (Kurzzeichen)','code'=>'zh'),
        array('title'=>'Deutsch','code'=>'de'),
        array('title'=>'Englisch (USA)','code'=>'en'),
        array('title'=>'Franz�sisch','code'=>'fr'),
        array('title'=>'Indonesisch','code'=>'id'),
        array('title'=>'Italienisch','code'=>'it'),
        array('title'=>'Japanisch','code'=>'jp'),
        array('title'=>'Koreanisch','code'=>'kr'),
        array('title'=>'Russisch','code'=>'ru'),
        array('title'=>'Spanisch','code'=>'es'),
        array('title'=>'Thai','code'=>'th'),
        array('title'=>'T�rkisch','code'=>'tr'),
        array('title'=>'Vietnamesisch','code'=>'vn'),
    ),
    'en'=>array(
        array('title'=>'Arabic','code'=>'ar'),
        array('title'=>'Brazilian Portuguese','code'=>'br'),
        array('title'=>'Simplified Chinese','code'=>'zh'),
        array('title'=>'German','code'=>'de'),
        array('title'=>'English (US)','code'=>'en'),
        array('title'=>'French','code'=>'fr'),
        array('title'=>'Indonesian','code'=>'id'),
        array('title'=>'Italian','code'=>'it'),
        array('title'=>'Japanese','code'=>'jp'),
        array('title'=>'Korean','code'=>'kr'),
        array('title'=>'Spanish','code'=>'es'),
        array('title'=>'Thai','code'=>'th'),
        array('title'=>'Turkish','code'=>'tr'),
        array('title'=>'Vietnamese','code'=>'vn'),
        array('title'=>'Russian','code'=>'ru'),
    ),
    'fr'=>array(
        array('title'=>'Arabe','code'=>'ar'),
        array('title'=>'Portugais br�silien','code'=>'br'),
        array('title'=>'Chinois simplifi�','code'=>'zh'),
        array('title'=>'Allemand','code'=>'de'),
        array('title'=>'Anglais (US)','code'=>'en'),
        array('title'=>'Fran�ais','code'=>'fr'),
        array('title'=>'Indon�sien','code'=>'id'),
        array('title'=>'Italien','code'=>'it'),
        array('title'=>'Japonais','code'=>'jp'),
        array('title'=>'Cor�en','code'=>'kr'),
        array('title'=>'Russe','code'=>'ru'),
        array('title'=>'Espagnol','code'=>'es'),
        array('title'=>'Tha�','code'=>'th'),
        array('title'=>'Turque','code'=>'tr'),
        array('title'=>'Vietnamien','code'=>'vn'),
    ),
    'id'=>array(
        array('title'=>'Arab','code'=>'ar'),
        array('title'=>'Portugis Brasilia','code'=>'br'),
        array('title'=>'Tionghoa Sederhana','code'=>'zh'),
        array('title'=>'Jerman','code'=>'de'),
        array('title'=>'Inggris (Amerika Serikat)','code'=>'en'),
        array('title'=>'Perancis','code'=>'fr'),
        array('title'=>'Indonesia','code'=>'id'),
        array('title'=>'Italia','code'=>'it'),
        array('title'=>'Jepang','code'=>'jp'),
        array('title'=>'Korea','code'=>'kr'),
        array('title'=>'Russe','code'=>'ru'),
        array('title'=>'Spanyol','code'=>'es'),
        array('title'=>'Thailand','code'=>'th'),
        array('title'=>'Turki','code'=>'tr'),
        array('title'=>'Vietnam','code'=>'vn'),
    ),
    'it'=>array(
        array('title'=>'Arabo','code'=>'ar'),
        array('title'=>'Portoghese brasiliano','code'=>'br'),
        array('title'=>'Cinese semplificato','code'=>'zh'),
        array('title'=>'Tedesco','code'=>'de'),
        array('title'=>'Inglese (Stati Uniti)','code'=>'en'),
        array('title'=>'Francese','code'=>'fr'),
        array('title'=>'Indonesiano','code'=>'id'),
        array('title'=>'Italiano','code'=>'it'),
        array('title'=>'Giapponese','code'=>'jp'),
        array('title'=>'Coreano','code'=>'kr'),
        array('title'=>'Russo','code'=>'ru'),
        array('title'=>'Spagnolo','code'=>'es'),
        array('title'=>'Thailandese','code'=>'th'),
        array('title'=>'Turco','code'=>'tr'),
        array('title'=>'Vietnamita','code'=>'vn'),
    ),
    'jp'=>array(
        array('title'=>'?????','code'=>'ar'),
        array('title'=>'????????????','code'=>'br'),
        array('title'=>'??????','code'=>'zh'),
        array('title'=>'????','code'=>'de'),
        array('title'=>'????????','code'=>'en'),
        array('title'=>'?????','code'=>'fr'),
        array('title'=>'???????','code'=>'id'),
        array('title'=>'?????','code'=>'it'),
        array('title'=>'???','code'=>'jp'),
        array('title'=>'???','code'=>'kr'),
        array('title'=>'????','code'=>'ru'),
        array('title'=>'?????','code'=>'es'),
        array('title'=>'???','code'=>'th'),
        array('title'=>'????','code'=>'tr'),
        array('title'=>'?????','code'=>'vn'),
    ),
    'kr'=>array(
        array('title'=>'???','code'=>'ar'),
        array('title'=>'?????(???)','code'=>'br'),
        array('title'=>'???(??)','code'=>'zh'),
        array('title'=>'???','code'=>'de'),
        array('title'=>'??(??)','code'=>'en'),
        array('title'=>'????','code'=>'fr'),
        array('title'=>'??????','code'=>'id'),
        array('title'=>'?????','code'=>'it'),
        array('title'=>'???','code'=>'jp'),
        array('title'=>'???','code'=>'kr'),
        array('title'=>'????','code'=>'ru'),
        array('title'=>'????','code'=>'es'),
        array('title'=>'???','code'=>'th'),
        array('title'=>'???','code'=>'tr'),
        array('title'=>'????','code'=>'vn'),
    ),
    'es'=>array(
        array('title'=>'�rabe','code'=>'ar'),
        array('title'=>'Portugu�s (Brasil)','code'=>'br'),
        array('title'=>'Chino simplificado','code'=>'zh'),
        array('title'=>'Alem�n','code'=>'de'),
        array('title'=>'Ingl�s (EE. UU.)','code'=>'en'),
        array('title'=>'Franc�s','code'=>'fr'),
        array('title'=>'Indonesio','code'=>'id'),
        array('title'=>'Italiano','code'=>'it'),
        array('title'=>'Japon�s','code'=>'jp'),
        array('title'=>'Coreano','code'=>'kr'),
        array('title'=>'Ruso','code'=>'ru'),
        array('title'=>'Espa�ol','code'=>'es'),
        array('title'=>'Tailand�s','code'=>'th'),
        array('title'=>'Turco','code'=>'tr'),
        array('title'=>'Vietnamita','code'=>'vn'),
    ),
    'th'=>array(
        array('title'=>'??????','code'=>'ar'),
        array('title'=>'???????? (??????)','code'=>'br'),
        array('title'=>'?????????','code'=>'zh'),
        array('title'=>'???????','code'=>'de'),
        array('title'=>'?????? (????????????)','code'=>'en'),
        array('title'=>'????????','code'=>'fr'),
        array('title'=>'???????????','code'=>'id'),
        array('title'=>'??????','code'=>'it'),
        array('title'=>'???????','code'=>'jp'),
        array('title'=>'??????','code'=>'kr'),
        array('title'=>'???????','code'=>'ru'),
        array('title'=>'????','code'=>'es'),
        array('title'=>'???','code'=>'th'),
        array('title'=>'?????','code'=>'tr'),
        array('title'=>'????????','code'=>'vn'),
    ),
    'tr'=>array(
        array('title'=>'Arap�a','code'=>'ar'),
        array('title'=>'Brezilya Portekizcesi','code'=>'br'),
        array('title'=>'Basitle?tirilmi? �ince','code'=>'zh'),
        array('title'=>'Almanca','code'=>'de'),
        array('title'=>'?ngilizce (ABD)','code'=>'en'),
        array('title'=>'Frans?zca','code'=>'fr'),
        array('title'=>'Endonezce','code'=>'id'),
        array('title'=>'?talyanca','code'=>'it'),
        array('title'=>'Japonca','code'=>'jp'),
        array('title'=>'Korece','code'=>'kr'),
        array('title'=>'Rus�a','code'=>'ru'),
        array('title'=>'?spanyolca','code'=>'es'),
        array('title'=>'Tayca','code'=>'th'),
        array('title'=>'T�rk�e','code'=>'tr'),
        array('title'=>'Vietnamca','code'=>'vn'),
    ),
    'vn'=>array(
        array('title'=>'Ti?ng ?-r?p','code'=>'ar'),
        array('title'=>'Ti?ng B? ?�o Nha - Braxin','code'=>'br'),
        array('title'=>'Ti?ng Trung Gi?n th?','code'=>'zh'),
        array('title'=>'Ti?ng ??c','code'=>'de'),
        array('title'=>'Ti?ng Anh (M?)','code'=>'en'),
        array('title'=>'Ti?ng Ph�p','code'=>'fr'),
        array('title'=>'Ti?ng In-?�-n�-xi-a','code'=>'id'),
        array('title'=>'Ti?ng �','code'=>'it'),
        array('title'=>'Ti?ng Nh?t','code'=>'jp'),
        array('title'=>'Ti?ng H�n','code'=>'kr'),
        array('title'=>'Ti?ng Nga','code'=>'ru'),
        array('title'=>'Ti?ng T�y Ban Nha','code'=>'es'),
        array('title'=>'Ti?ng Th�i','code'=>'th'),
        array('title'=>'Ti?ng Th? Nh? K?','code'=>'tr'),
        array('title'=>'Ti?ng Vi?t','code'=>'vn'),
    ),
    'zh'=>array(
        array('title'=>'????','code'=>'ar'),
        array('title'=>'??????','code'=>'br'),
        array('title'=>'????','code'=>'zh'),
        array('title'=>'??','code'=>'de'),
        array('title'=>'??????','code'=>'en'),
        array('title'=>'??','code'=>'fr'),
        array('title'=>'???','code'=>'id'),
        array('title'=>'????','code'=>'it'),
        array('title'=>'??','code'=>'jp'),
        array('title'=>'??','code'=>'kr'),
        array('title'=>'??','code'=>'ru'),
        array('title'=>'????','code'=>'es'),
        array('title'=>'??','code'=>'th'),
        array('title'=>'????','code'=>'tr'),
        array('title'=>'???','code'=>'vn'),
    ),
    'ru'=>array(
        array('title'=>'????????','code'=>'ar'),
        array('title'=>'??????????? ?????????????','code'=>'br'),
        array('title'=>'????????? (??????????)','code'=>'zh'),
        array('title'=>'????????','code'=>'de'),
        array('title'=>'?????????? (???)','code'=>'en'),
        array('title'=>'???????????','code'=>'fr'),
        array('title'=>'?????????????','code'=>'id'),
        array('title'=>'???????????','code'=>'it'),
        array('title'=>'????????','code'=>'jp'),
        array('title'=>'?????????','code'=>'kr'),
        array('title'=>'???????','code'=>'ru'),
        array('title'=>'?????????','code'=>'es'),
        array('title'=>'???????','code'=>'th'),
        array('title'=>'????????','code'=>'tr'),
        array('title'=>'???????????','code'=>'vn'),
    ));

$config['mc']['locs']['arr_c_list'] = array(
    'Afghanistan'=>'Afghanistan',
    'Aaland Islands'=>'Aaland Islands',
    'Albania'=>'Albania',
    'Algeria'=>'Algeria',
    'American Samoa'=>'American Samoa',
    'Andorra'=>'Andorra',
    'Angola'=>'Angola',
    'Anguilla'=>'Anguilla',
    'Antarctica'=>'Antarctica',
    'Antigua And Barbuda'=>'Antigua And Barbuda',
    'Argentina'=>'Argentina',
    'Armenia'=>'Armenia',
    'Aruba'=>'Aruba',
    'Australia'=>'Australia',
    'Austria'=>'Austria',
    'Azerbaijan'=>'Azerbaijan',
    'Bahamas'=>'Bahamas',
    'Bahrain'=>'Bahrain',
    'Bangladesh'=>'Bangladesh',
    'Barbados'=>'Barbados',
    'Belarus'=>'Belarus',
    'Belgium'=>'Belgium',
    'Belize'=>'Belize',
    'Benin'=>'Benin',
    'Bermuda'=>'Bermuda',
    'Bhutan'=>'Bhutan',
    'Bolivia'=>'Bolivia',
    'Bosnia and Herzegovina'=>'Bosnia and Herzegovina',
    'Botswana'=>'Botswana',
    'Bouvet Island'=>'Bouvet Island',
    'Brazil'=>'Brazil',
    'British Indian Ocean Territory'=>'British Indian Ocean Territory',
    'Brunei Darussalam'=>'Brunei Darussalam',
    'Bulgaria'=>'Bulgaria',
    'Burkina Faso'=>'Burkina Faso',
    'Burundi'=>'Burundi',
    'Cambodia'=>'Cambodia',
    'Cameroon'=>'Cameroon',
    'Canada'=>'Canada',
    'Cape Verde'=>'Cape Verde',
    'Cayman Islands'=>'Cayman Islands',
    'Central African Republic'=>'Central African Republic',
    'Chad'=>'Chad',
    'Chile'=>'Chile',
    'China'=>'China',
    'Christmas Island'=>'Christmas Island',
    'Cocos (Keeling) Islands'=>'Cocos (Keeling) Islands',
    'Colombia'=>'Colombia',
    'Comoros'=>'Comoros',
    'Congo'=>'Congo',
    'Cook Islands'=>'Cook Islands',
    'Costa Rica'=>'Costa Rica',
    'Cote D\'Ivoire'=>'Cote D\'Ivoire',
    'Croatia'=>'Croatia',
    'Cuba'=>'Cuba',
    'Curacao'=>'Curacao',
    'Cyprus'=>'Cyprus',
    'Czech Republic'=>'Czech Republic',
    'Democratic Republic of the Congo'=>'Democratic Republic of the Congo',
    'Denmark'=>'Denmark',
    'Djibouti'=>'Djibouti',
    'Dominica'=>'Dominica',
    'Dominican Republic'=>'Dominican Republic',
    'Ecuador'=>'Ecuador',
    'Egypt'=>'Egypt',
    'El Salvador'=>'El Salvador',
    'Equatorial Guinea'=>'Equatorial Guinea',
    'Eritrea'=>'Eritrea',
    'Estonia'=>'Estonia',
    'Ethiopia'=>'Ethiopia',
    'Falkland Islands'=>'Falkland Islands',
    'Faroe Islands'=>'Faroe Islands',
    'Fiji'=>'Fiji',
    'Finland'=>'Finland',
    'France'=>'France',
    'French Guiana'=>'French Guiana',
    'French Polynesia'=>'French Polynesia',
    'French Southern Territories'=>'French Southern Territories',
    'Gabon'=>'Gabon',
    'Gambia'=>'Gambia',
    'Georgia'=>'Georgia',
    'Germany'=>'Germany',
    'Ghana'=>'Ghana',
    'Gibraltar'=>'Gibraltar',
    'Greece'=>'Greece',
    'Greenland'=>'Greenland',
    'Grenada'=>'Grenada',
    'Guadeloupe'=>'Guadeloupe',
    'Guam'=>'Guam',
    'Guatemala'=>'Guatemala',
    'Guernsey'=>'Guernsey',
    'Guinea'=>'Guinea',
    'Guinea-Bissau'=>'Guinea-Bissau',
    'Guyana'=>'Guyana',
    'Haiti'=>'Haiti',
    'Heard and Mc Donald Islands'=>'Heard and Mc Donald Islands',
    'Honduras'=>'Honduras',
    'Hong Kong'=>'Hong Kong',
    'Hungary'=>'Hungary',
    'Iceland'=>'Iceland',
    'India'=>'India',
    'Indonesia'=>'Indonesia',
    'Iran'=>'Iran',
    'Iraq'=>'Iraq',
    'Ireland'=>'Ireland',
    'Isle of Man'=>'Isle of Man',
    'Israel'=>'Israel',
    'Italy'=>'Italy',
    'Jamaica'=>'Jamaica',
    'Japan'=>'Japan',
    'Jersey  (Channel Islands)'=>'Jersey  (Channel Islands)',
    'Jordan'=>'Jordan',
    'Kazakhstan'=>'Kazakhstan',
    'Kenya'=>'Kenya',
    'Kiribati'=>'Kiribati',
    'Kuwait'=>'Kuwait',
    'Kyrgyzstan'=>'Kyrgyzstan',
    'Lao People\'s Democratic Republic'=>'Lao People\'s Democratic Republic',
    'Latvia'=>'Latvia',
    'Lebanon'=>'Lebanon',
    'Lesotho'=>'Lesotho',
    'Liberia'=>'Liberia',
    'Libya'=>'Libya',
    'Liechtenstein'=>'Liechtenstein',
    'Lithuania'=>'Lithuania',
    'Luxembourg'=>'Luxembourg',
    'Macau'=>'Macau',
    'Macedonia'=>'Macedonia',
    'Madagascar'=>'Madagascar',
    'Malawi'=>'Malawi',
    'Malaysia'=>'Malaysia',
    'Maldives'=>'Maldives',
    'Mali'=>'Mali',
    'Malta'=>'Malta',
    'Marshall Islands'=>'Marshall Islands',
    'Martinique'=>'Martinique',
    'Mauritania'=>'Mauritania',
    'Mauritius'=>'Mauritius',
    'Mayotte'=>'Mayotte',
    'Mexico'=>'Mexico',
    'Micronesia, Federated States of'=>'Micronesia, Federated States of',
    'Moldova, Republic of'=>'Moldova, Republic of',
    'Monaco'=>'Monaco',
    'Mongolia'=>'Mongolia',
    'Montenegro'=>'Montenegro',
    'Montserrat'=>'Montserrat',
    'Morocco'=>'Morocco',
    'Mozambique'=>'Mozambique',
    'Myanmar'=>'Myanmar',
    'Namibia'=>'Namibia',
    'Nauru'=>'Nauru',
    'Nepal'=>'Nepal',
    'Netherlands'=>'Netherlands',
    'Netherlands Antilles'=>'Netherlands Antilles',
    'New Caledonia'=>'New Caledonia',
    'New Zealand'=>'New Zealand',
    'Nicaragua'=>'Nicaragua',
    'Niger'=>'Niger',
    'Nigeria'=>'Nigeria',
    'Niue'=>'Niue',
    'Norfolk Island'=>'Norfolk Island',
    'North Korea'=>'North Korea',
    'Northern Mariana Islands'=>'Northern Mariana Islands',
    'Norway'=>'Norway',
    'Oman'=>'Oman',
    'Pakistan'=>'Pakistan',
    'Palau'=>'Palau',
    'Palestine'=>'Palestine',
    'Panama'=>'Panama',
    'Papua New Guinea'=>'Papua New Guinea',
    'Paraguay'=>'Paraguay',
    'Peru'=>'Peru',
    'Philippines'=>'Philippines',
    'Pitcairn'=>'Pitcairn',
    'Poland'=>'Poland',
    'Portugal'=>'Portugal',
    'Puerto Rico'=>'Puerto Rico',
    'Qatar'=>'Qatar',
    'Republic of Kosovo'=>'Republic of Kosovo',
    'Reunion'=>'Reunion',
    'Romania'=>'Romania',
    'Russia'=>'Russia',
    'Rwanda'=>'Rwanda',
    'Saint Kitts and Nevis'=>'Saint Kitts and Nevis',
    'Saint Lucia'=>'Saint Lucia',
    'Saint Vincent and the Grenadines'=>'Saint Vincent and the Grenadines',
    'Samoa (Independent)'=>'Samoa (Independent)',
    'San Marino'=>'San Marino',
    'Sao Tome and Principe'=>'Sao Tome and Principe',
    'Saudi Arabia'=>'Saudi Arabia',
    'Senegal'=>'Senegal',
    'Serbia'=>'Serbia',
    'Seychelles'=>'Seychelles',
    'Sierra Leone'=>'Sierra Leone',
    'Singapore'=>'Singapore',
    'Sint Maarten'=>'Sint Maarten',
    'Slovakia'=>'Slovakia',
    'Slovenia'=>'Slovenia',
    'Solomon Islands'=>'Solomon Islands',
    'Somalia'=>'Somalia',
    'South Africa'=>'South Africa',
    'South Georgia and the South Sandwich Islands'=>'South Georgia and the South Sandwich Islands',
    'South Korea'=>'South Korea',
    'South Sudan'=>'South Sudan',
    'Spain'=>'Spain',
    'Sri Lanka'=>'Sri Lanka',
    'St. Helena'=>'St. Helena',
    'St. Pierre and Miquelon'=>'St. Pierre and Miquelon',
    'Sudan'=>'Sudan',
    'Suriname'=>'Suriname',
    'Svalbard and Jan Mayen Islands'=>'Svalbard and Jan Mayen Islands',
    'Swaziland'=>'Swaziland',
    'Sweden'=>'Sweden',
    'Switzerland'=>'Switzerland',
    'Syria'=>'Syria',
    'Taiwan'=>'Taiwan',
    'Tajikistan'=>'Tajikistan',
    'Tanzania'=>'Tanzania',
    'Thailand'=>'Thailand',
    'Timor-Leste'=>'Timor-Leste',
    'Togo'=>'Togo',
    'Tokelau'=>'Tokelau',
    'Tonga'=>'Tonga',
    'Trinidad and Tobago'=>'Trinidad and Tobago',
    'Tunisia'=>'Tunisia',
    'Turkey'=>'Turkey',
    'Turkmenistan'=>'Turkmenistan',
    'Turks '=>'Turks ',
    'Turks and Caicos Islands'=>'Turks and Caicos Islands',
    'Tuvalu'=>'Tuvalu',
    'Uganda'=>'Uganda',
    'Ukraine'=>'Ukraine',
    'United Arab Emirates'=>'United Arab Emirates',
    'United Kingdom'=>'United Kingdom',
    'Uruguay'=>'Uruguay',
    'United States of America'=>'United States of America',
    'USA Minor Outlying Islands'=>'USA Minor Outlying Islands',
    'Uzbekistan'=>'Uzbekistan',
    'Vanuatu'=>'Vanuatu',
    'Vatican City State (Holy See)'=>'Vatican City State (Holy See)',
    'Venezuela'=>'Venezuela',
    'Vietnam'=>'Vietnam',
    'Virgin Islands (British)'=>'Virgin Islands (British)',
    'Virgin Islands (U.S.)'=>'Virgin Islands (U.S.)',
    'Wallis and Futuna Islands'=>'Wallis and Futuna Islands',
    'Western Sahara'=>'Western Sahara',
    'Yemen'=>'Yemen',
    'Zambia'=>'Zambia',
    'Zimbabwe'=>'Zimbabwe',


);
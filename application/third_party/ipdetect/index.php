<?php
	include_once ('classes/geoip/geoip.class.php');
	include_once ('classes/Mobile-Detect-2.8.19/Mobile_Detect.php');
	$geoip = new geoIp;
	$detect  = new Mobile_Detect;

	echo 'IP? '.(filter_input(INPUT_GET, 'ip', FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE)?$_GET['ip']:$geoip->getIPAddress());
	echo '<br/>';
	echo 'Region? '.(filter_input(INPUT_GET, 'ip', FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE)?$geoip->getCountryFromIp($_GET['ip']):$geoip->getCountryFromIp());
	echo '<br/>';
	echo 'Device? '.($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	echo '<br/>';
    foreach($detect->getRules() as $name => $regex)
    {
		$check = $detect->{'is'.$name}();
        echo 'is'.$name.'()? '; var_dump($check); echo '<br/>';
    }
    foreach($detect->getProperties() as $name => $match)
    {
        $check = $detect->version($name);
        if($check!==false)
        {
	        echo 'version('.$name.')? '; var_dump($check); echo '<br/>';
        }
	}
<?php

	class ipwrapper {

		public function __construct($aLocalConfiguration=null)
		{
			//TO TEST IPADDRESS
			if(isset($_GET['ipaddress'])){
				$sIp = $_GET['ipaddress'];
				$_SERVER['HTTP_X_FORWARDED_FOR'] = $sIp;
				$_SERVER['REMOTE_ADDR'] = $sIp;
			}
			if(isset($_GET['showipaddress'])){
				var_dump($this->getCountryFromIp());
				var_dump($this->getIPAddress());
			}
			
			$aAllowedIPs = array
			(
			); 
			if ( !defined('IPWRAPPER_ALLOWED_BY_IP') && array_key_exists($this->getIPAddress(), $aAllowedIPs) )
		        define('IPWRAPPER_ALLOWED_BY_IP', $aAllowedIPs[$this->getIPAddress()]);
		    else 
		    	define('IPWRAPPER_ALLOWED_BY_IP', false);
		}

		/**
		* Remove extra data from the string with the IP address
		*
		* @param string $sIp as it's retrieved.
		* @return string
		*/
		protected function _getCleanedIp ($sIp)
		{
			$aAux = explode (',', $sIp);
			$iCount = count ($aAux);
			if ( $iCount == 1 )
			{
				return trim($sIp);
			}
			else
			{
		/*
		Private IP address ranges
		From: http://whatismyipaddress.com/private-ip

		The ranges and the amount of usable IP's are as follows:

		10.0.0.0 - 10.255.255.255
		Addresses: 16,777,216

		172.16.0.0 - 172.31.255.255
		Addresses: 1,048,576

		192.168.0.0 - 192.168.255.255
		Addresses: 65,536
		*/
				$bValidIp = false;
				$index = 0;
				do
				{ 
					$aAux [$index] = trim ($aAux [$index]);
					$aAux2 = explode ('.', $aAux[$index]);
					$bValidIp = strpos ( $aAux [$index], '10.') !== 0 && strpos ( $aAux [$index], '192.168.') !== 0 && ( $aAux2[0] != '172' || intval($aAux2[1]) < 16 &&  intval($aAux2[1]) > 31 );
					$index++;
				} while ( $iCount > $index && ! $bValidIp );
				$index--;

				return $bValidIp ? $aAux [$index] : $aAux [0];
			}
		}

		/**
		* This method returns the IP address even with the cache server
		*
		* @return string
		*/
		public function getIPAddress()
		{
			static $_sIp = null;
			if ( is_null ($_sIp) )
			{
				if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
				{
					$_sIp = $this->_getCleanedIp ($_SERVER['HTTP_X_FORWARDED_FOR']);				
				}
				else
				{	
					$_sIp =  $this->_getCleanedIp ($_SERVER['REMOTE_ADDR']);				
				}
			}
			return $_sIp;
		}

		/**
		* This method returns the country code (iso code 2) of the country detected with the IP. Empty string if the country isn't detected
		*
		* @return string
		*/
		public function getCountryFromIp ($sIp = null)
		{
			static $_sCountry = null;
			
			if ( is_null ($_sCountry) )
			{
				if($sIp == null) // NOTE - WILL GO CRAZY ON == '::1'
					$sIp = $this->getIPAddress();
				if($sIp == '::1') // NOTE - WILL GO CRAZY ON == '::1'
					$sIp = '123.151.42.61'; // Chinese IP workaround
				require_once (dirname(__FILE__).'/geoip.inc');
				$oGi = geoip_open (dirname(__FILE__).'/GeoIP.dat', GEOIP_STANDARD);
				// var_dump($sIp); var_dump($oGi); die();
				$sCountryCode = geoip_country_code_by_addr ($oGi, $sIp);
				geoip_close($oGi);
				if ( ! empty ($sCountryCode) )
				{
					$_sCountry = strtolower ($sCountryCode);
				}
				else
				{
					// The plan B - the old style strikes back
					$_sCountry = '';

					$url = 'ip/ip_detect.php?ip='.$sIp;
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl, CURLOPT_TIMEOUT, 10);

					$curlData = curl_exec($curl);

					if ( ! curl_errno ($curl) )
					{
						$curlData = json_decode($curlData, true);
						if ( is_array($curlData) && $curlData['name'] != '')
						{
							$_sCountry = strtolower($curlData['name']);
						}
					}
					curl_close($curl);
					
					// Store the result for future investigation
					/*
					$this->connectToDatabase ();
					mysql_query ('INSERT INTO
									`debug_ip_address`
								(
									`ip_address` ,
									`country` ,
									`timestamp`
								)
								VALUES
								(
									\''.mysql_real_escape_string  ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $sIp ).'\',
									\''.mysql_real_escape_string  ($_sCountry).'\',
									CURRENT_TIMESTAMP
								)');
					*/
				}
			}
			return $_sCountry;
		}
	}
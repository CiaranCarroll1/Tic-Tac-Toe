<?php
	session_start();
	
	$wsdl = "http://localhost:8080/TTTWebApplication/TTTWebService?WSDL";
	$trace = true;
	$exceptions = true;
	
	if(isset($_SESSION["uid"])) {
		$uid = $_SESSION["uid"];
		
		try {
			$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
			
			$xml_array['uid'] = $uid;
			$response = $proxy->showAllMyGames($xml_array);
			$val = (string) $response->return;
			echo $val;
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
?>
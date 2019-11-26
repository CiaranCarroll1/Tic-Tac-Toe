<?php
	session_start();
	
	$wsdl = "http://localhost:8080/TTTWebApplication/TTTWebService?WSDL";
	$trace = true;
	$exceptions = true;
	
	if(isset($_SESSION["gid"])) {
		$gid = $_SESSION["gid"];
		
		try {
			$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
			
			$xml_array['gid'] = $gid;
			$response = $proxy->getBoard($xml_array);
			$val = (string) $response->return;
			echo $val;
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
?>
<?php
	session_start();
	
	$wsdl = "http://localhost:8080/TTTWebApplication/TTTWebService?WSDL";
	$trace = true;
	$exceptions = true;
	
	if(isset($_SESSION["uid"])) {
		$uid = $_SESSION["uid"];
		
		try {
			$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
			
			$gid = $_SESSION["gid"];
			
			$xml_array['gid'] = $gid;
			$response = $proxy->checkWin($xml_array);
			$val = (string) $response->return;
			echo $val;
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
?>
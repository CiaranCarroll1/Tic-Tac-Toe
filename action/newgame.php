<?php
	session_start();
	
	if(isset($_SESSION["uid"])) {
		
		$wsdl = "http://localhost:8080/TTTWebApplication/TTTWebService?WSDL";
		$trace = true;
		$exceptions = true;
	
		try {
			$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
			
			$xml_array['uid'] = $_SESSION["uid"];
			$response = $proxy->newGame($xml_array);
			$val = (string) $response->return;
			$_SESSION["gid"] = $val;
			$_SESSION["pn"] = 1;

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
?>
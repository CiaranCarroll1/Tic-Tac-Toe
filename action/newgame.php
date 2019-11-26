<?php
	session_start();
	
	if(isset($_SESSION["uid"])) {
		
		include "wsdl.php";
	
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
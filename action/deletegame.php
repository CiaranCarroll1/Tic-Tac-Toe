<?php
	session_start();
	
	if(isset($_SESSION["uid"]) && isset($_SESSION["gid"])) {
	
		include "wsdl.php";
	
		try {
			$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
			
			$xml_array['gid'] = $_SESSION["gid"];
			$xml_array['uid'] = $_SESSION["uid"];
			$response = $proxy->deleteGame($xml_array);
			$val = (string) $response->return;

			unset($_SESSION["gid"]);
			
			echo $val;


		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
?>
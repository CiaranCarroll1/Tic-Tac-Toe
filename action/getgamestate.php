<?php
	session_start();
	$gid = $_POST["gid"];
	
	include "wsdl.php";

	try {
		$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
		
		$xml_array['gid'] = $gid;
		$response = $proxy->getGameState($xml_array);
		$val = (string) $response->return;
		echo $val;

	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
?>
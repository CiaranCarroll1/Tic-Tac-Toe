<?php
	
	include "wsdl.php";
		
	try {
		$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
		
		$response = $proxy->leagueTable();
		$val = (string) $response->return;
		echo $val;
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>
<?php
	$un = $_POST["uname"];
	$pw = $_POST["pword"];
	
	include "wsdl.php";
	
	try {
		$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
		
		$xml_array['username'] = $un;
		$xml_array['password'] = $pw;
		
		$response = $proxy->login($xml_array);
		$val = (string) $response->return;
		echo $val;
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>
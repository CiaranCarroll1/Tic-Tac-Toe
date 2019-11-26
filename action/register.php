<?php
	$un = $_POST["username"];
	$pw = $_POST["password"];
	$fn = $_POST["name"];
	$sn = $_POST["surname"];
	
	include "wsdl.php";
	
	try {
		$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
		
		$xml_array['username'] = $un;
		$xml_array['password'] = $pw;
		$xml_array['name'] = $fn;
		$xml_array['surname'] = $sn;
		
		$response = $proxy->register($xml_array);
		$val = (string) $response->return;
		echo $val;
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>
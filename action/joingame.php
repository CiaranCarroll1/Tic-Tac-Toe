<?php
	session_start();
	
	$gid = $_POST["gid"];
	
	include "wsdl.php";
	
	if(isset($_SESSION["uid"])) {
		$uid = $_SESSION["uid"];
		
		try {
			$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
			
			$xml_array['uid'] = $uid;
			$xml_array['gid'] = $gid;
			$response = $proxy->joinGame($xml_array);
			$val = (string) $response->return;
			
			
			$_SESSION["gid"] = $gid;
			$_SESSION["pn"] = 2;
			
			echo $val;
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
?>
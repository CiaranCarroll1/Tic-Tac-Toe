<?php
	session_start();
	$gid = $_POST["gid"];
	
	$wsdl = "http://localhost:8080/TTTWebApplication/TTTWebService?WSDL";
	$trace = true;
	$exceptions = true;

	try {
		$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
		
		$xml_array['gid'] = $gid;
		$response = $proxy->getGameState($xml_array);
		$val = (string) $response->return;
		
		switch($val)
		{
			case 'ERROR-NOGAME':
				$val = "E";
				break;
			case 'ERROR-DB':
				$val = "E";
				break;
			case -1: // Waiting for second player
				$val = -1;
				break;
			case 0: // Game in progress
				$val = 0;
				break;
			case 1: // Player one won
				$val = 1;
				break;
			case 2: // Player two won
				$val = 2;
				break;
			case 3: // Draw
				$val = 3;
				break;
		}
		
		echo $val;

	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
?>
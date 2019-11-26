<?php	
	session_start();
	
	$x = $_POST["x"];
	$y = $_POST["y"];
	
	if(isset($_SESSION["gid"])) 
	{
		$gid = $_SESSION["gid"];
		
		include "wsdl.php";
	
		try {
			$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
			
			$xml_array['x'] = $x;
			$xml_array['y'] = $y;
			$xml_array['gid'] = $gid;
			$response = $proxy->checkSquare($xml_array); //Check status of square.
			$val = (string) $response->return;
			echo $val; //Return result.

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	} else {
		header("location:main.php?err=1");
	}
			
?>
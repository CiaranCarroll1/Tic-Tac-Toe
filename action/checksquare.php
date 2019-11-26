<?php	
	session_start();
	
	$x = $_POST["x"];
	$y = $_POST["y"];
	
	if(isset($_SESSION["gid"])) 
	{
		$gid = $_SESSION["gid"];
		
		$wsdl = "http://localhost:8080/TTTWebApplication/TTTWebService?WSDL";
		$trace = true;
		$exceptions = true;
	
		try {
			$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
			
			$xml_array['x'] = $x;
			$xml_array['y'] = $y;
			$xml_array['gid'] = $gid;
			$response = $proxy->checkSquare($xml_array);
			$val = (string) $response->return;
			
			switch($val)
			{
				case 'ERROR-DB':
					$val = "error";
					break;
				case 0:
					$val = "0";
					break;
				case 1:
					$val = "1";
			}
			echo $val;

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	} else {
		header("location:main.php?err=1");
	}
			
?>
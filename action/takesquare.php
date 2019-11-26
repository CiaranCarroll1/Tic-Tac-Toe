<?php
	session_start();
	
	$x = $_POST["x"];
	$y = $_POST["y"];
	
	if(isset($_SESSION["uid"]) && isset($_SESSION["gid"])) {
		$uid = $_SESSION["uid"];
		$gid = $_SESSION["gid"];
		
		$wsdl = "http://localhost:8080/TTTWebApplication/TTTWebService?WSDL";
		$trace = true;
		$exceptions = true;
	
		try {
			$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
			
			$xml_array['x'] = $x;
			$xml_array['y'] = $y;
			$xml_array['pid'] = $uid;
			$xml_array['gid'] = $gid;
			$response = $proxy->takeSquare($xml_array);
			$val = (string) $response->return;
			
			switch ($val)
			{
				case 'ERROR':
					$val = "E";
					break;
				case 0:
					$val = "U";
					break;
				case 1:
					$response = $proxy->checkWin(['gid' => $gid]);
					$val = (string) $response->return;
					switch ($val)
					{
						case 0:
							$val = 0;
							break;
						case 1:
							$proxy->setGameState(['gid' => $gid,'gstate' => 1]);
							$val = 1;
							break;
						case 2:
							$proxy->setGameState(['gid' => $gid,'gstate' => 2]);
							$val = 2;
							break;
						case 3:
							$proxy->setGameState(['gid' => $gid,'gstate' => 3]);
							$val = 3;
                        break;
					}
					break;
			}
			echo $val;

		} 
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}else {
		header("location:main.php?err=1");
	}
?>
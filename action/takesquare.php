<?php
	session_start();
	
	$x = $_POST["x"];
	$y = $_POST["y"];
	
	if(isset($_SESSION["uid"]) && isset($_SESSION["gid"])) {
		$uid = $_SESSION["uid"];
		$gid = $_SESSION["gid"];
		
		include "wsdl.php";
	
		try {
			$proxy = new SoapClient($wsdl, array('trace' => $trace, 'exceptions' => $exceptions));
			
			$xml_array['x'] = $x;
			$xml_array['y'] = $y;
			$xml_array['pid'] = $uid;
			$xml_array['gid'] = $gid;
			$response = $proxy->takeSquare($xml_array);  //Try to take square.
			$val = (string) $response->return;
			
			switch ($val)
			{
				case 1:
					$response = $proxy->checkWin(['gid' => $gid]);  //Check win if successful.
					$val = (string) $response->return;
					
					switch ($val)
					{
						case 1:
							$proxy->setGameState(['gid' => $gid,'gstate' => 1]);
							break;
						case 2:
							$proxy->setGameState(['gid' => $gid,'gstate' => 2]);
							break;
						case 3:
							$proxy->setGameState(['gid' => $gid,'gstate' => 3]);
                        break;
					}
					break;
			}
			echo $val; //Return result.

		} 
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}else {
		header("location:main.php?err=1");
	}
?>
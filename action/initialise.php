<?php
	session_start();
	$uid = $_POST["user"];
	$un = $_POST["un"];
	$refer = $_POST["refer"];
	
	if($refer == "index.php" || !isset($_SESSION["uid"]) ) {
		$_SESSION["uid"] = $uid;
		$_SESSION["username"] = $un;
	}	
?>
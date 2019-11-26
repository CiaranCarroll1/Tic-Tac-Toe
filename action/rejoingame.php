<?php
	session_start();
	
	$gid = $_POST["gid"];
	
	$_SESSION["gid"] = $gid;
	$_SESSION["pn"] = 1;
?>
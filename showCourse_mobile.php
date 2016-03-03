<?php

	include 'dblib.php';

	$type = $_GET['t'];
	$major = $_GET['m'];
	$year = $_GET['y'];
	
	if(strlen($type)==0)
		$type ='%%';	
	if(strlen($major)==0)
		$major ='%%';	
	if(strlen($year)==0)
		$year ='%%';	
	
	$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
	mysqli_query($conn, 'set names utf8');

	$query = "SELECT * FROM Course where cTypecode like '".$type."' and cMajorcode like '".$major."' and cYear like '".$year."'";
	
	$data = mysqli_query($conn, $query);
	$json = array();
	
	if (mysqli_num_rows($data)) {
		while ($row = mysqli_fetch_assoc($data)) {
			$json['Course'][] = $row;
		}
	}
	
	mysqli_free_result($data);
	mysqli_close($conn);

	echo json_encode($json);

?>
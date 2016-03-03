<?php

	include 'dblib.php';

	$sid = $_GET['sid'];
	$slot = $_GET['slot'];
	
	if(strlen($sid)==0)
		$sid ='%%';	
	
	$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
	mysqli_query($conn, 'set names utf8');

	$query = "select * from Enroll a inner join Course b where a.cIndex=b.cIndex and a.sIndex=".$sid." and cSlot=".$slot;
	
	$data = mysqli_query($conn, $query);
	$json = array();
	
	if (mysqli_num_rows($data)) {
		while ($row = mysqli_fetch_assoc($data)) {
			$json['Enroll'][] = $row;
		}
	}
	
	mysqli_free_result($data);
	mysqli_close($conn);

	echo json_encode($json);

?>
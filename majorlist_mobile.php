<?php
	include 'dblib.php';

	$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
	mysqli_query($conn, 'set names utf8');
	

	$query = "select distinct cMajor, cMajorcode from Course";		
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
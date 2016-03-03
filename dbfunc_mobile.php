<?php
	include 'dblib.php';

	$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
	mysqli_query($conn, 'set names utf8');
	

	$method = $_GET['method'];
	$val1 = $_GET['val1'];
	$val2 = $_GET['val2'];
	$val3 = $_GET['val3'];

	/* 
	 * method=course
	 * val1 = 0 // total table print
	 * val1 = 1 // select table print
	 * > val2 = select name
	 * val1 = 2 // distict select table print
	 * > val2 = select name
	 */

	if(!strcmp($method,"course"))
	{
		if ($val1==1) {
			$query = "select ".$val2." from Course";
		}
		else if ($val1==2) {
			$query = "select distinct ".$val2." from Course";
		}
		else {
			$query = "select * from Course";
		}
		
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
		
	}
	else if(strcmp($method,"student"))
	{
		
	}
	else if(strcmp($method,"enroll"))
	{
		
	}
	else
	{
		
	}
	//echo "</xml>";
?>
<?php

	include 'lib.php';
	include 'dblib.php';

	$sid = $_POST['sid'];
	$cid = $_POST['cid'];
	$slot = $_POST['slot'];
	
	$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
	
	$count = 0;
	$slot_count = 0;
	$result = mysqli_query($conn, "SELECT * FROM Enroll WHERE sIndex=".$sid." and cIndex=".$cid." and cSlot=0");
	
	
	while($row = mysqli_fetch_array($result))
		$count++;
	
	
	//check == 0 -> error check == 1->sucess
	if($slot==4) {
		$slot_check = mysqli_query($conn, "SELECT * FROM Enroll WHERE sIndex=".$sid." and cIndex=".$cid." and cSlot=1");
		while($slot_row = mysqli_fetch_array($slot_check))
			$slot_count++;
		
		if($slot_count  != 0){
			$check2 = mysqli_query($conn, "update Course Set cCurNum = cCurNum-1 where cIndex =".$cid) or die("0"); //no check
		}
		//if slot number is number 4, remove this tuple
		$check = mysqli_query($conn, "delete from Enroll where sIndex=".$sid." and cIndex=".$cid) or die("0");
		echo $check;
	}
	else if($count>=1){
		 //if already exist
		echo "0";
	}
	else {
		 //if not exist, enroll sindex and cIndex (default = slot number 0 )
		$check = mysqli_query($conn, "INSERT INTO Enroll (sIndex,cIndex,cSlot) VALUES (".$sid.",".$cid.",0)") or die("0");
		echo $check;
	}
?>
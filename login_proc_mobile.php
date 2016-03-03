<meta http-equiv="content-type" content="text/html" charset="UTF-8">

<?php

	include 'lib.php';
	include 'dblib.php';
	
	$id=$_POST['id'];
	$pwd=$_POST['pwd'];
	
	$conn = mysqli_connect($db_host,$db_user,$db_passwd,$db_name);
 	
	$snoopy = new snoopy;
	$getUrl='http://bus.kumoh.ac.kr/modules_pc/contents/login_Proc.asp?BRS_us_id='.$id.'&BRS_us_pw='.$pwd;
	$snoopy->fetch($getUrl);
	
	$var = $snoopy->results;
	$html=str_get_html($var);
	
	$content=$html->find('script');
	$content2=ltrim($content[0]->innertext); //del space
	$content3=substr($content2,0,5); //parsing
	
	$count=0;
	
	if(strcmp($content3,"alert")) {//cmp  succeed
		$result = mysqli_query($conn,"SELECT * FROM Student where sIndex=".$id);
		while($row = mysqli_fetch_array($result)) $count++;
		if($count == 0) { //join id 
			mysqli_query($conn,"INSERT INTO Student (sIndex) VALUES (".$id.")");
		}
		echo "0"; 
	}
	else {
		echo "1"; //error
	}
	
	mysqli_close($conn);
?>
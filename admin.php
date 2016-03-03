<?php
include 'dblib.php';

$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
mysqli_query($conn, 'set names utf8');

$result = mysqli_query($conn, "SELECT date FROM admin");
$date = mysqli_fetch_array($result);

?>

<html lang='ko'>

	<head>
		<meta charset='utf-8'>
		<title>관리자 페이지</title>
	</head>
	<body>
		<!--content //-->
		<p align="center">
			<label >강의정보 업로드</label>
			<form method='post' action='./admin_proc.php' enctype='multipart/form-data' accept-charset="UTF-8">
				<input type="file" name="file" id='file' placeholder="File">
				<br><br>
				최종 변경일 : <? echo date($date['date']); ?>
				<br><br>
				
				<input type="submit" name="submit" value="submit" placeholder="Submit" >

			</form>
		</p>
		<!--// content-->
	</body>
</html>


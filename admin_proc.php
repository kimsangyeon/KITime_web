<?php

include 'dblib.php';
header('Content-Type: text/html; charset=UTF-8');

$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);

// file check
if (($_FILES["file"]["type"] == "text/xml") && ($_FILES["file"]["name"] == "Export.xml")) {
	if ($_FILES["file"]["error"] > 0) {
		echo "Error: " . $_FILES["file"]["error"] . "<br />";
	} else {
		// upload file
		move_uploaded_file($_FILES["file"]["tmp_name"], "" . $_FILES["file"]["name"]);
		//	echo "<p align='center'><br />Stored in: " . "" . $_FILES["file"]["name"] . "<br>";
		//	echo "<br /><br /><font color='red'>Please Wait..... <br /><br />";

		//<----------- parsing ------------>
		$file = "Export.xml";

		$xml = simplexml_load_file($file);

		//auto recreate & delete table
		mysqli_query($conn, "truncate Course");
		mysqli_query($conn, "delete from Course");

		$temp;

		foreach ($xml->children() as $child) {

			$temp = "";
			foreach ($child->children() as $child2) {

				switch ($child2 -> getName()) {
					case '교육과정코드' :
						$temp = $child2 . "' , '";
						break;
					case '교육과정명' :
						$temp .= $child2 . "' , '";
						break;
					case '교과목구분_교과목종류' :
						$temp .= $child2 . "' , '";
						break;
					case '교과목종류' :
						$temp .= $child2 . "' , '";
						break;
					case '교과목명' :
						$temp .= $child2 . "' , '";
						break;
					case '이수대상학년' :
						$temp .= $child2 . "' , '";
						break;
					case '학점' :
						$temp .= $child2 . "' , '";
						break;
					case '개설교과목코드' :
						$temp .= $child2 . "' , '";
						break;
					case '강의시간강의실' :
						//<------------------------->
						$time = '';
						$room = '';
						$arr = "";
						$arr2 = "";
						$time_temp = '';
						
						$arr = explode(',', $child2);

						// sort 'DAY/ROOM'
						for ($i = 0; $i < sizeof($arr); $i++)
							$arr2[$i] = explode('/', $arr[$i]);

						// sort 'DAY/'
						//		'ROOM/'
						for ($i = 0; $i < sizeof($arr2); $i++) {
							//<---------------- '월화수목금' -> '12345'
							if (strstr($arr2[$i][0], '월') != FALSE) {
								$time_temp = str_replace('월', '1', $arr2[$i][0]);
								$arr2[$i][0] = '1' . $time_temp;
							}
							if (strstr($arr2[$i][0], '화') != FALSE) {
								$time_temp = str_replace('화', '2', $arr2[$i][0]);
								$arr2[$i][0] = '2' . $time_temp;
							}
							if (strstr($arr2[$i][0], '수') != FALSE) {
								$time_temp = str_replace('수', '3', $arr2[$i][0]);
								$arr2[$i][0] = '3' . $time_temp;
							}
							if (strstr($arr2[$i][0], '목') != FALSE) {
								$time_temp = str_replace('목', '4', $arr2[$i][0]);
								$arr2[$i][0] = '4' . $time_temp;
							}
							if (strstr($arr2[$i][0], '금') != FALSE) {
								$time_temp = str_replace('금', '5', $arr2[$i][0]);
								$arr2[$i][0] = '5' . $time_temp;
							}
							$time .= $time_temp . '/';
							//<---------------------------------

							$room .= $arr2[$i][1] . '/';
						}

						$temp .= $child2 . "' , '" . $time . "' , '" . $room . "' , '";

						//<-------------------------->
						break;
					case '담당강사명' :
						$temp .= $child2 . "' , '";
						break;
					case '수강제한인원' :
						$temp .= $child2;
						break;
				}
			}
			$query = "INSERT INTO Course ( cMajorcode, cMajor, cName, cTypecode, cType, cYear, cGrade, cCode, cDate, cTime, cRoom, cProf, cMaxNum) VALUES ('" . $temp . "') ;";
			//echo $query . "<br>";

			mysqli_query($conn, 'set names utf8');
			mysqli_query($conn, $query);
			//echo"INSERT INTO Course ( cName, cType, cMajor, cYear, cGrade, cCode, cDate, cProf, cMaxNum) VALUES ('" . $temp . "'); <br>";
			//echo "('" . $temp . "'); <br>";

		}
		$date =  date("Y-m-d H:i:s");
		$query = "UPDATE admin SET date = '" . $date . "' ;";
		mysqli_query($conn, $query);
		
		mysqli_close($conn);
		//<----------- parsing end------------>
		echo "<p align='center'>";
		echo "<br /><br /><br /><font color='red'>upload complete <br /><br />";
		echo "<input type='button' value='close' onclick='self.close()'>";
	}

} else {
	echo "<p align='center'><br />Invalid file <br /><br /><br />";

	echo "<input type='button' value='back' onclick='history.back()'>";
}
?>
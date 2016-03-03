<?php
include 'dblib.php';

$id = $_GET['id'];

$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction

$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
mysqli_query($conn, 'set names utf8');

$result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM Enroll a inner join Course b where a.cIndex=b.cIndex and a.sIndex=".$id." and cSlot=0");
$row = mysqli_fetch_array($result);
$count = $row['count'];


if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
$SQL = "SELECT * FROM Enroll a inner join Course b where a.cIndex=b.cIndex and a.sIndex=".$id." and cSlot=0";
$result = mysqli_query($conn, $SQL ) or die("Couldn t execute query.".mysqli_error());

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = mysqli_fetch_array($result)) {
    $responce->rows[$i]['id']=array($row[cName], "/".$row[cCode],"/".$row[cMajor], "/".$row[cType],"/".$row[cGrade], "/".$row[cDate], "/".$row[cProf], "/".$row[cMaxNum], "/".$row[cIndex], "/".$row[cTime]);
    $responce->rows[$i]['cell']=array($row[cName], $row[cCode]);
    $i++;
}        
echo json_encode($responce);

/*
<
$query = "SELECT * FROM Course where cTypecode like '" . $type . "' and cMajorcode like '" . $major . "' and cYear like '" . $year . "'";
$result = mysqli_query($conn, $query);

$result_array = array();
while ($row = mysqli_fetch_object($result)) {
	$result_array[] = $row;
};

//결과값을 JSON형식으로 변환
$result_array = json_encode($result_array);
//변수 내용 출력
echo $result_array;
*/
?>


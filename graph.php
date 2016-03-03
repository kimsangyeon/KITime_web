<?php
include 'dblib.php';

$id = $_GET['id'];

$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
mysqli_query($conn, 'set names utf8');

$query = "select cName, cMaxNum, cCurNum, cCode, cDate, cTime from Enroll a inner join Course b where a.cSlot=1 and a.cIndex=b.cIndex and a.sIndex=" . $id;

//정보 가져오기
$data = mysqli_query($conn, $query);
?>
	<thead>
		<tr>
			<th></th>
			<th>퍼센트(%)</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while ($row_ = mysqli_fetch_array($data)) {
			if ($row_['cMaxNum'] > 1 && strcmp($row_['cTime'], '/') == 1) {
				echo '
		<tr>
			<th>' . $row_['cName'] . '</th>
			<td>';
				echo number_format($row_['cCurNum'] * 100 / $row_['cMaxNum'], 2);
'</td>
		</tr>';
			}
		}
		?>
	</tbody>
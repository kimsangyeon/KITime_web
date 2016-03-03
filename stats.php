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
				<th>과목명</th>
				<th>과목 코드</th>
				<th>시간 /강의실</th>
				<th>제한 인원</th>
				<th>수강 인원</th>
				<th>퍼센트(%)</th>
				<th>클립보드</th>
			</tr>
		</thead>

		<tbody>

			<?php
			while ($row_ = mysqli_fetch_array($data)) {
				echo '
			<tr>
				<th class="hour">' . $row_['cName'] . '</th>
				<td>' . $row_['cCode'] . '</td>
				<td>' . $row_['cDate'] . '</td>
				<td>' . $row_['cMaxNum'] . '</td>
				<td>' . $row_['cCurNum'] . '</td>
				<td>';
				if ($row_['cMaxNum'] != 0)
					echo number_format($row_['cCurNum'] * 100 / $row_['cMaxNum'], 2);
				echo '</td> 
				<td><input type="button" value="복사" onclick="var code=\'';  echo $row_['cCode'] .'\';alert(code + \' \n클립보드에 복사되었습니다.\');window.clipboardData.setData(\'Text\',code);"> </td></tr>';
			}
			?>
		</tbody>

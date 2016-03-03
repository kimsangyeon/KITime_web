<meta http-equiv="content-type" content="text/html" charset="UTF-8">

<?php

include 'lib.php';
include 'dblib.php';

ob_start();

$id = $_POST['id'];
$pwd = $_POST['pwd'];

$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);

$snoopy = new snoopy;
$getUrl = 'http://bus.kumoh.ac.kr/modules_pc/contents/login_Proc.asp?BRS_us_id=' . $id . '&BRS_us_pw=' . $pwd;
$snoopy -> fetch($getUrl);

$var = $snoopy -> results;
$html = str_get_html($var);

$content = $html -> find('script');
$content2 = ltrim($content[0] -> innertext);
//del space
$content3 = substr($content2, 0, 5);
//parsing

$count = 0;

if (!strcmp($id, "admin")) {// admin check
	$result = mysqli_query($conn, "SELECT * FROM admin where aPwd='" . $pwd . "'");
	while ($row = mysqli_fetch_array($result))
		$count++;
	if ($count != 0) {//join admin
		echo "<script language='javascript'>
				window.open('admin.php','admin', 'height=180,width=330,top=50,left=50,location=no,status=yes,resizable=nl,toolbar=no,menubar=no');
				location.href='index.html';
				</script>";
	} else {
		echo "<script language='javascript'>
		alert('Please check your student id or password!');
		location.href='index.html';
		</script>";
	}

} else if (strcmp($content3, "alert")) {//cmp
	$result = mysqli_query($conn, "SELECT * FROM Student where sIndex=" . $id);
	while ($row = mysqli_fetch_array($result))
		$count++;
	if ($count == 0) {//join id
		mysqli_query($conn, "INSERT INTO Student (sIndex) VALUES (" . $id . ")");
	}

	//save in cookies	->   setcookie("��Ű �̸�","��Ű ��",���ð�,"���","ȣ��Ʈ","���Ȼ��");
	$cook1 = setcookie("cookie_id", $id, time() + 3600);
	
	if ($cook1) {//success save
		echo "<script language='javascript'>
					location.href='main.php';
					</script>";
	} else {//fail save cookie
		echo "<script language='javascript'>
			alert('System error! check your computer');
			location.href='index.html';
			</script>";
	}
} else {
	echo "<script language='javascript'>
		alert('Please check your student id or password!');
		location.href='index.html';
		</script>";
}

mysqli_close($conn);
?>
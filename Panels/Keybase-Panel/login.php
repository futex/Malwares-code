<?php
session_start(); 
include ('config.php');
include('create.php');
$errorMessage = '';

if (isset($_SESSION['logged_in'])) {
   unset($_SESSION['logged_in']);
}


if (isset($_POST['username']) && isset($_POST['password'])) {
if ($_POST['username'] ===  $panelusername && $_POST['password'] === $panelpassword) {
$_SESSION['logged_in'] = true;
header('Location: index.php');
exit;
} else {
$errorMessage = 'Wrong username/password combination';
}
}
?>
<html>
<head>
<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KeyBase: Login</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
<div class="container">
	<div class="row text-center ">
		<div class="col-md-12">
                <br /><br />
                <h2> KeyBase: Login</h2>
               	<h5>( Login to get access to your logs )</h5>
                 <br />
            </div>
	</div>
<div class="row ">
 <div class="row ">
               
                  <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                        <strong>Enter Details To Login </strong>  
                            </div>
                            <div class="panel-body">
                                
<form method="post" name="frmLogin" id="frmLogin" action="login.php">
<table width="100%" border="1" align="center" cellpadding="2" cellspacing="2">
<tr>
<td width="90%">User Id</td>
<td><input name="username" type="text" id="username"></td>
</tr>
<tr>
<td width="90%">Password</td>
<td><input name="password" type="password" id="password"></td>
</tr>
<tr>

</tr>

</table>
<br>
<center><input type="submit" name="btnLogin" class="btn btn-primary" value="Login now"></center>
</form>
                            </div>
                           
                        </div>
                    </div>
                
                
        </div>
</div>

     <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
   
</body>

<?
echo "<center>";
echo " $errorMessage ";
echo "</center>";
?>
</html>
<?php require_once('Connections/ststsconfig.php');?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
$colname_kosam = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_kosam = $_SESSION['MM_Username'];
}
mysql_select_db($database_ststsconfig, $ststsconfig);
$query_kosam = sprintf("SELECT * FROM administrators WHERE username = %s", GetSQLValueString($colname_kosam, "text"));
$kosam = mysql_query($query_kosam, $ststsconfig) or die(mysql_error());
$row_kosam = mysql_fetch_assoc($kosam);
$totalRows_kosam = mysql_num_rows($kosam);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=sha1($_POST['password']);
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "/index.php";
  $MM_redirectLoginFailed = "/login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_ststsconfig, $ststsconfig);
  
  $LoginRS__query=sprintf("SELECT username, password FROM administrators WHERE username=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $ststsconfig) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
if (isset($_SESSION['MM_Username'])) { 
header("Location: index.php");
};?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CAP | Login</title>
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/fonts/css/font-awesome.min.css" rel="stylesheet">
<link href="/css/animate.min.css" rel="stylesheet">
<link href="/css/custom.css" rel="stylesheet">
<link href="/css/icheck/flat/green.css" rel="stylesheet">
<script src="/js/jquery.min.js"></script>

<!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>

<body style="background:#F7F7F7;">
<div class="">
  <div id="wrapper">
    <div id="login" class="animate form">
      <section class="login_content">
        <form action="<?php echo $loginFormAction; ?>" method="post" id="login">
          <h1>Login Form</h1>
          <div>
            <input name="username" class="form-control" placeholder="Username" required>
            <br />
          </div>
          <div>
            <input name="password" type="password" class="form-control" placeholder="Password" required>
          </div>
          <div>
            <button class="btn btn-primary submit" type="submit">Log in</button>
          </div>
          <div class="clearfix"></div>
          <div class="separator">
            <div class="clearfix"></div>
            <br />
            <div>
              <h1><i class="fa fa-paw" style="font-size: 26px;"></i> CAP| Login</h1>
              <p>©<?php echo date("Y")?> All Rights Reserved. Powered By S!r.ReaDy<3</p>
            </div>
          </div>
        </form>
        <!-- form --> 
      </section>
      <!-- content --> 
    </div>
  </div>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($kosam);
?>

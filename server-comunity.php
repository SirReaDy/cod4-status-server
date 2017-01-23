<?php include_once('Connections/ststsconfig.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "/login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
};?>
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



$colname_loggedin = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_loggedin = $_SESSION['MM_Username'];
}
mysql_select_db($database_ststsconfig, $ststsconfig);
$query_loggedin = sprintf("SELECT * FROM administrators WHERE username = %s", GetSQLValueString($colname_loggedin, "text"));
$loggedin = mysql_query($query_loggedin, $ststsconfig) or die(mysql_error());
$row_loggedin = mysql_fetch_assoc($loggedin);
$totalRows_loggedin = mysql_num_rows($loggedin);

if ($row_loggedin['power'] !='100') { header("Location: /");};

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_my_comunity = "SELECT * FROM comunity WHERE id = 1";
$my_comunity = mysql_query($query_my_comunity, $ststsconfig) or die(mysql_error());
$row_my_comunity = mysql_fetch_assoc($my_comunity);
$totalRows_my_comunity = mysql_num_rows($my_comunity);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE comunity SET comunity_name=%s, server_rules=%s WHERE id=%s",
                       GetSQLValueString($_POST['comunity_name'], "text"),
                       GetSQLValueString($_POST['server_rules'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_ststsconfig, $ststsconfig);
  $Result1 = mysql_query($updateSQL, $ststsconfig) or die(mysql_error());

  $updateGoTo = "/";
  header(sprintf("Location: %s", $updateGoTo));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CAP | Server Rules</title>

<!-- Bootstrap core CSS -->

<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/fonts/css/font-awesome.min.css" rel="stylesheet">
<link href="/css/animate.min.css" rel="stylesheet">

<!-- Custom styling plus plugins -->
<link href="/css/icheck/flat/green.css" rel="stylesheet" />
<link href="/css/custom.css" rel="stylesheet">
<script src="/js/nprogress.js"></script>
<script src="/js/jquery.min.js"></script>
<!--[if lt IE 9]>
        <script src="/assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>

<body class="nav-md">
<div class="container body">
  <div class="main_container">
    <div class="col-md-3 left_col">
      <div class="left_col scroll-view">
        <div class="navbar nav_title"> <a href="/" class="site_title"><i class="fa fa-line-chart"></i> <span>Cap Server Status</span></a> </div>
        <div class="clearfix"></div>
        <div class="profile">
          <div class="profile_pic"> <img src="/images/admin.png" alt="user" class="img-circle profile_img"> </div>
          <div class="profile_info"> <span>Welcome,</span>
            <h2>
              <?php if (!isset($_SESSION['MM_Username'])) { echo 'Guest';}if (isset($_SESSION['MM_Username'])) { echo $row_loggedin['username'];}?>
            </h2>
          </div>
        </div>
        <br />
        <?php include("template_include/navigation.php"); ?>
      </div>
    </div>
    <div class="top_nav">
      <div class="nav_menu">
        <nav class="" role="navigation">
          <div class="nav toggle"> <a id="menu_toggle"><i class="fa fa-bars"></i></a> </div>
          <ul class="nav navbar-nav navbar-right">
            <?php if (!isset($_SESSION['MM_Username'])) { ?>
            <li class=""><a href="/login.php" class="user-profile"><img src="images/admin.png" alt="admin"> Login <i class="fa fa-sign-in"></i></a></li>
            <?php };?>
            <?php if (isset($_SESSION['MM_Username'])) { ?>
            <li class=""> <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <img src="images/admin.png" alt=""><?php echo $row_loggedin['username']; ?> <span class=" fa fa-angle-down"></span> </a>
              <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                <li><a href="/profile.php"> Profile</a> </li>
                <li><a href="/logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a> </li>
              </ul>
            </li>
            <?php };?>
          </ul>
        </nav>
      </div>
    </div>
    <div class="right_col" role="main">
      <div class="row">
        <div class="col-md-9 col-sm-9 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Edit comunity info</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
                <p>
                  <label for="comunity_name">Comunity name * :</label>
                  <input type="text" name="comunity_name" value="<?php echo htmlentities($row_my_comunity['comunity_name'], ENT_COMPAT, 'UTF-8'); ?>" class="form-control" required>
                </p>
                <p>
                  <label for="server_rules">Server Rules (On homepage) * :</label>
                  <textarea name="server_rules" cols="50" rows="5" class="form-control" required><?php echo htmlentities($row_my_comunity['server_rules'], ENT_COMPAT, 'UTF-8'); ?></textarea>
                </p>
                <p>
                  <input type="submit" class="btn btn-primary pull-right" value="Save changes">
                </p>
                <input type="hidden" name="id" value="<?php echo $row_my_comunity['id']; ?>">
                <input type="hidden" name="MM_update" value="form1">
              </form>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
      <?php include("template_include/footer.php"); ?>
    </div>
  </div>
</div>
<?php include("template_include/javascript-files.php"); ?>
</body>
</html>
<?php
mysql_free_result($loggedin);

mysql_free_result($my_comunity);
?>

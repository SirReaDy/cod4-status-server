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

$colname_adminlogs = "-1";
if (isset($_GET['id'])) {
  $colname_adminlogs = $_GET['id'];
}
mysql_select_db($database_ststsconfig, $ststsconfig);
$query_adminlogs = sprintf("SELECT id, server_name FROM servers WHERE id = %s", GetSQLValueString($colname_adminlogs, "int"));
$adminlogs = mysql_query($query_adminlogs, $ststsconfig) or die(mysql_error());
$row_adminlogs = mysql_fetch_assoc($adminlogs);
$totalRows_adminlogs = mysql_num_rows($adminlogs);

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_serveradmins = "SELECT * FROM administrators ORDER BY username ASC";
$serveradmins = mysql_query($query_serveradmins, $ststsconfig) or die(mysql_error());
$row_serveradmins = mysql_fetch_assoc($serveradmins);
$totalRows_serveradmins = mysql_num_rows($serveradmins);

if(isset($_GET['filter'])) 
mysql_real_escape_string ($filter=$_GET['filter']);
if (empty($filter))
{
    $filter = '';
} 

if ($row_loggedin['power'] !='100') { header("Location: /");};

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cap | Admin Actions</title>

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
        <div class="navbar nav_title"> <a href="/" class="site_title"><i class="fa fa-line-chart"></i> <span>Cap | Admin Actions</span></a> </div>
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
              <h2>Admin Actions - <?php echo $row_adminlogs['server_name']; ?></h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">           
              <?php
			  
				$file = 'logs/'.$row_adminlogs['id'].'/adminactions.log';
				$searchfor = ''.$filter.'';
				
				// the following line prevents the browser from parsing this as HTML.
				//header('Content-Type: text/plain');
				
				// get the file contents, assuming the file to be readable (and exist)
				$contents = file_get_contents($file);
				// escape special characters in the query
				$pattern = preg_quote($searchfor, '/');
				// finalise the regular expression, matching the whole line
				$pattern = "/^.*$pattern.*\$/m";
				// search, and store all matching occurences in $matches
				if(preg_match_all($pattern, $contents, $matches)){
				   echo '<textarea class="form-control" rows="30">';
				   echo implode("\n", $matches[0]);
				   echo '</textarea>';
				}
				else{
				   echo "No matches found";
				}
				;?>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
          <div class="row">
            <div class="x_panel">
              <div class="x_title">
                <h2>Admin Actions - Log</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div class="row text-center"> <a href="/get-adminactions.php?id=<?php echo $row_adminlogs['id']; ?>" class="btn btn-info"><i class="fa fa-download fa-3x" aria-hidden="true"></i><br />
                  Download latest</a> </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="x_panel">
              <div class="x_title">
                <h2>Filter log by Admin</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <ul class="list-group">
                  <?php do { ?>
                    <li class="list-group-item"><a href="/admin-actions.php?id=<?php echo $row_adminlogs['id']; ?>&amp;filter=<?php echo $row_serveradmins['admin_uid']; ?>"><?php echo $row_serveradmins['username']; ?></a></li>
                    <?php } while ($row_serveradmins = mysql_fetch_assoc($serveradmins)); ?>
                </ul>
                <div class="clearfix"></div>
              </div>
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

mysql_free_result($adminlogs);

mysql_free_result($serveradmins);

?>

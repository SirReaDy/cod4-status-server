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
$query_my_servers = "SELECT id, server_name, server_ip, server_port FROM servers ORDER BY id DESC";
$my_servers = mysql_query($query_my_servers, $ststsconfig) or die(mysql_error());
$row_my_servers = mysql_fetch_assoc($my_servers);
$totalRows_my_servers = mysql_num_rows($my_servers);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
include_once('include/geoip.inc.php');
include ("include/COD4ServerStatus.php");
						
$server_ip = $_POST['server_ip'];
$server_port = $_POST['server_port'];
						
$status = new COD4ServerStatus(''.$server_ip.'', ''.$server_port.''); //Edit IP
$ip = "''.$server_ip.''.''.$server_port.''"; //Edit IP
						
if ($status->getServerStatus()){
$status->parseServerData();
$serverStatus = $status->returnServerData();
$players = $status->returnPlayers();
$pings = $status->returnPings();
$scores = $status->returnScores();
						
//set an IPv6 address for testing
if((strpos($server_ip, ":") === false)) {
//server_ipv4
$gi = geoip_open("include/GeoIP.dat",GEOIP_STANDARD);
$country = geoip_country_code_by_addr($gi, $server_ip);
}
else {
//ipv6
$gi = geoip_open("include/GeoIPv6.dat",GEOIP_STANDARD);
$country = geoip_country_code_by_addr_v6($gi, $server_ip);
}
include("include/friendly_mapname.php");
							;	
	
$server_name2=$serverStatus['sv_hostname'] = str_replace(array("^1", "^2","^3","^4","^5","^6","^7","^8","^9","^0"), "\n", $serverStatus['sv_hostname']);echo $serverStatus['sv_hostname'];
$server_online_players2 = sizeof($players);
$server_maxplayers2 = $serverStatus['sv_maxclients'];
$server_game2='Call of Duty 4 Server';
$server_current_map2=$fmapname;
$last_refresh2=date ("Y-m-d h:i:s");
$server_location2=$country;
$server_current_map_alias2=$serverStatus['mapname'];
}

	
  $insertSQL = sprintf("INSERT INTO servers (server_name, server_ip, server_port, ftp_username, ftp_password, screenshots_dir, server_maxplayers, server_online_players, server_game, server_location, server_current_map_alias, server_current_map, last_refresh) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($server_name2, "text"),
                       GetSQLValueString($_POST['server_ip'], "text"),
                       GetSQLValueString($_POST['server_port'], "int"),
					   GetSQLValueString($_POST['ftp_username'], "text"),
					   GetSQLValueString($_POST['ftp_password'], "text"),
					   GetSQLValueString($_POST['screenshots_dir'], "text"),
                       GetSQLValueString($server_maxplayers2, "int"),
                       GetSQLValueString($server_online_players2, "int"),
                       GetSQLValueString($server_game2, "text"),
					   GetSQLValueString($server_location2, "text"),
					   GetSQLValueString($server_current_map_alias2, "text"),
                       GetSQLValueString($server_current_map2, "text"),
                       GetSQLValueString($last_refresh2, "date"));

  mysql_select_db($database_ststsconfig, $ststsconfig);
  $Result1 = mysql_query($insertSQL, $ststsconfig) or die(mysql_error());
  
  

  $insertGoTo = "servers.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CAP | Servers</title>

<!-- Bootstrap core CSS -->

<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/fonts/css/font-awesome.min.css" rel="stylesheet">
<link href="/css/animate.min.css" rel="stylesheet">

<!-- Custom styling plus plugins -->
<link href="/css/icheck/flat/green.css" rel="stylesheet" />
<link href="/css/custom.css" rel="stylesheet">
<link href="js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
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
        <div class="navbar nav_title"> <a href="/" class="site_title"><i class="fa fa-line-chart"></i> <span>Cap Servers</span></a> </div>
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
              <h2>My Servers <small>server list</small></h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <table class="table table-striped table-bordered dt-responsive nowrap" id="datatable-responsive">
                <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th width="35%">Server Name</th>
                    <th width="30%">IP &amp; Port</th>
                    <th width="15%">Edit server</th>
                    <th width="15%">Delete server</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($totalRows_my_servers > 0) { // Show if recordset not empty ?>
                    <?php $i=1;?>
                    <?php do { ?>
                      <tr>
                        <th scope="row"><?php echo $i++ ;?></th>
                        <td><?php echo $row_my_servers['server_name']; ?></td>
                        <td><?php echo $row_my_servers['server_ip']; ?>:<?php echo $row_my_servers['server_port']; ?></td>
                        <td><a href="edit-server.php?id=<?php echo $row_my_servers['id']; ?>" class="btn btn-default btn-xs">Edit</a></td>
                        <td><a href="delete-server.php?id=<?php echo $row_my_servers['id']; ?>" class="btn btn-danger btn-xs">Delete</a></td>
                      </tr>
                      <?php } while ($row_my_servers = mysql_fetch_assoc($my_servers)); ?>
                    <?php } // Show if recordset not empty ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Add new server</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
                <p>
                  <label for="server_ip">Server IP * :</label>
                  <input type="text" id="server_ip" class="form-control" name="server_ip" value="" required />
                </p>
                <p>
                  <label for="server_port">Port * :</label>
                  <input type="text" id="server_port" class="form-control" name="server_port" value="" required />
                </p>
                <p>
                  <label for="ftp_username">FTP Username * :</label>
                  <input type="text" id="ftp_username" class="form-control" name="ftp_username" value="" required />
                </p>
                <p>
                  <label for="ftp_password">FTP Password * :</label>
                  <input type="password" id="ftp_password" class="form-control" name="ftp_password" value="" required />
                </p>
                <p>
                  <label for="screenshots_dir">Screenshots path * :</label>
                  <input type="text" id="screenshots_dir" class="form-control" name="screenshots_dir" value="" required />
                </p>
                <p>
                  <input type="submit" class="btn btn-primary pull-right" value="Add new server">
                </p>
                <input type="hidden" name="MM_insert" value="form1">
              </form>
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
<!-- Datatables --> 
<!-- <script src="js/datatables/js/jquery.dataTables.js"></script>
  <script src="js/datatables/tools/js/dataTables.tableTools.js"></script> --> 

<!-- Datatables--> 
<script src="js/datatables/jquery.dataTables.min.js"></script> 
<script src="js/datatables/dataTables.bootstrap.js"></script> 
<script src="js/datatables/dataTables.buttons.min.js"></script> 
<script src="js/datatables/buttons.bootstrap.min.js"></script> 
<script src="js/datatables/dataTables.fixedHeader.min.js"></script> 
<script src="js/datatables/dataTables.keyTable.min.js"></script> 
<script src="js/datatables/dataTables.responsive.min.js"></script> 
<script src="js/datatables/responsive.bootstrap.min.js"></script> 
<script type="text/javascript">
          $(document).ready(function() {
            $('#datatable').dataTable();
            $('#datatable-keytable').DataTable({
              keys: true
            });
            $('#datatable-responsive').DataTable();
            $('#datatable-scroller').DataTable({
              ajax: "js/datatables/json/scroller-demo.json",
              deferRender: true,
              scrollY: 380,
              scrollCollapse: true,
              scroller: true
            });
            var table = $('#datatable-fixed-header').DataTable({
              fixedHeader: true
            });
          });
          TableManageButtons.init();
        </script>
</body>
</html>
<?php
mysql_free_result($loggedin);

mysql_free_result($my_servers);
?>

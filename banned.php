<?php include_once('Connections/ststsconfig.php'); ?>
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

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_banned_player = "SELECT * FROM banned_players ORDER BY id DESC";
$banned_player = mysql_query($query_banned_player, $ststsconfig) or die(mysql_error());
$row_banned_player = mysql_fetch_assoc($banned_player);
$totalRows_banned_player = mysql_num_rows($banned_player);

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_coumunity = "SELECT comunity_name FROM comunity WHERE id = 1";
$coumunity = mysql_query($query_coumunity, $ststsconfig) or die(mysql_error());
$row_coumunity = mysql_fetch_assoc($coumunity);
$totalRows_coumunity = mysql_num_rows($coumunity);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $row_coumunity['comunity_name']; ?> CAP | Banned players</title>

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
        <div class="navbar nav_title"> <a href="/" class="site_title"><i class="fa fa-line-chart"></i> <span>Cap Banned Players</span></a> </div>
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
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Banned players <small>by Admins</small></h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a href="#"><i class="fa fa-chevron-up"></i></a> </li>
                <li><a href="#"><i class="fa fa-close"></i></a> </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <p class="text-muted font-13 m-b-30"> Here is the list of players who have ben banned on one of our servers. If you think that you are unfairly banned please contact us, <strong>check first the evidence against you here</strong>. </p>
              <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Player name</th>
                    <th>Time</th>
                    <th>Map</th>
                    <th>Guid (last 8 characters)</th>
                    <th>Banned by Admin</th>
                    <th>Link to screenshot</th>
                    <?php if (isset($_SESSION['MM_Username'])) { ?>
                    <th>Unban Player</th>
                    <?php };?>
                  </tr>
                </thead>
                <tbody>
                <?php if ($totalRows_banned_player > 0) { // Show if recordset not empty ?>
                  <?php do { ?>
                    <tr>
                      <td><?php echo $row_banned_player['player_name']; ?></td>
                      <td><?php echo $row_banned_player['time']; ?></td>
                      <td><?php echo $row_banned_player['map']; ?></td>
                      <td><?php echo $row_banned_player['guid']; ?></td>
                      <td><?php echo $row_banned_player['banned_by']; ?></td>
                      <td><a href="/<?php echo $row_banned_player['screenshot_url']; ?>" target="_blank"><i class="fa fa-camera"></i> Screenshot</a></td>
                      <?php if (isset($_SESSION['MM_Username'])) { ?>
                      <td><a href="/delete-ban.php?id=<?php echo $row_banned_player['id']; ?>" class="btn btn-info btn-xs">Unban</a></td>
                      <?php };?>
                    </tr>
                    <?php } while ($row_banned_player = mysql_fetch_assoc($banned_player)); ?>
                <?php };?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
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

mysql_free_result($banned_player);

mysql_free_result($coumunity);
?>

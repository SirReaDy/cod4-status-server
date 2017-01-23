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
$query_comunity_servers = "SELECT * FROM servers ORDER BY id ASC";
$comunity_servers = mysql_query($query_comunity_servers, $ststsconfig) or die(mysql_error());
$row_comunity_servers = mysql_fetch_assoc($comunity_servers);
$totalRows_comunity_servers = mysql_num_rows($comunity_servers);

$maxRows_last_bans = 5;
$pageNum_last_bans = 0;
if (isset($_GET['pageNum_last_bans'])) {
  $pageNum_last_bans = $_GET['pageNum_last_bans'];
}
$startRow_last_bans = $pageNum_last_bans * $maxRows_last_bans;

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_last_bans = "SELECT * FROM banned_players ORDER BY id DESC";
$query_limit_last_bans = sprintf("%s LIMIT %d, %d", $query_last_bans, $startRow_last_bans, $maxRows_last_bans);
$last_bans = mysql_query($query_limit_last_bans, $ststsconfig) or die(mysql_error());
$row_last_bans = mysql_fetch_assoc($last_bans);

if (isset($_GET['totalRows_last_bans'])) {
  $totalRows_last_bans = $_GET['totalRows_last_bans'];
} else {
  $all_last_bans = mysql_query($query_last_bans);
  $totalRows_last_bans = mysql_num_rows($all_last_bans);
}
$totalPages_last_bans = ceil($totalRows_last_bans/$maxRows_last_bans)-1;

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_server_admins = "SELECT username, admin_uid, power FROM administrators ORDER BY power DESC";
$server_admins = mysql_query($query_server_admins, $ststsconfig) or die(mysql_error());
$row_server_admins = mysql_fetch_assoc($server_admins);
$totalRows_server_admins = mysql_num_rows($server_admins);

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_comunity = "SELECT comunity_name, server_rules FROM comunity WHERE id = 1";
$comunity = mysql_query($query_comunity, $ststsconfig) or die(mysql_error());
$row_comunity = mysql_fetch_assoc($comunity);
$totalRows_comunity = mysql_num_rows($comunity);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cap Servers | Status <?php echo $row_comunity['comunity_name']; ?></title>
<?php include("include/header.php"); ?>
</head>

<body class="nav-md">
<div class="container body">
  <div class="main_container">
        <?php include("include/navigation.php"); ?>

    
    <div class="right_col" role="main">
      <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>COD4 Servers</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <table class="table table-striped projects">
                <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th width="20%">Server Name</th>
                    <th width="5%">Join</th>
                    <th width="15%">Players</th>
                    <th width="20%">Server Ip:Port</th>
                    <th width="20%">Current Map</th>
                  </tr>
                </thead>
                <?php if ($totalRows_comunity_servers > 0) { // Show if recordset not empty ?>
                  <tbody>
                    <?php do { ?>
                      <tr>
                        <td><?php echo '<img src="/images/flags/'.$row_comunity_servers['server_location'].'.png">';?></td>
                        <td><a href="/server-details.php?id=<?php echo $row_comunity_servers['id']; ?>"><?php echo $row_comunity_servers['server_name']; ?></a> <br />
                          <small><?php echo $row_comunity_servers['server_game']; ?></small></td>
                        <td><a href="cod4://<?php echo $row_comunity_servers['server_ip']; ?>:<?php echo $row_comunity_servers['server_port']; ?>" data-toggle="tooltip" title="Join via COD4x - COD4x Client required"><img src="/images/cod4.gif" alt="cod4" class="avatar"></a></td>
                        <td class="project_progress"><div class="progress progress_sm">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php
						$online_players = $row_comunity_servers['server_online_players'];
						$max_players = $row_comunity_servers['server_maxplayers'];
						$percent = ($online_players/$max_players)*100;
						echo $percent;?>"></div>
                          </div>
                          <small><?php echo $row_comunity_servers['server_online_players']; ?>/<?php echo $row_comunity_servers['server_maxplayers']; ?></small></td>
                        <td><?php echo $row_comunity_servers['server_ip']; ?>:<?php echo $row_comunity_servers['server_port']; ?></td>
                        <td><?php echo $row_comunity_servers['server_current_map']; ?></td>
                      </tr>
                      <?php } while ($row_comunity_servers = mysql_fetch_assoc($comunity_servers)); ?>
                  </tbody>
                  <?php } // Show if recordset not empty ?>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Server Rules <small>general</small></h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="dashboard-widget-content">
                    <?php $row_comunity['server_rules'] = str_replace(array("\r\n", "\r", "\n"), "<br />", $row_comunity['server_rules']); echo $row_comunity['server_rules']; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Server admins <small>all servers</small></h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="dashboard-widget-content">
                    <?php if ($totalRows_server_admins > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <div class="col-md-6">
                          <p><?php echo $row_server_admins['username']; ?> (power <?php echo $row_server_admins['power']; ?>)</p>
                          <div class="">
                            <div class="progress progress_sm" style="width: 76%;">
                              <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php echo $row_server_admins['power']; ?>"></div>
                            </div>
                          </div>
                        </div>
                        <?php } while ($row_server_admins = mysql_fetch_assoc($server_admins)); ?>
                      <?php } // Show if recordset not empty ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Banned players <small>recently</small></h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="dashboard-widget-content">
                <?php if ($totalRows_last_bans > 0) { // Show if recordset not empty ?>
                  <ul class="list-unstyled timeline widget">
                    <?php do { ?>
                      <li>
                        <div class="block">
                          <div class="block_content">
                            <h2 class="title"> <a href="banned.php"><?php echo $row_last_bans['player_name']; ?> banned</a></h2>
                            <div class="byline"> <span><?php echo $row_last_bans['time']; ?></span> by <a href="#"><?php echo $row_last_bans['banned_by']; ?></a></div>
                            <p class="excerpt">Player <strong><?php echo $row_last_bans['player_name']; ?></strong> (GUID <strong><?php echo $row_last_bans['guid']; ?></strong>) is banned by admin <strong><?php echo $row_last_bans['banned_by']; ?></strong> on map <?php echo $row_last_bans['map']; ?>. Banned on <?php echo $row_last_bans['time']; ?>… <a href="banned.php">Read&nbsp;More</a></p>
                          </div>
                        </div>
                      </li>
                      <?php } while ($row_last_bans = mysql_fetch_assoc($last_bans)); ?>
                  </ul>
                  <?php } // Show if recordset not empty ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include("include/footer.php"); ?>
    </div>
  </div>
</div>
<?php include("include/javascript-files.php"); ?>
</body>
</html>
<?php
mysql_free_result($loggedin);

mysql_free_result($comunity_servers);

mysql_free_result($last_bans);

mysql_free_result($server_admins);

mysql_free_result($comunity);
?>

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

mysql_real_escape_string ($prvi=$_GET['id']);

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_server_details = "SELECT * FROM servers WHERE id = $prvi";
$server_details = mysql_query($query_server_details, $ststsconfig) or die(mysql_error());
$row_server_details = mysql_fetch_assoc($server_details);
$totalRows_server_details = mysql_num_rows($server_details);

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_players_online = "SELECT * FROM online_players WHERE server_id = $prvi ORDER BY player_slot ASC";
$players_online = mysql_query($query_players_online, $ststsconfig) or die(mysql_error());
$row_players_online = mysql_fetch_assoc($players_online);
$totalRows_players_online = mysql_num_rows($players_online);

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_comunity = "SELECT comunity_name FROM comunity WHERE id = 1";
$comunity = mysql_query($query_comunity, $ststsconfig) or die(mysql_error());
$row_comunity = mysql_fetch_assoc($comunity);
$totalRows_comunity = mysql_num_rows($comunity);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
$odabrana_slika=$_POST['screenshot_url'];
	
rename (''.$odabrana_slika.'', './banned/'.$odabrana_slika.'');

$link_slike='banned/'.$odabrana_slika.'';
	
  $insertSQL = sprintf("INSERT INTO banned_players (player_name, `time`, `map`, `guid`, banned_by, admin_uid, screenshot_url) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['player_name'], "text"),
                       GetSQLValueString($_POST['time'], "text"),
                       GetSQLValueString($_POST['map'], "text"),
                       GetSQLValueString($_POST['guid'], "text"),
                       GetSQLValueString($_POST['banned_by'], "text"),
                       GetSQLValueString($_POST['admin_uid'], "text"),
                       GetSQLValueString($link_slike, "text"));

  mysql_select_db($database_ststsconfig, $ststsconfig);
  $Result1 = mysql_query($insertSQL, $ststsconfig) or die(mysql_error());
  
  

  $insertGoTo = '/'.$prvi.'.html';
  header(sprintf("Location: %s", $insertGoTo));
}
if ($row_server_details['server_ip'] =='') { header("Location: /404.php");};
?>
<?php 

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_servers = "SELECT * FROM servers WHERE id='$prvi'";
$servers = mysql_query($query_servers, $ststsconfig) or die(mysql_error());
$row_servers = mysql_fetch_assoc($servers);
$totalRows_servers = mysql_num_rows($servers);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $row_server_details['server_name']; ?> CAP | Server Details</title>

<!-- Bootstrap core CSS -->

<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/fonts/css/font-awesome.min.css" rel="stylesheet">
<link href="/css/animate.min.css" rel="stylesheet">

<!-- Custom styling plus plugins -->
<link href="/css/icheck/flat/green.css" rel="stylesheet" />
<link href="/css/custom.css" rel="stylesheet">
<link href="/js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="/js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
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
              <h2><i class="fa fa-bars"></i> <?php echo $row_server_details['server_name']; ?> <small>Last scanned <?php echo $row_server_details['last_refresh']; ?></small></h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                <?php if (isset($_SESSION['MM_Username'])) {?>
                <li><a href="/get-screenshots.php?id=<?php echo $row_server_details['id']; ?>"><i class="fa fa-refresh btn btn-primary"></i></a> </li>
                <li><a href="/delete-screenshots.php"><i class="fa fa-trash btn btn-danger"></i></a> </li>
                <?php };?>
                <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Server Details</a> </li>
                  <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">New Screenshots</a> </li>
                </ul>
                <div class="clearfix"></div>
                <div id="myTabContent" class="tab-content">
                  <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                    <p>
                    <div id="load_tweets">
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
                        <tbody>
                          <tr>
                            <td><?php echo '<img src="/images/flags/'.$row_server_details['server_location'].'.png">';?></td>
                            <td><?php echo $row_server_details['server_name']; ?> <br />
                              <small><?php echo $row_server_details['server_game']; ?></small></td>
                            <td><a href="cod4://<?php echo ''.$row_server_details['server_ip'].':'.$row_server_details['server_port'].'';?>" data-toggle="tooltip" title="Join via COD4x - COD4x Client required"><img src="/images/cod4.gif" alt="cod4" class="avatar"></a></td>
                            <td class="project_progress"><div class="progress progress_sm">
                                <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php
						$online_players = $row_server_details['server_online_players'];
						$max_players = $row_server_details['server_maxplayers'];
						$percent = ($online_players/$max_players)*100;
						echo $percent;?>"></div>
                              </div>
                              <small><?php echo ''.$row_server_details['server_online_players'].'/'.$row_server_details['server_maxplayers'].''; ?></small></td>
                            <td><?php echo ''.$row_server_details['server_ip'].':'.$row_server_details['server_port'].'';?></td>
                            <td><?php echo $row_server_details['server_current_map']; ?></td>
                          </tr>
                        </tbody>
                      </table>
                      <table class="table table-striped table-bordered projects">
                        <thead>
                          <tr>
                            <th width="5%">#</th>
                            <th width="20%">Player name</th>
                            <th width="5%">Score</th>
                            <th width="15%">Ping</th>
                          </tr>
                        </thead>
                        <?php if ($totalRows_players_online > 0) { // Show if recordset not empty ?>
                          <tbody>
                            <?php do { ?>
                              <tr>
                                <td><?php echo $row_players_online['player_slot']; ?>.</td>
                                <td><?php echo $row_players_online['player_name']; ?></td>
                                <td><?php echo $row_players_online['player_score']; ?></td>
                                <td><?php echo $row_players_online['player_ping']; ?></td>
                              </tr>
                              <?php } while ($row_players_online = mysql_fetch_assoc($players_online)); ?>
                          </tbody>
                          <?php } // Show if recordset not empty ?>
                      </table>
                    </div>
                    </p>
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                    <p>
                      <?php
					
echo '

<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Player name</th>
                    <th>Time</th>
                    <th>Map</th>
					<th>Guid (last 8 characters)</th>
                    <th>Link to screenshot</th>';
            ?>
                      <?php if (isset($_SESSION['MM_Username'])) {echo'<th>Ban Player</th>';};?>
                      <?php echo'
                  </tr>
                </thead>
                <tbody>';
					
$server_ip = $row_server_details['server_ip'];
$server_port = $row_server_details['server_port'];                    
$directory = 'screenshots/';
$images = glob($directory . "*.jpg");

include("include/PHP_JPEG_Metadata_Toolkit_1.12/JPEG.php");



foreach($images as $image)
{
	
$info = exif_read_data(''.$image.'');


$path_parts = pathinfo(''.$image.'');
$player_name = $path_parts['filename'];
$player_name2 = substr_replace(''.$player_name.'','', -4);
$player_name3 = str_replace(array("_"), "\n", $player_name2);
$display_player_name = str_replace(array("^1", "^2","^3","^4","^5","^6","^7","^8","^9","^0"), "\n", $player_name3);

$podatak = get_jpeg_image_data($image);


//************************************************ Get the position of map name ************************************************
$cowpos = strpos($podatak, "mp_",3);
//************************************************ Get the position of map name ************************************************

if (strpos($podatak, 'mp_shipment') !== false) {
$map_name = substr($podatak,"$cowpos",11);	
}
if (strpos($podatak, 'mp_showdown') !== false) {
$map_name = substr($podatak,"$cowpos",11);	
}
if (strpos($podatak, 'mp_strike') !== false) {
$map_name = substr($podatak,"$cowpos",9);	
}
if (strpos($podatak, 'mp_vacant') !== false) {
$map_name = substr($podatak,"$cowpos",9);	
}
if (strpos($podatak, 'mp_cargoship') !== false) {
$map_name = substr($podatak,"$cowpos",12);	
}
if (strpos($podatak, 'mp_crash_snow') !== false) {
$map_name = substr($podatak,"$cowpos",13);	
}
if (strpos($podatak, 'mp_convoy') !== false) {
$map_name = substr($podatak,"$cowpos",9);	
}
if (strpos($podatak, 'mp_backlot') !== false) {
$map_name = substr($podatak,"$cowpos",10);	
}
if (strpos($podatak, 'mp_bloc') !== false) {
$map_name = substr($podatak,"$cowpos",7);	
}
if (strpos($podatak, 'mp_bog') !== false) {
$map_name = substr($podatak,"$cowpos",6);	
}
if (strpos($podatak, 'mp_broadcast') !== false) {
$map_name = substr($podatak,"$cowpos",12);	
}
if (strpos($podatak, 'mp_carentan') !== false) {
$map_name = substr($podatak,"$cowpos",11);	
}
if (strpos($podatak, 'mp_countdown') !== false) {
$map_name = substr($podatak,"$cowpos",12);	
}
if (strpos($podatak, 'mp_crash') !== false) {
$map_name = substr($podatak,"$cowpos",8);	
}
if (strpos($podatak, 'mp_creek') !== false) {
$map_name = substr($podatak,"$cowpos",8);	
}
if (strpos($podatak, 'mp_crossfire') !== false) {
$map_name = substr($podatak,"$cowpos",12);	
}
if (strpos($podatak, 'mp_citystreets') !== false) {
$map_name = substr($podatak,"$cowpos",14);	
}
if (strpos($podatak, 'mp_farm') !== false) {
$map_name = substr($podatak,"$cowpos",7);	
}
if (strpos($podatak, 'mp_killhouse') !== false) {
$map_name = substr($podatak,"$cowpos",12);	
}
if (strpos($podatak, 'mp_overgrown') !== false) {
$map_name = substr($podatak,"$cowpos",12);	
}
if (strpos($podatak, 'mp_pipeline') !== false) {
$map_name = substr($podatak,"$cowpos",11);	
}

//************************************************ Get the position of date time ************************************************
$show_screenshot_date_time = substr($podatak,-22);
//************************************************ Get the position of date time ************************************************

$list_of_maps[] = 'mp_convoy Ambush';
$list_of_maps[] = 'mp_backlot Backlot';
$list_of_maps[] = 'mp_bloc Bloc';
$list_of_maps[] = 'mp_bog Bog';
$list_of_maps[] = 'mp_broadcast Broadcast'; //1.6
$list_of_maps[] = 'mp_carentan Chinatown'; //1.6
$list_of_maps[] = 'mp_countdown Countdown';
$list_of_maps[] = 'mp_crash Crash';
$list_of_maps[] = 'mp_creek Creek'; //1.6
$list_of_maps[] = 'mp_crossfire Crossfire';
$list_of_maps[] = 'mp_citystreets District';
$list_of_maps[] = 'mp_farm Downpour';
$list_of_maps[] = 'mp_killhouse Killhouse'; //1.6
$list_of_maps[] = 'mp_overgrown Overgrown';
$list_of_maps[] = 'mp_pipeline Pipeline';
$list_of_maps[] = 'mp_shipment Shipment';
$list_of_maps[] = 'mp_showdown Showdown';
$list_of_maps[] = 'mp_strike Strike';
$list_of_maps[] = 'mp_vacant Vacant';
$list_of_maps[] = 'mp_cargoship Wet Work';
$list_of_maps[] = 'mp_crash_snow Winter Crash'; //1.4

$curmap2 =$map_name;
$guidpart2 = substr($podatak,-36,-26);
foreach ($list_of_maps as $map2)
				{
			    $t = explode(' ',$map2,2);
			    if ($t[0] == $curmap2)
					{
					$curmap2 = $t[1];
					break;
					}
				}

//Friendly Map names
$friendly_map_name = $curmap2;
				
$info = exif_read_data(''.$image.'');
$datum = date('d:m:Y', $info['FileDateTime']);


?>
                      <?php 
				  
				  $show_screenshot_date_time2 = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $show_screenshot_date_time);
				  $guidpart3 = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $guidpart2);
				  echo'  <tr>
                      <td>'.$display_player_name.'</td>
                      <td>'.$show_screenshot_date_time2.'</td>
                      <td>'.$friendly_map_name.'</td>
					  <td>'.$guidpart3.'</td>
                      <td><a href="'.$image.'?'.date('U').'" target="_blank"><i class="fa fa-camera"></i> Screenshot</a></td>';?>
                      <?php if (isset($_SESSION['MM_Username'])) { echo '<td>
			<form method="post" name="form1" action="'.$editFormAction.'">
						
                   	 <input type="submit" class="btn btn-danger btn-sm" value="Add player to banned list">
                     <input type="hidden" name="player_name" value="'.$display_player_name.'">
                     <input type="hidden" name="time" value="'.$show_screenshot_date_time2.'">
                     <input type="hidden" name="map" value="'.$friendly_map_name.'">
                     <input type="hidden" name="guid" value="'.$guidpart3.'">
                     <input type="hidden" name="banned_by" value="'.$row_loggedin['username'].'">
                     <input type="hidden" name="admin_uid" value="'.$row_loggedin['admin_uid'].'">
                     <input type="hidden" name="screenshot_url" value="'.$image.'">
                     <input type="hidden" name="MM_insert" value="form1">
                    </form>
			</td>';}?>
                      <?php echo'
                    </tr>';
                    
}; echo'</tbody>
              </table>';
?> </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12 widget widget_tally_box">
          <div class="x_panel">
            <div class="x_title">
              <h2>Players stats</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="row">
                <div class="col-xs-4"> <span class="chart" data-percent="<?php echo $percent;?>"> <span class="percent"></span> </span> </div>
                <div class="col-xs-8">
                  <p>Maximum players<br />
                    <?php echo $row_server_details['server_maxplayers']; ?></p>
                  <p>Online players<br />
                    <?php echo $online_players; ?></p>
                </div>
              </div>
              <div class="row">
                <div class="x_title">
                  <h2>Current map</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="col-xs-12"> <img src="/images/maps/cod4/<?php echo $row_server_details['server_current_map_alias']; ?>.jpg" class="img-responsive img-thumbnail" alt="<?php echo $row_server_details['server_current_map']; ?>"> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include("template_include/footer.php"); ?>
    </div>
  </div>
</div>
<?php include("template_include/javascript-files.php"); ?>
<script src="/js/easypie/jquery.easypiechart.min.js"></script> 
<!-- Datatables --> 
<!-- <script src="/js/datatables/js/jquery.dataTables.js"></script>
  <script src="/js/datatables/tools/js/dataTables.tableTools.js"></script> --> 

<!-- Datatables--> 
<script src="/js/datatables/jquery.dataTables.min.js"></script> 
<script src="/js/datatables/dataTables.bootstrap.js"></script> 
<script src="/js/datatables/dataTables.buttons.min.js"></script> 
<script src="/js/datatables/buttons.bootstrap.min.js"></script> 
<script src="/js/datatables/dataTables.fixedHeader.min.js"></script> 
<script src="/js/datatables/dataTables.keyTable.min.js"></script> 
<script src="/js/datatables/dataTables.responsive.min.js"></script> 
<script src="/js/datatables/responsive.bootstrap.min.js"></script> 
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
<script>
    $(function() {
      $('.chart').easyPieChart({
        easing: 'easeOutBounce',
        lineWidth: '6',
        barColor: '#75BCDD',
        onStep: function(from, to, percent) {
          $(this.el).find('.percent').text(Math.round(percent));
        }
      });
      var chart = window.chart = $('.chart').data('easyPieChart');
      $('.js_update').on('click', function() {
        chart.update(Math.random() * 200 - 100);
      });

      //hover and retain popover when on popover content
      var originalLeave = $.fn.popover.Constructor.prototype.leave;
      $.fn.popover.Constructor.prototype.leave = function(obj) {
        var self = obj instanceof this.constructor ?
          obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
        var container, timeout;

        originalLeave.call(this, obj);

        if (obj.currentTarget) {
          container = $(obj.currentTarget).siblings('.popover')
          timeout = self.timeout;
          container.one('mouseenter', function() {
            //We entered the actual popover – call off the dogs
            clearTimeout(timeout);
            //Let's monitor popover content instead
            container.one('mouseleave', function() {
              $.fn.popover.Constructor.prototype.leave.call(self, self);
            });
          })
        }
      };
      $('body').popover({
        selector: '[data-popover]',
        trigger: 'click hover',
        delay: {
          show: 50,
          hide: 400
        }
      });

    });
  </script> 
<script type="text/javascript">
    $(document).ready(function(){
      refreshTable();
    });

    function refreshTable(){
        $('#tableHolder').load('refresh-servers-cronjob.php?id=<?php echo $row_players_online['id']; ?>', function(){
           setTimeout(refreshTable, 5000);
        });
    }
</script>
</body>
</html>
<?php
mysql_free_result($loggedin);

mysql_free_result($server_details);

mysql_free_result($players_online);

mysql_free_result($comunity);
?>

<?php include_once('../Connections/ststsconfig.php'); ?>
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

//if ($row_server_details['server_ip'] =='') { header("Location: /404.php");};
?>
<?php 

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_servers = "SELECT * FROM servers";
$servers = mysql_query($query_servers, $ststsconfig) or die(mysql_error());
$row_servers = mysql_fetch_assoc($servers);
$totalRows_servers = mysql_num_rows($servers);

if ($totalRows_servers > 0) {?>
<?php
include('../include/geoip.inc.php');
include ("../include/COD4ServerStatus.php");

?>




<?php do { ?>



<?php

$prvi= $row_servers['id'];				
$server_ip = $row_servers['server_ip'];
$server_port = $row_servers['server_port'];
$status = new COD4ServerStatus(''.$server_ip.'', ''.$server_port.''); //Edit IP
$ip = "''.$server_ip.''.''.$server_port.''"; //Edit IP
if ($status->getServerStatus()){
	$status->parseServerData();
	$serverStatus = $status->returnServerData();
	$players = $status->returnPlayers();
	$pings = $status->returnPings();
	$scores = $status->returnScores();
		if((strpos($server_ip, ":") === false)) {
			$gi = geoip_open("../include/GeoIP.dat",GEOIP_STANDARD);
			$country = geoip_country_code_by_addr($gi, $server_ip);
		}
		else {
			$gi = geoip_open("../include/GeoIPv6.dat",GEOIP_STANDARD);
			$country = geoip_country_code_by_addr_v6($gi, $server_ip);
		}
	include("../include/friendly_mapname.php");
	$server_name2=$serverStatus['sv_hostname'] = str_replace(array("^1", "^2","^3","^4","^5","^6","^7","^8","^9","^0"), "\n", $serverStatus['sv_hostname']);
	$server_online_players2 = sizeof($players);
	$server_maxplayers2 = $serverStatus['sv_maxclients'];
	$server_game2='Call of Duty 4 Server';
	$server_current_map2=$fmapname;
	$server_current_map_alias2=$serverStatus['mapname'];
	$server_location2=$country;
	$last_refresh2=date ("Y-m-d h:i:s");
};

if (empty($server_name2)){
exit();
}else{
	
mysql_select_db($database_ststsconfig, $ststsconfig);
$query_confirm = ("UPDATE servers SET server_name = '$server_name2', server_ip = '$server_ip', server_port = '$server_port', server_maxplayers = '$server_maxplayers2', server_online_players = '$server_online_players2', server_current_map = '$server_current_map2', server_current_map_alias = '$server_current_map_alias2', server_location='$server_location2', last_refresh = '$last_refresh2' WHERE id='$prvi'");
$confirm = mysql_query($query_confirm, $ststsconfig) or die(mysql_error());
mysql_select_db($database_ststsconfig, $ststsconfig);
$query_confirm3 = ("DELETE FROM online_players WHERE server_id='$prvi'");
$confirm3 = mysql_query($query_confirm3, $ststsconfig) or die(mysql_error());
            $rank = 1;
            foreach($players as $i => $v){
				 $server_id=$row_servers['id'];
				 $player_slot2=$rank;
				 $player_name2=$players[$i] = str_replace(array("^1", "^2","^3","^4","^5","^6","^7","^8","^9","^0","*",".","'","#"), "", $players[$i]);
				 $player_score2=$scores[$i];
				 $player_ping2=$pings[$i];
				 $player_country2='nun';
				 $rank++;
				mysql_select_db($database_ststsconfig, $ststsconfig);
				$query_confirm2 = ("INSERT INTO online_players SET server_id = '$prvi', player_slot = '$player_slot2', player_name = '$player_name2', player_score = '$player_score2', player_ping = '$player_ping2', player_country = '$player_country2'");
				$confirm2 = mysql_query($query_confirm2, $ststsconfig) or die(mysql_error());
			}
};

?>
<?php } while ($row_servers = mysql_fetch_assoc($servers));
};?>
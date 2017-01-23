<?php
mysql_select_db($database_ststsconfig, $ststsconfig);
$query_servers = "SELECT * FROM servers WHERE id='$prvi'";
$servers = mysql_query($query_servers, $ststsconfig) or die(mysql_error());
$row_servers = mysql_fetch_assoc($servers);
$totalRows_servers = mysql_num_rows($servers);
?>
<?php if ($totalRows_servers > 0) {?>
<?php
include_once('include/geoip.inc.php');
include ("include/COD4ServerStatus.php");
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
$gi = geoip_open("include/GeoIP.dat",GEOIP_STANDARD);
$country = geoip_country_code_by_addr($gi, $server_ip);
}
else {
$gi = geoip_open("include/GeoIPv6.dat",GEOIP_STANDARD);
$country = geoip_country_code_by_addr_v6($gi, $server_ip);
}
include("include/friendly_mapname.php");
$server_name2=$serverStatus['sv_hostname'] = str_replace(array("^1", "^2","^3","^4","^5","^6","^7","^8","^9","^0"), "\n", $serverStatus['sv_hostname']);
$server_online_players2 = sizeof($players);
$server_maxplayers2 = $serverStatus['sv_maxclients'];
$server_game2='Call of Duty 4 Server';
$server_current_map2=$fmapname;
$server_current_map_alias2=$serverStatus['mapname'];
$server_location2=$country;
$last_refresh2=date ("Y-m-d h:i:s");
};
if ($server_name2!='') {
$ftp1 = ftp_connect($row_servers['server_ip']) or die("Couldn't connect to server");
ftp_login($ftp1,$row_servers['ftp_username'],$row_servers['ftp_password']);
ftp_pasv($ftp1, TRUE); //Passive Mode is better for this
$dir = './'.$row_servers['screenshots_dir'].'';
$conn_id = $ftp1;
function filecollect($dir,$filelist) {
global $ftp1;
$files = ftp_nlist($ftp1,$dir);

$serverip=$row_servers['server_ip'];
$serverport=$row_servers['server_port'];

foreach ($files as $file) {
$isfile = ftp_size($ftp1, $file);
if($isfile == "-1") {
  $filelist = filecollect($dir.'/'.$file,$filelist,$num);
}
else {
  $filelist[(count($filelist)+1)] = $file;
	$path_parts = pathinfo($file);
	$name1 = $path_parts['extension'];
	$name2 = $path_parts['filename'];
	$serverip=$row_servers['server_ip'];
	$serverport=$row_servers['server_port'];
	$local_file = 'screenshots/'.$name2.'.'.$name1.'';
	$down = $file;
	ftp_get($ftp1,$local_file, $down, FTP_BINARY);
	ftp_delete($ftp1, $file);
}
}
return $filelist;
}
//$filelist = filecollect($dir,$filelist);
ftp_close($conn_id);
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
			 $player_name2=$players[$i] = str_replace(array("^1", "^2","^3","^4","^5","^6","^7","^8","^9","^0"), "\n", $players[$i]);
             $player_score2=$scores[$i];
             $player_ping2=$pings[$i];
			 $player_country2='nun';
             $rank++;
mysql_select_db($database_ststsconfig, $ststsconfig);
$query_confirm2 = ("INSERT INTO online_players SET server_id = '$prvi', player_slot = '$player_slot2', player_name = '$player_name2', player_score = '$player_score2', player_ping = '$player_ping2', player_country = '$player_country2'");
$confirm2 = mysql_query($query_confirm2, $ststsconfig) or die(mysql_error());           }
};
};
?>
<?php
mysql_free_result($servers);
?>

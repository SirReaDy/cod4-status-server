<?php require_once('../Connections/ststsconfig.php'); ?>
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

$MM_restrictGoTo = "../login.php";
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

$colname_kosam = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_kosam = $_SESSION['MM_Username'];
}
mysql_select_db($database_ststsconfig, $ststsconfig);
$query_kosam = sprintf("SELECT * FROM administrators WHERE username = %s", GetSQLValueString($colname_kosam, "text"));
$kosam = mysql_query($query_kosam, $ststsconfig) or die(mysql_error());
$row_kosam = mysql_fetch_assoc($kosam);
$totalRows_kosam = mysql_num_rows($kosam);

mysql_real_escape_string ($prvi=$_GET['id']);

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_servers = "SELECT * FROM servers WHERE id='$prvi' LIMIT 1";
$servers = mysql_query($query_servers, $ststsconfig) or die(mysql_error());
$row_servers = mysql_fetch_assoc($servers);
$totalRows_servers = mysql_num_rows($servers);


?>
<?php if ($totalRows_servers > 0) { // Show if recordset not empty ?>
<?php
include_once('../include/geoip.inc.php');
include ("../include/COD4ServerStatus.php");
?>
<?php 					
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
						
//set an IPv6 address for testing
if((strpos($server_ip, ":") === false)) {
//server_ipv4
$gi = geoip_open("../include/GeoIP.dat",GEOIP_STANDARD);
$country = geoip_country_code_by_addr($gi, $server_ip);
}
else {
//ipv6
$gi = geoip_open("../include/GeoIPv6.dat",GEOIP_STANDARD);
$country = geoip_country_code_by_addr_v6($gi, $server_ip);
}
include("../include/friendly_mapname.php");
	
$server_name2=$serverStatus['sv_hostname'] = str_replace(array("^1", "^2","^3","^4","^5","^6","^7","^8","^9","^0"), "\n", $serverStatus['sv_hostname']);echo $serverStatus['sv_hostname'];
$server_online_players2 = sizeof($players);
$server_maxplayers2 = $serverStatus['sv_maxclients'];
$server_game2='Call of Duty 4 Server';
$server_current_map2=$fmapname;
$server_current_map_alias2=$serverStatus['mapname'];
$server_location2=$country;
$last_refresh2=date ("Y-m-d h:i:s");
};
if ($serverStatus!='') {
if ($server_name2!='') {
	
//Download screenshots
$ftp1 = ftp_connect($row_servers['server_ip']) or die("Couldn't connect to server");
ftp_login($ftp1,$row_servers['ftp_username'],$row_servers['ftp_password']);
ftp_pasv($ftp1, TRUE); //Passive Mode is better for this

//Get them file!
//echo "Collecting Files from game server<br>";
//Set defaults just in case. PHP complains anyway if we don't.
$dir = './'.$row_servers['screenshots_dir'].'';
$conn_id = $ftp1;
function filecollect($dir,$filelist) {
global $ftp1; //Get our ftp
$files = ftp_nlist($ftp1,$dir); //get files in directory

$serverip=$row_servers['server_ip'];
$serverport=$row_servers['server_port'];

foreach ($files as $file) {
$isfile = ftp_size($ftp1, $file);

if($isfile == "-1") { //Is a file or directory?
  $filelist = filecollect($dir.'/'.$file,$filelist,$num); //If a folder, do a filecollect on it
  
  
}
else {
  $filelist[(count($filelist)+1)] = $file; //If not, add it as a file to the file list
  
	$path_parts = pathinfo($file);
	
	$name1 = $path_parts['extension'];
	$name2 = $path_parts['filename'];
	$serverip=$row_servers['server_ip'];
	$serverport=$row_servers['server_port'];
	$local_file = '../screenshots/'.$serverip.''.$serverport.'/'.$name2.'.'.$name1.'';
	$down = $file;
	ftp_get($ftp1,$local_file, $down, FTP_BINARY);
	
	ftp_delete($ftp1, $file);
}
}
return $filelist;
}
$filelist = filecollect($dir,$filelist);
// close the connection
ftp_close($conn_id);
//End download screenshots	

	
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
             
             
                $rank++;
				
				
mysql_select_db($database_ststsconfig, $ststsconfig);
$query_confirm2 = ("INSERT INTO online_players SET server_id = '$prvi', player_slot = '$player_slot2', player_name = '$player_name2', player_score = '$player_score2', player_ping = '$player_ping2', player_country = '$player_country2'");
$confirm2 = mysql_query($query_confirm2, $ststsconfig) or die(mysql_error());

            }
header ('Location: /'.$row_servers['id'].'.html');

};
};
if ($serverStatus=='') {header ('Location: /'.$row_servers['id'].'.html');};
?>
  <?php } while ($row_servers = mysql_fetch_assoc($servers)); ?>
</body>
</html>
<?php
mysql_free_result($kosam);
mysql_free_result($servers);
?>

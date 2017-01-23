<?php include_once('../Connections/ststsconfig.php'); ?>
<?php

mysql_real_escape_string ($prvi=$_GET['id']);			

mysql_select_db($database_ststsconfig, $ststsconfig);
$query_servers = "SELECT * FROM servers WHERE id='$prvi'";
$servers = mysql_query($query_servers, $ststsconfig) or die(mysql_error());
$row_servers = mysql_fetch_assoc($servers);
$totalRows_servers = mysql_num_rows($servers);
?>

<?php if ($totalRows_servers > 0) {?>
<?php
$ftp1 = ftp_connect($row_servers['server_ip']) or die("Couldn't connect to server");
ftp_login($ftp1,$row_servers['ftp_username'],$row_servers['ftp_password']);
ftp_pasv($ftp1, TRUE); //Passive Mode is better for this
$dir = './'.$row_servers['screenshots_dir'].'';
$conn_id = $ftp1;
function filecollect($dir,$filelist) {
global $ftp1;
$files = ftp_nlist($ftp1,$dir);
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
	$local_file = '../screenshots/'.$name2.'.'.$name1.'';
	$down = $file;
	ftp_get($ftp1,$local_file, $down, FTP_BINARY);
	//ftp_delete($ftp1, $file);
}
}
return $filelist;
}
$filelist = filecollect($dir,$filelist);
ftp_close($conn_id);
?>
<?php } while ($row_servers = mysql_fetch_assoc($servers)); ?>

<?php

if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

mysql_free_result($servers);


?>

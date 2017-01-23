<?php include_once('Connections/ststsconfig.php'); ?>
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
         
		$file = './logs/'.$row_servers['id'].'/adminactions.log';
		if (file_exists($file)) { unlink ($file); }
		 
		 
		 
		 //create directory if it dont exist
		 if (!file_exists('logs/'.$row_servers['id'].'')) {
    			mkdir('logs/'.$row_servers['id'].'', 0777, true);
			}
		 
		 
		        // define some variables
        $folder_path = "./logs";
        $local_file = './logs/'.$row_servers['id'].'/adminactions.log';
        $server_file = "/main/adminactions.log";
       
        //-- Connection Settings
        $ftp_server = $row_servers['server_ip']; // Address of FTP server.
        $ftp_user_name = $row_servers['ftp_username']; // Username
        $ftp_user_pass = $row_servers['ftp_password']; // Password
        #$destination_file = "FILEPATH";
       
        // set up basic connection
        $conn_id = ftp_connect($ftp_server);
       
        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
       
        // try to download $server_file and save to $local_file
        if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
            echo "Successfully written to $local_file\n";
        } else {
            echo "There was a problem\n";
        }
       
        // close the connection
        ftp_close($conn_id);
?>




<?php

if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

mysql_free_result($servers);

}
?>

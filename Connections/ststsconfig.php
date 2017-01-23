<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_ststsconfig = "capitains.com";
$database_ststsconfig = "cap_server";
$username_ststsconfig = "cap_server";
$password_ststsconfig = "cap_server";
$ststsconfig = mysql_connect($hostname_ststsconfig, $username_ststsconfig, $password_ststsconfig) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
<?php
mysql_query("SET character_set_client = 'utf8'");
mysql_query("SET character_set_connection = 'utf8'");
mysql_query("SET character_set_results = 'utf8'");
mysql_query("SET character_set_server = 'utf8'");
?>
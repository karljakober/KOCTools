<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("dbconfig.php");
mysql_connect($dbhost,$dbusername,$dbpassword) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());

if(empty($_GET)) {
    echo "wtf are you doing here";
} else {
	if ($_GET['version'] != '0.9.2') {
		echo 'You are using an old version. Go to http://koctools.com/ for a new version.';
		exit();
	} else {
		$host = str_replace("www", "", $_GET['host']);
		$query = mysql_query("SELECT * FROM `servers` WHERE `serverid` = '" . mysql_real_escape_string($host) . "'");
		unset($_GET['host']);
		
		if (!mysql_num_rows($query) && isset($_GET['servername'])) {
			mysql_query("INSERT IGNORE INTO `servers` (`servername`,`serverid`) VALUES ('" . strtolower($_GET['servername']) . "','" . $host . "');") or die(mysql_error());
		} elseif (!mysql_num_rows($query) && !isset($_GET['servername'])) {
			echo '2 row 16';
			mysql_close();
			exit();
		}

		$result = mysql_fetch_array($query);
		unset($_GET['servername']);
		if (isset($_GET['fb_sig']) && isset($_GET['fb_sig_expires'])) {
			if ($result['queuedtime'] == 0 && $result['updating'] == 0){ 
				$output_url = 'kingdomsofcamelot.com/fb/e2/src/ajax/fetchMapTiles.php?';
				foreach($_GET as $k => $v){
					$output_url .= $k.'='.$v.'&';
				}
				mysql_query("UPDATE servers SET queuedtime = '" . time() . "', url = '" . mysql_real_escape_string($output_url) . "' WHERE `serverid` = '" . mysql_real_escape_string($host) . "'");

				echo '1';
			} else {
				echo 'Server currently in queue';
			}
		} else {
			echo '2 row 24';
		}
	}
}
mysql_close();
exit();
?>
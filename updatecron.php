<?php
	require_once("dbconfig.php");
	mysql_connect($dbhost, $dbusername, $dbpassword) or die(mysql_error());
	mysql_select_db($dbname) or die(mysql_error());
	$query = mysql_query("SELECT * FROM `servers` WHERE `updating` = '1'");
	if(mysql_num_rows($query) == 0) {
		$query = mysql_query("SELECT * FROM `servers` WHERE `queuedtime` != '0' ORDER BY `queuedtime` LIMIT 1");
		$result = mysql_fetch_array($query);
		if(mysql_num_rows($query) == 1) {
			exec("/usr/bin/php /var/koctools.com/updatecli.php " . $result['servername'] . " >/dev/null &");
			mysql_query("UPDATE servers SET queuedtime = '0' WHERE `servername` = '" . $result['servername'] . "'");
			echo "updating top of queue server";
		} else {
			echo "no server in queue";
		}
	} else {
		echo "there is already a server updating!";
	}
?>

<?php
require_once("dbconfig.php");
mysql_connect($dbhost,$dbusername,$dbpassword) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());
if(isset($_POST['server']) && (isset($_POST['alliance']) || isset($_POST['player']))) {
	if (isset($_POST['player'])) {
		$what = mysql_real_escape_string($_POST['player']);
		if(strlen($what) > 0) {
			$query = mysql_query("SELECT DISTINCT player FROM `" . mysql_real_escape_string($_POST['server']) . "` WHERE `player` LIKE '" . $what . "%' LIMIT 10");
			if($query) {
				while ($result = mysql_fetch_array($query)) {
					echo "<li onclick=\"fillplayer('" . $result['player'] . "');\">" . $result['player'] . "</li>";
				}
			} else {
				echo 'ERROR: There was a problem with the query.';
			}
		}
	} else {
		$what = mysql_real_escape_string($_POST['alliance']);
		if(strlen($what) > 0) {
			$query = mysql_query("SELECT DISTINCT alliance FROM `" . mysql_real_escape_string($_POST['server']) . "` WHERE `alliance` LIKE '" . $what . "%' LIMIT 10");
			if($query) {
				while ($result = mysql_fetch_array($query)) {
					echo "<li onclick=\"fillalliance('" . $result['alliance'] . "');\">" . $result['alliance'] . "</li>";
				}
			} else {
				echo 'ERROR: There was a problem with the query.';
			}
		}
	}
	
} else {
	echo 'blah';
}
?>
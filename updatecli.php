<?php
require_once("dbconfig.php");
mysql_connect($dbhost,$dbusername,$dbpassword) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());
function createTables() {
	mysql_query("CREATE TABLE IF NOT EXISTS `" . $_SERVER['argv']['1'] . "` (
	  `townname` varchar(255) NOT NULL,
	  `player` varchar(255) NOT NULL,
	  `x` int(11) NOT NULL,
	  `y` int(11) NOT NULL,
	  `alliance` varchar(255) DEFAULT NULL,
	  `might` int(255) NOT NULL,
	  `misted` tinyint(1) DEFAULT '0'
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;") or die(mysql_error());

	mysql_query("CREATE TABLE IF NOT EXISTS `" . $_SERVER['argv']['1'] . "_wilderness` (
	  `type` varchar(255) NOT NULL,
	  `player` varchar(255) NOT NULL,
	  `x` int(11) NOT NULL,
	  `y` int(11) NOT NULL,
	  `level` int(11) NOT NULL,
	  `alliance` varchar(255) DEFAULT NULL,
	  `might` int(255) NOT NULL,
	  `misted` tinyint(1) DEFAULT '0'
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;") or die(mysql_error());

	mysql_query("CREATE TABLE IF NOT EXISTS `" . $_SERVER['argv']['1'] . "_barbarian` (
	  `x` int(11) NOT NULL,
	  `y` int(11) NOT NULL,
	  `level` int(11) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;") or die(mysql_error());

}

function get_web_page($url) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 9999999,      // timeout on connect
        CURLOPT_TIMEOUT        => 9999999,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}

function populateSlots($data, $alliancenames, $userinfo) {
	$types = array(
		0 => "bog",
		10 => "grassland",
		11 => "lake",
		20 => "woods",
		30 => "hills",
		40 => "mountain",
		50 => "plain",
		51 => "city",
		52 => "ruin",
		101 => "camelot1",
		102 => "camelot2",
		103 => "camelot3",
		104 => "camelot4",
		105 => "camelot5",
		106 => "camelot6"
	);
	echo $data;
	$arrkeys = array_keys($data);
	if ($data == NULL) {
		echo $bigurl;
	}
	$nokeys = count($arrkeys);
	for ($i = 0; $i < $nokeys; $i++) {
		$name = NULL;
		$might = NULL;
		$xcoord = NULL;
		$ycoord = NULL;
		$alliance = NULL;
		$misted = 0;
		$currentkey = $arrkeys[$i];
		$withword = "tip_" . $arrkeys[$i];
		$gotten = $data[$currentkey];
		$xcoord = intval($gotten['xCoord']);
		$ycoord = intval($gotten['yCoord']);
		if ($gotten['misted']) {
			$misted = 1;
		}
		$type = $types[$gotten['tileType']];
		if($gotten['tileUserId']) {
			$user = $userinfo["u" . $gotten['tileUserId']];
			if ($user && count($user) != 0) {
				$name = $user['n'];
				$might = $user['m'];
				
				if ($user['a'] && $user['a'] != 0) {
					$alliance = $alliancenames["a" . $user['a']];
				} else {
					$alliance = NULL;
				}
			}
		}
		if ($type == "city" && $gotten['tileUserId']) {
			echo "Entering City... <br />";
			mysql_query("INSERT IGNORE INTO " . $_SERVER['argv']['1'] . " (`townname`,`player`,`might`,`alliance`,`x`,`y`, `misted`) VALUES ('" . addslashes($gotten['cityName']) . "','" . addslashes($name) . "','" . $might . "','" . addslashes($alliance) . "','" . $xcoord . "','" . $ycoord . "'," . $misted . ");") or die(mysql_error());
		}
		if ($type == "grassland" || $type == "lake" || $type == "woods" || $type == "hills" || $type == "mountain" || $type == "plain") {
			echo "Entering Wilderness... <br />";
			mysql_query("INSERT IGNORE INTO " . $_SERVER['argv']['1'] . "_wilderness (`type`,`player`,`might`,`level`,`alliance`,`x`,`y`,`misted`) VALUES ('" . $type . "','" . addslashes($name) . "','" . $might . "','" . intval($gotten['tileLevel']) . "','" . addslashes($alliance) . "','" . $xcoord . "','" . $ycoord . "'," . $misted . ");") or die(mysql_error());
		}
		if ($type == "city" && !$gotten['tileUserId']) {
			echo "Entering Barbarian... <br/>";
			mysql_query("INSERT IGNORE INTO " . $_SERVER['argv']['1'] . "_barbarian (`x`,`y`,`level`) VALUES ('" . $xcoord . "','" . $ycoord . "','" . intval($gotten['tileLevel']) . "');") or die(mysql_error());
		}
	}
} 
	
$query = mysql_query("SELECT * FROM `servers` WHERE `servername` = '" . $_SERVER['argv']['1'] . "' LIMIT 1");
mysql_query("UPDATE `servers` SET `lastupdated` = '" . time() . "' WHERE `servername` = '" . $_SERVER['argv']['1'] . "'") or die(mysql_error());
$result = mysql_fetch_array($query);

$urlstart = "http://www" . $result['serverid'] . ".";

$urlmid = $result['url'];
$urlend = "&blocks=bl_0_bt_0,bl_0_bt_5,bl_0_bt_10,bl_0_bt_15,bl_0_bt_20,bl_0_bt_25,bl_0_bt_30,bl_0_bt_35,bl_0_bt_40,bl_0_bt_45,bl_0_bt_50,bl_0_bt_55,bl_0_bt_60,bl_0_bt_65,bl_0_bt_70,bl_0_bt_75,bl_0_bt_80,bl_0_bt_85,bl_0_bt_90,bl_0_bt_95,bl_0_bt_100,bl_0_bt_105,bl_0_bt_110,bl_0_bt_115,bl_0_bt_120,bl_0_bt_125,bl_0_bt_130,bl_0_bt_135,bl_0_bt_140,bl_0_bt_145,bl_0_bt_150,bl_0_bt_155,bl_0_bt_160,bl_0_bt_165,bl_0_bt_170,bl_0_bt_175,bl_0_bt_180,bl_0_bt_185,bl_0_bt_190,bl_0_bt_195,bl_0_bt_200,bl_0_bt_205,bl_0_bt_210,bl_0_bt_215,bl_0_bt_220,bl_0_bt_225,bl_0_bt_230,bl_0_bt_235,bl_0_bt_240,bl_0_bt_245,bl_0_bt_250,bl_0_bt_255,bl_0_bt_260,bl_0_bt_265,bl_0_bt_270,bl_0_bt_275,bl_0_bt_280,bl_0_bt_285,bl_0_bt_290,bl_0_bt_295,bl_0_bt_300,bl_0_bt_305,bl_0_bt_310,bl_0_bt_315,bl_0_bt_320,bl_0_bt_325,bl_0_bt_330,bl_0_bt_335,bl_0_bt_340,bl_0_bt_345,bl_0_bt_350,bl_0_bt_355,bl_0_bt_360,bl_0_bt_365,bl_0_bt_370,bl_0_bt_375,bl_0_bt_380,bl_0_bt_385,bl_0_bt_390,bl_0_bt_395,bl_0_bt_400,bl_0_bt_405,bl_0_bt_410,bl_0_bt_415,bl_0_bt_420,bl_0_bt_425,bl_0_bt_430,bl_0_bt_435,bl_0_bt_440,bl_0_bt_445,bl_0_bt_450,bl_0_bt_455,bl_0_bt_460,bl_0_bt_465,bl_0_bt_470,bl_0_bt_475,bl_0_bt_480,bl_0_bt_485,bl_0_bt_490,bl_0_bt_495,bl_0_bt_500,bl_0_bt_505,bl_0_bt_510,bl_0_bt_515,bl_0_bt_520,bl_0_bt_525,bl_0_bt_530,bl_0_bt_535,bl_0_bt_540,bl_0_bt_545,bl_0_bt_550,bl_0_bt_555,bl_0_bt_560,bl_0_bt_565,bl_0_bt_570,bl_0_bt_575,bl_0_bt_580,bl_0_bt_585,bl_0_bt_590,bl_0_bt_595,bl_0_bt_600,bl_0_bt_605,bl_0_bt_610,bl_0_bt_615,bl_0_bt_620,bl_0_bt_625,bl_0_bt_630,bl_0_bt_635,bl_0_bt_640,bl_0_bt_645,bl_0_bt_650,bl_0_bt_655,bl_0_bt_660,bl_0_bt_665,bl_0_bt_670,bl_0_bt_675,bl_0_bt_680,bl_0_bt_685,bl_0_bt_690,bl_0_bt_695,bl_0_bt_700,bl_0_bt_705,bl_0_bt_710,bl_0_bt_715,bl_0_bt_720,bl_0_bt_725,bl_0_bt_730,bl_0_bt_735,bl_0_bt_740,bl_0_bt_745";

$bigurl = $urlstart . $urlmid . $urlend;

createTables();
mysql_query("UPDATE `servers` SET `updating` = '1' WHERE `servername` = '" . $_SERVER['argv']['1'] . "'");
for ( $counter = 5; $counter <= '750'; $counter += 5) {
	if ($counter == 5) {
		$str = get_web_page($urlstart . $urlmid . $urlend);
		$obj = json_decode($str['content'], true);
		if (is_array($obj) && $obj['ok']) {
			mysql_query("TRUNCATE TABLE `" . $_SERVER['argv']['1'] . "`") or die(mysql_error());
			mysql_query("TRUNCATE TABLE `" . $_SERVER['argv']['1'] . "_wilderness`") or die(mysql_error());
			mysql_query("TRUNCATE TABLE `" . $_SERVER['argv']['1'] . "_barbarian`") or die(mysql_error());
		} else {
			mysql_query("UPDATE servers SET updating = '0' WHERE servername = '" . $_SERVER['argv']['1'] . "'");
			exec("/usr/bin/php /var/koctools.com/updatecron.php >/dev/null &");
			exit;
		}
		
	}
	
	$str = get_web_page($bigurl);
	$obj = json_decode($str['content'], true);
	populateSlots($obj['data'], $obj['allianceNames'], $obj['userInfo']);
	
	$prev = $counter - 5;
	$prev = "bl_" . $prev;
	$current = "bl_" . $counter;
	
	$bigurl = str_replace($prev, $current, $bigurl);
}

mysql_query("UPDATE servers SET updating = '0' WHERE servername = '" . $_SERVER['argv']['1'] . "'");
mysql_close();
?>

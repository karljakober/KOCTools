<?php
require 'provinces.php';
require 'dbconfig.php';

if(isset($_GET['province']) ){
    $img = imagecreatefrompng("blankmap.png");
}else{
    $img = imagecreatefrompng("kocgrid2.png");
}
$black = imagecolorallocate($img, 0, 0, 0);
$white = imagecolorallocate($img, 255, 255, 255);
$red   = imagecolorallocate($img, 255, 0, 0);
$green = imagecolorallocate($img, 0, 255, 0);
$blue  = imagecolorallocate($img, 0, 0, 255);

putenv('GDFONTPATH=' . realpath('.'));
$font = 'IttyBittyPixel';

mysql_connect($domain, $user, $password) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());
if (isset($_GET['alliance'])) {
	$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "` WHERE alliance='" . mysql_real_escape_string($_GET['alliance']) . "'") or die(mysql_error());
} elseif (isset($_GET['player'])) {
	$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "` WHERE player='" . mysql_real_escape_string($_GET['player']) . "'") or die(mysql_error());
} elseif (isset($_GET['howmany']) && isset($_GET['x']) && isset($_GET['y'])) {
	$topx = $_GET['x'] - $_GET['howmany'];
	$topy = $_GET['y'] - $_GET['howmany'];
	$botx = $_GET['x'] + $_GET['howmany'];
	$boty = $_GET['y'] + $_GET['howmany'];
	$otherconditional = "";
	if($_GET['level'] != "") {
		if($_GET['sign'] == "equal") { 
			$sign = "=";
		}
		if($_GET['sign'] == "greaterthan") { 
			$sign = ">";
		}
		if($_GET['sign'] == "lessthan") { 
			$sign = "<";
		}

		$otherconditional = " AND level" . $sign . mysql_real_escape_string($_GET['level']);
	}
	$topxflag = FALSE;
	$topyflag = FALSE;
	$botxflag = FALSE;
	$botyflag = FALSE;

	if($topx < 0) {
		$topx = 749 + $_GET['x'] - $_GET['howmany'];
		$topxflag = TRUE;
	}
	if($botx > 749) {
		$botx = $_GET['x'] + $_GET['howmany'] - 749;
		$botxflag = TRUE;
	}
	if($topy < 0) {
		$topy = 749 + $_GET['y'] - $_GET['howmany'];
		$topyflag = TRUE;
	}
	if($boty > 749) {
		$boty = $_GET['y'] + $_GET['howmany'] - 749;
		$botyflag = TRUE;
	}
	if ($topxflag == TRUE || $botxflag == TRUE) {
		$xconditional = "(ABS(`x`-" . mysql_real_escape_string($_GET['x']) . ")<=" . mysql_real_escape_string($_GET['howmany']) . " OR ABS(`x`-" . mysql_real_escape_string($_GET['x']) . ")>=(749-" . mysql_real_escape_string($_GET['howmany']) . "))";
	}
	if ($topxflag == FALSE && $botxflag == FALSE) {
		$xconditional = "(`x` BETWEEN " . mysql_real_escape_string($topx) . " AND " . mysql_real_escape_string($botx) . ")";
	}
	if ($topyflag == TRUE || $botyflag == TRUE) {
		$yconditional = "(ABS(`y`-" . mysql_real_escape_string($_GET['y']) . ")<=" . mysql_real_escape_string($_GET['howmany']) . " OR ABS(`y`-" . mysql_real_escape_string($_GET['y']) . ")>=(749-" . mysql_real_escape_string($_GET['howmany']) . "))";
	}
	if ($topyflag == FALSE && $botyflag == FALSE) {
		$yconditional = "(`y` BETWEEN " . $topy . " AND " . $boty . ")";
	}
	$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "_barbarian` WHERE " . $xconditional . " AND " . $yconditional . " " . $otherconditional) or die(mysql_error());
} elseif (isset($_GET['wildernessalliance'])) {
	$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "_wilderness` WHERE alliance='" . mysql_real_escape_string($_GET['wildernessalliance']) . "'") or die(mysql_error());
} elseif (isset($_GET['wildernessplayer'])) {
	$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "_wilderness` WHERE player='" . mysql_real_escape_string($_GET['wildernessplayer']) . "'") or die(mysql_error());
} elseif ($_GET['map'] == 'wilderness') {
	$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "_wilderness`") or die(mysql_error());
} elseif ($_GET['map'] == 'barbarian') {
	$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "_barbarian`") or die(mysql_error());
} else {
	$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "`") or die(mysql_error());
}

function remap($x, $in_min, $in_max, $out_min, $out_max)
{
  return ($x - $in_min) * ($out_max - $out_min) / ($in_max - $in_min) + $out_min;
}

$p = $_GET['province'];
while($row = mysql_fetch_array($result)) {
        if(isset($_GET['province']) && $row['x'] >= $province[$p][1] && $row['x'] < $province[$p][1]+150 && $row['y'] >= $province[$p][0] && $row['y'] < $province[$p][0]+150 ){
		
		imagerectangle($img, remap($row['x'], $province[$p][1], $province[$p][1]+150, 0, 780), 
                                     remap($row['y'], $province[$p][0], $province[$p][0]+150, 0, 850),  
                                     remap(1+$row['x'], $province[$p][1], $province[$p][1]+150, 0, 780),
                                     remap(1+$row['y'], $province[$p][0], $province[$p][0]+150, 0, 850),
                                     $red);
		}elseif(!isset($_GET['province'])){	
        imagerectangle($img, 29+$row['x'], 29+$row['y'], 31+$row['x'], 31+$row['y'], $red); }
	}
header('Content-type: image/png');
imagepng($img);
imagedestroy($img);
?>

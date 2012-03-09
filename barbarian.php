<?php
require_once("dbconfig.php");
mysql_connect($dbhost,$dbusername,$dbpassword) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());

$query = mysql_query("SHOW TABLES LIKE '" . $_GET['server'] . "'");
if(!mysql_num_rows($query)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}
function distance($x1, $y1, $x2, $y2)
{
   //return sqrt(($latitudeTo - $latitudeFrom)^2 + ($longitudeTo - $longitudeFrom)^2);
   
	$x = ( pow($x2-$x1,2));
	$y = ( pow($y2-$y1,2));

	$distance = ( sqrt($x + $y) );

	// Round to nearest full number

	//$roundtofull = round($distance);
	return round($distance,2);
}
 ?>

                	<div class="post">
                        <h2 class="title"><a href="#">Map of <?php echo addslashes($_GET['server']); ?></a></h2>
                		<p class="meta"><span class="date">Please note that the entire world wraps horizontally AND vertically. People at 0,0 will be right next to people at 749,749!</span></p>
                        <?php
						$query = mysql_query("SELECT * FROM servers WHERE servername = '" . addslashes($_GET['server']) . "' LIMIT 1");
						if(mysql_num_rows($query)) { 
							$row = mysql_fetch_array($query); ?>
                            <p class="meta"><span class="date">Last updated on <?php echo date("F j, Y, g:i a", $row['lastupdated']); ?> GMT</span></p>
						<?php } ?>

			  			<div style="clear: both;">&nbsp;</div>
						<div class="entry">

                            <div style="text-align:center">
                            	<h1>Map:</h1>
                            	<img src="http://www.koctools.com/mapimg.php?map=barbarian&server=<?php echo $_GET['server']; ?><?php if($_POST['howmany'] != ""){ echo "&howmany=" . $_POST['howmany']; } ?><?php if($_POST['x'] != ""){ echo "&x=" . $_POST['x']; } ?><?php if($_POST['y'] != ""){ echo "&y=" . $_POST['y']; } ?>" alt="map" width="560" height="560" />
                        <br /><br />

	<?php if (isset($_POST['barbarian'])) {
	?>
		<table style="border-spacing:1px; border: 1px #000000 solid;">
			<tr style="border: 1px #000000 dotted">
				<td>distance</td>
				<td>x</td>
				<td>y</td>
				<td>level</td>
			</tr>
			<?php
			if ($_POST['x'] == "" || $_POST['y'] == "" || $_POST['howmany'] == "") {
				echo "Check the values you've entered and try again";
			} else {

				
				$topx = $_POST['x'] - $_POST['howmany'];
				$topy = $_POST['y'] - $_POST['howmany'];
				$botx = $_POST['x'] + $_POST['howmany'];
				$boty = $_POST['y'] + $_POST['howmany'];
				$otherconditional = "";
				if($_POST['level'] != "") {
					if($_POST['sign'] == "equal") { 
						$sign = "=";
					}
					if($_POST['sign'] == "greaterthan") { 
						$sign = ">";
					}
					if($_POST['sign'] == "lessthan") { 
						$sign = "<";
					}

					$otherconditional = " AND level" . $sign . mysql_real_escape_string($_POST['level']);
				}
				$topxflag = FALSE;
				$topyflag = FALSE;
				$botxflag = FALSE;
				$botyflag = FALSE;
			
				if($topx < 0) {
					$topx = 749 + $_POST['x'] - $_POST['howmany'];
					$topxflag = TRUE;
				}
				if($botx > 749) {
					$botx = $_POST['x'] + $_POST['howmany'] - 749;
					$botxflag = TRUE;
				}
				if($topy < 0) {
					$topy = 749 + $_POST['y'] - $_POST['howmany'];
					$topyflag = TRUE;
				}
				if($boty > 749) {
					$boty = $_POST['y'] + $_POST['howmany'] - 749;
					$botyflag = TRUE;
				}
				if ($topxflag == TRUE || $botxflag == TRUE) {
					//$xconditional = "(`x` BETWEEN 0 AND " . $botx . ") OR (`x` BETWEEN  " . $topx . " AND 749)";
					$xconditional = "(ABS(`x`-" . mysql_real_escape_string($_POST['x']) . ")<=" . mysql_real_escape_string($_POST['howmany']) . " OR ABS(`x`-" . mysql_real_escape_string($_POST['x']) . ")>=(749-" . mysql_real_escape_string($_POST['howmany']) . "))";
				}
				if ($topxflag == FALSE && $botxflag == FALSE) {
					$xconditional = "(`x` BETWEEN " . mysql_real_escape_string($topx) . " AND " . mysql_real_escape_string($botx) . ")";
				}
				if ($topyflag == TRUE || $botyflag == TRUE) {
					//$yconditional = "(`y` BETWEEN 0 AND " . $boty . ") OR (`y` BETWEEN " . $topy . " AND 749)";
					$yconditional = "(ABS(`y`-" . mysql_real_escape_string($_POST['y']) . ")<=" . mysql_real_escape_string($_POST['howmany']) . " OR ABS(`y`-" . mysql_real_escape_string($_POST['y']) . ")>=(749-" . mysql_real_escape_string($_POST['howmany']) . "))";
				}
				if ($topyflag == FALSE && $botyflag == FALSE) {
					$yconditional = "(`y` BETWEEN " . $topy . " AND " . $boty . ")";
				}
				$result = mysql_query("SELECT * FROM `" . addslashes($_GET['server']) . "_barbarian` WHERE " . $xconditional . " AND " . $yconditional . " " . $otherconditional) or die(mysql_error());
				while($row = mysql_fetch_array($result)) { ?>
					<tr style="border: 1px solid;">
						<td><?php echo round(distance($_POST['x'],$_POST['y'],$row['x'],$row['y']), 2); ?></td>
						<td><?php echo $row['x']; ?></td>
						<td><?php echo $row['y']; ?></td>
						<td><?php echo $row['level']; ?></td>
					</tr>
				<?php } }
			//} ?>
		</table>
	<?php } ?>
                                
                                <br />
								<table width="100%" border="0">
								  <tr>
									<td>
									<form id="form3" name="barbarian" method="post" action="http://koctools.com/<?php echo $_GET['server']; ?>/barbarian">
									Search for barbarian village <input name="howmany" type="text" id="howmany" size="5" /> Spaces away from x:<input name="x" type="text" id="x" size="5" maxlength="3" /> y:<input name="y" type="text" id="y" size="5" maxlength="3" />
									 <br />
									 Restrict level to
									 <select name="sign" id="sign">
									   <option value="equal" selected="selected">=</option>
									   <option value="greaterthan">&gt;</option>
									   <option value="lessthan">&lt;</option>
									 </select> 
									 level: <input name="level" type="text" id="level" size="4" maxlength="2" />
							(Leave blank if you want all levels)<br />
							<input name="barbarian" type="submit" />
									</form>

									</td>
								  </tr>
								</table>
                            </div>
			  			</div>
					</div>

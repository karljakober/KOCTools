<?php
require_once("dbconfig.php");
mysql_connect($dbhost,$dbusername,$dbpassword) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());

$query = mysql_query("SHOW TABLES LIKE '" . $_GET['server'] . "'");
if(!mysql_num_rows($query)) {
	header("HTTP/1.0 404 Not Found");
	exit;
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
                            	<img src="http://www.koctools.com/mapimg.php?map=wilderness&server=<?php echo $_GET['server']; ?><?php if($_POST['wildernessalliance'] != ""){ echo "&wildernessalliance=" . $_POST['wildernessalliance']; } ?><?php if($_POST['wildernessplayer'] != ""){ echo "&wildernessplayer=" . $_POST['wildernessplayer']; } ?>" alt="map" width="560" height="560" />
                        <br /><br />

                                 <?php if ($_POST['wildernessalliance'] != "" || $_POST['wildernessplayer'] != "") { ?>
                                    <table style="margin:auto; border-spacing:0px; border-collapse:collapse; border-spacing:0px; border: 1px #000000 solid;">
                                	<tr style="border: 1px #000000 dotted">
                                    	<td>Name</td>
                                		<td>Type</td>
                                    	<td>X</td>
                                    	<td>Y</td>
                                    	<td>Alliance</td>
                                		<td>Might</td>
                                  	</tr> 
                                    <?php
							
					
                                    	mysql_connect($domain, $user, $password) or die(mysql_error());
                                    	mysql_select_db($dbname) or die(mysql_error());
                                    
                                    	if (isset($_POST['wildernessalliance'])) {
                                        	$field = 'alliance';
                                        	$what = mysql_real_escape_string($_POST['wildernessalliance']);
                                    	}
                                    	if (isset($_POST['wildernessplayer'])) {
                                        	$field = 'player';
                                        	$what = mysql_real_escape_string($_POST['wildernessplayer']);
                                    	}
                                    	$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "_wilderness` WHERE `" . $field . "` LIKE '" . $what ."'") or die(mysql_error());
                                    	while($row = mysql_fetch_array($result)) { ?>
                                           
                                  			<tr style="border: 1px #000000 dotted">
                                   				<td><?php echo urldecode($row['player']); ?></td>
                                    			<td><?php echo urldecode($row['type']); ?></td>
                                                <td><?php echo $row['x']; ?></td>
                                                <td><?php echo $row['y']; ?></td>
                                                <td><?php echo urldecode($row['alliance']); ?></td>
                                                <td><?php echo $row['might']; ?></td>
                                  			</tr>
                                  		<?php }
										mysql_close(); ?>
                                        </table>
                            		<?php } ?> 
										<?php if (isset($_POST['unownedsearch'])) {
	?>
		<table style="border-spacing:0px; border-collapse:collapse; border-spacing:0px; border: 1px #000000 solid;">
			<tr style="border: 1px #000000 dotted">
				<td>x</td>
				<td>y</td>
				<td>level</td>
			</tr>
			<?php
			if ($_POST['x'] == "" || $_POST['y'] == "" || $_POST['howmany'] == "" || $_POST['wildtype'] == "") {
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
				$result = mysql_query("SELECT * FROM `" . addslashes($_GET['server']) . "_wilderness` WHERE " . $xconditional . " AND " . $yconditional . " " . $otherconditional . " AND type = '" . mysql_real_escape_string($_POST['wildtype']) . "' AND player = '' AND misted = '0'") or die(mysql_error());
				while($row = mysql_fetch_array($result)) { ?>
					<tr style="border: 1px #000000 dotted">
						<td><?php echo $row['x']; ?></td>
						<td><?php echo $row['y']; ?></td>
						<td><?php echo $row['level']; ?></td>
					</tr>
				<?php } }
			//} ?>
		</table>
	<?php } ?>

                                
                                <br />
								<fieldset>
								<legend>Owned wilderness search:</legend>
                                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                	<tr>
                                    	<td>
                                  			<form id="form1" method="post" action="http://koctools.com/<?php echo $_GET['server']; ?>/wilderness">
                                  				<table width="100%" border="1">
                                    				<tr>
                                      					<td>Wilderness Alliance Search</td>
                                    				</tr>
                                    				<tr>
                                      					<td><input type="text" name="wildernessalliance" id="wildernessalliance" /></td>
                                    				</tr>
                                    				<tr>
                                      					<td><input name="Search for Alliance" type="submit" /></td>
                                    				</tr>
                                				</table>
                                  			</form>
                                 		</td>
                            			<td>
                                  			<form id="form2" method="post" action="http://koctools.com/<?php echo $_GET['server']; ?>/wilderness">
                                    			<table width="100%" border="1">
                                    				<tr>
                                      					<td>Wilderness Player Search</td>
                                    				</tr>
                                    				<tr>
                                                        <td><input type="text" name="wildernessplayer" id="wildernessplayer" /></td>
                                    				</tr>
                                                    <tr>
                                                    	<td><input name="Search for Player" type="submit" /></td>
                                    				</tr>
                                				</table>
                                			</form>
                                		</td>
                            		</tr>
                            	</table>
								</fieldset>
                                <fieldset>
								<legend>Unowned wilderness search:</legend>
                                <form id="form3" name="unownedwild" method="post" action="http://koctools.com/<?php echo $_GET['server']; ?>/wilderness">
									Type: 
									  <select name="wildtype" id="wildtype">
									    <option value="lake" selected="selected">Lake</option>
									    <option value="grassland">Grassland</option>
									    <option value="woods">Woods</option>
                                        <option value="mountain">Mountain</option>
                                        <option value="hills">Hills</option>
							      </select>
								    <br /> 
									  <input name="howmany" type="text" id="howmany" size="5" /> Spaces away from (<input name="x" type="text" id="x" size="5" maxlength="3" />,<input name="y" type="text" id="y" size="5" maxlength="3" />) <br />
									 
									 Restrict level to
									 <select name="sign" id="sign">
									   <option value="equal" selected="selected">=</option>
									   <option value="greaterthan">&gt;</option>
									   <option value="lessthan">&lt;</option>
									 </select> 
									 level: <input name="level" type="text" id="level" size="4" maxlength="2" />
							(Leave blank if you want all levels)<br />
							<input name="unownedsearch" type="submit" />
								  </form>
								</fieldset>
                            </div>
			  			</div>
					</div>

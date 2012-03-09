<?php
require_once("dbconfig.php");
mysql_connect($dbhost,$dbusername,$dbpassword) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());

$row_color1 = "#ffffff";
$row_color2 = "#dddddd";

$row_counter = 0;

$province_name[0] = "Tintagel[0,0]-[149,149]";
$province_name[1] = "Cornwall[150,0]-[299,149]";
$province_name[2] = "Astolat[300,0]-[449,149]";
$province_name[3] = "Lyonesse[450,0]-[599,149]";
$province_name[4] = "Corbenic[600,0]-[749,149]";
$province_name[5] = "Paimpont[0,150]-[149,299]";
$province_name[6] = "Cameliard[150,150]-[299,299]";
$province_name[7] = "Sarras[300,150]-[449,299]";
$province_name[8] = "Canoel[450,150]-[599,249]";
$province_name[9] = "Avalon[600,150]-[749,299]";
$province_name[10] = "Carmathen[0,300]-[149,449]";
$province_name[11] = "Shallot[150,300]-[299,449]";
$province_name[12] = "Cadbury[450,300]-[599,449]";
$province_name[13] = "Glastonbury[600,300]-[749,449]";
$province_name[14] = "Camlann[0,450]-[149,599]";
$province_name[15] = "Orkney[150,450]-[299,599]";
$province_name[16] = "Dore[300,450]-[449,599]";
$province_name[17] = "Logres[450,450]-[599,599]";
$province_name[18] = "Caerleon[600,450]-[749,599]";
$province_name[19] = "Parmenie[0,600]-[149,749]";
$province_name[20] = "Bodmin Moor[150,600]-[299,749]";
$province_name[21] = "Cellwig[300,600]-[449,749]";
$province_name[22] = "Listeneise[450,600]-[599,749]";
$province_name[23] = "Albion[600,600]-[749,749]";

$query = mysql_query("SHOW TABLES LIKE '" . $_GET['server'] . "'") or die(mysql_error());
/*if(!mysql_num_rows($query)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}*/
 ?>
<script type="text/javascript" src="http://koctools.com/jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
function lookupalliance(inputString, serverString) {
    if(inputString.length == 0) {
        $('#alliancesuggestions').hide();
    } else {
        $.post("http://koctools.com/autocomplete.php", {alliance: ""+inputString+"" , server: ""+serverString+""}, function(data){
            if(data.length >0) {
                $('#alliancesuggestions').show();
                $('#allianceautoSuggestionsList').html(data);
            }
        });
    }
}

function lookupplayer(inputString, serverString) {
    if(inputString.length == 0) {
        $('#playersuggestions').hide();
    } else {
        $.post("http://koctools.com/autocomplete.php", {player: ""+inputString+"" , server: ""+serverString+""}, function(data){
            if(data.length >0) {
                $('#playersuggestions').show();
                $('#playerautoSuggestionsList').html(data);
            }
        });
    }
}

function fillalliance(thisValue) {
	$('#alliance').val(thisValue);
	$('#alliancesuggestions').hide();
}
function fillplayer(thisValue) {
	$('#player').val(thisValue);
	$('#playersuggestions').hide();
}
</script>

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
<?php
if( isset( $_GET['province'] ) ){
?>
                                <h3>Zoomed on: <?php echo $province_name[$_GET['province'] ] ?></h3> <h5><a href="http://www.koctools.com/map.php?server=<?php echo $_GET['server']; ?><?php if($_POST['alliance'] != ""){ echo "&alliance=" . $_POST['alliance']; } ?><?php if($_POST['player'] != ""){ echo "&player=" . $_POST['player']; } ?>" target="_self">(zoom out)</a></h5>
<?php
}
?>

                            	<img src="http://www.koctools.com/mapimg.php?<?php if( isset( $_GET['province'] ) ){ echo 'province='.$_GET['province']; }?>&server=<?php echo $_GET['server']; ?><?php if($_POST['alliance'] != ""){ echo "&alliance=" . $_POST['alliance']; } ?><?php if($_POST['player'] != ""){ echo "&player=" . $_POST['player']; } ?>" <?php if( !isset($_GET['province'] ) ){echo "usemap=\"#provinces\" ";}?> alt="map" />
                                



<map name="provinces" id="provinces">  
				<area shape="rect" alt="Tintagel" title="" coords="30,30,180,180" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/0" target="_self" />
				<area shape="rect" alt="Cornwall" title="" coords="180,30,330,180" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/1" target="_self" />
				<area shape="rect" alt="Astolat" title="" coords="330,30,480,180" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/2" target="_self" />
				<area shape="rect" alt="Lyonesse" title="" coords="480,30,630,180" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/3" target="_self" />
				<area shape="rect" alt="Corbenic" title="" coords="630,30,780,180" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/4" target="_self" />
				<area shape="rect" alt="Paimpont" title="" coords="30,180,180,330" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/5" target="_self" />
				<area shape="rect" alt="Cameliard" title="" coords="180,180,330,330" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/6" target="_self" />
				<area shape="rect" alt="Sarras" title="" coords="330,180,480,330" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/7" target="_self" />
				<area shape="rect" alt="Canoel" title="" coords="480,180,630,330" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/8" target="_self" />
				<area shape="rect" alt="Avalon" title="" coords="630,180,780,330" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/9" target="_self" />
				<area shape="rect" alt="Carmathen" title="" coords="30,330,180,480" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/10" target="_self" />
				<area shape="rect" alt="Shallot" title="" coords="180,330,330,480" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/11" target="_self" />
				<area shape="rect" alt="Cadburry" title="" coords="480,330,630,480" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/12" target="_self" />
				<area shape="rect" alt="Glastonburry" title="" coords="630,330,780,480" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/13" target="_self" />
				<area shape="rect" alt="Camlann" title="" coords="30,480,180,630" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/14" target="_self" />
				<area shape="rect" alt="Orkney" title="" coords="180,480,330,630" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/15" target="_self" />
				<area shape="rect" alt="Dore" title="" coords="330,480,480,630" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/16" target="_self" />
				<area shape="rect" alt="Logres" title="" coords="480,480,630,630" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/17" target="_self" />
				<area shape="rect" alt="Caerleon" title="" coords="630,480,780,630" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/18" target="_self" />
				<area shape="rect" alt="Parmenie" title="" coords="30,630,180,780" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/19" target="_self" />
				<area shape="rect" alt="Bodmin Moor" title="" coords="180,630,330,780" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/20" target="_self" />
				<area shape="rect" alt="Cellwig" title="" coords="330,630,480,780" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/21" target="_self" />
				<area shape="rect" alt="Listeneise" title="" coords="480,630,630,780" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/22" target="_self" />
				<area shape="rect" alt="Albion" title="" coords="630,630,780,780" href="http://www.koctools.com/<?php echo $_GET['server'] ?>/map/23" target="_self" />
				<!-- Created by Online Image Map Editor (http://www.maschek.hu/imagemap/index) -->
				</map>                        
<br /><br />

                                 	<?php if ($_POST['alliance'] != "" || $_POST['player'] != "") { ?>
					                <table border="0" cellspacing="0" cellpadding="3" style="margin:auto;" width="60%">
                                	<tr bgcolor="#000000">
                                    	<th style="color: #ffffff;">Name</th>
										<th style="color: #ffffff;">Town</th>
                                    	<th style="color: #ffffff;">X</th>
                                    	<th style="color: #ffffff;">Y</th>
                                    	<th style="color: #ffffff;">Alliance</th>
										<th style="color: #ffffff;">Might</th>
                                  	</tr>
                                    <?php
                                    	mysql_connect($domain, $user, $password) or die(mysql_error());
                                    	mysql_select_db($dbname) or die(mysql_error());
                                    
                                    	if (isset($_POST['alliance'])) {
                                        	$field = 'alliance';
                                        	$what = mysql_real_escape_string($_POST['alliance']);
                                    	}
                                    	if (isset($_POST['player'])) {
                                        	$field = 'player';
                                        	$what = mysql_real_escape_string($_POST['player']);
                                    	}
                                    	$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "` WHERE `" . $field . "` = '" . $what ."' LIMIT 300") or die(mysql_error());
                                    	while($row = mysql_fetch_array($result)) { ?>
                                           
                                  			<tr bgcolor="<?php if ($row_counter & 1) echo "$row_color1"; else echo "$row_color2";?>">
                                   				<td><?php echo urldecode($row['player']); ?></td>
                                    			<td><?php echo urldecode($row['townname']); ?></td>
                                                <td><?php echo $row['x']; ?></td>
                                                <td><?php echo $row['y']; ?></td>
                                                <td><?php echo urldecode($row['alliance']); ?></td>
                                                <td><?php echo $row['might']; ?></td>
                                  			</tr>
                                  		<?php
						$row_counter++;
						} ?>
                                		</table>
                            		<?php
// Elmware's CSV Output 
?>
<br>
<script type="text/javascript">
function SelectAll(id)
{
    document.getElementById(id).focus();
    document.getElementById(id).select();
}
</script>

<table width="100" cellspacing="10" cellpadding="0" border="0" style="margin:auto;">
  <tr>
    <td>
<b>CSV:</b><br>
      <textarea name="csv" id="cutAndPaste" onClick="SelectAll('cutAndPaste');" rows="14" cols="94">
Player,Town,X,Y,Alliance,Might,
<?php

	$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "` WHERE `" . $field . "` = '" . $what ."' LIMIT 300") or die(mysql_error());	
	while($row = mysql_fetch_array($result)) { ?>
"<?php echo $row['player']; ?>","<?php echo urldecode($row['townname']); ?>",<?php echo $row['x']; ?>,<?php echo $row['y']; ?>,"<?php echo urldecode($row['alliance']); ?>",<?php echo $row['might']; ?>,
<?php }
	mysql_close(); ?>
</textarea>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="1" bordercolor="#000000" bgcolor="#ffeeaa" cellspacing="0" cellpadding="5">
	<tr>
	  <td>
	    <p><b>To use:</b></p>
	    <p>Cut and paste into Notepad. &nbsp;Then save with quotes as <b>"[filename].csv"</b>.<br>
	    <i>eg: "cities.csv"</i></p>
	    <p>Open in a spreadsheet that supports CSV files such as Excel. &nbsp;If prompted check "First row contains headers." &nbsp;Once opened, you may save as another format such as Excel spreadsheet file.</p>
	  </td>
	</tr>
      </table>
    </td>
  </tr>
</table>
<?php // End of Elmware's CSV Output
 } ?>
                                <br />
                                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                	<tr>
                                    	<td>
                                  			<form id="form1" method="post" action="http://koctools.com/<?php echo $_GET['server']; ?>/map/">
                                  				<table width="100%" border="1">
                                    				<tr>
                                      					<td>Alliance Search</td>
                                    				</tr>
                                    				<tr>
                                      					<td>
															<div style="position: relative;">
																<div>
																	<input type="text" name="alliance" id="alliance" autocomplete="off" onkeyup="lookupalliance(this.value, '<?php echo $_GET['server']; ?>');"  />
																</div>
																<div class="suggestionsBox" id="alliancesuggestions" style="display: none;">
																	<img src="http://koctools.com/upArrow.png" style="position: relative; top: -12px; left: 30px" alt="upArrow" />
																	<div class="suggestionList" id="allianceautoSuggestionsList">
																	</div>
																</div>
															</div>
														</td>
                                    				</tr>
                                    				<tr>
                                      					<td><input name="Search for Alliance" type="submit" /></td>
                                    				</tr>
                                				</table>
                                  			</form>
                                 		</td>
                            			<td>
                                  			<form id="form2" method="post" action="http://koctools.com/<?php echo $_GET['server']; ?>/map/">
                                    			<table width="100%" border="1">
                                    				<tr>
                                      					<td>Player Search</td>
                                    				</tr>
                                    				<tr>
                                                        <td>
															<div style="position: relative;">
																<div>
																	<input type="text" name="player" id="player" autocomplete="off" onkeyup="lookupplayer(this.value, '<?php echo $_GET['server']; ?>');"/>
																</div>
																	<div class="suggestionsBox" id="playersuggestions" style="display: none;">
																		<img src="http://koctools.com/upArrow.png" style="position: relative; top: -12px; left: 30px" alt="upArrow" />
																		<div class="suggestionList" id="playerautoSuggestionsList">
																	</div>
																</div>
															</div>
														</td>
                                    				</tr>
                                                    <tr>
                                                    	<td><input name="Search for Player" type="submit" /></td>
                                    				</tr>
                                				</table>
                                			</form>
                                		</td>
                            		</tr>
									</table>
									<fieldset>
									<legend>Compare two alliances</legend>
									<table width="100%" border="0" cellspacing="0" cellpadding="5">
									<tr>
										
                                    	<td>
											<table width="100%" border="1">
												<tr>
													<td>Alliance 1</td>
												</tr>
												<tr>
													<td><input type="text" name="alliance1" id="alliance1" /></td>
												</tr>
												<tr>
													<td>Color: <select name="color1">
													<option value="red" selected="selected">Red</option>
													<option value="blue">Blue</option>
													<option value="green">Green</option>
													<option value="black">Black</option>
													</select></td>
												</tr>
											</table>
                                 		</td>
                            			<td>
											<table width="100%" border="1">
												<tr>
													<td>Alliance 2</td>
												</tr>
												<tr>
													<td><input type="text" name="alliance2" id="alliance2" /></td>
												</tr>
												<tr>
													<td>Color: <select name="color2">
													<option value="red">Red</option>
													<option value="blue" selected="selected">Blue</option>
													<option value="green">Green</option>
													<option value="black">Black</option>
													</select></td>
												</tr>
											</table>
                                		</td>
										
                            		</tr>
                            	</table>
								</fieldset>
                            </div>
			  			</div>
					</div>

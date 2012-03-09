<?php
require_once("dbconfig.php");
mysql_connect($dbhost,$dbusername,$dbpassword) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());


$row_color1 = "#ffffff";
$row_color2 = "#dddddd";

$row_counter = 0;

$query = mysql_query("SHOW TABLES LIKE '" . $_GET['server'] . "'");
if(!mysql_num_rows($query)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}
 ?>

                	<div class="post">
						<h2 class="title"><a href="#">Top Players on <?php echo addslashes($_GET['server']); ?></a></h2>
                        <?php
						$query = mysql_query("SELECT * FROM servers WHERE servername = '" . addslashes($_GET['server']) . "' LIMIT 1");
						if(mysql_num_rows($query)) { 
							$row = mysql_fetch_array($query); ?>
                            <p class="meta"><span class="date">Last updated on <?php echo date("F j, Y, g:i a", $row['lastupdated']); ?> GMT</span></p>
						<?php } ?>
			  			<div style="clear: both;">&nbsp;</div>
						<div class="entry">
                            <div style="text-align:center">
                                <table border="0" cellspacing="0" cellpadding="8" width="60%" style="margin-left: auto; margin-right: auto;">
                                	<tr bgcolor="#000000">
                                	<th style="color: #ffffff; text-align:center">Name</th>
                                        <th style="color: #ffffff; text-align:center">Town</th>
                                        <th style="color: #ffffff; text-align:center">X</th>
                                        <th style="color: #ffffff; text-align:center">Y</th>
                                        <th style="color: #ffffff; text-align:center">Alliance</th>
                                        <th style="color: #ffffff; text-align:center">might</th>
                                	</tr>
      								<?php
									mysql_connect($domain, $user, $password) or die(mysql_error());
									mysql_select_db($dbname) or die(mysql_error());
		
									$result = mysql_query("SELECT * FROM `" . mysql_real_escape_string($_GET['server']) . "` ORDER BY `might` DESC LIMIT 200") or die(mysql_error());
									while($row = mysql_fetch_array($result)) { ?>
                                        
					<tr bgcolor="<?php if ($row_counter & 1) echo "$row_color1"; else echo "$row_color2"; ?>">
                                            <td><?php echo urldecode($row['player']); ?></td>
                                            <td><?php echo urldecode($row['townname']); ?></td>
                                            <td><?php echo $row['x']; ?></td>
                                            <td><?php echo $row['y']; ?></td>
                                            <td><?php echo urldecode($row['alliance']); ?></td>
                                            <td><?php echo $row['might']; ?></td>
                                        </tr>
      								<?php
								$row_counter++;
								}
									mysql_close(); ?>
   								</table>
							</div>
						</div>
                    </div>
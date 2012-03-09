<?php
require_once("dbconfig.php");
mysql_connect($dbhost,$dbusername,$dbpassword) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());
$row_color1 = "#ffffff";
$row_color2 = "#dddddd";

$row_counter = 0;
?>
                        <h2 class="title"><a href="#">Select your server.   </a></h2>
                        <div style="clear: both;">&nbsp;</div>
                        <div class="entry">
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
								<tr bgcolor="#000000">
                                <td colspan="5">&nbsp;</td>
                             	</tr>
								<?php
								$result = mysql_query("SELECT * FROM `servers` ORDER BY `serverid`");
								while ($row = mysql_fetch_array($result)) {
									?>
									<tr bgcolor="<?php if ($row_counter & 1) echo "$row_color1"; else echo "$row_color2"; ?>">
										<td width="20%"><?php echo $row['servername']; ?></td>
										<td width="20%"><a href="http://koctools.com/<?php echo $row['servername']; ?>/map/">Player Map</a></td>
										<td width="20%"><a href="http://koctools.com/<?php echo $row['servername']; ?>/top/">Top Players</a></td>
										<td width="20%"><a href="http://koctools.com/<?php echo $row['servername']; ?>/wilderness/">Wilderness Search</a></td>
										<td width="20%"><a href="http://koctools.com/<?php echo $row['servername']; ?>/barbarian/">Barbarian Search</a></td>
									</tr>
									<?php
									$row_counter++;
								}
								?>
                            </table>
                        </div>

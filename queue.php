<?php
require_once("dbconfig.php");
mysql_connect($dbhost,$dbusername,$dbpassword) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());
?>

                	<div class="post">
						<h2 class="title"><a href="#">Server update queue</a></h2>
                        <?php
						$query = mysql_query("SELECT * FROM `servers` WHERE `updating` = '1'");
						if(mysql_num_rows($query)) { 
							$row = mysql_fetch_array($query); ?>
                            <p class="meta"><span class="date">Currently updating <?php echo $row['servername']; ?></span></p>
						<?php } ?>
			  			<div style="clear: both;">&nbsp;</div>
						<h2>Update Queue</h2>
						<div class="entry">
                            <div style="text-align:center">
                                <table style="margin:auto; border-spacing:0px; border-collapse:collapse; border-spacing:0px; border: 1px #000000 solid;">
                                	<tr style="border: 1px #000000 dotted">
                                		<td>Server Name</td>
                                	</tr>
      								<?php

									$result = mysql_query("SELECT * FROM `servers` WHERE `queuedtime` != '0' ORDER BY `queuedtime` ASC") or die(mysql_error());
									while($row = mysql_fetch_array($result)) { ?>
                                        
										<tr style="border: 1px #000000 dotted">
                                            <td><?php echo $row['servername']; ?></td>
                                        </tr>
      								<?php }
									mysql_close(); ?>
   								</table>
							</div>
						</div>
                    </div>
<?php
$p_x = 0;
$p_y = 0;
$count = 0;
$province = array();

for($p_x = 0 ; $p_x < 750; $p_x = $p_x+150){
    for($p_y = 0 ; $p_y < 750; $p_y = $p_y+150){
        $province[$count][0] = $p_x;
        $province[$count][1] = $p_y;
        $count++;
    }
} 





?>

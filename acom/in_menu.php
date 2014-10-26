<?
logmod($con,'in_menu');
$rsme	= getrs($con,"SELECT * FROM me where meid = '".$rwtr['meid']."'  order by lv1, lv2, lv3","me"); 
if (mysqli_num_rows($rsme) != 0) { 
	$prev = 'top';
	echo "<ul id='me'>".PHP_EOL;
	while ($rwme = mysqli_fetch_array($rsme)) {	
		if ($rwme['trid'] == '') {
			if (isset($autr[$rwme['trid']])){
				if ($autr[$rwme['trid']] == 's' or $rwus['us'] == 'edk'){
					//val($rwme['ti'].' op aaa '.$rwme['trid']);
					if ($rwme['trid'] != '') {
						$action = "<a href=index.php?t=".$rwme['trid']." >".$rwme['ti']." </a>";
					} else {
						$action = "<a href=index.php?t=".$trid." >".$rwme['ti']." </a>";
					}
				} elseif ($autr[$rwme['trid']] == 'g'){
					$action = "<a href='' ><font color='grey'>".$rwme['ti']."</font></a>";
				} else {
					$action = 'niks';
				}	
			} elseif ( $rwus['us'] == 'edk') {	
				//val($rwme['ti'].' op bbb '.$rwme['trid']);			
				if ($rwme['trid'] != '') {
					$action = "<a href=index.php?t=".$rwme['trid']." >".$rwme['ti']." </a>";
				} else {
					$action = "<a href=index.php?t=".$trid." >".$rwme['ti']." </a>";
				}
			}
		} elseif (isset($rwme['ur'])) { // van uit pa omzetten dus
			$action = "<a href=".$rwtrbis['ur']." >".$rwme['ti']."</a>";
		} else {
			if ($autr[$rwme['trid']] == 's') {
				$action = "<a href=index.php?t=".$rwme['trid']." >".$rwme['ti']." </a>";
			} else {
				$action = " ";
			}
		}
		if ($action != 'niks') {
			if ($rwme['lv2'] == 0 && $rwme['lv3'] == 0)  /* lv1 */ {
				if ($prev == 'lv3') { /* close lv1 + lv2 */ 
					echo "</ul></li></ul></li>".PHP_EOL;
				}
				if ($prev == 'lv2') { /* close lv2 */
					echo "</ul></li></ul></li>".PHP_EOL;
				}
				if ($prev == 'lv1') { /* close lv1 */
					echo "</ul></li>".PHP_EOL;
				}
				echo "<li>".PHP_EOL;	
				echo $action.PHP_EOL;
				
				echo "<ul>".PHP_EOL;
				$prev = 'lv1';
			} elseif ($rwme['lv2'] != 0 && $rwme['lv3'] == 0) {  /* lv2 */
				if ($prev == 'lv3') { /* close lv3 */
					echo "</ul></li>".PHP_EOL;
				}
				if ($prev == 'lv2') { /* close lv2 */
					echo "</ul></li>".PHP_EOL;
				}
				echo "<li >".PHP_EOL;
				if (isset($rwme['ti'])) {
					echo $action.PHP_EOL;
				}
				echo "<ul>".PHP_EOL;
				$prev = 'lv2';
			}
			elseif ($rwme['lv2'] != 0 && $rwme['lv3'] != 0) {  /* lv3 */
				echo "<li >".PHP_EOL;
				echo $action.PHP_EOL;
				echo "</li>".PHP_EOL;
				$prev = 'lv3';
			}
		}
	}
	echo "<!-- close menu  except last ul from prev ".$prev."-->".PHP_EOL;
	if ($prev == 'lv3') { /* close lv3 */
		echo "</ul></li></ul></li></ul></li>".PHP_EOL;
	}
	if ($prev == 'lv2') { /* close lv2 */
		echo "</ul></li></ul></li>".PHP_EOL;
	}
	if ($prev == 'lv1') { /* close lv1 */
		echo "</ul></li>".PHP_EOL;
	}

	echo "</ul>".PHP_EOL;
	$rwct = 1;
}
?>
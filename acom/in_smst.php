<? 
logmod($con,'in_smst.php toon statistiek');
echo "<tr><td>".PHP_EOL;
if ($rp == 'det' or $rp == 'all'){
	echo "<table>".PHP_EOL;
	$eerste		= 'a';
	arsort($pri);
	logarr($con,$pri,'prio');
	logarr($con,$tit,'tit');
	foreach ($pri as $ken => $prio) { 
		if (isset($tit[$ken])) {
			if ($eerste	== 'a'){
				echo "<tr class=kop><td>soort</td><td>oefening</td><td>vorm</td>".PHP_EOL; 
				echo "<td>eerst</td><td>goed</td><td>van</td><td>laatste 20</td></tr>".PHP_EOL;
				$eerste		= 'u';
			}
			$tiEl 		= str_getcsv($tit[$ken],'|');
			echo "<tr><td>".$tiEl[0]."</td><td  style='min-width:150px;'>".$tiEl[1]."</td><td>".$tiEl[2]."</td>".PHP_EOL; 
			echo "<td>".$pri[$ken]."</td>".PHP_EOL;
			if (isset($oktl[$ken]) || isset($noktl[$ken])) {
				$tot		= $oktl[$ken] + $noktl[$ken];
				$sp			= $spsc[$ken] * 3;
				$rest		= 60 - $sp;
				echo "<td>".$oktl[$ken]."</td><td>".$tot."</td>".PHP_EOL;
				//val($spsc[$ken]);
				if ($pri[$ken] != 1) {
					echo "<td><table><tr><td style='width:".$sp."px; height:10px; background:blue;padding:0px;'></td>
						<td style='width:".$rest."px; height:10px; background:lightgray;padding:0px; '></td></tr></table></td><tr>".PHP_EOL;
				} else {
					echo "<td style='color:lime;'>klaar!</td><tr>".PHP_EOL;
				}
			} else {
				echo "<td>0</td><td>0</td>".PHP_EOL;
				echo "<td></td><tr>".PHP_EOL;
			}
		}
	}
	echo "</table>".PHP_EOL;
} 
if ($rp == 'sim' or $rp == 'all'){ //gewoon
	$eerste		= 'a';
	echo "<table>".PHP_EOL;
	if (isset($oktl)){
		$sr = '';
		$rsco			= getrs($con,"SELECT * FROM ts where co = '".$acco."' and tp = 'co' order by tsky","co"); 
		while ($rwco 	= mysqli_fetch_array($rsco)){
			if ($eerste	== 'a'){
				echo "<tr class=kop><td>soort</td><td>goed</td><td>van</td></tr>".PHP_EOL;
				$eerste		= 'u';
			}
			if ($sr != $rwco['sr']) {
				$rwts		= getrw($con,"SELECT * FROM ts where co = 'defl' and tp = 'sr' and sr = '".$rwco['sr']."'","ts");
				$sr			= $rwco['sr'];
				$tiEl 		= str_getcsv($rwts['ti'],'|');
				echo "<tr><td>".$tiEl[0]."</td>".PHP_EOL;
				if (isset($oktl[$rwco['sr']])) {	
					$tot		= $oktl[$sr] + $noktl[$sr];
					$sco		= round(($oktl[$sr] * 60 )/ $tot);
					$rest		= 60 - $sco;
					echo "<td>".$oktl[$sr]."</td><td>".$tot."</td><td><table><tr>
							<td style='width:".$sco."px; height:10px; background:blue;padding:0px;'></td>
							<td style='width:".$rest."px; height:10px; background:lightgray;padding:0px;'></td></tr></table></td></tr>".PHP_EOL;
				} else {
					echo "<td>0</td><td>0</td><td></td></tr>".PHP_EOL;
				}
			}
		}
	}
	echo "</table>".PHP_EOL;
}
if ($rp == 'ver' or $rp == 'all'){
	$eerste		= 'a';
	if (isset($oktl) and isset($_SESSION['ver'])){
		echo "<table><tr class=kop><td>laatste 50 scores (blauw = goed, laatste vooraan)</td></tr><tr>".PHP_EOL;	
		echo "<td><table><tr>".PHP_EOL;	
		$ver	= $_SESSION['ver'];
		while( strlen($ver) > 0 ) {			
			if (substr($ver,0,1) == '1') {
				echo "<td class=bl1></td>";
			} else {	
				echo "<td class=bl2></td>";			}
			$ver= substr($ver,1);
		//	val($ver);
		} 
		echo "</tr></table></td></tr></table>".PHP_EOL;
	}
}

echo "</td></tr>".PHP_EOL;
?>

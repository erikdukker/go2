<? 
logmod($con,'in_smtn.php toon sommen');
$pasTx = '';
$somTx = '';
foreach ($pas as $par => $val) { $pasTx .= $par.'='.$val.';';}
foreach ($som as $par => $val) { $somTx .= $par.'='.$val.';';}
if (isset($_SESSION['lg'])){
	echo "<input id='k|".$somNo."' 	name='k|".$somNo."' type='text' style='font-size:12px' size='200' value = '".$ken."' >";	
	echo "<input id='p|".$somNo."' 	name='p|".$somNo."' type='text' style='font-size:12px' size='200' value = '".$pasTx."' >";	
	echo "<input id='s|".$somNo."' 	name='s|".$somNo."' type='text' style='font-size:12px' size='200' value = '".$somTx."' >";	
} else {
	echo "<input id='k|".$ken."' 	name='k|".$somNo."' type='hidden' value = '".$ken."' />";	
	echo "<input id='p|".$somNo."' 	name='p|".$somNo."' type='hidden' value = '".$pasTx."' />";	
	echo "<input id='s|".$somNo."' 	name='s|".$somNo."' type='hidden' value = '".$somTx."' />";	
}
if ($somNo == 0 or $somNo == 1) {
	echo "<div id=d|".$somNo." style='display:inline'>".PHP_EOL;
} else {
	echo "<div id=d|".$somNo." style='display:none'>".PHP_EOL;
}
echo "<table class=sm >".PHP_EOL;
echo "<tr><th class='sm md' style='min-width:100px'>".$som['tn']."</th><th class='sm'>=</th>".PHP_EOL;
if ($vr == 'mk') {
	echo "<td class=an style='min-width:300px'>";
	$ag=1;
	for ( $i= 1; $i <= $som['aa']; $i++) {if ($som['a'.$i] == $som['rs']) {$ok = $i;}}
	for ( $i= 1; $i <= $som['aa']; $i++) {
		if (isset($pas['kb'])) {$kb = "style='width: ".$pas['kb']."px'"; } else { $kb = "";} // afwijkende breedte
		if ($som['a'.$i] == $som['rs']) {$ok = $i;} 
		echo "<div class='kn' ".$kb." name=k".$somNo.'|'.$i." id=k|".$somNo.'|'.$i." onclick='pro(".$somNo.",1,".$i.",".$ok.",".$exAc.")'>";
		echo $som['a'.$i]."</div>";
		if ($pas['ag']	== $ag) {		
			echo "<br>";	
			$ag=1;
		} else {
			$ag++;
		}
	}
	echo "</td>";		
} elseif ($vr 	== 'ov') {
	echo "<td style='min-width:300px'>";			
	if ($exAc	== 1 and isset($som['sc'])) {$sc = $som['sc'];} else { $sc = '';} //sc is het in gegeven antwoord
	if ($somNo	== 1) {$setFoc = 'a'; } 
	echo "<input type='text' class='md' pattern='".$pas['pa']."}' id=t|".$somNo." size=7 value='".$sc."' onchange='pro(".$somNo.",2,99,".$som['rs'].",".$exAc.")'>".PHP_EOL;
	echo "<input class='nd md'	disabled type='text' id=o|".$somNo." size=7 value='".$som['rs']."' style='background:#41fa10' )'></td>".PHP_EOL;
	echo "</td>";			
}
echo "<td class=im><img id=ok|".$somNo." class='nd' src='zimg/ok17.png' alt='OK' width='17' height='17' >";
echo "<img id=no|".$somNo." class='nd' src='zimg/no17.png' alt='NO' width='17' height='17' >";
//echo "<input name='leeg' type='text' style='font-size:1px' size='1'>";	
echo "</td>";
echo "</tr>".PHP_EOL;
echo "</table>".PHP_EOL; 
echo "</div>".PHP_EOL; 
if ($exAc == 1) {
	if ($vr == 'mk') {
		for ( $i= 1; $i <= $som['aa']; $i++) {
			if ($som['a'.$i] == $som['rs']){$ok = $i;} // bepaal ok
		}
		echo "<script>pro(0,1,".$som['sc'].",".$ok.",".$exAc.")</script>";
	} else {
		echo "<script>pro(0,2,99,".$som['rs'].",".$exAc.")</script>";
	}
	$exAc = 0;
}
unset($pas,$som);
?>



<?
logmod($con,'pi_cosl.php stel configuratie samen');
$cosl	= getpar('cosl');
$cosr	= getpar('cosr');
echo "<form accept-charset='UTF-8' action='acom\up_cosl.php' id='coslform' method='post'>";
echo "<table>".PHP_EOL;
echo "<tr><th>Andere test bewerken? </th></tr> ".PHP_EOL;
echo "<tr><td><select size='1' id='cosl' name='cosl' onclick='vvtr(\"cosl\",\"cosl\",\"cosl\")'>".PHP_EOL;
$rsts	= getrs($con,"select distinct co from ts","ts"); 
while ($rwts = mysqli_fetch_array($rsts)) {
	if ($rwts['co'] != 'defl') {
		echo "<option ";
		if (isset($cosl)){
			if($rwts['co'] == $cosl) { echo "selected ";}
		}
		echo "value='".trim($rwts['co'])."'>(".trim($rwts['co']).") </option>".PHP_EOL;
	}
}
echo "<option "; if(!isset($cosl)) { echo "selected ";} echo "value=''>selecteer een configuratie</option>".PHP_EOL;
echo "</select> ".PHP_EOL;
echo "<input  name= nwCo size='5' type='text' />".PHP_EOL; 
echo "Aktie <input type='radio' name='aktie' value='c' checked	>wijzigen ".PHP_EOL; 
echo "<input type='radio' name='aktie' value='n' >nieuw ".PHP_EOL; 
echo "<input type='radio' name='aktie' value='d' >verwijder ".PHP_EOL; 
echo "<input class='but cust1' name='fcommit' type='submit' value='uitvoeren' >".PHP_EOL; 
echo "</td></tr>";
echo "<tr><td>sorteeer op <select size='1' id='cosr' name='cosr' onclick='vvtr(\"cosl\",\"cosr\",\"cosr\")'>".PHP_EOL;
echo "<option "; if($cosr == 'sr') 	{ echo "selected ";} echo "value='sr'>bewerking</option>".PHP_EOL;
echo "<option "; if($cosr == 'br') 	{ echo "selected ";} echo "value='br'>bereik</option>".PHP_EOL;
echo "<option "; if(!isset($cosr)) { echo "selected ";} echo "value=''>selecteer sorteer</option>".PHP_EOL;
echo "</select></td></tr>".PHP_EOL;
echo "</table>";
$rsco			= getrs($con,"SELECT * FROM ts where co = '".$cosl."' and tp = 'co'","co"); 
while ($rwco 	= mysqli_fetch_array($rsco)){
	$tco[$rwco['sr']."|".$rwco['br']."|".$rwco['vr']] = '1';
}
echo "<table>".PHP_EOL;
$rsco			= getrs($con,"SELECT * FROM ts where co = 'defl' and tp = 'co' order by tsky","co defl	"); 
while ($rwco 	= mysqli_fetch_array($rsco)){
	$tiEl 		= str_getcsv($rwco['ti'],'|');
//	logarr($con,$tiEl,'leuk');
	$ky 		= $rwco['sr']."|".$rwco['br']."|".$rwco['vr'];
	if (isset($tco[$ky])) { $set = 'checked';} else { $set = '';}
	$rg			= "<tr><td><input type='checkbox' value='a' name='ch".$ky."' ".$set." ></td>"; 
	$rg			.= "<td>".$tiEl[0]."</td><td>".$rwco['br']."</td><td>".$tiEl[1]."</td><td>".$tiEl[2]."</td><td>(".$rwco['wd'].")</td></tr>"; 
	if(!isset($cosr) or $cosr == '' ) {
		echo $rg.PHP_EOL;
	} else {
		if ($cosr == 'sr') { $rgs[$rwco['sr'].$rwco['br'].$rwco['vr']] = $rg;}
		if ($cosr == 'br') { $rgs[$rwco['br'].$rwco['sr'].$rwco['vr']] = $rg;}
	}
}
if(isset($cosr) and $cosr != '') {
	ksort($rgs);
	foreach ($rgs as $key => $rg) {
		echo $rg.PHP_EOL;
	}
}
echo "</table></form>".PHP_EOL;
?>
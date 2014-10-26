<?
logmod($con,'pi_cosb.php test bereik');

$rsco				= getrs($con,"SELECT * FROM ts where co = 'test' and tp = 'co'","co"); 
while ($rwco 		= mysqli_fetch_array($rsco)){
	$tco[$rwco['sr']."|".$rwco['br']."|".$rwco['vr']] = '1';
}
echo "<table>".PHP_EOL;
echo "<form accept-charset='UTF-8' action='acom\up_cosb.php' id='cosbform' method='post'>";
echo "<tr><td><input class='but cust1' name='fcommit' type='submit' value='uitvoeren' >".PHP_EOL; 
echo "</td></tr>";
echo "</table>";
echo "<table class=qs>".PHP_EOL;
$rsco			= getrs($con,"SELECT * FROM ts where tp = 'br' order by tsky","co"); 
while ($rwco 	= mysqli_fetch_array($rsco)){
	$tiEl 		= str_getcsv($rwco['ti'],'|');
//	logarr($con,$tiEl,'leuk');
	$ky 		= $rwco['sr']."|".$rwco['br']."|".$rwco['vr'];
	if (isset($tco[$ky])) { $set = 'checked';} else { $set = '';}
	echo "<tr><td><input type='checkbox' value='a' name='ch".$ky."' ".$set." ></td>".PHP_EOL; 
	echo "<td>".$tiEl[0]."</td><td>".$rwco['br']."</td><td>(".$rwco['wd'].")</td></tr>".PHP_EOL; 
}
echo "</table></form>".PHP_EOL;
?>
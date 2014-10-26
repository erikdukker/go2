<?
logmod($con,'pi_cots.php test configuratie');
$acco	= getpar('acco');
echo "<form accept-charset='UTF-8' action='index.php?t=smaa' id='accoform' method='post'>"; 
echo "<table>".PHP_EOL;
echo "<tr><th>Andere configuratie testen? </th></tr> ".PHP_EOL;
echo "<tr><td><select size='1' id='acco' name='acco'  onclick='vvtr(\"cots\",\"acco\",\"acco\")'>".PHP_EOL;
$rsts	= getrs($con,"select distinct co from ts order by co","ts"); 
while ($rwts = mysqli_fetch_array($rsts)) {
	if ($rwts['co'] != 'defl') {
		echo "<option ";
		if (isset($acco)){
			if($rwts['co'] == $acco) { echo "selected ";}
		}
		echo "value='".trim($rwts['co'])."'>(".trim($rwts['co']).") </option>".PHP_EOL;
	}
}
echo "<option "; if(!isset($acco)) { echo "selected ";} echo "value=''>selecteer een configuratie</option>".PHP_EOL;
echo "</select> ".PHP_EOL; 
echo "<input class='but cust1' name='fcommit' type='submit' value='uitvoeren' >".PHP_EOL; 
echo "</td></tr>";
echo "</table></form>".PHP_EOL;
?>
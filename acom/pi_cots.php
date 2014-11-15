<?
logmod($con,'pi_cots.php test configuratie');
$acco	= getpar('acco');
echo "<form accept-charset='UTF-8' action='index.php?t=smaa' id='accoform' method='post'>"; 
echo "<table>".PHP_EOL;
echo "<tr><th>Andere configuratie testen? </th></tr> ".PHP_EOL;
echo "<tr><td><select size='1' id='acco' name='acco'  onclick='vvtr(\"cots\",\"acco\",\"acco\")'>".PHP_EOL;
$rsts	= getrs($con,"select distinct id from ts where tp = 'co'","ts"); 
while ($rwts = mysqli_fetch_array($rsts)) {
	echo "<option ";
	if (isset($acco)){
		if($rwts['id'] == $acco) { echo "selected ";}
	}
	echo "value='".trim($rwts['id'])."'>".trim($rwts['id'])."</option>".PHP_EOL;
}
echo "<option "; if(!isset($acco)) { echo "selected ";} echo "value=''>selecteer een configuratie</option>".PHP_EOL;
echo "</select> ".PHP_EOL; 
echo "<input class='but cust1' name='fcommit' type='submit' value='uitvoeren' >".PHP_EOL; 
echo "</td></tr>";
echo "</table></form>".PHP_EOL;
?>
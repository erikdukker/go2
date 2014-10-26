<?	
logmod($con,'pi_prof');
$obid	= getpar('obid');
echo "<h2>selecteer</h2>".PHP_EOL;
echo "<table id='detb'>".PHP_EOL;
echo "<th>authorisatie profielen</th>".PHP_EOL;
echo "<td><select size='1' id='obid' name='obid' onclick='vvtr(\"prof\",\"obid\",\"obid\")'>".PHP_EOL;
$rsob 	= getrs($con,"select * from ob where obtp = 'usau' order by obid","ob 2"); 
while ($rwobsl2 = mysqli_fetch_array($rsob)) {
		echo "<option ";
		if (isset($obid)){
			if($rwobsl2['obid'] == $obid) { echo "selected ";}
		}
		echo "value='".trim($rwobsl2['obid'])."'>(".trim($rwobsl2['obid']).") ".$rwobsl2['obds']."</option>".PHP_EOL;
}
echo "<option "; if(!isset($obid)) { echo "selected ";} echo "value=''> </option>".PHP_EOL;
echo "</select> ".PHP_EOL;	
echo "</td></tr>".PHP_EOL;
echo "</table>".PHP_EOL;
$rwob	= getrw($con,"select * from ob where obid = '".$obid."'","ob 4"); 
$vals	= toar($rwob['obpa']);
logarr($con,$vals,'att opgehaald');
echo "<form accept-charset='UTF-8' action='acom/up_prof.php'  method='post'>".PHP_EOL;
echo "<table id='detb'>".PHP_EOL;
echo "<tr><th>objectid</th><td><input class='string' name='obid' size='20' type='text' value='".$rwob['obid']."' ></td></tr>	".PHP_EOL;
echo "<tr><th>objectds</th><td><input class='string' name='obds' size='40' type='text' value='".$rwob['obds']."' ></td></tr>	".PHP_EOL;
echo "</table>".PHP_EOL;
echo "<table id='detb'>".PHP_EOL;
echo "<tr><th>transaktie</th><th>autorisatie</th></tr>".PHP_EOL;
$i	= 0;
$rstr 			= getrs($con,"SELECT * FROM tr order by trid","tr"); 
while ($rwtr 	= mysqli_fetch_array($rstr)){
	if (isset($vals['t'.$rwtr['trid']])) { $aut = $vals['t'.$rwtr['trid']]; } else { $aut = '';}
	echo "<tr><td><input class='string' name=tr".$i."' size='5' disabled type='text' value='".$rwtr['trid']."' ></td>	".PHP_EOL;
	echo "<td><input class='string' name='des".$i."' size='50' disabled type='text' value='".$rwtr['ds']."' /></td>".PHP_EOL;
	echo "<td><input class='string' name='aut".$i."' size='2' type='text' value='".$aut."' /></td>".PHP_EOL;
	echo "<td><input class='string' name='trid".$i."' size='5' hidden type='text' value='".$rwtr['trid']."' /></td></tr>	".PHP_EOL;
	$i++;	
}
$i	= 0;
foreach ($vals as $att => $val){
	if (substr($att,0,1) == 'o'){	
		$auob	= substr($att,1);
		echo "<tr><td><input class='string' name='ao".$i."' size='5' type='text' value='".$auob."' ></td>".PHP_EOL;
		echo "<td><input class='string' name='au".$i."' size='2' type='text' value='".$val."' /></td></tr>".PHP_EOL;
		$i++;
	}
}
echo "<tr><td><input class='string' name='ao99' size='5' type='text' </td>".PHP_EOL;
echo "<td><input class='string' name='au99' size='2' type='text' </td></tr>".PHP_EOL;

echo "</table>".PHP_EOL;
?>
<table id='detb'>
<tr> <th>Aktie</th> </tr>
<tr> 
<td> <input type="radio" name="aktie" value="w" checked	>  wijzigen 
<input type="radio" name="aktie" value="v" 		>  verwijderen 
<input type="radio" name="aktie" value="k" 		>  kopieer  </td>
<tr class='required'>
<td> <input class="but cust1" id="filled_form_submit" name="fcommit" type="submit" value="aktie uitvoeren" />
<input type='hidden' name='obky' size='20' type='text' value='<? echo $rwob['obky'] ?>' /></td>
</td>
</tr>
</table>
</form> 
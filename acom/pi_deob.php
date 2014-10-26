<?	
logmod($con,'pi_deob');
$obid	= getpar('obid');
$obtp	= getpar('obtp');

echo "<h2>selecteer</h2>".PHP_EOL;
echo "<table id='detb'>".PHP_EOL;
echo "<tr><th>objecttype</th>".PHP_EOL;
echo "<td><select size='1' id='obtp' name='obtp2' onclick='vvtr(\"deob\",\"obtp\",\"obtp\")'>".PHP_EOL;
$rsob 	= getrs($con,"select * from ob where obtp = 'obtp' order by obid","ob 1"); 
while ($rwobsl1 = mysqli_fetch_array($rsob)) {
	if($rwobsl1['obid'] != 'obtp') {
		echo "<option ";
		if (isset($obtp)){
			if($rwobsl1['obid'] == $obtp) { echo "selected ";}
		}
		echo "value='".trim($rwobsl1['obid'])."'>(".trim($rwobsl1['obid']).") ".$rwobsl1['obds']."</option>".PHP_EOL;
	}
}
echo "<option "; if(!isset($obtp)) { echo "selected ";} echo "value=''> </option>".PHP_EOL;
echo "</select> ".PHP_EOL;	
echo "</td>".PHP_EOL;
echo "<th>object per objecttype</th>".PHP_EOL;
echo "<td><select size='1' id='obid' name='obid' onclick='vvtr(\"deob\",\"obid\",\"obid\")'>".PHP_EOL;
$rsob 	= getrs($con,"select * from ob where obtp = '".$obtp."' order by obid","ob 2"); 
while ($rwobsl2 = mysqli_fetch_array($rsob)) {
		echo "<option ";
		if($rwobsl2['obid'] == $obid) { echo "selected ";}
		echo "value='".trim($rwobsl2['obid'])."'>(".trim($rwobsl2['obid']).") ".$rwobsl2['obds']."</option>".PHP_EOL;
}
echo "<option "; if(!isset($obid))  { echo "selected ";} echo "value=''> </option>".PHP_EOL;
echo "</select> ".PHP_EOL;	
echo "</td></tr>".PHP_EOL;
echo "<tr><th>object (alle)</th>".PHP_EOL;
echo "<td><select size='1' id='obid2' name='obid2' onclick='onclick='vvtr(\"deob\",\"obid2\",\"obid\")'>".PHP_EOL;
$rsob 	= getrs($con,"select *  from ob where obtp <> 'obtp' order by obid","ob 3"); 
while ($rwobsl3 = mysqli_fetch_array($rsob)) {
	if($rwobsl3['obid'] != 'obtp') {
		echo "<option ";
		if(isset($obid)) { 
			if($rwobsl3['obid'] ==  $obid) { echo "selected ";}
		}
		echo "value='".trim($rwobsl3['obid'])."'>(".trim($rwobsl3['obid']).") ".$rwobsl3['obds']."</option>".PHP_EOL;
	}
}
echo "<option "; if(!isset($obid))  { echo "selected ";} echo "value=''> </option>".PHP_EOL;
echo "</select> ".PHP_EOL;	
echo "</td></tr></table>".PHP_EOL;
if(isset($obid)) {
	echo "<h2><br>geselecteerd</h2>".PHP_EOL;
	$rwob	= getrw($con,"select * from ob where obid = '".$obid."'","ob 4"); 
	$vals	= toar($rwob['obpa']);
	echo "<form accept-charset='UTF-8' action='acom/up_deob.php' class='new_filled_form'  id='generatedForm' method='post'>".PHP_EOL;
	echo "<table id='detb'>".PHP_EOL;
	echo "<tr><th>objectype</th>".PHP_EOL;
	echo "<td><select size='1' name='obtp' >".PHP_EOL;
	$rsob 	= getrs($con,"select * from ob where obtp = 'obtp' order by sq","ob 5"); 
	while ($rwobsl5 = mysqli_fetch_array($rsob)) {
		echo "<option ";
		if(isset($rwob['obtp'])) { 
			if($rwobsl5['obid'] == $rwob['obtp']) { echo "selected ";}
		}
		echo "value='".trim($rwobsl5['obid'])."'>(".trim($rwobsl5['obid']).") ".$rwobsl5['obds']."</option>".PHP_EOL;
	}
	echo "<option "; if(!isset($obid))  { echo "selected ";} echo "value=''></option>".PHP_EOL;
	echo "</select> ".PHP_EOL;	
	echo "</td></tr>".PHP_EOL;

	echo "<tr><th>objectid</th><td><input class='string' name='obid' size='20' type='text' value='".$rwob['obid']."' ></td></tr>	".PHP_EOL;
	echo "<tr><th>objectds</th><td><input class='string' name='obds' size='40' type='text' value='".$rwob['obds']."' ></td></tr>	".PHP_EOL;
	echo "</table>".PHP_EOL;

	echo "<table id='detb'>".PHP_EOL;
	echo "<tr><th>par</th><th>waarde</th></tr>".PHP_EOL;
	$i	= 0;
	if (isset($vals)){
		foreach ($vals as $att => $val){
			$i	= $i + 10;
			if(strstr($val,'^') != null) {
				$val = substr(strstr($val,"^"),1);
			}
			echo "<tr><td><input class='string' name='att".$i."' size='20' type='text' value='".$att."' ></td>	".PHP_EOL;
			echo "<td><input class='string' name='val".$i."' size='70' type='text' value='".$val."' /></td>".PHP_EOL;
		}
		$i	= $i + 10;
	}
	echo "<tr><td><input class='string' name='att".$i."' size='20' type='text' value='' ></td>	".PHP_EOL;
	echo "<td><input class='string' name='val".$i."' size='70' type='text' value='' /></td>".PHP_EOL;
	$i	= $i + 10;
	echo "<tr><td><input class='string' name='att".$i."' size='20' type='text' value='' ></td>	".PHP_EOL;
	echo "<td><input class='string' name='val".$i."' size='70' type='text' value='' /></td>".PHP_EOL;
	$i	= $i + 10;
	echo "<tr><td><input class='string' name='att".$i."' size='20' type='text' value='' ></td>	".PHP_EOL;
	echo "<td><input class='string' name='val".$i."' size='70' type='text' value='' /></td>".PHP_EOL;
	$i	= $i + 10;
	echo "<tr><td><input class='string' name='att".$i."' size='20' type='text' value='' ></td>	".PHP_EOL;
	echo "<td><input class='string' name='val".$i."' size='70' type='text' value='' /></td>".PHP_EOL;
	$i	= $i + 10;
	echo "</table>".PHP_EOL;
}
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

<?	
logmod($con,'pi_deus');
$deus	= getpar('deus');
echo "<table id='detb'>".PHP_EOL;
echo "<tr><th>Andere user bewerken? </th></tr> ".PHP_EOL;
echo "<tr><td><select size='1' id='deus' name='detr' onclick='vvtr(\"deus\",\"deus\",\"deus\")'>".PHP_EOL;
$rsus 	= getrs($con,"SELECT * from us where dl != 'x'","us"); 
while ($rwus = mysqli_fetch_array($rsus)){
	echo "<option ";
	if (isset($deus)){
		if($rwus['us'] == $deus) { echo "selected ";}
	}
	echo "value='".$rwus['us']."'>".$rwus['us']."</option>".PHP_EOL;
}
if (!isset($deus)) { echo "<option value='' selected> selecteer een user </option>".PHP_EOL; }
echo "</select>".PHP_EOL;
echo "</td></tr></table>".PHP_EOL;
?>
<br><br><h2> te bewerken user </h2>
<form accept-charset="UTF-8" action="acom/up_deus.php" id="usfrm" method="post">
<?	
$rwus 	= getrw($con,"SELECT * FROM us where us = '".$deus."'","el"); 
$vals	= toar($rwus['uspa']);
?>
<table id='detb'>	
<tr><th>user</th><th>email</th></tr> 
<td><input class="string" name="us"  type="text" value="<? echo $rwus['us'] ?>" /></td>
<td><input class="string" name="em"  type="text" value="<? echo $rwus['em'] ?>" /></td>
</tr>
<tr><th>voornaam</th><th>achternaam</th></tr> 
<td><input class="string" name="vn"  type="text" value="<? echo $rwus['vn'] ?>" /></td>
<td><input class="string" name="an"  type="text" value="<? echo $rwus['an'] ?>" /></td>
</tr>
</table>
<?
echo "<table id='detb'>".PHP_EOL;
echo "<tr><th>par</th><th>waarde</th></tr>".PHP_EOL;
$i	= 0;
if (isset($vals) and isset($rwus['uspa'])){
	foreach ($vals as $att => $val){
		$i	= $i + 10;
		if (strstr($val,'^') != null) {
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
echo "</table>".PHP_EOL;
?>
<table id='detb'>
<tr><td>Aktie </td></tr>
<tr><td><input type="radio" name="aktie" value="c" checked	>wijzigen </td>
<td> 	<input type="radio" name="aktie" value="d" 			>verwijder </td></tr>
</table>
<table id='detb'>
<tr><td> 	<input class="but cust1" id="filled_form_submit" name="fcommit" type="submit" value="user aktie uitvoeren" >
<td>		<input class="but cust1" id="filled_form_submit" name="fcommit" type="submit" value="sluiten" 
			onclick=" window.opener.document.location.reload(true);window.close();" > 
<td><input type="hidden" name="usky" size="20" type="text" value="<? echo $rwus['usky'] ?>"  /> </td> </tr>
</table>
</form>  
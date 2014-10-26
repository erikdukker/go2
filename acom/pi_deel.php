<?	
logmod($con,'pi_deel');
$detr	= getpar('detr');
$elky	= getpar('elky');
echo "<table id='detb'>".PHP_EOL;
echo "<tr><th>Andere element bewerken? </th></tr> ".PHP_EOL;
echo "<tr><td><select size='1' id='detr' name='detr' onclick='vvtr(\"deel\",\"detr\",\"detr\")'>".PHP_EOL;
$rstr 	= getrs($con,"select * from tr order by trid","tr"); 
while ($rwtr = mysqli_fetch_array($rstr)) {
	echo "<option ";
	if (isset($detr)){
		if($rwtr['trid'] == $detr) { echo "selected ";}
	}
	echo "value='".trim($rwtr['trid'])."'>(".trim($rwtr['trid']).") ".$rwtr['ds']."</option>".PHP_EOL;
}
echo "<option "; if(!isset($detr)) { echo "selected ";} echo "value=''>selecteer een transaktie</option>".PHP_EOL;
echo "</select> ".PHP_EOL;	
echo "</td>".PHP_EOL;
echo "<td><select size='1' id='elky' name='elky' onclick='vvtr(\"deel\",\"elky\",\"elky\")'>".PHP_EOL;
$rsel 	= getrs($con,"select * from el WHERE trid = '".$detr."' order by sq","el");  
while ($rwel = mysqli_fetch_array($rsel)) {
	echo "<option ";
	if (isset($elky)){
		if($rwel['elky'] == $elky) { echo "selected ";}
	}
	echo "value='".trim($rwel['elky'])."'>(".trim($rwel['sq']).") ".$rwel['ti']." </option>".PHP_EOL;
}
echo "<option "; if(!isset($elky)) { echo "selected ";} echo "value=''>selecteer een element </option>".PHP_EOL;
echo "</select> ".PHP_EOL;	
echo "</td></tr></table>".PHP_EOL;
?>
<script type="text/javascript" src="astd/ckeditor/ckeditor.js">
});
</script>
<?
if (isset($elky)) {
	$rwel 	= getrw($con,"SELECT * FROM el where elky = '".$elky."'","el"); 
	$elpa	= toar($rwel['elpa']);
?>
	<div>
	<form accept-charset="UTF-8" action="acom\up_deel.php" class="new_filled_form"  id="generatedForm" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" </div>
	<table id='detb'>
	<tr><th>titel</th></tr>
	<tr><td>
	<input class="string" name="ti" size="80" type="text" value="<? echo $rwel['ti'] ?>" /></td>
	</tr></table>
	<table id='detb'>
	<tr><th>Plugin</th><td><input class="string" name="pi" size="40" type="text" value="<? echo $elpa['pi'] ?>" /></td>  
	</table> 
	<table id='detb'>
	<tr><h3>Aktie</h3></th> </tr>
	<tr> <td> <input type="radio" name="aktie" value="c" checked	>  wijzigen </td>
	<td> <input type="radio" name="aktie" value="v" 		>  verwijderen </td>
	<td> <input type="radio" name="aktie" value="mmin" 		>  schuif voor vorige </td>
	<td> <input type="radio" name="aktie" value="mplus" 	>  schuif na volgende </td>
	<td> <input type="radio" name="aktie" value="kmin" 		>  kopieer ervoor </td>
	<td> <input type="radio" name="aktie" value="kplus" 	>  kopieer erna </td> </tr>
	<tr>
	<td> <input class="but cust1" name="fcommit" type="submit" value="uitvoeren" /> </td>
	<td> <input class="but cust1" name="fcommit" value="sluiten" type="but" 
							onclick=" window.opener.document.location.reload(true);window.close();" /> </td> </tr>
	<td class='required'><input type="hidden" name="elky" size="20" type="text" value="<? echo $elky ?>"  /> </td> 
	</table>
	<table id='detb'>
	<tr><th>tekst</th></tr>
	<tr><td><textarea class="large"  id="tx" name="tx"  > <? echo $rwel['tx'] ?></textarea></td></tr>
	</table>
	</form> 
	</div>

<script>
CKEDITOR.replace( 'tx');
</script>
<style>
.cke_contents {
height: 170px !important;
width: 980px !important;
}
</style>
<?
}

<?
logmod($con,'pi_deme');
$meid	= getpar('meid');
echo "<table id='detb'>".PHP_EOL;
echo "<tr><th>Ander menu bewerken? </th> ".PHP_EOL;
echo "<td><select size='1' id='meid' name='meid' onclick='vvtr(\"deme\",\"meid\",\"meid\")'>".PHP_EOL;
$rsme		= getrs($con,"SELECT DISTINCT meid from me order by meid",'me'); 
while ($rwme = mysqli_fetch_array($rsme)){
	echo "<option ";
	if (isset($meid)){
		if($rwme['meid'] == $meid) { echo "selected ";}
	}
	echo "value='".$rwme['meid']."'>".$rwme['meid']."</option>".PHP_EOL;
}
if (!isset($meid)) { echo "<option value='' selected> selecteer een menu </option>".PHP_EOL; }
echo "</select>".PHP_EOL;
echo "</td></tr></table>".PHP_EOL;
$palst 	= array();
$i		= 1;
$rstr 	= getrs($con,"SELECT * from tr order by trid","tr"); 
while ($rwtr = mysqli_fetch_array($rstr)) {
	$palst[$i]['trky'] 	=$rwtr['trky'];
	$palst[$i]['trid'] 	=$rwtr['trid'];
	$palst[$i]['ds'] 	="(".$rwtr['trid'].")".$rwtr['ds'];
	$i++;	
}
$act = array( );
$act[1] = array (	"<option select 	value='n'	> 									</option>",
					"<option 			value='v'	> verander 							</option>",
					"<option 			value='1sv'	> schuif voor vorige				</option>",
					"<option 			value='1sn'	> schuif na volgende				</option>",
					"<option 			value='1nm'	> nieuwe menubalkkeuze				</option>",
					"<option 			value='1nu'	> nieuwe uitklapkeuze				</option>",
					"<option 			value='1d'	> verwijder (als geen uitklapkeuze)</option>");

$act[2] = array (	"<option select 	value='n'	> 									</option>",
					"<option 			value='v'	> verander 							</option>",
					"<option 			value='2sv'	> schuif voor vorige				</option>",
					"<option 			value='2sn'	> schuif na volgende				</option>",
					"<option 			value='2ns'	> nieuw subkeuze					</option>",
					"<option 			value='2d'	> verwijder (als geen subkeuze)		</option>");

$act[3] = array (	"<option select 	value='n'	> 									</option>",
					"<option 			value='v'	> verander							</option>",
					"<option 			value='3sv'	> schuif voor vorige				</option>",
					"<option 			value='3sn'	> schuif na volgende				</option>",
					"<option 			value='3d'	> verwijder 						</option>");
?>
<div id='el'>
<h2>menu</h2>
<form accept-charset="UTF-8" action="acom/up_deme.php" method="post">
<table id='meForm'>
<th>menubalk keuze</th><th>uitklap keuze<th><th>pagina</th><th>aktie</th>
<?
$i = 1;
$me = array();
if (isset($meid)){
	$rsme	= getrs($con,"SELECT * from me WHERE meid = '".$meid."'  order by lv1, lv2, lv3","me"); 
	while ($rwme = mysqli_fetch_array($rsme)) {
		$me[$i]['meky'] = $rwme['meky'];
		$me[$i]['ti'] 	= $rwme['ti'];
		$me[$i]['lv1'] 	= $rwme['lv1'];
		$me[$i]['lv2'] 	= $rwme['lv2'];
		$me[$i]['lv3'] 	= $rwme['lv3'];
		$me[$i]['meid'] = $rwme['meid'];
		$me[$i]['ref'] 	= 'x';
		$me[$i]['trid'] = $rwme['trid'];
		$i++;
	}	
	$i 	= 1;
	while (isset($me[$i]['meky'])){
		if ($me[$i]['lv2'] 	!= 0 and  $me[$i-1]['lv2'] == 0 	){ $me[$i - 1]['ref'] = '';}
		if ($me[$i]['lv3'] 	!= 0 and  $me[$i-1]['lv3'] == 0 	){ $me[$i - 1]['ref'] = '';}
		$i++;
	}	
	$i 	= 1;
	$lv	= 0;
	while (isset($me[$i]['meky'])){
		if ($me[$i]['lv2'] == 0 and $me[$i]['lv3'] == 0 ) { 		// de top
			$me[$i]['lv'] 	= 1;
			echo "<tr>";
			echo "<td class='required'><input class='string' name='ti".$i."' size='20' type='text' value='".$me[$i]['ti']."' /></td>";
			echo "<td></td><td></td>".PHP_EOL; 
		} elseif ($me[$i]['lv2'] != 0 and $me[$i]['lv3'] == 0 ) { 	// sub
			$me[$i]['lv'] 	= 2;
			echo "<tr><td></td>";
			echo "<td class='required'><input class='string' name='ti".$i."' size='20' type='text' value='".$me[$i]['ti']."' /></td>";
			echo "<td></td>".PHP_EOL; 
		} elseif ($me[$i]['lv2'] != 0 and $me[$i]['lv3'] != 0 ) { // sub sub
			$me[$i]['lv'] 	= 3;
			echo "<tr><td></td><td></td>";
			echo "<td class='required'><input class='string' name='ti".$i."' size='20' type='text' value='".$me[$i]['ti']."' /></td>";
			echo "".PHP_EOL; 
		}
		echo "<td>";
		if ($me[$i]['ref'] 	== 'x'){
			echo "<select size='1' name='trid".$i."'>";
			$j 		= 1;
			while (isset($palst[$j]['trid'])){
				echo "<option ";
				if($palst[$j]['trid'] == $me[$i]['trid']) { echo "selected ";}
				echo "value='".$palst[$j]['trid']."'>".$palst[$j]['ds']."</option>".PHP_EOL;
				$j++;
			}
			if (!isset($me[$i]['trid'])) { echo "<option value='' selected> </option>".PHP_EOL; }
			echo "</select>".PHP_EOL;
		}
		echo "</td><td>";
		echo "<select size='1' name='actie".$i."'>";
		foreach ($act[$me[$i]['lv']] as $opt){ echo $opt.PHP_EOL; }
		echo "</select>".PHP_EOL;
		echo "<td><input type='hidden' name='lv1".$i."' 	size='20' type='text' value='".$me[$i]['lv1']."'  /></td>".PHP_EOL;
		echo "<td><input type='hidden' name='lv2".$i."' 	size='20' type='text' value='".$me[$i]['lv2']."'  /></td>".PHP_EOL;
		echo "<td><input type='hidden' name='lv3".$i."'		size='20' type='text' value='".$me[$i]['lv3']."'  /></td>".PHP_EOL;
		echo "<td><input type='hidden' name='meid".$i."' 	size='20' type='text' value='".$me[$i]['meid']."'  /></td>".PHP_EOL;
		echo "<td><input type='hidden' name='meky".$i."' 	size='20' type='text' value='".$me[$i]['meky']."'  /></td>".PHP_EOL;
		echo "</td>";	
		echo "</tr>".PHP_EOL;
		$i++;
	}
}
?>	
<tr class='required'>
<hd>  </hd><td> <input class="but cust1" id="filled_form_submit" name="fcommit" type="submit" value="aktie uitvoeren" /> </td>
<hd>  </hd><td> <input class="but cust1" id="filled_form_submit" name="fcommit" value="sluiten" <input type="but" 
						onclick=" window.opener.document.location.reload(true);window.close();" /> </td>
</tr>
</table> 
</form> 
<? 

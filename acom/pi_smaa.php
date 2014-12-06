<?
logmod($con,'pi_smaa start sommen');
$acco	= getpar('acco');
$lv		= getpar('lv','vol');
$rp		= getpar('rp','sim');
if (isset ($_POST['acco'])) {$acco	=  $_POST['acco']; $_SESSION['acco'] = $acco;}
echo "<script src='acom/gosm.js' type='text/javascript'></script> ".PHP_EOL;
include 'acom/in_smgn.php';
$anSom	= 5;
$exAc	= 0;
$usea	= 'u';
if (isset($_SESSION['voKen'])) {	 // de laatste som herhalen 
	$ken	= $_SESSION['voKen'];
	$kens	= str_getcsv($ken,'|');
	logval($con,$_SESSION['voKen'],'ken vorige');
	$sr		= $kens[0];
	$br		= $kens[1];
	$vr		= $kens[2];
	$exAc	= 1;
	//val($vr);
	$pas	= toar($_SESSION['voPas']);
	logarr($con,$pas,'pas vorige');
	logarr($con,$_SESSION,'session');
	$som	= toar($_SESSION['voSom']);
	logarr($con,$som,'som vorige');
	$somNo 	= 0;
	include 'acom/in_smtn.php';
} else {
	echo "<div id=d|0></div>";
}
$i		= 0;
if (isset($somRes)){
	foreach ($somRes as $iken => $apas) {
		$volg[$i]		= $iken;
		$i++;
	}
	//val($anSom);
	shuffle($volg);
	logarr($con,$volg,'volg');
	echo "<form action='acom/up_smtn.php' id='fsmtn' name='fsmtn' method='post' >".PHP_EOL;
	for ($somNo = 1; $somNo <= $anSom; $somNo++) {
		$iken	= $volg[$somNo - 1];
		$ken	= substr($iken,1);
		//val('iken '.$iken.'ken '.$ken);
		$kens	= str_getcsv(substr($iken,1),'|');
		$sr		= $kens[0];
		$br		= $kens[1];
		$vr		= $kens[2];
		$pas	= $somPas[$ken];
		$som	= $somRes[$iken];
		logarr($con,$kens,"de toon loop (kens)");
		logarr($con,$pas,"de toon loop (pas)");
		logarr($con,$som,"de toon loop (som)");
		if ($somNo == $anSom) { // laatste
			$exAc = 9;
		} 
		include 'acom/in_smtn.php';
		if (isset($setFoc)) {echo "<script>document.getElementById('t|1').focus()</script>"; unset($setFoc);}
		$exAc 	= 0; // leeg
	}
	echo "<input id='ansom' name='ansom' type='hidden' value = '".$anSom."' />";	
	echo "</form> ".PHP_EOL; 
	echo "</div>".PHP_EOL;
	echo "</td></tr>".PHP_EOL;
}
include 'acom/in_smem.php'; 
echo "<tr><td><table><tr><td  class='lab'>".PHP_EOL;
echo "<h2>stel in</h2>".PHP_EOL;
echo "</td><td>doel: ".PHP_EOL;
echo "<select size='1' id='lv' name='lv' onclick='vvtr(\"smaa\",\"lv\",\"lv\")'>".PHP_EOL;
echo "<option "; if($lv == 'sn') 	{ echo "selected ";} echo "value='sn'>snel oefenen</option>".PHP_EOL;
echo "<option "; if($lv == 'vol') 	{ echo "selected ";} echo "value='vol'>voldoende (6)</option>".PHP_EOL;
echo "<option "; if($lv == 'goe') 	{ echo "selected ";} echo "value='goe'>goed (8)</option>".PHP_EOL;
echo "<option "; if($lv == 'gom') 	{ echo "selected ";} echo "value='gom'>top (9)</option>".PHP_EOL;
echo "</select></td><td>scores:".PHP_EOL;	
echo "<select size='1' id='rp' name='rp' onclick='vvtr(\"smaa\",\"rp\",\"rp\")'>".PHP_EOL;
echo "<option "; if($rp == 'sim') 	{ echo "selected ";} echo "value='sim'>gewoon</option>".PHP_EOL;
echo "<option "; if($rp == 'det') 	{ echo "selected ";} echo "value='det'>extra</option>".PHP_EOL;
echo "<option "; if($rp == 'ver') 	{ echo "selected ";} echo "value='ver'>scoreverloop</option>".PHP_EOL;
echo "<option "; if($rp == 'all') 	{ echo "selected ";} echo "value='all'>alles</option>".PHP_EOL;
echo "<option "; if($rp == 'niet') 	{ echo "selected ";} echo "value='niet'>geen scores</option>".PHP_EOL;
echo "</select> ".PHP_EOL;	
echo "</td></tr></table></td></tr>".PHP_EOL;
include 'acom/in_smst.php'; 
?>	
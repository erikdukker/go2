<?	
/* algemene kode */
include 'in_func.php';
include '../aown/in_lconn.php'; 
include '../astd/PHPExcel/Classes/PHPExcel.php';
$t		=	time() - 1387818826;
$file	=	'UPTS'.$t.'.xlsx';
logval($con,$file,"filename"	);
$target = "../zfil/".$file; 
if ( move_uploaded_file($_FILES['upts']['tmp_name'], $target) )  { 
	echo "De test ".basename( $_FILES['upts']['name'])." is geupload.";
} 
else  { 
	echo "Sorry, uw test is niet geupload.";
}	 
logval($con,'voor','voor');
//$_SESSION['log'] = 'start';
$objPHPExcel = PHPExcel_IOFactory::load($target);
$clpr	= 'niet leeg';
for ($x = 0; $clpr != ''; $x++) { 
	$label[$x] = trim(leescel($objPHPExcel,$x,1)); 
	$clpr = $label[$x];
//	echo "<br>".$clpr ;
}
$xmx	= $x - 1;

//$_SESSION['log'] = "ldco";

$tpweg = leescel($objPHPExcel,0,2);

exsql($con,"delete from ts ","heel defl verwijderen"); 

$nalaatste	= 'nog niet';
for ($y = 2; $nalaatste	==  'nog niet'; $y++) { 
	if ( strlen(leescel($objPHPExcel,0,$y)) != 0 ) {
	    unset($tsval,$tspa);
		for ($x = 0; $x < $xmx ; $x++) { 
			$waarde	= leescel($objPHPExcel,$x,$y);
			$tsval[$label[$x]] = $waarde;
		}
		$tspaTx	= str_getcsv($tsval['tspa'],';');
		foreach ( $tspaTx as $tspaPar) {
			$tspa[substr(trim($tspaPar),0,strpos($tspaPar,"="))] = substr(trim($tspaPar),strpos($tspaPar,"=")+ 1);
		}
		switch ($tsval['tp']) {
			case 'vr': 	
				exsql($con,"insert ts set co = 'defl', tp = 'vr', vr = '".$tsval['id']."', 
					ti = '".$tsval['ti']."', wd = '".$tsval['wd']."', rl = '".$tsval['rl']."', tspa = '".totx($tspa)."', tx = '".$tsval['tx']."'","vr");
				break;	
			case 'sr': 	
				exsql($con,"insert ts set co = 'defl', tp = 'sr', sr = '".$tsval['id']."',
					ti = '".$tsval['ti']."', wd = '".$tsval['wd']."', rl = '".$tsval['rl']."', tspa = '".totx($tspa)."', tx = '".$tsval['tx']."'","tp");
				break;			
			case 'br': 	
				exsql($con,"insert ts set co = 'defl', tp = 'br', br = '".$tsval['id']."',
					ti = '".$tsval['ti']."', wd = '".$tsval['wd']."', rl = '".$tsval['rl']."', tspa = '".totx($tspa)."', tx = '".$tsval['tx']."'","br");
				break;		
		}
} else {
		$nalaatste	= 'nu wel';
	}
}
$rwvr		= getrw($con,"SELECT * FROM ts where co = 'defl' and tp = 'vr' and vr = 'ov'","vr ov"); 
$vrpaov		= toar($rwvr['tspa']);
$vrtiov		= $rwvr['ti'];
$rwvr		= getrw($con,"SELECT * FROM ts where co = 'defl' and tp = 'vr' and vr = 'mk'","vr mk"); 
$vrpamk		= toar($rwvr['tspa']);
$vrtimk		= $rwvr['ti'];
$rsbr		= getrs($con,"SELECT * FROM ts where co = 'defl' and tp = 'br' order by tsky","br"); 
while ($rwbr 	= mysqli_fetch_array($rsbr)){
	unset($covrs);
	$brpa		= toar($rwbr['tspa']);
	logarr($con,$brpa,"brpa");
	$rwsr		= getrw($con,"SELECT * FROM ts where co = 'defl' and tp = 'sr' and sr = '".$brpa['sr']."' order by tsky","sr ophalen"); 
	$srpa		= toar($rwbr['tspa']);
	$cos		= str_getcsv($rwbr['rl'],';');
	foreach ( $cos as $co) { // aanvullen
		$coel		= str_getcsv($co,'|');
		if (isset($coel[1])) {
			$covrs[$coel[0].'|'.$coel[1]]	= $coel[1];
		} else {
			$covrs[$coel[0].'|ov']	= 'ov';
			$covrs[$coel[0].'|mk']	= 'mk';			
		}
	}
	logarr($con,$covrs,"aangevulde co");

	foreach ( $covrs as $coky => $covr) {
		$coel		= str_getcsv($coky,'|');
		$tspa		= array();
		if ($coel[1]	== 'ov'){ $vrpa = $vrpaov; $vrti =	 $vrtiov;} else { $vrpa = $vrpamk; $vrti = $vrtimk;}
		if ($vrpa != null){	 foreach ($vrpa as $att => $val) { 	$tspa[$att]		= $val; }}
		if ($srpa != null){	 foreach ($srpa as $att => $val) { 	$tspa[$att]		= $val; }}
		if ($brpa != null){	 foreach ($brpa as $att => $val) { 	$tspa[$att]		= $val; }}
		$ti		= $rwsr['ti']."|".$rwbr['ti']."|".$vrti;
		$wd		= $rwsr['wd'] + $rwbr['wd'] + $rwvr['wd'];
		exsql($con,"insert ts set co = '".$coel[0]."', tp = 'co', 
			sr = '".$brpa['sr']."', br = '".$rwbr['br']."', vr = '".$coel[1]."',
			ti = '".$ti."', wd = '".$wd."', tspa = '".totx($tspa)."'","co");	
	}
}
header("location:"."../index.php?t=cosd&m=excel geladen");
?>


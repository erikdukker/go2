<?	
/* algemene kode */
include 'in_func.php';
include '../aown/in_lconn.php'; 
include '../astd/PHPExcel/Classes/PHPExcel.php';
$t		=	time() - 1387818826;
$file	=	'UPTS'.$t.'.xlsx';
logval($con,$file,"filename"	);
$target = "../zfil/".$file; 
if ( move_uploaded_file($_FILES['upev']['tmp_name'], $target) )  { 
	echo "De test ".basename( $_FILES['upts']['name'])." is geupload.";
} 
else  { 
	echo "Sorry, uw events zijn niet geupload.";
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

exsql($con,"delete from ev where rol = 'std' ","heel std verwijderen"); 

$nalaatste	= 'nog niet';
for ($y = 2; $nalaatste	==  'nog niet'; $y++) { 
	if ( strlen(leescel($objPHPExcel,0,$y)) != 0 ) {
	    unset($tsval,$evpa);
		for ($x = 0; $x < $xmx ; $x++) { 
			$waarde	= leescel($objPHPExcel,$x,$y);
			$tsval[$label[$x]] = $waarde;
		}
		$evpaTx	= str_getcsv($tsval['evpa'],';');
		foreach ( $evpaTx as $evpaPar) {
			$evpa[substr(trim($evpaPar),0,strpos($evpaPar,"="))] = substr(trim($evpaPar),strpos($evpaPar,"=")+ 1);
		}
		switch ($tsval['tp']) {
			case 'st': 	
				exsql($con,"insert ev set rol = '".$tsval['rol']."', tp = '".$tsval['tp']."', evid = '".$tsval['evid']."', 
					ti = '".$tsval['ti']."',  evpa = '".totx($evpa)."', tx = '".$tsval['tx']."'","ev");
				break;	
			case 'ev': 	
				exsql($con,"insert ev set rol = '".$tsval['rol']."', tp = '".$tsval['tp']."', evid = '".$tsval['evid']."', 
					ti = '".$tsval['ti']."',  evpa = '".totx($evpa)."', tx = '".$tsval['tx']."'","ev");
				break;	
		}
} else {
		$nalaatste	= 'nu wel';
	}
}
header("location:"."../index.php?t=cosd&m=excel geladen");
?>


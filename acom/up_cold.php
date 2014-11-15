<?	
/* algemene kode */
include 'in_func.php';
include '../aown/in_lconn.php'; 
include '../astd/PHPExcel/Classes/PHPExcel.php';
$t		=	time() - 1387818826;
$file	=	'UPTS'.$t.'.xlsx';
//logval($con,$file,"filename"	);
$target = "../zfil/".$file; 
if ( move_uploaded_file($_FILES['upts']['tmp_name'], $target) )  { 
	echo "De test ".basename( $_FILES['upts']['name'])." is geupload.";
} 
else  { 
	echo "Sorry, uw test is niet geupload.";
}	 
//logval($con,'voor','voor');
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

exsql($con,"TRUNCATE TABLE ts","heel ts verwijderen"); 

$nalaatste	= 'nog niet';
for ($y = 2; $nalaatste	==  'nog niet'; $y++) { 
	if ( strlen(leescel($objPHPExcel,0,$y)) != 0 ) {
	    unset($tsval,$tspa);
		for ($x = 0; $x < $xmx ; $x++) { 
			$waarde	= leescel($objPHPExcel,$x,$y);
			$tsval[$label[$x]] = $waarde;
		}
		switch ($tsval['tp']) {
			case 'pa': 	
				$sets	= str_getcsv($tsval['tspa'],'<'); //splits de verschillende vormen	
			//	logarr($con,$sets,"pa sets ");
				foreach ( $sets as $set) {
					if ($set != '') {
						$set	= trim($set,">"); // strip >
					//	logval($con,$set,"pa set");
						$tpas	= str_getcsv($set,';');
				//		logarr($con,$tpas,"pa tpas ");
						foreach ( $tpas as $tpar) {
							$ttpas[substr(trim($tpar," "),0,strpos($tpar,"="))] = substr(trim($tpar," "),strpos($tpar,"=")+ 1);
						//	logarr($con,$ttpas,"pa na ".$tpar);
						}
						$id	= $tsval['id'];
						//logarr($con,$tsval,"pa tsval ");
					//	logval($con,$id,"pa id ");
					//	logarr($con,$ttpas,"pa ttpas ");
						foreach ( $ttpas as $par => $wrd) {
							if ($par == $id) {
								$ky	= $wrd;
							} else {
								$pas[$ky.'|'.$par] = $wrd;
							}					
						}
						unset($tpas,$ttpas);
					}
				}
				exsql($con,"insert ts set tp = 'pa', id = '".$tsval['id']."', 
					ti = '".$tsval['ti']."', tspa = '".totx($pas)."'","pa");
				//logarr($con,$pas,"pa pas ");
				if ($id == 'vr') {
					$vrs = $pas;
				}
				unset($pas);
			break;			
			case 'co': 	
				exsql($con,"insert ts set id = '".$tsval['id']."', tp = 'co', 
					ti = '".$tsval['ti']."', tspa = '".totx('')."', tx = '".totx('')."'","tp");
			break;			
			case 'br': 	
				$sets	= str_getcsv($tsval['tspa'],'<'); //splits de verschillende vormen				
				unset  	($sets[0]);
				foreach ( $sets as $set) {
					$set	= trim($set,">"); // strip >
					$tpas	= str_getcsv($set,';');
					foreach ( $tpas as $tpar) { //parameters in $pas aggregeren
						$pas[substr(trim($tpar," "),0,strpos($tpar,"="))] = substr(trim($tpar," "),strpos($tpar,"=")+ 1);
						//logarr($con,$pas,"pas na ".$tpar);
					}
					if (isset($pas['rk'])) {
						$pas['om']		= $tsval['ti'];
						$pasrk 			= $pas;
						$pasrk['br'] 	= $tsval['id'];						
					} else {
						if (isset($pas['rl'])) {$cos[$pas['rl']] = $pas;}
					}
					unset($pas);
				}
				foreach ( $cos as $rl => $pars) {
					$rlel		= str_getcsv($rl,'|'); //splits co van vrm	
					$pas['vr'] 	= $rlel[1];
				//	logarr($con,$pas,"pas na init");
					$rwco		= getrw($con,"SELECT * FROM ts where id = '".$rlel[0]."' and tp = 'co'","co"); 	
					$tspa		= toar($rwco['tspa']);
					$tx			= toar($rwco['tx']);
					foreach ( $pasrk as $par => $wrd) { // de rekenregels rk
						$pas[$par] 	= $wrd;
					}
				//	logarr($con,$pas,"pas na rk");
					foreach ( $pars as $par => $wrd) { // de co parameters
						$pas[$par] 	= $wrd;
					}
				//	logarr($con,$pas,"pa na co ");
				//	logarr($con,$vrs,"vrs ");
					foreach ( $vrs as $par => $wrd) { // de vorm (vr) parameters aanvullen
						$pael			= str_getcsv($par,'|'); //splits par
						if ($pael[0] 	== $rlel[1]) {
							if (!isset($pas[$pael[1]])){
								$pas[$pael[1]] 	= $wrd;
							}
						}
					}
					if (isset($pas['wd']) and isset($pas['wc'])) { // is er een correctie ?
						$pas['wd'] = $pas['wd'] + $pas['wc'];
						unset($pas['wc']);
					}		
					logarr($con,$pas,"pas na vr ");
					$ky			= $pas['br'].'|'.$pas['vr'];
					$tspa[$ky] 	= $pas;					
					exsql($con,"update ts set tspa = '".totx($tspa)."' where tsky = '".$rwco['tsky']."'","update co");
					unset($pas);
				}
				unset($sets,$cos);
			break;		
		}
} else {
		$nalaatste	= 'nu wel';
	}
}
header("location:"."../index.php?t=cosd&m=excel geladen");
?>


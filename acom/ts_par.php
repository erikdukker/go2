<?
/* algemene kode */
include 'in_func.php';
include '../aown/in_lconn.php'; 
logmod($con,'in_smgn.php genereer sommen');
$parvr	= "<vr=mk;as=10;aa=5;ag=5><vr=ov;as=10>";
$parco		= "<rk=vm|1;t1b=1;t1t=10;t2b=1;t2t=1><co=tf01|mk;wd=10;ao=3><co=tf01|ov;wd=15;ao=10>";

$vrs		= str_getcsv($parvr,'<'); //splits de verschillende vormen
unset($vrs[0]);
logarr($con,$vrs,"vormen");

foreach ( $vrs as $vr) {
	$vr		= trim($vr,">"); // strip >
	$tpas	= str_getcsv($vr,';');
	foreach ( $tpas as $tpar) {
		$pas[substr(trim($tpar," "),0,strpos($tpar,"="))] = substr(trim($tpar," "),strpos($tpar,"=")+ 1);
		logarr($con,$pas,"vr na ".$tpar);
	}
	if ($pas['vr']='mk'){ $mkpar	= $pas;}
	if ($pas['vr']='ov'){ $ovpar	= $pas;}
	unset($pas);
}
$sets	= str_getcsv($parco,'<'); //splits de verschillende parametersets
unset($sets[0]);
foreach ( $sets as $set) {
	$set	= trim($set,">"); // strip >
	$tpas	= str_getcsv($set,';');
	foreach ( $tpas as $tpar) { //parameters in $pas aggregeren
		$pas[substr(trim($tpar," "),0,strpos($tpar,"="))] = substr(trim($tpar," "),strpos($tpar,"=")+ 1);
		logarr($con,$pas,"pas na ".$tpar);
		}
}
if (!isset($pas['rk'])) { logval($con,"","fout in parameters: rk niet gevuld"); } else {
	$selvr	= str_getcsv($pas['rk'],'|');
	if ($selvr = 'mk') {$vr = $mkpar;}
	if ($selvr = 'ov') {$vr = $ovpar;}
	foreach ( $vr as $key => $entry) {
		if (!isset($pas[$key])){
			$pas[$key]	= $entry;
		}			
	}
}


logarr($con,$pas,"pas geagregeerd");
?>
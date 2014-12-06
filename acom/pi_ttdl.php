<?
logmod($con,'pi_ttdl.php delete totalen');
//logval($con,memory_get_usage(),"aan het begin");
$rwco			= getrw($con,"SELECT * FROM ts where id = '".$_SESSION['acco']."' and tp = 'co'","co"); 
$tspa			= toar($rwco['tspa']);
foreach ( $tspa as $br => $pas) {
	logarr($con,$pas,"pas");
	$rkel	= str_getcsv($pas['rk'],'|'); //splits co van vrm	
	$sr		= $rkel[0];
	$br		= $pas['br'];
	$vr		= $pas['vr'];
	if ($rwtt = getrw($con,"SELECT * FROM tt where sr = '".$sr."' and br = '".$br."' and vr ='".$vr.
							"' and ssid = '".$_SESSION['gossid']."'","tt")){
		exsql($con,"delete from tt where ttky = '".$rwtt['ttky']."'","ttdel"); 
	}
}
?>
<?	
include '../aown/in_lconn.php';
include '../acom/in_func.php';
$bood	= 'leuk';
$aktie	= $_POST['aktie']; 
logarr($con,$_POST,'alle waarden');
	
if ($aktie == 'd' ) {
	if ($_POST['cosl'] == $_POST['nwCo']) {
		exsql($con,"delete from ts where co = '".$_POST['cosl']."' and tp = 'co'","co"); 
		$bood			='configuratie verwijderd';
	} else {
		$bood			='configuratie NIET verwijderd. Geselecteerde naam moet gelijk zijn aan ingetypte ';
	}
}
	
if ($aktie == 'c' ) {
	$rsco			= getrs($con,"SELECT * FROM ts where co = '".$_POST['cosl']."' and tp = 'co'","co"); 
	while ($rwco 	= mysqli_fetch_array($rsco)){
		$ky 		= $rwco['sr']."|".$rwco['br']."|".$rwco['vr']; 
		if (!isset($_POST['ck'.$ky])) {
			exsql($con,"delete from ts where tsky = '".$rwco['tsky']."'","verwijder"); 
		} else {
			$alAan[$ky] 	= 'a';
		}
	}	
	foreach ($_POST as $key => $entry) {
		if (substr($key,0,2) == 'ch' ) {
			$kyEl		= str_getcsv(substr($key,2),'|');
			logarr($con,$kyEl,'waaarom');
			exsql($con,"insert ts set co = '".$_POST['cosl']."', tp = 'co'".
				", sr = '".$kyEl[0]."', br = '".$kyEl[1]."', vr = '".$kyEl[2]."'","nieuw");	
		}
	}
	$bood			='configuratie veranderd ';
}
		
if ($aktie == 'n' ) {
logval($con,$aktie,'aktie  komt i strigst');
	foreach ($_POST as $key => $entry) {	
		logval($con,$key,'akey');
		logval($con,substr($key,0,2),'akey');
		if (substr($key,0,2) == 'ch' and $entry == 'a') {
			logval($con,$entry,'entrykey');
			//	val ($key);
			$kyEl		= str_getcsv(substr($key,2),'|');
						logarr($con,$kyEl,'waaarom');
			exsql($con,"insert ts set co = '".$_POST['nwCo']."', tp = 'co'".
					", sr = '".$kyEl[0]."', br = '".$kyEl[1]."', vr = '".$kyEl[2]."'","nieuw");			
		}
	}
	$bood			='configuratie opgeslagen ';
}
header("location:"."../index.php?t=cosl&m=enquete OK is gelukt");
?>

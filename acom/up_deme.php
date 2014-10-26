<?	
include '../aown/in_lconn.php';
include '../acom/in_func.php';
logmod($con,'up_deme');
$i = 1;
$me = array();
while ($_POST["ti".$i]){
	$me[$i]['ti'] 		= $_POST["ti".$i];
	$me[$i]['trid'] 	= $_POST["trid".$i];
	$me[$i]['actie'] 	= $_POST["actie".$i];
	$me[$i]['lv1'] 		= $_POST["lv1".$i];
	$me[$i]['lv2']	 	= $_POST["lv2".$i];
	$me[$i]['lv3'] 		= $_POST["lv3".$i];
	$me[$i]['meid'] 	= $_POST["meid".$i];
	$me[$i]['meky'] 	= $_POST["meky".$i];
	$i++;
}
$i = 1;		
$stat	= '';
$renum 	= '';
$me[$i]['ti'];
while ($me[$i]['actie'])  {
	switch ($me[$i]['actie']) {
			case 'n':	break;		// niets
			case 'v': 				// verander 
						exsql($con,"update me set ti ='".$me[$i]['ti']."', trid ='".$me[$i]['trid']."'  WHERE meky = '".$me[$i]['meky']."'","verander");
						break;	
			case '3d': 				// verwijder 
						exsql($con,"delete from me where meky = '".$me[$i]['meky']."'","verwijder"); 
						break;	
			case '1d': 				// verwijder 
						if	($me[$i]['lv1'] != $me[$i + 1]['lv1'] or $me[$i + 1]['lv2'] == 0) { //geen uitklap
							exsql($con,"delete from me where meky = '".$me[$i]['meky']."'","verwijder"); 
							$renum 	= 'x';	
						}
						break;	
			case '2d': 				// verwijder  
						//echo $me[$i]['lv2']."!".$me[$i + 1]['lv2']."!".$me[$i + 1]['lv3'].PHP_EOL;
						if	($me[$i]['lv2'] != $me[$i + 1]['lv2'] or $me[$i + 1]['lv3'] == 0) { //geen sub
							exsql($con,"delete from me where meky = '".$me[$i]['meky']."'","verwijder"); 

						}	
						$renum 	= 'x';		
							break;	
			case '1nm': 			// nieuwe menubalkkeuze	
						$lv1 	= $me[$i]['lv1'] + 4;
						exsql($con,"insert me SET lv1 = '".$lv1."',lv2 = '".$me[$i]['lv2']."',lv3 = '".$me[$i]['lv3']."', 
								ti = 'nieuw',trid = '', meid='".$me[$i]['meid']."'","nieuw");
						$renum 	= 'x';		
						break;	
			case '1nu': 			// nieuwe uitklapkeuze	
						exsql($con,"insert me SET lv1 = '".$me[$i]['lv1']."',lv2 = '9999',lv3 = '0', 
								ti = 'nieuw',trid = '', meid='".$me[$i]['meid']."'","nieuw");
						$renum 	= 'x';		
						break;	
			case '2ns': 				// nieuw subkeuze
						exsql($con,"insert me SET lv1 = '".$me[$i]['lv1']."', lv2 = '".$me[$i]['lv2']."', lv3 = '9999', 
								ti = 'nieuw',trid = '', meid='".$me[$i]['meid']."'","nieuw");
						$renum 	= 'x';		
						break;	
			case '1sv': 				//  schuif voor vorige 
						if ($me[$i]['lv1'] >= 20) {
							$lv1 	= $me[$i]['lv1'] - 13;
							exsql($con,"update me set lv1 = '".$lv1."' where lv1 = '".$me[$i]['lv1']."'","schuif");
							$renum 	= 'x';	
						}
						break;	
			case '1sn': 				// schuif na volgende 
						$lv1 	= $me[$i]['lv1'] + 13;
						exsql($con,"update me set lv1 = '".$lv1."' where lv1 = '".$me[$i]['lv1']."' ","schuif");
						$renum 	= 'x';		
						break;				
			case '2sv': 				// schuiven boven  
						if ($me[$i]['lv2'] >= 20) {
							$lv2 	= $me[$i]['lv2'] - 13;
							exsql($con,"update me set lv2 = '".$lv2."' where lv2 = '".$me[$i]['lv2']."' ","schuif");
							$renum 	= 'x';		
						}	
						break;	
			case '2sn': 				// schuiven onder   
						$lv2 	= $me[$i]['lv2'] + 13;
						exsql($con,"update me set lv2 = '".$lv2."' where lv2 = '".$me[$i]['lv2']."' ","schuif");
						$renum 	= 'x';		
						break;				
			case '3sv': 				// schuiven boven   
						if ($me[$i]['lv3'] >= 20) {
							$lv3 	= $me[$i]['lv3'] - 13;
							exsql($con,"update me set lv3 = '".$lv3."' where lv3 = '".$me[$i]['lv3']."' ","schuif");
							$renum 	= 'x';
						}				
						break;	
			case '3sn': 				// schuiven onder    
						$lv3 	= $me[$i]['lv3'] + 13;
						exsql($con,"update me set lv3 = '".$lv3."' where lv3 = '".$me[$i]['lv3']."'","schuif");
						break;				
	}	
	$i++;
}
if ( $renum 	= 'x' ) {
	$lv1 	= 0;
	$lv2 	= 0;
	$lv3 	= 0;
	$lv1nw 	= 0;
	$rsme	= getrs($con,"SELECT * from me WHERE  meid = '".$me[1]['meid']."' order by lv1, lv2, lv3","me"); 
	while ($rwme = mysqli_fetch_array($rsme)) {
		//tval($lv1,'$lv1');
		//tval($rwme['lv1'],'$lv1');
		if ($lv1 != $rwme['lv1']) {
			$lv1	= $rwme['lv1'];
			$lv2 	= 0;
			$lv3 	= 0;
			$lv1nw 	= $lv1nw + 10;
			$lv2nw 	= 0;
			$lv3nw 	= 0;
		} elseif ($lv2 != $rwme['lv2']) {
			$lv2	= $rwme['lv2'];
			$lv3 	= 0;
			$lv2nw 	= $lv2nw + 10;
			$lv3nw 	= 0;
		} elseif ($lv3!= $rwme['lv3']) {
			$lv3	= $rwme['lv3'];
			$lv3nw 	= $lv3nw + 10;
		}
		exsql($con,"update me set lv1 = '".$lv1nw."', lv2 = '".$lv2nw."',lv3 = '".$lv3nw."' where meky = '".$rwme['meky']."'","hernum");
	}	
}	
header("location:"."../index.php?t=deme");
?>

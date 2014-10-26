<?	 
include '../aown/in_lconn.php';
include '../acom/in_func.php';
logmod($con,'up_deel');
$elky		= $_POST['elky'];
$sq			= $_POST['sq'];
$ti 		= san($con,$_POST['ti']);
$tx 		= san($con,$_POST['tx']);
$elpa['pi']	= san($con,$_POST['tx']);		
	
$stat	= '';
//logarr($con,$_POST,'wat krijgen we binnen');
switch ($_POST['aktie']) {
		case 'a': 	$stat	= 'klaar'; break;		// annuleer		 	
		case 'v': 									// verwijderen 
					exsql($con,"delete from el where elky = '".$elky."'","verwijder");
					$stat	= 'klaar'; break;	
}			
if ($stat	!= 'klaar'){
	$rwel 	= getrw($con,"SELECT * FROM el where elky = '".$elky."'","el"); 
	$elpa['pi']	= san($con,$_POST['pi']);
	switch ($_POST['aktie']) {
		case 'c': 	 								// wijzigen 
			$velden 	= " sq = '".$sq."'";
			exsql($con,"UPDATE el SET ti = '".$ti."',tx = '".$tx."',elpa = '".totx($elpa)."',".$velden." WHERE elky = '".$elky."'","wijzig");
			$stat	= 'klaar'; break;		 	
		case 'mmin': 								// schuif voor vorige
		    if ($rwel['sq'] == 100) { 
				$velden 	= "sq = '90'";
			} else {
				$sqnw	 	= $rwel['sq'] - 110;
				$velden 	.= "sq = ".$sqnw;
			}
			exsql($con,"UPDATE el SET ".$velden." WHERE elky = '".$elky."'","schuif");
			$stat	= 'hernum'; break;	
		case 'mplus': 								// schuif na volgende 
			$sqnw	 	= $rwel['sq'] - 110;
			$velden 	.= "sq = ".$sqnw;
			exsql($con,"UPDATE el SET ".$velden." WHERE elky = '".$elky."'","schuif");
			$stat	= 'hernum'; break;		
		case 'kmin': 								// kopieer ervoor
			$velden 	.= "trid= '".$rwel['trid']."',";
		    if ($rwel['sq'] == 100) { 
				$velden 	.= "sq = '90'";
			} else {
				$sqnw	 	= $rwel['sq'] - 110;
				$velden 	.= "sq = ".$sqnw;
			}
			exsql($con,"insert el SET ".$velden ,"kopieer");
			$stat	= 'hernum'; break;	
		case 'kplus': 								// kopieer erna 
			$velden 	.= "trid= '".$rwel['trid']."',";
			$sqnw	 	= $rwel['sq'] - 110;
			$velden 	.= "sq = ".$sqnw;
			exsql($con,"insert el SET ".$velden ,"kopieer");
			$stat	= 'hernum'; break;					
	}			
	if ($stat	= 'hernum') {
		$newsq		= 100;
		$sqvoor 	= '';
		$rsel = getrs($con,"SELECT * FROM el where trid= '".$rwel['trid']."' order by sq","el"); 
		while ($rwel3 = mysqli_fetch_array($rsel)) {
			if (!isset($sqvoor)) { $sqvoor = $rwel3['sq']; $sq	= $newsq; $newsq	= $newsq + 100;} 				// eerste
			elseif ($sqvoor <  $rwel3['sq']) { $sqvoor = $rwel3['sq']; $sq	= $newsq; $newsq	= $newsq + 100;} 	// doornummeren
		 	exsql($con,"update el set sq ='".$sq."' where elky='".$rwel3['elky']."'","hernum");
		}
	}
}	
header("location:"."../index.php?t=deel");
?>
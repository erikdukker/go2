<?	
/* up_deus */
include '../aown/in_lconn.php';
include '../acom/in_func.php';
logarr($con,$_POST,'$_POST');
$vals = array();
foreach ($_POST as $att => $val){
	if (substr($att,0,3) == 'att'){
	    if (isset($val)){
			$vals[$val]	= trim($_POST["val".substr($att,3)]);
		}
	}
}
ksort($vals);
$aktie	= $_POST['aktie'];
$us		= $_POST['us'];
$usky	= $_POST['usky'];
$em		= $_POST['em'];
$vn		= $_POST['vn'];
$an		= $_POST['an'];
$rwus 	= getrw($con,"SELECT * FROM us where usky = '".$usky."'","us"); 
$velden = "us = '".$us."',";
$velden .= "em = '".$em."',";
$velden .= "vn = '".$vn."',";
$velden .= "an = '".$an."',";
$velden .= "uspa = '".totx($vals)."'";
switch ($aktie) {
	case 'c': 	 								// wijzigen 
		exsql($con,"update us set ".$velden." where usky = '".$usky."'","wijzig");
		break;	
	case 'n': 	 								// nieuw
		$rsus 		= getrs($con,"select * from us where dl <> 'x' and usky= '".$usky."' ","us"); 
		if ( mysqli_num_rows($rsus) != 0) {
			$stat	= 'error';
		} else {
			exsql($con,"insert us set ".$velden,'nieuw 1');
			exsql($con,"insert el set usky= '".$usky."',elky= '100', ti = 'nieuwe usansaktie'",'nieuw 2');
			$stat	= 'klaar';
		}
		 break;				
	case 'd': 								// verwijder 		
		exsql($con,"update us set dl = 'x' where usky='".$rwus['usky']."'","verwijder"); 
		}	
header("location:"."../index.php?t=deus");
?>

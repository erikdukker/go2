<?	
/* up_dets */
include '../acom/in_func.php';
include '../aown/in_lconn.php';

$vals = array();
foreach ($_POST as $att => $val){
	if (substr($att,0,3) == 'att'){
	    if ($val != ''){
			$vals[$val]	= trim($_POST["val".substr($att,3)]);
		}
	}
}
ksort($vals);
$tspa	= totx($vals);
switch ($_POST['aktie']) {
	case 'w': 				// verander 
		exsql($con,"update ts set ti = '".$_POST['ti']."', tx = '".$_POST['tx']."', tspa = '".$tspa."'  where tsky= '".$_POST['tsky']."'","wijzig");
		break;	
	case 'v': 				// verwijder 
		exsql($con,"delete from ts where tsky= '".$_POST['tsky']."'","verwijder");
		break;	
	case 'k': 				// kopieren  
		$rsts 	= mysqli_query("select * from ts where id = '".$_POST['id']."'");
		if ($rowts 	= mysqli_fetch_array($rsts)) {
			exsql($con,"update ts set ti = '".$_POST['ti']."', tx = '".$_POST['tx']."', tspa = '".$tspa."'  where tsky= '".$rowts['tsky']."'","wijzig");
		} else {
			exsql($con,"insert ts set id = '".trim($_POST['id'])."', ti = '".$_POST['ti']."', tx = '".$_POST['tx']."', tspa = '".$tspa."'","invoegen");
		}
		$_SESSION['id']	= trim($_POST['id']);
		break;	
}	
header("location:".$abpath."/index.php?t=dets");
?>


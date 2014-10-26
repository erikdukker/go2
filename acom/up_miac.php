<?	
include '../aown/in_lconn.php';
include '../acom/in_func.php';
logmod($con,'up_miac');
logarr($con,$_POST,'$_POST');
$vals = array();
foreach ($_POST as $att => $val){
	if (substr($att,0,3) == 'att'){
	    if ($val != ''){
			$vals[$val]	= trim($_POST["val".substr($att,3)]);
		}
	}
}
ksort($vals);
$aktie	= $_POST['aktie'];
$usky	= $_POST['usky'];
$vn		= $_POST['vn'];
$an		= $_POST['an'];
$rwus 	= getrw($con,"SELECT * FROM us where usky = '".$usky."'","us"); 
$uspa	= toar($rwus['uspa']);
$uspa['usea']	= $_POST['usea'];
$velden .= "vn = '".$vn."',";
$velden .= "an = '".$an."',";
$velden .= "uspa = '".totx($uspa)."'";		// wijzigen 
exsql($con,"update us set ".$velden." where usky = '".$usky."'","wijzig");
header("location:"."../index.php?t=miac");
?>
<!--   -->


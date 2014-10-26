<?	
include '../aown/in_lconn.php';
include '../acom/in_func.php';
logmod($con,'up_prof');
logarr($con,$_POST,'Post');
$vals = array();
foreach ($_POST as $att => $val){
	if (isset($val)){
		$key	= 't'.$val;
		if (substr($att,0,4) == 'trid'){ $vals[$key]				= trim($_POST["aut".substr($att,4)]);}		
		$key	= 'o'.$val;
		if (substr($att,0,2) == 'ao' and $val != ''){ $vals[$key]	= trim($_POST["au".substr($att,2)]); }
	}
}
ksort($vals);
$obpa	= totx($vals);
switch ($_POST['aktie']) {
	case 'w': 				// verander 
		exsql($con,"update ob set obds = '".$_POST['obds']."', obpa = '".$obpa."'  where obky= '".$_POST['obky']."'","update ob 1");
		break;	
	case 'v': 				// verwijder 
		exsql($con,"delete from ob where obky = '".$_POST['obky']."'","verwijder ob"); 
		break;	
	case 'k': 				// kopieren   
		if ($rwob 	= getrw($con,"select * from ob where obid = '".$_POST['obid']."'","ob")) {
			exsql($con,"update ob set obtp = 'usau', obds = '".$_POST['obds']."', obpa = '".$obpa."'  where obky= '".$rwob['obky']."'","update ob 3	");
		} else {
			exsql($con,"insert ob set obtp = 'usau', obds = '".$_POST['obds']."', obid = '".trim($_POST['obid'])."', obpa = '".$obpa."'","insert ob");
		}
		$_SESSION['obid']	= trim($_POST['obid']);
		break;	
}	
echo "<script>window.location.replace(document.referrer)</script>"
?>


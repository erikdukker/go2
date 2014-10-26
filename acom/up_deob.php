<?	
include '../aown/in_lconn.php';
include '../acom/in_func.php';
logmod($con,'up_deob'); 
$vals = array();
foreach ($_POST as $att => $val){
	if (substr($att,0,3) == 'att'){
	//	echo $att."!".substr($att,3)."!".$val.$_POST["val".substr($att,3)]."!"."<br>";
	    if ($val != ''){
			$vals[$val]	= trim($_POST["val".substr($att,3)]);
		}
	}
}
ksort($vals);
//tarr($vals,'vals');
$obpa	= totx($vals);
switch ($_POST['aktie']) {
	case 'w': 				// verander 
		exsql($con,"update ob set obds = '".$_POST['obds']."', obpa = '".$obpa."'  where obky= '".$_POST['obky']."'","update ob");
		break;	
	case 'v': 				// verwijder 
		exsql($con,"delete from ob where obky = '".$_POST['obky']."'","verwijder"); 
		break;	
	case 'k': 				// kopieren   
		if ($rwob 	= getrw($con,"select * from ob where obid = '".$_POST['obid']."'","ob")) {
			exsql($con,"update ob set obds = '".$_POST['obds']."', obpa = '".$obpa."'  where obky= '".$rwob['obky']."'","update ob");
		} else {
			exsql($con,"insert ob set obtp = '".$_POST['obtp']."', obid = '".trim($_POST['obid'])."', obds = '".$_POST['obds']."', obpa = '".$obpa."'","insert ob");
		}
		$_SESSION['obid']	= trim($_POST['obid']);
		$_SESSION['obtp']	= $_POST['obtp'];
		break;	
}	
echo "<script>window.location.replace(document.referrer) </script> ";


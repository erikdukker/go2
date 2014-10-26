<?	
include '../aown/in_lconn.php';
include '../acom/in_func.php';
logmod($con,'up_detr');
$trid		= san($con,$_POST['trid']);
$ds			= san($con,$_POST['ds']);
$meid		= $_POST['meid'];
$aktie		= $_POST['aktie'];
$trky		= $_POST['trky'];
$rwtr 		= getrw($con,"SELECT * FROM tr where trky = '".$trky."'","tr"); 
$naar 		= '';
switch ($aktie) {
	case 'c': 	 							// wijzigen 
		exsql($con,"update tr set ds = '".$ds."',meid = '".$meid."' where trky = '".$trky."'","wijzig");
		break;	
	case 'k': 								// kopieer
		exsql($con,"insert tr set trid = '".$trid."',ds = '".$ds."',meid = '".$meid."'","kopieer");
		exsql($con,"create temporary table temp_table as select * from el where trid='".$rwtr['trid']."' ","kopieer"); 
		exsql($con,"update temp_table set trid='".$trid."'","kopieer");
		exsql($con,"update temp_table set elky='' ","kopieer");
		exsql($con,"insert into el select * from temp_table","kopieer");
		exsql($con,"drop temporary table temp_table","kopieer");
		$naar = '&detr='.$trid;
		break;	
	case 'd': 								// verwijder 		
		$rsme 	= getrs($con,"select * from me where trid = '".$rwtr['trid']."'","me"); 
		if ( mysqli_num_rows($rsme) > 0) {
			val('transaktie zit nog in menu');
		} else {
			exsql($con,"delete from tr where trid='".$rwtr['trid']."'","verwijder"); 
			exsql($con,"delete from el where trid='".$rwtr['trid']."'","verwijder"); 
		}		
}	
header("location:"."../index.php?t=detr".$naar);
?>

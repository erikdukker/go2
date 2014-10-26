<?	
include '../aown/in_lconn.php';
include '../acom/in_func.php';
$bood	= 'leuk';
$aktie	= $_POST['aktie']; 
logarr($con,$_POST,'alle waarden');
$_SESSION['acco']	='test';

$rsco			= getrs($con,"SELECT * FROM ts where co = 'test' and tp = 'co'","co"); 
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
		exsql($con,"insert ts set co = 'test', tp = 'co'".
			", sr = '".$kyEl[0]."', br = '".$kyEl[1]."', vr = '".$kyEl[2]."'","nieuw");	
	}
	}
header("location:"."../index.php?t=smaa");
?>

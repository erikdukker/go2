<?
logmod($con,'pi_ttdl.php delete totalen');
//logval($con,memory_get_usage(),"aan het begin");
$rsco			= getrs($con	,"SELECT * FROM ts where co = '".$_SESSION['acco']."' and tp = 'co'","co"); 
while ($rwco 	= mysqli_fetch_array($rsco)){
	if ($rwtt = getrw($con,"SELECT * FROM tt where sr = '".$rwco['sr']."' and br = '".$rwco['br']."' and vr ='".$rwco['vr'].
							"' and ssid = '".$_SESSION['gossid']."'","tt")){
		exsql($con,"delete from tt where ttky = '".$rwtt['ttky']."'","ttdel"); 
	}
}
?>
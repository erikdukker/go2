<?	
/* up_smtn som tonen*/
include 'in_func.php';
include '../aown/in_lconn.php';
logarr($con,$_POST,"post");
$gossid			= $_SESSION['gossid'];
foreach ($_POST as $vld => $val) { 
	if (substr($vld,0,2) == 'k|') {
		$ken	= $val;
		$kens	= str_getcsv($ken,'|');
		$sr		= $kens[0];
		$br		= $kens[1];
		$vr		= $kens[2];
		$pastx	= str_getcsv($_POST['p|'.substr($vld,2)],';');
		$somtx	= str_getcsv($_POST['s|'.substr($vld,2)],';');
		foreach ($pastx as $par1 ) { $par2 = str_getcsv($par1,'=');if (isset($par2[1])){$par[$par2[0]] = $par2[1];}}
		foreach ($somtx as $som1 ) { $som2 = str_getcsv($som1,'=');if (isset($som2[1])){$som[$som2[0]] = $som2[1];}}
		$ok		= $som['ok'];
		//val('ok '.$ok);
		if ($ok == 'a') { $oktl = 1;$noktl = 0;} else { $oktl = 0;$noktl = 1;} 
		$_SESSION['ver'] = substr($oktl.$_SESSION['ver'],0,50);	
		$sel = array("af", "op", "vm", "dl", "ma", "br");
		if (in_array($sr,$sel)){
			$uq	= str_replace(' ', '', $som['tn']);
		}
		// detail
		exsql($con,"insert dt SET ken = '".$ken."', uq = '".$uq."', ssid = '".$gossid."', ok = '".$ok."', 
					som = '".totx($som)."', par = '".totx($par)."'","detail");
		// totaal					
		if ($rwtt	= getrw($con,"SELECT * FROM tt where sr = '".$sr."' and br = '".$br."' and vr ='".$vr."' and ssid = '".$gossid."'","ts" )){
			$oktl	= $oktl 	+ $rwtt['oktl'];
			$noktl	= $noktl 	+ $rwtt['noktl'];
			$sp		= $ok.$rwtt['sp'];	
			exsql($con,"update tt SET oktl = '".$oktl."', noktl = '".$noktl."', sp = '".$sp."' where  ttky = '".$rwtt['ttky']."'","upd totaal");
		} else {
			exsql($con,"insert tt SET oktl = '".$oktl."', noktl = '".$noktl."', sp = '".$ok."', sr = '".$sr."', br = '".$br."', vr ='".$vr."', ssid = '".$gossid."'","nieuw totaal");
		}
		// vorige 
		if (substr($vld,2) == $_POST['ansom']){
			$_SESSION['voKen']	= $ken;
			$_SESSION['voPas']	= totx($par);
			$_SESSION['voSom']	= totx($som);
		}
	}
}
logarr($con,$_SESSION,"SESSION");
echo "<script>document.location.href ='../index.php?t=smaa'</script>";
//header("location:".$abpath."/index.php?t=smaa");
?>
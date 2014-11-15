<? 
logmod($con,'in_smem.php ');
if (isset($evq)) {
	$ev 		= str_getcsv($evq,';');	
	$rwev 		= getrw($con,"SELECT * FROM ev where evid = '".$ev[0]."'","ev");
	$evpa		= toar($rwev['evpa']);
	unset($ev[0]);
	$evq		= '';
	if (isset($ev)) {
		foreach ($ev as $evp) { if ($evp != '') {$evq = $evq.';'.$evp;}}
	}
	$come = $rwev['tx'];
	if (isset($evpa['vv'])) {
		if (isset($evq)) {
			$evq 	= $evpa['vv'].';'.$evq;
		} else {
			$evq 	= $evpa['vv'];
		}	
	}	
	$_SESSION['evq'] = $evq;
} else {
	$come = "..";
}

echo "<tr><td><table><tr><td class='lab'>".PHP_EOL;
echo "<a href='reg/login.php' style='font-size:15px;color:#2828BD;text-decoration: none;'>advies</a></td><td class=come>".$come.PHP_EOL;
echo "</td></tr></table></td></tr>".PHP_EOL;
?>

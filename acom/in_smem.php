<? 
logmod($con,'in_smem.php Emma');
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
echo "<tr><td>".PHP_EOL;
echo "<table><tr><td>".PHP_EOL;
echo "<img id=coach2 border='0' src='zimg/coach2.png' style='margin-right:5px; float:left' width='93' height='125' alt='coach Emma' >";
echo "<p><a href='reg/login.php' style='text-decoration: none;'>Coach Emma</a></p></td>".PHP_EOL;
echo "<td class=come>".$come."</td>".PHP_EOL;
echo "</tr></table>".PHP_EOL;
echo "</td></tr>".PHP_EOL;
?>

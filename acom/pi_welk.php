<?	
/* pi_welk */
if (!isset($autr)) { 
	$rwus	= getrw($con,"SELECT * FROM us where em = '".$_SESSION['em']."'","us"); 
	$uspa	= toar($rwus['uspa']);
	$uspa['usau']	= 'norm';
	exsql($con,"update us set uspa = '".totx($uspa)."' where usky = '".$rwus['usky']."'","wijzig");
	echo "<script>window.location='index.php?t=welk' </script>".PHP_EOL;
}
?>
<?
if (!isset$_GET['ts'])){
	$_SESSION['id']	= $_GET['ts'];
}
echo "<script src='acom/gosm.js' type='text/javascript'></script> ".PHP_EOL;
echo "<div class='lf'>".PHP_EOL;
$id	= $_SESSION['id'];
$rwts 	= getrw($con,"SELECT * FROM ts where id = '".$id."'","test"); 
$tspa	= toar($rwts["tspa"]);
echo "<div class = 'ti'><h2>".$rwts['ti']."</h2></div>".PHP_EOL;
echo "<div class = 'tx'>".$rwts['tx']."</div>".PHP_EOL;	
include 'acom/in_tsbs.php';
include 'acom/in_tsfl.php';
include 'acom/in_tsma.php';
include 'acom/in_tssh.php';
include 'acom/in_tskn.php';
echo "</div>".PHP_EOL;
echo "<div class='rg'>".PHP_EOL;
include 'acom/in_coac.php';
echo "</div>".PHP_EOL;
?>	
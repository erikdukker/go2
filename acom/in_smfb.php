<? 
// in_smfb.php som feedback
$i = 1; 
echo "<div>".PHP_EOL;	
echo "<img id=coach2 border='0' src='zimg/coach2.png' style='margin-right:5px; float:left' width='93' height='125' alt='coach Emma' >";
echo "<p>Coach Emma: </p><p>Ik hou je scores bij en geef je advies</p>".PHP_EOL;
echo "</div>".PHP_EOL;	
echo "<table><tr></tr><tr><td><img id='iok' border='0' src='zimg/ok17.png' alt='OK' ></td><td id='txok'></td></tr>".PHP_EOL;	
echo "<tr><td><img id='ino' border='0' src='zimg/no17.png' alt='OK' ></td><td id='txno'></td></tr></table>".PHP_EOL;
echo "<table><tr><td><div id='brok' class='br' style='background:#41fa10 ;width:0px;'></div>".PHP_EOL;
echo "<div id='brno' class='br' style='background:#fd0404 ;width:0px;'></div></td></tr></table>".PHP_EOL;
echo "<table><tr><td id='tt'></td></tr></table>".PHP_EOL;
echo "<div class ='fll'>".PHP_EOL;	
echo "<div id='kr' class = 'tx' style='color:red;font-size:200%; '></div>".PHP_EOL;
echo "<div id='ad' class = 'tx' ></div>".PHP_EOL;
echo "</div>".PHP_EOL;
echo "<div class ='fll'><table><tr><td>".	PHP_EOL;
//if ($us == 'anon') {
	echo "<a href='reg/login.php' class='button cust4'>Aanmelden ?</a>";
	echo "<table><tr><td>Bewaar scores en beter advies!</td></tr></table>".PHP_EOL;
//}
echo "</td></tr></table></div>".PHP_EOL;
$tsok	= $_SESSION['tsok'];
$tsno	= $_SESSION['tsno'];
$cofs	= $_SESSION['cofs'];
if (!isset($tsok)) {$tsok = 0;}
if (!isset($tsno)) {$tsno = 0;}
if (!isset($cofs)) {$cofs = 0;}
echo "<script> setwrd(".$tsok.",".$tsno.",'".$cofs."') </script>";
?>
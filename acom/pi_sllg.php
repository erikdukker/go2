 <?
echo "<!-- pi_sllg selecteer logs --> ".PHP_EOL;
$rsss		= $_GET['rsss'];
if(!isset($rsss)) { 
	unset ($_SESSION['sstv'],$_SESSION['ssus']);
	unset ($_SESSION['gossid']);
}
$sstv		= $_GET['sstv'];
if(!isset($sstv)) { 
	$sstv	= $_SESSION['sstv'];
} elseif ($sstv	!= $_SESSION['sstv']) {
	unset($_SESSION['ssus']);
	$_SESSION['sstv']	= $sstv;
}
$ssus		= $_GET['ssus'];
if(isser($ssus)) { 
	$ssus	= $_SESSION['ssus'];
} else {
	$_SESSION['ssus'] = $ssus;
}
$gossid		= $_GET['gossid'];
if(ISSET($gossid)) { 
	$gossid	= $_SESSION['gossid'];
} else {
	$_SESSION['gossid'] = $gossid;
}	
echo "<h2><br></h2>".PHP_EOL;
echo "<form action='index.php?t=sllg' name='sllg' id='sllg' method='post' >".PHP_EOL;
echo "<table>".PHP_EOL;
echo "<tr><th class='hg'>Wanneer</th>".PHP_EOL;
if (isset($sstv)n and $rwus['us'] == 'edk'){
	echo "<th class='hg'>naam</th>".PHP_EOL;
}
echo "<tr><td><select  size= 5 id='sstv2' onclick='vvtr(\"sllg\",\"sstv2\",\"sstv\")'>".PHP_EOL;
	if ($sstv == 'hs'){ $sel = "selected";} else { $sel ='';};
	echo "<option $sel value='hs'>Huidige sessie</option>".PHP_EOL;
	if ($sstv == 'vd'){ $sel = "selected";} else { $sel ='';};
	echo "<option $sel value='vd'>Vandaag</option>".PHP_EOL;
	if ($sstv == 'gi'){ $sel = "selected";} else { $sel ='';};
	echo "<option $sel value='gi'>Gisteren</option>".PHP_EOL;
	if ($sstv == 'dw'){ $sel = "selected";} else { $sel ='';};
	echo "<option $sel value='dw'>Tot week terug</option>".PHP_EOL;
	if ($sstv == 'dm'){ $sel = "selected";} else { $sel ='';};
	echo "<option $sel value='dm'>Tot maand terug</option>".PHP_EOL;
echo "</select> ".PHP_EOL;	
echo "</td>".PHP_EOL;
if ($sstv != '' ){
	switch ($sstv) {
    case "vd":
		$gt = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        break;
    case "gi":
		$gt = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
        break;
    case "dw":
		$gt = mktime(0, 0, 0, date("m"), date("d")-7, date("Y"));
        break;
    case "dm":
		$gt = mktime(0, 0, 0, date("m")-1, date("d"), date("Y"));
        break;
	}
//	val($gt);
	logval($con,$sstv,'$sstv');
}
if ($rwus['us'] == 'edk'){
	echo "<td><select  size= 5 id='ssus2' onclick='vvtr(\"sllg\",\"ssus2\",\"ssus\")'>".PHP_EOL;
	$rsus2 = getrs($con,"select * from us where usky in (select usky from ss where ssst >= '".$gt."') order by vn","us");
	while ($rwus2 = mysqli_fetch_array($rsus2)){
		echo "<option ";
		if($rwus2['usky'] ==  $ssus) { echo "selected ";}
		echo "value='".trim($rwus2['usky'])."'>".$rwus2['vn']." ".$rwus2['an']."</option>".PHP_EOL;
	}
	echo "</select> ".PHP_EOL;	
	echo "</td>".PHP_EOL;
}

echo "<td class='tp'><input class='button cust1' id='rsss' type='text' value='selekteer opnieuw' onclick='vvtr(\"sllg\",\"rsss\",\"rsss\" )'/> ".PHP_EOL;
echo "<a href='".$home."' class='button cust1'>terug</a>".PHP_EOL;
echo "</td></tr></table>".PHP_EOL;
echo "</form>".PHP_EOL;
    if ($ssus != '') {
		$sssel 	= " and usky = '".$ssus."'";
	} elseif ( $us == 'anon') {
		$sssel 	= " and gossid = '".$_SESSION['gossid']."'";
	} else {
		$sssel 	= " and usky = '".$us."'";
	}
	if ($gossid == 'all') {
		$sssel 	= "";
	} elseif ($gossid == '') {
		$sssel 	= "";
	} else {
		$sssel 	= " and id like '".$gossid."|%' ";
	}
	$rsco 	= getrs($con,"select * from co ","co"); 
	while ($rwco = mysqli_fetch_array($rsco)){
		$co[$rwco['coid']] = $rwco['coti'];
	}
	echo "<br><br><table>".PHP_EOL;
	
	$rsss	= mysqli_query($con,"select * from ss where ssst >= '".$gt."' ".$sssel." order by ssst desc"); 
	while ($rwss = mysqli_fetch_array($rsss)){
		echo "<tr><td>Oefenen van ".date("Y/m/d G.i:s", $rwss['ssst'])." tot ".date("G.i:s", $rwss['ssls'])."</td> </tr>".PHP_EOL;

	}
	echo "</table>".PHP_EOL;
	*/
	echo "<br><br><table>".PHP_EOL;
	echo "sessie: ".$_SESSION['gossid'];
	$rslg	= mysqli_query($con,"select * from lg where gossid = '".$_SESSION['gossid']."' order by lgky desc"); 
	while ($rwlg = mysqli_fetch_array($rslg)){
		echo "<tr><td style='width:140px'>".$rwlg['ts']."</td><td>". $rwlg['tx']."</td> </tr>".PHP_EOL;
	}
	echo "</table>".PHP_EOL;
	


?>
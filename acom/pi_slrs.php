 <?
echo "<!-- pi_slrs selecteer resultaat --> ".PHP_EOL;
//$_SESSION['log'] = "pi_slrs";
//logarr($con,$_POST,'post');
//logarr($con,$_SESSION,'session');
$rsrs		= $_GET['rsrs'];
if(isset($rsrs)) { 
	unset($_SESSION['srtv'],$_SESSION['srus'],$_SESSION['srid']);	
}
$srtv		= $_GET['srtv'];
if(isset($srtv)) { 
	$srtv	= $_SESSION['srtv'];
} elseif ($srtv	!= $_SESSION['srtv']) {
	unset($_SESSION['srus']);
	$_SESSION['srtv']	= $srtv;
}
$srus		= $_GET['srus'];
if(!isset($srus)) { 
	$srus	= $_SESSION['srus'];
} else {
	$_SESSION['srus'] = $srus;
}
$srid		= $_GET['srid'];
if(!isset($srid)) { 
	$srid	= $_SESSION['srid'];
} else {
	$_SESSION['srid'] = $srid;
}	
echo "<h2><br></h2>".PHP_EOL;
echo "<form action='index.php?t=slrs' name='slrs' id='slrs' method='post' >".PHP_EOL;
echo "<table>".PHP_EOL;
echo "<tr><th class='hg'>Wanneer</th>".PHP_EOL;
if (isset($srtv) and $rwus['us'] == 'edk'){
	echo "<th class='hg'>naam</th>".PHP_EOL;
}
if (isset($srtv)){
	echo "<th class='hg'>Soort oefening</th>".PHP_EOL;
}
echo "<tr><td><select  size= 5 id='srtv2' onclick='vvtr(\"slrs\",\"srtv2\",\"srtv\")'>".PHP_EOL;
	if ($srtv == 'vd'){ $sel = "selected";} else { $sel ='';};
	echo "<option $sel value='vd'>Vandaag</option>".PHP_EOL;
	if ($srtv == 'gi'){ $sel = "selected";} else { $sel ='';};
	echo "<option $sel value='gi'>Gisteren</option>".PHP_EOL;
	if ($srtv == 'dw'){ $sel = "selected";} else { $sel ='';};
	echo "<option $sel value='dw'>Tot week terug</option>".PHP_EOL;
	if ($srtv == 'dm'){ $sel = "selected";} else { $sel ='';};
	echo "<option $sel value='dm'>Tot maand terug</option>".PHP_EOL;
echo "</select> ".PHP_EOL;	
echo "</td>".PHP_EOL;
if ($srtv != '' ){
	switch ($srtv) {
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
	logval($con,$srtv,'$srtv');
}
if ($rwus['us'] == 'edk'){
	echo "<td><select  size= 5 id='srus2' onclick='vvtr(\"slrs\",\"srus2\",\"srus\")'>".PHP_EOL;
	$rsus2 = getrs($con,"select * from us where usky in (select usky from ss where ssst >= '".$gt."') order by vn","us");
	while ($rwus2 = mysqli_fetch_array($rsus2)){
		echo "<option ";
		if($rwus2['usky'] ==  $srus) { echo "selected ";}
		echo "value='".trim($rwus2['usky'])."'>".$rwus2['vn']." ".$rwus['an']."</option>".PHP_EOL;
	}
	echo "</select> ".PHP_EOL;	
	echo "</td>".PHP_EOL;
}
if (isset($srtv)){
	echo "<td><select  size= 5 id='srid2' onclick='vvtr(\"slrs\",\"srid2\",\"srid\")'>".PHP_EOL;
	echo "<option ";
	if ("all" ==  $srid) { echo "selected ";}
	echo "value='all' >alles</option>".PHP_EOL;
	$rsco 	= getrs($con,"select * from co where cotp = 'tp' order by cosq ","coid"); 
	while ($rwco = mysqli_fetch_array($rsco)){
		echo "<option ";
		if($rwco['coid'] ==  $srid) { echo "selected ";}
		echo "value='".trim($rwco['coid'])."'>".$rwco['coti']."</option>".PHP_EOL;
	}
	echo "</select> ".PHP_EOL;	
echo "</td>".PHP_EOL;
}
echo "<td class='tp'>".PHP_EOL;
if (isset($srid)){
	echo "<input class='button cust3' name='selres' type='submit' value='laat resultaten zien' /><br>".PHP_EOL;
}
echo "<td class='tp'><input class='button cust1' id='rsrs' type='text' value='selekteer opnieuw' onclick='vvtr(\"slrs\",\"rsrs\",\"rsrs\" )'/> ".PHP_EOL;
echo "<a href='".$home."' class='button cust1'>terug</a>".PHP_EOL;
echo "</td></tr></table>".PHP_EOL;
echo "</form>".PHP_EOL;
if ($_POST['selres'] != '') {
    if ($srus != '') {
		$sssel 	= " and usky = '".$srus."'";
	} elseif ( $us == 'anon') {
		$sssel 	= " and gossid = '".$_SESSION['gossid']."'";
	} else {
		$sssel 	= " and usky = '".$us."'";
	}
	if ($srid == 'all') {
		$srsel 	= "";
	} elseif ($srid == '') {
		$srsel 	= "";
	} else {
		$srsel 	= " and id like '".$srid."|%' ";
	}
	$rsco 	= getrs($con,"select * from co ","co"); 
	while ($rwco = mysqli_fetch_array($rsco)){
		$co[$rwco['coid']] = $rwco['coti'];
	}

	$rsss 	= getrs($con,"select * from ss where ssst >= '".$gt."' ".$sssel." order by ssst desc","ss"); 
	while ($rwss = mysqli_fetch_array($rsss)){
		echo "<br><br><table>".PHP_EOL;
		echo "<tr><th><h2>Oefenen van ".date("Y/m/d G.i:s", $rwss['ssst'])." tot ".date("G.i:s", $rwss['ssls'])."</h2></td> </tr>".PHP_EOL;
		echo "</table>".PHP_EOL;
		$rsrs 	= getrs($con,"select * from rs where gossid = '".$rwss['gossid']."'".$srsel,"ss"); 
		if (mysqli_num_rows($rsrs) != 0) { 
			while ($rwrs = mysqli_fetch_array($rsrs)){
				$tot[$rwrs['id']]++;
				if ( $rwrs['ok'] == 'o'){
					$tok[$rwrs['id']]++;
				} else {
					$tno[$rwrs['id']]++;
				}
				$ttm[$rwrs['id']] = $ttm[$rwrs['id']] + $rwrs['rt'];
			}
			echo "<br><table>".PHP_EOL;
			echo "<tr><th><h3>soort</h3></th><th><h3>getallen</h3></th><th><h3>vorm</h3></th>".PHP_EOL;
			echo "<th><img id='iok' border='0' src='zimg/ok17.png' alt='OK' ></h3></th>".PHP_EOL;
			echo "<th><img id='ino' border='0' src='zimg/no17.png' alt='NO' ></th><th><h3>verhouding</h3></th><th><h3>sec/ant</h3></th></tr>".PHP_EOL;
			foreach ($tot as $id => $aant) {
			//	val($del);
				$idar 	= str_getcsv ($id,$del);
				$brok		= $tok[$id] * 100 / $tot[$id];
				$brno		= $tno[$id] * 100 / $tot[$id];
				$gemtd		= round(($ttm[$id]/$tot[$id])/1000, 2);
				echo "<tr><td>".$co[$idar[0]]."</td><td>".$co[$idar[1]]."</td><td>".$co[$idar[2]]."</td><td>".$tok[$id]."</td><td>".$tno[$id]."</td>".PHP_EOL;
				echo "<td><div id='brok' class='br' style='background:#41fa10 ;width:".$brok."px;'></div>".PHP_EOL;
				echo "<div id='brno' class='br' style='background:#fd0404 ;width:".$brno."px;'></div></td>".PHP_EOL;
				echo "<td>".$gemtd."</td> </tr>".PHP_EOL;
			}
		}
		echo "</table>".PHP_EOL;
		unset($tot,$tok,$tno,$ttm);
	}
}

?>
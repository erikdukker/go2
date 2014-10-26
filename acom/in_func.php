<?//php functions
$del 				= '|';
$_SESSION['lgid'] 	= 'leeg'; // wordt in lconn gevuld
function totx($in) 		{ return serialize($in); }
function toar($in) 		{ return unserialize($in); }
function san($con,$in) 	{ return mysqli_real_escape_string($con,$in); }	// sanitize voor sql
function org($in) 		{ return stripslashes($in); }				// en weer terug
function val($var) 		{ echo "<script> alert('voor>".$var."<na'); </script>"; }
function logmod($con,$module) {
	if (isset($_SESSION['lg'] )){
//		echo "<!-- ".$module." --> ".PHP_EOL; // werk niet in update modules
		$tx	= ">>>>>> ".$module;
		$dm	= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'" );
	}
}
function logval($con,$var,$label) {
	if (isset($_SESSION['lg'] )){
		$tx	= $label.": ".$var.PHP_EOL;
		$dm	= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'" );
	}
}
function logarr($con,$var,$label) {
	if (isset($_SESSION['lg'] )){
		$rw='';
		if (!empty($var)) {
			foreach ($var as $key => $entry) 	{ if(is_array($entry)){ $rw .= $key . ": " . implode(',',$entry) . "<br>"; } else { $rw .=  $key . ": " . $entry . "<br>"; } }
		}
		$tx	= $label.": <br>".$rw.PHP_EOL;
		$dm	= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'" );	
	}
}
function getpar($par,$default='leeg') {
	if(isset($_GET[$par])) 		{ $_SESSION[$par]	= $_GET[$par];}
	if(isset($_SESSION[$par])) 	{ 
		$val				= $_SESSION[$par];
	} elseif ($default != 'leeg') {
		$val = $default;
	} else {
		$val = null;
	}
	return($val);
}
function getrw($con,$sq,$label) {
	if (isset($_SESSION['lg'])){
		$tx			= $label.": ".$sq."<br>";
		$dm	= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'"  );	
		$rs			= mysqli_query($con,$sq);
		if ($sqrw	= mysqli_fetch_array($rs)) {
			$tx		= $label." resultaat: ".mysqli_num_rows($rs)." regels";
			$dm		= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'"  );
		} else {
			$tx		= $label." fout ?: ".mysqli_error($con)." resultaat: niets geselecteerd";
			$dm	= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'"  );
		}
		return $sqrw;
	} else {
		$rs	= mysqli_query($con,$sq);
		return mysqli_fetch_array($rs);
	}
}
function getrs($con,$sq,$label) {
	if (isset($_SESSION['lg'] )){
		$tx		= $label.": ".$sq.PHP_EOL;
		$dm	= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'"  );
		if ($rs	= mysqli_query($con,$sq)) {
			$tx	= $label." resultaat: ".mysqli_num_rows($rs)." regels";
			$dm	= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'" );
		} else {
			$tx	= $label." fout ?: ".mysqli_error($con)." resultaat: niets geselecteerd";
			$dm	= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'" );
		}
		return $rs;
	} else {
		$rs		= mysqli_query($con,$sq);
		return $rs;
	}
}
function exsql($con,$sq,$label) {
	if (isset($_SESSION['lg'] )){
		$tx	= $label.": <br>".$sq.PHP_EOL;
		$dm	= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'" );
		$rs					= mysqli_query($con,$sq);
		$tx	= $label.": <br> fout ?: ".mysqli_error($con); 
		$dm	= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'" );
		$tx	= $label.": <br> gewijzigd: ".mysqli_affected_rows($con)." regels";
		$dm	= mysqli_query($con,"insert lg SET lgid ='".$_SESSION['lgid']."', tx ='".san($con,$tx)."'" );
	} else {
		$rs	= mysqli_query($con,$sq);
	}
}
function inprad($con,$obj,$par,$ss='') {
	echo "<!-- object ".$obj."-->".PHP_EOL;
	$rwob 	= getrw($con,"SELECT * FROM ob where obid = '".$obj."'","ob"); 
	$t		= toar($rwob['obpa']);

	if (isset($t['tx'])) { 	echo "<th>".$t['tx']."</th>".PHP_EOL; }
	if (!isset($par[$obj])) { if (isset($t['df'])) { $par[$obj]=$t['df']; }	 else { echo 'verzorg default van '.$obj;} }
	echo "<td><select size='1' name=$obj".$ss." >".PHP_EOL;

		if ($par[$obj]=='a') {
			echo "<option selected value='a'>aan</option>".PHP_EOL;
			echo "<option value='u'>uit</option>".PHP_EOL;
		} else {
			echo "<option value='a'>aan</option>".PHP_EOL;
			echo "<option selected value='u'>uit</option>".PHP_EOL;
		}
	echo "</select></td>".PHP_EOL;
	
} 
function leescel($objPHPExcel,$x,$y){
	return trim($objPHPExcel->getActiveSheet()->getCell(PHPExcel_Cell::stringFromColumnIndex($x).$y)->getCalculatedValue());
}

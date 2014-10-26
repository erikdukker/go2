<?	
/* algemene kode */
include 'in_func.php';
include '../aown/in_lconn.php'; 
include '../astd/PHPExcel/Classes/PHPExcel.php';
$t		=	time() - 1387818826;
$file	=	'UPSM'.$t.'.xlsx';
logval($con,$file,"filename"	);
$target = "../zfil/".$file; 
if ( move_uploaded_file($_FILES['upsm']['tmp_name'], $target) )  { 
	echo "De sommen in ".basename( $_FILES['upsm']['name'])." zijn geupload.";
} 
else  { 
	echo "Sorry, uw sommen zijn niet geupload.";
}	 
logval($con,'voor','voor');
//$_SESSION['log'] = 'start';
$objPHPExcel = PHPExcel_IOFactory::load($target);
$clpr	= 'niet leeg';
for ($x = 0; $clpr != ''; $x++) { 
	$label[$x] = trim(leescel($objPHPExcel,$x,1)); 
	$clpr = $label[$x];
//	echo "<br>".$clpr ;
}
$xmx	= $x - 1;

$tpweg = leescel($objPHPExcel,0,2);
exsql($con,"delete from sm where smid like '".$tpweg."%'","eerst weg"); 

$nalaatste	= 'nog niet';
for ($y = 2; $nalaatste	==  'nog niet'; $y++) { 
	unset($smid,$smpa);
	if ( strlen(leescel($objPHPExcel,0,$y)) != 0 ) {
		for ($x = 0; $x < $xmx ; $x++) { 
			$waarde	= leescel($objPHPExcel,$x,$y); 
			switch ($label[$x]) {
				case 'tp':
					$smid 	=  $waarde;
					break;
				case 'vs':
					$smid 	.=  '|'.$waarde;
					break;
				default:
				    if (substr($label[$x],0,1) == 't') {
						$smid 	.=  '|'.$waarde;
					} else {
						$smpa[$label[$x]] = $waarde;
					}
			}
		}
		exsql($con,"insert sm SET smid = '".$smid."', smpa = '".totx($smpa)."'","nieuw");
	} else {
		$nalaatste	= 'nu wel';
	}
}
echo $_SESSION['log'];
logval($con,'na','na');
?>


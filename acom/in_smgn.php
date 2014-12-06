<?
logmod($con,'in_smgn.php genereer sommen');
//logval($con,memory_get_usage(),"aan het begin");
unset($oktl,$noktl);
switch ($lv) {
	case 'sn': 	$aantAnt=6;		$dlOk=40; 	break;	//snel oefenen
	case 'vol': $aantAnt=10;	$dlOk=60; 	break;	//voldoende (6)
	case 'goe': $aantAnt=10;	$dlOk=80; 	break;	//goed (8)
	case 'gom': $aantAnt=20;	$dlOk=90; 	break;	//top (9) 
}
// ophalen sr
$rwsr			= getrw($con,"SELECT * FROM ts where tp = 'pa' and id = 'sr'","pa");
$tspa			= toar($rwsr['tspa']);
foreach ( $tspa as $par => $wrd) {
	$pael		= str_getcsv($par,'|'); //splits par
	$srom[$pael[0]] 	= $wrd;
}
logarr($con,$srom,"srom");

$rwco		= getrw($con,"SELECT * FROM ts where id = '".$acco."' and tp = 'co'","co"); 
$tspa		= toar($rwco['tspa']);
foreach ( $tspa as $br => $pas) {
	logarr($con,$pas,"pas");
	$rkel	= str_getcsv($pas['rk'],'|'); //splits co van vrm	
	$sr		= $rkel[0];
	$br		= $pas['br'];
	$vr		= $pas['vr'];
	$ken	= $sr."|".$br."|".$vr;	
	if ($rwtt = getrw($con,"SELECT * FROM tt where sr = '".$sr."' and br = '".$br."' and vr ='".$vr.
							"' and ssid = '".$_SESSION['gossid']."'","tt")){	
		if (!isset($alsom[$ken])) { //totalen 1 keer tellen
			if (isset($oktl['tot'])) {
				$oktl['tot']	= $oktl['tot'] 	+ $rwtt['oktl'];	
				$noktl['tot']	= $noktl['tot']	+ $rwtt['noktl'];	
			} else {
				$oktl['tot']	= $rwtt['oktl'];	
				$noktl['tot']	= $rwtt['noktl'];	
			}	
			if (isset($oktl[$sr])) {
				$oktl[$sr]	= $oktl[$sr] 	+ $rwtt['oktl'];	
				$noktl[$sr]= $noktl[$sr]	+ $rwtt['noktl'];	
			} else {
				$oktl[$sr]	= $rwtt['oktl'];	
				$noktl[$sr]= $rwtt['noktl'];	
			}
			if (isset($oktl[$ken])) {
				$oktl[$ken]	= $oktl[$ken] 	+ $rwtt['oktl'];	
				$noktl[$ken]= $noktl[$ken]	+ $rwtt['noktl'];	
			} else {
				$oktl[$ken]	= $rwtt['oktl'];	
				$noktl[$ken]= $rwtt['noktl'];	
			} 
			$alsom[$ken]	= 'a';
		}
		logarr($con,$oktl,'oktl');
		logarr($con,$noktl,'noktl');
	//	$pcOk[$ken]	= substr_count(substr($rwtt['sp'],0,$aantAnt),'a');
		if (!isset($pas['ao']) or $pas['ao'] == 0) { $pas['ao'] = 10;}
		$tel		= substr_count(substr($rwtt['sp'],0,$pas['ao']),'a');
		$pcOk[$ken]	= (100 * $tel) / $pas['ao'];

		if ($pcOk[$ken] >= $dlOk ) {
	// val($pcOk[$ken].' '.$dlOk );
			$pri[$ken]	= 1;
		} else {
			$tot		= $rwtt['oktl'] + $rwtt['noktl'];	
			$sco		= round(($rwtt['oktl'] *	100 )/ $tot);	
			//$scocor		= -1 * round($tot/20) * ($sco / 5); // goeie score dan vooral doorgaan
			$scocor		= 0; // eerst maar niet
			$sw			= rand(-10,10);
			//$sw			= 0; // eerst maar niet
			$pri[$ken]	= 1000 - (round($pas['wd'] + $scocor + $sw)); // de waardering, voortgang , swing voor variatie
			$prilog		= $ken." | pri: ".$pri[$ken]." wd: ".$pas['wd']." swing: ".$sw." score: ".$sco." score cor: ".$scocor;
			logval($con,$prilog,"prilog");
		}
	} else { 	// geen scores
		$cor		= 0; // eerst maar niet
		$sw			= rand(-10,10);
		$pri[$ken]	= 1000 - (round($pas['wd'] + $cor + $sw)); 
		$prilog		= $ken." | pri: ".$pri[$ken]." wd: ".$pas['wd']." swing: ".$sw." cor: ".$cor;
		logval($con,$prilog,"prilog");
	}
	$tit[$ken]		= $pas['rk'].'|'.$pas['om'].'|'.$pas['vr'];
	$somPas[$ken] 	= $pas;	
}
if (!isset($oktl['tot']) and !isset($noktl['tot'])) {
	if (isset($evq)) {
		$evq 	= 'stgr;'.$evq;
	} else {
		$evq 	= 'stgr;';
	}	
}
arsort($pri);
logarr($con,$somPas,"sompas");
$tlpri			= 0;
foreach ($pri as $ken => $prio) {
	if ($prio != 1 and $tlpri < 5 ) {
		$selSm[$ken] 	= $prio;
		$tlpri++;	
	}
}
if ($tlpri 	!= 0 ) {
	logarr($con,$selSm,"sel");
	for ($smtl 	= 1; $smtl <= 5; $smtl++){
		$welke 	= rand(1,$tlpri);
		logval($con,$welke,"welke");
		$j		= 0;
		unset($ken);
		foreach ($selSm as $tken => $tmp) {
			$j++;
	//		val('j  '.$j);
			if ($j 		== $welke ) {
				$ken 	= $tken;
				//val('ken in de sel'.$ken);
			}
		}
		unset($som);
		$pas	= $somPas[$ken];
		logarr($con,$pas,"pas na somPas ");
		$kens	= str_getcsv($ken,'|');
		$sr		= $kens[0];	
		$br		= $kens[1];
		$vr		= $kens[2];
		$rk		= $pas['rk'];
		$ok		= 'u';
		$tl		= 20;
		while ( $ok		== 'u' and $tl	> 0 ) {
			unset($som,$ants);
			for ($i=1;$i<=4;$i++) {
				if (!isset($pas['t'.$i.'s'])) { 
					if (isset($pas['t'.$i.'b']) and isset($pas['t'.$i.'t'])) {
						$som['t'.$i]		= rand($pas['t'.$i.'b'],$pas['t'.$i.'t']);
					}	
				} else {
					switch ($pas['t'.$i.'s']) {
						case 'b': 	
							$som['t'.$i] 	= rand(1,9) * ( pow(10,rand(1,2)));break;
						case 'c': 	
							$som['t'.$i] 	= rand(1,99999) / ( pow(10,rand(1,4)));break;
						case 'd': 	
							$r1		= rand(1,99).".";
							$r2		= rand($pas['t'.$i.'b'],$pas['t'.$i.'t']) - 1;
							$r1		.= rand(1,9*pow(10,$r2));
							$som['t'.$i] =$r1;
						break;
					}		
				}	
			//	if (isset($som['t'.$i])) {val('t'.$i.' '.$som['t'.$i]);}
	
			}	
			$ok		= 'a';
			switch ($rk) {
				case 'mi|1': 
					$som['rs']	= $som['t1'] - $som['t2']; $som['tn']	= $som['t1']." - ".$som['t2']; // aftrekken
				break;
				case 'pl|1':
					$som['rs']	= $som['t1'] + $som['t2']; $som['tn']	= $som['t1']." + ".$som['t2']; // optellen
					if (isset($som['t3'])) {
						$som['rs']	= $som['rs'] + $som['t3']; $som['tn']	= $som['tn']." + ".$som['t3'];
					}
					if (isset($som['t4'])) {
						$som['rs']	= $som['rs'] + $som['t4']; $som['tn']	= $som['tn']." + ".$som['t4'];
					}
				break;
				case 'pl|2':
					$r2		= rand($pas['t1b'],$pas['t1t']) - 1;
					$r3		= rand(1,99).".".rand(1*pow(10,$r2),9*pow(10,$r2));
					$r4		= rand(1,99).".".rand(1*pow(10,$r2),9*pow(10,$r2));
					$som['t1'] =$r3;
					$som['t2'] =$r4;
					$som['rs'] = $r3 + $r4; $som['tn']	= $som['t1']." + ".$som['t2']; // optellen 2 getallen
				break;
				case 'ke|1': //  vermenigvuldigen
					$som['rs']	= $som['t1'] * $som['t2']; $som['tn']	= $som['t1']." x ".$som['t2']; 
				break;
				case 'ke|2': //	vermeninigvuldigen met decimalen			
					$r1			= $som['t1'] / pow(10,$som['t2']);
					$r2			= pow(10,$som['t3']);
					$som['tn']	= $r1." * ".$r2;
					$som['rs']			= $r1 * $r2;
				break;
				case 'de|1': 
					$r1			= $som['t1'] * $som['t2'];
					$som['tn']	= $r1." / ".$som['t2'];
					$som['rs']	= $som['t1']; 
				break;			
				case 'de|2': 				
					$r1			= $som['t1'] / pow(10,$som['t2']);
					$r2			= pow(10,$som['t3']);
					$som['tn']	= $r1." / ".$r2;
					$som['rs']			= $r1 / $r2;
				break;
				case 'br|1': //   a/b = ?/c
					$rest			= $som['t2'] % $som['t1'];
					if ($rest != 0) {
						//val('t1 '.$som['t1']." t2 ".$som['t2']." t3 ".$som['t3'].' rest '.$rest);
						$r1			= $som['t1'] * $som['t3'];
						$r2			= $som['t2'] * $som['t3'];
						$som['tn']	= $r1."/".$r2;
						for ($j = 7; $j >= 2; $j = $j -1){
							$rest1		= $som['t1'] % $j;
							$rest2		= $som['t2'] % $j;
							if ($rest1 == 0 and $rest2 == 0) {
								//val('j '.$j);
								$som['t1']		= $som['t1'] / $j;
								$som['t2']		= $som['t2'] / $j;
							}						
						}
						$som['rs']			= $som['t1']."/".$som['t2'];
						//val($som['rs']);
						if ($vr == 'mk'){	
							$ants[0]	= $som['rs'];
							$ants[1]	= $r1."/".$som['t2'];
							$ants[2]	= $som['t1']."/".$r2;
							$ants[3]	= ($som['t1']+1)."/".($som['t2']+1);
							$ants[4]	= ($r1-1)."/".($r2-1);
							$r1			= $som['t1'] * ($som['t3']-1);
							$r2			= $som['t2'] * ($som['t3']-1);							
							$ants[5]	= $r1."/".$som['t2'];
							$r1			= $som['t1'] * ($som['t3']+1);
							$r2			= $som['t2'] * ($som['t3']+1);							
							$ants[6]	= $r1."/".$som['t2'];		
							for($i = 0; $i < count($ants); $i++) {
								if ($ants[$i] == $ants[0]) { unset($ants[$i]); }
							}								
						}
					} else {
						$ok		= 'u';
					}
				break;
				case 'br|2': //   a/b = ?/c
					if ($som['t3'] != $som['t4']) {
						for ($j = 7; $j >= 2; $j = $j -1){
							$rest1		= $som['t1'] % $j;
							$rest2		= $som['t2'] % $j;
							if ($rest1 == 0 and $rest2 == 0) {
								$som['t1']		= $som['t1'] / $j;
								$som['t2']		= $som['t2'] / $j;
							}						
						}
						$r1			= $som['t1'] * $som['t3'];
						$r2			= $som['t2'] * $som['t3'];
						$r3			= $som['t1'] * $som['t4'];
						$r4			= $som['t2'] * $som['t4'];
						$som['tn']	= $r1."/".$r2." = ?/".$r4." dan ? ";	
						$som['rs']			= $r3;
						
						if ($vr == 'mk'){	
							$ants[0]	= $som['rs'];
							$ants[1]	= $r1;
							$ants[2]	= $som['rs']+1;
							$ants[3]	= $som['t1'];
							$ants[4]	= $som['t4']*3;						
							$ants[5]	= $som['t3'];
							$ants[6]	= $som['t4']*2;	
							for($i = 0; $i < count($ants); $i++) {
								if ($ants[$i] == $ants[0]) { unset($ants[$i]); }
							}
						}
					} else {
						$ok		= 'u';
					}
				break;
				case 'br|3': //   0,4 = ? / ?
					$rest			= $som['t1'] % $som['t2'];
					if ($rest != 0) {
						$r1			= round($som['t1']/$som['t2'],3);
						$som['tn']	= $r1;
						for ($j = 7; $j >= 2; $j = $j -1){
							$rest1		= $som['t1'] % $j;
							$rest2		= $som['t2'] % $j;
							if ($rest1 == 0 and $rest2 == 0) {
								//val('j '.$j);
								$som['t1']		= $som['t1'] / $j;
								$som['t2']		= $som['t2'] / $j;
							}						
						}
						$som['rs']			= $som['t1']."/".$som['t2'];
						//val($som['rs']);
						if ($vr == 'mk'){	
							$ants[0]	= $som['rs'];
							$ants[1]	= ($som['t1']+1)."/".$som['t2'];
							$ants[2]	= $som['t1']."/".($som['t2']+1);
							$ants[3]	= ($som['t1']+1)."/".($som['t2']+1);
							$ants[4]	= ($som['t1']-1)."/".($som['t2']-1);			
							$ants[5]	= ($som['t1']+2)."/".($som['t2']);					
							$ants[6]	= ($som['t1']+3)."/".($som['t2']+1);
							for($i = 0; $i < count($ants); $i++) {
								if ($ants[$i] == $ants[0]) { unset($ants[$i]); }
							}
						}
					} else {
						$ok		= 'u';
					}
				break;
				case 'br|4': //   a / b = c d / a
					if ($som['t1'] > $som['t2']) {
						$r1			= $som['t1'] % $som['t2'];
						if ($r1 == 0) {
							$ok		= 'u';
						}
						$r2			= $som['t1'] - $r1;
						$r3			= $r2 / $som['t2']; // hele
						$som['tn']	= $som['t1'].'/'.$som['t2'];
						$som['rs']			= $r3.' '.$r1.'/'.$som['t2'];
						//val($som['rs']);
						if ($vr == 'mk'){	
							$ants[0]	= $som['rs'];
							$ants[1]	= $r3.' '.$r1.'/'.$som['t2'];
							$ants[2]	= $r3.' '.$r2.'/'.$som['t2'];
							$ants[3]	= ($r3-1).' '.$r1.'/'.$som['t2'];
							$ants[4]	= ($r3+1).' '.$r1.'/'.$som['t2'];
							$ants[5]	= $r3.' '.($r1+1).'/'.$som['t2'];				
							$ants[6]	= ($r3+1).' '.($r1+1).'/'.$som['t2'];			
							for($i = 0; $i < count($ants); $i++) {
								if ($ants[$i] == $ants[0]) { unset($ants[$i]); }
							}
						}
					} else {
						$ok		= 'u';
					}
				break;	
				case 'br|5': //   c a / b =  d / b
					if ($som['t1'] != $som['t2']) {
						$r1			= $som['t3'] * $som['t2'] + $som['t1'];							
						$som['tn']	= $som['t3'].' '.$som['t1'].'/'.$som['t2'];
						$som['rs']			= $r1.'/'.$som['t2'];
						//val($som['rs']);
						if ($vr == 'mk'){	
							$ants[0]	= $som['rs'];
							$ants[1]	= $som['t1'].'/'.$som['t2'];
							$ants[2]	= $r1.'/'.($som['t2']+1);
							$ants[3]	= ($r1-1).'/'.$som['t2'];
							$ants[4]	= ($r1+1).'/'.$som['t2'];
							$ants[5]	= ($som['t1']+1).'/'.$som['t2'];				
							$ants[6]	= ($som['t1']+5).'/'.$som['t2'];	
							for($i = 0; $i < count($ants); $i++) {
								if ($ants[$i] == $ants[0]) { unset($ants[$i]); }
							}
						}
					} else {
					
						$ok		= 'u';
					}
				break;
				case 'br|6': //   a / c en b /d
					if ($som['t1'] != $som['t3'] and $som['t2'] != $som['t4']) {
						$r1			= $som['t1'] * $som['t4'];							
						$r2			= $som['t2'] * $som['t3'];							
						$r3			= $som['t3'] * $som['t4'];	
						for ($j = 7; $j >= 2; $j = $j -1){
							$rest1		= $r1 % $j;
							$rest2		= $r2 % $j;
							$rest3		= $r3 % $j;
							if ($rest1 == 0 and $rest2 == 0 and $rest3 == 0) {
								//val('j '.$j);
								$r1		= $r1 / $j;
								$r2		= $r2 / $j;
								$r3		= $r3 / $j;
							}						
						}
						$som['tn']	= 'maak gelijknamig '.$som['t1'].'/'.$som['t3'].' en '.$som['t2'].'/'.$som['t4'];
						$som['rs']			= $r1.'/'.$r3.' en '.$r2.'/'.$r3;
						//val($som['rs']);
						if ($vr == 'mk'){	
							$ants[0]	= $som['rs'];
							$ants[1]	= $r1.'/'.($r3+1).' en '.$r2.'/'.($r3+1);
							$ants[2]	= ($r1+2).'/'.$r3.' en '.($r2+1).'/'.$r3;
							$ants[3]	= ($r1+2).'/'.($r3+1).' en '.$r2.'/'.($r3+1);
							$ants[4]	= ($r1+2).'/'.($r3+1).' en '.($r2+1).'/'.($r3+1);
							$ants[5]	= ($r1+2).'/'.$r3.' en '.$r2.'/'.$r3;
							$ants[6]	= $r1.'/'.($r3+1).' en '.($r2+1).'/'.($r3+1);
							for($i = 0; $i < count($ants); $i++) {
								if ($ants[$i] == $ants[0]) { unset($ants[$i]); }
							}
						}
					} else {
					
						$ok		= 'u';
					}
				break;
				case 'br|7': //   a / c of b /d
					if ($som['t1'] != $som['t3'] and $som['t2'] != $som['t4']) {
						$r1			= $som['t1'] / $som['t3'];						
						$r2			= $som['t2'] / $som['t4'];	
						$som['tn']	= 'wat is de grootste '.$som['t1'].'/'.$som['t3'].' of '.$som['t2'].'/'.$som['t4'];
						$pas['aa']	= 2;
						if ($r1 > $som['t2']) {
							$som['rs']			= $som['t1'].'/'.$som['t3'];
							$ants[0]	= $som['rs'];
							$ants[1]	= $som['t2'].'/'.$som['t4'];
						} else {
							$som['rs']			= $som['t2'].'/'.$som['t4'];
							$ants[0]	= $som['rs'];
							$ants[1]	= $som['t1'].'/'.$som['t3'];
						}								
					} else {
						$ok		= 'u';
					}
				break;	
				case 'br|8': //   a / c + b /d
					if ($som['t1'] != $som['t3'] and $som['t2'] != $som['t4']) {
						$r1			= $som['t1'] * $som['t4'];							
						$r2			= $som['t2'] * $som['t3'];							
						$r3			= $r1 + $r2;							
						$r4			= $som['t3'] * $som['t4'];	
						for ($j = 7; $j >= 2; $j = $j -1){
							$rest1		= $r1 % $j;
							$rest2		= $r2 % $j;
							$rest3		= $r3 % $j;
							$rest4		= $r4 % $j;
							if ($rest1 == 0 and $rest2 == 0 and $rest3 == 0 and $rest4 == 0) {
								//val('j '.$j);
								$r1		= $r1 / $j;
								$r2		= $r2 / $j;
								$r3		= $r3 / $j;
								$r4		= $r4 / $j;
							}						
						}
						$som['tn']	= $som['t1'].'/'.$som['t3'].' + '.$som['t2'].'/'.$som['t4'];
						$som['rs']			= $r3.'/'.$r4;
						//val($som['rs']);
						if ($vr == 'mk'){	
							$ants[0]	= $som['rs'];
							$ants[1]	= $r3.'/'.($r4+1);
							$ants[2]	= $som['t1'].'/'.$r4;
							$ants[3]	= ($som['t1']+1).'/'.$r4;
							$ants[4]	= $r3.'/'.$som['t4'];
							$ants[5]	= $r1.'/'.$som['t4'];
							$ants[6]	= $som['t2'].'/'.$r4;
							for($i = 0; $i < count($ants); $i++) {
								if ($ants[$i] == $ants[0]) { unset($ants[$i]); }
							}
						}
					} else {							
						$ok		= 'u';
					}							
				break;	
				case 'br|9': //   a / c - b /d
					if ($som['t1'] != $som['t3'] and $som['t2'] != $som['t4'] and ($som['t1']/$som['t3'] > $som['t2']/$som['t4'])) {
						$r1			= $som['t1'] * $som['t4'];							
						$r2			= $som['t2'] * $som['t3'];							
						$r3			= $r1 - $r2;							
						$r4			= $som['t3'] * $som['t4'];	
						for ($j = 7; $j >= 2; $j = $j -1){
							$rest1		= $r1 % $j;
							$rest2		= $r2 % $j;
							$rest3		= $r3 % $j;
							$rest4		= $r4 % $j;
							if ($rest1 == 0 and $rest2 == 0 and $rest3 == 0 and $rest4 == 0) {
								//val('j '.$j);
								$r1		= $r1 / $j;
								$r2		= $r2 / $j;
								$r3		= $r3 / $j;
								$r4		= $r3 / $j;
							}						
						}
						$som['tn']	= $som['t1'].'/'.$som['t3'].' - '.$som['t2'].'/'.$som['t4'];
						$som['rs']			= $r3.'/'.$r4;
						//val($som['rs']);
						if ($vr == 'mk'){	
							$ants[0]	= $som['rs'];
							$ants[1]	= $r3.'/'.($r4+1);
							$ants[2]	= $som['t1'].'/'.$r4;
							$ants[3]	= ($som['t1']+1).'/'.$r4;
							$ants[4]	= $r3.'/'.$som['t4'];
							$ants[5]	= $r1.'/'.$som['t4'];
							$ants[6]	= $som['t2'].'/'.$r4;
							for($i = 0; $i < count($ants); $i++) {
								if ($ants[$i] == $ants[0]) { unset($ants[$i]); }
							}
						}
					} else {							
						$ok		= 'u';
					}							
				break;	
				case 'br|a': //   a / c  * b /d
					if ($som['t1'] != $som['t3'] and $som['t2'] != $som['t4']) {
						$r1			= $som['t1'] * $som['t2'];							
						$r2			= $som['t3'] * $som['t4'];	
						for ($j = 7; $j >= 2; $j = $j -1){
							$rest1		= $r1 % $j;
							$rest2		= $r2 % $j;
							if ($rest1 == 0 and $rest2 == 0) {
								//val('j '.$j);
								$r1		= $r1 / $j;
								$r2		= $r2 / $j;
							}						
						}
						$som['tn']	= $som['t1'].'/'.$som['t3'].' * '.$som['t2'].'/'.$som['t4'];
						$som['rs']			= $r1.'/'.$r2;
						//val($som['rs']);
						if ($vr == 'mk'){	
							$ants[0]	= $som['rs'];
							$ants[1]	= $r1.'/'.($r2+1);
							$ants[2]	= $som['t1'].'/'.$r2;
							$ants[3]	= ($som['t1']+1).'/'.$r2;
							$ants[4]	= ($r1+1).'/'.$som['t2'];
							$ants[5]	= $r1.'/'.$som['t4'];
							$ants[6]	= $som['t1'].'/'.$som['t4'];
							for($i = 0; $i < count($ants); $i++) {
								if ($ants[$i] == $ants[0]) { unset($ants[$i]); }
							}
						}
					} else {							
						$ok		= 'u';
					}							
				break;			
				case 'br|b': //   a/c / b/d
					if ($som['t1'] != $som['t3'] and $som['t2'] != $som['t4']) {
						$r1			= $som['t1'] * $som['t4'];							
						$r2			= $som['t2'] * $som['t3'];	
						for ($j = 7; $j >= 2; $j = $j -1){
							$rest1		= $r1 % $j;
							$rest2		= $r2 % $j;
							if ($rest1 == 0 and $rest2 == 0) {
								//val('j '.$j);
								$r1		= $r1 / $j;
								$r2		= $r2 / $j;
							}						
						}
						$som['tn']	= $som['t1'].'/'.$som['t3'].' * '.$som['t2'].'/'.$som['t4'];
						$som['rs']			= $r1.'/'.$som['t2'];
						//val($som['rs']);
						if ($vr == 'mk'){	
							$ants[0]	= $som['rs'];
							$ants[1]	= $r1.'/'.($r2+1);
							$ants[2]	= $r2.'/'.$r1;
							$ants[3]	= ($som['t1']+1).'/'.$r2;
							$ants[4]	= $r1.'/'.$som['t4'];
							$ants[5]	= $r1.'/'.($som['t4']+1);
							$ants[6]	= $som['t2'].'/'.$r2;
							for($i = 0; $i < count($ants); $i++) {
								if ($ants[$i] == $ants[0]) { unset($ants[$i]); }
							}
						}
					} else {							
						$ok		= 'u';
					}							
				break;
				case 'mv|1': 	//   a ^ b							// meer vaardigheden
					$som['tn']	= $som['t1']." ^ ".$som['t2'];
					$som['rs']			= pow($som['t1'],$som['t2']);
					//val($som['rs']);
					if ($vr == 'mk'){	
						$ants[0]	= $som['rs'];
						$ants[1]	= $som['rs'] - 10;
						$ants[2]	= $som['rs'] * 2 + 1;
						$ants[3]	= $som['rs'] + 5;
						$ants[4]	= round($som['rs'] / 2);					
						$ants[5]	= $som['rs'] * 2;					
						$ants[6]	= $som['rs'] + 3;	
						for($i = 0; $i < count($ants); $i++) {
							if ($ants[$i] == $ants[0]) { unset($ants[$i]); }
						}
					}
						
				break;
				case 'mv|2': //   afronden ov
					$r1		= rand(1,99).".";
					$r2		= $som['t1'] - 1;
					$r1		.= rand(pow(10,$r2),9*pow(10,$r2));
					$r1		.= rand((430),(583));
					$som['tn']	= 'rond '.$r1.' af op '.$som['t1'].' decimalen';
					$som['rs']			= round($r1,$som['t1']);
				break;							
			}
			if ($ok	== 'a') {
				if (isset($pas['bb']) and $som['rs'] < $pas['bb']) 	{ $ok = 'u';}
				if (isset($pas['bt']) and $som['rs'] > $pas['bt']) 	{ $ok = 'u';}
				if (isset($som['rs']) and( $som['rs'] == 0)) 		{ $ok = 'u';}
			}	
			$tl--;
		}
		logarr($con,$som,"bepaalde som");
		$nabew = $vr.'|'.$rk;
		switch ($nabew) { // generieke meerkeuze antwoorden
			case 'mk|mi|1': 
			case 'mk|pl|1': 
			case 'mk|ke|1': 
			case 'mk|de|1': 
			case 'mk|ma|1': 
				if ( $som['rs'] > 30  or $som['rs'] < -30) {
					$van	= ($som['rs']* 70 ) / 100;
					$tot	= ($som['rs']* 110) / 100;
				} elseif ( $som['rs']> 20  or $som['rs']< -20) {
					$van	= ($som['rs']* 50) / 100;
					$tot	= ($som['rs']* 120) / 100;
				} elseif ($som['rs']< 0) {
					$van	= -1;
					$tot	= -11;
				} else {
					$van	= 1;
					$tot	= 11;
				}
				unset($al);
				$al[$som['rs']] 	= 1;
				$tel	= 1;
				for ($i = 0; $i <= 20; $i++){ 
					$ant	 	= rand((int)$van,(int)$tot);
					if (!isset($al[$ant])) {
						$ants[$tel] = $ant;
						$al[$ant] 	= 1;
						$tel++;
					}
				}
				if ($tel < 5) {$aa 	= $tel;} else {$aa = 5;}
				$ants[rand(1,$aa)]	= $som['rs'];
				logarr($con,$ants,"alle antwoorden	");

				for ($i = 1; $i <= $aa; $i++){ 
					$som['a'.$i]	= $ants[$i];
				}
				$pas['aa'] 				= $aa; // aantal gevonden antwoorden
			break;
			case 'mk|br|1': 
			case 'mk|br|2': 
			case 'mk|br|3': 
			case 'mk|br|4': 
			case 'mk|br|5';
			case 'mk|br|6': 
			case 'mk|br|7': 
			case 'mk|br|8': 
			case 'mk|br|9': 
			case 'mk|br|a': 
			case 'mk|br|b';
			case 'mk|mv|1': 
				$antOk		= 'u';
				while ($antOk	== 'u') {
					shuffle($ants);
					for ($j = 1; $j <= $pas['aa']; $j++){
						$som['a'.$j] = $ants[$j-1];											
						//val($som['a'.$j]." ".$som['rs']);
						if ($ants[$j-1]	== $som['rs']) {
							$antOk		= 'a';
						}
					}	
				}
			break;
		}
	//	val('1: '.$nabew);
		switch ($nabew) { // naar decimale komma
			case 'ov|ke|2': 
			case 'ov|de|2': 
			case 'ov|mv|2':  
			case 'ov|pl|2': 
			case 'ov|pl|1': 
			case 'ov|mi|1': 
				$som['rs']	=	str_replace(".",",",$som['rs']);
				$som['tn']	=	str_replace(".",",",$som['tn']);
			break;
		}
		if ($vr == 'mk'){	
			for ($j = 1; $j <= $pas['aa']; $j++){
				if ($som['a'.$j] 	== $som['rs']) {
					$som['ko']	= $j;
		//			val($som['ko']);
				}
			}			
		}
		$somRes[$smtl.$ken] 	= $som;
		//val('ken in gn '.$ken);
		logarr($con,$som,"som aangevuld");
	}
	logarr($con,$somRes,"somRes");
}
?>
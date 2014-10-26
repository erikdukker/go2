<?
logmod($con,'in_smgn.php genereer sommen');
//logval($con,memory_get_usage(),"aan het begin");
unset($oktl,$noktl);
switch ($lv) {
	case 'sn': 	$aantAnt=6;		$aantOk=4; 	break;	//snel oefenen
	case 'vol': $aantAnt=10;	$aantOk=6; 	break;	//voldoende (6)
	case 'goe': $aantAnt=10;	$aantOk=8; 	break;	//goed (8)
	case 'gom': $aantAnt=20;	$aantOk=16; break;	//goed (8) meer oefenen
}
$rsco			= getrs($con,"SELECT * FROM ts where co = '".$acco."' and tp = 'co'","co"); 
while ($rwco 	= mysqli_fetch_array($rsco)){
	logarr($con,$rwco,"stats som");
	$ken		= $rwco['sr']."|".$rwco['br']."|".$rwco['vr'];	
	$sr			= $rwco['sr'];
	if ($rwtt = getrw($con,"SELECT * FROM tt where sr = '".$rwco['sr']."' and br = '".$rwco['br']."' and vr ='".$rwco['vr'].
							"' and ssid = '".$_SESSION['gossid']."'","tt")){	
		if (isset($oktl['tot'])) {
			$oktl['tot']	= $oktl['tot'] 	+ $rwtt['oktl'];	
			$noktl['tot']	= $noktl['tot']	+ $rwtt['noktl'];	
		} else {
			$oktl['tot']	= $rwtt['oktl'];	
			$noktl['tot']	= $rwtt['noktl'];	
		}	
		if (isset($oktl[$rwco['sr']])) {
			$oktl[$rwco['sr']]	= $oktl[$rwco['sr']] 	+ $rwtt['oktl'];	
			$noktl[$rwco['sr']]= $noktl[$rwco['sr']]	+ $rwtt['noktl'];	
		} else {
			$oktl[$rwco['sr']]	= $rwtt['oktl'];	
			$noktl[$rwco['sr']]= $rwtt['noktl'];	
		}
		if (isset($oktl[$ken])) {
			$oktl[$ken]	= $oktl[$ken] 	+ $rwtt['oktl'];	
			$noktl[$ken]= $noktl[$ken]	+ $rwtt['noktl'];	
		} else {
			$oktl[$ken]	= $rwtt['oktl'];	
			$noktl[$ken]= $rwtt['noktl'];	
		}
		$spsc[$ken]	= substr_count(substr($rwtt['sp'],0,$aantAnt),'a');
		$tot		= $rwtt['oktl'] + $rwtt['noktl'];	
		$sco		= round(($rwtt['oktl'] *	100 )/ $tot);	
		if ($spsc[$ken] > $aantOk) {
			$pri[$ken]	= 1;
		} else {
			$wd			= $rwco['wd'];
			//$scocor		= -1 * round($tot/20) * ($sco / 5); // goeie score dan vooral doorgaan
			$scocor		= 0; // eerst maar niet
			$sw			= rand(-10,10);
			//$sw			= 0; // eerst maar niet
			$pri[$ken]	= 1000 - (round($rwco['wd'] + $scocor + $sw)); // de waardering, voortgang , swing voor variatie
			$prilog		= $ken." | pri: ".$pri[$ken]." wd: ".$wd." swing: ".$sw." score: ".$sco." score cor: ".$scocor;
			logval($con,$prilog,"prilog");
		}
	} else { 
		$wd			= $rwco['wd'];
	//	$cor		= -5; 			// nog geen scores beetje naar voren halen
		$cor		= 0; 			// eerst maar niet
		$sw			= rand(-10,10);
		//$sw			= 0; 			// eerst maar niet
		$pri[$ken]	= 1000 - (round($rwco['wd'] + $cor + $sw)); 
		$prilog		= $ken." | pri: ".$pri[$ken]." wd: ".$wd." swing: ".$sw." cor: ".$cor;
		logval($con,$prilog,"prilog");
	}
	$tit[$ken]		= $rwco['ti'];
	$somPas[$ken] 	= $rwco['tspa'];	
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
		unset($ken,$t1,$t2,$t3,$t4,$rs);
		foreach ($selSm as $tken => $tmp) {
			$j++;
	//		val('j  '.$j);
			if ($j 		== $welke ) {
				$ken 	= $tken;
				//val('ken in de sel'.$ken);
			}
		}
		unset($som);
		$pas	= toar($somPas[$ken]);
		logarr($con,$pas,"pas");
		$kens	= str_getcsv($ken,'|');
		$sr		= $kens[0];	
		$br		= $kens[1];
		$vr		= $kens[2];
		$ok		= 'u';
		$tl		= 20;
		while ( $ok		== 'u' and $tl	> 0 ) {
			if (!isset($pas['t1s'])) { 
				if (isset($pas['t1b']) and isset($pas['t1t'])) {
					$t1		= rand($pas['t1b'],$pas['t1t']);
				}	
			} else {
				switch ($pas['t1s']) {
					case 'b': 	
						$t1 	= rand(1,9) * ( pow(10,rand(1,2)));break;
					case 'c': 	
						$t1 	= rand(1,99999) / ( pow(10,rand(1,4)));break;
					//	val('t1 '.$t1);
				}		
			}	
			if (!isset($pas['t2s'])) { 
				if (isset($pas['t2b']) and isset($pas['t2t'])) {
					$t2		= rand($pas['t2b'],$pas['t2t']);
				}
			} else {
				switch ($pas['t2s']) {
					case 'b': 	
						$t2 	= rand(1,9) * ( pow(10,rand(1,2)));break;
					//	val('t2 '.$t2);
				}		
			}	
			if (!isset($pas['t3s'])) { 
				if (isset($pas['t3b']) and isset($pas['t3t'])) {
					$t3		= rand($pas['t3b'],$pas['t3t']);
				}
			} else {
				switch ($pas['t3s']) {
					case 'b': 	
						$t3 	= rand(1,9) * ( pow(10,rand(1,2)));break;
					//	val('t3 '.$t3);
				}		
			}
			if (!isset($pas['t4s'])) { 
				if (isset($pas['t4b']) and isset($pas['t4t'])) {
					$t4		= rand($pas['t4b'],$pas['t4t']);
				}
			} else {
				switch ($pas['t4s']) {
					case 'b': 	
						$t4 	= rand(1,9) * ( pow(10,rand(1,2)));break;
					//	val('t4 '.$t4);
				}		
			}
			$ok		= 'a';
			switch ($sr) {
				case 'af': $rs	= $t1 - $t2; $som['tn']	= $t1." - ".$t2; break;	// aftrekken
				case 'op': 
					if (isset($t3)){
						$rs	= $t1 + $t2 + $t3; $som['tn']	= $t1." + ".$t2." + ".$t3; break;	// optellen 3 getallen
					} else {
						$rs	= $t1 + $t2; $som['tn']	= $t1." + ".$t2; break;	// optellen 
					}
				case 'vm': $rs	= $t1 * $t2; $som['tn']	= $t1." x ".$t2; break;	// vermenigvuldigen
				case 'dl': 								// delen
					$som['tn']	= $t1." / ".$t2;
					if ($t2 != 0 and $t1 != 0) {	
						$rest			= $t1 % $t2;
						if ($rest == 0) {
							$rs			= $t1 / $t2; 
						} else {
							$ok	= 'u';
						}
					} else {
						$ok		= 'u';
					}
					break;					
				case 'br': 								// breuken
					switch ($pas['sp']) {
						case 'bv1': //   a/b = ?/c
							$rest			= $t2 % $t1;
							if ($rest != 0) {
								//val('t1 '.$t1." t2 ".$t2." t3 ".$t3.' rest '.$rest);
								$r1			= $t1 * $t3;
								$r2			= $t2 * $t3;
								$som['tn']	= $r1."/".$r2;
								$som['aa']	= 5;
								for ($j = 7; $j >= 2; $j = $j -1){
									$rest1		= $t1 % $j;
									$rest2		= $t2 % $j;
									if ($rest1 == 0 and $rest2 == 0) {
										//val('j '.$j);
										$t1		= $t1 / $j;
										$t2		= $t2 / $j;
									}						
								}
								$rs			= $t1."/".$t2;
								//val($rs);
								if ($vr == 'mk'){	
									$ants[0]	= $rs;
									$ants[1]	= $r1."/".$t2;
									$ants[2]	= $t1."/".$r2;
									$ants[3]	= ($t1+1)."/".($t2+1);
									$ants[4]	= ($r1-1)."/".($r2-1);
									$r1			= $t1 * ($t3-1);
									$r2			= $t2 * ($t3-1);							
									$ants[5]	= $r1."/".$t2;
									$r1			= $t1 * ($t3+1);
									$r2			= $t2 * ($t3+1);							
									$ants[6]	= $r1."/".$t2;	
								}
							} else {
								$ok		= 'u';
							}
							break;
						case 'bv2': //   a/b = ?/c
							if ($t3 != $t4) {
								for ($j = 7; $j >= 2; $j = $j -1){
									$rest1		= $t1 % $j;
									$rest2		= $t2 % $j;
									if ($rest1 == 0 and $rest2 == 0) {
										$t1		= $t1 / $j;
										$t2		= $t2 / $j;
									}						
								}
								$r1			= $t1 * $t3;
								$r2			= $t2 * $t3;
								$r3			= $t1 * $t4;
								$r4			= $t2 * $t4;
								$som['tn']	= $r1."/".$r2." = ?/".$r4." dan ? ";	
								$som['aa']	= 5;
								$rs			= $r3;
								//val($rs);
								if ($vr == 'mk'){	
									$ants[0]	= $rs;
									$ants[1]	= $r1;
									$ants[2]	= $rs+1;
									$ants[3]	= $t1;
									$ants[4]	= $t4*3;						
									$ants[5]	= $t3;
									$ants[6]	= $t4*2;	
								}
							} else {
								$ok		= 'u';
							}
							break;
						case 'bv3': //   0,4 = ? / ?
							$rest			= $t1 % $t2;
							if ($rest != 0) {
								$r1			= round($t1/$t2,3);
								$som['tn']	= $r1;
								$som['aa']	= 5;
								for ($j = 7; $j >= 2; $j = $j -1){
									$rest1		= $t1 % $j;
									$rest2		= $t2 % $j;
									if ($rest1 == 0 and $rest2 == 0) {
										//val('j '.$j);
										$t1		= $t1 / $j;
										$t2		= $t2 / $j;
									}						
								}
								$rs			= $t1."/".$t2;
								//val($rs);
								if ($vr == 'mk'){	
									$ants[0]	= $rs;
									$ants[1]	= ($t1+1)."/".$t2;
									$ants[2]	= $t1."/".($t2+1);
									$ants[3]	= ($t1+1)."/".($t2+1);
									$ants[4]	= ($t1-1)."/".($t2-1);			
									$ants[5]	= ($t1+2)."/".($t2);					
									$ants[6]	= ($t1+3)."/".($t2+1);	
								}
							} else {
								$ok		= 'u';
							}
							break;
						case 'bv4': //   a / b = c d / a
							if ($t1 > $t2) {
								$r1			= $t1 % $t2;
								if ($r1 == 0) {
									$ok		= 'u';
								}
								$r2			= $t1 - $r1;
								$r3			= $r2 / $t2; // hele
								$som['tn']	= $t1.'/'.$t2;
								$som['aa']	= 5;
								$rs			= $r3.' '.$r1.'/'.$t2;
								//val($rs);
								if ($vr == 'mk'){	
									$ants[0]	= $rs;
									$ants[1]	= $r3.' '.$r1.'/'.$t2;
									$ants[2]	= $r3.' '.$r2.'/'.$t2;
									$ants[3]	= ($r3-1).' '.$r1.'/'.$t2;
									$ants[4]	= ($r3+1).' '.$r1.'/'.$t2;
									$ants[5]	= $r3.' '.($r1+1).'/'.$t2;				
									$ants[6]	= ($r3+1).' '.($r1+1).'/'.$t2;									
								}
							} else {
								$ok		= 'u';
							}
							break;	
						case 'bv5': //   c a / b =  d / b
							if ($t1 != $t2) {
								$r1			= $t3 * $t2 + $t1;							
								$som['tn']	= $t3.' '.$t1.'/'.$t2;
								$som['aa']	= 5;
								$rs			= $r1.'/'.$t2;
								//val($rs);
								if ($vr == 'mk'){	
									$ants[0]	= $rs;
									$ants[1]	= $t1.'/'.$t2;
									$ants[2]	= $r1.'/'.($t2+1);
									$ants[3]	= ($r1-1).'/'.$t2;
									$ants[4]	= ($r1+1).'/'.$t2;
									$ants[5]	= ($t1+1).'/'.$t2;				
									$ants[6]	= ($t1+5).'/'.$t2;	
								}
							} else {
							
								$ok		= 'u';
							}
							break;
						case 'bv6': //   a / c en b /d
							if ($t1 != $t2 and $t3 != $t4) {
								$r1			= $t1 * $t4;							
								$r2			= $t2 * $t3;							
								$r3			= $t3 * $t4;	
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
								$som['tn']	= $t1.'/'.$t3.' en '.$t2.'/'.$t4;
								$som['aa']	= 5;
								$rs			= $r1.'/'.$r3.' en '.$r2.'/'.$r3;
								//val($rs);
								if ($vr == 'mk'){	
									$ants[0]	= $rs;
									$ants[1]	= $r1.'/'.($r3+1).' en '.$r2.'/'.($r3+1);
									$ants[2]	= ($r1+2).'/'.$r3.' en '.($r2+1).'/'.$r3;
									$ants[3]	= ($r1+2).'/'.($r3+1).' en '.$r2.'/'.($r3+1);
									$ants[4]	= ($r1+2).'/'.($r3+1).' en '.($r2+1).'/'.($r3+1);
									$ants[5]	= ($r1+2).'/'.$r3.' en '.$r2.'/'.$r3;
									$ants[6]	= $r1.'/'.($r3+1).' en '.($r2+1).'/'.($r3+1);
								}
							} else {
							
								$ok		= 'u';
							}
							break;
						case 'bv7': //   a / c of b /d
							if ($t1 != $t2 and $t3 != $t4) {
								$r1			= $t1 / $t3;						
								$r2			= $t2 / $t4;	
								$som['tn']	= 'wat is de grootste '.$t1.'/'.$t3.' en '.$t2.'/'.$t4;
								$som['aa']	= 2;
								if ($r1 > $t2) {
									$rs			= $t1.'/'.$t3;
									$ants[0]	= $rs;
									$ants[1]	= $t2.'/'.$t4;
								} else {
									$rs			= $t2.'/'.$t4;
									$ants[0]	= $rs;
									$ants[1]	= $t1.'/'.$t3;
								}								
							} else {
								$ok		= 'u';
							}
							break;	
						case 'bv8': //   a / c + b /d
							if ($t1 != $t3 and $t2 != $t4) {
								$r1			= $t1 * $t4;							
								$r2			= $t2 * $t3;							
								$r3			= $r1 + $r2;							
								$r4			= $t3 * $t4;	
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
								$som['tn']	= $t1.'/'.$t3.' + '.$t2.'/'.$t4;
								$som['aa']	= 5;
								$rs			= $r3.'/'.$4;
								//val($rs);
								if ($vr == 'mk'){	
									$ants[0]	= $rs;
									$ants[1]	= $r3.'/'.($r4+1);
									$ants[2]	= $t1.'/'.$r4;
									$ants[3]	= ($t1+1).'/'.$r4;
									$ants[4]	= $r3.'/'.$t4;
									$ants[5]	= $r1.'/'.$t4;
									$ants[6]	= $t2.'/'.$r4;
								}
							} else {							
								$ok		= 'u';
							}							
							break;	
						case 'bv9': //   a / c - b /d
							if ($t1 != $t3 and $t2 != $t4 and ($t1/$t3 > $t2/$t4) {
								$r1			= $t1 * $t4;							
								$r2			= $t2 * $t3;							
								$r3			= $r1 - $r2;							
								$r4			= $t3 * $t4;	
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
								$som['tn']	= $t1.'/'.$t3.' - '.$t2.'/'.$t4;
								$som['aa']	= 5;
								$rs			= $r3.'/'.$4;
								//val($rs);
								if ($vr == 'mk'){	
									$ants[0]	= $rs;
									$ants[1]	= $r3.'/'.($r4+1);
									$ants[2]	= $t1.'/'.$r4;
									$ants[3]	= ($t1+1).'/'.$r4;
									$ants[4]	= $r3.'/'.$t4;
									$ants[5]	= $r1.'/'.$t4;
									$ants[6]	= $t2.'/'.$r4;
								}
							} else {							
								$ok		= 'u';
							}							
							break;	
					}
					break;
				case 'mv': 								// meer vaardigheden
					switch ($pas['sp']) {			
						case 'ma': //   a ^ b machten
							$som['tn']	= $t1." ^ ".$t2;
							$som['aa']	= 5;
							$rs			= pow($t1,$t2);
							//val($rs);
							if ($vr == 'mk'){	
								$ants[0]	= $rs;
								$ants[1]	= $rs - 10;
								$ants[2]	= $rs * 2 + 1;
								$ants[3]	= $rs + 5;
								$ants[4]	= round($rs / 2);					
								$ants[5]	= $rs * 2;					
								$ants[6]	= $rs + 3;	
							}
								
							break;
						case 'afr': //   a ^ b
							$r1			= rand(1,99).".";
							if ($t1 > 0) {
								$r1		.= rand(pow(10,$t1),9*pow(10,$t1));
							}
							$r1		.= rand((3*pow(10,$t1)),(7*pow(10,$t1)));
							$som['tn']	= $r1.' op '.$t1.' decimalen';
							$rs			= round($r1,$t1);
							break;							
						}
					break;
			}
			if ($ok	== 'a') {
				if (isset($pas['bb']) and $rs < $pas['bb']) { $ok = 'u';}
				if (isset($pas['bt']) and $rs > $pas['bt']) { $ok = 'u';}
				if ( $rs == 0) 								{ $ok = 'u';}
			}	
			$tl--;
		}
		if (isset($t1)) {$som['t1']	= $t1;}
		if (isset($t2)) {$som['t2']	= $t2;}
		if (isset($t3)) {$som['t3']	= $t3;}
		if (isset($t4)) {$som['t4']	= $t4;}
		if (isset($rs)) {$som['rs']	= "'".$rs."'";}
		logarr($con,$som,"bepaalde som");
		$sel = array("af", "op", "vm", "dl", "ma");
		if (in_array($sr,$sel)){
			if ($vr == 'mk'){
				if ( $rs > 30  or $rs < -30) {
					$van	= ($rs* 70 ) / 100;
					$tot	= ($rs* 110) / 100;
				} elseif ( $rs> 20  or $rs< -20) {
					$van	= ($rs* 50) / 100;
					$tot	= ($rs* 120) / 100;
				} elseif ($rs< 0) {
					$van	= -1;
					$tot	= -11;
				} else {
					$van	= 1;
					$tot	= 11;
				}
				$aa		= 1;
				for ($i = 1; $i <= 10; $i++){
					$tant 	= rand((int)$van,(int)$tot);
					$ok		= 'a';
					for ($zoek = 1; $zoek < $aa; $zoek++){
						if ($som['a'.$zoek] == $tant) {$ok = 'u';}
						if ($rs				== $tant) {$ok = 'u';}
					}	
					if ($ok == 'a' and $aa <= 5) {
						$som['a'.$aa]	= $tant;
						$aa++;
					}
				}
				if ($aa > 5) {$aa = 5;} else {$aa = $aa - 1;}
				$som['aa'] 			= $aa; // aantal gevonden antwoorden
				$som['a'.rand(1,$aa)] = $rs;
			}
		}	
		$sel = array("br","mv");
		if (in_array($sr,$sel)){
			if ($vr == 'mk'){
			//	val($ants[4].$som['aa'].'leukt 2');		
				$antOk		= 'u';
				while ($antOk	== 'u') {
					shuffle($ants);
					for ($j = 1; $j <= $som['aa']; $j++){
						$som['a'.$j] = $ants[$j-1];											
						//val($som['a'.$j]." ".$rs);
						if ($ants[$j-1]	== $rs) {
							$antOk		= 'a';
						}
					}	
				}
			}
		}
		$som['rs']		= $rs;	
		$somRes[$smtl.$ken] 	= totx($som);
		//val('ken in gn '.$ken);
		logarr($con,$som,"som aangevuld");
	}
	logarr($con,$somRes,"somRes");
}
?>
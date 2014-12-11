<?
include 'aown/in_lconn.php'; 	// aanloggen op db
include 'acom/in_func.php';		// niet object georienteerd  maar wel met een laag van functies
logmod($con,'index');
if (isset($_SESSION['em']))	{	// is er aangelogd (alleen voor beheerstaken
	$rwus	= getrw($con,"select * from us where em = '".$_SESSION['em']."'","us"); 
}
if (!isset($rwus['uspa'])){		// zo niet: haal de default bevoegdheden op
	$rwus	= getrw($con,"select * from us where us = 'anon'","us"); 
	$us		= 'anon'; 
}
$uspa	= toar($rwus['uspa']);
if (isset($_SESSION['evq'])) {	// ophalen evant queue
	$evq	= $_SESSION['evq'];
}	
//val($_COOKIE['gewoonoefenen']);
if (isset($_GET['si'])) {		//GO sessie id bij index meegegeven  in bv email link
	$_SESSION['gossid']	= $_GET['si'];
} elseif (isset($_COOKIE['gewoonoefenen'])) {
	$_SESSION['gossid']	= $_COOKIE['gewoonoefenen'];
}

if (!isset($_SESSION['gossid'])){ //GO sessie id starten
	if (!empty($_SERVER['HTTP_CLIENT_IP']))  { 			//check ip from share internet
		$ip=$_SERVER['HTTP_CLIENT_IP']; 
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
		 $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; 
	} else {
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	//$_SESSION['lg']	= 'log aan';
	$gossid 		= uniqid();	// genereer een uniek nummer
	$sspa['usip']	= $ip;
	exsql($con,"insert ss SET ssid='".$gossid."', usky = '".$rwus['usky']."', ssst = '".time()."', ssls = '".time()."', sspa = '".totx($sspa)."'","nieuw session");
	logval($con,$gossid,"sessie id ");
	$_SESSION['gossid']	= $gossid;
	setcookie('gewoonoefenen', $gossid , time() + (86400 * 30), '/'); 	// 86400 = 1 day
} else {
	$gossid			= $_SESSION['gossid'];
	$rwss			= getrw($con,"SELECT * FROM ss where ssid = '".$_SESSION['gossid']."'","ss"); 
	$sspa			= toar($rwss['sspa']);
	if (isset($_SESSION['acco']))	{
		$sspa['acco']	= $_SESSION['acco'];
	} elseif (isset($sspa['acco']))	{
		$_SESSION['acco'] = $sspa['acco'];
	}
	exsql($con,"update ss SET ssls = '".time()."', sspa = '".totx($sspa)."'where ssid ='".$gossid."'","session update"); // timestamp in de go sessie
}
if(isset($_GET['t'])) 		{ $_SESSION['trid']	= $_GET['t'];} 			//transaktie mee gegeven
if(isset($_SESSION['trid'])){ $trid				= $_SESSION['trid'];} else {$trid = null;}

//logarr($con,$uspa,'user parameters uspa');
if (isset($uspa)){
	foreach ($uspa as $usat => $usvl){
		if ($usat == 'usau') {											// autorisatie profiel uit user profiel
			$_SESSION['usau']	= $usvl;
			$rwob	= getrw($con,"SELECT * FROM ob where obid = '".$usvl."'","ob"); 
			$obpa	= toar($rwob['obpa']);
			foreach ($obpa as $obat => $obvl){							// profiel naar naar array $autr
				if (substr($obat,0,1) == 't') {
					$tran	= substr($obat,1);
					if (isset($autr[$tran])) { $trau = $autr[$tran]; } else { $trau =''; }
					if ($obvl == 's' or $trau == 's' ) {
						$autr[$tran] = 's'; // start
					} elseif ($obvl == 'g' or $trau == 'g' ) {
						$autr[$tran] = 'g'; // grijs
					} else {
						$autr[$tran] = 'v'; // verberg
					}
				}
			}	
		} elseif ($usat == 'trid') { //test key
			if ($trid == null) { 
				$trid = $usvl;
			}			
		} elseif ($usat == 'ltbz') { //laatste bezoek
			$ltbz = $usvl;	
		} elseif ($usat == 'log') { //laatste bezoek
			if ($usvl == 'aan') {
				$_SESSION['log'] = $uspa['em'];
			} else {
				$_SESSION['log'] = '';
			}
		}	
	}
} else {
	$trid = 'welk'; 				// zou niet moeten voorkomen: geen tranaktie naar welkom scherm
}
if ($rwus['us'] == 'edk') {			// de superuser heeft altijd toegang
	if (isset($trid)) {
		if ($trid == 'welk' ) {
			if (isset($_GET['t'])) {
				$trid = $_GET['t'];
			}
		}
	}
} elseif (!isset($autr) and !isset($rwus['us'])) { 
	$trid = 'welk';
} elseif (!isset($autr[$trid]) or $autr[$trid] != 's') {
	$trid= '';
}
//logarr($con,$autr,'autr');
include 'aown/in_htmh.php';
if (isset($trid)) { // alleen als er een tranactie is
	$rwtr 	= getrw($con,"select * from tr where trid = '".$trid."'","tr"); 
	echo "<div id='wrapper'>".PHP_EOL;
	echo "<div id='hdr'>".PHP_EOL;
	echo "<div id='tb'>".PHP_EOL;
	echo "<img alt='img' src='aown/GO logo 5 300x50.png'>".PHP_EOL;
	echo "</div>".PHP_EOL;	
	include 'acom/in_menu.php';
	echo "</div>".PHP_EOL;
	echo "<!-- na kop -->".PHP_EOL;
	$sep= '';
	if ($rwtr['meid']	!= null and $trid != 'smaa') {		
		echo "<div id='patit'> &#187 ".$rwtr['ds']."</div>".PHP_EOL;
	} else {
		echo "<div id='patit'> </div>".PHP_EOL;
	}
	echo "<!-- trans: ".$trid." -->".PHP_EOL;
	echo "<table><tr><td> ".PHP_EOL;
	$rsel 			= getrs($con,"SELECT * FROM el where trid= '".$trid."' order by sq","el"); 
	while ($rwel 	= mysqli_fetch_array($rsel)){
		$elpa = toar($rwel['elpa']);
		echo "<div class='el' id='el_".$rwct."'>".PHP_EOL;
		if (trim($elpa['pi']) != '') {
			include 'acom/'.$elpa['pi']."";
		} else {	
			echo "<div class='tx'>".PHP_EOL;
			if 	( $rwel['ti'] != NULL) {
				echo "<div class='ti' id='ti_".$rwct."'><h3>".$rwel['ti']."</h3></div>".PHP_EOL;
			}
			echo $rwel['tx'];	
			echo "</div>".PHP_EOL;
		}
		echo "</div>".PHP_EOL;
		$rwct++;
	}	
	echo "</td><td><h2> <h2></td></tr></table>".PHP_EOL;
	echo "<table><tr><td class='lab'><h2>advertentie</td><td>(we hopen de kosten terug te verdienen)</h2></td></tr></table>".PHP_EOL;
	echo "<table><tr><td class='lab'></td><td>".PHP_EOL;
	?>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- goex blok onder -->
	<ins class="adsbygoogle"
		 style="display:inline-block;width:728px;height:90px"
		 data-ad-client="ca-pub-6483878787902895"
		 data-ad-slot="2114386565"></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>
	<?
	echo "</td></tr></table>".PHP_EOL;
	echo "</div>".PHP_EOL;
} else {
	echo "<h1>geen toegang</h1>";
}
?>
</body>
</html>
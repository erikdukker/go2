<?
auth('prod');
$enky	= $_SESSION['enky'];
$resbo = mysqli_query("SELECT * from bo WHERE enky ='".$enky."' and boid='uitn'"); 
if ($rowbo = mysqli_fetch_array($resbo)) {
	$rsts = mysqli_query("SELECT * from en WHERE enky ='".$enky."' and st = 'o'"); 
	if (mysqli_num_rows($rsts)){
		echo "<form accept-charset='UTF-8' action='acom\sdbo.php' method='post'>";
		$rwts	= mysqli_fetch_array($rsts);
		$tspa	=	toar($rwts['tspa']);
		$resrp = mysqli_query("SELECT * from rp WHERE enky ='".$enky."' and st = 'i' "); 
		$i = 1;
		echo "<table class=qst><tr> ".PHP_EOL;
		while ($rowrp= mysqli_fetch_array($resrp)) { 
			echo "<tr> ".PHP_EOL;
			echo "<td><input type='checkbox' name= 'ch".$i."' size='10'>".PHP_EOL;
			echo "<input name='em".$i."' type='text' value = '".$rowrp['em']."' readonly size='50'>";
			echo "<input name='rpky".$i."' type='hidden' value = '".$rowrp['rpky']."' readonly></td>";
			echo "</tr> ".PHP_EOL;
			$i++;
		}
		echo "</tr> ".PHP_EOL;
		echo "<table class=qst><tr> ".PHP_EOL;
		echo "<th><input class='button cust1' id='filled_form_submit' name='fcommit' type='submit' value='uitnodigen' /> </th>".PHP_EOL;
		echo "<input name='enky' type='hidden' value = '".$rwts['enky']."' >";
		echo "<input name='from' type='hidden' value = '".$tspa['enem']."' >";
		echo "<input name='boid' type='hidden' value = 'uitn' >";
		echo "<input name='rpst' type='hidden' value = 'u' >";
		echo "</tr></table></form>".PHP_EOL;
	} else { 
		echo "<br><h1>enquete niet goedgekeurd</h1><br>";
	}	
} else {
	echo "<h2>Eerst de boodschap voor uitnodigen verzorgen</h2>".PHP_EOL;
}

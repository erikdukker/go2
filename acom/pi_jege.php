<?	
logmod($con,'pi_jege je gegevens ');
echo "<form accept-charset='UTF-8' action='acom/up_jege.php' id='llfrm' method='post'>".PHP_EOL;	
if (!isset($sspa['llem'])) {$sspa['llem'] = '';}
if (!isset($sspa['llvn'])) {$sspa['llvn'] = '';}
echo "<table>".PHP_EOL;		
echo "<tr><td>email</td><td><input name='llem' type='email' value='".$sspa['llem']."' /></td><td>we sturen je een email met een link om verder te gaan</td></tr> ".PHP_EOL;
echo "<tr><td>voornaam</td><td><input name='llvn' type='text' value='".$sspa['llvn']."' /></td><td>het is leuk om je naam te kunnen gebruiken</td></tr>".PHP_EOL;
echo "</table>".PHP_EOL;
echo "<table>".PHP_EOL;
echo "<tr><td><input class='but cust1' name='fcommit' type='submit' value='opslaan' >".PHP_EOL;
echo "</table>".PHP_EOL;
echo "</form>".PHP_EOL;
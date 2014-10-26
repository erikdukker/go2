<?	
logmod($con,'pi_cold');
// eerst oude files op ruimen
$dir= 'zfil/';
//echo 'hier zijn we'.getcwd();
$files = glob($dir.'/*'); 
foreach($files as $file) {
    $filemtime=filemtime ($file);
    if (time()-$filemtime>= 172800) {
       unlink($file);
	}
}
?>
<h2><br>configuratie loaden</h2>
<form enctype="multipart/form-data" action="acom/up_cold.php" method="POST">
<table id='dd'>
<tr><td>Tests laden <input name="upts" type="file"> </td></tr>
<tr><td><input class="button cust1" name="upts" type="submit" value="laden" /></td></tr>
</table>
</form>
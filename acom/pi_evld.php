<?	
logmod($con,'pi_evld');
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
<h2><br>events laden</h2>
<form enctype="multipart/form-data" action="acom/up_evld.php" method="POST">
<table id='dd'>
<tr><td>Events laden <input name="upev" type="file"> </td></tr>
<tr><td><input class="button cust1" name="upev1" type="submit" value="laden" /></td></tr>
</table>
</form>
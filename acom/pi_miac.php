<?	
echo "<!-- pi_miac mijn account  -->" .PHP_EOL;
?>
<br><br>Uw gegevens:
<form accept-charset="UTF-8" action="acom/up_miac.php" id="usfrm" method="post">
<?	
$rwus 	= getrw($con,"SELECT * FROM us where em = '".$_SESSION['em']."'","us"); 
$vals	= toar($rwus['uspa']);
?>
<table id='detb'>		
<tr><th>user</th><td><? echo $rwus['us'] ?></td></tr>
<th>email</th><td><? echo $rwus['em'] ?></td></tr> 
<tr></tr>
<tr><th>voornaam</th><td><input class="string" name="vn"  type="text" value="<? echo $rwus['vn'] ?>" /></td></tr>
<th>achternaam</th><td><input class="string" name="an"  type="text" value="<? echo $rwus['an'] ?>" /></td></tr>
<tr> <? inprad($con,'usea',$uspa); ?> </tr> 
</table>
<table id='detb'>
<tr><td> 	<input class="but cust1" name="fcommit" type="submit" value="opslaan" >
<td><input type="hidden" name="usky" size="20" type="text" value="<? echo $rwus['usky'] ?>"  /> </td> 
<td><a href='reg\change-pwd.php' class='but cust1'>Verander paswoord</a> </td>
<td><a href='reg\logout.php' class='but cust1'>Uitloggen</a> </td></tr>
</table>
</form>  
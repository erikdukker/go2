<?//db-connectie maken
ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);
$_SESSION['lg'] ='on'; //log aan	
// sessie kan gestart zijn door inloggen of vorige scherm anders nu een sessie aanmaken
if(!session_id('go')){ session_start('go');}
// aanloggen database
$con=mysqli_connect("localhost","go","4rfvbgt5","go");
if (mysqli_connect_errno($con))  {
  echo "Kan niet aanloggen bij MySQL: " . mysqli_connect_error();
}
?>
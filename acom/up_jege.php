<?
include '../aown/in_lconn.php';
include '../acom/in_func.php';
logarr($con,$_POST,"post");
$rwss	= getrw($con,"SELECT * FROM ss where ssid = '".$_SESSION['gossid']."'","ss"); 
$sspa	= toar($rwss['sspa']);
$sspa['llem']	= $_POST['llem'];
$sspa['llvn']	= $_POST['llvn'];
$sspa['acco']	= $_SESSION['acco'];
exsql($con,"update ss SET sspa = '".totx($sspa)."' where ssid = '".$_SESSION['gossid']."'","wijzig");
//val('doet ie het');
$from		= "info@gewoonoefenen.nl";
$subject	= "Ga verder met je oefening";
//create a boundary for the email
$boundary = 'zxcvbnm';
$headers  = "From: ".$from." \r\n";
$headers .= "Reply-To: ".$from." \r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/alternative;\r\n";
$headers .= "     boundary=". $boundary."\r\n";
$message = "This is a MIME encoded message.";
$message .= "\r\n\r\n--" . $boundary . "\r\n";
$message .= "Content-type: text/plain; charset=utf-8\r\n\r\n";
$message .= "Uw e-mailprogramma ondersteunt geen HTML-mail. ";
$message .= "\r\n\r\n--".$boundary."\r\n";
$message .= "Content-type: text/html; charset=utf-8\r\n\r\n";
$message .= "<html><head><title>".$subject."</title><meta http-equiv='Content-Type  content='text/html; charset=ISO-8859'></head>";
$message .= "<body>";
$message .= "<table align='center' border='0' cellpadding='0' cellspacing='0' width='650'>";
$message .= "<tbody><tr><td colspan='3' style='font-size: 12px; color: rgb(153, 153, 153); font-family: arial,helvetica,sans-serif; padding-bottom: 5px;' align='center'>.</td></tr>";
$message .= "<tr><td colspan='3' style='padding-bottom:20px;'><img src='http://gewoonoefenen.nl/go2/zimg/GO logo 5 300x50.png'></td></tr>";
$message .= "<tr><td colspan='3' style='padding-bottom:20px;'>";
$message .= "<p>Beste ".$_POST['llvn']."</p><p>Met deze link kun je je oefening weer opstarten:	<a href='http://gewoonoefenen.nl/go2/index.php?t=smaa&si=".$rwss['ssid']."'>start oefening</a></p><p></p><p>met vriendelijke groet Emma</p>";
$message .= "</td></tr></tbody></table>";
//$message .= "<html><body>gestart op 17 Sep 2014 10:22:36</body></html>";
$message .= "</body></html>";
//logval($con,$message,"$message");
$result = mail($_POST['llem'],$subject,$message, $headers);
if($result) {
//exsql($con,"update rp set st = 's' where rpky ='".$rpky."'","update");
}
header("location:"."../index.php?t=jege");
?>


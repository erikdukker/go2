<?PHP
require_once("fg_membersite.php");

$fgmembersite = new FGMembersite();

//Provide your site name here
$fgmembersite->SetWebsiteName('gewoonoefenen.nl');

//Provide the email address where you want to get notifications
$fgmembersite->SetAdminEmail('info@gewoonoefenen.nl');

//Provide your database login details here:
//hostname, user name, pw, database name and table name
//note that the script will create the table (for example, fgusers in this case)
//by itself on submitting register.php for the first time
$fgmembersite->InitDB(/*hostname*/'localhost',
                      /*us*/'go',
                      /*pw*/'4rfvbgt5',
                      /*database name*/'go',
                      /*table name*/'us');

//For better security. Get a random string from this link: http://tinyurl.com/randstr
// and put it here
$fgmembersite->SetRandomKey('smdJ5BTFzZO6YYc');

?>
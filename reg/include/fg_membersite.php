	<?PHP
/*
    Registration/Login script from HTML Form Guide
    V1.0

    This program is free software published under the
    terms of the GNU Lesser General Public License.
    http://www.gnu.org/copyleft/lesser.html
    

This program is distributed in the hope that it will
be useful - WITHOUT ANY WARRANTY; without even the
implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.

For updates, please visit:
http://www.html-form-guide.com/php-form/php-registration-form.html
http://www.html-form-guide.com/php-form/php-login-form.html

*/
require_once("class.phpmailer.php");
require_once("formvalidator.php");

class FGMembersite
{
    var $admin_email;
    var $from_address;
    
    var $us;
    var $pwd;
    var $database;
    var $tablename;
    var $connection;
    var $rand_key;
    
    var $error_message;
    
    //-----Initialization -------
    function FGMembersite()
    {
        $this->sitename = 'gewoonoefenen.nl';
        $this->rand_key = 'smdJ5BTFzZO6YYc';
    }
    
    function InitDB($host,$uname,$pwd,$database,$tablename)
    {
        $this->db_host  = $host;
        $this->us = $uname;
        $this->pwd  = $pwd;
        $this->database  = $database;
        $this->tablename = $tablename;
        
    }
    function SetAdminEmail($em)
    {
        $this->admin_email = $em;
      //  $this->$from_address = $em;
    }
    
    function SetWebsiteName($sitename)
    {
        $this->sitename = $sitename;
    }
    
    function SetRandomKey($key)
    {
        $this->rand_key = $key;
    }
    
    //-------Main Operations ----------------------
    function RegisterUser()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }
        
        $formvars = array();
        
        if(!$this->ValidateRegistrationSubmission())
        {
            return false;
        }
        
        $this->CollectRegistrationSubmission($formvars);
        
        if(!$this->SaveToDatabase($formvars))
        {
            return false;
        }
        
        if(!$this->SendUserConfirmationEmail($formvars))
        {
            return false;
        }

        $this->SendAdminIntimationEmail($formvars);
        
        return true;
    }

    function ConfirmUser()
    {
        if(empty($_GET['code'])||strlen($_GET['code'])<=10)
        {
            $this->HandleError("Geef de bevestigingscode");
            return false;
        }
        $user_rec = array();
        if(!$this->UpdateDBRecForConfirmation($user_rec))
        {
            return false;
        }
        
        $this->SendUserWelcomeEmail($user_rec);
        
        $this->SendAdminIntimationOnRegComplete($user_rec);
        
        return true;
    }    
    
    function Login()
    {
        if(empty($_POST['us']))
        {
            $this->HandleError("de usernaam is leeg!");
            return false;
        }
        
        if(empty($_POST['pw']))
        {
            $this->HandleError("het paswoord is!");
            return false;
        }
        
        $us = trim($_POST['us']);
        $pw = trim($_POST['pw']);
        
        if(!isset($_SESSION)){ session_start('go'); }
        if(!$this->CheckLoginInDB($us,$pw))
        {
            return false;
        }
        
        $_SESSION[$this->GetLoginSessionVar()] = $us;
        
        return true;
    }
    
    function CheckLogin()
    {
         if(!isset($_SESSION)){ session_start('go'); }

         $sessionvar = $this->GetLoginSessionVar();
         
         if(empty($_SESSION[$sessionvar]))
         {
            return false;
         }
         return true;
    }
    
    function vn()
    {
        return isset($_SESSION['vn'])?$_SESSION['vn']:'';
    }
    function an()
    {
        return isset($_SESSION['an'])?$_SESSION['an']:'';
    }
    
    function UserEmail()
    {
        return isset($_SESSION['em'])?$_SESSION['em']:'';
    }
    
    function LogOut()
    {
        session_start();
        
        $sessionvar = $this->GetLoginSessionVar();
        
        $_SESSION[$sessionvar]=NULL;
        
        unset($_SESSION[$sessionvar]);
    }
    
    function EmailResetpwLink()
    {
      if(empty($_POST['em']))
        {
            $this->HandleError("het emailadres is leeg!");
            return false;
        }
        $user_rec = array();
        if(false === $this->GetUserFromEmail($_POST['em'], $user_rec))
        {
            return false;
        }
        if(false === $this->SendResetpwLink($user_rec))
        {
            return false;
        }
        return true;
    }
    
    function Resetpw()
    {
        if(empty($_GET['em']))
        {
            $this->HandleError("het email adres is leeg!");
            return false;
        }
        if(empty($_GET['code']))
        {
            $this->HandleError("de reset code is leeg!");
            return false;
        }
        $em = trim($_GET['em']);
        $code = trim($_GET['code']);
        
        if($this->GetResetpwCode($em) != $code)
        {
            $this->HandleError("Bad reset code!");
            return false;
        }
        
        $user_rec = array();
        if(!$this->GetUserFromEmail($em,$user_rec))
        {
            return false;
        }
        
        $npw = $this->ResetUserpwInDB($user_rec);
        if(false === $npw || empty($npw))
        {
            $this->HandleError("Error updating new pw");
            return false;
        }
        
        if(false == $this->SendNewpw($user_rec,$npw))
        {
            $this->HandleError("Error sending new pw");
            return false;
        }
        return true;
    }
    
    function Changepw()
    {
        if(!$this->CheckLogin())
        {
            $this->HandleError("Not logged in!");
            return false;
        }
        
        if(empty($_POST['opw']))
        {
            $this->HandleError("Oude paswoord is leeg!");
            return false;
        }
        if(empty($_POST['npw']))
        {
            $this->HandleError("Nieuwe paswoord is leeg!");
            return false;
        }
        
        $user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }
        
        $pwd = trim($_POST['opw']);
        
        if($user_rec['pw'] != md5($pwd))
        {
            $this->HandleError("The old pw does not match!");
            return false;
        }
        $npw = trim($_POST['npw']);
        
        if(!$this->ChangepwInDB($user_rec, $npw))
        {
            return false;
        }
        return true;
    }
    
    //-------Public Helper functions -------------
    function GetSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }    
    
    function SafeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return'';
        }
        return htmlentities($_POST[$value_name]);
    }
    
    function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }
    
    function GetSpamTrapInputName()
    {
        return 'sp'.md5('KHGdnbvsgst'.$this->rand_key);
    }
    
    function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = nl2br(htmlentities($this->error_message));
        return $errormsg;
    }    
    //-------Private Helper functions-----------
    
    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }
    
    function HandleDBError($err)
    {
        $this->HandleError($err."\r\n mysqlierror:".mysql_error());
    }
    
    function GetFromAddress()
    {
		$this->from_address = 'info@gewoonoefenen.nl';
        if(!empty($this->from_address))
        {
            return $this->from_address;
        }

        $host = $_SERVER['SERVER_NAME'];

        $from ="nobody@$host";
        return $from;
    } 
    
    function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }
    
    function CheckLoginInDB($us,$pw)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }          
        $us = $this->SanitizeForSQL($us);
        $pwdmd5 = md5($pw);
        $qry = "Select vn, an, em from $this->tablename where us='$us' and pw='$pwdmd5' and uscc='y'";
        
        $result = mysqli_query($this->connection,$qry);
        
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Inloggen niet gelukt. De usernaam en het paswoord passen niet bij elkaar");
            return false;
        }
        
        $row = mysqli_fetch_assoc($result);
        
        
        $_SESSION['vn']  = $row['vn'];
        $_SESSION['an']  = $row['an'];
        $_SESSION['em'] = $row['em'];
        
        return true;
    }
    
    function UpdateDBRecForConfirmation(&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }   
        $uscc = $this->SanitizeForSQL($_GET['code']);
        
        $result = mysql_query("Select vn, an, em from $this->tablename where uscc='$uscc'",$this->connection);   
        if(!$result || mysql_num_rows($result) <= 0)
        {
            $this->HandleError("Verkeerde bevestigingscode.");
            return false;
        }
        $row = mysql_fetch_assoc($result);
        $user_rec['vn'] = $row['vn'];
        $user_rec['an'] = $row['an'];
        $user_rec['em']= $row['em'];
        
        $qry = "Update $this->tablename Set uscc='y' Where  uscc='$uscc'";
        
		if(!mysqli_query( $this->connection, $qry))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$qry");
            return false;
        }      
        return true;
    }
    
    function ResetUserpwInDB($user_rec)
    {
        $npw = substr(md5(uniqid()),0,10);
        
        if(false == $this->ChangepwInDB($user_rec,$npw))
        {
            return false;
        }
        return $npw;
    }
    
    function ChangepwInDB($user_rec, $npw)
    {
        $npw = $this->SanitizeForSQL($npw);
        
        $qry = "Update $this->tablename Set pw='".md5($npw)."' Where  usky=".$user_rec['usky']."";
        
        if(!mysqli_query( $this->connection, $qry ))
        {
            $this->HandleDBError("Error updating the pw \nquery:$qry");
            return false;
        }     
        return true;
    }
    
    function GetUserFromEmail($em,&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }   
        $em = $this->SanitizeForSQL($em);
     //   $var = $em	;
		//echo "<script> alert('1voor>".$var."<na'); </script>";
 
        $result = mysqli_query($this->connection,"Select * from $this->tablename where em='$em'");  

        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("We kennen geen gebruiker met het emailadres: $em");
            return false;
        }
        $user_rec = mysqli_fetch_assoc($result);

        
        return true;
    }
    
    function SendUserWelcomeEmail(&$user_rec)
    {
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($user_rec['em'],$user_rec['vn']);
        
        $mailer->Subject = "Welkom bij ".$this->sitename;

        $mailer->From = $this->GetFromAddress();        
        
        $mailer->Body ="Hallo ".$user_rec['vn']."\r\n\r\n".
        "Welkom! Je registratie bij ".$this->sitename." is voltooid.\r\n".
        "\r\n".
        "Met vriendelijke groet,\r\n".
        "Webmaster\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            $this->HandleError("Welkoms email niet verzonden.");
            return false;
        }
        return true;
    }
    
    function SendAdminIntimationOnRegComplete(&$user_rec)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "Registratie klaar: ".$user_rec['vn'];

        $mailer->From = $this->GetFromAddress();         
        
        $mailer->Body ="Er is een nieuwe gebruiker geregistreerd op ".$this->sitename."\r\n".
        "Voornaam: ".$user_rec['vn']."\r\n".
        "Email adres: ".$user_rec['em']."\r\n";
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function GetResetpwCode($em)
    {
       return substr(md5($em.$this->sitename.$this->rand_key),0,10);
    }
    
    function SendResetpwLink($user_rec)
    {
	//		$var = $user_rec['em'];
	//	echo "<script> alert('1voor>".$var."<na'); </script>";
  
        $em = $user_rec['em'];
        
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($em,$user_rec['vn']);
        
        $mailer->Subject = "Je paswoord terugzetverzoek op ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
        
        $link = $this->GetAbsoluteURLFolder().
                '/resetpwd.php?em='.
                urlencode($em).'&code='.
                urlencode($this->GetResetpwCode($em));
				
        $mailer->Body ="Hallo ".$user_rec['vn']."\r\n\r\n".
        "Je hebt gevraagd om je paswoord te resetten op ".$this->sitename."\r\n".
        "Klik om het verzoek te voltooien op de link : \r\n".$link."\r\n".
        "Met vriendelijke groet,\r\n".
        "Webmaster\r\n".		
        $this->sitename;
    
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function SendNewpw($user_rec, $npw)
    {
        $em = $user_rec['em'];
        
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($em,$user_rec['vn']);
        
        $mailer->Subject = "Je nieuwe aanlog gegevens voor ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
        
        $mailer->Body ="Hallo ".$user_rec['vn']."\r\n\r\n".
        "Je paswoord is opnieuw gezet ".
        "Je login gegevens:\r\n".
        "usernaam:".$user_rec['us']."\r\n".
        "paswoord:$npw\r\n".
        "\r\n".
        "Je kunt aanloggen op: ".$this->GetAbsoluteURLFolder()."/login.php\r\n".
        "\r\n".
        "Met vriendelijke groeten,\r\n".
        "Webmaster\r\n".
        $this->sitename;
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }    
    
    function ValidateRegistrationSubmission()
    {
        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->HandleError("Automated submission prevention: case 2 failed");
            return false;
        }
        
        $validator = new FormValidator();
        $validator->addValidation("vn","req","Geef voornaam");
        $validator->addValidation("an","req","Geef achternaam");
        $validator->addValidation("em","email","Geef een correct email adres");
        $validator->addValidation("em","req","Geef email adres");
        $validator->addValidation("us","req","Geef een usernaam");
        $validator->addValidation("pw","req","Geef een pw");
        
        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= $inpname.':'.$inp_err."\n";
            }
            $this->HandleError($error);
            return false;
        }        
        return true;
    }
    
    function CollectRegistrationSubmission(&$formvars)
    {
        $formvars['vn'] = $this->Sanitize($_POST['vn']);
        $formvars['an'] = $this->Sanitize($_POST['an']);
        $formvars['em'] = $this->Sanitize($_POST['em']);
        $formvars['us'] = $this->Sanitize($_POST['us']);
        $formvars['pw'] = $this->Sanitize($_POST['pw']);
    }
    
    function SendUserConfirmationEmail(&$formvars)
    {
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($formvars['em'],$formvars['vn']);
        
        $mailer->Subject = "Je registratie bij ".$this->sitename;

        $mailer->From = $this->GetFromAddress();        
        
        $uscc = $formvars['uscc'];
        
        $confirm_url = $this->GetAbsoluteURLFolder().'/confirmreg.php?code='.$uscc;
        
        $mailer->Body ="Hallo ".$formvars['vn']."\r\n\r\n".
        "Bedankt voor je registratie bij ".$this->sitename."\r\n".
        "Klik op de link hieronder om je registratie te voltooien.\r\n".
        "$confirm_url\r\n".
        "\r\n".
        "Met vriendelijke groet,\r\n".
        "Webmaster\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            $this->HandleError("Registratie bevestigings email versturen niet gelukt.");
            return false;
        }
        return true;
    }
    function GetAbsoluteURLFolder()
    {
        $scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
        $scriptFolder .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
        return $scriptFolder;
    }
    
    function SendAdminIntimationEmail(&$formvars)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "Nieuwe registratie: ".$formvars['vn'];

        $mailer->From = $this->GetFromAddress();         
        
        $mailer->Body ="Een nieuwe gebruiker is geregistreerd op ".$this->sitename."\r\n".
        "Voornaam: ".$formvars['vn']."\r\n".
        "Achternaam: ".$formvars['an']."\r\n".
        "Email adres: ".$formvars['em']."\r\n".
        "Userid: ".$formvars['us'];
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function SaveToDatabase(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        if(!$this->Ensuretable())
        {
            return false;
        }
        if(!$this->IsFieldUnique($formvars,'em'))
        {
            $this->HandleError("Dit emailadres is al bekend");
            return false;
        }
        
        if(!$this->IsFieldUnique($formvars,'us'))
        {
            $this->HandleError("Deze useernaam is al in gebruik. Kies een andere usernaam");
            return false;
        }        
        if(!$this->InsertIntoDB($formvars))
        {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
        return true;
    }
    
    function IsFieldUnique($formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select us from $this->tablename where $fieldname='".$field_val."'";
        $result = mysqli_query($this->connection,$qry);   
        if($result && mysqli_num_rows($result) > 0)
        {
            return false;
        }
        return true;
    }
    
    function DBLogin()
    {

        $this->connection = mysqli_connect($this->db_host,$this->us,$this->pwd,$this->database);

        if(!$this->connection)
        {   
            $this->HandleDBError("Database Login failed! Please make sure that the DB login credentials provided are correct");
            return false;
        }
        if(!mysqli_select_db($this->connection, $this->database))
        {
            $this->HandleDBError('Failed to select database: '.$this->database.' Please make sure that the database name provided is correct');
            return false;
        }
        if(!mysqli_query($this->connection,"SET NAMES 'UTF8'"))
       {
            $this->HandleDBError('Error setting utf8 encoding');
            return false;
        }
        return true;
    }    
    
    function Ensuretable()
    {
        $result = mysqli_query($this->connection,"SHOW COLUMNS FROM $this->tablename");   
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return $this->CreateTable();
        }
        return true;
    }
    
    function CreateTable()
    {
        $qry = "Create Table $this->tablename (".
                "usky INT NOT NULL AUTO_INCREMENT ,".
                "vn VARCHAR( 128 ) NOT NULL ,".
                "an VARCHAR( 128 ) NOT NULL ,".
                "em VARCHAR( 64 ) NOT NULL ,".
                "us VARCHAR( 16 ) NOT NULL ,".
                "pw VARCHAR( 32 ) NOT NULL ,".
                "uscc VARCHAR(32) ,".
                "PRIMARY KEY ( usky )".
                ")";
                
        if(!mysqli_query($this->connection,$qry))
        {
            $this->HandleDBError("Error creating the table \nquery was\n $qry");
            return false;
        }
        return true;
    }
    
    function InsertIntoDB(&$formvars)
    {
    
        $uscc = $this->MakeConfirmationMd5($formvars['em']);
        
        $formvars['uscc'] = $uscc;
        
        $insert_query = 'insert into '.$this->tablename.'(
                vn,
                an,
                em,
                us,
                pw,
                uscc
                )
                values
                (
                "' . $this->SanitizeForSQL($formvars['vn']) . '",
                "' . $this->SanitizeForSQL($formvars['an']) . '",
                "' . $this->SanitizeForSQL($formvars['em']) . '",
                "' . $this->SanitizeForSQL($formvars['us']) . '",
                "' . md5($formvars['pw']) . '",
                "' . $uscc . '"
                )';      
        if(!mysql_query( $insert_query ,$this->connection))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
            return false;
        }        
        return true;
    }
    function MakeConfirmationMd5($em)
    {
        $randno1 = rand();
        $randno2 = rand();
        return md5($em.$this->rand_key.$randno1.''.$randno2);
    }
    function SanitizeForSQL($str)
    {
        if( function_exists( "mysqli_real_escape_string" ) )
        {
              $ret_str = mysqli_real_escape_string($this->connection,$str );
         }
        else
        {
              $ret_str = addslashes( $str );
        }
        return $ret_str;
    }
    
 /*
    Sanitize() function removes any potential threat from the
    data submitted. Prevents email injections or any other hacker attempts.
    if $remove_nl is true, newline chracters are removed from the input.
    */
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }    
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }    
}
?>
<?PHP
require_once("./include/membersite_config.php");

if(isset($_POST['submitted']))
{
   if($fgmembersite->RegisterUser())
   {
        $fgmembersite->RedirectToURL("thank-you.html");
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Registreer</title>
    <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
    <script src="scripts/pwdwidget.js" type="text/javascript"></script>      
</head>
<body>

<!-- Form Code Start -->
<div id='fg_membersite'>
<form id='register' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Registreer</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<div class='short_explanation'>* verplicht invullen</div>
<input type='text'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />

<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
<div class='container'>
    <label for='vn' >Voornaam*: </label><br/>
    <input type='text' name='vn' id='vn' value='<?php echo $fgmembersite->SafeDisplay('vn') ?>' maxlength="50" /><br/>
    <span id='register_name_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='an' >Achternaam*: </label><br/>
    <input type='text' name='an' id='an' value='<?php echo $fgmembersite->SafeDisplay('an') ?>' maxlength="50" /><br/>
    <span id='register_name_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='em' >Email Adres*:</label><br/>
    <input type='text' name='em' id='em' value='<?php echo $fgmembersite->SafeDisplay('em') ?>' maxlength="50" /><br/>
    <span id='register_email_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='us' >Usernaam*:</label><br/>
    <input type='text' name='us' id='us' value='<?php echo $fgmembersite->SafeDisplay('us') ?>' maxlength="50" /><br/>
    <span id='register_us_errorloc' class='error'></span>
</div>
<div class='container' style='height:80px;'>
    <label for='pw' >Paswoord*:</label><br/>
    <div class='pwdwidgetdiv' id='thepwddiv' ></div>
    <noscript>
    <input type='password' name='pw' id='pw' maxlength="50" />
    </noscript>     
    <div id='register_pw_errorloc' class='error' style='clear:both'></div>
</div>

<div class='container'>
    <input type='submit' name='Submit' value='Verstuur' />
</div>

</fieldset>
</form>
<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

<script type='text/javascript'>
// <![CDATA[
    var pwdwidget = new pwWidget('thepwddiv','pw');
    pwdwidget.MakePWDWidget();
    
    var frmvalidator  = new Validator("register");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
    frmvalidator.addValidation("vn","req","Geef voornaam");
    frmvalidator.addValidation("an","req","Geef achternaam");
    frmvalidator.addValidation("em","req","Geef email adres");
    frmvalidator.addValidation("em","em","Geef een correct email adres");
    frmvalidator.addValidation("us","req","Geef een usernaam");    
    frmvalidator.addValidation("pw","req","Geef een paswoord");

// ]]>
</script>

<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>
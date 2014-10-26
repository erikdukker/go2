<?PHP
require_once("./include/membersite_config.php");

if(isset($_POST['submitted']))
{
   if($fgmembersite->Login())
   {
        $fgmembersite->RedirectToURL("../index.php");
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Login</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>

<!-- Form Code Start -->
<div id='fg_membersite'>
<form id='login' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Aanmelden</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<div class='short_explanation'>* verplichte velden</div>

<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
<div class='container'>
    <label for='us' >usernaam*:</label><br/>
    <input type='text' name='us' id='us' value='<?php echo $fgmembersite->SafeDisplay('us') ?>' maxlength="50" /><br/>
    <span id='login_us_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='pw' >paswoord*:</label><br/>
    <input type='pw' name='pw' id='pw' maxlength="50" /><br/>
    <span id='login_pw_errorloc' class='error'></span>
</div>

<div class='container'>
    <input type='submit' name='Submit' value='Verstuur' />
</div>
<div class='short_explanation'><a href='register.php'>Nog niet geregistreerd?</a></div>
<div class='short_explanation'><a href='reset-pwd-req.php'>Paswoord vergeten?</a></div>
</fieldset>
</form>
<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

<script type='text/javascript'>
// <![CDATA[

    var frmvalidator  = new Validator("login");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();

    frmvalidator.addValidation("us","req","Geef usernaam");
    
    frmvalidator.addValidation("pw","req","Geef paswoord");

// ]]>
</script>
</div>
<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>
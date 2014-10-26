<?PHP
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}

if(isset($_POST['submitted']))
{
   if($fgmembersite->Changepw())
   {
        $fgmembersite->RedirectToURL("changed-pwd.html");
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Paswoord veranderen</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
      <link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
      <script src="scripts/pwdwidget.js" type="text/javascript"></script>       
</head>
<body>

<!-- Form Code Start -->
<div id='fg_membersite'>
<form id='changepwd' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Paswoord veranderen</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<div class='short_explanation'>* verplicht veld</div>

<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
<div class='container'>
    <label for='opw' >Oud paswoord*:</label><br/>
    <div class='pwdwidgetdiv' id='opwdiv' ></div><br/>
    <noscript>
    <input type='pw' name='opw' id='opw' maxlength="50" />
    </noscript>    
    <span id='changepwd_opw_errorloc' class='error'></span>
</div>

<div class='container'>
    <label for='npw' >Nieuw paswoord*:</label><br/>
    <div class='pwdwidgetdiv' id='npwdiv' ></div>
    <noscript>
    <input type='pw' name='npw' id='npw' maxlength="50" /><br/>
    </noscript>
    <span id='changepwd_npw_errorloc' class='error'></span>
</div>

<br/><br/><br/>
<div class='container'>
    <input type='submit' name='Submit' value='Verstuur' />
</div>

</fieldset>
</form>
<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

<script type='text/javascript'>
// <![CDATA[
    var pwdwidget = new pwWidget('opwdiv','opw');
    pwdwidget.enableGenerate = false;
    pwdwidget.enableShowStrength=false;
    pwdwidget.enableShowStrengthStr =false;
    pwdwidget.MakePWDWidget();
    
    var pwdwidget = new pwWidget('npwdiv','npw');
    pwdwidget.MakePWDWidget();
    
    
    var frmvalidator  = new Validator("changepwd");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();

    frmvalidator.addValidation("opw","req","Geef uw oude paswoord");
    
    frmvalidator.addValidation("npw","req","Geef uw nieuwe paswoord");

// ]]>
</script>
</div>
<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>
<?PHP
require_once("./include/membersite_config.php");

$emailsent = false;
if(isset($_POST['submitted']))
{
   if($fgmembersite->EmailResetpwLink())
   {
        $fgmembersite->RedirectToURL("reset-pwd-link-sent.html");
        exit;
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Paswoord terugzet verzoek</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>
<!-- Form Code Start -->
<div id='fg_membersite'>
<form id='resetreq' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Reset pw</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<div class='short_explanation'>* verplichte velden</div>

<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
<div class='container'>
    <label for='em' >Je email adres*:</label><br/>
    <input type='text' name='em' id='em' value='<?php echo $fgmembersite->SafeDisplay('em') ?>' maxlength="50" /><br/>
    <span id='resetreq_em_errorloc' class='error'></span>
</div>
<div class='short_explanation'>Er wordt een link om je paswoord terug te zetten gestuurd naar je emailadres</div>
<div class='container'>
    <input type='submit' name='Submit' value='Verstuur' />
</div>

</fieldset>
</form>
<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

<script type='text/javascript'>
// <![CDATA[

    var frmvalidator  = new Validator("resetreq");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();

    frmvalidator.addValidation("em","req","Geef het emailadres dat u heeft opgegeven hebt bij het registreren");
    frmvalidator.addValidation("em","email","Geef het emailadres dat u heeft opgegeven hebt bij het registreren");

// ]]>
</script>

</div>
<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>
<?php
/////////////////////////////////////////////////////////////////// XHE WRAPER /////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////// Class's

if (!defined("___XHE___"))
{
// базовый общий дл€ всех
include("Objects/xhe_base.php");
// базовый DOM
include("Objects/DOM/xhe_base_dom.php");

// дл€ совместимости с предыдущими верси€ми
include("Objects/DOM/Compatible/xhe_anchor_compatible.php");
include("Objects/DOM/Compatible/xhe_body_compatible.php");
include("Objects/DOM/Compatible/xhe_button_compatible.php");
include("Objects/DOM/Compatible/xhe_checkbutton_compatible.php");
include("Objects/DOM/Compatible/xhe_element_compatible.php");
include("Objects/DOM/Compatible/xhe_form_compatible.php");
include("Objects/DOM/Compatible/xhe_frame_compatible.php");
include("Objects/DOM/Compatible/xhe_image_compatible.php");
include("Objects/DOM/Compatible/xhe_input_compatible.php");
include("Objects/DOM/Compatible/xhe_inputbutton_compatible.php");
include("Objects/DOM/Compatible/xhe_inputfile_compatible.php");
include("Objects/DOM/Compatible/xhe_inputimage_compatible.php");
include("Objects/DOM/Compatible/xhe_radiobox_compatible.php");
include("Objects/DOM/Compatible/xhe_scriptelement_compatible.php");
include("Objects/DOM/Compatible/xhe_selectelement_compatible.php");
include("Objects/DOM/Compatible/xhe_table_compatible.php");
include("Objects/DOM/Compatible/xhe_textarea_compatible.php");

// вспомогательные
include("Objects/DOM/xhe_anticaptcha.php");
include("Objects/DOM/xhe_captchabot.php");

// DOM (output)
include("Objects/DOM/xhe_anchor.php");
include("Objects/DOM/xhe_image.php");
include("Objects/DOM/xhe_inputbutton.php");
include("Objects/DOM/xhe_button.php");
include("Objects/DOM/xhe_selectelement.php");
include("Objects/DOM/xhe_frame.php");
include("Objects/DOM/xhe_form.php");
include("Objects/DOM/xhe_scriptelement.php");

// DOM (input)
include("Objects/DOM/xhe_input.php");
include("Objects/DOM/xhe_inputfile.php");
include("Objects/DOM/xhe_textarea.php");
include("Objects/DOM/xhe_checkbutton.php");
include("Objects/DOM/xhe_radiobox.php");
include("Objects/DOM/xhe_table.php");
include("Objects/DOM/xhe_body.php");
include("Objects/DOM/xhe_inputimage.php");
include("Objects/DOM/xhe_element.php");

// System
include("Objects/System/xhe_mouse.php");
include("Objects/System/xhe_sound.php");
include("Objects/System/xhe_keyboard.php");
include("Objects/System/xhe_textfile.php");
include("Objects/System/xhe_file.php");
include("Objects/System/xhe_clipboard.php");
include("Objects/System/xhe_folder.php");
include("Objects/System/xhe_excel.php");
include("Objects/System/xhe_msword.php");
include("Objects/System/xhe_firebird.php");



// Web
include("Objects/Web/xhe_browser.php");
include("Objects/Web/xhe_webpage.php");
include("Objects/Web/xhe_seo.php");
include("Objects/Web/xhe_raw.php");
include("Objects/Web/xhe_connection.php");
include("Objects/Web/xhe_mail.php");
include("Objects/Web/xhe_ftp.php");
include("Objects/Web/xhe_submitter.php");
include("Objects/Web/xhe_proxycheker.php");


// Window
include("Objects/Window/xhe_application.php");
include("Objects/Window/xhe_debug.php");
include("Objects/Window/xhe_windowsshell.php");
include("Objects/Window/xhe_window.php");

/////////////////////////////////////////////////////////////////////////// Objects

// XWeb human emulator host
if (empty($xhe_host) or $xhe_host=="")
  $xhe_host ="127.0.0.1:7010"; 
// XWeb human emulator password
if (empty($server_password) or $server_password=="")
  $server_password="";

$anticapcha= new XHEAnticapcha("antigate.com");
$captchabot= new XHECaptchabot();

// create Window objects
$app          = new XHEApplication($xhe_host,$server_password);
$windows      = new XHEWindowsShell($xhe_host,$server_password);
$window       = new XHEWindow($xhe_host,$server_password);
$mouse        = new XHEMouse($xhe_host,$server_password);
$sound        = new XHESound($xhe_host,$server_password);
$debug        = new XHEDebug($xhe_host,$server_password);
$keyboard     = new XHEKeyboard($xhe_host,$server_password);
$clipboard    = new XHEClipboard($xhe_host,$server_password);
$textfile     = new XHETextFile($xhe_host,$server_password);
$file_os      = new XHEFile_os($xhe_host,$server_password);
$folder       = new XHEFolder($xhe_host,$server_password);
$table        = new XHETable($xhe_host,$server_password);
$msword       = new XHEMsWord($xhe_host,$server_password);
$excel        = new XHEExcel($xhe_host,$server_password);
$firebird    = new XHEFirebird($xhe_host,$server_password);



// create Web objects
$browser      = new XHEBrowser($xhe_host,$server_password);
$webpage      = new XHEWebPage($xhe_host,$server_password);
$raw          = new XHERaw($xhe_host,$server_password);
$seo          = new XHESEO($xhe_host,$server_password);
$connection   = new XHEConnection($xhe_host,$server_password);
$mail         = new XHEMail($xhe_host,$server_password);
$ftp          = new XHEFTP($xhe_host,$server_password);
$submitter    = new XHESubmitter($xhe_host,$server_password);
$proxycheker  = new XHEProxyCheker($xhe_host,$server_password);


$frame        = new XHEFrame($xhe_host,$server_password);
$form         = new XHEForm($xhe_host,$server_password);
$body         = new XHEBody($xhe_host,$server_password);

// create Dom (output) objects
$anchor       = new XHEAnchor($xhe_host,$server_password);
$image        = new XHEImage($xhe_host,$server_password);
$button       = new XHEInputButton($xhe_host,$server_password);
$btn          = new XHEButton($xhe_host,$server_password);
$listbox      = new XHESelectElement($xhe_host,$server_password);
$script       = new XHEScriptElement($xhe_host,$server_password);

// create Dom (input) objects
$input        = new XHEInput($xhe_host,$server_password);
$inputfile    = new XHEInputFile($xhe_host,$server_password);
$textarea     = new XHETextArea($xhe_host,$server_password);
$checkbox     = new XHECheckButton($xhe_host,$server_password);
$radiobox     = new XHERadioButton($xhe_host,$server_password);
$inputimage   = new XHEInputImage($xhe_host,$server_password);
$element      = new XHEElement($xhe_host,$server_password);
}

define ("___XHE___", "DEFINED");
$bClosePHPIfNotConnected=false;

////////////////////////////////////////////////////////////////////////////////////
?>

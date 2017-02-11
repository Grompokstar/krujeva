<?php

$xhe_host ="127.0.0.1:7010";

// The following code is required to properly run XWeb Human Emulator
require("../Templates/xweb_human_emulator.php");

// navigate to google
$browser->navigate("http://www.google.com");
// wait on browser
$browser->wait_for(30,1);

// Quit
$app->quit();
?>
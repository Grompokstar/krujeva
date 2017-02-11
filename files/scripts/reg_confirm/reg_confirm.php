<?php

//$xhe_host ="127.0.0.1:7011";
require("Templates/xweb_human_emulator.php");
require_once("functions.php");

$email="vanya.testscript@mail.ru";
$mail_login="vanya.testscript";
$mail_password="q1w2e3r4t5";

$dbg = true;



$browser->navigate("ya.ru");

$body = $webpage->get_element_innerHtml_by_name('body');

var_dump($submitter->generate_random_name("RU", "man"));
var_dump('we');
exit();


$anchor->click_by_inner_text("�����������",true);
$input->click_by_name("name");
$input->set_focus_by_name("name");
$input->set_value_by_name("name",$submitter->generate_random_name("RU","man"));
$input->click_by_name("email");
$input->set_focus_by_name("email");
$input->set_value_by_name("email",$email);
$input->click_by_number(2);
$input->set_value_by_number(2,$submitter->generate_random_city("RU"));
$listbox->select_random_by_name("birthday");
$listbox->select_random_by_name("birthmonth");
$listbox->select_random_by_name("birthyear");
$anchor->click_by_inner_text("� ������",true);
$button->click_by_inner_text("������� ������",true);

$browser->navigate("mail.ru");
$anchor->click_by_inner_text("�����",true);
$input->set_value_by_name("Login",$mail_login);
$input->set_value_by_name("Password",$mail_password);
$button->click_by_name("EnterBtn");
sleep(2);
$element->click_by_inner_text("\"���� ��������� �����\" ",true);
$dt=$webpage->get_body_inter_prefix_all("face=Arial><B","</B>");



$app->quit();
?>
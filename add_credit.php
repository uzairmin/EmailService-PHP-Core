<?php
require "main.php";

header('Content-Type:application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content_Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

$data = json_decode(file_get_contents("php://input"),true);
$email = $data['email'];
$token = $data['token'];
$amount = $data['amount'];
$data1 =  ['card[number]' =>"3822424242424242",'card[exp_month]' => "10",'card[exp_year]' => "2028",'card[cvc]' => "123"];
$main = new Mainn();
$stripTokenResponse = $main->getStripeToke($data1);
$stripTokenRes = json_decode($stripTokenResponse);
$stripToken =  $stripTokenRes->id;
$addBalance = $main->charge($stripToken,$amount);

$main = new Mainn();
$main->insertAmount($amount,$email);
?>
<?php
ini_set('display_errors', 1);
require "config.php";

/* check if userid is set and is valid based on the regex we have */
if(!isset($_POST['user_id']))
{
   die(json_encode(array("success"=>false,"message"=>"userId not set")));
}

if(!preg_match("/^[0-9]+$/",$_POST['user_id'])){
    die(json_encode(array("success"=>false,"message"=>"Invalid userId")));
}

/* check if page is set in request body */
if(!isset($_POST['page']))
{
   die(json_encode(array("success"=>false,"message"=>"page not set")));
}

$token = create_custom_token(json_encode($_POST));

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => URL.'/listUserTransactions',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'user_id='.$_POST['user_id'].'&page='.$_POST['page'],
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$token,
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

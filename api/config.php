<?php
require "vendor/autoload.php";


use \Firebase\JWT\JWT;


define('ISSUER',"042464b0-b05b-4a5d-b05bf96b-338feaede999");

define('AUDIENCE',"da2d46b9-de76-498e-8746-471e8dd3d120");
define('EXPIRATION',60);
define('SUBJECT','dmme-api-request');
define('ALGORITHM','RS256');//RS256
define('URL','https://backend.dmme.cash');


function create_custom_token($body) {
    // print_r($body);
    $private_key = openssl_pkey_get_private(file_get_contents('utils/dmme.pem'));  

    if($body==NULL)
    {
     $payload = array(
      'iss' => ISSUER,
      'aud' => AUDIENCE,
      'exp' => time() + EXPIRATION,
      'sub' => SUBJECT
    );
   }
   else
   {
     $payload = array(
      'iss' => ISSUER,
      'aud' => AUDIENCE,
      'exp' => time() + EXPIRATION,
      'sub' => SUBJECT,
      'rbh' => base64_encode(hash('sha256', $body, true))
    );
   }
   return JWT::encode($payload, $private_key, ALGORITHM);
  }
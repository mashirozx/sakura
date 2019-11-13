<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$image = file_get_contents("test.jpg");

function Imgur_API($image) {
  $client_id = "98cd21cdfc58130";
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://api.mashiro.top/imgur-api/3/image');
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
  curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($image)));
  
  $reply = curl_exec($ch);
  curl_close($ch);
  
  $reply = json_decode($reply);
  var_dump($reply);
  printf('<img height="180" src="%s" >', $reply->data->link);
  $res = $reply->data->link;
  $res = 'https://images.weserv.nl/?url='.$res;
  echo $res;
  printf('<img height="180" src="%s" >', $res);
}

Imgur_API($image);
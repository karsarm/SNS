<?php

$access_token = 'S2M3TIomRD4UpsklOVHcM1yE1IvhWxOXgo946RGxQCaS4tkobVLwhonAJdLJGjXF1tpoCS+0ZvIGJFivBN4cLUz/1hZ16e2P1xuWTKnpzT0AOFlRmBwlPDc3/PckKJEJec4a66/hL8DRSyTAo/V5cAdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v2/bot/message/reply';

// receive json data from line webhook
$raw = file_get_contents('php://input');
$receive = json_decode($raw, true);

// parse received events
$event = $receive['events'][0];
$reply_token  = $event['replyToken'];
$message_text = $event['message']['text'];


// build request headers
$headers = array('Content-Type: application/json',
                 'Authorization: Bearer ' . $access_token);

// build request body
$message = array('type' => 'text',
                 'text' => $message_text);

$body = json_encode(array('replyToken' => $reply_token,
                          'messages'   => array($message)));


// post json with curl
$options = array(CURLOPT_URL            => $url,
                 CURLOPT_CUSTOMREQUEST  => 'POST',
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_HTTPHEADER     => $headers,
                 CURLOPT_POSTFIELDS     => $body);

$curl = curl_init();
curl_setopt_array($curl, $options);
curl_exec($curl);
curl_close($curl);
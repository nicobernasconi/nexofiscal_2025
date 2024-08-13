<?php

require '../vendor/autoload.php' ;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Psr7\Request;
$client = new Client();
$headers = [
  'Authorization' => 'Apisecret YkbBEmLp1YGa5VubWIHfx7B7BLOpd9qYu3G5CWm2jstpN6CwMz20bTc5dsItkXCV'
];
$request = new Request('POST', 'https://dev.vdocipher.com/api/videos/d6a614bae4a74f17869687e56346a52f/otp', $headers);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();

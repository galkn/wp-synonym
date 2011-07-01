<?php

$word = $_GET['word'];

$random_index = rand(0,1);
$api_keys = array('c1ea368f9c750c82b10dac6d43becd7b','5e8cd6f5493e5c0074cb77102774b9e0');
$url = 'http://words.bighugelabs.com/api/2/'.$api_keys[$random_index].'/'.$word.'/xml';

$xml_response = file($url);
$xml_response = $xml_response[0];

echo $xml_response;


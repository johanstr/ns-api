<?php

$username = 'je eigen username van de api (mail)';
$password = 'je eigen password voor de api';
$url = 'http://webservices.ns.nl/ns-api-stations-v2';

// Eerst initialeren we een cURL object
$ch = curl_init();

// Nu zetten we een aantal opties in de header, die aan elke verbinding met een webserver voorafgaat
// We plaatsen daar de URL van de API
// Daarna geven we aan dat we een normale return transfer willen, controle terug naar ons
// Daarna geven aan wat de gebruikersnaam en het wachtwoord is voor het gebruiken van de api
// Tot slot geven we nog even
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);


$output = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);


$simpleXml = simplexml_load_string($output);

echo $simpleXml->Station[0]->Code . ' - ' .
    $simpleXml->Station[0]->Namen->Kort . '<br />';

$jsonOutput = json_encode($simpleXml);

echo '<hr /><pre>';
echo print_r($jsonOutput);
echo '</pre>';

$jsonOutput = json_decode($jsonOutput);

echo '<hr /><pre>';
echo print_r($jsonOutput);
echo '</pre>';


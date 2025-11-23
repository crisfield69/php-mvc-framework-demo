<?php

require_once __DIR__ . '/../../config.php';

$curl = curl_init(API_EXPOSANTS_URL);
curl_setopt($curl, CURLOPT_CAINFO, '../cert.cer');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Autorisation' => API_KEY]);
$exposantData = curl_exec($curl);

if( $exposantData === false) {
    var_dump(curl_error($curl));
}
else {    
    $exposant = json_decode($exposantData)[0];
    var_dump($exposant);  
    $standData = getStang($exposant->stand);
    $stand = json_decode($standData);
    var_dump($stand);
}



function getStang($standUrl)
{
    if(empty($standUrl)) return null;
    $standUrl = str_replace('http://', 'https://', $standUrl);
    $curl = curl_init($standUrl);
    curl_setopt($curl, CURLOPT_CAINFO, '../cert.cer');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Autorisation' => API_KEY]);
    $data = curl_exec($curl);
    return $data;
}
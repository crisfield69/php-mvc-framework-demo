<?php

require_once __DIR__ . '/../../config.php';

$curl = curl_init(API_EXPOSANTS_URL);
curl_setopt($curl, CURLOPT_CAINFO, '../cert.cer');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Autorisation' => API_KEY]);
$data = curl_exec($curl);

if( $data === false) {
    var_dump(curl_error($curl));
}
else {    
    $data = json_decode($data);
    foreach($data as $exposant) {        
        var_dump($exposant);        
        $standData = getStang($exposant->stand);
        $stand = json_decode($standData);
        var_dump($stand);
    }
}

function getStang($standUrl)
{
    $standUrl = str_replace('http://', 'https://', $standUrl);
    $curl = curl_init($standUrl);
    curl_setopt($curl, CURLOPT_CAINFO, '../cert.cer');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Autorisation' => API_KEY]);
    $data = curl_exec($curl);
    return $data;
}
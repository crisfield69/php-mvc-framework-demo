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
    echo '<pre>';
    var_dump($data);
    echo '</pre>';   
}
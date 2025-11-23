<?php 

require_once 'config.php';
require_once 'connect.php';
require_once 'util.php';

$eol = ', ';

$curl = curl_init(API_EXPOSANTS_URL);
curl_setopt($curl, CURLOPT_CAINFO, 'cert.cer');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Autorisation' => API_KEY]);
$data = curl_exec($curl);

if($data === false) {

    echo json_encode([
        'result'    =>  'error', 
        'action'    => '',
        'message'   =>  'Erreur lors de l\'insertion des exposants dans la table.'
    ]);

}
else {
    
    $query = 'TRUNCATE '.EXPOSANTS_TABLE;
    mysqli_query($connexion, $query);
    $query = 'ALTER TABLE '.EXPOSANTS_TABLE.' AUTO_INCREMENT = 1';
    mysqli_query($connexion, $query);

    $data = json_decode($data, true);
    foreach($data as $exposant)
    {  
        if($exposant['event_edition'] !== ANNEE_COURANTE) continue;
        
        $query = 'INSERT INTO '.EXPOSANTS_TABLE.' SET ';
        foreach(EXPOSANTS_TABLE_COLUMNS as $columnName) {

            if(in_array($columnName, ['products_informations', 'alpha', 'services_informations'])) {
                continue;
            }

            $columnValue = $exposant[$columnName];

            if(empty($columnValue)) {
                $query .= $columnName .'=""'.$eol;
            }
            else{
                switch($columnName) {

                    case 'specialties':
                    case 'labels':
                        
                        $stringValue = '';
                        foreach($columnValue as $value) {
                            $stringValue .= mb_ucfirst(getEnd($value), 'UTF-8') . '|||';
                        }
                        $stringValue = substr($stringValue, 0, -3);
                        $query .= $columnName .'='. escape($stringValue).$eol;

                    break;

                    case 'participant_images':
                    case 'products_images':

                        $images = [];
                        foreach($columnValue as $image) {
                            $images[] = $image['url'];
                        }
                        
                        $images = array_map(function ($array) {return $array['url'];}, $columnValue);
                        $query .= $columnName .'='. escape(implode('|||', $images)).$eol;

                    break;
                    
                    default:
                        $query .= $columnName .'='. escape(removeEmoticons($columnValue)).$eol;
                    break;
                }
            }
        }

        $standData = getStang($exposant['stand']);
        $stand = json_decode($standData, true);
        $query .= 'products_informations='.escape($stand['sold_products_informations']).$eol;
        $query .= 'alpha='.escape($stand['alpha']).$eol;
        $query .= 'services_informations='.escape($stand['provided_services_informations']).$eol;
        

        $query = substr($query, 0, -2);
        mysqli_query($connexion, $query);
    }
    
    require_once 'view.insertExposants.php';

}

curl_close($curl);


function removeEmoticons($string) {

    // Emoticons
    $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clear_string = preg_replace($regex_emoticons, '', $string);

    // Miscellaneous Symbols and Pictographs
    $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clear_string = preg_replace($regex_symbols, '', $clear_string);

    // Transport And Map Symbols
    $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clear_string = preg_replace($regex_transport, '', $clear_string);

    // Miscellaneous Symbols
    $regex_misc = '/[\x{2600}-\x{26FF}]/u';
    $clear_string = preg_replace($regex_misc, '', $clear_string);

    // Dingbats
    $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
    $clear_string = preg_replace($regex_dingbats, '', $clear_string);

    return $clear_string;
}

function getStang($standUrl)
{    
    $standUrl = str_replace('http://', 'https://', $standUrl);
    $curl = curl_init($standUrl);
    curl_setopt($curl, CURLOPT_CAINFO, 'cert.cer');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Autorisation' => API_KEY]);
    $data = curl_exec($curl);
    return $data;
}

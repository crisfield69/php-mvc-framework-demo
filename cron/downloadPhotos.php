<?php


if(empty($_GET)) {
    die();
}

$step = intval($_GET['step']);

require_once 'config.php';
require_once 'connect.php';
require_once 'util.php';

ini_set('max_execution_time', 3600);
ini_set('memory_limit', '-1');
const NB_STEPS = 5;
const EOL = "\n";


if($step === 1) {
    if(file_exists(EXPOSANTS_IMAGES_PATH.'_temp')) {
        rrmdir(EXPOSANTS_IMAGES_PATH.'_temp');
    }
    mkdir(EXPOSANTS_IMAGES_PATH.'_temp', 0777, true);
}

$query = 'SELECT * FROM '.EXPOSANTS_TABLE;
$result = mysqli_query($connexion, $query);

$exposants = [];
while($exposant=mysqli_fetch_object($result)) {
    if(!empty($exposant->participant_images)) {
        $photosSourceUrls = explode('|||', $exposant->participant_images);
        $exposants[] = (object) [
            'urls'  =>  $photosSourceUrls,
            'id'    =>  $exposant->id
        ];
    } 
}

$nombre_par_etape = count($exposants)/NB_STEPS;

if(isDecimal($nombre_par_etape)) {
    $nombre_par_etape = floor($nombre_par_etape)+1;
}

$exposants = array_chunk($exposants, $nombre_par_etape);

downloadPhotos($step-1, $exposants);

$step++;

if($step<=NB_STEPS) {
    header('Location: '.SITE_URL.'cron/downloadPhotos.php?step='.$step);
    exit();
}
else {   
   header('Location:'.SITE_URL.'cron/resizePhotos.php?step=1');
   exit();
}


function downloadPhotos($num, $exposants)
{
    foreach($exposants[$num] as $exposant) {
        copyImagesFromUrlsArray($exposant->urls, 'users', $exposant->id);         
    }
}

function isDecimal($value) 
{
    if(fmod($value, 1) !== 0.00){
        return true;
    }
     return false;
}



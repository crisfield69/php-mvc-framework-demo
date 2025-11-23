<?php

if(empty($_GET)) {
    die();
}

$step = $_GET['step'];

require_once 'config.php';
require_once 'util.php';

ini_set('max_execution_time', 3600);
ini_set('memory_limit', '-1');

const EXPOSANTS_DIRECTORY_PATH = EXPOSANTS_IMAGES_PATH .'_temp/users';
const EOL = "\n";
const NB_STEPS = 5;

$exposantsDirectories = [];
if ($exposantsOpendir = opendir(EXPOSANTS_DIRECTORY_PATH)) {
    while (false !== ($exposantDirectory = readdir($exposantsOpendir))) {
        if ($exposantDirectory != "." && $exposantDirectory != ".." && is_dir(EXPOSANTS_DIRECTORY_PATH.'/'.$exposantDirectory)) {
            $exposantsDirectories[] = $exposantDirectory;
        }
    }
    closedir($exposantsOpendir);
}
sort($exposantsDirectories);

$nombre_par_etape = count($exposantsDirectories)/NB_STEPS;
if(isDecimal($nombre_par_etape)) {
    $nombre_par_etape = floor($nombre_par_etape)+1;
}

$exposantsDirectories = array_chunk($exposantsDirectories, $nombre_par_etape);
$exposantsDirectories = getFullDirectories($exposantsDirectories[$step-1]);


foreach($exposantsDirectories as $directory)
{
    for($i=1; $i<count($directory); $i++) {
        $exposantDirectory = $directory[0];
        $exposantFile = $directory[$i];
        $targetPhotoName = (int) filter_var($exposantFile, FILTER_SANITIZE_NUMBER_INT);
        list($width, $height) = getimagesize(EXPOSANTS_DIRECTORY_PATH.'/'.$exposantDirectory.'/'.$exposantFile);
        $resizePhoto = resizePhoto(
            EXPOSANTS_DIRECTORY_PATH.'/'.$exposantDirectory.'/'.$exposantFile, 
            EXPOSANTS_DIRECTORY_PATH.'/'.$exposantDirectory,
            $targetPhotoName
        );
    }
}

$step++;
if($step<=NB_STEPS) {
    header('Location: '.SITE_URL.'cron/resizePhotos.php?step='.$step);
    exit();
}
else {
    if(file_exists(EXPOSANTS_IMAGES_PATH.'_sauv')) {
        rrmdir(EXPOSANTS_IMAGES_PATH.'_sauv');
    }
    if(file_exists(EXPOSANTS_IMAGES_PATH)) {
        rename(EXPOSANTS_IMAGES_PATH, EXPOSANTS_IMAGES_PATH.'_sauv');
    }
    rename(EXPOSANTS_IMAGES_PATH.'_temp', EXPOSANTS_IMAGES_PATH);
    echo 'Processus de mise à jour terminé';
}


function getFullDirectories($exposantsDirectories) {
    $directories = [];
    foreach($exposantsDirectories as $exposantDirectory) {
        $array = [];    
        if ($exposantOpendir = opendir(EXPOSANTS_DIRECTORY_PATH.'/'.$exposantDirectory)) {
            while (false !== ($exposantFile = readdir($exposantOpendir))) {
                if ($exposantFile != "." && $exposantFile != ".." && is_file(EXPOSANTS_DIRECTORY_PATH.'/'.$exposantDirectory.'/'.$exposantFile)) {
                    $array[] = $exposantFile;
                }
            }
            closedir($exposantOpendir);
        }
        sort($array, SORT_NUMERIC);
        array_unshift($array, $exposantDirectory);
        $directories[] = $array;
    }
    return $directories;
}


function isDecimal($value) 
{
    if(fmod($value, 1) !== 0.00){
        return true;
    }
     return false;
}
<?php

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../connect.php';

$query = 'SELECT * FROM '.EXPOSANTS_TABLE;
$result = mysqli_query($connexion, $query);
$exposants = [];

while($exposant=mysqli_fetch_assoc($result)) {
    
    $exposant['images'] = [];
    $imagesPath = SITE_PATH.'downloads/exposants/users/'.$exposant['id'];
    $imagesUrl = SITE_URL.'downloads/exposants/users/'.$exposant['id'];

    if(is_dir($imagesPath)) {
        $openDir = opendir($imagesPath);
        while(false !== ($file = readdir($openDir))) {
            if($file != '.' && $file != '..' && is_file($imagesPath.'/medium/'.$file)) {
                $exposant['images'][] = $imagesUrl.'/medium/'.$file;
            }
        }
        closedir($openDir);
    }
    $exposants[] = $exposant;
}

require_once __DIR__ . '/templates/exposants.php';
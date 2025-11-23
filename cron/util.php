<?php

ini_set('max_execution_time', 3600);
ini_set('memory_limit', '-1');

require_once 'debug.php';


function mb_ucfirst(string $str, ?string $encoding = null)
{
    return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) . mb_substr($str, 1, null, $encoding);
}

function getEnd($string)
{    
    $a = explode(' - ', $string);
    if(count($a)>=2) return $a[1];
    return $string;
}

function getSlug($string)
{
    $specials = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'È', 'É', 'Ê', 'Ë', 'è', 'é', 'ê', 'ë', 'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'Ù', 'Ú', 'Û', 'Ü', 'ù', 'ú', 'û', 'ü', 'ß', 'Ç', 'ç', 'Ð', 'ð', 'Ñ', 'ñ', 'Þ', 'þ', 'Ý');
    $standards  = array('A', 'A', 'A', 'A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e', 'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i', 'O', 'O', 'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o', 'o', 'o', 'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u', 'B', 'C', 'c', 'D', 'd', 'N', 'n', 'P', 'p', 'Y');
    $string = str_replace($specials, $standards, $string);
    $string = preg_replace("/[^A-Za-z0-9]+/", "-", $string);
    $string = trim($string, '-');
    $string = strtolower($string);
    return $string;
}

function escape($string)
{	
    global $connexion;
    return '"' . mysqli_real_escape_string($connexion, $string) . '"';
}

function resizePhoto($sourcePhotoPath, $targetDirectoryPath, $targetPhotoName)
{
    $result = (object) [
        'success' => true,
        'message' => ''
    ];

    // Suppression photo si fichier invalide
    if(!exif_imagetype($sourcePhotoPath)) {
        unlink($sourcePhotoPath);
        $result->success = false;
        $result->message = 'Fichier invalide 1 supprimé';
        return $result;
    }

    // Déclaration tailles finales dans les sous-répertoires
    $subDirectoriesWidths = ['small' => 300, 'large' => 1800];

    // Assignation des dimensions sources, du type source, et calcul du ratio
    list($sourceWidth, $sourceHeight, $sourceType) = getimagesize($sourcePhotoPath);
    $ratio = $sourceHeight / $sourceWidth;

    // Initialisation de la photo source en fonction du type source
    $sourcePhoto = false;
    switch($sourceType) {
        case 1:
            @$sourcePhoto = imagecreatefromgif($sourcePhotoPath);
        break;

        case 2:
            @$sourcePhoto = imagecreatefromjpeg($sourcePhotoPath);
        break;
        
        case 3:
            @$sourcePhoto = imagecreatefrompng($sourcePhotoPath);
        break;

        default:
            unlink($sourcePhotoPath);
            $result->success = false;
            $result->message = 'Fichier invalide 2 supprimé';
            return $result;
        break;
    }

    // Correction orientation photo en fonction des informations exif
    if($sourcePhoto !== false) {        
        $exif = false;
        $exif = @exif_read_data($sourcePhotoPath);
        if($exif !== false) {
            if(!empty($exif['Orientation'])) {
                switch($exif['Orientation']) {
                    case 8:
                        @$sourcePhoto = imagerotate($sourcePhoto, 90, 0);
                    break;
                    case 3:
                        @$sourcePhoto = imagerotate($sourcePhoto, 180, 0);
                    break;
                    case 6:
                        @$sourcePhoto = imagerotate($sourcePhoto, -90, 0);
                    break;
                }
            }
        }        
    }    

    // Redimensionnement et copie dans les sous répertoires
    foreach ($subDirectoriesWidths as $subDirectoryWidth => $targetWidth) {

        $imagecopyresampled = false;
        $imagejpeg = false;
        $copy = false;

        // Création du sous-répertoire si inexistant
        if (!file_exists($targetDirectoryPath.'/'.$subDirectoryWidth)) {
            mkdir($targetDirectoryPath.'/'.$subDirectoryWidth, 0777, true);
        }
        // Simple copie/déplacement si source incorrecte 
        if ($sourcePhoto === false) {
            if(copy($sourcePhotoPath, $targetDirectoryPath.'/'.$subDirectoryWidth.'/'.$targetPhotoName.'.jpg' )) {
                $copy = true;
            }
            else{
                $copy = false;
            }
        }
        // Redimensionnement et copie/déplacement si source ok
        else {
            if($sourceWidth < $targetWidth) $targetWidth = $sourceWidth;
            $targetPhoto = imagecreatetruecolor($targetWidth, round($ratio * $targetWidth));            
            imagealphablending($targetPhoto, false);
            imagesavealpha($targetPhoto, true);
            if(imagecopyresampled($targetPhoto, $sourcePhoto, 0, 0, 0, 0, $targetWidth, round($ratio * $targetWidth), $sourceWidth, $sourceHeight)) {
                $imagecopyresampled = true;
                if(imagejpeg($targetPhoto, $targetDirectoryPath.'/'.$subDirectoryWidth.'/'.$targetPhotoName.'.jpg' , 90)) {
                    $imagejpeg = true;
                }
                else{
                    $imagejpeg = false;
                }
            }
            else {
                $imagecopyresampled = false;
            }
        }
    }
    if($imagecopyresampled === true && $imagejpeg === true) {
        $result->success = true;
        $result->message = 'Fichier redimensionné acec succès';
        return $result;
    }
    else {
        if($copy === true) {
            $result->success = false;
            $result->message = 'Fichier non redimensionné mais copié';
            return $result;
        }
        else{
            $result->success = false;
            $result->message = 'Fichier non redimensionné et non copié';
            return $result;
        }
    }
}

function copyImagesFromUrlsArray($photosSourcesUrlsArray, $endPath, $directoryName)
{
    $directoryPath = EXPOSANTS_IMAGES_PATH.'_temp/'.$endPath;
    if(!file_exists($directoryPath)) {
        mkdir($directoryPath, 0777, true);
    }
    for($i=0; $i<count($photosSourcesUrlsArray); $i++) {
        $photoSourceUrl = $photosSourcesUrlsArray[$i];
        $imageSize = @getimagesize($photoSourceUrl);
        if(!is_array($imageSize)) continue;

        $extension = image_type_to_extension($imageSize[2]);
        $extension = '.jpeg'? '.jpg' : $extension;
        
        $endDirectoryPath = $directoryPath.'/'.$directoryName;
        if(!file_exists($endDirectoryPath)) {
            mkdir($endDirectoryPath, 0777, true);
        }
        @copy($photoSourceUrl, $endDirectoryPath.'/'.$i.$extension);                
    }
}

function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src .'/'. $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}
<?php

namespace Core;

class PhotosManager
{

    protected $allowedPhotoTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'];
    protected $subDirectoriesWidths = ['small' => 300, 'medium' => 600, 'large' => 1000, 'xlarge' => 2000];
    protected $forbiddenFiles = ['.', '..', '.DS_Store', '_DS_Store'];

    public $photosType = 'jpg';
    public $photoQuality = 90;


    /**
     * setPhotoType function
     * 
     * Set all photo type output. 
     * Expected values : 'jpg', 'png', 'gif'
     *
     * @param string $photosType
     * @return void
     */
    public function setPhotoType(string $photosType): void
    {
        $this->photosType = $photosType;
    }


    /**
     * setPhotoQuality function
     * 
     * Set all photo quality output. 
     * Expected values : int betweeen 0 and 100
     *
     * @param int $photoQuality
     * @return void
     */
    public function setPhotoQuality(int $photoQuality): void
    {
        $this->photoQuality = $photoQuality;
    }


    /**
     * setDirectoriesWidth function
     *
     * Set All subdirectories and associeted photos widths. 
     * Expected values : ['small' => 300, etc.]
     * 
     * @param array $directoriesWidths
     * @return void
     */
    public function setDirectoriesWidth(array $directoriesWidths): void
    {
        $this->subDirectoriesWidths = $directoriesWidths;
    }


    /**
     * renamePhoto function
     *
     * @param string $directoryPath
     * @param string $directoryName
     * @param integer $previousName
     * @param integer $nexName
     * @param string $type
     * @return void
     */
    public function renamePhoto(string $path, string $directoryName, int $previousName, int $nexName, string $type) : void
    {
        foreach ($this->subDirectoriesWidths as $subDirectory => $targetWidth) {
            $photoSource = $path . $directoryName . '/' . $subDirectory . '/' . $previousName . '.' . $type;
            $photoTarget = $path . $directoryName . '/' . $subDirectory . '/' . $nexName . '.' . $type;
            if(
                file_exists($photoSource) && is_file($photoSource) ) {
                @rename( $photoSource, $photoTarget );
            }
        }
    }


    /**
     * addPhotos function
     *
     * @param array $uploadedPhotosFiles
     * @param string $photosPath
     * @param integer $directoryId
     * @return void
     */
    public function addPhotos(array $uploadedPhotosFiles, string $photosPath, $directoryId, int $currentPhotosNumber = 0): void
    {
        $photosPathDirectory = $photosPath . $directoryId . '/';
        if (!file_exists($photosPathDirectory)) {
            mkdir($photosPathDirectory, 0777, true);
        }
        foreach ($this->subDirectoriesWidths as $subDirectoryWidth => $targetWidth) {
            if (!file_exists($photosPathDirectory . $subDirectoryWidth)) {
                mkdir($photosPathDirectory . $subDirectoryWidth . '/', 0777, true);
            }
        }
        $newPhotosNumber = sizeof($uploadedPhotosFiles['name']);
        if ($newPhotosNumber == 0) return;
        
        if($currentPhotosNumber === 0)
            $currentPhotosNumber = $this->countPhotos($photosPathDirectory . 'small/');
        
        $increment = 1;

        for ($n = 0; $n < $newPhotosNumber; $n++) {
            $type = $uploadedPhotosFiles['type'][$n];
            if (!in_array($type, $this->allowedPhotoTypes)) continue;
            switch ($type) {
                case 'image/jpeg':
                    $sourcePhoto = imagecreatefromjpeg($uploadedPhotosFiles['tmp_name'][$n]);
                    break;
                case 'image/gif':
                    $sourcePhoto = imagecreatefromgif($uploadedPhotosFiles['tmp_name'][$n]);
                    break;
                case 'image/png':
                    $sourcePhoto = imagecreatefrompng($uploadedPhotosFiles['tmp_name'][$n]);
                    break;
            }

            list($sourceWidth, $sourceHeight) = getimagesize($uploadedPhotosFiles['tmp_name'][$n]);
            $ratio = $sourceHeight / $sourceWidth;
            ini_set('memory_limit', '-1');
            $targetName = $increment + $currentPhotosNumber;
            $increment++;

            foreach ($this->subDirectoriesWidths as $subDirectoryWidth => $targetWidth) {
                if ($sourceWidth < $targetWidth) $targetWidth = $sourceWidth;
                $targetPhoto = imagecreatetruecolor($targetWidth, $ratio * $targetWidth);
                imagealphablending($targetPhoto, false);
                imagesavealpha($targetPhoto, true);
                imagecopyresampled($targetPhoto, $sourcePhoto, 0, 0, 0, 0, $targetWidth, $ratio * $targetWidth, $sourceWidth, $sourceHeight);

                if ($this->photosType === 'png') {
                    imagepng($targetPhoto, $photosPathDirectory . $subDirectoryWidth . '/' . $targetName . '.' . $this->photosType, 0);
                }

                if ($this->photosType === 'jpg') {
                    imagejpeg($targetPhoto, $photosPathDirectory . $subDirectoryWidth . '/' . $targetName . '.' . $this->photosType, $this->photoQuality);
                }
            }
        }
    }

    
    /**
     * addPhoto function
     *
     * @param array $uploadedPhotoFile
     * @param string $photosPath
     * @param integer $directoryId
     * @return void
     */
    public function addPhoto($uploadedPhotoFile, string $photosPath, $directoryId, string $targetName ): void
    {
        $photosPathDirectory = $photosPath . $directoryId . '/';
        if (!file_exists($photosPathDirectory)) {
            mkdir($photosPathDirectory, 0777, true);
        }
        foreach ($this->subDirectoriesWidths as $subDirectoryWidth => $targetWidth) {
            if (!file_exists($photosPathDirectory . $subDirectoryWidth)) {
                mkdir($photosPathDirectory . $subDirectoryWidth . '/', 0777, true);
            }
        }
        if(is_array($uploadedPhotoFile)) {
            $type = $uploadedPhotoFile['type'];
            $uploadedPhotoFileTmpName = $uploadedPhotoFile['tmp_name'];
        }

        if(is_object($uploadedPhotoFile)) {
            $type = $uploadedPhotoFile->type;
            $uploadedPhotoFileTmpName = $uploadedPhotoFile->tmp_name;
        }

        if (!in_array($type, $this->allowedPhotoTypes)) return;

        switch ($type) {
            case 'image/jpeg':
                $sourcePhoto = imagecreatefromjpeg($uploadedPhotoFileTmpName);
                break;
            case 'image/gif':
                $sourcePhoto = imagecreatefromgif($uploadedPhotoFileTmpName);
                break;
            case 'image/png':
                $sourcePhoto = imagecreatefrompng($uploadedPhotoFileTmpName);
                break;
        }

        list($sourceWidth, $sourceHeight) = getimagesize($uploadedPhotoFileTmpName);
        $ratio = $sourceHeight / $sourceWidth;
        ini_set('memory_limit', '-1');

        foreach ($this->subDirectoriesWidths as $subDirectoryWidth => $targetWidth) {
            if ($sourceWidth < $targetWidth) $targetWidth = $sourceWidth;
            $targetPhoto = imagecreatetruecolor($targetWidth, round($ratio * $targetWidth));
            imagealphablending($targetPhoto, false);
            imagesavealpha($targetPhoto, true);
            imagecopyresampled($targetPhoto, $sourcePhoto, 0, 0, 0, 0, $targetWidth, round($ratio * $targetWidth), $sourceWidth, $sourceHeight);

            if ($this->photosType === 'png') {
                imagepng($targetPhoto, $photosPathDirectory . $subDirectoryWidth . '/' . $targetName . '.' . $this->photosType, 0);
            }

            if ($this->photosType === 'jpg') {
                imagejpeg($targetPhoto, $photosPathDirectory . $subDirectoryWidth . '/' . $targetName . '.' . $this->photosType, $this->photoQuality);
            }
        }
        
    }


    /**
     * forwardPhoto function
     *
     * Forward photo position by renaming files
     * 
     * @param string $fileName
     * @param integer $directoryId
     * @param string $photosPath
     * @return void
     */
    public function forwardPhoto(string $fileName, $directoryId, string $photosPath): void
    {
        $photosPathDirectory = $photosPath . $directoryId . '/';
        $list = explode('.', $fileName);
        $photoNum = $list[0];
        $photosNumber = $this->countPhotos($photosPathDirectory . 'small/');
        if ($photoNum < $photosNumber) {
            foreach ($this->subDirectoriesWidths as $subDirectoryWidth => $targetWidth) {
                $subDirectoryPath = $photosPathDirectory . $subDirectoryWidth . '/';
                rename($subDirectoryPath . $photoNum . '.' . $this->photosType, $subDirectoryPath . '0.' . $this->photosType);
                rename($subDirectoryPath . ($photoNum + 1) . '.' . $this->photosType, $subDirectoryPath . $photoNum . '.' . $this->photosType);
                rename($subDirectoryPath . '0.' . $this->photosType, $subDirectoryPath . ($photoNum + 1) . '.' . $this->photosType);
            }
        }
    }


    /**
     * backwardPhoto function
     *
     * Backward photo position by renaming files
     * 
     * @param string $fileName
     * @param integer $directoryId
     * @param string $photosPath
     * @return void
     */
    public function backwardPhoto(string $fileName, $directoryId, string $photosPath): void
    {
        $photosPathDirectory = $photosPath . $directoryId . '/';
        $list = explode('.', $fileName);
        $photoNum = $list[0];
        if ($photoNum > 1) {
            foreach ($this->subDirectoriesWidths as $subDirectoryWidth => $targetWidth) {
                $subDirectoryPath = $photosPathDirectory . $subDirectoryWidth . '/';
                rename($subDirectoryPath . $photoNum . '.' . $this->photosType, $subDirectoryPath . '0.' . $this->photosType);
                rename($subDirectoryPath . ($photoNum - 1) . '.' . $this->photosType, $subDirectoryPath . $photoNum . '.' . $this->photosType);
                rename($subDirectoryPath . '0.' . $this->photosType, $subDirectoryPath . ($photoNum - 1) . '.' . $this->photosType);
            }
        }
    }


    /**
     * deletePhoto function
     *
     * Delete photo and rename files
     * 
     * @param string $fileName
     * @param integer $directoryId
     * @param string $photosPath
     * @return void
     */
    public function deletePhoto(string $fileName, ?string $directoryId, string $photosPath): void
    {
        if($directoryId !== '') {
            $photosPathDirectory = $photosPath . $directoryId . '/';
        }
        else{
            $photosPathDirectory = $photosPath;
        }        

        $list = explode('.', $fileName);
        $photoNum = $list[0];
        $photosNumber = $this->countPhotos($photosPathDirectory . 'small/');
        if ($photosNumber - $photoNum > 0) {
            foreach ($this->subDirectoriesWidths as $subDirectoryWidth => $targetWidth) {
                unlink($photosPathDirectory . $subDirectoryWidth . '/' . $fileName);
            }
            for ($n = $photoNum + 1; $n <= $photosNumber; $n++) {
                if (file_exists($photosPathDirectory . 'small/' . $n . '.' . $this->photosType)) {
                    foreach ($this->subDirectoriesWidths as $subDirectoryWidth => $targetWidth) {
                        rename($photosPathDirectory . $subDirectoryWidth . '/' . $n . '.' . $this->photosType, $photosPathDirectory . $subDirectoryWidth . '/' . ($n - 1) . '.' . $this->photosType);
                    }
                }
            }
        } else {
            foreach ($this->subDirectoriesWidths as $subDirectoryWidth => $targetWidth) {
                unlink($photosPathDirectory . $subDirectoryWidth . '/' . $fileName);
            }
        }
    }



    /**
     * removeTempDirectories function
     *
     * @param string $directoriesPath
     * @return void
     */
    public function removeTempDirectories(string $directoriesPath): void
    {
        if (is_dir($directoriesPath)) {
            $subElements = scandir($directoriesPath);
            foreach ($subElements as $subElement) {
                if (
                    $subElement != '.' &&
                    $subElement != '..' &&
                    $subElement != '.DS_Store' &&
                    strpos($subElement, '_') === 0 &&
                    is_dir($directoriesPath . '/' . $subElement)
                ) {                    
                    $this->removeDirectory($directoriesPath . $subElement);
                }                 
            }
        }
    }



    /**
     * removeDirectory function
     *
     * @param string $directoryPath
     * @return void
     */
    public function removeDirectory(string $directoryPath): void
    {
        if (file_exists($directoryPath) && is_dir($directoryPath)) {
            $subElements = scandir($directoryPath);
            foreach ($subElements as $subElement) {
                if ($subElement != '.' && $subElement != '..') {
                    if (filetype($directoryPath . '/' . $subElement) == 'dir') {
                        $this->removeDirectory($directoryPath . '/' . $subElement);
                    } else {
                        unlink($directoryPath . '/' . $subElement);
                    }
                }
            }
            reset($subElements);
            rmdir($directoryPath);
        }
    }



    /**
     * copyDirectory function
     *
     * @param string $sourceDirectoryPath
     * @param string $targetDirectoryPath
     * @return void
     */
    public function copyDirectory(string $sourceDirectoryPath, string $targetDirectoryPath): void
    {        
        if(file_exists($sourceDirectoryPath) && is_dir($sourceDirectoryPath)) {
            if(file_exists($targetDirectoryPath) && is_dir($targetDirectoryPath)) {
                $this->removeDirectory($targetDirectoryPath);
            }   
            @mkdir($targetDirectoryPath);         
            foreach (scandir($sourceDirectoryPath) as $file) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($sourceDirectoryPath . '/' . $file)) {
                        $this->copyDirectory($sourceDirectoryPath . '/' . $file, $targetDirectoryPath . '/' . $file);
                    } else {
                        copy($sourceDirectoryPath . '/' . $file, $targetDirectoryPath . '/' . $file);
                    }
                }
            }
        }                
    }


    /**
     * directoryExist function
     *
     *  Checks if a directory exist
     * 
     * @param string $directoryPath
     * @return bool
     */
    public function directoryExist(string $directoryPath): bool
    {
        $path = realpath($directoryPath);
        if ($path !== false and is_dir($path)) {
            return true;
        }
        return false;
    }


    /**
     * countPhotos function
     *
     * @param string $directoryPath
     * @return integer
     */
    public function countPhotos(string $directoryPath): int
    {
        $filesNumber = 0;
        $openDir  = opendir($directoryPath);
        while (false !== ($fileName = readdir($openDir))) {
            if (in_array($fileName, $this->forbiddenFiles)) {
                continue;
            }
            if (is_dir($directoryPath . $fileName)) continue;
            $filesNumber++;
        }
        closedir($openDir);
        return $filesNumber;
    }



    public function photoExist($photoName)
    {
        return is_file($photoName);
    }

}

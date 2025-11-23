<?php

namespace Core;

use Core\Database;
use stdClass;

class Util
{
	
	public static function prepare(string $data): string
	{
		return htmlspecialchars(stripslashes(trim($data)));
	}

	public static function escape($string)
	{
		$database = Database::getInstance();
		return '"' . mysqli_real_escape_string($database->getConnexion(), $string) . '"';
	}

	public static function check(array $requisits): object
	{
		$check = new \stdClass;
		$check->result = true;
		$check->message = '';

		foreach ($requisits as $requisit => $fields) {
			if (!is_array($fields)) {
				$var = $fields;
				$fields = array($var);
			}

			//------------------------------------------------------------------					
			if ($requisit === 'required') {
				foreach ($fields as $field) {
					if ($field === '') {
						$check->result = false;
						$check->message = 'Veuillez renseigner tous les champs obligatoires';
						return $check;
					}
				}
			}

			//------------------------------------------------------------------
			if ($requisit === 'select') {
				foreach ($fields as $field) {
					if ($field === '0') {
						$check->result = false;
						$check->message = 'Veuillez renseigner tous les champs obligatoires';
						return $check;
					}
				}
			}

			//------------------------------------------------------------------
			if ($requisit === 'email') {
				foreach ($fields as $field) {
					if (!preg_match('/^[-+.\w]{1,64}@[-.\w]{1,64}\.[-.\w]{2,6}$/i', $field)) {
						$check->result = false;
						$check->message = 'Veuillez saisir une adresse email correcte';
						return $check;
					}
				}
			}

			//------------------------------------------------------------------
			if ($requisit === 'equals') {
				foreach ($fields as $field) {
					if ($field[0] !== $field[1]) {
						$check->result = false;
						$check->message = 'Veuillez saisir deux valeurs identiques';
						return $check;
					}
				}
			}

			//------------------------------------------------------------------
			if ($requisit === 'forbidden') {
				foreach ($fields as $field) {
					if ($field[0] === $field[1]) {
						$check->result = false;
						$check->message = 'Veuillez saisir une valeur correcte';
						return $check;
					}
				}
			}
		}
		return $check;
	}


	public static function getSlug( string $string) : string
	{
		$specials = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'È', 'É', 'Ê', 'Ë', 'è', 'é', 'ê', 'ë', 'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'Ù', 'Ú', 'Û', 'Ü', 'ù', 'ú', 'û', 'ü', 'ß', 'Ç', 'ç', 'Ð', 'ð', 'Ñ', 'ñ', 'Þ', 'þ', 'Ý');
		$standards  = array('A', 'A', 'A', 'A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e', 'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i', 'O', 'O', 'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o', 'o', 'o', 'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u', 'B', 'C', 'c', 'D', 'd', 'N', 'n', 'P', 'p', 'Y');
		$string = str_replace($specials, $standards, $string);
		$string = str_replace('_', '-', $string);
		$string = preg_replace("#[^A-Za-z0-9]+#", "-", $string);		
		$string = trim($string, '-');
		$string = strtolower($string);
		return $string;
	}


	public static function dateToFrench( string $date, string $format) : string 
    {
        $days_en = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $days_fr = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $months_en = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $months_fr = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        return str_replace($months_en, $months_fr, str_replace($days_en, $days_fr, date($format, strtotime($date))));
    }


	public static function normalizeUploadedFiles(&$files)
    {   
        if(
            !isset($files) || 
            empty($files) || 
            !isset($files['name']) ||
            empty($files['name'])
        ) 
        return [];
        $normalizedFiles    =   [];
        $filesCount         =   count($files['name']);
        $filesKeys          =   array_keys($files);
        for ($i=0; $i<$filesCount; $i++) {
			$params = new stdClass;
            foreach ($filesKeys as $key) {
				$params->$key = $files[$key][$i];
			}
			$normalizedFiles[$i] = $params;
		}				
        return $normalizedFiles;
    }
	
	public static function deleteDirectory($directory_path)
    {
        if (is_dir($directory_path)) {
            $sub_elements = scandir($directory_path);
            foreach ($sub_elements as $sub_element) {
               if ($sub_element !== '.' && $sub_element !== '..') {
                  $sub_element_path = $directory_path . '/' . $sub_element;
                  if (is_dir($sub_element_path)) {
                    self::deleteDirectory($sub_element_path);
                  } 
				  else {
                     unlink($sub_element_path);
                  }
               }
            }
            rmdir($directory_path);
         }        
    }
	
}
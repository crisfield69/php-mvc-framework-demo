<?php

require_once 'config.php';
require_once 'connect.php';
require_once 'util.php';

$query = 'DROP TABLE '.EXPOSANTS_TABLE;
mysqli_query($connexion, $query);

$query = 'CREATE TABLE IF NOT EXISTS '.EXPOSANTS_TABLE.' (id INT(11), ';
foreach(EXPOSANTS_TABLE_COLUMNS as $column) {
    if(in_array($column, ['paper_introduction', 'website_introduction', 'participant_images', 'products_images', 'products_informations', 'services_informations'])){
        $query .= $column .' TEXT, ';
    }
    else{
        $query .= $column .' VARCHAR(255), ';
    }
}
$query = substr($query, 0, -2);
$query .= ')';

mysqli_query($connexion, $query);

$query = 'ALTER TABLE '.EXPOSANTS_TABLE.' ADD PRIMARY KEY (id)';
mysqli_query($connexion, $query);

$query = 'ALTER TABLE '.EXPOSANTS_TABLE.' MODIFY id int(11) NOT NULL AUTO_INCREMENT';
mysqli_query($connexion, $query);


require_once 'view.createExposantsTable.php';
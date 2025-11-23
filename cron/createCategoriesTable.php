<?php

require_once 'config.php';
require_once 'connect.php';
require_once 'util.php';

$query = 'DROP TABLE '.CATEGORIES_TABLE;
mysqli_query($connexion, $query);

$query = 'CREATE TABLE IF NOT EXISTS '.CATEGORIES_TABLE.'(id INT(11), '
.'parent_id int(11) NOT NULL, '
.'nom varchar(255) NOT NULL, '
.'slug varchar(255) NOT NULL, '
.'ordre int(11) NOT NULL, '
.'temp int(11) DEFAULT 0)';
mysqli_query($connexion, $query);

$query = 'ALTER TABLE '.CATEGORIES_TABLE.' ADD PRIMARY KEY (id)';
mysqli_query($connexion, $query);

$query = 'ALTER TABLE '.CATEGORIES_TABLE.' MODIFY id int(11) NOT NULL AUTO_INCREMENT';
mysqli_query($connexion, $query);

header('Location:'.SITE_URL.'cron/insertCategories.php');
exit();
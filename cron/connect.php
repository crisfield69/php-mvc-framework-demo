<?php

require_once 'config.php';

$connexion = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
mysqli_query($connexion, "SET NAMES 'utf8'");
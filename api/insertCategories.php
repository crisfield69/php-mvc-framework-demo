<?php

require_once 'config.php';
require_once 'connect.php';
require_once 'util.php';

$activity_sectors = [];
$specialties = [];
$query = 'SELECT * FROM '.EXPOSANTS_TABLE;
$result = mysqli_query($connexion, $query);

while($exposant = mysqli_fetch_assoc($result))
{
    if(empty($exposant['activity_sector'])) continue;
    if(!isset($activity_sectors[$exposant['activity_sector']])) 
    {
        $activity_sectors[$exposant['activity_sector']] = [
            'nom'   =>  $exposant['activity_sector'],
            'slug'  =>  getSlug($exposant['activity_sector'])
        ];
    }
    if(empty($exposant['specialties'])) continue;
    $exposant_specialties = mb_ucfirst(getEnd($exposant['specialties']), 'UTF-8');
    if(!isset($specialties[$exposant_specialties])) 
    {
        $specialties[$exposant_specialties] = [
            'nom'           =>  $exposant_specialties,
            'slug'          =>  getSlug($exposant_specialties),
            'parent_slug'   =>  getSlug($exposant['activity_sector'])
        ];
    }
}

foreach($activity_sectors as $activity_sector)
{
    $maxordre = getMaxOrdre(0);
    $query = 'SELECT * FROM '.CATEGORIES_TABLE.' WHERE nom='.escape($activity_sector['nom']);
    $result = mysqli_query($connexion, $query);    
    if(mysqli_num_rows($result) === 0) 
    {
        $query = 'INSERT INTO '.CATEGORIES_TABLE.' SET '
        .'nom='.escape($activity_sector['nom']).', '
        .'slug='.escape($activity_sector['slug']).', '
        .'ordre='.intval($maxordre+1).', '
        .'parent_id=0';
        mysqli_query($connexion, $query);
    }
}

foreach($specialties as $specialty)
{
    $query = 'SELECT id FROM '.CATEGORIES_TABLE.' WHERE slug='.escape($specialty['parent_slug']);
    $result = mysqli_query($connexion, $query);
    $row = mysqli_fetch_assoc($result);
    $parent_id = $row['id'];
    $maxordre = getMaxOrdre($parent_id);
    $query = 'SELECT * FROM '.CATEGORIES_TABLE.' WHERE nom='.escape($specialty['nom']);
    $result = mysqli_query($connexion, $query);
    if(mysqli_num_rows($result) === 0) 
    {
        $query = 'INSERT INTO '.CATEGORIES_TABLE.' SET '
        .'nom='.escape($specialty['nom']).', '
        .'slug='.escape($specialty['slug']).', '
        .'ordre='.intval($maxordre+1).', '
        .'parent_id='.intval($parent_id);
        mysqli_query($connexion, $query);
    }
}

$query = 'SELECT * FROM '.CATEGORIES_TABLE;
$result = mysqli_query($connexion, $query);
while($category = mysqli_fetch_assoc($result))
{
    $id = $category['id'];
    $parent_id = $category['parent_id'];   
    if($parent_id == 0) continue;
    $query2 = 'SELECT * FROM '.CATEGORIES_TABLE.' WHERE id='.intval($parent_id);
    $result2 = mysqli_query($connexion, $query2);
    if(mysqli_num_rows($result2) === 0) 
    {
        $maxordre = getMaxOrdre(0);
        $query3 = 'UPDATE '.CATEGORIES_TABLE.' SET '
        .'parent_id=0, '
        .'ordre='.intval($maxordre+1).' '
        .'WHERE id='.intval($id);
        mysqli_query($connexion, $query3);
    }  
}

setOrdre(0);
$query = 'SELECT * FROM '.CATEGORIES_TABLE;
$result = mysqli_query($connexion, $query);
while($category = mysqli_fetch_assoc($result))
{
    $id = $category['id'];
   setOrdre($id);
}

function setOrdre($id)
{
    global $connexion;    
    $query2 = 'SELECT * FROM '.CATEGORIES_TABLE.' WHERE parent_id='.intval($id).' ORDER BY ordre';
    $result2 = mysqli_query($connexion, $query2);
    if(mysqli_num_rows($result2) !== 0)
    {
        $ordre = 1;
        while($category2 = mysqli_fetch_assoc($result2)) 
        {
            $id2 = $category2['id'];
            $query3 = 'UPDATE '.CATEGORIES_TABLE.' SET '
            .'ordre='.intval($ordre).' '
            .'WHERE id='.intval($id2);
            mysqli_query($connexion, $query3);
            $ordre++;
        }
    }
}

function getMaxOrdre($parent_id)
{
    global $connexion;
    $query = 'SELECT MAX(ordre) AS maxordre FROM '.CATEGORIES_TABLE.' WHERE parent_id='.intval($parent_id);
    $result = mysqli_query($connexion, $query);
    $maxordre = 0;
    if(mysqli_num_rows($result) !== 0) 
    {
        $row = mysqli_fetch_assoc($result);
        $maxordre = intval($row['maxordre']);
    }    
    return $maxordre;
}

require_once 'view.insertCategories.php';
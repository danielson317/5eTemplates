<?php
include('character.inc.php');
include('websheet.inc.php');
include('install.inc.php');

echo 'Hello World';

$create = new CreateDB('dnd.db');
$create->createCharacterTable();
// Character Sheet
// Spell Card
// Moster Stats

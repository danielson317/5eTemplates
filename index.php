<?php
DEFINE('ROOT_PATH', __DIR__);

// include('character.inc.php');
// include('websheet.inc.php');
// include('install.inc.php');

echo printCharacterSheet();

// $create = new CreateDB('dnd.db');
// $create->createCharacterTable();
// Character Sheet
// Spell Card
// Moster Stats

function printCharacterSheet()
{
  ob_start();
  include ROOT_PATH . '/themes/default/templates/character.tpl.php';
  return ob_get_clean();
}

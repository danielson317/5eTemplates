<?php

function printCharacterSheet()
{
  ob_start();
  include ROOT_PATH . '/themes/default/templates/character.tpl.php';
  return ob_get_clean();
}

function getAlignmentList()
{
  return array(
    'lg' => 'Lawful Good',
    'ng' => 'Neutral Good',
    'cg' => 'Chaotic Good',
    'ln' => 'Lawful Neutral',
    'cn' => 'Chaotic Neutral',
    'le' => 'Lawful Evil',
    'ne' => 'Neutral Evil',
    'ce' => 'Chaotic Evil',
    'l' => 'Lawful',
    'n' => 'Neutral',
    'c' => 'Chaotic',
    'g' => 'Good',
    'e' => 'Evil',
  );
}

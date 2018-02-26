<?php

function printSpellCard($spell)
{
  extract($spell);

  ob_start();

  include ROOT_PATH . '/themes/default/templates/spell.tpl.php';

  return ob_get_clean();
}

function getLevelList()
{
  return array(
    0 => 'Cantrip',
    1 => '1st',
    2 => '2nd',
    3 => '3rd',
    4 => '4th',
    5 => '5th',
    6 => '6th',
    7 => '7th',
    8 => '8th',
    9 => '9th',
    10 => 'Racial Skill',
    11 => 'Class Skill',
  );
}

function getRangeList()
{
  return array(
    0 => 'unlimited',
    1 => 'same plane',
    2 => 'Self',
    3 => 'Touch',
    4 => 'Sight',
    5 => '5 feet',
    10 => '10 feet',
    15 => '15 feet',
    30 => '30 feet',
    60 => '60 feet',
    90 => '90 feet',
    100 => '100 feet',
    120 => '120 feet',
    150 => '150 feet',
    300 => '300 feet',
    500 => '500 feet',
    5280 => '1 mile',
    26400 => '5 miles',
  );
}
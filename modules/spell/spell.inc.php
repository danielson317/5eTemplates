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

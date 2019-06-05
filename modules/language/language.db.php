<?php

function installLanguage()
{
  GLOBAL $db;

  $query = new CreateQuery('languages');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('script_id', 'INTEGER');
  $query->addField('source_id', 'INTEGER');
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  $sources = array_flip(getSourceList());
  $scripts = array_flip(getScriptList());

  $languages = array(
    array(
      'name' => 'Common',
      'script_id' => $scripts['Common'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Dwarvish',
      'script_id' => $scripts['Dwarvish'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Elvish',
      'script_id' => $scripts['Elvish'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Giant',
      'script_id' => $scripts['Dwarvish'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Gnomish',
      'script_id' => $scripts['Dwarvish'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Goblin',
      'script_id' => $scripts['Dwarvish'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Halfling',
      'script_id' => $scripts['Common'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Orc',
      'script_id' => $scripts['Dwarvish'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Abyssal',
      'script_id' => $scripts['Infernal'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Celestial',
      'script_id' => $scripts['Celestial'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Deep Speech',
      'script_id' => $scripts['Unwritten'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Draconic',
      'script_id' => $scripts['Draconic'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Infernal',
      'script_id' => $scripts['Infernal'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Primordial',
      'script_id' => $scripts['Dwarvish'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Sylvan',
      'script_id' => $scripts['Elvish'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
    array(
      'name' => 'Undercommon',
      'script_id' => $scripts['Elvish'],
      'source_id' => $sources['BR'],
      'description' => '',
    ),
  );
}

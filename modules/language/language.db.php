<?php

function installLanguage()
{
  GLOBAL $db;

  $query = new CreateQuery('languages');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('script_id', CreateQuery::TYPE_INTEGER);
  $query->addField('source_id', CreateQuery::TYPE_INTEGER);
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
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

  foreach ($languages as $languge)
  {
    createLanguage($languge);
  }
}

/**
 * @param int $page
 *
 * @return array|false
 */
function getLanguagePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('languages');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('script_id');
  $query->addField('description');

  $query->addPager($page);

  return $db->select($query);
}

/**
 * @return array
 */
function getLanguageList($id = FALSE)
{
  GLOBAL $db;

  $query = new SelectQuery('languages');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query, $id);
}

/**
 * @param int $language_id
 *
 * @return array|false
 */
function getLanguage($language_id)
{
  GLOBAL $db;

  $query = new SelectQuery('languages');
  $query->addField('id');
  $query->addField('name');
  $query->addField('source_id');
  $query->addField('script_id');
  $query->addField('description');
  $query->addConditionSimple('id', $language_id);

  return $db->selectObject($query);
}

/**
 * @param array $language
 *
 * @return int
 */
function createLanguage($language)
{
  GLOBAL $db;

  $query = new InsertQuery('languages');
  $query->addField('name', $language['name']);
  $query->addField('source_id', $language['source_id']);
  $query->addField('script_id', $language['script_id']);
  $query->addField('description', $language['description']);

  return $db->insert($query);
}

/**
 * @param array $language
 */
function updateLanguage($language)
{
  GLOBAL $db;

  $query = new UpdateQuery('languages');
  $query->addField('name', $language['name']);
  $query->addField('source_id', $language['source_id']);
  $query->addField('script_id', $language['script_id']);
  $query->addField('description', $language['description']);
  $query->addConditionSimple('id', $language['id']);

  $db->update($query);
}

/**
 * @param int $language_id
 */
function deleteLanguage($language_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('languages');
  $query->addConditionSimple('id', $language_id);

  $db->delete($query);
}

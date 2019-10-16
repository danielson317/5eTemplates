<?php

function installSubclass()
{
  GLOBAL $db;

  $query = new CreateQuery('subclasses');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('class_id', 'INTEGER', 0, array('N'));
  $query->addField('source_id', 'INTEGER');
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);

  $sources = array_flip(getSourceList());
  $classes = array_flip(getClassList());
  $subclasses = array();

  // Cleric.
  $subclasses += array(
    array(
      'name' => 'Knowledge',
      'class_id' => $classes['Cleric'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Life',
      'class_id' => $classes['Cleric'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Light',
      'class_id' => $classes['Cleric'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Nature',
      'class_id' => $classes['Cleric'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Tempest',
      'class_id' => $classes['Cleric'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Trickery',
      'class_id' => $classes['Cleric'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'War',
      'class_id' => $classes['Cleric'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
  );

  // Fighter.
  $subclasses += array(
    array(
      'name' => 'Champion',
      'class_id' => $classes['Fighter'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
  );

  // Rogue.
  $subclasses += array(
    array(
      'name' => 'Thief',
      'class_id' => $classes['Rogue'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
  );

  // Wizard.
  $subclasses += array(
    array(
      'name' => 'Abjuration',
      'class_id' => $classes['Wizard'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Conjuration',
      'class_id' => $classes['Wizard'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Divination',
      'class_id' => $classes['Wizard'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Enchantment',
      'class_id' => $classes['Wizard'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Evocation',
      'class_id' => $classes['Wizard'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Illusion',
      'class_id' => $classes['Wizard'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Necromancy',
      'class_id' => $classes['Wizard'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
    array(
      'name' => 'Transmutation',
      'class_id' => $classes['Wizard'],
      'description' => '',
      'source_id' => $sources['BR'],
    ),
  );

  foreach ($subclasses as $subclass)
  {
    createSubclass($subclass);
  }
}

/**
 * @param int $class_id
 * @param int $page
 *
 * @return array|bool
 */
function getSubclassPager($class_id, $page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('subclasses');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('class_id', $class_id);
  $query->addPager($page);
  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

/**
 * @param int|bool $class_id - optional. Filter by given class if present.
 *
 * @return array
 */
function getSubclassList($class_id = FALSE)
{
  GLOBAL $db;

  $query = new SelectQuery('subclasses');
  $query->addField('id')->addField('name', 'value');
  if ($class_id)
  {
    $query->addConditionSimple('class_id', $class_id);
  }

  return $db->selectList($query);
}

/**
 * @param int $sublcass_id
 *
 * @return array|FALSE
 */
function getSubclass($sublcass_id)
{
  GLOBAL $db;

  $query = new SelectQuery('subclasses');
  $query->addField('id');
  $query->addField('class_id');
  $query->addField('name');
  $query->addField('description');
  $query->addField('source_id');
  $query->addConditionSimple('id', $sublcass_id);

  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

/**
 * @param array $subclass
 *
 * @return int|false
 */
function createSubclass($subclass)
{
  GLOBAL $db;

  $query = new InsertQuery('subclasses');
  $query->addField('class_id', $subclass['class_id']);
  $query->addField('name', $subclass['name']);
  $query->addField('description', $subclass['description']);
  $query->addField('source_id', $subclass['source_id']);

  return $db->insert($query);
}

/**
 * @param array $subclass
 */
function updateSubclass($subclass)
{
  GLOBAL $db;

  $query = new UpdateQuery('subclasses');
  $query->addField('name', $subclass['name']);
  $query->addField('description', $subclass['description']);
  $query->addField('source_id', $subclass['source_id']);
  $query->addConditionSimple('id', $subclass['id']);

  $db->update($query);
}

/**
 * @param int $subclass_id
 */
function deleteSubclass($subclass_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('subclasses');
  $query->addConditionSimple('id', $subclass_id);
  $db->delete($query);
}

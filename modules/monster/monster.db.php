<?php

/******************************************************************************
 *
 *  Monster.
 *
 ******************************************************************************/
function installMonster()
{
  GLOBAL $db;

  $query = new CreateQuery('monsters');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('monster_type_id', 'INTEGER', array('N'), 0);
  $query->addField('value', 'INTEGER', array('N'), 0);
  $query->addField('magic', 'INTEGER', array('N'), 0);
  $query->addField('attunement', 'INTEGER', array('N'), 0);
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('monster_types');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('description', 'TEXT');
  $db->create($query);
}

function getMonsterPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('monsters');
  $query->addField('id')
    ->addField('name')
    ->addField('monster_type_id')
    ->addField('value')
    ->addField('magic')
    ->addField('attunement')
    ->addField('description');
  $query->addOrder('id');
  //  $query->addOrder('monster_type_id');
  //  $query->addOrder('name');
  //  $query->addPager($page);

  $results = $db->select($query);
  if (!$results)
  {
    return array();
  }
  return $results;
}

function getMonster($id)
{
  GLOBAL $db;

  $query = new SelectQuery('monsters');
  $query->addField('id')
    ->addField('name')
    ->addField('monster_type_id')
    ->addField('value')
    ->addField('magic')
    ->addField('attunement')
    ->addField('description');
  $query->addConditionSimple('id', $id);
  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createMonster($monster)
{
  GLOBAL $db;

  $query = new InsertQuery('monsters');
  $query->addField('name', $monster['name'])
    ->addField('monster_type_id', $monster['monster_type_id'])
    ->addField('value', $monster['value'])
    ->addField('magic', $monster['magic'])
    ->addField('attunement', $monster['attunement'])
    ->addField('description', $monster['description']);

  return $db->insert($query);
}

function updateMonster($monster)
{
  GLOBAL $db;

  $query = new UpdateQuery('monsters');
  $query->addField('name', $monster['name'])
    ->addField('monster_type_id', $monster['monster_type_id'])
    ->addField('value', $monster['value'])
    ->addField('magic', $monster['magic'])
    ->addField('attunement', $monster['attunement'])
    ->addField('description', $monster['description']);
  $query->addConditionSimple('id', $monster['id']);

  $db->update($query);
}

function deleteMonster($id)
{

}

/******************************************************************************
 *
 *  Monster Type.
 *
 ******************************************************************************/

function getMonsterTypeList()
{
  GLOBAL $db;

  $query = new SelectQuery('monster_types');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}

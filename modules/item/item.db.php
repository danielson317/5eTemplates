<?php


/******************************************************************************
 *
 *  Item.
 *
 ******************************************************************************/
function installItem()
{

}

function getItemPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('items');
  $query->addField('id')
    ->addField('name')
    ->addField('item_type_id')
    ->addField('value')
    ->addField('magic')
    ->addField('attunement')
    ->addField('description');
  $query->addOrder('item_type_id');
  $query->addOrder('name');
  $query->addPager($page);

  $results = $db->select($query);
  if (!$results)
  {
    return array();
  }
  return $results;
}

function getItem($id)
{
  GLOBAL $db;

  $query = new SelectQuery('items');
  $query->addField('id')
        ->addField('name')
        ->addField('item_type_id')
        ->addField('value')
        ->addField('magic')
        ->addField('attunement')
        ->addField('description');
  $query->addCondition('id', ':id');
  $args = array(
    ':id' => $id,
  );

  $results = $db->select($query, $args);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createItem($item)
{
  GLOBAL $db;

  $query = new InsertQuery('items');
  $query->addField('name')
        ->addField('item_type_id')
        ->addField('value')
        ->addField('magic')
        ->addField('attunement')
        ->addField('description');
  $args = SQLite::buildArgs($item);

  return $db->insert($query, $args);
}

function updateItem($item)
{
  GLOBAL $db;

  $query = new UpdateQuery('items');
  $query->addField('name')
        ->addField('item_type_id')
        ->addField('value')
        ->addField('magic')
        ->addField('attunement')
        ->addField('description');
  $query->addCondition('id');
  $args = SQLite::buildArgs($item);

  $db->update($query, $args);
}

function deleteItem($id)
{

}

/******************************************************************************
 *
 *  Item Type.
 *
 ******************************************************************************/

function getItemTypeList()
{
  GLOBAL $db;

  $query = new SelectQuery('item_types');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}
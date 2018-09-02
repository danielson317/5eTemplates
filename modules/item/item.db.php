<?php
/******************************************************************************
 *
 *  Install.
 *
 ******************************************************************************/
function installItem()
{
  GLOBAL $db;

  $query = new CreateQuery('items');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $query->addField('item_type_id', 'INTEGER', array('N'), 0);
  $query->addField('value', 'INTEGER', array('N'), 0);
  $query->addField('attunement', 'INTEGER', array('N'), 0);
  $query->addField('description', 'TEXT');
  $db->create($query);

  $query = new CreateQuery('item_types');
  $query->addField('id', 'INTEGER', array('P', 'A'));
  $query->addField('name', 'TEXT', array('N'));
  $db->create($query);
}



/******************************************************************************
 *
 *  Item.
 *
 ******************************************************************************/

/**
 * @param int $page
 * @return array
 */
function getItemPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('items');
  $query->addField('id')
    ->addField('name')
    ->addField('item_type_id')
    ->addField('value')
    ->addField('attunement')
    ->addField('description');
  $query->addOrder('id');
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

function createItem($item)
{
  GLOBAL $db;

  $query = new InsertQuery('items');
  $query->addField('name', $item['name'])
        ->addField('item_type_id', $item['item_type_id'])
        ->addField('value', $item['value'])
        ->addField('attunement', $item['attunement'])
        ->addField('description', $item['description']);

  return $db->insert($query);
}

function updateItem($item)
{
  GLOBAL $db;

  $query = new UpdateQuery('items');
  $query->addField('name', $item['name'])
        ->addField('item_type_id', $item['item_type_id'])
        ->addField('value', $item['value'])
        ->addField('attunement', $item['attunement'])
        ->addField('description', $item['description']);
  $query->addConditionSimple('id', $item['id']);

  $db->update($query);
}

function deleteItem($id)
{

}

/******************************************************************************
 *
 *  Item Type.
 *
 ******************************************************************************/

function getItemTypePager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('item_types');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addOrder('id');
  $query->addPager($page);

  $results = $db->select($query);
  if (!$results)
  {
    return array();
  }
  return $results;
}

function getItemTypeList()
{
  GLOBAL $db;

  $query = new SelectQuery('item_types');
  $query->addField('id')->addField('name', 'value');

  return $db->selectList($query);
}


function getItemType($id)
{
  GLOBAL $db;

  $query = new SelectQuery('item_types');
  $query->addField('id');
  $query->addField('name');
  $query->addField('description');
  $query->addConditionSimple('id', $id);
  $results = $db->select($query);
  if (!$results)
  {
    return FALSE;
  }
  $result = array_shift($results);
  return $result;
}

function createItemType($item)
{
  GLOBAL $db;

  $query = new InsertQuery('item_types');
  $query->addField('name', $item['name']);
  $query->addField('description', $item['description']);

  return $db->insert($query);
}

function updateItemType($item)
{
  GLOBAL $db;

  $query = new UpdateQuery('item_types');
  $query->addField('name', $item['name']);
  $query->addField('description', $item['description']);
  $query->addConditionSimple('id', $item['id']);

  $db->update($query);
}

function deleteItemType($id)
{
  GLOBAL $db;
  $query = new DeleteQuery('item_types');
  $query->addConditionSimple('id', $id);

  $db->delete($query);
}

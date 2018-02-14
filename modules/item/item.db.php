<?php


/******************************************************************************
 *
 *  Item.
 *
 ******************************************************************************/
function installItem()
{

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

function createItem($id)
{

}

function updateItem($item)
{
  GLOBAL $db;

  $query = new UpdateQuery('items');
  $query->addField('name', $item['name'])
        ->addField('item_type_id', $item['item_type_id'])
        ->addField('value', $item['value'])
        ->addField('magic', $item['magic'])
        ->addField('attunement', $item['attunement'])
        ->addField('description', $item['description']);
  $query->addCondition('id');
  $args = array(':id' => $id);

  $results = $db->update($query, $args);
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
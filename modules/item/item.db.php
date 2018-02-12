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

  $query = new Query(Query::OPERATION_SELECT, 'items');
  $query->addField('id')
        ->addField('name')
        ->addField('item_type_id')
        ->addField('value')
        ->addField('magic')
        ->addField('attunment')
        ->addField('description');
  $query->addCondition('id', ':id');
  $args = array(
    ':id' => $id,
  );

  $results = $db->executeQuery($query, $args);
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

function updateItem($id)
{

}

function deleteItem($id)
{

}

/******************************************************************************
 *
 *  Item Type.
 *
 ******************************************************************************/

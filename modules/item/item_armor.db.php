<?php
/***
 *
 * item.db.php
 */

/**
 *
 */
function installItemArmor()
{
  GLOBAL $db;

   $query = new CreateQuery('items_armor');
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N', 'U'));
  $query->addField('base_ac', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('dex_cap', CreateQuery::TYPE_INTEGER, 0, array('N'), 0); // 0: no cap, > 0 cap, < 0 No Dex.
  $query->addField('str_score', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('stealth_disadvantage', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addForeignKeyConstraint('item_id', 'id', 'items');
  $db->create($query);
}

/******************************************************************************
 *
 *  Item.
 *
 ******************************************************************************/

/**
 * @param int $item_id
 *
 * @return array|false
 */
function getItemArmor($item_id)
{
  GLOBAL $db;

  $query = new SelectQuery('items_armor');

  $query->addField('item_id', 'id');
  $query->addField('base_ac');
  $query->addField('dex_cap');
  $query->addField('str_score');
  $query->addField('stealth_disadvantage');

  $query->addConditionSimple('item_id', $item_id);

  return $db->selectObject($query);
}

function createItemArmor($item)
{
  GLOBAL $db;
  $query = new InsertQuery('items_armor');

  $query->addField('item_id', $item['id']);
  $query->addField('base_ac', $item['base_ac']);
  $query->addField('dex_cap', $item['dex_cap']);
  $query->addField('str_score', $item['str_score']);
  $query->addField('stealth_disadvantage', $item['stealth_disadvantage']);

  $db->insert($query);
}

function updateItemArmor($item)
{
  GLOBAL $db;
  $query = new UpdateQuery('items_armor');

  $query->addField('base_ac', $item['base_ac']);
  $query->addField('dex_cap', $item['dex_cap']);
  $query->addField('str_score', $item['str_score']);
  $query->addField('stealth_disadvantage', $item['stealth_disadvantage']);

  $query->addConditionSimple('item_id', $item['id']);
  $db->update($query);
}

/**
 * @param int $item_id
 */
function deleteItemArmor($item_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('items_armor');
  $query->addConditionSimple('item_id', $item_id);
  $db->delete($query);
}

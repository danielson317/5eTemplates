<?php
/***
 *
 * item.db.php
 */

/**
 *
 */
function installItemMagic()
{
  GLOBAL $db;

  // Magic.
  $query = new CreateQuery('item_magic');
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N', 'U', 'P'));
  $query->addField('rarity_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('bonus', CreateQuery::TYPE_STRING, 128, array('N'), 0); // +1 to AC, +2 to hit and damage, Resistant to Fire Damage.
  $query->addField('attunement', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('attunement_requirements', CreateQuery::TYPE_STRING, 1024, array('N'), 0); // Only usable by druid, or evil.
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
function getItemMagic($item_id)
{
  GLOBAL $db;

  $query = new SelectQuery('item_magic');

  // All items.
  $query->addField('item_id', 'id');
  $query->addField('rarity_id');
  $query->addField('bonus');
  $query->addField('attunement');
  $query->addField('attunement_requirements');

  $query->addConditionSimple('item_id', $item_id);

  return $db->selectObject($query);
}

function createItemMagic($item)
{
  GLOBAL $db;
  $query = new InsertQuery('item_magic');

  $query->addField('item_id', $item['id']);
  $query->addField('rarity_id', $item['rarity_id']);
  $query->addField('bonus', $item['bonus']);
  $query->addField('attunement', $item['attunement']);
  $query->addField('attunement_requirements', $item['attunement_requirements']);

  $db->insert($query);
}

function updateItemMagic($item)
{
  GLOBAL $db;
  $query = new UpdateQuery('item_magic');

  // All items.
  $query->addField('rarity_id', $item['rarity_id']);
  $query->addField('bonus', $item['bonus']);
  $query->addField('attunement', $item['attunement']);
  $query->addField('attunement_requirements', $item['attunement_requirements']);

  $query->addConditionSimple('item_id', $item['id']);
  $db->update($query);
}

/**
 * @param int $item_id
 */
function deleteItemMagic($item_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('item_magic');
  $query->addConditionSimple('item_id', $item_id);
  $db->delete($query);
}

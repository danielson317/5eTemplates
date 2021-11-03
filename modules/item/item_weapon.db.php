<?php
/***
 *
 * item.db.php
 */

/**
 *
 */
function installItemWeapon()
{
  GLOBAL $db;

  $query = new CreateQuery('items_weapon');
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N', 'U'));
  $query->addField('range_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('max_range_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('ammunition', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('finesse', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('heavy', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('light', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('loading', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('reach', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('thrown', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
  $query->addField('two_handed', CreateQuery::TYPE_BOOL, 0, array('N'), 0);
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
function getItemWeapon($item_id)
{
  GLOBAL $db;

  $query = new SelectQuery('items_weapon');

  $query->addField('item_id', 'id');
  $query->addField('range_id');
  $query->addField('max_range_id');
  $query->addField('ammunition');
  $query->addField('finesse');
  $query->addField('heavy');
  $query->addField('light');
  $query->addField('loading');
  $query->addField('reach');
  $query->addField('thrown');
  $query->addField('two_handed');

  $query->addConditionSimple('item_id', $item_id);

  return $db->selectObject($query);
}

function createItemWeapon($item)
{
  GLOBAL $db;
  $query = new InsertQuery('items_weapon');

  $query->addField('item_id', $item['id']);
  $query->addField('range_id', $item['range_id']);
  $query->addField('max_range_id', $item['max_range_id']);
  $query->addField('ammunition', $item['ammunition']);
  $query->addField('finesse', $item['finesse']);
  $query->addField('heavy', $item['heavy']);
  $query->addField('light', $item['light']);
  $query->addField('loading', $item['loading']);
  $query->addField('reach', $item['reach']);
  $query->addField('thrown', $item['thrown']);
  $query->addField('two_handed', $item['two_handed']);

  $db->insert($query);
}

function updateItemWeapon($item)
{
  GLOBAL $db;
  $query = new UpdateQuery('items_weapon');

  $query->addField('range_id', $item['range_id']);
  $query->addField('max_range_id', $item['max_range_id']);
  $query->addField('ammunition', $item['ammunition']);
  $query->addField('finesse', $item['finesse']);
  $query->addField('heavy', $item['heavy']);
  $query->addField('light', $item['light']);
  $query->addField('loading', $item['loading']);
  $query->addField('reach', $item['reach']);
  $query->addField('thrown', $item['thrown']);
  $query->addField('two_handed', $item['two_handed']);

  $query->addConditionSimple('item_id', $item['id']);
  $db->update($query);
}

/**
 * @param int $item_id
 */
function deleteItemWeapon($item_id)
{
  GLOBAL $db;

  $query = new DeleteQuery('items_weapon');
  $query->addConditionSimple('item_id', $item_id);
  $db->delete($query);
}

<?php

function installItemDamage()
{
  GLOBAL $db;

  // Item damage - one to many.
  $query = new CreateQuery('items_damage');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('N', 'P', 'A'));
  $query->addField('item_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('die_count', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('die_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('damage_type_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('versatile', CreateQuery::TYPE_BOOL, 0, array('N'));
  $query->addForeignKeyConstraint('item_id', 'id', 'items');
  $db->create($query);
}

function getItemDamageList($item_id)
{
  GLOBAL $db;

  $query = new SelectQuery('items_damage');
  $query->addField('id');
  $query->addField('die_count');
  $query->addField('die_id');
  $query->addField('damage_type_id');
  $query->addField('versatile');

  $query->addConditionSimple('item_id', $item_id);
  return $db->select($query);
}

function getItemDamage($item_damage_id)
{
  GLOBAL $db;

  $query = new SelectQuery('items_damage');
  $query->addField('item_id');
  $query->addField('die_count');
  $query->addField('die_id');
  $query->addField('damage_type_id');
  $query->addField('versatile');

  $query->addConditionSimple('id', $item_damage_id);
  return $db->selectObject($query);
}

function createItemDamage($item_damage)
{
  GLOBAL $db;

  $query = new InsertQuery('items_damage');
  $query->addField('item_id', $item_damage['item_id']);
  $query->addField('die_count', $item_damage['die_count']);
  $query->addField('die_id', $item_damage['die_id']);
  $query->addField('damage_type_id', $item_damage['damage_type_id']);
  $query->addField('versatile', $item_damage['versatile']);

  return $db->insert($query);
}

function updateItemDamage($item_damage)
{
  GLOBAL $db;

  $query = new InsertQuery('items_damage');
  $query->addField('die_count', $item_damage['die_count']);
  $query->addField('die_id', $item_damage['die_id']);
  $query->addField('damage_type_id', $item_damage['damage_type_id']);
  $query->addField('versatile', $item_damage['versatile']);

  $query->addConditionSimple('id', $item_damage['id']);
  return $db->insert($query);
}

<?php

function installDamage()
{
  GLOBAL $db;

  $query = new CreateQuery('damage');
  $query->addField('item_damage_id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('die_count', 'INTEGER', 0, array('N'), 0);
  $query->addField('die', 'INTEGER', 0, array('N'), 0);
  $query->addField('damage_type_id', 'INTEGER', 0, array('N'), 0);
  $db->create($query);
}
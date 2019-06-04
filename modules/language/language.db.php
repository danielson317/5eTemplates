<?php

function installLanguage()
{
  GLOBAL $db;

  $query = new CreateQuery('languages');
  $query->addField('id', 'INTEGER', 0, array('P', 'A'));
  $query->addField('name', 'TEXT', 32, array('N'));
  $query->addField('script_id', 'INTEGER');
  $query->addField('source_id', 'INTEGER');
  $query->addField('description', 'TEXT', 1024);
  $db->create($query);
}

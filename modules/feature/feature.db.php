<?php

function installFeature()
{
  GLOBAL $db;

  $query = new CreateQuery('features');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('A', 'P'), 0);
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'), 0);
  $query->addField('description', CreateQuery::TYPE_STRING, 1024, array('N'), 0);
  $db->create($query);

}

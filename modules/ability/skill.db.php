<?php

function installSkill()
{
  GLOBAL $db;

  $query = new CreateQuery('skills');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('code', CreateQuery::TYPE_STRING, 8, array('N'));
  $query->addField('name', CreateQuery::TYPE_STRING, 32, array('N'));
  $query->addField('ability_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('description', CreateQuery::TYPE_STRING, 1024);
  $db->create($query);

  $abilities = array_flip(getAbilityCodeList());
  $skills = array(
    array(
      'code' => 'Acro',
      'name' => 'Acrobatics',
      'ability_id' => $abilities['DEX'],
      'description' => '',
    ),
    array(
      'code' => 'AnmH',
      'name' => 'Animal Handling',
      'ability_id' => $abilities['WIS'],
      'description' => '',
    ),
    array(
      'code' => 'Arca',
      'name' => 'Arcana',
      'ability_id' => $abilities['INT'],
      'description' => '',
    ),
    array(
      'code' => 'Athl',
      'name' => 'Athletics',
      'ability_id' => $abilities['STR'],
      'description' => '',
    ),
    array(
      'code' => 'Decn',
      'name' => 'Deception',
      'ability_id' => $abilities['CHA'],
      'description' => '',
    ),
    array(
      'code' => 'Hist',
      'name' => 'History',
      'ability_id' => $abilities['INT'],
      'description' => '',
    ),
    array(
      'code' => 'Inst',
      'name' => 'Insight',
      'ability_id' => $abilities['WIS'],
      'description' => '',
    ),
    array(
      'code' => 'Intm',
      'name' => 'Intimidation',
      'ability_id' => $abilities['CHA'],
      'description' => '',
    ),
    array(
      'code' => 'Invn',
      'name' => 'Investigation',
      'ability_id' => $abilities['INT'],
      'description' => '',
    ),
    array(
      'code' => 'Med',
      'name' => 'Medicine',
      'ability_id' => $abilities['WIS'],
      'description' => '',
    ),
    array(
      'code' => 'Natr',
      'name' => 'Nature',
      'ability_id' => $abilities['INT'],
      'description' => '',
    ),
    array(
      'code' => 'Perc',
      'name' => 'Perception',
      'ability_id' => $abilities['WIS'],
      'description' => '',
    ),
    array(
      'code' => 'Perf',
      'name' => 'Performance',
      'ability_id' => $abilities['CHA'],
      'description' => '',
    ),
    array(
      'code' => 'Pers',
      'name' => 'Persuasion',
      'ability_id' => $abilities['CHA'],
      'description' => '',
    ),
    array(
      'code' => 'Rlgn',
      'name' => 'Religion',
      'ability_id' => $abilities['INT'],
      'description' => '',
    ),
    array(
      'code' => 'SloH',
      'name' => 'Slight of Hand',
      'ability_id' => $abilities['DEX'],
      'description' => '',
    ),
    array(
      'code' => 'Stl',
      'name' => 'Stealth',
      'ability_id' => $abilities['DEX'],
      'description' => '',
    ),
    array(
      'code' => 'Surv',
      'name' => 'Survival',
      'ability_id' => $abilities['WIS'],
      'description' => '',
    ),
  );

  foreach($skills as $skill)
  {
    createSkill($skill);
  }
}

function getSkillPager($page = 1)
{
  GLOBAL $db;

  $query = new SelectQuery('skills');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addField('ability_id');
  $query->addOrderSimple('name');
  $query->addPager($page);

  $results = $db->select($query);

  if (!$results)
  {
    return array();
  }
  return $results;
}

function getSkillList()
{
  GLOBAL $db;

  $query = new SelectQuery('skills');
  $query->addField('id')->addField('name', 'value');
  $query->addOrderSimple('name');

  return $db->selectList($query);
}

function getSkill($id)
{
  GLOBAL $db;

  $query = new SelectQuery('skills');
  $query->addField('id');
  $query->addField('name');
  $query->addField('code');
  $query->addField('description');
  $query->addField('ability_id');
  $query->addConditionSimple('id', $id);

  return $db->selectObject($query);
}

function createSkill($skill)
{
  GLOBAL $db;

  $query = new InsertQuery('skills');
  $query->addField('name', $skill['name']);
  $query->addField('code', $skill['code']);
  $query->addField('description', $skill['description']);
  $query->addField('ability_id', $skill['ability_id']);

  return $db->insert($query);
}

function updateSkill($skill)
{
  GLOBAL $db;

  $query = new UpdateQuery('skills');
  $query->addField('name', $skill['name']);
  $query->addField('code', $skill['code']);
  $query->addField('description', $skill['description']);
  $query->addField('ability_id', $skill['ability_id']);
  $query->addConditionSimple('id', $skill['id']);

  $db->update($query);
}

function deleteSkill($id)
{
  GLOBAL $db;

  $query = new DeleteQuery('skills');
  $query->addConditionSimple('id', $id);
  $db->delete($query);
}

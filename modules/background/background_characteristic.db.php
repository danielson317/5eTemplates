<?php

function installBackgroundCharacteristics()
{
  GLOBAL $db;

  $query = new CreateQuery('background_characteristics');
  $query->addField('id', CreateQuery::TYPE_INTEGER, 0, array('P', 'A'));
  $query->addField('background_id', CreateQuery::TYPE_INTEGER, 0, array('N'));
  $query->addField('characteristic', CreateQuery::TYPE_STRING, 8, array('N')); // special, trait, ideal, bond, flaw
  $query->addField('alignment', CreateQuery::TYPE_STRING, 2);
  $query->addField('description', CreateQuery::TYPE_STRING, 1024, array('N'));
  $query->addField('source_id', CreateQuery::TYPE_INTEGER, 0, array('N'), 0);
  $query->addField('source_location', CreateQuery::TYPE_INTEGER);
  $db->create($query);

  $backgrounds = array_flip(getBackgroundList());
  $sources = array_flip(getSourceList());
  $background_characteristics = array(
    array(
      'background_id' => $backgrounds['Acolyte'],
      'characteristic' => 'trait',
      'alignment' => '',
      'description' => 'I idolize a particular hero of my faith, and constantly refer to that person’s deeds and example.',
      'source_id' => $sources['BR'],
    ),
    array(
      'background_id' => $backgrounds['Acolyte'],
      'characteristic' => 'trait',
      'alignment' => '',
      'description' => 'I can find common ground between the fiercest enemies, empathizing with them and always working toward peace.',
      'source_id' => $sources['BR'],
    ),
    array(
      'background_id' => $backgrounds['Acolyte'],
      'characteristic' => 'trait',
      'alignment' => '',
      'description' => 'I see omens in every event and action. The gods try to speak to us, we just need to listen.',
      'source_id' => $sources['BR'],
    ),
    array(
      'background_id' => $backgrounds['Acolyte'],
      'characteristic' => 'trait',
      'alignment' => '',
      'description' => 'Nothing can shake my optimistic attitude.',
      'source_id' => $sources['BR'],
    ),
    array(
      'background_id' => $backgrounds['Acolyte'],
      'characteristic' => 'trait',
      'alignment' => '',
      'description' => 'I quote (or misquote) sacred texts and proverbs in almost every situation.',
      'source_id' => $sources['BR'],
    ),
    array(
      'background_id' => $backgrounds['Acolyte'],
      'characteristic' => 'trait',
      'alignment' => '',
      'description' => 'I am tolerant (or intolerant) of other faiths and respect (or condemn) the worship of other gods.',
      'source_id' => $sources['BR'],
    ),
    array(
      'background_id' => $backgrounds['Acolyte'],
      'characteristic' => 'trait',
      'alignment' => '',
      'description' => 'I’ve enjoyed fine food, drink, and high society among my temple’s elite. Rough living grates on me.',
      'source_id' => $sources['BR'],
    ),
    array(
      'background_id' => $backgrounds['Acolyte'],
      'characteristic' => 'trait',
      'alignment' => '',
      'description' => 'I’ve spent so long in the temple that I have little practical experience dealing with people in the outside world.',
      'source_id' => $sources['BR'],
    ),
    array(
      'background_id' => $backgrounds['Acolyte'],
      'characteristic' => 'ideal',
      'alignment' => 'l',
      'description' => 'Tradition. The ancient traditions of worship and sacrifice must be preserved and upheld.',
      'source_id' => $sources['BR'],
    ),
  );

  foreach ($background_characteristics as $background_characteristic)
  {
    createBackgroundCharacteristic($background_characteristic);
  }
}

function createBackgroundCharacteristic($background_characteristic)
{
  GLOBAL $db;

  $query = new InsertQuery('background_characteristics');
  $query->addField('background_id', $background_characteristic['background_id']);
  $query->addField('characteristic', $background_characteristic['characteristic']);
  $query->addField('alignment', $background_characteristic['alignment']);
  $query->addField('description', $background_characteristic['description']);
  $db->insert($query);
}

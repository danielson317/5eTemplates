<?php

function characterDisplay($character_id)
{
  $character = getCharacter($character_id);

  $output = htmlWrap('h3', $character['name']);

  /***********************
   * Primary.
   ***********************/
  $group = '';

  // Gender
  $group .= lineItem('Gender',  '(' . getGenderList($character['gender']) . ')');

  // Race.
  $list = getRaceList();
  $sublist = getSubraceList();
  $race = $list[$character['race_id']];
  if ($character['subrace_id'])
  {
   $race .= ' (' . $sublist[$character['subrace_id']] . ')';
  }
  $group .= lineItem('Race', $race);

  // Class.
  $list = getClassList();
  $sublist = getSubclassList();
  $character_classes = getCharacterClassList($character_id);
  foreach($character_classes as $character_class)
  {
    $class = $list[$character_class['class_id']];
    if ($character_class['subclass_id'])
    {
      $class .= ' (' . $sublist[$character_class['subclass_id']] . ')';
    }
    $class .= ' ' . $character_class['level'];
    $group .= lineItem('Class', $class);
  }

  // Background.
  $list = getBackgroundList();
  $group .= lineItem('Background', $list[$character['background_id']]);

  // Alignment.
  $list = getAlignmentList();
  $group .= lineItem('Alignment', $list[$character['alignment']]);

  // Players.
  $list = getPlayerList();
  $group .= lineItem('Player', $list[$character['player_id']]);

  // Primary group.
  $output .= htmlWrap('div', $group, array('class' => array('primary', 'group')));

  /***********************
   * Abilities.
   ***********************/
  $group = '';
  $list = getAbilityList();
  $character_abilities = getCharacterAbilityList($character_id);
  foreach($character_abilities as $character_ability)
  {
    $group .= lineItem($list[$character_ability['ability_id']], getAbilityModifier($character_ability['score']) . ' (' . $character_ability['score'] . ')');
  }
  $output .= htmlWrap('div', $group, array('class' => array('abilities', 'group')));

  // Abilities.
  $group = '';
  $list = getSkillList();
  $character_skills = getCharacterSkillList($character_id);
  $count = 0;
  foreach($character_skills as $character_skill)
  {
    $skill = getSkill($character_skill['skill_id']);
    $character_ability = getCharacterAbility($character_id, $skill['ability_id']);
    $group .= lineItem($list[$character_skill['skill_id']], getSkillModifier($character_ability['score'], 1, $character_skill['proficiency_multiplier']) . ' (x' . $character_skill['proficiency_multiplier'] . ')');
    $count++;
    if (($count % 6) === 0)
    {
      $output .= htmlWrap('div', $group, array('class' => array('skills-' . ($count/6), 'group')));
      $group = '';
    }
  }
  $output .= htmlWrap('div', $group, array('class' => array('skills-' . ($count/6 + 1), 'group')));

  return htmlWrap('div', $output, array('id' => 'character_summary'));
}

function printCharacterSheet()
{
  ob_start();
  include ROOT_PATH . '/themes/default/templates/character.tpl.php';
  return ob_get_clean();
}

function getCharacterProficiencyTable($character_id)
{
  $proficiency_table = new TableTemplate('proficiencies');

  // Languages
  $languages = getLanguageList();
  $character_language_maps = getCharacterLanguageList($character_id);
  $list = array();
  foreach($character_language_maps as $character_language_map)
  {
    $attr = array(
      'query' => array(
        'character_id' => $character_id,
        'language_id' => $character_language_map['language_id'],
      ),
      'class' => array('language'),
    );
    $list[] = a($languages[$character_language_map['language_id']], '/ajax/character/language', $attr);
  }
  $proficiency_table->addRow(array('Languages', implode(', ', $list)));

  $proficiency_types = array();

  // Items types.
  $character_item_type_proficiency_map = getCharacterItemTypeProficiencyList($character_id);
  foreach($character_item_type_proficiency_map as $character_item_type_proficiency)
  {
    $proficiency = getItemType($character_item_type_proficiency['item_type_id']);

    // Find the highest parent item type. If the item type is already the highest
    // use itself as the parent.
    $item_type = $proficiency;
    $item_type_id = $proficiency['parent_item_type_id'];
    while ((int)$item_type_id !== 0)
    {
      $item_type = getItemType($item_type_id);
      $item_type_id = $item_type['parent_item_type_id'];
    }
    $item_type_id = $item_type['id'];

    if (!isset($proficiency_types[$item_type_id]))
    {
      $proficiency_types[$item_type_id] = array();
    }
    $proficiency['type'] = 'item_type';
    $proficiency_types[$item_type_id][] = $proficiency;
  }

  // Items.
  $character_item_proficiency_map = getCharacterItemProficiencyList($character_id);
  foreach($character_item_proficiency_map as $character_item_proficiency)
  {
    $proficiency = getItem($character_item_proficiency['item_id']);

    $item_type_id = $proficiency['item_type_id'];
    do
    {
      $item_type = getItemType($item_type_id);
      $item_type_id = $item_type['parent_item_type_id'];
    } while ((int)$item_type['parent_item_type_id'] !== 0);

    if (!isset($proficiency_types[$item_type['id']]))
    {
      $proficiency_types[$item_type['id']] = array();
    }
    $proficiency['type'] = 'item';
    $proficiency['proficiency'] = $character_item_proficiency['proficiency'];
    $proficiency_types[$item_type['id']][] = $proficiency;
  }

  // Render lists.
  $item_types = getItemTypeList();
  foreach($proficiency_types as $proficiency_item_type_id => $proficiency_type)
  {
    $row = array();
    foreach($proficiency_type as $proficiency)
    {
      if ($proficiency['type'] === 'item_type')
      {
        $attr = array(
          'query' => array(
            'item_type_id' => $proficiency['id'],
            'character_id' => $character_id,
          ),
          'class' => array('item-type-proficiency'),
        );
        $row[] = a($proficiency['name'], '/ajax/character/item-type-proficiency', $attr);
      }
      else
      {
        $name = $proficiency['name'];
        if ($proficiency['proficiency'])
        {
          $name .= '(' . $proficiency['proficiency'] . 'x)';
        }

        $attr = array(
          'query' => array(
            'item_id' => $proficiency['id'],
            'character_id' => $character_id,
          ),
          'class' => array('item-proficiency'),
        );
        $row[] = a($name, '/ajax/character/item-proficiency', $attr);
      }
    }
    $proficiency_table->addRow(array($item_types[$proficiency_item_type_id], implode(', ', $row)));
  }

  // Create links.
  $links = array();
  $attr = array(
    'query' => array('character_id' => $character_id),
    'class' => array('add-language'),
  );
  $links[] = a('Add Language', '/ajax/character/language', $attr);

  $attr = array(
    'query' => array('character_id' => $character_id),
    'class' => array('add-item-proficiency'),
  );
  $links[] = a('Add Item', '/ajax/character/item-proficiency', $attr);

  $attr = array(
    'query' => array('character_id' => $character_id),
    'class' => array('add-item-type-proficiency'),
  );
  $links[] = a('Add Item Type', '/ajax/character/item-type-proficiency', $attr);

  $proficiency_table->addRow(array(join(', ', $links)), array('colspan' => 2));

  return $proficiency_table;
}

function getGenderList($id = FALSE)
{
  $list = array(
    'm' => 'Male',
    'f' => 'Female',
  );

  return getListItem($list, $id);
}

function getCharacterProficiencyBonusForLevel($level)
{
  return floor((7 + $level) / 4);
}

function getCharacterLevel($character_id)
{
  $class_list = getCharacterClassList($character_id);
  $level = 0;
  foreach ($class_list as $class)
  {
    $level += $class['level'];
  }
  return $level;
}

function getCharacterProficiencyBonus($character_id)
{
  $level = getCharacterLevel($character_id);
  return getCharacterProficiencyBonusForLevel($level);
}
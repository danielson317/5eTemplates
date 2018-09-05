<?php

/******************************************************************************
 *
 * List
 *
 ******************************************************************************/

function spellList()
{
  $page = getUrlID('page', 1);
  $spells = getSpellPager($page);

  $template = new ListPageTemplate('Spells');
  $template->addCssFilePath('/themes/default/css/spell.css');

  // Operations.
  $attr = array(
    'href' => 'spell',
  );
  $template->addOperation(htmlWrap('a', 'New Spell', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($spells) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  $attr = array(
    'href' => 'spells/print?page=' . $page,
  );
  $template->addOperation(htmlWrap('a', 'Print Cards', $attr));

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('spell-list'));
  $table->setHeader(array('Name', 'Level', 'School', 'Description'));

  $schools = getSchoolList();
  $spells = getSpellPager($page);
  foreach ($spells as $spell)
  {
    $row = array();
    $attr = array(
      'href' => '/spell?id=' . $spell['id'],
    );
    $row[] = htmlWrap('a', $spell['name'], $attr);
    $row[] = $spell['level'];
    $row[] = $schools[$spell['school_id']];
    $row[] = $spell['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

/******************************************************************************
 *
 * Upsert
 *
 ******************************************************************************/

function spellUpsertForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/item.css');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(spellUpsertSubmit());
  }

  $spell_id = getUrlID('id');

  $form = new Form('spell_form');
  $title = 'Add New Spell';
  if ($spell_id)
  {
    $spell = getSpell($spell_id);
    $form->setValues($spell);
    $title = 'Edit spell ' . htmlWrap('em', $spell['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldHidden('id');
  $form->addField($field);

  // Name.
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Source.
  $options = getSourceList();
  $field = new FieldSelect('source_id', 'Source', $options);
  $field->setValue(1);
  $form->addField($field);

  // School.
  $options = getSchoolList();
  $field = new FieldSelect('school_id', 'School', $options);
  $form->addField($field);

  // Level.
  $options = getLevelList();
  $field = new FieldSelect('level', 'Level', $options);
  $field->setValue(1);
  $form->addField($field);

  // Speed.
  $options = getCastingTimeList();
  $field = new FieldSelect('speed', 'Casting Time', $options);
  $field->setValue('6');
  $form->addField($field);

  // Range.
  $options = getRangeList();
  $field = new FieldSelect('range', 'Range', $options);
  $field->setValue('5');
  $form->addField($field);

  // Ritual.
  $field = new FieldCheckbox('ritual', 'Can be Cast as a Ritual');
  $form->addField($field);

  // Concentration.
  $field = new FieldCheckbox('concentration', 'Requires Concentration');
  $form->addField($field);

  // Verbal.
  $field = new FieldCheckbox('verbal', 'Verbal');
  $form->addField($field);

  // Semantic.
  $field = new FieldCheckbox('semantic', 'Semantic (gesture)');
  $form->addField($field);

  // Material.
  $field = new FieldText('material', 'Materials');
  $form->addField($field);

  // Duration
  $options = getDurationList();
  $field = new FieldSelect('duration', 'Duration', $options);
  $field->setValue(1);
  $form->addField($field);

  // AOE
  $options = array(0 => '') + getAoeList();
  $field = new FieldSelect('aoe_id', 'Area of Effect', $options);
  $field->setValue(0);
  $form->addField($field);

  // AOE Range
  $options = array(0 => '') + getRangeList();
  $field = new FieldSelect('aoe_range', 'AOE Range', $options);
  $field->setValue(0);
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('alternate', 'At Higher Levels');
  $form->addField($field);

  // Submit
  $value = 'Create';
  if ($spell_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  $template->setForm($form);

  return $template;
}

function spellUpsertSubmit()
{
  $spell = $_POST;
  $spell['ritual'] = isset($_POST['ritual']) ? 1 : 0;
  $spell['concentration'] = isset($_POST['concentration']) ? 1 : 0;
  $spell['verbal'] = isset($_POST['verbal']) ? 1 : 0;
  $spell['semantic'] = isset($_POST['semantic']) ? 1 : 0;
  unset($spell['submit']);

  if ($spell['id'])
  {
    updateSpell($spell);
    echo htmlWrap('h3', 'Spell ' . htmlWrap('em', $spell['name']) . ' (' . $spell['id'] . ') updated.');
  }
  else
  {
    unset($spell['id']);
    $spell['id'] = createSpell($spell);
    echo htmlWrap('h3', 'New spell ' . htmlWrap('em', $spell['name']) . ' (' . $spell['id'] . ') created.');
  }
}

/******************************************************************************
 *
 * Print
 *
 ******************************************************************************/

function spellPrintForm()
{
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    return spellPrintSubmit();
  }

  $page = getUrlID('page', 1);
  $items = getItemPager($page);

  $template = new ListPageTemplate();
  $template->setTitle('Print Item Cards');
  $template->addJsFilePath('/themes/default/js/print_list.js');
  $template->setBodyAttr(array('class' => array('print-page')));

  // Operations.
  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($items) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  // List.
  $table = new TableTemplate();
  $table->setAttr('class', array('spell-print-list', 'print-list'));
  $table->setHeader(array('Qty', 'Name', 'Level', 'Type', 'Description'));

  $schools = getSchoolList();
  $levels = getLevelList();
  $spells = getSpellPager($page);
  foreach ($spells as $spell)
  {
    $row = array();

    // Qty.
    $qty = '';

    // Count.
    $attr = array(
      'type' => 'integer',
      'name' => $spell['id'],
      'class' => array('button'),
    );
    $qty .= htmlSolo('input', $attr);

    // Minus.
    $attr = array(
      'type' => 'button',
      'name' => $spell['id'] . '_minus',
      'value' => '-',
      'class' => array('minus'),
      'tabindex' => -1,
    );
    $qty .= htmlSolo('input', $attr);

    // Plus.
    $attr = array(
      'type' => 'button',
      'name' => $spell['id'] . '_plus',
      'value' => '+',
      'class' => array('plus'),
      'tabindex' => -1,
    );
    $qty .= htmlSolo('input', $attr);
    $row[] = $qty;

    $row[] = $spell['name'];
    $row[] = $levels[$spell['level']];
    $row[] = $schools[$spell['school_id']];
    $row[] = $spell['description'];
    $table->addRow($row);
  }

  $form = $table->__toString();

  $attr = array(
    'type' => 'submit',
    'name' => 'print',
    'value' => 'Print',
  );
  $form .= htmlSolo('input', $attr);

  $attr = array(
    'id' => 'spell-print-form',
    'method' => 'post',
  );
  $template->setList(htmlWrap('form', $form, $attr));

  return $template;
}

function spellPrintSubmit()
{
  $template = new HTMLTemplate();
  $template->addCssFilePath('/themes/default/css/spell.css');
  $template->setTitle('Print Spells');

  $output = '';
  $spells = $_POST;
  unset($spells['print']);
  $schools = getSchoolList();
  $levels = getLevelList();
  $casting_times = getCastingTimeList();
  $duration = getDurationList();
  $ranges = getRangeList();
  foreach($spells as $spell_id => $qty)
  {
    if (!$qty || $qty < 0)
    {
      continue;
    }
    for ($k = 0; $k < $qty; $k++)
    {
      $spell = getSpell($spell_id);

      // School.
//      $spell['school'] = $schools[$spell['school_id']];
      if ($spell['level'] <= 0)
      {
        $spell['school'] = $schools[$spell['school_id']] . ' ' . $levels[$spell['level']];
      }
      elseif ($spell['level'] <= 9)
      {
        $spell['school'] = $levels[$spell['level']] . '-level ' . $schools[$spell['school_id']];
      }
      else
      {
        $spell['school'] = $levels[$spell['level']];
      }

      // Attributes.
      $spell['speed'] = $casting_times[$spell['speed']];
      $spell['range'] = $ranges[$spell['range']];
      $spell['duration'] = $duration[$spell['duration']];

      // Components.
      $spell['components'] = '';
      if ($spell['verbal'])
      {
        $spell['components'] .= 'V';
      }
      if ($spell['semantic'])
      {
        $spell['components'] .= ', S';
      }
      if ($spell['material'])
      {
        $spell['components'] .= ', M (' . $spell['material'] . ')';
      }
      $spell['components'] = trim($spell['components'], ' ,');

      // Text fields.
      $spell['description'] = str_replace("\n", '<br>', $spell['description']);
      $output .= printSpellCard($spell);
    }
  }
  $template->setBody($output);
  return $template;
}

/******************************************************************************
 *
 * Range.
 *
 ******************************************************************************/
function rangeList()
{
  $page = getUrlID('page', 1);
  $ranges = getRangePager($page);

  $template = new ListPageTemplate('Ranges');
  $template->addCssFilePath('/themes/default/css/item.css');

  // Operations.
  $attr = array(
    'href' => 'range',
  );
  $template->addOperation(htmlWrap('a', 'New Range', $attr));

  if ($page > 1)
  {
    $attr = array(
      'href' => '?page=' . ($page - 1),
    );
    $template->addOperation(htmlWrap('a', 'Prev Page', $attr));
  }

  if (count($ranges) >= DEFAULT_PAGER_SIZE)
  {
    $attr = array(
      'href' => '?page=' . ($page + 1),
    );
    $template->addOperation(htmlWrap('a', 'Next Page', $attr));
  }

  // List
  $table = new TableTemplate();
  $table->setAttr('class', array('range-list'));
  $table->setHeader(array('ID', 'Name', 'Description'));

  foreach($ranges as $range)
  {
    $row = array();
    $attr = array(
      'href' => 'range?id=' . $range['id'],
    );
    $row[] = $range['id'];
    $row[] = htmlWrap('a', $range['name'], $attr);
    $row[] = $range['description'];
    $table->addRow($row);
  }
  $template->setList($table);
  return $template;
}

function rangeUpsertForm()
{
  $template = new FormPageTemplate();
  $template->addCssFilePath('/themes/default/css/item.css');

  // Submit.
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
  {
    $template->addMessage(rangeUpsertSubmit());
  }

  $item_id = getUrlID('id');

  $form = new Form('range_form');
  $title = 'Add Range';
  if ($item_id)
  {
    $item = getRange($item_id);
    $form->setValues($item);
    $title = 'Edit range ' . htmlWrap('em', $item['name']);
  }
  $form->setTitle($title);

  // ID.
  $field = new FieldNumber('id', 'ID');
  $form->addField($field);

  // Name.
  $field = new FieldText('name', 'Name');
  $form->addField($field);

  // Description.
  $field = new FieldTextarea('description', 'Description');
  $form->addField($field);

  // Submit
  $value = 'Create';
  if ($item_id)
  {
    $value = 'Update';
  }
  $field = new FieldSubmit('submit', $value);
  $form->addField($field);

  $template->setForm($form);

  return $template;
}

function rangeUpsertSubmit()
{
//  $range = $_POST;
//  unset($range['submit']);
//
//  if ($range['id'])
//  {
//    updateRange($range);
//    return htmlWrap('h3', 'Range ' . htmlWrap('em', $range['name']) . ' (' . $range['id'] . ') updated.');
//  }
//  else
//  {
//    createRange($range);
//    return htmlWrap('h3', 'New range ' . htmlWrap('em', $range['name']) . ' (' . $range['id'] . ') created.');
//  }
}

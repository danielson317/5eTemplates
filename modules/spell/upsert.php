<head>
  <link rel="stylesheet" href="/themes/default/css/page.css">
  <link rel="stylesheet" href="/themes/default/css/form.css">
  <link rel="stylesheet" href="/themes/default/css/spell.css">
</head>
<body class="spell-insert-form">
<?php

include '../../libraries/bootstrap.inc.php';

if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
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

$output = '';

$output .= menu();

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
$options = getSpeedList();
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
$field = new FieldCheckbox('concentration', 'Semantic (gesture)');
$form->addField($field);

// Material.
$field = new FieldText('material', 'Materials');
$form->addField($field);

// Duration
$options = getSpeedList();
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
$output .= $form;

echo $output;
?>
</body>

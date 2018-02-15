<head>
  <link rel="stylesheet" href="/themes/default/css/page.css">
  <link rel="stylesheet" href="/themes/default/css/form.css">
</head>
<body class="spell-insert-form">
<?php

include '../../libraries/bootstrap.inc.php';

if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
{
  $item = $_POST;
  $item['magic'] = isset($_POST['magic']) ? 1 : 0;
  $item['attunement'] = isset($_POST['attunement']) ? 1 : 0;
  unset($item['submit']);

  if ($item['id'])
  {
    updateItem($item);
    echo htmlWrap('h3', 'Item ' . htmlWrap('em', $item['name']) . ' (' . $item['id'] . ') updated.');
  }
  else
  {
    unset($item['id']);
    $item['id'] = createItem($item);
    echo htmlWrap('h3', 'New item ' . htmlWrap('em', $item['name']) . ' (' . $item['id'] . ') created.');
  }
}

// Links
$attr = array(
  'href' => '/modules/item/list.php',
);
echo htmlWrap('a', 'Item List', $attr);

$item_id = getUrlID('id');

$form = new Form('item_form');
$title = 'Add New Item';
if ($item_id)
{
  $item = getItem($item_id);
  $form->setValues($item);
  $title = 'Edit item ' . htmlWrap('em', $item['name']);
}
$form->setTitle($title);

// ID.
$field = new FieldHidden('id');
$form->addField($field);

// Name.
$field = new FieldText('name', 'Name');
$form->addField($field);

// Type.
$options = getItemTypeList();
$field = new FieldSelect('item_type_id', 'Type', $options);
$form->addField($field);

// Value.
$field = new FieldText('value', 'Value (GP)');
$form->addField($field);

// Magic.
$field = new FieldCheckbox('magic', 'Magical');
$form->addField($field);

// Attunement.
$field = new FieldCheckbox('attunement', 'Requires Attunement');
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
echo $form;

?>
</body>

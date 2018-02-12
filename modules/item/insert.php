<head>
  <link rel="stylesheet" href="/themes/default/css/form.css">
</head>
<body class="spell-insert-form">
<?php

include '../../libraries/bootstrap.inc.php';

if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
{

}
else
{
  $item = getItem(getUrlID('id'));

  $form = new Form('spell_insert_form');

  // Name.
  $field = new FieldText('name', 'Name');
  $form->addField($field)->setValue($item['name']);

  echo $form;
}
?>
</div>
</body>

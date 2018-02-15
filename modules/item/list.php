<head>
  <link rel="stylesheet" href="/themes/default/css/page.css">
</head>
<body class="spell-insert-form">
<?php

include '../../libraries/bootstrap.inc.php';

$page = getUrlID('page', 1);

// Title
$output = htmlWrap('h1', 'All Items');

// Links
$attr = array(
  'href' => '/modules/item/upsert.php',
);
$output .= htmlWrap('a', 'Create New Items', $attr);

// Table.
$table = new TableTemplate();
$table->setAttr('class', array('item-list'));
$table->addHeader(array('Name', 'Value', 'Type', 'Description'));

$item_types = getItemTypeList();
$items = getItemPager($page);
foreach($items as $item)
{
  $row = array();
  $attr = array(
    'href' => '/modules/item/upsert.php?id=' . $item['id'],
  );
  $row[] = htmlWrap('a', $item['name'], $attr);
  $row[] = $item['value'];
  $row[] = $item_types[$item['item_type_id']];
  $row[] = $item['description'];
  $table->addRow($row);
}
$output .= $table;

echo $output;
?>
</body>
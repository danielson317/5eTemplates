<head>
  <link rel="stylesheet" href="/themes/default/css/page.css">
  <link rel="stylesheet" href="/themes/default/css/item.css">
</head>
<body class="spell-insert-form">
<?php

include '../../libraries/bootstrap.inc.php';

$page = getUrlID('page', 1);
$output = '';

$output .= menu();

// Title
$output .= htmlWrap('h1', 'All Items');

// Links
$links = '';
$attr = array(
  'href' => '/modules/item/upsert.php',
);
$links .= htmlWrap('a', 'New Item', $attr);

if ($page > 1)
{
  $attr = array(
    'href' => '?page=' . ($page - 1),
  );
  $links .= htmlWrap('a', 'Prev Page', $attr);
}

$attr = array(
  'href' => '?page=' . ($page + 1),
);
$links .= htmlWrap('a', 'Next Page', $attr);

$attr = array(
  'href' => '/modules/item/print.php?page=' . $page,
);
$links .= htmlWrap('a', 'Print Cards', $attr);

$output .= htmlWrap('div', $links, array('class' => array('operations')));

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
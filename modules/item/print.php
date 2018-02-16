<head>
  <link rel="stylesheet" href="/themes/default/css/page.css">
  <link rel="stylesheet" href="/themes/default/css/item.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="/modules/item/item_print.js"></script>
</head>
<body class="spell-print-page">
<?php

include '../../libraries/bootstrap.inc.php';

if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
{
  $items = $_POST;
  unset($items['print']);
  $item_types = getItemTypeList();
  foreach($items as $item_id => $qty)
  {
    if (!$qty || $qty < 0)
    {
      continue;
    }
    for ($k = 0; $k < $qty; $k++)
    {
      $item = getItem($item_id);
      $item['type'] = $item_types[$item['item_type_id']];
      echo printItemCard($item);
    }
  }
  die();
}

$page = getUrlID('page', 1);
$output = '';

$output .= menu();

// Title
$output .= htmlWrap('h1', 'Print Item Cards');

// Links
$links = '';
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

$output .= $links;

$output .= htmlWrap('h1', 'Set Quantity of cards to print.');

// Table.
$table = new TableTemplate();
$table->setAttr('class', array('item-print-list'));
$table->addHeader(array('Qty', 'Name', 'Value', 'Type', 'Description'));

$item_types = getItemTypeList();
$items = getItemPager($page);
foreach($items as $item)
{
  $row = array();

  // Qty.
  $qty = '';

  // Count.
  $attr = array(
    'type' => 'integer',
    'name' => $item['id'],
    'class' => array('button'),
  );
  $qty .= htmlSolo('input', $attr);

  // Minus.
  $attr = array(
    'type' => 'button',
    'name' => $item['id'] .'_minus',
    'value' => '-',
    'class' => array('minus'),
    'tabindex' => -1,
  );
  $qty .= htmlSolo('input', $attr);

  // Plus.
  $attr = array(
    'type' => 'button',
    'name' => $item['id'] .'_plus',
    'value' => '+',
    'class' => array('plus'),
    'tabindex' => -1,
  );
  $qty .= htmlSolo('input', $attr);
  $row[] = $qty;

  $row[] = $item['name'];
  $row[] = $item['value'];
  $row[] = $item_types[$item['item_type_id']];
  $row[] = $item['description'];
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
  'id' => 'item-print-form',
  'method' => 'post',
);
$output .= htmlWrap('form', $form, $attr);


echo $output;
?>
</body>
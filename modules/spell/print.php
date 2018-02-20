<head>
  <link rel="stylesheet" href="/themes/default/css/page.css">
  <link rel="stylesheet" href="/themes/default/css/spell.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="/themes/default/js/print_list.js"></script>
</head>
<body class="spell-print-page">
<?php

include '../../libraries/bootstrap.inc.php';

if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
{
  $spells = $_POST;
  unset($spells['print']);
  $schools = getSchoolList();
  $levels = getLevelList();
  $speeds = getSpeedList();
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
      $spell['school'] = $schools[$spell['school_id']];
      if ($spell['level'] == 0)
      {
        $spell['school'] .= ' ' . $levels[$spell['level']];
      }
      else
      {
        $spell['school'] = $levels[$spell['level']] . '-level ' . $spell['school'];
      }

      // Attributes.
      $spell['speed'] = $speeds[$spell['speed']];
      $spell['range'] = $ranges[$spell['range']];
      $spell['duration'] = $speeds[$spell['duration']];

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
      echo printSpellCard($spell);
    }
  }
  die();
}

$page = getUrlID('page', 1);
$output = '';

$output .= menu();

// Title
$output .= htmlWrap('h1', 'Print Spell Cards');

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
$table->setAttr('class', array('spell-print-list', 'print-list'));
$table->addHeader(array('Qty', 'Name', 'Level', 'Type', 'Description'));

$schools = getSchoolList();
$levels = getLevelList();
$spells = getSpellPager($page);
foreach($spells as $spell)
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
    'name' => $spell['id'] .'_minus',
    'value' => '-',
    'class' => array('minus'),
    'tabindex' => -1,
  );
  $qty .= htmlSolo('input', $attr);

  // Plus.
  $attr = array(
    'type' => 'button',
    'name' => $spell['id'] .'_plus',
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
$output .= htmlWrap('form', $form, $attr);


echo $output;
?>
</body>
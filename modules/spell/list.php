<head>
  <link rel="stylesheet" href="/themes/default/css/page.css">
  <link rel="stylesheet" href="/themes/default/css/spell.css">
</head>
<body class="spell-insert-form">
<?php

include '../../libraries/bootstrap.inc.php';

$page = getUrlID('page', 1);
$output = '';

$output .= menu();

// Title
$output .= htmlWrap('h1', 'All Spells');

// Links
$links = '';
$attr = array(
  'href' => '/modules/spell/upsert.php',
);
$links .= htmlWrap('a', 'New Spell', $attr);

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
  'href' => '/modules/spell/print.php?page=' . $page,
);
$links .= htmlWrap('a', 'Print Cards', $attr);

$output .= htmlWrap('div', $links, array('class' => array('operations')));

// Table.
$table = new TableTemplate();
$table->setAttr('class', array('spell-list'));
$table->addHeader(array('Name', 'Level', 'School', 'Description'));

$schools = getSchoolList();
//$aoes = getAoeList();
$spells = getSpellPager($page);
foreach($spells as $spell)
{
  $row = array();
  $attr = array(
    'href' => '/modules/spell/upsert.php?id=' . $spell['id'],
  );
  $row[] = htmlWrap('a', $spell['name'], $attr);
  $row[] = $spell['level'];
  $row[] = $schools[$spell['school_id']];
  $row[] = $spell['description'];
  $table->addRow($row);
}
$output .= $table;

echo $output;
?>
</body>
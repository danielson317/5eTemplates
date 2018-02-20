<?php
/******************************************************************************
 *
 * DEBUG Helpers
 *
 ******************************************************************************/
function debugPrint($variable, $name = '', $die = TRUE)
{
  echo '<h1 class="debug">' . $name . '</h1>';
  echo '<pre>';
  print_r($variable);
  echo '</pre>';
  if ($die)
  {
    die();
  }
}

/******************************************************************************
 *
 * URL Helpers
 *
 ******************************************************************************/
function getUrlID($name, $default = FALSE)
{
  if (!isset($_GET[$name]) || !is_numeric($_GET[$name]))
  {
    return $default;
  }

  return abs($_GET[$name]);
}

function redirect($path, $statusCode = '303')
{
  header('Location: ' . $path, TRUE, $statusCode);
  die();
}

/******************************************************************************
 *
 * HTML Helpers
 *
 ******************************************************************************/
function buildAttr($attr)
{
  assert(is_array($attr), 'Attributes passed to buildAttr should be an array().');
  $attr_string = '';
  foreach ($attr as $name => $value)
  {
    if (is_array($value))
    {
      $value = implode(' ', $value);
    }
    $attr_string .= ' ' . $name . '="' . $value . '"';
  }

  return $attr_string;
}

function htmlWrap($tag, $content, $attr = array())
{
  return '<' . $tag . buildAttr($attr) . '>' . $content . '</' . $tag . '>';
}

function htmlSolo($tag, $attr)
{
  return '<' . $tag . buildAttr($attr) . '>';
}

/******************************************************************************
 *
 * HTML Templates
 *
 ******************************************************************************/

Class TableTemplate
{
  // Primary values.
  protected $header = array();
  protected $rows = array();

  protected $attr = array();

  /**
   * Standard constructor. Pass the path to the template file.
   */
  public function __construct($id = FALSE)
  {
    if ($id)
    {
      $attr['id'] = $id;
    }
  }

  public function addHeader($header)
  {
    $this->header = $header;
    return $this;
  }
  public function addRows($rows)
  {
    $this->rows = $rows;
    return $this;
  }
  public function addRow($row, $attr = array())
  {
    $this->rows[] = $row;
    return $this;
  }
  public function setAttr($name, $value)
  {
    $this->attr[$name] = $value;
  }
  public function __toString()
  {
    $output = '';

    // Generate table.
    $output .= $this->generateHTMLHeader();
    $output .= $this->generateHTMLRows();

    $output = htmlWrap('table', $output, $this->attr);
    return $output;
  }

  private function generateHTMLHeader()
  {
    // Header columns.
    $output = '';
    $count = 1;
    foreach ($this->header as $label)
    {
      $attr = array('class' => array('column-' . $count));
      $output .= htmlWrap('th', $label, $attr);
      $count++;
    }

    // Header wrappers.
    $output = htmlWrap('tr', $output);
    $output = htmlWrap('thead', $output);
    return $output;
  }

  private function generateHTMLRows()
  {
    $output = '';
    foreach ($this->rows as $row)
    {
      $row_output = '';
      $count = 1;
      foreach ($row as $cell)
      {
        $attr = array('class' => array('column-' . $count));
        $row_output .= htmlWrap('td', $cell, $attr);
        $count++;
      }
      $output .= htmlWrap('tr', $row_output);
    }
    $output = htmlWrap('tbody', $output);
    return $output;
  }
}

function menu()
{
  $output = '';

  $attr = array(
    'href' => '/modules/item/list.php',
  );
  $output .= htmlWrap('a', 'Items', $attr);

  $attr = array(
    'href' => '/modules/spell/list.php',
  );
  $output .= htmlWrap('a', 'Spells', $attr);

  $attr = array('class' => array('menu'));
  $output = htmlWrap('div', $output, $attr);
  return $output;
}
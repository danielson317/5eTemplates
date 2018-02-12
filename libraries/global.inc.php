<?php
/**
 * Template
 *
 * A class designed to handle all types of template output.
 */
Class TableTemplate
{
  // Primary values.
  protected $header = array();
  protected $rows = array();

  protected $class = '';
  protected $id = '';

  /**
   * Standard constructor. Pass the path to the template file.
   */
  public function __construct()
  {
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
  public function addClass($class)
  {
    $this->class = $class;
  }
  public function __toString()
  {
    $output = '';

    // Generate table.
    $output .= '<table class="' . $this->class . '">';
    $output .= $this->generateHTMLHeader();
    $output .= $this->generateHTMLRows();
    $output .= '</table>';

    // Yup.
    return $output;
  }
  private function generateHTMLHeader()
  {
    $output = '<thead>';
    $output .= '<tr>';
    $count = 1;
    foreach ($this->header as $label)
    {
      $output .= '<th class="column-' . $count . '">';
      $output .= $label;
      $output .= '</th>';
      $count++;
    }
    $output .= '</tr></thead>';
    return $output;
  }
  private function generateHTMLRows()
  {
    $output = '<tbody>';

    foreach ($this->rows as $row)
    {
      $output .= '<tr>';
      $count = 1;
      foreach ($row as $cell)
      {
        $output .= '<td class="column-' . $count . '">';
        $output .= $cell;
        $output .= '</td>';
        $count++;
      }
      $output .= '</tr>';
    }
    $output .= '</tbody>';
    return $output;
  }
}

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

function getUrlID($name, $default = FALSE)
{
  if (!isset($_GET[$name]) || !is_numeric($_GET[$name]))
  {
    return $default;
  }

  return abs($_GET[$name]);
}

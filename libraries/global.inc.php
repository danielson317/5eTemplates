<?php

define('DEFAULT_PAGER_SIZE', 100);
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
class URL
{
  protected $path = '';
  protected $query = array();
  protected $fragment = '';

  function __construct($url = FALSE)
  {
    if (!$url)
    {
      $url = $_SERVER['REQUEST_URI'];
    }

    // Home path.
    if ($url == '/')
    {
      $this->path = '/';
      return;
    }

    $start = strpos($url, '/') + 1;
    $end = strpos($url, '?');

    // No query string. Only the path is defined.
    if ($end === FALSE)
    {
      $this->path = substr($url, $start);
      return;
    }
    $this->path = substr($url, $start, $end - 1);

    // Build the query string.
    $query = substr($url, $end + 1);
    $query = explode('&', $query);
    foreach ($query as $parameter)
    {
      $parts = explode('=', $parameter);
      $this->query[$parts[0]] = isset($parts[1]) ? urldecode($parts[1]) : FALSE;
    }
  }

  function getPath()
  {
    return $this->path;
  }

  function getQuery()
  {
    return $this->query;
  }
}

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

function htmlSolo($tag, $attr = array())
{
  return '<' . $tag . buildAttr($attr) . '>';
}

function stringToAttr($string)
{
  $replace = array(' ', '_');
  return strtolower(str_replace($replace, '-', $string));
}

function optionList($list, $selected = FALSE)
{
  $output = '';
  foreach ($list as $key => $value)
  {
    $attr = array(
      'value' => $key,
    );
    if ($key == $selected)
    {
      $attr['selected'] = 'selected';
    }
    $output .= htmlWrap('option', $value, $attr);
  }
  return $output;
}

/******************************************************************************
 *
 * Global Lists.
 *
 ******************************************************************************/

function getDiceList()
{
  return array(
    '2' => 'd2',
    '4' => 'd4',
    '6' => 'd6',
    '8' => 'd8',
    '10' => 'd10',
    '12' => 'd12',
    '20' => 'd20',
    '100' => 'd100'
  );
}

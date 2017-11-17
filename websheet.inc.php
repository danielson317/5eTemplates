<?php

function dewordify($string)
{
  $search = [                 // www.fileformat.info/info/unicode/<NUM>/ <NUM> = 2018
    "\xC2\xAB",     // « (U+00AB) in UTF-8
    "\xC2\xBB",     // » (U+00BB) in UTF-8
    "\xE2\x80\x98", // ‘ (U+2018) in UTF-8
    "\xE2\x80\x99", // ’ (U+2019) in UTF-8
    "\xE2\x80\x9A", // ‚ (U+201A) in UTF-8
    "\xE2\x80\x9B", // ‛ (U+201B) in UTF-8
    "\xE2\x80\x9C", // “ (U+201C) in UTF-8
    "\xE2\x80\x9D", // ” (U+201D) in UTF-8
    "\xE2\x80\x9E", // „ (U+201E) in UTF-8
    "\xE2\x80\x9F", // ‟ (U+201F) in UTF-8
    "\xE2\x80\xB9", // ‹ (U+2039) in UTF-8
    "\xE2\x80\xBA", // › (U+203A) in UTF-8
    "\xE2\x80\x93", // – (U+2013) in UTF-8
    "\xE2\x80\x94", // — (U+2014) in UTF-8
    "\xE2\x80\xA6", // … (U+2026) in UTF-8
  ];

  $replacements = [
    '<<',
    '>>',
    "'",
    "'",
    "'",
    "'",
    '"',
    '"',
    '"',
    '"',
    '<',
    '>',
    '-',
    '-',
    '...'
  ];

  return str_replace($search, $replacements, $string);
}

function sanitize($data)
{
  if (is_null($data))
  {
    return '';
  }
  assert(is_string($data) || is_numeric($data) || is_bool($data), 'Non-string passed to sanitize!');

  $data = dewordify($data);
  $data = trim($data);
  $data = htmlspecialchars($data, ENT_IGNORE | ENT_QUOTES);
  return $data;
}

function buildAttr($attr)
{
  $attr_string = '';
  foreach ($attr as $name => $value)
  {
    if (is_numeric($name))
    {
      continue;
    }
    if (is_array($value))
    {
      $value = implode(' ', $value);
    }
    $attr_string .= ' ' . sanitize($name) . '="' . sanitize($value) . '"';
  }

  return $attr_string;
}

function divSimple($content, $class)
{
  return div($content, array('class' => array($class)));
}
function div($content, $attr = array())
{
  return '<div' . buildAttr($attr) . '>' . $content . '</div>';
}

function buildSummary()
{
  $output = '';

  // Name.
  // $temp = '';
  // $temp .= divSimple()
}

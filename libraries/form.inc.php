<?php

class Form
{
  protected $id;
  protected $attr = array();
  protected $fields = array();

  function __construct($id)
  {
    $this->id = $id;
  }

  function __toString()
  {
    $this->attr['id'] = $this->id;
    $output = '<form' . buildAttr($this->attr) . '>';
    foreach ($this->fields as $field)
    {
      $output .= $field;
    }
    $output .= '</form>';
    return $output;
  }

  function setAttr($name, $value)
  {
    $this->attr[$name] = $vaue;
    return '';
  }

  function addField(Field $field)
  {
    $this->fields[] = $field;
    return $field;
  }

  function getAttr($name)
  {
    return isset($this->attr[$name]) ? $this->attr[$name] : FALSE;
  }
}

abstract class Field
{
  protected $id;
  protected $attr = array();
  protected $label = '';
  protected $value = '';

  function __construct($id, $label = '')
  {
    $this->id = $id;
    $this->label = $label;
  }
  abstract function __toString();

  function setLabel($label)
  {
    $this->label = $label;
    return $this;
  }
  function setValue($value)
  {
    $this->value = $value;
    return $this;
  }
}

class FieldText extends Field
{
  function __toString()
  {
    // Wrapper.
    $attr = array('class' => array('field', 'text'));
    $output = '<div' . buildAttr($attr) . '>';

    // Label.
    $attr = array(
      'class' => array('label', 'text'),
      'for' => $this->id,
    );
    $output .= '<label' . buildAttr($attr) . '>' . $this->label . '</label>';

    // Input.
    $attr = $this->attr;
    $attr['id'] = $this->id;
    $attr['type'] = 'text';
    if ($this->value)
    {
      $attr['value'] = $this->value;
    }
    $output .= '<input' . buildAttr($attr) . '>';

    // Close.
    $output .= '</div>';
    return $output;
  }
}

class FieldSelect extends Field
{
  protected $options;

  function __construct($id, $name = '', $options = array())
  {
    parent::__construct($id, $name);
    $this->setOptions($options);
  }

  function __toString()
  {
    $this->attr['id'] = $this->id;
    $this->attr['type'] = 'text';

    $attr = array('class' => array('field', 'text'));
    $output = '<div' . buildAttr($attr) . '>';

    $attr = array(
      'class' => array('labe', 'text'),
      'for' => $this->id,
    );
    $output .= '<label' . buildAttr($attr) . '>' . $this->label . '</label>';
    $output .= '<select' . buildAttr($this->attr) . '>';
    $output .= '</div>';
    return $output;
  }

  function setOptions($options)
  {
    $this->options = $options;
    return $this;
  }
}

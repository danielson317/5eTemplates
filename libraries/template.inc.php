<?php

/******************************************************************************
 *
 * Page Templates.
 *
 ******************************************************************************/

/**
 * Class HTMLTemplate
 */
class HTMLTemplate
{
  protected $title = '';
  protected $css_file_paths = array();
  protected $js_file_paths = array();
  protected $body_attr = array();
  protected $body = '';

  function __construct()
  {
    $this->addCssFilePath('/themes/default/css/page.css');
    $this->addCssFilePath('/themes/default/css/form.css');
    $this->addJsFilePath('https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js');
  }

  function __toString()
  {
    extract(get_object_vars($this));
    ob_start();

    include ROOT_PATH . '/themes/default/templates/html.tpl.php';

    return ob_get_clean();
  }

  function setTitle($title)
  {
    $this->title = $title;
  }
  function addCssFilePath($path)
  {
    $this->css_file_paths[] = $path;
  }
  function addJsFilePath($path)
  {
    $this->js_file_paths[] = $path;
  }
  function setBodyAttr($attr)
  {
    $this->body_attr = $attr;
  }
  function getBodyAttr()
  {
    return $this->body_attr;
  }
  function setBody($body)
  {
    $this->body = $body;
  }
}

/**
 * Class ListTemplate
 */
class ListTemplate extends HTMLTemplate
{
  protected $operations = '';
  protected $list = '';

  function __construct($title = 'List')
  {
    parent::__construct();

    $this->setTitle($title);
    $this->setBodyAttr(array('id' => stringToAttr($title)));
  }

  function __toString()
  {
    // Body.
    extract(get_object_vars($this));
    ob_start();

    include ROOT_PATH . '/themes/default/templates/list.tpl.php';

    $body = ob_get_clean();
    $this->setBody(menu() . $body);

    // Wrapper.
    return parent::__toString();
  }

  function setOperations($ops)
  {
    $this->operations = $ops;
  }

  function addOperation($op)
  {
    $this->operations .= $op;
  }

  function setList($list)
  {
    $this->list = $list;
  }
}

/**
 * Class ListTemplate
 */
class FormTemplate extends HTMLTemplate
{
  protected $messages = '';
  protected $form = '';

  function __construct()
  {
    parent::__construct();
  }

  function __toString()
  {
    // Body.
    $body = '';
    $body .= menu();
    $body .= $this->messages;
    $body .= $this->form;
    $this->setBody($body);

    // Wrapper.
    return parent::__toString();
  }

  function setMessages($messages)
  {
    $this->messages = $messages;
  }

  function addMessage($message)
  {
    $this->messages .= $message;
  }

  function setForm(Form $form)
  {
    $this->setTitle($form->getTitle());

    $this->form = $form;
  }
}

/******************************************************************************
 *
 * HTML Structures
 *
 ******************************************************************************/

/**
 * Class TableTemplate
 */
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

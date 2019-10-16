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
  protected $messages = array();
  protected $body_attr = array();
  protected $body = '';

  function __construct()
  {
    // Global.
    $this->addCssFilePath('/themes/default/css/page.css');
    $this->addCssFilePath('/themes/default/css/form.css');

    // Jquery.
    $this->addJsFilePath('/libraries/jquery/jquery.min.js');
    $this->addJsFilePath('/libraries/jquery/jquery-ui.min.js');
    $this->addCssFilePath('/libraries/jquery/jquery-ui.min.css');

    $this->addJsFilePath('/libraries/global.js');
  }

  function __toString()
  {
    if (isset($_SESSION) && isset($_SESSION['messages']))
    {
      $this->messages = $_SESSION['messages'];
      $_SESSION['messages'] = array();
    }

    // Head.
    $output = '';
    $output .= htmlWrap('title', $this->title);
    foreach($this->css_file_paths as $css_file_path)
    {
      $attr = array(
        'rel' => 'stylesheet',
      );
      if (CLEAN_URLS)
      {
        $attr['href'] = $css_file_path;
      }
      else
      {
        $attr['href'] = 'http://127.0.0.1/5eTemplates' . $css_file_path;
      }
      $output .= htmlSolo('link', $attr);
    }
    $output .= htmlWrap('script', 'var CLEAN_URLS=' . CLEAN_URLS . ';');
    foreach($this->js_file_paths as $js_file_path)
    {
      $attr = array();
      if (CLEAN_URLS)
      {
        $attr['src'] = $js_file_path;
      }
      else
      {
        $attr['src'] = 'http://127.0.0.1/5eTemplates' . $js_file_path;
      }
      $output .= htmlWrap('script', '', $attr);
    }
    $output = htmlWrap('head', $output);

    // Body.
    $output .= htmlWrap('body', $this->body, $this->body_attr);
    $output = '<!DOCTYPE HTML>' . htmlWrap('html', $output);
    return $output;
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
class ListPageTemplate extends HTMLTemplate
{
  protected $operations = '';
  protected $list = '';
  protected $menu = '';

  function __construct($title = 'List')
  {
    parent::__construct();

    $this->setTitle($title);
    $this->setBodyAttr(array('id' => stringToAttr($title)));
    $this->menu = menu();
  }

  function __toString()
  {
    // Body.
    extract(get_object_vars($this));
    ob_start();

    include ROOT_PATH . '/themes/default/templates/list.tpl.php';

    $body = ob_get_clean();
    $this->setBody($this->menu . $body);

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

  /**
   * @param string $menu - html of the menu.
   */
  function setMenu($menu)
  {
    $this->menu = $menu;
  }
}

/**
 * Class ListTemplate
 */
class FormPageTemplate extends HTMLTemplate
{
  protected $messages = '';
  protected $form = '';
  protected $upper = '';

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
    $body .= $this->upper;
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

  /**
   * @param string $upper
   */
  public function setUpper($upper)
  {
    $this->upper = $upper;
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

  public function setHeader($header)
  {
    $this->header = $header;
    return $this;
  }
  public function addRows($rows)
  {
    $this->rows = $rows;
    $this->attr = array_fill(0, count($rows), array());
    return $this;
  }
  public function addRow($row, $attr = array())
  {
    $this->rows[] = $row;
    $this->attr[] = $attr;
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
    $attr = reset($this->attr);
    foreach ($this->rows as $row)
    {
      $row_output = '';
      $count = 1;
      foreach ($row as $cell)
      {
        $class = iis($attr, 'class', array());
        $class[] = 'column-' . $count;
        $attr['class'] = $class;
        $row_output .= htmlWrap('td', $cell, $attr);
        $count++;
      }
      $output .= htmlWrap('tr', $row_output);
      $attr = next($this->attr);
    }
    $output = htmlWrap('tbody', $output);
    return $output;
  }

  static function tableRow($row, $attr = array())
  {
    $output = '';
    $count = 1;
    foreach ($row as $cell)
    {
      $attr = iis($attr, 0, array());
      $class = iis($attr, 'class', array());
      assert(is_array($class));
      $class[] = 'column-' . $count;
      $attr['class'] = $class;
      $output .= htmlWrap('td', $cell, $attr);
      $count++;
    }
    return htmlWrap('tr', $output, $attr);
  }
}

Class ListTemplate
{
  protected $list;
  protected $list_type;
  protected $attr;
  protected $pointer = 0;

  public function __construct($list_type = 'ol')
  {
    $this->list_type = $list_type;
  }

  public function setListType($list_type)
  {
    $this->list_type = $list_type;
  }

  public function addListItem($item, $attr = array())
  {
    $this->list[$this->pointer] = $item;
    $this->attr[$this->pointer] = $attr;
    $this->pointer++;
  }

  public function setAttr($attr)
  {
    $this->attr = array_merge($this->attr, $attr);
  }

  public function __toString()
  {
    $output = '';

    for($k = 0; $k < $this->pointer; $k++)
    {
      $list_item = $this->list[$k];
      $attr = $this->attr[$k];
      $output .= htmlWrap('li', $list_item, $attr);
    }

    $output = htmlWrap($this->list_type, $output, $attr);
    return $output;
  }

}
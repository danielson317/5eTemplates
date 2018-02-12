<?php

class Item
{
  // Summary
  protected $name;
  protected $type;
  protected $value;
  protected $magic;
  protected $attunment;
  protected $description;

  /***************
   * Operators
   ***************/
  public function __construct($item)
  {
    $this->name = $item['name'];
    $this->type = $item['item_type'];
    $this->value = $item['value'] . ' GP';
    $this->magic = $item['magic'] ? 'M' : '';
    $this->attunment = $item['attunment'] ? 'A': '';
    $this->description = $item['description'];
  }
  public function __toString()
  {
    ob_start();

    include ROOT_PATH . '/themes/default/templates/item.tpl.php';

    return ob_get_clean();
  }
}

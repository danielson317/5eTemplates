<?php

class Item
{
  // Summary
  protected $id;
  protected $name;
  protected $item_type_id;
  protected $value;
  protected $magic;
  protected $attunement;
  protected $description;

  /***************
   * Overloads
   ***************/
  public function __construct($id)
  {
    $this->id = 0;
  }
  public function __toString()
  {
    ob_start();

    include ROOT_PATH . '/themes/default/templates/item.tpl.php';

    return ob_get_clean();
  }

  /***************
   * Getters
   ***************/
  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @return int
   */
  public function getItemTypeId()
  {
    return $this->item_type_id;
  }

  /**
   * @return int
   */
  public function getValue()
  {
    return $this->value;
  }

  /**
   * @return bool
   */
  public function getMagic()
  {
    return $this->magic;
  }

  /**
   * @return bool
   */
  public function getAttunement()
  {
    return $this->attunement;
  }

  /**
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }

  /***************
   * Setters
   ***************/
  /**
   * @param int $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @param string $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * @param int $item_type_id
   */
  public function setItemTypeId($item_type_id)
  {
    $this->item_type_id = $item_type_id;
  }

  /**
   * @param int $value
   */
  public function setValue($value)
  {
    $this->value = $value;
  }

  /**
   * @param bool $magic
   */
  public function setMagic($magic)
  {
    $this->magic = $magic;
  }

  /**
   * @param bool $attunement
   */
  public function setAttunement($attunement)
  {
    $this->attunement = $attunement;
  }

  /**
   * @param string $description
   */
  public function setDescription($description)
  {
    $this->description = $description;
  }

  /***************
   * Database.
   ***************/

  private function _load()
  {
    GLOBAL $db;

    $query = new Query(Query::OPERATION_SELECT, 'items');
    $query->addField('id')
      ->addField('name')
      ->addField('item_type_id')
      ->addField('value')
      ->addField('magic')
      ->addField('attunement')
      ->addField('description');
    $query->addCondition('id', ':id');
    $args = array(
      ':id' => $this->id,
    );

    $results = $db->select($query, $args);
    if (!$results)
    {
      throw new Exception('No results returned from database or given id.');
    }
    $result = array_shift($results);
    return $result;
  }

  private function _update()
  {
    GLOBAL $db;

    $query = new Query(Query::OPERATION_UPDATE, 'items');
    $query->addField('id')
      ->addField('name')
      ->addField('item_type_id')
      ->addField('value')
      ->addField('magic')
      ->addField('attunement')
      ->addField('description');
    $query->addCondition('id', ':id');
    $args = array(
      ':id' => $this->id,
    );

    $results = $db->select($query, $args);
    if (!$results)
    {
      throw new Exception('No results returned from database or given id.');
    }
    $result = array_shift($results);
    return $result;
  }
}

<?php

class SQLite
{
  private $db;

  function __construct($db_path, $username = '', $password = '')
  {
    $opt = array(
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    );

    $connect_string = 'sqlite:' . $db_path;
    $this->db = new PDO($connect_string, $username, $password, $opt);
  }

  function executeQuery(Query $query, $args = array())
  {
    $query = $this->db->prepare($query);
    $query->execute($args);

    $results = array();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }
}

class Query
{
  protected $operation;
  const OPERATION_SELECT = 1;
  const OPERATION_CREATE = 2;
  const OPERATION_UPDATE = 3;
  const OPERATION_DELETE = 4;

  const COMPARE_EQUAL = 1;
  const COMPARE_NOT_EQUAL = 2;
  const COMPARE_LESS_THAN = 3;
  const COMPARE_LESS_THAN_EQUAL = 4;
  const COMPARE_MORE_THAN = 5;
  const COMPARE_MORE_THAN_EQUAL = 6;
  const COMPARE_NULL = 7;
  const COMPARE_NOT_NULL = 8;

  // array(
  //   'table_name' => array(
  //     'alias' => 'tn',
  //     'join' => 'join_table_alias',
  //   )
  // )
  protected $tables = array();
  protected $fields = array();
  protected $conditions = array();

  function __construct($operation, $table)
  {
    $this->operation = $operation;
    $this->tables[$table] = array();
  }

  function __toString()
  {
    $output = '';
    switch($this->operation)
    {
      case self::OPERATION_SELECT:
      {
        $output .= 'SELECT';
        foreach ($this->fields as $alias => $details)
        {
          $output .= ' ' . $details['name'] . ' AS ' . $alias . ',';
        }
        $output = trim($output, ',');
        $output .= ' FROM ' . key($this->tables);

        if ($this->conditions)
        {
          $output .= ' WHERE';
          foreach($this->conditions as $condition)
          {
            $output .= ' ' . $condition['alias'];
            switch($condition['comparison'])
            {
              case self::COMPARE_EQUAL:
              {
                $output .= ' =';
                break;
              }
            }
            $output .= ' ' . $condition['value'];
          }
        }
        break;
      }
    }
    return $output;
  }

  function addField($name, $alias = '', $table_alias = '')
  {
    if (!$alias)
    {
      $alias = $name;
    }
    $this->fields[$alias] = array(
      'name' => $name,
    );
    return $this;
  }

  function addCondition($alias, $value, $comparison = self::COMPARE_EQUAL)
  {
    $this->conditions[] = array(
      'alias' => $alias,
      'value' => $value,
      'comparison' => $comparison,
    );
  }
}

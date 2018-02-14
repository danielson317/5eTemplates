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

  function select(Query $query, $args = array())
  {
    $query = $this->db->prepare($query);
    $query->execute($args);
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  function selectList(Query $query)
  {
    $query = $this->db->prepare($query);
    $query->execute();

    $list = array();
    while($row = $query->fetch(PDO::FETCH_ASSOC))
    {
      $list[$row['id']] = $row['value'];
    }
    return $list;
  }

  function update(Query $query, $args = array())
  {
    $query = $this->db->prepare($query);
    $query->execute($args);
  }
}

abstract class Query
{
  const COMPARE_EQUAL = 1;
  const COMPARE_NOT_EQUAL = 2;
  const COMPARE_LESS_THAN = 3;
  const COMPARE_LESS_THAN_EQUAL = 4;
  const COMPARE_MORE_THAN = 5;
  const COMPARE_MORE_THAN_EQUAL = 6;
  const COMPARE_NULL = 7;
  const COMPARE_NOT_NULL = 8;

  protected $tables = array();
  protected $fields = array();
  protected $conditions = array();

  function __construct($table, $alias = '')
  {
    $this->addTable($table, $alias);
  }

  abstract function __toString();

  abstract function addField($name);

  function addTable($table, $alias = '')
  {
    if (!$alias)
    {
      $alias = $table;
    }
    $this->tables[$alias] = array(
      'name' => $table,
    );
  }

  function addCondition($alias, $value = '', $comparison = self::COMPARE_EQUAL)
  {
    if (!$value)
    {
      $value = ':' . $alias;
    }
    $this->conditions[] = array(
      'alias' => $alias,
      'value' => $value,
      'comparison' => $comparison,
    );
  }
}

class SelectQuery extends Query
{
  function __toString()
  {
    $output = '';
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
}

class UpdateQuery extends Query
{
  function __toString()
  {
    $output = '';
    $output .= 'UPDATE '  . key($this->tables) . ' SET';
    foreach ($this->fields as $name => $value)
    {
      $output .= ' ' . $name . ' = ' . $value . ',';
    }
    $output = trim($output, ',');

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
    return $output;
  }

  function addField($name, $value = '')
  {
    if (!$value)
    {
      $value = ':' . $name;
    }
    $this->fields[$name] = array(
      'value' => $value,
    );
    return $this;
  }
}
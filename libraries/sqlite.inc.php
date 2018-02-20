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

  function select(SelectQuery $query, $args = array())
  {
    $query = $this->db->prepare($query);
    $query->execute($args);
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  function selectList(SelectQuery $query)
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

  function insert(InsertQuery $query, $args = array())
  {
    $query = $this->db->prepare($query);
    $query->execute($args);
    return $this->db->lastInsertId();
  }

  function update(UpdateQuery $query, $args = array())
  {
    $query = $this->db->prepare($query);
    $query->execute($args);
  }

  function create(CreateQuery $query, $args = array())
  {
    $query = $this->db->prepare($query);
    $query->execute($args);
  }

  static function buildArgs($args)
  {
    $new_args = array();
    foreach ($args as $key => $value)
    {
      $new_args[':' . $key] = $value;
    }
    return $new_args;
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

  static function concatenate()
  {
    $string = '';
    $args = func_get_args();
    foreach($args as $arg)
    {
      $string .= ' || ' . $arg;
    }
    $string = trim($string, ' |');
    return $string;
  }

  static function literal($string)
  {
    return '\'' . $string . '\'';
  }
}

class SelectQuery extends Query
{
  protected $orders = array();
  protected $page = FALSE;
  protected $page_size = FALSE;

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

    // Where.
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

    // Order by.
    if ($this->orders)
    {
      $output .= ' ORDER BY';
      foreach ($this->orders as $alias => $dir)
      {
        $output .= ' ' . $alias . ' ' . $dir . ',';
      }
    }
    $output = trim($output, ',');

    // Pager
    if ($this->page)
    {
      $output .= ' LIMIT ' . $this->page_size;
      $output .= ' OFFSET ' . (($this->page_size * $this->page) - $this->page_size);
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

  function addOrder($alias, $dir = 'ASC')
  {
    $this->orders[$alias] = $dir;
  }

  function addPager($page = 1, $page_size = 100)
  {
    $this->page = $page;
    $this->page_size = $page_size;
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
      $output .= ' ' . $name . ' = ' . $value['value'] . ',';
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

class InsertQuery extends Query
{
  function __toString()
  {
    $output = '';
    $output .= 'INSERT INTO '  . key($this->tables) . ' (';
    foreach ($this->fields as $name => $value)
    {
      $output .= ' ' . $name . ',';
    }
    $output = trim($output, ',');

    $output .= ') VALUES (';
    foreach ($this->fields as $name => $value)
    {
      $output .= ' ' . $value['value'] . ',';
    }
    $output = trim($output, ',');

    $output .= ')';
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

class CreateQuery extends Query
{
  function __toString()
  {
    $output = '';
    $output .= 'CREATE TABLE `'  . key($this->tables) . '` (';
    foreach ($this->fields as $name => $value)
    {
      $output .= ' `' . $name . '` ' . $value['type'];
      foreach ($value['flags'] as $flag)
      {
        if ($flag == 'N')
        {
          $output .= ' NOT NULL';
        }
        elseif ($flag == 'P')
        {
          $output .= ' PRIMARY KEY';
        }
        elseif ($flag == 'A')
        {
          $output .= ' AUTOINCREMENT';
        }
        elseif ($flag == 'U')
        {
          $output .= ' UNIQUE';
        }
      }

      if ($value['default'] !== FALSE)
      {
        $output .= ' DEFAULT ' . $value['default'];
      }
      $output .= ',';
    }
    $output = trim($output, ',');

    $output .= ')';
    return $output;
  }

  // Flags:
  // A = Auto Increment
  // P = Primary Key
  // N = Not Null
  // U = Unique
  function addField($name, $type = 'INTEGER', $flags = array(), $default = FALSE)
  {
    $this->fields[$name] = array(
      'type' => $type,
      'flags' => $flags,
      'default' => $default
    );
    return $this;
  }
}
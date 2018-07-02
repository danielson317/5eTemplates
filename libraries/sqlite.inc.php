<?php

abstract class Database
{
  const COMPARE_EQUAL = 1;
  const COMPARE_NOT_EQUAL = 2;
  const COMPARE_LESS_THAN = 3;
  const COMPARE_LESS_THAN_EQUAL = 4;
  const COMPARE_GREATER_THAN = 5;
  const COMPARE_GREATER_THAN_EQUAL = 6;
  const COMPARE_NULL = 7;
  const COMPARE_NOT_NULL = 8;

  const GROUP_AND = 1;
  const GROUP_OR = 2;

  protected $db;

  // VCrUD operations.
  abstract function select(SelectQuery $query, $args = array());
  function selectList(SelectQuery $query, $args = array())
  {
    $results = $this->select($query, $args);

    $list = array();
    foreach($results as $result)
    {
      $list[$result['id']] = $result['value'];
    }
    return $list;
  }
  abstract function insert(InsertQuery $query, $args = array());
  abstract function update(UpdateQuery $query, $args = array());
  abstract function delete(DeleteQuery $query, $args = array());

  // Database structure.
  abstract function create(CreateQuery $query, $args = array());

  // Helpers.
  abstract protected function _buildConditionGroup(Query $query, $group_name = 'default', $type = Database::GROUP_AND);
  abstract protected function _buildCondition(Query $query, QueryCondition $condition);

  // String manipulation.
  abstract function concatenate();
  abstract function literal($string);
}

class SQLite extends Database
{
  const PLACEHOLDER_TOKEN = ':';

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
    $sql  = '';
    $sql .= 'SELECT';
    foreach ($query->getFields() as $alias => $details)
    {
      $sql .= ' ' . $details['table_alias'] . '.' . $details['name'] . ' AS ' . $alias . ',';
    }
    $sql = trim($sql, ',');

    // Add tables.
    $tables = $query->getTables();
    reset($tables);
    $sql .= ' FROM ' . current($tables)['name'] . ' ' . key($tables);
//    next($tables);
//    while (next($tables))
//    {
//      $tables
//    }

    // Where.
    if ($query->getConditions())
    {
      $sql .= ' WHERE ' . $this->_buildConditionGroup($query);
    }

    // Order by.
    if ($query->getOrders())
    {
      $sql .= ' ORDER BY';
      foreach ($query->getOrders() as $alias => $dir)
      {
        $sql .= ' ' . $alias . ' ' . $dir . ',';
      }
    }
    $sql = trim($sql, ',');

    // Pager
    if ($query->getPage())
    {
      $sql .= ' LIMIT ' . $query->getPageSize();
      $sql .= ' OFFSET ' . (($query->getPageSize() * $query->getPage()) - $query->getPageSize());
    }

    $prepared_statement = $this->db->prepare($sql);
    $prepared_statement->execute($query->getValues());
    return $prepared_statement->fetchAll(PDO::FETCH_ASSOC);
  }

  function insert(InsertQuery $query, $args = array())
  {
    $sql = '';
    $sql .= 'INSERT INTO '  . key($query->getTables()) . ' (';
    foreach ($query->getFields() as $name => $value)
    {
      $sql .= ' ' . $name . ',';
    }
    $sql = trim($sql, ',');

    $sql .= ') VALUES (';
    foreach ($query->getFields() as $name => $value)
    {
      $sql .= ' ' . $value['value'] . ',';
    }
    $sql = trim($sql, ',');

    $sql .= ')';

    $prepared_statement = $this->db->prepare($sql);
    $prepared_statement->execute($args);
    return $this->db->lastInsertId();
  }

  function update(UpdateQuery $query, $args = array())
  {
    $sql = '';
    $sql .= 'UPDATE '  . key($query->getTables()) . ' SET';
    foreach ($query->getFields() as $name => $value)
    {
      $sql .= ' ' . $name . ' = ' . $value['value'] . ',';
    }
    $sql = trim($sql, ',');

    // Where.
    if ($query->getConditions())
    {
      $sql .= ' WHERE ' . $this->_buildConditionGroup($query);
    }

    $prepared_statement = $this->db->prepare($sql);
    $prepared_statement->execute($args);
  }

  function delete(DeleteQuery $query, $args = array())
  {
    $sql = '';
    $sql .= 'DELETE FROM '  . key($query->getTables());

    // Where.
    if ($query->getConditions())
    {
      $sql .= ' WHERE ' . $this->_buildConditionGroup($query);
    }

    $prepared_statement = $this->db->prepare($sql);
    $prepared_statement->execute($args);
  }

  // Structure
  function create(CreateQuery $query, $args = array())
  {
    $primary_key = array();
    foreach($query->getFields() as $name => $value)
    {
      if (array_search('P', $value['flags']) !== FALSE)
      {
        $primary_key[] = $name;
      }
    }
    $key_count = count($primary_key);

    $sql = '';
    $sql .= 'CREATE TABLE `'  . key($query->getTables()) . '` (';

    foreach ($query->getFields() as $name => $value)
    {
      $sql .= ' `' . $name . '` ' . $value['type'];
      foreach ($value['flags'] as $flag)
      {
        if ($flag == 'N')
        {
          $sql .= ' NOT NULL';
        }
        elseif ($flag == 'P' && $key_count == 1)
        {
          $sql .= ' PRIMARY KEY';
        }
        elseif ($flag == 'A')
        {
          $sql .= ' AUTOINCREMENT';
        }
        elseif ($flag == 'U')
        {
          $sql .= ' UNIQUE';
        }
      }

      if ($value['default'] !== FALSE)
      {
        $sql .= ' DEFAULT ' . $value['default'];
      }
      $sql .= ',';
    }
    if ($key_count > 1)
    {
      $sql .= ' PRIMARY KEY(';
      foreach($primary_key as $key)
      {
        $sql .= '`' . $key . '` ' . ', ';
      }
      $sql = trim($sql, ', ');
      $sql .= ')';
    }
    $sql = trim($sql, ',');

    $sql .= ')';

    $prepared_statement = $this->db->prepare($sql);
    $prepared_statement->execute($args);
  }

  /***************************
   * Helpers
   ***************************/
  static function buildArgs($args)
  {
    $new_args = array();
    foreach ($args as $key => $value)
    {
      $new_args[':' . $key] = $value;
    }
    return $new_args;
  }

  protected function _buildConditionGroup(Query $query, $group_name = 'default', $type = Database::GROUP_AND)
  {
    $conditions = array();
    foreach($query->getConditions() as $condition)
    {
      if ($condition->getGroup() == $group_name)
      {
        $conditions[] = $this->_buildCondition($query, $condition);
      }
    }

    foreach($query->getConditionGroups() as $subgroup_name => $condition_group)
    {
      if ($condition_group->getParent() == $group_name)
      {
        $conditions[] = '(' . $this->_buildConditionGroup($query, $subgroup_name, $condition_group->getType()) . ')';
      }
    }

    $join = ' AND ';
    if ($type == Database::GROUP_OR)
    {
      $join = ' OR ';
    }
    return implode($join, $conditions);
  }

  protected function _buildCondition(Query $query, QueryCondition $condition)
  {
    $sql = $condition->getTable() . '.' . $condition->getField();
    $placeholder =  self::PLACEHOLDER_TOKEN . $condition->getTable() . '_' . $condition->getField();
    switch($condition->getComparison())
    {
      case Database::COMPARE_EQUAL:
      {
        $sql .= ' =';
        break;
      }
      case Database::COMPARE_NOT_EQUAL:
      {
        $sql .= ' !=';
        break;
      }
      case Database::COMPARE_LESS_THAN:
      {
        $sql .= ' >';
        break;
      }
      case Database::COMPARE_LESS_THAN_EQUAL:
      {
        $sql .= ' >';
        break;
      }
      case Database::COMPARE_GREATER_THAN:
      {
        $sql .= ' >';
        break;
      }
      case Database::COMPARE_GREATER_THAN_EQUAL:
      {
        $sql .= ' >=';
        break;
      }
      case Database::COMPARE_NULL:
      {
        $sql .= ' = NULL';
        break;
      }
      case Database::COMPARE_NOT_NULL:
      {
        $sql .= ' != NULL';
        break;
      }
    }
    $sql .= ' ' . $placeholder;

    $query->addValue($placeholder, $condition->getValue());

    return $sql;
  }

  function concatenate()
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

  function literal($string)
  {
    return '\'' . $string . '\'';
  }
}

abstract class Query
{
  protected $tables = array();
  protected $fields = array();
  protected $conditions = array();
  protected $condition_groups = array();
  protected $values = array();

  // Getters
  function getTables()
  {
    return $this->tables;
  }
  function getFields()
  {
    return $this->fields;
  }
  function getValues()
  {
    return $this->values;
  }

  /**
   * @return QueryCondition[]
   */
  function getConditions()
  {
    return $this->conditions;
  }

  /**
   * @return QueryConditionGroup[]
   */
  function getConditionGroups()
  {
    return $this->condition_groups;
  }

  // Overload.
  function __construct($table, $alias = '')
  {
    $this->addTable($table, $alias);
  }

  // Setters.
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

  function addConditionGroup(QueryConditionGroup $group)
  {
    $this->condition_groups[$group->getName()] = $group;
  }

  function addCondition(QueryCondition $condition)
  {
    $this->conditions[] = $condition;
  }

  function addConditionSimple($field_alias, $value, $comparison = Database::COMPARE_EQUAL)
  {
    $this->conditions[] = new QueryCondition($field_alias, key($this->getTables()), $comparison, $value);
  }

  function addValue($placeholder, $value)
  {
    $this->values[$placeholder] = $value;
  }
}

class QueryCondition
{
  protected $field_alias;
  protected $table_alias;
  protected $comparison;
  protected $value;
  protected $group;

  function __construct($field_alias, $table_alias, $comparison, $value)
  {
    $this->field_alias = $field_alias;
    $this->table_alias = $table_alias;
    $this->comparison = $comparison;
    $this->value = $value;
    $this->group = 'default';
  }

  function setValue($value)
  {
    $this->value = $value;
  }

  function setGroup($group)
  {
    $this->group = $group;
  }

  function getField()
  {
    return $this->field_alias;
  }

  function getTable()
  {
    return $this->table_alias;
  }

  function getComparison()
  {
    return $this->comparison;
  }

  function getValue()
  {
    return $this->value;
  }

  function getGroup()
  {
    return $this->group;
  }
}

class QueryConditionGroup
{
  protected $name;
  protected $type;
  protected $parent;

  function __construct($name, $type = Database::GROUP_AND, $parent = 'default')
  {
    $this->name = $name;
    $this->type = $type;
    $this->parent = $parent;
  }

  function setParent($parent)
  {
    $this->parent = $parent;
  }

  function setType($type)
  {
    $this->type = $type;
  }

  function getName()
  {
    return $this->name;
  }
  function getType()
  {
    return $this->type;
  }

  function getParent()
  {
    return $this->parent;
  }
}

class SelectQuery extends Query
{
  protected $orders = array();
  protected $page = FALSE;
  protected $page_size = FALSE;

  function getOrders()
  {
    return $this->orders;
  }
  function getPage()
  {
    return $this->page;
  }
  function getPageSize()
  {
    return $this->page_size;
  }

  function addField($name, $alias = '', $table_alias = '')
  {
    if (!$alias)
    {
      $alias = $name;
    }
    if (!$table_alias)
    {
      $table_alias = key($this->tables);
    }
    $this->fields[$alias] = array(
      'name' => $name,
      'table_alias' => $table_alias,
    );
    return $this;
  }

  function addOrder($alias, $dir = 'ASC')
  {
    $this->orders[$alias] = $dir;
    return $this;
  }

  function addPager($page = 1, $page_size = DEFAULT_PAGER_SIZE)
  {
    $this->page = $page;
    $this->page_size = $page_size;
  }
}

class InsertQuery extends Query
{
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

class UpdateQuery extends Query
{
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

class DeleteQuery extends Query
{
}

class CreateQuery extends Query
{
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
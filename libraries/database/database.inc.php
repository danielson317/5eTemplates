<?php

/**
 * Class Database
 */
abstract class Database
{
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
  abstract protected function _buildConditionGroup(Query $query, $group_name = 'default', $type = QueryConditionGroup::GROUP_AND);
  abstract protected function _buildCondition(Query $query, QueryCondition $condition);

  // String manipulation.
  abstract function concatenate();
  abstract function literal($string);
}

/**
 * Class Query
 */
abstract class Query
{
  protected $tables = array();
  protected $fields = array();
  protected $conditions = array();
  protected $condition_groups = array();
  protected $values = array();

  /**
   * @param string $table
   * @param string $alias
   */
  function __construct($table_name, $table_alias = '')
  {
    $this->addTableSimple($table_name, $table_alias);
  }

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

  // Setters.
  function addTable(QueryTable $table)
  {
    $this->tables[$table->getAlias()] = $table;
  }

  function addTableSimple($table_name, $table_alias = '')
  {
    if (!(bool)$table_alias)
    {
      $table_alias = $table_name;
    }
    $table = new QueryTable($table_name, $table_alias);
    $this->tables[$table->getAlias()] = $table;
  }

  function addConditionGroup(QueryConditionGroup $group)
  {
    $this->condition_groups[$group->getName()] = $group;
  }

  function addCondition(QueryCondition $condition)
  {
    $this->conditions[] = $condition;
  }

  function addConditionSimple($field_alias, $value, $comparison = QueryCondition::COMPARE_EQUAL)
  {
    $this->conditions[] = new QueryCondition($field_alias, key($this->getTables()), $comparison, $value);
  }

  function addValue($placeholder, $value)
  {
    $this->values[$placeholder] = $value;
  }
}

/**
 * Class SelectQuery
 */
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

/**
 * Class InsertQuery
 */
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

/**
 * Class UpdateQuery
 */
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

/**
 * Class DeleteQuery
 */
class DeleteQuery extends Query
{
}

/**
 * Class CreateQuery
 */
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

/**
 * Class QueryCondition
 */
class QueryCondition
{
  const COMPARE_EQUAL = 1;
  const COMPARE_NOT_EQUAL = 2;
  const COMPARE_LESS_THAN = 3;
  const COMPARE_LESS_THAN_EQUAL = 4;
  const COMPARE_GREATER_THAN = 5;
  const COMPARE_GREATER_THAN_EQUAL = 6;
  const COMPARE_NULL = 7;
  const COMPARE_NOT_NULL = 8;
  const COMPARE_LIKE = 9;
  const COMPARE_IN = 10;

  protected $field_alias;
  protected $table_alias;
  protected $comparison;
  protected $value;
  protected $group;
  protected $value_field_alias = FALSE;
  protected $value_field_table_alias = FALSE;

  /**
   * @param string $field_alias
   * @param string $table_alias
   * @param int $comparison
   * @param mixed $value
   */

  function __construct($field_alias, $table_alias, $comparison = QueryCondition::COMPARE_EQUAL, $value = FALSE)
  {
    $this->field_alias = $field_alias;
    $this->table_alias = $table_alias;
    $this->comparison = $comparison;
    $this->value = $value;
    $this->group = 'default';
  }

  /**
   * @param int|string $value
   */
  function setValue($value)
  {
    $this->value = $value;
  }

  function setGroup($group)
  {
    $this->group = $group;
  }

  function setValueField($field_alias, $table_alias)
  {
    $this->value_field_alias = $field_alias;
    $this->value_field_table_alias = $table_alias;
  }

  /**
   * @return string
   */
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
    if ($this->value_field_table_alias)
    {
      return $this->value_field_table_alias . '.' . $this->value_field_alias;
    }
    return $this->value;
  }

  function isValueField()
  {
    return (bool)$this->value_field_table_alias;
  }

  function getGroup()
  {
    return $this->group;
  }
}

/**
 * Class QueryConditionGroup
 */
class QueryConditionGroup
{
  const GROUP_AND = 1;
  const GROUP_OR = 2;

  protected $name;
  protected $type;
  protected $parent;

  function __construct($name, $type = QueryConditionGroup::GROUP_AND, $parent = 'default')
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

/**
 * Class QueryTable
 */
class QueryTable
{
  const INNER_JOIN = 1; // Both match. Exclude outliers.
  const OUTER_JOIN = 2; // Either match. Include everything.
  const LEFT_JOIN = 3;  // Include all left and matching right.
  const RIGHT_JOIN = 4; // Include all right and matching left.

  protected $name;
  protected $alias;
  protected $join;
  protected $condition;

  function __construct($name, $alias, $join = QueryTable::INNER_JOIN, QueryCondition $condition = NULL)
  {
    $this->name = $name;
    $this->alias = $alias;
    $this->join = $join;
    $this->condition = $condition;
  }

  function getName()
  {
    return $this->name;
  }

  function getAlias()
  {
    return $this->alias;
  }

  function getJoin()
  {
    return $this->join;
  }

  function setCondition(QueryCondition $condition)
  {
    $this->condition = $condition;
  }

  function setConditionSimple($left_field_alias, $left_table_alias, $comparison, $right_field_alias, $right_table_alias)
  {
    $this->condition = new QueryCondition($left_field_alias, $left_table_alias, $comparison, FALSE);
    $this->condition->setValueField($left_field_alias, $right_table_alias);
  }

  /**
   * @return QueryCondition
   */
  function getCondition()
  {
    return $this->condition;
  }
}
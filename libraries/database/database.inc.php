<?php

/**
 * Class Database
 */
abstract class Database
{
  /* @var PDO $db*/
  protected $db;

  // VCrUD operations.
  /**
   * @param SelectQuery $query
   * @return bool|array()
   */
  abstract function select(SelectQuery $query);

  /**
   * @param SelectQuery $query
   * @return array
   */
  function selectList(SelectQuery $query, $id = FALSE)
  {
    $results = $this->select($query);

    $list = array();
    foreach($results as $result)
    {
      $list[$result['id']] = $result['value'];
    }
    return getListItem($list, $id);
  }

  /**
   * @param SelectQuery $query
   * @return bool|FALSE
   */
  function selectObject(SelectQuery $query)
  {
    $results = $this->select($query);
    if (!$results)
    {
      return FALSE;
    }
    $result = array_shift($results);
    return $result;
  }

  /**
   * @param SelectQuery $query
   * @param string      $field_alias
   *
   * @return bool|mixed
   */
  function selectField(SelectQuery $query, $field_alias, $default = FALSE)
  {
    $results = $this->select($query);
    if (!$results)
    {
      return FALSE;
    }
    $result = array_shift($results);
    return iis($result, $field_alias, $default);
  }

  /**
   * @param SelectQuery[] $select_queries - An array of inner select queries.
   * @param int|FALSE $limit
   * @param string|FALSE $order
   *
   * @return mixed
   */
  abstract function selectUnion($select_queries, $limit = FALSE, $order = FALSE);

  /**
   * @param InsertQuery $query
   * @return int|FALSE
   */
  abstract function insert(InsertQuery $query);

  /**
   * @param UpdateQuery $query
   */
  abstract function update(UpdateQuery $query);

  /**
   * @param DeleteQuery $query
   */
  abstract function delete(DeleteQuery $query);

  function passThrough($query)
  {
    return $this->db->query($query);
  }

  // Database structure.
  abstract function create(CreateQuery $query);
  abstract function alterAdd(AlterAddQuery $query);
  abstract function alterAlter(AlterAlterQuery $query);
  abstract function alterRename(AlterRenameQuery $query);
  abstract function addIndex(AddIndexQuery $query);
  abstract function drop(DropQuery $query);
  abstract function dbExists($name);
  abstract function truncate(TruncateQuery $query);
  abstract function backupDatabase($db_name);
  abstract function restoreDatabase($file_path, $database_name);

  // Helpers.
  abstract protected function _buildConditionGroup(Query $query, $group_name = 'default', $type = QueryConditionGroup::GROUP_AND);
  abstract protected function _buildConditionGroupTable(Query $query, QueryTable $table, $group_name = 'default', $type = QueryConditionGroup::GROUP_AND);
  abstract protected function _buildCondition(Query $query, QueryCondition $condition);
  abstract protected function _buildJoins(Query $query, $tables);
  abstract protected function _buildOrder(QueryOrder $order);

  // String manipulation.

  /**
   * @param string[] ...$args
   * @return mixed
   */
  abstract function concatenate($args);
  /**
   * @param string[] ...$args
   * @return mixed
   */
  abstract function coalesce($args);
  abstract function literal($string);
  abstract function likeEscape($string);
  abstract function structureEscape($string);
  abstract function fieldTable($field, $table_alias);
  abstract function dataTypeList($type);
  abstract function sanitizePlacholderName($string);

  abstract function debugPrint($sql, $debug, $values);
}

/**
 * Class Query
 */
abstract class Query
{
  CONST FIELD_FORMAT_DATETIME = 'date';
  CONST FIELD_FORMAT_CURRENCY = 'currency';
  CONST FIELD_FORMAT_DATE = 'date_only';
  CONST FIELD_FORMAT_BYPASS = 'bypass';
  CONST FIELD_FORMAT_LOWER = 'lower';
  CONST FIELD_FORMAT_MAX = 'max';
  CONST FIELD_FORMAT_TIME = 'time';
  CONST FIELD_FORMAT_COUNT = 'count';
  CONST FIELD_FORMAT_COUNTALL = 'countall';
  CONST FIELD_FORMAT_SUM = 'sum';

  public static function getFieldFormats($key = FALSE)
  {
    $list = array(
      self::FIELD_FORMAT_DATETIME =>  'date',
      self::FIELD_FORMAT_CURRENCY => 'currency',
      self::FIELD_FORMAT_DATE => 'date_only',
      self::FIELD_FORMAT_BYPASS => 'bypass',
      self::FIELD_FORMAT_LOWER => 'lower',
      self::FIELD_FORMAT_MAX => 'max',
      self::FIELD_FORMAT_TIME => 'time',
      self::FIELD_FORMAT_COUNT => 'count',
      self::FIELD_FORMAT_COUNTALL => 'countall',
      self::FIELD_FORMAT_SUM => 'sum',
    );
    return getListItem($list, $key);
  }

  protected $tables = array();
  protected $fields = array();
  protected $conditions = array();
  protected $condition_groups = array();
  protected $values = array();
  protected $debug = FALSE;
  protected $argument_prefix = '';

  /**
   * @param string $table_name
   * @param string $table_alias
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

  function getArgumentPrefix()
  {
    return $this->argument_prefix;
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

  function addConditionSimple($field, $value, $comparison = QueryCondition::COMPARE_EQUAL)
  {
    $this->conditions[] = new QueryCondition($field, key($this->getTables()), $comparison, $value);
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

  function addValue($placeholder, $value)
  {
    $this->values[$placeholder] = $value;
  }

  function setArgumentPrefix($prefix)
  {
    $this->argument_prefix = $prefix;
  }

  /**
   * @param bool|string $debug
   * @return Query
   */
  function setDebug($debug = TRUE)
  {
    $this->debug = $debug;
    return $this;
  }

  function getDebug()
  {
    return $this->debug;
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
  protected $group_by = array();
  protected $distinct = FALSE;
  protected $special_instruction = FALSE;

  /**
   * @return QueryOrder[]
   */
  function getOrders()
  {
    return $this->orders;
  }
  function getGroupBy()
  {
    return $this->group_by;
  }
  function getPage()
  {
    return $this->page;
  }
  function getPageSize()
  {
    return $this->page_size;
  }
  function setDistinct($distinct = TRUE)
  {
    $this->distinct = $distinct;
  }
  function getDistinct()
  {
    return $this->distinct;
  }

  function addField($name, $alias = '', $table_alias = '', $format = FALSE)
  {
    assert(!$format || Query::getFieldFormats($format), 'Unknown format passed to add field.');
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
      'format' => $format,
    );
    return $this;
  }

  /**
   * @param string $name
   * @param string $alias
   *
   * @return $this
   */
  function addFieldBypass($name, $alias = '')
  {
    $this->addField($name, $alias, '', 'bypass');
    return $this;
  }

  function addOrderSimple($field, $dir = QueryOrder::DIRECTION_ASC)
  {
    $this->orders[] = new QueryOrder($field, key($this->tables), $dir);
    return $this;
  }

  function addOrder(QueryOrder $order)
  {
    $this->orders[] = $order;
  }

  function addPager($page = 1, $page_size = PAGER_SIZE_DEFAULT)
  {
    $this->page = $page;
    $this->page_size = $page_size;
  }

  function addLimit($limit)
  {
    $this->page_size = $limit;
  }

  function addGroupBy($field, $table_alias = false)
  {
    if (!$table_alias)
    {
      $table_alias = key($this->tables);
    }

    $this->group_by[] = array(
      'field' => $field,
      'table_alias' => $table_alias,
    );
    return $this;
  }

  function addSpecialInstruction($instruction)
  {
    $this->special_instruction = $instruction;
  }

  function getSpecialInstruction()
  {
    return $this->special_instruction;
  }
}

/**
 * Class InsertQuery
 */
class InsertQuery extends Query
{
  protected $identity_insert = FALSE;
  function addField($name, $value = 0, $field_alias = '', $table_alias = '')
  {
    if (!$field_alias)
    {
      $field_alias = $name;
    }
    if (!$table_alias)
    {
      $table_alias = key($this->tables);
    }
    $this->fields[$name] = array(
      'field_alias' => $field_alias,
      'table_alias' => $table_alias,
      'value' => $value,
    );
    return $this;
  }
  function setIdentityInsert()
  {
    $this->identity_insert = TRUE;
  }
  function getIdentityInsert()
  {
    return $this->identity_insert;
  }
}

/**
 * Class UpdateQuery
 */
class UpdateQuery extends Query
{
  protected $skip_condition = FALSE;

  function addField($name, $value = 0, $field_alias = '', $table_alias = '')
  {
    if (!$field_alias)
    {
      $field_alias = $name;
    }
    if (!$table_alias)
    {
      $table_alias = key($this->tables);
    }
    $this->fields[$name] = array(
      'field_alias' => $field_alias,
      'table_alias' => $table_alias,
      'value' => $value,
    );
    return $this;
  }

  function addFieldBypassValue($name, $value, $field_alias = '', $table_alias = '')
  {
    if (!$field_alias)
    {
      $field_alias = $name;
    }
    if (!$table_alias)
    {
      $table_alias = key($this->tables);
    }
    $this->fields[$name] = array(
      'field_alias' => $field_alias,
      'table_alias' => $table_alias,
      'value' => $value,
      'value_bypass' => TRUE,
    );
    return $this;
  }

  function skipCondition($skip_condition = TRUE)
  {
    $this->skip_condition = $skip_condition;
  }

  functIon getSkipCondition()
  {
    return $this->skip_condition;
  }
}

/**
 * Class DeleteQuery
 */
class DeleteQuery extends Query
{
  protected $page_size = FALSE;

  function getPageSize()
  {
    return $this->page_size;
  }

  function addLimit($limit)
  {
    $this->page_size = $limit;
  }
}

/**
 * Class CreateQuery
 */
class CreateQuery extends Query
{
  // Data types.
  const TYPE_INTEGER = 1;
  const TYPE_BOOL = 2;
  const TYPE_STRING = 3;
  const TYPE_DATETIME = 4;
  const TYPE_CURRENCY = 5;
  const TYPE_DECIMAL = 6;
  const TYPE_DATE = 7;

  // Flags.
  const FLAG_AUTOINCREMENT = 'A';
  const FLAG_PRIMARY_KEY = 'P';
  const FLAG_NOT_NULL = 'N';
  const FLAG_UNIQUE = 'U';

  function addField($name, $type = CreateQuery::TYPE_INTEGER, $length = 0, $flags = array(), $default = FALSE)
  {
//    assert((is_int($length) && $type !== CreateQuery::TYPE_DECIMAL) || (strtolower($length) === 'max' && $type !== CreateQuery::TYPE_DECIMAL), 'Length must be an integer or "max"');
    $this->fields[$name] = array(
      'type' => $type,
      'length' => $length,
      'flags' => $flags,
      'default' => $default
    );
    return $this;
  }
}

/**
 * Class DropQuery
 */
class DropQuery extends Query
{
}

/**
 * Class CreateQuery
 */
class AlterAddQuery extends Query
{
  function addField($name, $type = CreateQuery::TYPE_INTEGER, $length = 0, $flags = array(), $default = FALSE)
  {
    assert(is_int($length) || strtolower($length) === 'max', 'Length must be an integer or "max"');
    $this->fields[$name] = array(
      'type' => $type,
      'length' => $length,
      'flags' => $flags,
      'default' => $default
    );
    return $this;
  }
}

/**
 * Class AlterAlterQuery
 *
 * Identical to the add query so just inherit from there.
 */
class AlterAlterQuery extends AlterAddQuery
{
}

/**
 * Class AlterRenameQuery
 */
class AlterRenameQuery extends Query
{
  function addField($name, $new_name = '', $empty = '')
  {
    $this->fields[$name] = $new_name;
    return $this;
  }
}

class AddIndexQuery extends Query
{
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
      'type' => 'NONCLUSTERED',
    );
    return $this;
  }
}

/**
 * Class Truncate Query
 */
class TruncateQuery extends Query
{
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
  const COMPARE_NOT_IN = 11;
  const COMPARE_LENGTH_GREATER_THAN = 12;
  const COMPARE_EXISTS = 13;
  const COMPARE_NOT_EXISTS = 14;

  const COMPARE_NULL_0 = 100;
  const COMPARE_NOT_NULL_0 = 101;


  protected $fields = array();
//  protected $table_alias;
  protected $comparison;
  protected $value;
  protected $group;
  protected $value_field = FALSE;
  protected $value_field_table_alias = FALSE;

  /**
   * @param string $field
   * @param string $table_alias
   * @param int    $comparison
   * @param mixed  $value
   * @param string $data_type
   */

  function __construct($field, $table_alias, $comparison = QueryCondition::COMPARE_EQUAL, $value = FALSE, $data_type = '<none>')
  {
    $this->fields[] = array(
      'field' => $field,
      'table_alias' => $table_alias,
      'data_type' => $data_type,
    );
    $this->value_field = FALSE;
    $this->value_field_table_alias = FALSE;
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

  function setFieldSelectQuery(SelectQuery $select_query)
  {
    $this->fields = array();
    $this->fields[] = array(
      'field' => $select_query,
      'table_alias' => '',
      'data_type' => 'field-select-query',
    );
  }

  function setValueSelectQuery(SelectQuery $select_query)
  {
    $this->value_field = $select_query;
  }

  function setFieldBypass($string)
  {
    $this->fields = array();
    $this->fields[] = array(
      'field' => $string,
      'table_alias' => '',
      'data_type' => 'bypass',
    );
  }

  /**
   * @param string $group
   */
  function setGroupName($group)
  {
    $this->group = $group;
  }

  /**
   * @param QueryConditionGroup $group
   */
  function setGroup(QueryConditionGroup $group)
  {
    $this->group = $group->getName();
  }

  function setValueField($field, $table_alias)
  {
    $this->value_field = $field;
    $this->value_field_table_alias = $table_alias;
  }

  function addConcatField($field, $table_alias, $data_type = '<none>')
  {
    $this->fields[] = array(
      'field' => $field,
      'table_alias' => $table_alias,
      'data_type' => $data_type,
    );
  }

  /**
   * @return array()
   */
  function getFields()
  {
    return $this->fields;
  }

//  function getTable()
//  {
//    return $this->table_alias;
//  }

  function getComparison()
  {
    return $this->comparison;
  }

  function getValue()
  {
    assert(!$this->value_field_table_alias, 'Value field must use getValueTable & getValueField');
    return $this->value;
  }

  function isValueField()
  {
    return (bool)$this->value_field_table_alias;
  }

  function isValueSelectQuery()
  {
    return (bool)$this->value_field && !(bool)$this->value_field_table_alias;
  }

  function getValueTable()
  {
    assert((bool)$this->value_field_table_alias, 'No value table. Use setValueField or getValue.');
    return $this->value_field_table_alias;
  }

  /**
   * @return string|SelectQuery
   */
  function getValueField()
  {
    assert((bool)$this->value_field, 'No value table. Use setValueField or getValue.');
    return $this->value_field;
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
    assert($name !== 'default', 'Default is a condition for restricted groups');
    $this->name = $name;
    $this->type = $type;
    $this->parent = $parent;
  }

  function setParent(QueryConditionGroup $parent)
  {
    $this->parent = $parent->getName();
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
  protected $conditions = array();
  protected $condition_groups = array();

  function __construct($name, $alias, $join = QueryTable::INNER_JOIN, QueryCondition $condition = NULL)
  {
    $this->name = $name;
    $this->alias = $alias;
    $this->join = $join;
    if ($condition)
    {
      $this->conditions[] = $condition;
    }
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

  function addCondition(QueryCondition $condition)
  {
    $this->conditions[] = $condition;
  }

  function addConditionGroup(QueryConditionGroup $condition_group)
  {
    $this->condition_groups[] = $condition_group;
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
}

Class QueryOrder
{
  const DIRECTION_ASC = 1;
  const DIRECTION_DESC = 2;

  protected $field;
  protected $table_alias;
  protected $direction;
  protected $sort_function;

  function __construct($field, $table_alias, $direction = self::DIRECTION_ASC, $sort_function = FALSE)
  {
    $this->field = $field;
    $this->table_alias = $table_alias;
    $this->direction = $direction;
    $this->sort_function = $sort_function;
  }

  function getField()
  {
    return $this->field;
  }

  function getTableAlias()
  {
    return $this->table_alias;
  }

  function getDirection()
  {
    return $this->direction;
  }

  function getSortFunction()
  {
    return $this->sort_function;
  }
}

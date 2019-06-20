<?php

function getItemTypeList($parent_id = 0, $level = 0)
{
  $item_types = getItemTypeListForParent($parent_id);

  $list = array();
  foreach($item_types as $key => $value)
  {
    $list[$key] = str_repeat('-', $level) . $value;
    $list += getItemTypeList($key, $level + 1);
  }

  return $list;
}

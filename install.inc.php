<?php

function getDB()
{
  $pdo = new PDO('sqlite:dnd.db');
  return $pdo;
}

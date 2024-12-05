<?php
session_start();

const CONN = [
  "db_name" => "education",
  "host" => "localhost",
  "username" => "root",
  "password" => ""
];

require __DIR__ . "/functions.php";
require __DIR__ . "/classes/Connection.php";
require __DIR__ . "/classes/User.php";
require __DIR__ . "/classes/CardSet.php";
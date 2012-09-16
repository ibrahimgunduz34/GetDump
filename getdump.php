<?php
define('APPLICATION_PATH', dirname(__FILE__));
include('library/loader.php');

Loader::getInstance();

function getArgValAssoc()
{
  global $argv;
  $params = array();
  foreach($argv as $arg) {
    if(strpos($arg, '=') !== false) {
      $param  = explode('=', $arg);
      $key    = substr( trim( $param[0] ), 2);
      $value  = trim($param[1]);
      $params[$key] = $value;
    }
  }
  return $params;
}

$parameters = getArgValAssoc();

if(!array_key_exists('adapter', $parameters)) {
  echo 'adapter parameter not exists.' . PHP_EOL; exit;
}

if(!array_key_exists('host', $parameters)) {
  echo 'host parameter not exists.' . PHP_EOL; exit;
}

if(!array_key_exists('database', $parameters)) {
  echo 'database parameter not exists.' . PHP_EOL; exit;
}

if(!array_key_exists('username', $parameters)) {
  echo 'username parameter not exists.' . PHP_EOL; exit;
}

if(!array_key_exists('password', $parameters)) {
  echo 'password parameter not exists.' . PHP_EOL; exit;
}


$context = new GetDump_Context($parameters['adapter'], 
                                $parameters['host'], 
                                $parameters['database'], 
                                $parameters['username'], 
                                $parameters['password']);

echo $context->getDump() . PHP_EOL;
?>

<?php
abstract class GetDump_Adapter_Abstract
{
  protected $_connection;
  protected $_host;
  protected $_db;
  protected $_username;
  protected $_password;

  /**
    class constructor
    @param string $host
    @param string $db
    @param string $username
    @param string $password
   */
  public function __construct($host, $db, $username, $password)
  {
    $this->_host        = $host;
    $this->_db          = $db;
    $this->_username    = $username;
    $this->_password    = $password;
  }

  /**
    returns database dump

    @return string
   */
  public function getDump() 
  {
    $tableList = $this->_getTableCollection();
    $output = '';
    foreach($tableList as $table) {
      $output .= $this->_getCreateTableScript($table);
      $output .= $this->_getDataAsScript($table);
    }
    return $output;
  }

  /**
    returns database connection

    @return database_connection
   */
  abstract protected function _getConnection();

  /**
    runs a sql query and returns result.

    @param string $sql
    @return array
   */
  abstract protected function _query($sql);

  /**
    returns table list in database.
    
    @return array
   */
  abstract protected function _getTableCollection();

  /**
    returns create table sql script 

    @param string $tableName
    @return string
   */
  abstract protected function _getCreateTableScript($tableName);

  /**
    returns data as insert into script
    @param string $tableName
    @param boolean $assoc
    @return string
   */
  abstract protected function _getDataAsScript($tableName, $period = 1000);
}

<?php
class GetDump_Adapter_Mysql extends GetDump_Adapter_Abstract 
                            implements GetDump_Adapter_Interface
{

  /**
    returns database connection

    @return database_connection
   */
  protected function _getConnection()
  {
    if(!$this->_connection) {
      $this->_connection = mysql_connect($this->_host, 
                                          $this->_username, 
                                          $this->_password);
      
      if(!$this->_connection) {
        throw new GetDump_Exception_Connection('Connection failed.');
      }

      $result = mysql_select_db($this->_db, $this->_connection);
      if(!$result) {
        throw new GetDump_Exception_UnknownDatabase('Unknown database : ' . $this->_db);
      }
    }
    return $this->_connection;
  }

  /**
    runs a sql query and returns result.

    @param string $sql
    @return array
   */
  protected function _query($sql, $assoc = false)
  {
    $query = mysql_query($sql, $this->_getConnection() );
    if($queryError = mysql_error($this->_getConnection())) {
      throw new GetDump_Exception_QueryError('query error occurred: ' . $queryError);
    }

    $result = array();
    while(($assoc) ? 
        $row = mysql_fetch_assoc($query) :  
        $row = mysql_fetch_row($query)) {
      $result[] = $row;
    }
    return $result;
  }

  /**
    returns table list in database.
    
    @return array
   */
  protected function _getTableCollection()
  {
    $result = $this->_query('SHOW TABLES');
    $tableList = array();
    foreach($result as $table) {
      $tableList[] = $table[0];
    }
    return $tableList;
  }

  /**
    returns create table sql script 

    @param string $tableName
    @return string
   */
  protected function _getCreateTableScript($tableName)
  {
    $result = $this->_query('SHOW CREATE TABLE ' . $tableName);
    return $result[0][1] . ";\n\n\n";
  }

  /**
    returns data as insert into script
    @param string $tableName
    @param boolean $assoc
    @return string
   */
  protected function _getDataAsScript($tableName, $period = 1000)
  {
    $data = $this->_query('SELECT * FROM ' . $tableName, true);
    $output = '';
    if( count($data) > 0) { 
      $recordCount = 0;
      $fieldList = array_keys($data[0]);
      foreach($data as $dataItem) {
        if($recordCount % $period == 0) {
          if($output) {
            $output = substr($output, 0, -1) . ';';
          }
          $output .= 'INSERT INTO ' . $tableName . 
            ' (' . implode(',', $fieldList) . ') VALUES';
        }
        $output .= '(';
        $dataItem = array_map('mysql_real_escape_string', $dataItem);
        $output .= "'" . implode("','", $dataItem) . "'";
        $output .= '),';

        $recordCount++;
      }
      $output = substr($output, 0, -1). ';';
      $output .= "\n\n\n";
    }

    return $output;
  }
}

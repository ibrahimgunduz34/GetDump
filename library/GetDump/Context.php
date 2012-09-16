<?php
class GetDump_Context
{
  private $_adapter;
  private $_host;
  private $_db;
  private $_username;
  private $_password;
 
  /**
    class constructor

    @param string $adapterName;
    @param string $host;
    @param string $db
    @param string $username;
    @param string $password;
   */
  public function __construct($adapterName, $host, $db, $username, $password)
  {
    $this->_adapterName   = $adapterName;
    $this->_host          = $host;
    $this->_db            = $db;
    $this->_username      = $username;
    $this->_password      = $password;
  }

  /**
    returns adapter namespace
    @param string $adapter
    @return string
   */
  private function _getAdapterName($adapter) 
  {
    return implode('_', array('GetDump', 'Adapter', ucfirst($adapter)));
  }

  /**
    returns db adapter class

    @param string $adapter
    @return GetDump_Adapter_Interface
   */
  private function _getAdapter($adapter)
  {
    if(!class_exists($this->_getAdapterName($adapter))) {
      throw new GetDump_Exception_UnknownAdapter('Unknown adapter '. $adapter);
    }
    $adapterName = $this->_getAdapterName($adapter);
    return new $adapterName($this->_host, 
                                      $this->_db, 
                                      $this->_username, 
                                      $this->_password);
  }

  /**
    returns database dump.
    @return string
   */
  public function getDump()
  {
    $adapter = $this->_getAdapter($this->_adapterName);
    return $adapter->getDump();
  }
}

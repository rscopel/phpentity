<?php
/*
 * Core: Database
 *
 * @todo determine affected rows on prepared statements for UPDATE and CREATE
 * @todo clear records
 *
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 *
 * @author Dallas Vogels <dvogels@islandlinux.org>
 * @copyright (c) 2007-2009 Dallas Vogels
 */

/**
 * Core Seed
 */
require_once 'classCoreSeed.php';

/**
 * Core DB Class
 * 
 * Adds database functionality on top of CoreSeed
 * 
 * @todo error messages (numbers)
 * @todo refactor update and delete to use single method (is same process)
 */ 
class CoreDatabase extends CoreSeed {

  /**
   * DB Connection
   */
  protected $conn = null; 

  /**
   * Constructor
   * 
   * @param string $namespace
   * @param object &$conn
   * @param object &$log
   * @return void
   */
  public function __construct($namespace, &$conn, &$log) {
    
    parent::__construct($namespace, $log);
    
    $this->conn = $conn;
    
  }
  
  /**
   * Get Timestamp
   * 
   * Generated a MDB2 timestamp
   * 
   * @return string
   */
	public function getTimestamp() {
		return date('Y-m-d H:i:s', time());
	} 
	
  /**
   * Prepared Delete
   * 
   * Returns FALSE if failed otherwise returns number of affected rows
   * 
   * @param string $sql
   * @param array $arrData
   * @return mixed
   */
  public function preparedDelete($sql, $arrData) {
  	
		$ret = false;
		
		$preparedStatement = $this->conn->prepare($sql);
		
		if (PEAR::isError($preparedStatement)) {
			$this->_setError(preg_replace("/\n/", '', $preparedStatement->getDebugInfo()), __METHOD__, 200);
		} else {

			// just in case array is using name=>value pairs
			$arrValues = array_values($arrData);
			$dml =& $preparedStatement->execute($arrValues);
			
			if (PEAR::isError($dml)) {
				$this->log->debug(__METHOD__.': data ['.print_r($arrValues, TRUE).']');
				$this->_setError(preg_replace("/\n/", '',$dml->getDebugInfo()), __METHOD__, 200);
			} else {
				$ret = $this->conn->_affectedRows(null);
				$this->log->debug(__METHOD__.': updated ['.$ret.'] record(s)');
			}
		}
		return $ret;
	}	
   
  /**
   * Prepared Insert
   * 
   * Returns FALSE if failed otherwise returns id of inserted record
   * 
   * @param string $sql
   * @param array $arrData
   * @return mixed
   */
  public function preparedInsert($sql, $arrData) {
  	
		$ret = false;
		
		$preparedStatement = $this->conn->prepare($sql);
		
		if (PEAR::isError($preparedStatement)) {
			$this->_setError(preg_replace("/\n/", '', $preparedStatement->getDebugInfo()), __METHOD__, 200);
		} else {

			// just in case array is using name=>value pairs
			$arrValues = array_values($arrData);
			$dml =& $preparedStatement->execute($arrValues);
			
			if (PEAR::isError($dml)) {
				$this->log->debug(__METHOD__.': data ['.print_r($arrValues, TRUE).']');
				$this->_setError(preg_replace("/\n/", '',$dml->getDebugInfo()), __METHOD__, 200);
			} else {
				$ret = $this->conn->lastInsertID();
				$this->log->debug(__METHOD__.': inserted record with id ['.$ret.']');
			}
		}
		return $ret;
	}
	
	/**
	 * Prepared Multi-Insert
	 * 
	 * This method wraps over preparedInsert() and returns an array.  The array
	 * consists of elements containing either the id of the newly created record
	 * or FALSE where something failed.
	 * 
	 * @param string $sql
	 * @param array $arrData
	 * @return array
	 */
	public function preparedMultiInsert($sql, $arrData) {
		
		$arrReturn = array();
		
		foreach ($arrData as $dataKey => $arrInsert) {
			$arrReturn[$dataKey] = $this->preparedInsert($sql, $arrInsert);
		}
		
		return $arrReturn;
		
	}
	
  /**
   * Prepared Select
   * 
   * Returns FALSE if failed otherwise returns array of record(s)
   * 
   * @param string $sql
   * @param array $arrData
   * @return mixed
   */
  public function &preparedSelect($sql, $arrData) {
  	
		$ret = false;
		
		$preparedStatement = $this->conn->prepare($sql, null, MDB2_PREPARE_RESULT);
		
		if (PEAR::isError($preparedStatement)) {
			$this->_setError(preg_replace("/\n/", '', $preparedStatement->getDebugInfo()), __METHOD__, 200);
		} else {

			// just in case array is using name=>value pairs
			$arrValues = array_values($arrData);
			$result =& $preparedStatement->execute($arrValues);

			if (PEAR::isError($result)) {
				$this->log->debug(__METHOD__.': data ['.print_r($arrValues, TRUE).']');
				$this->_setError(preg_replace("/\n/", '',$result->getDebugInfo()), __METHOD__, 200);
			} else {
				$ret =& $result->fetchAll(MDB2_FETCHMODE_ASSOC);
				$result->free();
				$this->log->debug(__METHOD__.': found ['.count($ret).'] record(s)');
			}
		}
		return $ret;
	}	
	
  /**
   * Prepared Update
   * 
   * Returns FALSE if failed otherwise returns number of affected rows
   * 
   * @param string $sql
   * @param array $arrData
   * @return mixed
   */
  public function preparedUpdate($sql, $arrData) {
  	
		$ret = false;
		
		$preparedStatement = $this->conn->prepare($sql);
		
		if (PEAR::isError($preparedStatement)) {
			$this->_setError(preg_replace("/\n/", '', $preparedStatement->getDebugInfo()), __METHOD__, 200);
		} else {

			// just in case array is using name=>value pairs
			$arrValues = array_values($arrData);
			$dml =& $preparedStatement->execute($arrValues);
			
			if (PEAR::isError($dml)) {
				$this->log->debug(__METHOD__.': data ['.print_r($arrValues, TRUE).']');
				$this->_setError(preg_replace("/\n/", '',$dml->getDebugInfo()), __METHOD__, 200);
			} else {
				$ret = $this->conn->_affectedRows(null);
				$this->log->debug(__METHOD__.': updated ['.$ret.'] record(s)');
			}
		}
		return $ret;
	}
	
	/**
	 * Execute SQL
	 * 
	 * Generic processing
	 * 
	 * @param string $sql
	 * @return boolean
	 */
	public function executeSQL($sql) {
		
		$ret = false;

		$dml =& $this->conn->exec($sql);
		
		$this->log->debug(__METHOD__.': SQL ['.$sql.']');
		
		// Always check that result is not an error
		if (PEAR::isError($dml)) {
			$this->_setError(preg_replace("/\n/", '',$dml->getDebugInfo()), __METHOD__, 200);
		} else {
			$ret = true;
		}
		
		return $ret;
		
	}
	
	/**
	 * Query SQL
	 * 
	 * Generic processing for queries
	 * 
	 * @param string $sql
	 * @return mixed
	 */
	public function &querySQL($sql) {
		
		$ret = false;
		
    $result =& $this->conn->query($sql);
    
		// Always check that result is not an error
		if (PEAR::isError($result)) {
			$this->_setError(preg_replace("/\n/", '',$result->getDebugInfo()), __METHOD__, 200);
		} else {
			$ret =& $result->fetchAll(MDB2_FETCHMODE_ASSOC);
			$result->free();    
		}
		
    return $ret;
    
  }
	
}  // end of class
?>

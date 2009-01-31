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
 */ 
class CoreDatabase extends CoreSeed {

  /**
   * DB Connection
   */
  protected $conn = null; 
  
  /**
   * Generate MDB2 timestamp
   * 
   * Used to generate date_created and date_modified for INSERTs and UPDATEs.
   * 
   * Timestamps are in YYYY-MM-DD HH:MI:SS format.
   * 
   * @return string
   */   
  protected function _generate_timestamp() {
    
    return date('Y-m-d H:i:s', time());
    
  }

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
   * Execute Multiple SQL Queries
   * 
   * @todo return number of rows affected
   * 
   * @param array $arr_sql
   * @return void
   */
  public function process_multi_exec($arr_sql) {
    
    foreach($arr_sql as $sql) {
      
      $this->log->debug(__METHOD__.': processing ['.$sql.']');
      
      $affected =& $this->conn->exec($sql);
      
      // Always check that result is not an error
      if (PEAR::isError($affected)) {
          $this->log->crit(__METHOD__.': '.$affected->getMessage());
      }          
      
    }    
    
  }
  
	/**
	 * Process exec
	 * 
	 * Setting $insert to TRUE will cause the return value to be the last inserted id.
	 * 
	 * @param string $sql
	 * @param boolean $getInsertID default FALSE
	 * @return mixed
	 */
	public function &processExec($sql, $getInsertID = FALSE) {

		// init
		$ret = TRUE;

		$affectedRows =& $this->conn->exec($sql);
		
		$this->log->debug(__METHOD__.': SQL ['.$sql.']');
		
		// Always check that result is not an error
		if (PEAR::isError($affectedRows)) {
			$this->_set_error(preg_replace("/\n/", '',$affectedRows->getDebugInfo()), __METHOD__, 1001);
			$ret = FALSE;
		} else {
			if ($getInsertID) {
				$ret = $this->conn->lastInsertID();
			}
		}
		
		return $ret;
		
	}  
  
	/**
	 * Process Multi Prepared Statement
	 * 
	 * @param string $sql
	 * @param array $arr_data
	 * @return array
	 */
	public function &processMultiPrepared($sql, &$arr_data) {

		$arr_return = array();
		
		reset($arr_data);	// reset internal pointer
		
		$sth = $this->conn->prepare($sql);

		if (PEAR::isError($sth)) {
			$this->_set_error(preg_replace("/\n/", '', $sth->getDebugInfo()), __METHOD__, 1000);
		} else {
		
			foreach ($arr_data as $key => $row) {
				
				$row = array_values($row);
				
				$affectedRows = $sth->execute($row);

				if (PEAR::isError($affectedRows)) {
					$this->log->debug(__METHOD__.': data ['.print_r($row, TRUE).']');
					$this->_set_error(preg_replace("/\n/", '',$affectedRows->getDebugInfo()), __METHOD__, 1001);
				} else {
					$arr_return[$key] = $this->conn->lastInsertID();
				}

			}
				
			$this->log->debug(__METHOD__.': saved ['.count($arr_return).'] records');
			
		}

		return $arr_return;
  	
  }  
  
  /**
   * Process Query
   * 
   * @param string $sql
   * @return array
   */
  public function &process_query($sql) {
    
    $result =& $this->conn->query($sql);
    
    $this->log->debug(__METHOD__.': SQL ['.$sql.']');
    
    // Always check that result is not an error
    if (PEAR::isError($result)) {
    	$this->_set_error(preg_replace("/\n/", '',$result->getDebugInfo()), __METHOD__, 1001);
    }    
    
    $arr_result =& $result->fetchAll(MDB2_FETCHMODE_ASSOC);
    
    $result->free();    
    
    return $arr_result;
    
  }  

} 
?>

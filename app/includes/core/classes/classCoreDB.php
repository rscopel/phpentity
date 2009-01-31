<?php
/*
 * Core: Database CRUD
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
 * Adds database functionality
 */ 
class CoreDB extends CoreSeed {
	
  /**
   * Database Structure Array
   */
  protected $_arr_table_struct = null;
  
  /**
   * Table Name
   */
  protected $_table_name = null;
  
  /**
   * Table Field Constants
   * 
   * Fields that are always present in the table.  These must be present in the
   * table structure or everything grinds to a halt.
   * 
   * Each value is the MDB2 constant as found on http://cvs.php.net/viewcvs.
   * cgi/pear/MDB2/docs/datatypes.html?view=co
   * 
   */
  protected $_arr_field_constants = array('id' => 'integer',
                                          'date_created' => 'timestamp',
                                          'date_modified' => 'timestamp',
                                          'viewable' => 'boolean',
                                          'deleted' => 'boolean'); 

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
   * Get Field Type
   * 
   * @param string $field_name
   * @return string
   */
  protected function _get_field_type($field_name) {
    
    return $this->_arr_table_struct[$this->_table_name][$field_name]['type'];
    
  }
   
  /**
   * Load Table Structure
   * 
   * @return void
   */
  protected function _load_table_structure() {
    
    if (! isset($this->_arr_table_struct[$this->_table_name])) {
      
      $this->log->debug(__METHOD__.': loading table structure for ['.$this->_table_name.']');
      
      // load the MDB2 module that provides the functionality to determine the
      // field types
      $this->conn->loadModule('Reverse', NULL, TRUE);
      
      $this->_arr_table_struct[$this->_table_name] = $this->conn->tableInfo($this->_table_name);
      
      if ($this->conn->isError($this->_arr_table_struct[$this->_table_name])) {
        // @todo determine verbose error reporting
        die_hard($this->log, __METHOD__.': major error loading structure for ['.$this->_table_name.']');  
      }
      
      if (count($this->_arr_table_struct[$this->_table_name]) === 0) {
        die_hard($this->conn, __METHOD__.': 0 element array; table ['.$this->_table_name.'] not loaded');
      }
      
      // loop through the fields and trim unneccessary data
      foreach ($this->_arr_table_struct[$this->_table_name] as $key => $arr_field) {
        
        $this->_arr_table_struct[$this->_table_name][$arr_field['name']]['type'] = $arr_field['mdb2type'];
        
        // ensure the length is set; datatime/timestamp does not have a length
        if (isset($arr_field['length'])) {
          $field_length = $this->_arr_table_struct[$this->_table_name][$arr_field['name']]['length'] = $arr_field['length'];
        } else {
          $field_length = NULL;
        }
        
        $this->_arr_table_struct[$this->_table_name][$arr_field['name']]['length'] = $field_length;
        
        // cleanup
        unset($this->_arr_table_struct[$this->_table_name][$key]);
        
      }
    
      // switch to first entry below for more verbose logging
      //$this->log->debug(__METHOD__.': table structure loaded'."\n".print_r($this->_arr_table_struct[$this->_table_name], TRUE));
      $this->log->debug(__METHOD__.': table structure loaded for ['.$this->_table_name.']');
        
    } else {

      $this->log->debug(__METHOD__.': table structure already loaded for ['.$this->_table_name.']');
      
    }
    
  }  

  /**
   * Prepare Data
   * 
   * Prepare data for use later.  Compares the passed array to the table fields
   * and field constants.  Will ensure that fields to be
   * updated/inserted/selected exist in the table fields.  Note that this will
   * not force all fields to exist in the passed array.  The id of the record is
   * returned.
   * 
   * @param array
   * @return integer
   */
  protected function _prepare_data(&$arr_data) {
    
    // grab the key, will be wiped in next loop
    $id = $arr_data['id']; 

    // require only field names for this test
    $arr_fields = array_keys($arr_data);

    // compare each field to the table structure fields
    foreach ($arr_fields as $field) {
      
      // remove constants, these will be handled separate
      if (isset($this->_arr_field_constants[$field])) {
        
        // no need for this field right now
        unset($arr_data[$field]);
        
      } else {
        
        // match up with the table structure
        if (!isset($this->_arr_table_struct[$this->_table_name][$field])) {
          die_hard($this->log, __METHOD__.": extra field [$field] found");
        }
        
      }
      
    } 
    
    // drop the id in again
    $arr_data['id'] = $id;
    
    return $id;
    
  }  

  /**
   * Execute Multiple SQL Queries
   * 
   * @todo return number of rows affected
   * 
   * @param array $arr_sql
   * @return void
   */
  protected function _process_multi_exec($arr_sql) {
    
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
   * Process Query
   * 
   * @param string $sql
   * @return array
   */
  protected function &_process_query($sql) {
    
    $result =& $this->conn->query($sql);
    
    $this->log->debug(__METHOD__.': SQL ['.$sql.']');
    
    // Always check that result is not an error
    if (PEAR::isError($result)) {
        die_hard($this->log, __METHOD__.': '.$result->getMessage());
    }    
    
    $arr_result =& $result->fetchAll(MDB2_FETCHMODE_ASSOC);
    
    $result->free();    
    
    return $arr_result;
    
  }
  
  /**
   * SQL Create Record
   * 
   * @param array &$arr_data
   * @return integer id of inserted record
   */
  protected function _CRUD_create(&$arr_data) {
    
    $ret = FALSE;

    $arr_data['date_created'] = $this->_generate_timestamp();
    $arr_data['date_modified'] = $arr_data['date_created'];

    // build the fields and prefix fields with a colon
    $fields = ':'.implode(', :', array_keys($arr_data));

    //$arr_types = $this->_CRUD_get_types($arr_data);

    foreach ($arr_data as $field_name => $field_data) {
      $arr_types[] = $this->_get_field_type($field_name); 
    }

    $sql = 'INSERT INTO '.$this->_table_name.' ('.preg_replace('/:/', '', $fields).') VALUES ('.$fields.')';
    
    $this->log->debug(__METHOD__.': processing sql ['.$sql.']');

    $affected =& $this->conn->prepare($sql, $arr_types);

    if (PEAR::isError($affected)) {
      die_hard($this->log, __METHOD__.': '.$sth->getMessage());
    }

    $affected->execute($arr_data); 

    if (PEAR::isError($affected)) {
      die_hard($this->log, __METHOD__.': '.$sth->getMessage());
    }

    // returns 0 if no record was created
    // @todo figure out why no record was created...
    $ret = $this->conn->lastInsertID();
    
    $affected->free();

    //echo "$ret<br/>";
    //print_r($arr_types).'<br/>';
    //print_r($arr_data); exit;
    
    if ($ret) {
      $this->log->debug(__METHOD__.": inserted record id [$ret]");
    } else {
      $this->log->debug(__METHOD__.': could not insert new record, id was ['.$ret.']');
    }

    return $ret;
    
  }
  
  /**
   * SQL Delete Records
   * 
   * Requires an array with the 'id' element set as an array of ids or a blank
   * array.  The 'where' element can be set and contains sub-elements that
   * consist of a data => value pair of fieldname => value.
   * 
   * @param array $arr_data
   * @return integer number of records affected
   */
  protected function _CRUD_delete($arr_data) {
    
    $ret = FALSE;
    
    $ids = '';
    if (count($arr_data['id'])) {
      $ids = implode(',', $arr_data['id']);
    }
    
    $arr_where = array();
    if (isset($arr_data['where'])) {
      
      foreach($arr_data['where'] as $field_name => $field_value) {
        
        $arr_where[] = "$field_name = ".$this->conn->quote($field_value, $this->_get_field_type[$field_name]); 
        
      }
      
    }
    
    $this->log->debug(__METHOD__.": deleted records");
    
    return $ret;
    
  }

  /**
   * Get Field Types
   * 
   * @param array &$arr_data
   * @return array
   */
  protected function &_CRUD_get_types(&$arr_data) {
    
    $arr_types = array();
    
    foreach ($arr_data as $field_name => $field_data) {
      $arr_types[] = $this->_arr_table_struct[$this->_table_name][$field_name]['type']; 
    }
    
    return $arr_types;
    
  }
  
  /**
   * Check for Deleted Record
   * 
   * @param integer $id
   * @return boolean
   */
  protected function _CRUD_is_deleted($id) {

    $ret = FALSE;
    
    $sql = 'SELECT deleted FROM '.$this->_table_name.' WHERE id = '.
        $this->conn->quote($id, 'integer').' AND deleted = 1';
        
    $arr_exists = $this->process_query($sql);
    
    if (count($arr_exists)) {      
      // @todo issue with error processing, this will not trigger upstream error recognition of what actually happened...
      $this->log->warning(__METHOD__.': record id ['.$id.'] has been deleted');
      $ret = TRUE;
    }
    
    return $ret;
    
  }
  
  /**
   * SQL Retrieve Record
   * 
   * Retrieve a record
   * 
   * @param array $arr_ids default null
   * @param string $sort default null
   * @param integer $limit default null
   * @param integer $offset default null
   * @param integer $where default null
   * @return array
   */
  protected function &_CRUD_retrieve($arr_ids = NULL, $sort = NULL, $limit = NULL, $offset = NULL, $where = null) {
    
    $arr_ret = array();
    
    // init the SQL query
    $sql = 'SELECT * FROM '.$this->_table_name.' WHERE deleted = 0';
    
    // check for ids
    if (!is_null($arr_ids)) {

      $sql .= ' AND ';
      
      // check for single record instance
      if (count($arr_ids) === 1) {
        $sql .= 'id = '.array_pop($arr_ids);
      } else {
        $sql .= 'id IN ('.implode(',', $arr_ids).')';
      }
      
    }
    
    // add where if required
    if (!is_null($where)) {
      $sql .= ' AND '.$where;
    }

    if ($sort) {
      $sql .= ' ORDER BY '.$sort;
    }
    
    // set the limit and offset if required
    if (!is_null($offset) && !is_null($limit)) {
      $this->conn->setLimit($limit, $offset);
    } elseif (!is_null($limit)) {
      $this->conn->setLimit($limit);
    }
    
    $this->log->debug(__METHOD__.': processing SQL ['.$sql.']');
    
    $result =& $this->conn->query($sql);

    // Always check that result is not an error
    if (PEAR::isError($result)) {
        die_hard($this->log, __METHOD__.': '.$result->getMessage());
    }    
    
    $arr_ret =& $result->fetchAll(MDB2_FETCHMODE_ASSOC);
    
    $result->free();
    
    return $arr_ret;
    
  }  
    
  /**
   * SQL Update Record
   * 
   * @param array &$arr_data
   * @return integer number of records affected
   */
  protected function _CRUD_update(&$arr_data) {
    
    $ret = FALSE;
    
    if (!$this->_CRUD_is_deleted($arr_data['id'])) {
  
      $arr_data['date_modified'] = $this->_generate_timestamp();
  
      $fields = '';
      $id = $arr_data['id'];
      unset($arr_data['id']);
      
      foreach (array_keys($arr_data) as $field) {
        $fields .= "$field = :$field, ";      
      }
      if ($fields) {
        $fields = substr($fields, 0, -2);
      }
      $arr_data['id'] = $id;
  
      $arr_types = $this->_CRUD_get_types($arr_data);
  
      $affected =& $this->conn->prepare('UPDATE '.$this->_table_name.' SET '.$fields.' WHERE id = :id AND deleted = 0', $arr_types);
  
      $affected->execute($arr_data);
  
      // @todo not generating errors on unique fields where dupes occur!!!
      if (PEAR::isError($affected)) {
        die_hard($this->log, __METHOD__.': '.$sth->getMessage());
      } else {
        $ret = TRUE;
      }
      
      // @todo determine number of affected rows for return value
      
      $affected->free();
      
      $this->log->debug(__METHOD__.': updated record id ['.$arr_data['id'].']');

    }

    return $ret;
    
  }
  
  
  /**
   * Pre-Process SQL
   * 
   * Append necessary filters such as deleted and viewed.
   * 
   * @param string $sql
   * @param boolean $use_view default FALSE
   * @return string
   */
  protected function _pre_process_sql($sql, $use_view = FALSE) {
    
    // init the sql
    $sql .= ' AND (deleted = 0';
    
    if ($use_view) {
      $sql .= ' AND viewable = 1';
    }
    
    $sql .= ')';
    
    return $sql;
    
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
   * Clears Deleted Records
   *
   * @todo stream-line deletion process
   *  
   * @return integer number of records affected
   */
  public function clear_deleted() {
    
    $arr_data['id'] = array();
    $arr_data['where']['deleted'] = 1;
    
    $ret = $this->_CRUD_delete($arr_data);
    
    return $ret;
    
  }

  /**
   * Delete Record
   * 
   * @param integer $id
   * return boolean
   */
  public function delete_record($id) {
    
    $ret = FALSE;
    
    $arr_data['deleted'] = 1;
    $arr_data['id'] = $id;
    
    if (!$this->_CRUD_is_deleted($id)) {
      $ret = $this->_CRUD_update($arr_data);
    
      $this->log->debug(__METHOD__.': deleted record id ['.$id.']');
    }
    
    return $ret;
    
  }
   
  /**
   * Load Record
   * 
   * @param integer $id
   * @return array
   */
  public function &load_record($id) {
    
    $arr_ret = array();

    if (intval($id) != $id) {
      die_hard($this->log, __METHOD__.': invalid id ['.$id.']');
    }
    
    $arr_ret = $this->_CRUD_retrieve(array($id));
    
    return $arr_ret;
    
  }

  /**
   * Load Records
   * 
   * Loads records from the database.  If the id array is null all records will
   * be returned.  Use the start and limit to return specific ranges of data and
   * the sort to order by.
   * 
   * @param array $arr_ids default null
   * @param string $sort default null
   * @param integer $limit default null
   * @param integer $offset default null
   * @param integer $where default null
   * @return array
   */
  public function &load_records($arr_ids = NULL, $sort = NULL, $limit = NULL, $offset = NULL, $where = NULL) {
    
    $arr_ret = array();
    
    $arr_ret = $this->_CRUD_retrieve($arr_ids, $sort, $limit, $offset, $where);
    
    return $arr_ret;
    
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
  
  /**
   * Save Record
   * 
   * Setting $use_view to TRUE will filter only records with the view field set
   * to TRUE (1) in the database (not implemented yet).
   * 
   * @param array &$arr_data
   * @param boolean $use_view FALSE
   * @return mixed
   */
  public function save_record(&$arr_data, $use_view = FALSE) {
    
    $ret = FALSE;
    
    if (!isset($arr_data['id'])) {
      $arr_data['id'] = 0;
    }
    
    $id = $this->_prepare_data($arr_data);
    
    // null, zero, false, or empty string triggers creation of record
    if ($id) {
      
      // update
      $ret = $this->_CRUD_update($arr_data);
      

    } else {
      
      // create
      $ret = $this->_CRUD_create($arr_data);
      
    }
    
    return $ret;
    
  }
  
  /**
   * Set Database Connection
   * 
   * @param object
   * @return void
   */
  public function set_connection(&$conn) {
    
    if (is_object($conn)) {
      $this->conn = $conn;
    } else {
      $this->log->err(__METHOD__.': connection is NOT an object');
    }
    
  }
  
  /**
   * Set Table Name
   * 
   * @param string $table_name
   */
  public function set_table_name($table_name) {
    
    $this->_table_name = $this->_sanitize_string($table_name);
    
    $this->log->debug(__METHOD__.': set tablename to ['.$this->_table_name.']');
    
    $this->_load_table_structure();
    
  }
    
} 
?>

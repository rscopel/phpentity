<?php
/*
 * Core Seed Class
 * 
 * The Core Seed is extended in all classes.
 *
 * @todo implement singleton
 * @todo implement standardized error reporting
 * 
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 * 
 * @author Dallas Vogels <dvogels@islandlinux.org>
 * @copyright (c) 2007-2009 Dallas Vogels
 */

/**
 * Core Seed Class
 */ 
class CoreSeed {
	
  /**
   * Error Array
   */
  protected $_arr_errors = null;

  /**
   * Last Error Array
   */
  protected $_arr_last_error = null; 
   
  /**
   * Timer array
   */
  protected $_arr_timer = array();
  
  /**
   * Name Space
   */
  protected $_namespace = null;
  
  /**
   * Pear::Log
   */
  protected $log;
    
  /**
   * Check Integer
   * 
   * Checks passed value and ensures it is an integer.  Returns FALSE if not.
   * 
   * @param mixed $value
   * @return mixed
   */
  protected function _check_integer($value) {
    
    // assume bad value; prove it to be good
    $ret = FALSE;
    
    if (is_numeric($value)) {
      
      //$this->log->debug(__METHOD__.': is_numeric('.$value.') is TRUE');
      
      $test = (int) $value;
      
      if ($test == $value) {
        // all is good
        $ret = $value;
        //$this->log->debug(__METHOD__.': is_int('.$value.') is TRUE');
      }
      
    }
    
    if ($ret === FALSE) {
      $this->log->err(__METHOD__.": incorrect integer value [$value]");
    }
    
    return $ret;
    
  }
  
  /**
   * Clear Errors
   * 
   * @return void
   */
  protected function _clear_errors() {
  	$this->_arr_errors = null;
  	$this->log->debug(__METHOD__.': cleared any previous errors');  	
  }
  
  /**
   * Sanitize String
   * 
   * @param string $string
   * @return string
   */
  protected function _sanitize_string($string) {
    
    $ret = preg_replace("/\W/", "", $string);
    
    if ($ret != $string) {
      $this->log->warning(__METHOD__.": string sanitized: [$string]:[$ret]");
    }
    
    return $ret;
    
  }

  /**
   * Set Error Message
   * 
   * @param string $message
   * @param string $method
   * @param integer $number default 0
   * @return void
   */
  protected function _set_error($message, $method, $number = null) {
  
    // express null in text    
    if (is_null($number)) {
      $err_number = 'null';
    }
    
    $arr_error['method'] = $method;
    
    $arr_error['number'] = $number;

    $arr_error['message'] = $message;

    $this->_arr_errors[] =  $arr_error;
    
    $this->_arr_last_error = &$this->_arr_errors[count($this->_arr_errors) - 1];

    $this->log->err(__METHOD__." method: [$method], number: [$number], message: [$message]");
  
  }
    
  /**
   * Get Elapsed Time
   * 
   * @param string $id
   * @return float
   */
  protected function _timer_get($id) {
    
    if (!isset($this->_arr_timer[$id]['elapsed'] )) {
      $this->log->warn(__METHOD__.': no time to return (oops)...');
    } else {
      return $this->_arr_timer[$id]['elapsed'];
    }

  }  
  
  /**
   * Timer End
   * 
   * @param string $id
   * @return mixed
   */
  protected function _timer_end($id) {
    
    if (!isset($this->_arr_timer[$id]['start'] )) {
      
      $this->log->warn(__METHOD__.': missing the start value (oops), not tracking...');
      
    } else {
    
      // using microtime and storing as a float
      $this->_arr_timer[$id]['end'] = microtime(TRUE);
      
      // calculate the difference
      $this->_arr_timer[$id]['elapsed'] = $this->_arr_timer[$id]['end'] - $this->_arr_timer[$id]['start'];
      
      // gain an efficiency
      return $this->_arr_timer[$id]['elapsed'];
    }
    
  }  
  
  /**
   * Timer Start
   * 
   * @param string $id
   * @return void
   */
  protected function _timer_start($id) {
    
    // using microtime and storing as a float
    $this->_arr_timer[$id]['start'] = microtime(TRUE);
    
  }
   
  /**
   * Constructor
   * 
   * @param string $namespace
   * @param object &$conn
   * @param object &$log
   * @return void
   */
  public function __construct($namespace, &$log) {
    
    $this->log = $log;
    
    if (!$this->_namespace) {
     
      // ensure the namespace is friendly
      $namespace = $this->_sanitize_string($namespace);
    
      if (! $namespace) {
        die_hard($this->log, __METHOD__.': no namespace');  // STOP 
      }
  
      $this->_namespace = $namespace;
      $this->log->debug(__METHOD__.": Set namespace to [$namespace]");
    }

  }
  
  /**
   * Get Errors
   * 
   * @return array
   */
  public function get_errors() {
    
    return $this->_arr_errors;
    
  }
  
  /**
   * Get Last Error
   */
  public function get_last_error() {
    
    return $this->_arr_last_error;
    
  }
  
  /**
   * Get Namespace
   * 
   * @return string
   */
  public function get_namespace() {
    
    return $this->_namespace;
    
  }
  
  /**
   * Error Test
   * 
   * @param string
   * @param string $label default ''
   * @param integer $err_number default null
   * @return void
   */
  public function test_error($err_message, $label = '', $err_number = null) {
  
    $this->_set_error($err_message, $label, $err_number);
    
  }
  
  /**
   * Timer Test
   * 
   * @param integer $timer_wait in microseconds
   * @return float
   */
  public function test_timer($timer_wait) {
    
    $this->log->debug(__METHOD__.': timer wait set at ['.$timer_wait.'] milliseconds ('.($timer_wait / 1000000).') seconds');
    
    $this->_timer_start('tester');
    usleep($timer_wait);
    $this->_timer_end('tester');
    
    $this->log->debug(__METHOD__.': Total Time: ['.$this->_timer_get('tester').'] seconds');
    return $this->_timer_get('tester');
    
  }  
  
}  
?>

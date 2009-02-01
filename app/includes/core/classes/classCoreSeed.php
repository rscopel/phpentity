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
 * 
 */

/**
 * Core Seed Class
 */ 
class CoreSeed {

  /**
   * Timer array
   */
  protected $_arrTimer = array();
    
  /**
   * Error Array
   */
  protected $_arrErrors = null;

  /**
   * Last Error Array
   */
  protected $_arrLastError = null;   
  
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
  protected function _checkInteger($value) {
    
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
  protected function _clearErrors() {
  	$this->_arrErrors = null;
  	$this->log->debug(__METHOD__.': cleared any previous errors');  	
  }

  /**
   * Sanitize String
   * 
   * @param string $string
   * @return string
   */
  protected function _sanitizeString($string) {
    
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
  protected function _setError($message, $method, $number = null) {
  
    // express null in text    
    if (is_null($number)) {
      $number = 'null';
    }
    
    $arrError['method'] = $method;
    
    $arrError['number'] = $number;

    $arrError['message'] = $message;

    $this->_arrErrors[] =  $arrError;
    
    $this->_arrLastError = &$this->_arrErrors[count($this->_arrErrors) - 1];

    $this->log->err(__METHOD__." method: [$method], number: [$number], message: [$message]");
  
  }
  
  /**
   * Get Elapsed Time
   * 
   * @param string $id
   * @return float
   */
  protected function _timerGet($id) {
    
    if (!isset($this->_arrTimer[$id]['elapsed'] )) {
      $this->log->warn(__METHOD__.': no time to return (oops)...');
    } else {
      return $this->_arrTimer[$id]['elapsed'];
    }

  }  
  
  /**
   * Timer End
   * 
   * @param string $id
   * @return mixed
   */
  protected function _timerEnd($id) {
    
    if (!isset($this->_arrTimer[$id]['start'] )) {
      
      $this->log->warn(__METHOD__.': missing the start value (oops), not tracking...');
      
    } else {
    
      // using microtime and storing as a float
      $this->_arrTimer[$id]['end'] = microtime(TRUE);
      
      // calculate the difference
      $this->_arrTimer[$id]['elapsed'] = $this->_arrTimer[$id]['end'] - $this->_arrTimer[$id]['start'];
      
      // gain an efficiency
      return $this->_arrTimer[$id]['elapsed'];
    }
    
  }   

  /**
   * Timer Start
   * 
   * @param string $id
   * @return void
   */
  protected function _timerStart($id) {
    
    // using microtime and storing as a float
    $this->_arrTimer[$id]['start'] = microtime(TRUE);
    
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
      $namespace = $this->_sanitizeString($namespace);
    
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
  public function getErrors() {
    
    return $this->_arrErrors;
    
  }  
  
  /**
   * Get Last Error
   */
  public function getLastError() {
    
    return $this->_arrLastError;
    
  }
  
  /**
   * Get Namespace
   * 
   * @return string
   */
  public function getNamespace() {
    
    return $this->_namespace;
    
  }
  
  /**
   * Error Test
   * 
   * @param string $errMessage
   * @param string $label default ''
   * @param integer $errNumber default null
   * @return void
   */
  public function testError($errMessage, $label = '', $errNumber = null) {
  
    $this->_setError($errMessage, $label, $errNumber);
    
  }
  
  /**
   * Timer Test
   * 
   * @param integer $timer_wait in microseconds
   * @return float
   */
  public function testTimer($timerWait) {
    
    $this->log->debug(__METHOD__.': timer wait set at ['.$timerWait.'] milliseconds ('.($timerWait / 1000000).') seconds');
    
    $this->_timerStart('tester');
    usleep($timerWait);
    $this->_timerEnd('tester');
    
    $this->log->debug(__METHOD__.': Total Time: ['.$this->_timerGet('tester').'] seconds');
    return $this->_timerGet('tester');
    
  }  
  
}  
?>

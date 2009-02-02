<?php
/*
 * Main Logging Include
 *
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 *
 * @todo log rotation for file logs
 * @todo db logging
 *
 * @author Dallas Vogels <dvogels@islandlinux.org>
 * @copyright (c) 2007-2009 Dallas Vogels
 *
 */

// initialize the log
$log = &Log::singleton('composite');

if (DV_LOG_FILE) {
  
  // everyone read/write
  // @todo tighten up but let dev mode have rwx across the board
  $arrLogConfiguration = array('mode' => '666');
  
  $logFile = &Log::singleton('file', DV_LOG_FILE_PATH, DV_APP_NAME, $arrLogConfiguration, DV_LOG_FILE);
  $log->addChild($logFile);
  
  $log->debug(basename(__FILE__).': Pear::Log->file initialized with ['.DV_LOG_FILE.']');
  
}

if (DV_LOG_FIREBUG) {
  
  // set buffering to allow header calls
  $arrLogConfiguration = array('buffering' => TRUE);
  
  $logFirebug = &Log::singleton('firebug', '', DV_APP_NAME, $arrLogConfiguration, DV_LOG_FIREBUG);
  $log->addChild($logFirebug);
  
  $log->debug(basename(__FILE__).': Pear::Log->firebug initialized with ['.DV_LOG_FIREBUG.']');
  
}

if (DV_LOG_EMAIL) {
  
  // email subject
  $arrLogConfiguration = array('subject' => 'Important Log Events');  

  // email configuration
  $arrLogConfiguration = array(
		'subject' => 'Log Events: ['.DV_APP_NAME.']',
		'from' => DV_COMPANY_NAME.' <'.DV_LOG_EMAIL_ADDRESS.'>',
		'preamble' => 'Something worthy of attention has occurred:'
		);  
  
  $logEmail = &Log::singleton('mail', DV_LOG_EMAIL_ADDRESS, DV_APP_NAME, $arrLogConfiguration, DV_LOG_EMAIL);
  $log->addChild($logEmail);
  
  $log->debug(basename(__FILE__).': Pear::Log->email initialized with ['.DV_LOG_EMAIL.'], delivered to ['.DV_LOG_EMAIL_ADDRESS.']');
  
}

?>

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
  $arr_log_conf = array('mode' => '666');
  
  $log_file = &Log::singleton('file', DV_LOG_FILE_PATH, DV_APP_NAME, $arr_log_conf, DV_LOG_FILE);
  $log->addChild($log_file);
  
  $log->debug(basename(__FILE__).': Pear::Log->file initialized with ['.DV_LOG_FILE.']');
  
}

if (DV_LOG_FIREBUG) {
  
  // set buffering to allow header calls
  $arr_log_conf = array('buffering' => TRUE);
  
  $log_firebug = &Log::singleton('firebug', '', DV_APP_NAME, $arr_log_conf, DV_LOG_FIREBUG);
  $log->addChild($log_firebug);
  
  $log->debug(basename(__FILE__).': Pear::Log->firebug initialized with ['.DV_LOG_FIREBUG.']');
  
}

if (DV_LOG_EMAIL) {
  
  // email subject
  $arr_log_conf = array('subject' => 'Important Log Events');  
  
  $log_email = &Log::singleton('mail', DV_LOG_EMAIL_ADDRESS, DV_APP_NAME, $arr_log_conf, DV_LOG_EMAIL);
  $log->addChild($log_email);
  
  $log->debug(basename(__FILE__).': Pear::Log->email initialized with ['.DV_LOG_EMAIL.'], delivered to ['.DV_LOG_EMAIL_ADDRESS.']');
  
}

?>

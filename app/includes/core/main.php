<?php
/*
 * Main Include
 * 
 * Main entry point for includes.  This is where the initialization and loading
 * of required libraries starts.  Each major component, such as Smarty and
 * phpMailer, is stored in it's own configuration file and loaded here.
 *
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 *
 * @author Dallas Vogels <dvogels@islandlinux.org>
 * @copyright (c) 2007-2009 Dallas Vogels
 * 
 */

// Set the error reporting level for PHP; will be overridden by per domain
// settings.  This initial setting will ensure all errors are displayed.
error_reporting(E_ALL);

/**
 * Main Constants
 */
require 'mainConstants.php';

/**
 * Global Basic Functions
 */
require 'mainBasicFunctions.php';

// get the domain name and set the domain include
$domainName = dvGetDomainName();
$domainInclude =  DV_APP_ROOT.'domains/'.$domainName.'/includes/mainConstants.php';

// check that main contants for the domain exists, must load per domain settings
if (file_exists($domainInclude)) {
  
  /**
   * Pear::Log
   * 
   * The constants that Pear::Log uses for log levels is required for the per
   * domain constants.
   */
  require('Log.php');  
  
  /**
   * Per domain include
   */
  require($domainInclude);
  
} else {
  
  die("Could not bootstrap domain [$domainName]");

}

// set the session save path
// @todo cleanup for session save path
session_save_path(DV_APP_ROOT.'domains/'.$domainName.'sessions/');

/**
 * Main Logging
 */
require 'mainLogging.php';
$log->debug(basename(__FILE__).': bootstrapping complete for domain ['.$domainName.']');

if (DV_LOCAL_DEV === TRUE) {
	$log->debug(basename(__FILE__).': USING LOCAL DEVELOPMENT');
}

// set the error reporting level for PHP as per domain settings
error_reporting(DV_ERROR_REPORTING);
$log->debug(basename(__FILE__).': setting PHP error level reporting to '.DV_ERROR_REPORTING);

// error check the domain for proper setup
dv_check_domain_setup($log, DV_APP_ROOT.'domains/'.$domainName.'/');

/**
 * Main Smarty
 */
require 'mainSmarty.php';

/**
 * Main Database
 */
require 'mainDatabase.php';

/**
 * Main Processing
 */
require 'mainProcessing.php';

?>

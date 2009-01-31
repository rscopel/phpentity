<?php
/*
 * Main Database Include
 *
 * Database abstraction entry point.  Note that logging must be enabled for this
 * include to function correctly.
 * 
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 *
 * @todo everything db related
 *
 * @author Dallas Vogels <dvogels@islandlinux.org>
 * @copyright (c) 2007-2009 Dallas Vogels
 *
 */

/**
 * Database Abstraction
 */
require 'MDB2.php';

$dsn = array(
    'phptype'  => DV_DB_TYPE,
    'username' => DV_DB_USERNAME,
    'password' => DV_DB_PASSWORD,
    'hostspec' => DV_DB_HOST,
    'database' => DV_DB_DATABASE,
);

$options = array(
    'debug'       => 2,
    'portability' => MDB2_PORTABILITY_ALL,
);

if (DV_DB_CONNECTION == 'factory') {
  $conn =& MDB2::factory($dsn, $options);
} elseif (DV_DB_CONNECTION == 'connect') {
  $conn =& MDB2::connect($dsn, $options);
}
$log->debug(basename(__FILE__).': database connection type ['.DV_DB_CONNECTION.']');
$log->debug(basename(__FILE__).': connecting to database ['.DV_DB_DATABASE.'] with user ['.DV_DB_USERNAME.'] on host ['.DV_DB_HOST.']');

if (PEAR::isError($conn)) {
    die_hard($log, basename(__FILE__).': '.$conn->getDebugInfo());
}

?>
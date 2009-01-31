<?php
/*
 * _template Constants
 * 
 * Domain defined constants.
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
 * Path to static web root
 */
define('DV_STATIC_WEB_ROOT', '/app/www/'); 

/**
 * PHP Error Reporting Level
 * 
 * See http://ca3.php.net/error_reporting for settings.
 */
define('DV_ERROR_REPORTING', E_ALL);

/**
 * Log to Email
 * 
 * http://www.indelible.org/php/Log/guide.html#log-levels
 * 
 * Set to FALSE to ignore
 */
define('DV_LOG_EMAIL', FALSE);

/**
 * Log to Email address
 */
define('DV_LOG_EMAIL_ADDRESS', DV_APP_EMAIL_ADMIN);

/**
 * Log to File
 * 
 * http://www.indelible.org/php/Log/guide.html#log-levels
 * 
 * Set to FALSE to ignore
 * */
define('DV_LOG_FILE', PEAR_LOG_INFO);

/**
 * Log to File Path
 * 
 */
define('DV_LOG_FILE_PATH', DV_APP_ROOT."domains/$domain_name/logs/pear-log.log");

/**
 * Log to Firebug
 * 
 * http://www.indelible.org/php/Log/guide.html#log-levels
 * 
 * Set to FALSE to ignore
 */
define('DV_LOG_FIREBUG', PEAR_LOG_DEBUG);

/**
 * Database Connection Type
 * 
 * Set to 'factory' for efficient connections.  This is suitable for production
 * servers where a connection is only made when needed.
 * 
 * Set to 'connect' for eager connections.  Suitable for development servers
 * where database connection issues can be quickly identified.
 */
define('DV_DB_CONNECTION', 'connect');

/**
 * Database Type
 */ 
define('DV_DB_TYPE', 'mysql');

/**
 * Database Username
 */ 
define('DV_DB_USERNAME', 'dev');

/**
 * Database Password
 */ 
define('DV_DB_PASSWORD', 'dev');

/**
 * Database Host
 */ 
define('DV_DB_HOST', 'localhost');

/**
 * Database Name
 */ 
define('DV_DB_DATABASE', 'dv_phpentity');

/**
 * Smarty Caching
 * 
 * Set to TRUE to enable
 */
define('DV_SMARTY_CACHING', FALSE);

/**
 * Smarty Caching Timeout
 * 
 * Number of seconds for cache to refresh, set to -1 to never expire.
 */
define('DV_SMARTY_CACHE_TIMEOUT', 3600);

?>

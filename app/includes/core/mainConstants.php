<?php
/*
 * Main Constants
 * 
 * Globally defined constants.
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
 * Local development flag
 * 
 * Set to TRUE to use the local _template domain.
 */
define ('DV_LOCAL_DEV', TRUE);

/**
 * Application Name
 */
define('DV_APP_NAME', 'entity');

/**
 * Local App Root
 */
//define('DV_APP_ROOT', preg_replace("/app\/www\/.*/", "", $_SERVER['SCRIPT_FILENAME'])."app/");
define('DV_APP_ROOT', realpath(dirname(__FILE__).'/../../').'/');

/**
 * Main Administrative Email
 */
define('DV_APP_EMAIL_ADMIN', 'dvogels@islandlinux.org');
 
//********************************************************************
// 
//  3rd Party Constants
//
//********************************************************************

//******************************************************
// phpmailer Constants
//******************************************************

/**
 * Path to phpmailer Include
 */
define ('DV_PHPMAILER_INCLUDE', DV_APP_ROOT.'includes/3rd_party/phpmailer/2.1.0beta2/class.phpmailer.php');


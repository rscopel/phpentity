<?php
/*
 * Main Smarty Include
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
 * Smarty Root
 */
define('DV_SMARTY_ROOT', DV_APP_ROOT.'includes/3rd_party/smarty/2.6.22/libs/');

/**
 * Main Smarty Include
 */
require(DV_SMARTY_ROOT.'Smarty.class.php');

// initialize Smarty
$smarty = new Smarty();

// set paths for global
$smarty->template_dir = DV_APP_ROOT.'smarty/templates/';
$smarty->plugins_dir = array(DV_APP_ROOT.'smarty/plugins/', DV_SMARTY_ROOT.'plugins/');

// set paths for per-domain settings
$smarty_root = DV_APP_ROOT."domains/$domain_name/smarty/";
$smarty->cache_dir = $smarty_root.'cache/';
$smarty->compile_dir = $smarty_root.'compile/';

// set up caching for per-domain settings
$smarty->caching = DV_SMARTY_CACHING;
$smarty->cache_lifetime = DV_SMARTY_CACHE_TIMEOUT;
$log->debug(basename(__FILE__).': initialized smarty templating engine');

?>
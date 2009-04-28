<?php
/*
 * Main Index
 * 
 * Main entry point.
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
 * Main Include
 */
require("../includes/core/main.php");

// for debugging
$thisFile = dvGetFile(__FILE__);
$log->debug($thisFile.': accessing');

$smarty->assign('pageTitle', "TEMPLATE");
$smartyTemplate = 'public/template.tpl';

$log->debug($thisFile.': using template ['.$smartyTemplate.']');

$smarty->display($smartyTemplate);
 
?>

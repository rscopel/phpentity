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

$smarty->assign('pageTitle', "TEMPLATE");
$smartyTemplate = 'public/template.tpl';

$smarty->display($smartyTemplate);
 
?>

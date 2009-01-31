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

$smarty->assign('dv_page_title', "TEMPLATE");
$smarty_template = 'public/template.tpl';

$smarty->display($smarty_template);
 
?>

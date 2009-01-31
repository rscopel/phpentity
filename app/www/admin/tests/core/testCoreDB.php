<?php
/*
 * Tests: Core Database CRUD
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
require('../../../../includes/core/main.php');

$smarty_template = 'admin/tests/default.tpl';
$smarty->assign('dv_page_title', 'Tests: Core Database');
$smarty->assign('dv_page', $_SERVER['PHP_SELF']);

/**
 * Core Database Include
 */
require(DV_APP_ROOT.'includes/core/classes/classCoreDB.php');

// grab an action, if any
if (isset($_GET["action"])) {
  $action = $_GET["action"];
} else {
  $action = '';
}

$database = New CoreDB("test_record", $conn, $log);

$output = '';

switch($action) {

  default:
    break;
  
}

// build menu
$arr_menu[] = array('action' => "ACTIONHERE", 'title' => "TITLEHERE");

$smarty->assign_by_ref('arr_menu', $arr_menu);
$smarty->assign_by_ref('dv_output', $output);
$smarty->display($smarty_template);
?>
<?php
/*
 * Tests: Core Database
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
$smarty->assign('dv_page_title', 'Tests: CoreDatabase');
$smarty->assign('dv_page', $_SERVER['PHP_SELF']);

/**
 * Core Database Include
 */
require(DV_APP_ROOT.'includes/core/classes/classCoreDatabase.php');

// grab an action, if any
if (isset($_GET["action"])) {
  $action = $_GET["action"];
} else {
  $action = '';
}

$database = New CoreDatabase("testCoreDatabase", $conn, $log);

$output = '';	// output is between <pre> tags

switch($action) {

	case 'multiPreparedInsert':
		
		$sql = 'INSERT INTO _test (name, description, date_created) VALUES (?, ?, ?)';
		$arrData['first'] = array('multi-test name', rand(0, 1000), $database->getTimestamp());
		$arrData[] = array('multi-test name2', rand(0, 1000), $database->getTimestamp());
		$arrData[] = array('multi-test name3', rand(0, 1000), $database->getTimestamp());
		$arrResult = $database->preparedMultiInsert($sql, $arrData);
		
		$output = print_r($arrResult, true)."\nErrors:\n".print_r($database->getErrors(), true);
		
		break;
		
	case 'preparedDelete':
	
		$sql = 'DELETE FROM _test WHERE deleted = ? AND (id > ?)';
		$arrData = array(0, 10);
		$result = $database->preparedDelete($sql, $arrData);
		
		if ($result !== false) {
			$output = 'Delete affected ['.$result.'] records';
		} else {
			$output = print_r($database->getErrors(), true);
		}
		
		break;

	case 'preparedInsert':
	
		$sql = 'INSERT INTO _test (name, description, date_created) VALUES (?, ?, ?)';
		$arrData = array('test name', rand(0, 1000), $database->getTimestamp());
		$result = $database->preparedInsert($sql, $arrData);
		
		if ($result !== false) {
			$output = 'Inserted record with id ['.$result.']';
		} else {
			$output = print_r($database->getErrors(), true);
		}
		
		break;
		
	case 'preparedSelect':
		
		$sql = 'SELECT * FROM _test WHERE deleted = ? AND viewable = ?';
		$arrData = array(0, 1);
		$result = $database->preparedSelect($sql, $arrData);
		
		if ($result !== false) {
			$output = print_r($result, true);
		} else {
			$output = print_r($database->getErrors(), true);
		}		
		
		break;
		
	case 'preparedUpdate':
		
		$sql = 'UPDATE _test SET viewable = ? WHERE (id > ? AND id < ?)';
		$arrData = array(0, 1, 5);
		$result = $database->preparedUpdate($sql, $arrData);
		
		if ($result !== false) {
			$output = 'Update affected ['.$result.'] records';
		} else {
			$output = print_r($database->getErrors(), true);
		}		
		
		break;
		
	case 'truncateTable':
		
		$sql = 'TRUNCATE _test';
		$result = $database->executeSQL($sql);
		
		if ($result !== false) {
			$output = 'Truncated table';
		} else {
			$output = print_r($database->getErrors(), true);
		}
		
		break;	
	
	case 'query':

		$sql = 'SELECT * FROM _test WHERE deleted = 0';
		$result = $database->querySQL($sql);
		
		if ($result !== false) {
			$output = print_r($result, true);
		} else {
			$output = print_r($database->getErrors(), true);
		}		

		break;
		
  default:
    break;
  
}

// build menu
$arr_menu[] = array('action' => "preparedDelete", 'title' => "Prepared Delete");
$arr_menu[] = array('action' => "preparedInsert", 'title' => "Prepared Insert");
$arr_menu[] = array('action' => "preparedSelect", 'title' => "Prepared Select");
$arr_menu[] = array('action' => "preparedUpdate", 'title' => "Prepared Update");
$arr_menu[] = array('action' => "query", 'title' => "Query");
$arr_menu[] = array('action' => "multiPreparedInsert", 'title' => "Multi-Prepared Insert");
$arr_menu[] = array('action' => "truncateTable", 'title' => "Truncate Table");

$smarty->assign_by_ref('arr_menu', $arr_menu);
$smarty->assign_by_ref('dv_output', $output);
$smarty->display($smarty_template);
?>
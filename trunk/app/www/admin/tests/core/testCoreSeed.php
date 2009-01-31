<?php
/*
 * Tests: Core Seed
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
$smarty->assign('dv_page_title', 'Core Seed');
$smarty->assign('dv_page', $_SERVER['PHP_SELF']);

/**
 * Core Seed Include
 */
require(DV_APP_ROOT.'includes/core/classes/classCoreSeed.php');

// create the core seed object
$obj_seed = New CoreSeed("test_seed", $conn, $log);

// grab an action, if any
if (isset($_GET["action"])) {
  $action = $_GET["action"];
} else {
  $action = "";
}

$output = "";

switch($action) {
  
  case 'incorrect_namespace':
    $obj_seed = New CoreSeed('test seed!', $log);
    $output = "tested [$action]";
    break;
    
  case 'timer_test':
    $obj_seed = New CoreSeed('test_timer', $log);
    $random_wait = rand(1, 20) * 100000;
    $output = 'Timer test result in seconds: ['.$obj_seed->test_timer($random_wait).'], random wait in seconds ['.($random_wait / 1000000).']';
    break;  

  case 'error_test':
    $obj_seed = New CoreSeed('test_error', $log);
    $obj_seed->test_error('Major Malfunction', 'testing', -1);
    $obj_seed->test_error('Major Malfunction 2', 'testing', -1);
    $obj_seed->test_error(basename(__FILE__).' test');
    $obj_seed->test_error('Major Malfunction 3', '', -1);
    $output = print_r($obj_seed->get_errors(), TRUE);
    break;  
  
}

// build menu
$arr_menu[] = array('action' => "incorrect_namespace", 'title' => "Incorrect Namespace");
$arr_menu[] = array('action' => "timer_test", 'title' => "Timer Test");
$arr_menu[] = array('action' => "error_test", 'title' => "Error Test");

$smarty->assign_by_ref('arr_menu', $arr_menu);
$smarty->assign_by_ref('dv_output', $output);
$smarty->display($smarty_template);
?>
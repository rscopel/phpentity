<?php
/*
 * Tests: CoreSeed
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

$smartyTemplate = 'admin/tests/default.tpl';
$smarty->assign('pageTitle', 'CoreSeed');
$smarty->assign('dvPage', $_SERVER['PHP_SELF']);

/**
 * Core Seed Include
 */
require(DV_APP_ROOT.'includes/core/classes/classCoreSeed.php');

// grab an action, if any
if (isset($_GET["action"])) {
  $action = $_GET["action"];
} else {
  $action = "";
}

$output = "";

switch($action) {
  
  case 'incorrectNamespace':
    $Seed = New CoreSeed('test seed!', $log);
    $output = "tested [$action]";
    break;
    
  case 'timerTest':
    $Seed = New CoreSeed('testTimer', $log);
    $randomWait = rand(1, 20) * 100000;
    $output = 'Timer test result in seconds: ['.$Seed->testTimer($randomWait).'], random wait in seconds ['.($randomWait / 1000000).']';
    break;  

  case 'errorTest':
    $Seed = New CoreSeed('testError', $log);
    $Seed->testError('Major Malfunction', 'testing', -1);
    $Seed->testError('Major Malfunction 2', 'testing', -1);
    $Seed->testError(basename(__FILE__).' test');
    $Seed->testError('Major Malfunction 3', '', -1);
    $output = print_r($Seed->getErrors(), TRUE);
    break;  
  
}

// build menu
$arrMenu[] = array('action' => "incorrectNamespace", 'title' => "Incorrect Namespace");
$arrMenu[] = array('action' => "timerTest", 'title' => "Timer Test");
$arrMenu[] = array('action' => "errorTest", 'title' => "Error Test");

$smarty->assign_by_ref('arrMenu', $arrMenu);
$smarty->assign_by_ref('dvOutput', $output);
$smarty->display($smartyTemplate);
?>
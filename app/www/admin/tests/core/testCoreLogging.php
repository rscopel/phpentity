<?php
/*
 * Tests: Logging
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
$smarty->assign('pageTitle', 'CoreSeed->Logging');
$smarty->assign('dvPage', $_SERVER['PHP_SELF']);

// grab an action, if any
if (isset($_GET["action"])) {
  $action = $_GET["action"];
} else {
  $action = "";
}

$output = "";

$arrLogLevels = array(
  'debug' => PEAR_LOG_DEBUG,
  'info' => PEAR_LOG_INFO,
  'notice' => PEAR_LOG_NOTICE,
  'warning' => PEAR_LOG_WARNING,
  'err' => PEAR_LOG_ERR,
  'crit' => PEAR_LOG_CRIT,
  'alert' => PEAR_LOG_ALERT,
  'emerge' => PEAR_LOG_EMERG 
 );

switch($action) {
  
  case 'allLevels':
    
    foreach ($arrLogLevels as $key => $data) {
    
      $msg = basename(__FILE__).": testing [$key]";
      $log->log($msg, $data);
      $output .= $msg."\n";
    
    }
    
    break;
    
  default:
    
    if (isset($arrLogLevels[$action])) {

      $msg = basename(__FILE__).": testing [$action]";
      $log->log($msg, $arrLogLevels[$action]);
      $output .= $msg."\n";
            
    }
    
    break;
  
}

// build menu
$arrMenu[] = array('action' => 'allLevels', 'title' => 'Test All');

foreach ($arrLogLevels as $key => $data) {
  $arrMenu[] = array('action' => $key, 'title' => "Test [$key]");
}

$smarty->assign_by_ref('arrMenu', $arrMenu);
$smarty->assign_by_ref('dvOutput', $output);
$smarty->display($smartyTemplate);
?>
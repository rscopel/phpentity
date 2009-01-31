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

$smarty_template = 'admin/tests/default.tpl';
$smarty->assign('dv_page_title', 'Logging');
$smarty->assign('dv_page', $_SERVER['PHP_SELF']);

// grab an action, if any
if (isset($_GET["action"])) {
  $action = $_GET["action"];
} else {
  $action = "";
}

$output = "";

$arr_log_levels = array(
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
  
  case 'all_levels':
    
    foreach ($arr_log_levels as $key => $data) {
    
      $msg = basename(__FILE__).": testing [$key]";
      $log->log($msg, $data);
      $output .= $msg."\n";
    
    }
    
    break;
    
  default:
    
    if (isset($arr_log_levels[$action])) {

      $msg = basename(__FILE__).": testing [$action]";
      $log->log($msg, $arr_log_levels[$action]);
      $output .= $msg."\n";
            
    }
    
    break;
  
}

// build menu
$arr_menu[] = array('action' => 'all_levels', 'title' => 'Test All');

foreach ($arr_log_levels as $key => $data) {
  $arr_menu[] = array('action' => $key, 'title' => "Test [$key]");
}

$smarty->assign_by_ref('arr_menu', $arr_menu);
$smarty->assign_by_ref('dv_output', $output);
$smarty->display($smarty_template);
?>
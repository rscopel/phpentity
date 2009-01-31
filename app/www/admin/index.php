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
 * @copyright 2008 Dallas Vogels
 *
 */
 
/**
 * Main Include
 */
require("../../includes/core/main.php");

// init this page for use in template
$smarty->assign('dv_this_page', $_SERVER['PHP_SELF']);

// init
$dv_arr_error_message = array();
$dv_action_message = '';
$dv_page_title = 'Main Administration';

// init the navigation cookie trail
$dv_arr_navigation[] = array('title' => 'Main Menu', 'm' => '', 'a' => array());

// init the primary action if not set
if (!isset($arr_data['a'][0])) {
  $arr_data['a'][0] = '';
}

// init the module if necessary
if (!isset($arr_data['m'])) {
  $arr_data['m'] = '';
}

$log->debug(basename(__FILE__).': processing module: ['.$arr_data['m'].'], primary action: ['.$arr_data['a'][0].']');

if ($dv_module) {

  $smarty->assign('dv_module', $dv_module);

  // @todo build better process to deal with multiple modules
  $dv_module = 'testing/modules/admin/'.$dv_module;

  $dv_required_file = dv_get_require_file(DV_APP_ROOT, $dv_module); 

  if ($dv_required_file === FALSE) {
    die_hard($log, basename(__FILE__).': could not find module ['.$dv_module.']');
  } else {
    
    require_once $dv_required_file;
    
  }
    
} else {
  
  // defaults
  $smarty_template = 'admin/index.tpl';
  
}

// no caching on admin side
$smarty->caching = FALSE;
$smarty->assign_by_ref('dv_action_message', $dv_action_message);
$smarty->assign_by_ref('dv_arr_error_message', $dv_arr_error_message);
$smarty->assign('dv_error_message_count', count($dv_arr_error_message));
$smarty->assign_by_ref('dv_page_title', $dv_page_title);
$smarty->assign_by_ref('dv_arr_navigation', $dv_arr_navigation);
$smarty->display($smarty_template);

?>

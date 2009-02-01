<?php
/*
 * Administration Index
 * 
 * Main entry point to the administration area.
 *
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 *
 * @author Dallas Vogels <dvogels@islandlinux.org>
 * @copyright (c) 2008-2009 Dallas Vogels
 *
 */
 
/**
 * Main Include
 */
require("../../includes/core/main.php");

// init this page for use in template
$smarty->assign('thisPage', $_SERVER['PHP_SELF']);

// init
$arrErrorMessage = array();
$arrAction_Message = '';
$pageTitle = 'Main Administration';

// init the navigation cookie trail
$arrNavigation[] = array('title' => 'Main Menu', 'm' => '', 'a' => array());

// init the primary action if not set
if (!isset($arrWebData['a'][0])) {
  $arrWebData['a'][0] = '';
}

// init the module if necessary
if (!isset($arrWebData['m'])) {
  $arrWebData['m'] = '';
}

$log->debug(basename(__FILE__).': processing module: ['.$arrWebData['m'].'], primary action: ['.$arrWebData['a'][0].']');

if ($dvModule) {

  $smarty->assign('dvModule', $dvModule);

  // @todo build better process to deal with multiple modules
  $dvModule = 'testing/modules/admin/'.$dvModule;

  $requiredFile = dvGetRequiredFile(DV_APP_ROOT, $dvModule); 

  if ($requiredFile === FALSE) {
    die_hard($log, basename(__FILE__).': could not find module ['.$dvModule.']');
  } else {
    
    require_once $requiredFile;
    
  }
    
} else {
  
  // defaults
  $smartyTemplate = 'admin/index.tpl';
  
}

// no caching on admin side
$smarty->caching = FALSE;
$smarty->assign_by_ref('actionMessage', $actionMessage);
$smarty->assign_by_ref('arrErrorMessage', $arrErrorMessage);
$smarty->assign('errorMessageCount', count($arrErrorMessage));
$smarty->assign_by_ref('pageTitle', $pageTitle);
$smarty->assign_by_ref('arrNavigation', $arrNavigation);
$smarty->display($smartyTemplate);

?>

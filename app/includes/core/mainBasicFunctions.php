<?php
/*
 * Global Basic Functions
 * 
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
 * Die without giving up to much info the the user.
 * 
 * @todo incorporate into smarty display
 * 
 * @param object &$log
 * @param string $message
 * @return void
 */
function die_hard(&$log, $message) {
  
  // log the message
  $log->crit($message);
  
  // @todo incorporate into smarty display for user friendly display
  die("Please contact the system administrator");
  
}

/**
 * Check Domain Setup
 * 
 * Ensure that the proper directories and write permissions are set up for this
 * domain.
 * 
 * Future dev: this can be the precurser to a dynamic creation for new domains
 * 
 * @todo cache lookups for better performance, maybe a text file with timestamp
 * 
 * @param object &$log
 * @param string $domain_name
 * @return TRUE (or nothing at all)
 */
function dv_check_domain_setup(&$log, $domain_root) {

  $arr_checks[] = array('required' => TRUE, 'writable' => FALSE, 'type' => 'd', 'path' => 'includes/');
  $arr_checks[] = array('required' => TRUE, 'writable' => TRUE,  'type' => 'd', 'path' => 'sessions/');
  $arr_checks[] = array('required' => TRUE, 'writable' => TRUE,  'type' => 'd', 'path' => 'logs/');
  $arr_checks[] = array('required' => TRUE, 'writable' => FALSE,  'type' => 'd', 'path' => 'smarty/');  
  $arr_checks[] = array('required' => TRUE, 'writable' => TRUE,  'type' => 'd', 'path' => 'smarty/cache/');
  $arr_checks[] = array('required' => TRUE, 'writable' => TRUE,  'type' => 'd', 'path' => 'smarty/compile/');
  $arr_checks[] = array('required' => TRUE, 'writable' => TRUE,  'type' => 'd', 'path' => 'tmp/');
  $arr_checks[] = array('required' => TRUE, 'type' => 'f', 'path' => 'includes/mainConstants.php');
  
  // prove wrong
  $error = FALSE;
  
  // loop thru the checks
  foreach ($arr_checks as $arr_check) {
    
    // Does the directory exist?
    if ($arr_check['required'] && $arr_check['type'] == 'd') {
      
      // make sure path exists
      if (! is_dir($domain_root.$arr_check['path'])) {
        $error = TRUE;
        $log->error(__FUNCTION__.': missing directory ['.$arr_check['path'].'] at ['.$domain_root.']');
      } else {
        
        // Is the directory writable?
        if ($arr_check['writable']) {

          if (! is_writable($domain_root.$arr_check['path'])) {
            $error = TRUE;
            $log->err(__FUNCTION__.': cannot write to ['.$arr_check['path'].'] at ['.$domain_root.']');
          }
          
        } // end writable
        
      } // end is_dir
      
    } // end required
    
    // Does the file exist?
    if ($arr_check['required'] && $arr_check['type'] == 'f') {
      
      // make sure path exists
      if (! is_file($domain_root.$arr_check['path'])) {
        $error = TRUE;
        $log->err(__FUNCTION__.': missing file ['.$arr_check['path'].']  at ['.$domain_root.']');
      }      
      
    }
    
  } // end foreach
  
  if ($error) {
    die_hard($log, 'Cannot initialize ['.$domain_root.'] at ['.$domain_root.']');
  }
  
  return TRUE;
  
}

/**
 * Check for Filename Duplicates
 * 
 * Returns new filename if duplicate is found.
 * 
 * @param string $directory
 * @param string $filename
 * @return mixed
 */
function dv_check_filename_dupe($directory, $filename) {
  
  global $log;
  
  // not found/error occurred
  $ret = TRUE;
  
  // ensure the directory exists
  if (is_dir($directory)) {
  
    // loop through directory contents and determine if dupe exists
    if ($handle = opendir($directory)) {
    
      $filefound = FALSE;
  
      /* This is the correct way to loop over the directory. */
      while (false !== ($file = readdir($handle))) {
        
        if ($file == $filename) {
          
          $filefound = TRUE;
          
          // stop loop
          break;
          
        }
        
      }
      
      closedir($handle);
      
      if (! $filefound) {
        
        // good, this is what we want
        $log->debug(__FUNCTION__.': did not find file ['.$directory.$filename.']');
        
        // return the filename
        $ret = FALSE;
        
      } else {
        
        // file/dir found
        $log->debug(__FUNCTION__.': found ['.$directory.$filename.']');
       
      }
        
    } else {
      
      die_hard($log, __FUNCTION__.' could not open directory ['.$directory.']');
      
    }
  
  } else {
    
    die_hard($log, __FUNCTION__.' directory ['.$directory.'] does not exist or is not a directory!');
    
  }
  
}

/**
 * Add Navigation Item
 */
function dv_add_nav_item($arr_navigation, $title, $module, $action) {
  
  // check action for array, convert if necessary
}

/**
 * Get Domain Name
 * 
 * Checks for local development.  Returns the _SERVER[SERVER_NAME] otherwise.
 * 
 * @return string
 */
function dv_get_domain_name() {
  
  // check for local development
  if (DV_LOCAL_DEV) {
    $domain_name = "_template";
  } else {
    $domain_name = $_SERVER['SERVER_NAME'];
  }
  
  return $domain_name;
  
}

/**
 * Get Specified File Path
 * 
 * @param string $root_path
 * @param string $module
 * @return mixed
 */
function dvGetRequiredFile($root_path, $module) {
  
  // @todo refactor one day
  global $log;
  
  $ret = FALSE;
  
  // @todo fix the regular expression
  // $test_module_name = preg_replace('/([!a-z]|[!A-Z]|[^\-]|[^_]|[^0-9])/', '', $module);
  $test_module_name = $module;
  
  if ($test_module_name != $module) {
    die_hard($log, basename(__FILE__).': file path contains bad characters, original: ['.$module.'] cleaned: ['.$test_module_name.']');
  }
  
  $file_path = $root_path.'includes/'.$module.'.php'; 
  
  $log->debug(__METHOD__.': looking for ['.$file_path.']');
  
  if (file_exists($file_path)) {
    $ret = $file_path;
  } else {
    $log->warning(basename(__FILE__).': cannot find required file');
  }
  
  return $ret;
  
}
?>

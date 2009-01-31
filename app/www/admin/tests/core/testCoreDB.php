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

$tablename = 'entity_test';

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

/**
 * Initialize record Class
 * 
 * @param string $tablename default $tablename
 * @return &object
 */
function &dv_init_record($tablename = 'entity') {
  
  // ease of use; for testing
  global $conn, $log;
  
  $obj_db = New CoreDB("test_record", $conn, $log);
  $obj_db->set_table_name($tablename);  
  
  return $obj_db;
}

/**
 * Grab an open ID for testing
 * 
 * @param integer default 1 
 * @return integer
 */
function dv_get_id($tablename, $limit = 1) {  
  
  $obj_db = dv_init_record($tablename);
  $arr_records = $obj_db->load_records(null, null, $limit);
  
  if (count($arr_records) == 1) { 
    
    return (int) $arr_records[0]['id'];
    
  } else {
  
    $arr_ids = array();
        
    foreach ($arr_records as $arr_record)  {
      $arr_ids[] = (int) $arr_record['id'];
    }
    
    return implode(',', $arr_ids);
    
  }
  
} 

// grab available id for use in some of the tests
$id_record = dv_get_id($tablename, 1);

$log->debug(basename(__FILE__).': using test id ['.$id_record.']');

$output = '';

switch($action) {
  
  case 'db_load_table_structure':
    $obj_db = dv_init_record($tablename);
    $output = 'Refer to firebug logging.';
    break;

  case 'db_save_new_record':
    $arr_data = array('id' => 0); 
    $obj_db = dv_init_record($tablename);
    $id = $obj_db->save_record($arr_data);
    $output = "Inserted record id [$id]";
    break;
    
  case 'db_save_update_record':
    $arr_data = array('id' => $id_record, 'desc_short' => time()); 
    $obj_db = dv_init_record($tablename);
    $records_affected = $obj_db->save_record($arr_data);
    $output = "Updated record id [$id_record], number of records affected: [$records_affected]";
    break;    
        
  case 'db_load_record':
    $obj_db = dv_init_record($tablename);
    $arr_data = $obj_db->load_record($id_record);
    $output = print_r($arr_data, TRUE);
    break;

  case 'db_load_records':
    $obj_db = dv_init_record($tablename);
    $arr_data = $obj_db->load_records(explode(',', dv_get_id($tablename, 3)), 'id DESC');
    $output = print_r($arr_data, TRUE);
    break;

  case 'db_delete_record':
    $obj_db = dv_init_record($tablename);
    $records_affected = $obj_db->delete_record($id_record);
    $output = "Deleted record id [$id_record], number of records affected: [$records_affected]";
    break;

  default;
    break;
  
}

// build menu
$arr_menu[] = array('action' => "db_load_table_structure", 'title' => "DB: Init Class");
$arr_menu[] = array('action' => "db_save_new_record", 'title' => "DB: Create Record");
$arr_menu[] = array('action' => "db_load_record", 'title' => "DB: Retrieve Record");
$arr_menu[] = array('action' => "db_load_records", 'title' => "DB: Retrieve Records");
$arr_menu[] = array('action' => "db_save_update_record", 'title' => "DB: Update Record");
$arr_menu[] = array('action' => "db_delete_record", 'title' => "DB: Delete Record");

$smarty->assign_by_ref('arr_menu', $arr_menu);
$smarty->assign_by_ref('dv_output', $output);
$smarty->display($smarty_template);
?>
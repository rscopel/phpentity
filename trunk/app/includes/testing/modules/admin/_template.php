<?php
/**
 * ADMIN MODULE TEMPLATE
 *
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 *
 * @author Dallas Vogels <dvogels@islandlinux.org>
 * @copyright (c) 2008 Dallas Vogels
 */

$smarty_template = 'admin/_template.tpl';
$dv_page_title = 'ADMIN TEMPLATE';
$dv_arr_navigation[] = array('title' => 'Admin _template', 'm' => '_template', 'a' => array());

$log->debug(basename(__FILE__).': processing action ['.$arr_data['a'][0].']');

switch ($arr_data['a'][0]) {
    
  default:
    // this is how to set an error
    $dv_arr_error_message[] = 'testing _template';
    break;
  
}

?>

<?php
/*
 * Main Processing Include
 *
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 *
 * @author Dallas Vogels <dvogels@islandlinux.org>
 * @copyright (c) 2007-2009 Dallas Vogels
 * 
 */

// treat POST and GET as the same
$arr_data = array_merge($_POST, $_GET);

// init the module
if (isset($arr_data['m'])) {
  $dv_module = $arr_data['m'];  
} else {
  $dv_module = '';
}

// evaluate for sub-actions such as a2, a3, a4, ect.
$counter = '';
$arr_subactions = array();
while (isset($arr_data['a'.$counter])) {
  $arr_subactions[] = $arr_data['a'.$counter];
  if (!$counter) {
    $counter++;
  } else {
    $counter = 1;
  }
}

?>
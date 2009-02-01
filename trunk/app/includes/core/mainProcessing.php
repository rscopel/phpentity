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
$arrWebData = array_merge($_POST, $_GET);

// init the module
if (isset($arrWebData['m'])) {
  $dvModule = $arrWebData['m'];  
} else {
  $dvModule = '';
}

// evaluate for sub-actions such as a2, a3, a4, ect.
$counter = '';
$arr_subactions = array();
while (isset($arrWebData['a'.$counter])) {
  $arr_subactions[] = $arrWebData['a'.$counter];
  if (!$counter) {
    $counter++;
  } else {
    $counter = 1;
  }
}

?>
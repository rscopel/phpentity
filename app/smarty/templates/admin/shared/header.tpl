{*
  Shared Public Header
   
  $LastChangedDate$
  $LastChangedRevision$
  $LastChangedBy$
  
  author Dallas Vogels <dvogels@islandlinux.org>
  copyright (c) 2007-2008 Dallas Vogels
  
*}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{$smarty.const.DV_APP_NAME} - {$dv_page_title}</title>
  <link rel="stylesheet" href="{$smarty.const.DV_STATIC_WEB_ROOT}admin/resources/stylesheets/default-admin.css" type="text/css">
</head>

<body>

<div id="navigation">
{assign var='menu_counter' value=0}
{foreach from=$dv_arr_navigation item=dv_arr_nav}
  {assign var='menu_counter' value=`$menu_counter+1`}
  {if $menu_counter > 1} &gt;&gt; {/if}
  <a href="{$dv_this_page}{if $dv_arr_nav.m}?m={$dv_arr_nav.m|escape}{foreach from=$dv_arr_nav.a item=arr_a}&a[]={$arr_a|escape}{/foreach}{/if}">{$dv_arr_nav.title|escape}</a>
{/foreach}
</div>

<h2>{$dv_page_title}</h2>

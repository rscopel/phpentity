{*
  Shared Public Header
   
  $LastChangedDate$
  $LastChangedRevision$
  $LastChangedBy$
  
  author Dallas Vogels <dvogels@islandlinux.org>
  copyright (c) 2007-2009 Dallas Vogels
  
*}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{$smarty.const.DV_APP_NAME} - {$pageTitle}</title>
  <link rel="stylesheet" href="{$smarty.const.DV_STATIC_WEB_ROOT}admin/resources/stylesheets/default-admin.css" type="text/css">
</head>

<body>

<div id="navigation">
{assign var='menu_counter' value=0}
{foreach from=$arrNavigation item=arrNav}
  {assign var='menuCounter' value=`$menuCounter+1`}
  {if $menuCounter > 1} &gt;&gt; {/if}
  <a href="{$thisPage}{if $arrNav.m}?m={$arrNav.m|escape}{foreach from=$arrNav.a item=arr_a}&a[]={$arr_a|escape}{/foreach}{/if}">{$arrNav.title|escape}</a>
{/foreach}
</div>

<h2>{$pageTitle}</h2>

{* 

  Default Template for Tests
  
  $LastChangedDate$
  $LastChangedRevision$
  $LastChangedBy$
 
  author Dallas Vogels <dvogels@islandlinux.org>
  copyright 2007 Dallas Vogels
  
*}{include file='admin/tests/shared/header.tpl'}

<div style="float: left; width: 80%">
<h1>Output:</h1>
{if $dv_output}<pre>{$dv_output}</pre>{/if}
<p>&nbsp;</p>
</div>

<div style="float: left; width: 20%">
<h1>Menu</h1>
<ul>
  {foreach from=$arr_menu item=menu_item}
  <li><a href="{$dv_page}?action={$menu_item.action}">{$menu_item.title}</a></li>
  {/foreach}
</ul>
</div>

{include file='admin/tests/shared/footer.tpl'}
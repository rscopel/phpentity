{* 

  Default Template for Tests
  
  $LastChangedDate$
  $LastChangedRevision$
  $LastChangedBy$
 
  author Dallas Vogels <dvogels@islandlinux.org>
  copyright (c) 2007-2009 Dallas Vogels
  
*}{include file='admin/tests/shared/header.tpl'}

<div><a href="./">Directory Listing</a></div>

<div style="float: left; width: 80%">
<h1>Output:</h1>
{if $dvOutput}<pre>{$dvOutput}</pre>{/if}
<p>&nbsp;</p>
</div>

<div style="float: left; width: 20%">
<h1>Menu</h1>
<ul>
  {foreach from=$arrMenu item=menuItem}
  <li><a href="{$dv_page}?action={$menuItem.action}">{$menuItem.title}</a></li>
  {/foreach}
</ul>
</div>

{include file='admin/tests/shared/footer.tpl'}
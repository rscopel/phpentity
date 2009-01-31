{*
  Message Template
   
  $LastChangedDate$
  $LastChangedRevision$
  $LastChangedBy$
  
  author Dallas Vogels <dvogels@islandlinux.org>
  copyright (c) 2008 Dallas Vogels
*}
{if $dv_error_message_count}

<div id="dv_error_message">

  <h4>Errors Occurred:</h4>
  
  <ul>
{foreach from=$dv_arr_error_message item=dv_error_message}
    <li>{$dv_error_message|escape}</li>
{/foreach}
  </ul>
</div>
{/if}

{if $dv_action_message}
<div id="dv_action_message">
  {$dv_action_message|escape}
</div>
{/if}
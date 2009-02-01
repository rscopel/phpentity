{*
  Message Template
   
  $LastChangedDate$
  $LastChangedRevision$
  $LastChangedBy$
  
  author Dallas Vogels <dvogels@islandlinux.org>
  copyright (c) 2008 Dallas Vogels
*}
{if $errorMessageCount}

<div id="dv_error_message">

  <h4>Errors Occurred:</h4>
  
  <ul>
{foreach from=$arrErrorMessage item=errorMessage}
    <li>{$errorMessage|escape}</li>
{/foreach}
  </ul>
</div>
{/if}

{if $actionMessage}
<div id="actionMessage">
  {$actionMessage|escape}
</div>
{/if}
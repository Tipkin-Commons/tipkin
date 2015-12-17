{extends file="layout.tpl"}
{block name=page_title}{$currentUser->getUsername()} - Invitation{/block}

{block name=page_content}
<div class="col_9 column visible">
	
	{$message}
	{include file='_formInvite.tpl'}
	
</div>
<div class="col_3">
	{include file='_menuActionRight.tpl'}
</div>

{/block}
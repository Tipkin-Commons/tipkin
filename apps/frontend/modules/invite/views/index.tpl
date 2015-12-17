{extends file="layout.tpl"}

{block name=page_title}Invitez vos amis{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#connexion-form').validate();
});
//-->
</script>
{/literal}
<div class="col_12">
	{$message}
	{if isset($connexionMessage)}
		{$connexionMessage}
	{/if}
</div>
<div class="col_9 visible">
	{include file='_formInvite.tpl'}
</div>
<div class="col_3">
	{include file='_menuActionRight.tpl'}
</div>
{/block}
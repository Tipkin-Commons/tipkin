{extends file="layout.tpl"}

{block name=page_title prepend}Connectez-vous{/block}

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
<div class="col_3">
</div>
<div class="col_6 visible">
	{include file='_formConnect.tpl'}
</div>
{/block}
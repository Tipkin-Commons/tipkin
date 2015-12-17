{extends file="layout.tpl"}

{block name=page_title prepend}Inscription{/block}

{block name=meta_desc}Inscrivez-vous gratuitement sur TIPKIN pour tout louer entre particuliers{/block}

{block name=page_content}
<div class="col_2">
</div>
<div class="col_8 visible">
	{if isset($registrationMessage)}
		{$registrationMessage}
	{/if}
	{include file='_formRegister.tpl'}
</div>
{/block}
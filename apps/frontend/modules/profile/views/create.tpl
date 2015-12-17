{extends file="layout.tpl"}

{block name=page_title}{$currentUser->getUsername()} - Créer profil{/block}

{block name=page_content}
<div class="col_8 column visible">
	<h2>Remplissez votre profil</h2>
	
	{include file='_form.tpl'}
	
</div>
{/block}
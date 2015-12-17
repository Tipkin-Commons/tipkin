{extends file="layout.tpl"}

{block name=page_title}{$currentUser->getUsername()} - Créer profil{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
var $radios = $('input:radio[name=gender]');
if($radios.is(':checked') === false) {
    $radios.filter('[value=M]').attr('checked', true);
//-->
</script>
{/literal}
<div class="col_8 column visible">
	<h2>Remplissez votre profil</h2>
	
	{include file='_form.tpl'}
	
</div>
{/block}
{extends file="layout.tpl"}

{block name=page_title}Nouvelle annonce{/block}

{block name=page_content}
<div class="col_12">
	<h4>Créer une nouvelle annonce</h4>
</div>
<div style="margin: 0px 10px;">
	{$message}
</div>
<div class="col_3" style="margin-top: -2px;">
	<ul class="menu vertical right">
		<li class="active">
			<a>Annonce</a>
		</li>
		<li class="disabled">
			<a>Photos</a>
		</li>
		<li class="disabled">
			<a>Prix</a>
		</li>
		<li class="disabled">
			<a>Calendrier</a>
		</li>
		<li class="disabled">
			<a>Indisponibilités</a>
		</li>
	</ul>
</div>
<div class="col_9 visible">
{include file='_formAnnounce.tpl'}
</div>
{/block}
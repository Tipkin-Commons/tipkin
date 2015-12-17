{extends file="layout.tpl"}

{block name=page_title}Nouvelle annonce{/block}

{block name=page_content}
<div class="col_12">
	<h4>Republier l'annonce "<i>{$announce->getTitle()}</i>"</h4>
</div>
<div style="margin: 0px 10px;">
	{$message}
</div>
<div class="col_3" style="margin-top: -2px;">
	<ul class="menu vertical right">
		<li>
			<a href="/announcements/unarchive/{$announce->id()}">Calendrier</a>
		</li>
		<li class="active">
			<a href="/announcements/unarchive/indisponibilities/{$announce->id()}">Indisponibilités</a>
		</li>
	</ul>
</div>
<div class="col_9 visible">
{include file='_formIndisponibilities.tpl'}
</div>
{/block}
{extends file="layout.tpl"}

{block name=page_title}Nouvelle annonce{/block}

{block name=page_content}
<div class="col_12">
	<h4>Gérer la publication de mon annonce</h4>
</div>
<div style="margin: 0px 10px;">
	{$message}
</div>
<div class="col_3" style="margin-top: -2px;">
	<ul class="menu vertical right">
		<li>
			<a href="/announcements/edit/{$announce->id()}">Annonce</a>
		</li>
		<li>
			<a href="/announcements/edit/photo/{$announce->id()}">Photos</a>
		</li>
		<li>
			<a href="/announcements/edit/prices/{$announce->id()}">Prix</a>
		</li>
		<li class="active">
			<a href="/announcements/edit/calendar/{$announce->id()}">Calendrier</a>
		</li>
		<li>
			<a href="/announcements/edit/indisponibilities/{$announce->id()}">Indisponibilités</a>
		</li>
	</ul>
</div>
<div class="col_9 visible" >
{include file='_formCalendar.tpl'}
</div>
{/block}
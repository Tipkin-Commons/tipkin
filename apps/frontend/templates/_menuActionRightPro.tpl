{literal}
<script type="text/javascript">
<!--
$(function(){
	$('.submenu').css('width', '220px');
});
//-->
</script>
{/literal}

<ul class="menu vertical right">
	<li>
		<a href="/announcements-pro">
			<span class="icon small" data-icon="v"></span>
			Mes annonces
		</a>
		<ul>
			<li class="submenu">
				<a href="/announcements-pro/new">
					<span class="icon small" data-icon="+"></span>
					Nouvelle annonce
				</a>
			</li>
			<li class="submenu divider">
				<a href="/announcements-pro/drafts">
					<i>Brouillons ({$nbDrafts})</i>
				</a>
			</li>
			<li class="submenu divider">
				<a href="/announcements-pro/validated">
					Annonces ({$nbValidated})
				</a>
			</li>
			<li class="submenu">
				<a href="/announcements-pro/archived">
					Archives ({$nbArchived})
				</a>
			</li>
			<li class="submenu  divider">
				<a href="/announcements-pro">
					<span class="icon small" data-icon="a"></span>
					Voir la liste
				</a>
			</li>
		</ul>
	</li>
</ul>
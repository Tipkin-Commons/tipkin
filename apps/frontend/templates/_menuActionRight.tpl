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
	{if $nbFeedbackRequests > 0}
		<li>
			<a href="/feedback">
				Feedback(s) en attente ({$nbFeedbackRequests})
			</a>
		</li>
	{/if}
	<li>
		<a href="/contacts">
			Ma Tipkin-ship
		</a>
		<ul>
			<li class="submenu">
				<a href="/contacts/wait">
					<i> En attente ({$nbWait})</i>
				</a>
			</li>
			<li class="submenu divider">
				<a href="/contacts/family">
					Famille ({$nbFamily})
				</a>
			</li>
			<li class="submenu">
				<a href="/contacts/friends">
					Amis ({$nbFriends})
				</a>
			</li>
			<li class="submenu">
				<a href="/contacts/neighbors">
					Voisins ({$nbNeighbors})
				</a>
			</li>
			<li class="submenu">
				<a href="/contacts/tippeurs">
					Tippeurs ({$nbTippeurs})
				</a>
			</li>	
			<li class="submenu divider">
				<a href="/contacts">
					Voir la liste
				</a>
			</li>
			<li class="submenu divider">
				<a href="/invite">
					Inviter des amis
				</a>
			</li>
		</ul>
	</li>
	<li>
		<a>
			Mon activité
		</a>
		<ul>
			<li class="submenu">
				<a href="/activities/reservations">
					Mes emprunts ({$nbReservations})
				</a>
			</li>
			<li class="submenu divider">
				<a href="/activities/locations">
					Mes prêts ({$nbLocations})
				</a>
			</li>
		</ul>
	</li>
	<li>
		<a href="/announcements">
			Mes annonces
		</a>
		<ul>
			<li class="submenu">
				<a href="/announcements/new">
					Nouvelle annonce
				</a>
			</li>
			<li class="submenu divider">
				<a href="/announcements/drafts">
					<i>Brouillons ({$nbDrafts})</i>
				</a>
			</li>
			<li class="submenu divider">
				<a href="/announcements/pending">
					En attente ({$nbPending})
				</a>
			</li>
			<li class="submenu">
				<a href="/announcements/validated">
					Validés ({$nbValidated})
				</a>
			</li>
			<li class="submenu">
				<a href="/announcements/refused">
					Refusées ({$nbRefused})
				</a>
			</li>
			<li class="submenu">
				<a href="/announcements/archived">
					Archivées ({$nbArchived})
				</a>
			</li>
			<li class="submenu  divider">
				<a href="/announcements">
					Voir la liste
				</a>
			</li>
		</ul>
	</li>
	<li>
		<a href="/addresses">
			Mes adresses
		</a>
		<ul>
			<li class="submenu">
				<a href="/addresses/new" class="lightbox">
					Nouvelle adresse
				</a>
			</li>
			<li class="submenu  divider">
				<a href="/addresses">
					Voir la liste
				</a>
			</li>
		</ul>
	</li>
</ul>
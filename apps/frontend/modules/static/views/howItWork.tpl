{extends file="layout.tpl"}

{block name=page_title prepend}Comment ça marche{/block}
{block name=meta_desc}Découvrez TIPKIN en vous familiarisant avec notre philosophie de partage{/block}

{block name=page_content}
{literal}
<style type="text/css">
<!--
#how-it-work .col_4 {
	height: 900px;
}
#how-it-work .col_4 div {
	margin-bottom: 30px;
	text-align: justify;
}

#how-it-work h1 {
	color : #0098A3;
	text-align: center;
	font-size: 2em;
}

#how-it-work h2 {
	color : #0098A3;
	font-size: 1.4em;
}

.gray { font-weight: bold;}

.feedbacks, .count-items {float: none !important;}
.no-margin {margin: 0 !important}
.star img {width: 35px}
-->
</style>
{/literal}
<div class="col_12" id="how-it-work">
	<h1>Comment ça marche ?</h1>
	<div class="clearfix"></div>
	<div class="col_4 visible">
		<h2>Accédez aux objets en toute simplicité</h2>
		<div class="gray">
			Empruntez n'importe quel objet à un tippeur contre un pourboire. Ou faites profiter de vos affaires
			endormies.
			<br /><br />
			Avec Tipkin venez prendre plaisir à mutualiser, rencontrer, expérimenter.
		</div>
		<div>
			Inscrivez-vous en tant que membre
			<br />
			<img alt="" src="{Profile::AVATAR_DEFAULT_FEMALE}" width="80"/>
			<img alt="" src="{Profile::AVATAR_DEFAULT_MALE}" width="80"/>
		</div>
		<div>
			Recherchez l'objet près de chez vous
			<br />
			<img alt="" src="/images/objects.png" width="110"/>
			<img alt="" src="/images/localize.png"/>
		</div>
		<div>
			<img alt="" src="/images/charte/enveloppe.png" class="align-left"/>
			Faites votre demande de réservation
		</div>
		<div class="clearfix"></div>
		<div>
			Rencontrez et partagez l'objet
			<br />
			<img alt="" src="{Profile::AVATAR_DEFAULT_FEMALE}" width="80"/>
			+<img alt="" src="/images/pod.png" width="25"/>+
			<img alt="" src="{Profile::AVATAR_DEFAULT_MALE}" width="80"/>
		</div>
		<div>
			<img alt="" src="/images/charte/count-feedback-large.png" class="align-left"/>
			<br />
			Commentez votre emprunt
		</div>
		<div class="clearfix"></div>
		<div>
			Agrandissez votre tipkin-ship
			<br />
			<img alt="" src="/images/family.png"/>
			<img alt="" src="/images/friends.png"/>
			<img alt="" src="/images/neighbors.png"/>
			<img alt="" src="/images/tippeurs.png"/>
		</div>
	</div>
	<div class="col_4 visible">
		<h2>Pourquoi emprunter sur Tipkin ?</h2>
		<div>
			<img alt="" src="/images/objects.png" width="110" class="align-left"/>
			<div>
				Vous trouvez tout ce dont vous avez besoin ponctuellement ou pour une courte durée.
			</div>
		</div>
		<div class="clearfix"></div>
		<div>
			<img alt="" src="/images/localize.png" width="100" class="align-right"/>
			L'objet se trouve à proximité de chez vous
		</div>
		<div class="clearfix"></div>
		<div>
			<img alt="" src="/images/charte/astuce.png" class="align-left"/>
			Des astuces vous permettent d'en savoir plus sur l'objet et de faire un vrai partage
		</div>
		<div class="clearfix"></div>
		<div>
			<div class="feedbacks no-margin">
				<div class="count-items no-margin">
					<div class="number no-margin">2</div>
					<div style="float: left;" class="no-margin star">
						<img alt="" src="/images/star-on.png"/>
						<img alt="" src="/images/star-off.png"/>
						<img alt="" src="/images/star-off.png"/>
						<img alt="" src="/images/star-off.png"/>
						<img alt="" src="/images/star-off.png"/>
					</div>
				</div>
			</div>
			<div class="clearfix no-margin"></div>
			Vous pouvez consulter les notes et les commentaires donnés par les autres Tippeurs
		</div>
		<div>
			<img alt="" src="/images/charte/enveloppe.png" class="align-left"/>
			La confidentialité. Vos coordonnées ne sont données que si vous le décidez
		</div>
		<div class="clearfix"></div>
		<div>
			<img alt="" src="/images/family.png"/>
			<img alt="" src="/images/friends.png"/>
			<img alt="" src="/images/neighbors.png"/>
			<img alt="" src="/images/tippeurs.png"/>
			<br />
			Votre communauté est présente et vous pouvez agrandir votre réseau
		</div>
		<div>
			<img alt="" src="/images/tree.png" class="align-left"/>
			<img alt="" src="/images/porte-monnaie.png" width="50" class="align-left"/>
			<br />
			Créez du lien,  protégez la planète et faites des économies en participant à la consommation collaborative
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="col_4 visible">
		<h2>Pourquoi prêter contre un pourboire sur Tipkin ?</h2>
		<div>
			<div style="text-align: center;" class="no-margin">
				<img alt="" src="/images/calendar.png" width="200"/>
			</div>
			<br />
			Un calendrier est mis à votre disposition pour gérer vos disponibilités sans contrainte
		</div>
		<div>
			<img alt="" src="/images/family.png"/>
			<img alt="" src="/images/friends.png"/>
			<img alt="" src="/images/neighbors.png"/>
			<img alt="" src="/images/tippeurs.png"/>
			<br />
			Votre communauté a un tarif appliqué selon votre souhait et vos différents liens
		</div>
		<div>
			<div class="feedbacks no-margin">
				<div class="count-items no-margin">
					<div class="number no-margin">2</div>
					<div style="float: left;" class="no-margin star">
						<img alt="" src="/images/star-on.png"/>
						<img alt="" src="/images/star-off.png"/>
						<img alt="" src="/images/star-off.png"/>
						<img alt="" src="/images/star-off.png"/>
						<img alt="" src="/images/star-off.png"/>
					</div>
				</div>
			</div>
			<div class="clearfix no-margin"></div>
			Vous pouvez consulter les notes et les commentaires donnés par les autres Tippeurs
		</div>
		<div>
			<img alt="" src="/images/charte/enveloppe.png" class="align-left"/>
			La confidentialité. Vos coordonnées ne sont données que si vous le décidez
		</div>
		<div class="clearfix"></div>
		<div>
			<img alt="" src="/images/money.png" class="align-left"/>
			<br />
			Augmentez vos pourboires et agrandissez votre communauté
		</div>
		<div class="clearfix"></div>
		<div>
			<img alt="" src="/images/tree.png" class="align-left"/>
			<img alt="" src="/images/porte-monnaie.png" width="50" class="align-left"/>
			<br />
			Créez du lien, protégez la planète et faites des économies en participant à la consommation collaborative
		</div>
		<div class="clearfix"></div>
	</div>
</div>
{/block}
{extends file="layout.tpl"}

{block name=page_title prepend}Mention légales{/block}

{block name=page_content}
<div class="col_12" style="text-align: justify;">
	<h1>Informations Légales</h1>
	{config name="company-information"}
	<br />
	{config name="company-identification-number"}
	<br />
	Hébergement : {config name="hosting-platform"}
	<br />

	<h4>Données personnelles</h4>
	Ce site est déclaré auprès de la Commission Nationale Informatique et Liberté sous le numéro : {config name="cnil-number"}.
	Les informations collectées nécessaires à votre enregistrement ne sont pas conservées sans votre consentement.
	Les données de connexion sont effacées 1 an après votre dernière connexion. Certaines données informatiques sont collectées à des fins statistiques et ne permettent pas de vous identifier.
	Ces données sont supprimées dès que le traitement des statistiques est terminé.<br />
	Vous disposez d'un droit d'accès, de modification et de suppression des données personnelles vous concernant. Pour prendre connaissance de ces données ou pour exercer vos droits, contactez nous par email ou par courrier à l'adresse suivante : contact@tipkin.fr.
  <br />
	<h4>Propriété Intellectuelle</h4>
	Le contenu de ce site est protégé par le droit d'auteur. Toute reproduction sans le consentement explicite de l'auteur est strictement interdite.
  <br />
	<h4>Site soutenu par le dispositif Eccone</h4>
	Le dispositif <a href="http://www.eccone.fr">Eccone</a> accompagne la communauté contributrice au développement de Tipkin.
	Cet accompagnement a pour objectif d'ouvrir Tipkin pour permettre sa reproduction et enrichissement selon un cadre précis inspiré du
	<a href="http://unisson.co/fr/communs/">modèle des Communs libres</a>.<br />
	L'association Sol&amp;TIC est partenaire du dispositif et soutient Tipkin en lui fournissant un service de portage administratif nécessaire à son fonctionnement légal.
</div>
{/block}

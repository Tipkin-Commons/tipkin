{extends file="layout.tpl"}

{block name=page_title}Paiement{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	
});
//-->
</script>
{/literal}

{assign var="date" value=($reservation->getCreatedTime()|date_format:'%d/%m/%Y:%H:%M:%S')}
{assign var="freeText" value="Paiement"}
{assign var="stringToHash" value="6597294*{$date}*{$amount}EUR*{$reservation->id()}*{$freeText}*3.0*FR*sarltipkin*{$currentUser->getMail()}**********"}
{assign var="hashKey" value="FB8733209502DC9DD0FF67A034FDD24FA08C259D"}
{assign var="mac" value=hash_hmac('sha1',$stringToHash, $reservation->getUsableKey($hashKey))}

<div class="col_2"></div>
<div class="col_8">
	<h1>Paiement de l'acompte de réservation</h1>
	<fieldset>
		Pour finaliser votre demande de mise en relation, nous vous invitons à régler votre acompte de réservation d'un montant de : <strong>{$amount}EUR</strong>.
		<br /><br />  
		Merci de vous munir du moyen de paiement accepté par notre plateforme (CB, VISA, MASTERCARD),
		puis cliquez sur le bouton suivant : 
		<br /><br />
		<form method="post" name="CMCICFormulaire" style="padding: 0px;" target="_top" action="https://ssl.paiement.cic-banques.fr/paiement.cgi">
			<input type="hidden" name="version" value="3.0">
			<input type="hidden" name="TPE" value="6597294">
			<input type="hidden" name="date" value="{$date}">
			<input type="hidden" name="montant" value="{$amount}EUR">
			<input type="hidden" name="reference" value="{$reservation->id()}">
			<input type="hidden" name="MAC" value="{$mac}">
			<input type="hidden" name="url_retour"
			value="http://beta.tipkin.fr/back">
			<input type="hidden" name="url_retour_ok"
			value="http://beta.tipkin.fr/paiement/ok">
			<input type="hidden" name="url_retour_err"
			value="http://beta.tipkin.fr/paiement/error">
			<input type="hidden" name="lgue" value="FR">
			<input type="hidden" name="societe" value="sarltipkin">
			<input type="hidden" name="texte-libre" value="{$freeText}">
			<input type="hidden" name="mail" value="{$currentUser->getMail()}">
			<input type="submit" name="bouton" id="paiement-button" value="Procéder au paiement sécurisé de l'acompte">
		</form>
	</fieldset>
</div>
{/block}

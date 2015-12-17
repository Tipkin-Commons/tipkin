{extends file="layout.tpl"}

{block name=page_title}Signaler une annonce{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#form-moderate').validate();
});
//-->
</script>
{/literal}
<div class="col_12">
	<h4>Signaler une annonce</h4>
	<div style="border: solid 1px; overflow: hidden;">
		<div class="col_4 visible" id="announce-{$announce->id()}">
			{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{$announce->getPhotoMain()}"}
			{assign var="userPhoto" value="{$profilesManager->getByUserId($announce->getUserId())->getAvatar()}"}
			<div class="center">
				<img alt="image de l'annonce" style="width: 100%; margin-bottom:10px;" src="{$mainPhoto}"/>
			</div>
			<div class="clearfix"></div>
			<div style="width: 25%; height: 100%; float: left; margin-bottom:10px;" class="center">
				<img alt="image de profil" style="width: 100%" src="{$userPhoto}"/>
			</div>
			<div style="width: 73%; float: right; margin-left: 2%">
				<label>{$announce->getTitle()}</label>
				<br />
				<label><span class="icon small red" data-icon="&"></span><span>{$announce->getCity()} {$announce->getDepartmentId()}</span></label>
				<label>				
					{$announce->getPricePublic()} € 
					<span>/j</span>
				</label>
			</div>
		</div>
		<div class="col_8">
			{if isset($messageSent)}
				{$message}
				<div class="clearfix"></div>
				<div class="right" style="margin-top: 20px;">
					<a class="btn red" href="/view/member/announce-{$announce->getLink($announce->id())}">Retour</a>
				</div>
			{else}
				<form method="post" id="form-moderate">
					<div>
						En signalant cette annonce, un message sera envoyé à l'équipe d'administration pour modération.
						<br /><br />
					</div>
					<label for="message">Pour quelle raison souhaitez-vous signaler cette annonce :</label>
					<textarea id="message" name="message" class="required" minlength="25"
						placeholder="Ecrivez votre message ici" 
						style="height: 120px"></textarea>
					
					<input type="hidden" name="user-id" value="{$currentUser->id()}">
					<input type="hidden" name="announce-id" value="{$announce->id()}">
					<div class="right">
						<button name="submit-form">Envoyer</button>
						<a class="btn red" href="/view/member/announce-{$announce->getLink($announce->id())}">Annuler</a>
					</div>
				</form>
			{/if}
		</div>
	</div>
</div>
{/block}
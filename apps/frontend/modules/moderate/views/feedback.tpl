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
	<h4>Signaler un feedback</h4>
	<div style="border: solid 1px; overflow: hidden;">
		<div class="feedbacks col_12">
			<div class="item">
				<div class="user">
					<div class="avatar">
						<img alt="image de profil" src="{$profilesManager->getByUserId($feedback->getUserAuthorId())->getAvatar()}" width="75"/>
					</div>
					<div class="username">
						{assign var="username" value=$usersManager->get($feedback->getUserAuthorId())->getUsername()}
						{$username}
					</div>
				</div>
				<div class="feedback-item">
					<div class="mark">
						{assign var="mark" value=$feedback->getMark()}
						{while $mark > 0}
							<img alt="" src="/images/star-on.png"/>
							{assign var="mark" value=$mark-1}	
						{/while}
						{assign var="unmark" value=5-$feedback->getMark()}
						{while $unmark > 0}
							<img alt="" src="/images/star-off.png"/>
							{assign var="unmark" value=$unmark-1}
						{/while}
						<div class="creation-date">
							{date_format(date_create($feedback->getCreationDate()),'d/m/Y')}
						</div>
					</div>
					<div class="comment">
						{$feedback->getComment()} 
					</div>
				</div>
			</div>
		</div>
		<div class="col_12">
			{if isset($messageSent)}
				{$message}
				<div class="clearfix"></div>
				<div class="right" style="margin-top: 20px;">
					<a class="btn red" href="/users/member/{$feedback->getUserOwnerId()}">Retour</a>
				</div>
			{else}
				<form method="post" id="form-moderate">
					<div>
						En signalant ce feedback, un message sera envoyé à l'équipe d'administration pour modération.
						<br /><br />
					</div>
					<label for="message">Pour quelle raison souhaitez-vous signaler ce feedback :</label>
					<textarea id="message" name="message" class="required" minlength="25"
						placeholder="Ecrivez votre message ici" 
						style="height: 120px"></textarea>
					
					<input type="hidden" name="user-id" value="{$currentUser->id()}">
					<input type="hidden" name="feedback-id" value="{$feedback->id()}">
					<div class="right">
						<button name="submit-form">Envoyer</button>
						<a class="btn red" href="/users/member/{$feedback->getUserOwnerId()}">Annuler</a>
					</div>
				</form>
			{/if}
		</div>
	</div>
</div>
{/block}
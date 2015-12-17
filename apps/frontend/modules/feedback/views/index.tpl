{extends file="layout.tpl"}

{block name=page_title}Feedback en attente(s){/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('.tab-content').css('minHeight','400px');

	$('#count-feedback-owner').html($('#owner div.feedback').length);
	$('#count-feedback-subscriber').html($('#subscriber div.feedback').length);
});

//-->
</script>
{/literal}
<div class="col_9">
	<div class="col_12">
	<h4>Feedback en attente(s)</h4>
		{$message}
		<div id="tabs">
			<ul class="tabs">
				<li>
					<a href="#owner">Prêteur (<span id="count-feedback-owner"></span>)</a>
				</li>
				<li>
					<a href="#subscriber">Emprunteur (<span id="count-feedback-subscriber"></span>)</a>
				</li>
			</ul>
			<div class="tab-content" id="owner">
				{assign var="countOwnerFeedback" value=0}
				{foreach from=$listOfFeedbackRequests item=feedbackRequest}
					{if $feedbackRequest->getUserOwnerId() == $currentUser->id()}
						{assign var="countOwnerFeedback" value=$countOwnerFeedback+1}
						{assign var="profile" value=$profilesManager->getByUserId($feedbackRequest->getUserSubscriberId())}
						{assign var="announce" value=$announcementsManager->get($feedbackRequest->getAnnounceId())}
						{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
						<div class="feedback">
							<div class="col_4 visible">
								<h5>Annonce</h5>
								<div class="photo-user-search">
									<div title="Voir cette annonce" class="user-photo">
										<a href="/view/member/announce-{$announce->getLink($announce->id())}">
											<img alt="image de l'annonce" src="{$mainPhoto}"/>
										</a>
									</div>
								</div>
								<div class="nom-produit-carousel">
									<a href="/view/member/announce-{$announce->getLink($announce->id())}">
										{$announce->getTitle()}
									</a>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="col_4 visible">
								<h5>Emprunteur</h5>
								<div class="photo-user-search">
									<div title="Voir son profil" class="user-photo">
										<a href="/users/member/{$profile->getUserId()}">
											<img alt="image de profil" src="{$profile->getAvatar()}" class="align-left"/>
										</a>
									</div>
								</div>
								<a href="/users/member/{$profile->getUserId()}">
									{$profile->getFirstName()} {$profile->getLastName()}
								</a>
								<div class="clearfix"></div>
							</div>
							<div class="col_4 right">
								<a class="lightbox" href="/feedback/{$feedbackRequest->id()}">Laisser un feedback</a>
								<div>
									Demande créé le :
									<br />
									{date_format(date_create($feedbackRequest->getCreationDate()), 'd/m/Y')}
								</div>
							</div>
						</div>
					{/if}
				{/foreach}
				{if $countOwnerFeedback == 0}
					Aucun feedback en attente
				{/if}
			</div>
			<div class="tab-content" id="subscriber">
				{assign var="countSubscriberFeedback" value=0}
				{foreach from=$listOfFeedbackRequests item=feedbackRequest}
					{if $feedbackRequest->getUserSubscriberId() == $currentUser->id()}
						{assign var="countSubscriberFeedback" value=$countSubscriberFeedback+1}
						{assign var="profile" value=$profilesManager->getByUserId($feedbackRequest->getUserOwnerId())}
						{assign var="announce" value=$announcementsManager->get($feedbackRequest->getAnnounceId())}
						{assign var="mainPhoto" value="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"}
						<div class="feedback">
							<div class="col_4 visible">
								<h5>Annonce</h5>
								<div class="photo-user-search">
									<div title="Voir cette annonce" class="user-photo">
										<a href="/view/member/announce-{$announce->getLink($announce->id())}">
											<img alt="image de l'annonce" src="{$mainPhoto}"/>
										</a>
									</div>
								</div>
								<div class="nom-produit-carousel">
									<a href="/view/member/announce-{$announce->getLink($announce->id())}">
										{$announce->getTitle()}
									</a>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="col_4 visible">
								<h5>Prêteur</h5>
								<div class="photo-user-search">
									<div title="Voir son profil" class="user-photo">
										<a href="/users/member/{$profile->getUserId()}">
											<img alt="image de profil" src="{$profile->getAvatar()}" class="align-left"/>
										</a>
									</div>
								</div>
								<a href="/users/member/{$profile->getUserId()}">
									{$profile->getFirstName()} {$profile->getLastName()}
								</a>
								<div class="clearfix"></div>
							</div>
							<div class="col_4 right">
								<a class="lightbox" href="/feedback/{$feedbackRequest->id()}">Laisser un feedback</a>
								<div>
									Demande créé le :
									<br />
									{date_format(date_create($feedbackRequest->getCreationDate()), 'd/m/Y')}
								</div>
							</div>
						</div>
					{/if}
				{/foreach}
				{if $countSubscriberFeedback == 0}
					Aucun feedback en attente
				{/if}
			</div>
		</div>		
	</div>
</div>
<div class="col_3" style="margin-top: 26px;">
	{include file='_menuActionRight.tpl'}
</div>
{/block}
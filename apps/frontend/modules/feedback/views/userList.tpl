{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#owner-link').click();
});
//-->
</script>
{/literal}
<div style="width: 800px">
	<h4>Feedback de cet utilisateur</h4>
	<div class="col_12">
		<ul class="tabs">
			<li>
				<a id="owner-link" href="#owner">Feedbacks Prêteur</a>
			</li>
			<li>
				<a href="#subscriber">Feedbacks Emprunteur</a>
			</li>
		</ul>
		<div id="owner" class="feedbacks tab-content">
			{assign var="countFeedbacks" value=0}
			{foreach from=$listOfFeedbacks item=feedback}
				{if $feedback->getUserSubscriberId() == $feedback->getUserAuthorId()}
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
						<div class="right col_12">
							<a href="/view/member/announce-{$feedback->getAnnounceId()}">Voir l'annonce</a>
							{if $isAuthenticate == 'true' && 
								($currentUser->id() == $feedback->getUserOwnerId() || $currentUser->id() == $feedback->getUserSubscriberId()) &&
								$currentUser->id() != $feedback->getUserAuthorId()}
								<a href="/moderate/feedback/{$feedback->id()}">Signaler ce feedback</a>
							{/if}
						</div>
					</div>
					{assign var="countFeedbacks" value=$countFeedbacks+1}
				{/if}
			{/foreach}
			{if $countFeedbacks == 0}
				<div>
					Aucun feedback disponible
				</div>
			{/if}
		</div>
		<div id="subscriber" class="feedbacks tab-content" style="display: none;">
			{assign var="countFeedbacks" value=0}
			{foreach from=$listOfFeedbacks item=feedback}
				{if $feedback->getUserOwnerId() == $feedback->getUserAuthorId()}
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
						<div class="right col_12">
							<a href="/view/member/announce-{$feedback->getAnnounceId()}">Voir l'annonce</a>
						</div>
					</div>
					{assign var="countFeedbacks" value=$countFeedbacks+1}
				{/if}	
			{/foreach}
			{if $countFeedbacks == 0}
				<div>
					Aucun feedback disponible
				</div>
			{/if}
		</div>
	</div>
</div>
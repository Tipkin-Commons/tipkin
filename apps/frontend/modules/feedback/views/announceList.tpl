<div style="width: 800px">
	<h4>Feedback de cette annonce</h4>
	<div id="announce" class="feedbacks tab-content">
		{assign var="countFeedbacks" value=0}
		{foreach from=$listOfFeedbacks item=feedback}
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
				{if $isAuthenticate == 'true' && 
					($currentUser->id() == $feedback->getUserOwnerId() || $currentUser->id() == $feedback->getUserSubscriberId()) &&
					$currentUser->id() != $feedback->getUserAuthorId()}
					<div class="right col_12">
						<a href="/moderate/feedback/{$feedback->id()}">Signaler ce feedback</a>
					</div>
				{/if}
			</div>
			{assign var="countFeedbacks" value=$countFeedbacks+1}
		{/foreach}
		{if $countFeedbacks == 0}
			<div>
				Aucun feedback disponible
			</div>
		{/if}
	</div>
</div>
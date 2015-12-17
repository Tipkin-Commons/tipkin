{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#close').click(function(){
		$('#fancybox-close').click();
	});
	$('#confirm-button').click(function(){
		
	});
});
//-->
</script>
{/literal}
<div style="width: 800px">
	<h4>Feedback de {$usersManager->get($feedback->getUserOwnerId())->getUsername()}</h4>
	<div class="feedbacks tab-content">
		<div class="item">
			<div class="user">
				<div class="avatar">
					<img src="{$profilesManager->getByUserId($feedback->getUserAuthorId())->getAvatar()}" width="75"/>
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
						<img src="/images/star-on.png"/>
						{assign var="mark" value=$mark-1}	
					{/while}
					{assign var="unmark" value=5-$feedback->getMark()}
					{while $unmark > 0}
						<img src="/images/star-off.png"/>
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
	<div style="float: right;">
		<form method="post" action="/admin/feedback/delete/{$feedback->id()}" style="display: inline;">
			<button class="green" id="confirm-button" name="submit-form">Supprimer</button>
		</form>
		<a id="confirm-command" href=""></a>
		<button class="red" id="close">Annuler</button>
	</div>		
</div>
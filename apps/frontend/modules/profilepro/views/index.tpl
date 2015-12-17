{extends file="layout.tpl"}

{block name=page_title}{$currentUser->getUsername()}{/block}

{block name=page_content}

{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#edit-profile').click(function(){
		location.href = "/profile-pro/edit";
	});
});
//-->
</script>
{/literal}
<div class="col_9">
	<div style="width: 26%; float: left;">
		<div class="col_12 visible center">
				{include file='_photoWidget.tpl'}
		</div>
		<div class="col_12">
			<ul class="menu vertical">
				<li>
					<a style="width: auto" href="/profile-pro/update-mail" class="lightbox">
						<span class="icon small" data-icon="@"></span>
						Email
					</a>
				</li>
				<li class="divider">
					<a href="/profile-pro/update-password" class="lightbox">
						<span class="icon small" data-icon="O"></span>
						Mot de passe
					</a>
				</li>
			</ul>
		</div>
		<div class="col_12">
			<a href="/profile-pro/delete" class="lightbox">Désinscription</a>
		</div>
	</div>
	<div style="width: 71%; float: right; margin-left: 3%">
		{$message}
		{if $isMailVerified != '1'}
		<div class="col_12">
			Votre email n'a pas été vérifié par nos services.
			<br />
			<a href="/profile-pro/valid-email">Cliquez ici pour valider votre email maintenant</a>
		</div>
		{/if}
		<div class="col_12">
			<button class="small" id="edit-profile" style="float: right;">
				<span class="icon small" data-icon="7"></span>
				Modifier
			</button>
			{include file='_indexContent.tpl'}
		</div>
	</div>	
</div>
<div class="col_3" style="margin-top: 26px;">
	{include file='_menuActionRightPro.tpl'}
</div>
{/block}
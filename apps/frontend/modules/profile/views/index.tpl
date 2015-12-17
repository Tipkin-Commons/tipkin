{extends file="layout.tpl"}

{block name=page_title}{$currentUser->getUsername()}{/block}

{block name=page_content}

{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#edit-profile').click(function(){
		location.href =  "/profile/edit";
	});
});
//-->
</script>
{/literal}
<div class="col_9">
	<div style="width: 26%; float: left;">
		{if $profilExist == 'true'}
		<div class="col_12 visible center">
				{include file='_photoWidget.tpl'}
		</div>
		{/if}
		<div class="col_12">
			<ul class="menu vertical">
				<li>
					<a style="width: auto" href="/profile/update-mail" class="lightbox">
						<span class="icon small" data-icon="@"></span>
						Email
					</a>
				</li>
				<li>
					<a style="width: auto" href="/profile/update-mailing" class="lightbox">
						<span class="icon small" data-icon="@"></span>
						Newsletter
					</a>
				</li>
				<li class="divider">
					<a href="/profile/update-password" class="lightbox">
						<span class="icon small" data-icon="O"></span>
						Mot de passe
					</a>
				</li>
				<li>
					<a href="/profile/delete" class="lightbox">
						<span class="icon small" data-icon="m"></span>
						Désinscription
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div style="width: 71%; float: right; margin-left: 3%;">
		{$message}
		<div class="col_12">
			{if $profilExist == 'true'}
			<button class="small" id="edit-profile" style="float: right;">
				<span class="icon small" data-icon="7"></span>
				Modifier
			</button>
			{include file='_indexContent.tpl'}
			{else}
				Vous devez compléter votre profil pour accéder à toutes les fonctionnalités de TIPKIN.
				<br />
				<a href="/profile/create">Cliquez ici pour le compléter maintenant</a>
				
			{/if}
		</div>
	</div>	
</div>
{if $profilExist == 'true'}
<div class="col_3" style="margin-top: 26px;">
	{include file='_menuActionRight.tpl'}
</div>
{/if}
{/block}
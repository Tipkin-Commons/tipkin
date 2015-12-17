{extends file="layout.tpl"}

{block name=page_title}{$currentUser->getUsername()} - Modifier profil{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#back-to-profile').click(function(){
		location.href = "/profile";
	});

	var $radios = $('input:radio[name=gender]');
    if($radios.is(':checked') === false) {
        $radios.filter('[value={/literal}{$profile->getGender()}{literal}]').attr('checked', true);
    }
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
					<a style="width: auto" href="/profile/update-mail" class="lightbox">
						<span class="icon small" data-icon="@"></span>
						Email
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
	<div style="width: 71%; float: right; margin-left: 3%">
		{$message}
		<div class="col_12 visible">
			<div style="float: right;">
				<button class="small" id="back-to-profile">
					<span class="icon small" data-icon=":"></span>
					Annuler
				</button>
			</div>
			{include file='_form.tpl'}
		</div>
	</div>
</div>
<div class="col_3" style="margin-top: 26px;">
{include file='_menuActionRight.tpl'}
</div>
{/block}
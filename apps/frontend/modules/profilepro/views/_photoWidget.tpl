{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#avatar').click(function(){
		$('#avatar-popup').click();
	});
});

//-->
</script>
{/literal}
<div style="margin: auto;">
	<button class="small" id="avatar">
		<span class="icon small" data-icon="1"></span>
		Changer ma photo
	</button>
	<br /><br />
	<a id="avatar-popup" class="lightbox" href="/profile-pro/photo"></a>
	<img alt="image de profil" src="{$profilePro->getAvatar()}" class="avatar" />
	<br /><br />
	<a href="/users/pro/{$profilePro->getUserId()}">Voir mon profil public</a>
</div>
<div id="fb-root"></div>
<script src="http://connect.facebook.net/fr_FR/all.js" ></script>
<script src="/js/facebook.js" ></script>
{literal}
<script type="text/javascript">
<!--

	
//-->
</script>
{/literal}
<div id="fb-login-button">
	<fb:login-button perms="email" id="fb-button" />
</div>
<form method="post" action="/facebook-connect" id="facebook-connect-form">
	<input type="hidden" id="usernamefb" name="usernamefb"/>
	<input type="hidden" id="mailfb" name="mailfb"/>
</form>
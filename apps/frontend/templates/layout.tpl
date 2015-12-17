<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>
			{block name=page_title} | TIPKIN{/block}
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="Content-Language" content="fr,en"/>
		<meta name="description" content="{block name=meta_desc}{/block}" />
		<link href="/images/charte/favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
		<script type="text/javascript" src="/lib/jquery-ui/js/jquery-1.7.1.min.js"></script>
		<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<script type="text/javascript" src="/lib/kick-start/js/prettify.js"></script>                                   <!-- PRETTIFY -->
		<script type="text/javascript" src="/lib/kick-start/js/kickstart.js"></script>                                  <!-- KICKSTART -->
		<script type="text/javascript" src="/js/jquery.validate.js"></script>
		<script type="text/javascript" src="/js/dateFormat.js"></script>
		<link rel="stylesheet" type="text/css" href="/lib/kick-start/css/kickstart.css" media="all" />                  <!-- KICKSTART -->
		<link rel="stylesheet" type="text/css" href="/lib/kick-start/style.css" media="all" />                          <!-- CUSTOM STYLES -->
		<link type="text/css" href="/lib/jquery-ui/css/smoothness/jquery-ui-1.8.18.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="/lib/jquery-ui/js/jquery-ui-1.8.18.custom.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/js/jcarousel-skins/tango/skin.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/css/frontend.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/css/app.css" media="all" />
		<link rel="search" type="application/opensearchdescription+xml" title="Tipkin" href="http://beta.tipkin.fr/opensearch.xml" />
	</head>
	<body>

	<a id="top-of-page"></a>
	<div id="wrap" class="clearfix">
		<div>
			<ul id="top-menu" class="menu">
				<li>
				<a href="/" class="tooltip-bottom" title="Accueil" id="lienhome"></a>
				</li>
				{if $isAuthenticate == 'false'}
				<li id="baselineco">
				{else}
				<li id="baselinedeco">
				{/if}
					Je possède. Tu empruntes. Nous partageons !
				</li>
				<li>
					{if $isAuthenticate == 'false'}
					<a href="/login">
						Entrer un objet
					</a>
					{else}
					<a href="/announcements/new">
						Entrer un objet
					</a>
					{/if}
				</li>
				<li>
					<a href="/search/page=/region=/department=/category=/subcategory=/zipcode=/filter=">Rechercher un objet</a>
				</li>
				{if $isAuthenticate == 'false'}
				<li>
					<a href="/login">Connexion</a>
				</li>
				<li>
					<a href="/registration">Inscrivez-vous</a>
				</li>
				{else}
				<li>
					<a href="/profile" class="tooltip-bottom" title="{$currentUser->getUsername()}">Mon profil</a>
				</li>
				<li>
					<a href="/disconnect" id="disconnect" class="tooltip-bottom" title="Déconnexion"><span class="icon small" data-icon="l"></span></a>
				</li>
				{/if}
			</ul>
		</div>
		<div>
			<noscript>
				<div class="col_12 visible" style="color: red; text-align: center;">
					<h5>
						Le support du javascript est désactivé !
						<br />
						Veuillez l'activer pour	profiter pleinement de toutes les fonctionnalités proposées par TIPKIN.
					</h5>
					Visitez la page suivante pour en savoir plus :
					<br />
					<a target="_blank" href="http://www.enable-javascript.com/fr/">http://www.enable-javascript.com/fr/</a>
					<br /><br />
				</div>
			</noscript>
			<div id="content"  style="min-height: 400px;">
			{block name=page_content}Contenu par défaut{/block}
			</div>
			<!-- <div align=center><iframe width="560" height="315" src="http://www.youtube.com/embed/-wlXTXtoqPI" frameborder="0" allowfullscreen></iframe></div> -->
			<div id="bottom">
				<hr />
				<div class="col_2">
					<h5>Tipkin</h5>
					<ul>
						<li><a href="/">Accueil</a></li>
						<li><a href="/contact">Contact</a></li>
						<li><a href="/faq">FAQ</a></li>
						<li><a href="/legals">Mentions légales</a></li>
						<li><a href="/files/CGU-Tipkin.pdf" target="_blank">CGU</a></li>
					</ul>
				</div>
				<div class="col_2">
					<h5>Communauté</h5>
					<ul>
						<li><a href="/how-it-work">Comment ça marche</a></li>
						<li><a rel="nofollow" href="http://blog.tipkin.fr">Blog</a></li>
						<li><a rel="nofollow" href="http://www.facebook.com/pages/Tipkin/279559575444209">Facebook</a></li>
						<li><a rel="nofollow" href="http://twitter.com/tipkinfr">Twitter</a></li>
						<li><a href="/invite">Inviter des amis</a></li>
					</ul>
				</div>
				<div class="col_2">
					<h5>Nos Actus</h5>
					<ul>
						<li><a href="/opinion">Témoignages</a></li>
						<li><a href="/files/www.20minutes.fr-louer-en-un-clic.pdf" target="_blank">Presse</a></li>
					</ul>
				</div>
				<div class="right col_4" style="float: right;">
				 Partager Tipkin
				 <a rel="nofollow" href="http://www.facebook.com/pages/Tipkin/279559575444209" target="_blank"><span class="icon social large grey" title="Facebook" data-icon="F"></span></a>
				 <a rel="nofollow" href="http://twitter.com/tipkinfr" target="_blank"><span class="icon social large grey" title="Twitter" data-icon="T"></span></a>
				 <a href="https://plus.google.com/118240090168216739498" rel="publisher" target="_blank"><span class="icon social large grey" title="Google+" data-icon="G"></span></a>
				</div>
			</div>
		</div>
		<br />
		<div class="clear"></div>
		<div id="footer">
			<a id="link-top" href="#top-of-page">Top</a>
		</div>
		<div id="bigniou">
		</div>
	</div>
	{literal}
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-25542418-4']);
	  _gaq.push(['_setDomainName', 'tipkin.fr']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
	{/literal}
	</body>
</html>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="Content-Language" content="fr,en"/>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
		<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<script type="text/javascript" src="/lib/kick-start/js/prettify.js"></script>                                   <!-- PRETTIFY -->
		<script type="text/javascript" src="/lib/kick-start/js/kickstart.js"></script>                                  <!-- KICKSTART -->
		<script type="text/javascript" src="/js/jquery.validate.js"></script>
		<link rel="stylesheet" type="text/css" href="/lib/kick-start/css/kickstart.css" media="all" />                  <!-- KICKSTART -->
		<link rel="stylesheet" type="text/css" href="/lib/kick-start/style.css" media="all" />                          <!-- CUSTOM STYLES -->
		<link rel="stylesheet" type="text/css" href="/css/backend.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/css/app.css" media="all" />
		<title>
			{block name=page_title}TIPKIN Administration{/block}
		</title>
	</head>
	<body>
	<a id="top-of-page"></a>
	<div id="wrap" class="clearfix">
		{literal}
		<script type="text/javascript">
		<!--
		$(function(){
			
		});
		//-->
		</script>
		{/literal}
		<div>
			<ul id="top-menu" class="menu">
				{if $isAdminAuthenticate == 'false'}
				<li>
					<a href="/admin/">TIPKIN <i>Administration</i></a>
				</li>
				{/if}
				{if $isAdminAuthenticate == 'true'}
				<li class="tooltip-bottom" title="Gestion des utilisateurs">
					<a href="/admin/users">
						<span class="icon small" data-icon="u"></span>
						Utilisateur
					</a>
				</li>
				<li class="tooltip-bottom" title="Gestion des annonces">
					<a href="/admin/announcements">
						<span class="icon small" data-icon="v"></span>
						Annonce({$nbAnnouncePendings})
					</a>
				</li>
				<li class="tooltip-bottom" title="Gestion des catégories">
					<a href="/admin/categories">
						<span class="icon small" data-icon="g"></span>
						Catégorie
					</a>
				</li>
				<li class="tooltip-bottom" title="Modération">
					<a href="/admin/moderate">
						<span class="icon small" data-icon="W"></span>
						Modération({$nbModerates})
					</a>
				</li>
				<li class="tooltip-bottom" title="Gestion des témoignages">
					<a href="/admin/opinion">
						<span class="icon small" data-icon="w"></span>
						Témoignage({$nbOpinions})
					</a>
				</li>
				<li class="tooltip-bottom" title="Gestion des historiques de réservation">
					<a href="/admin/history">
						<span class="icon small" data-icon="b"></span>
						Historique
					</a>
				</li>
				<li style="float: right;" class="tooltip-bottom" title="Déconnexion">
					<a href="/admin/disconnect">
						<span class="icon small" data-icon="l"></span>
						&nbsp;
					</a>
				</li>
				{/if}
			</ul>
		</div>
		<div style="min-height: 400px;">
			{block name=page_content}Contenu par défaut{/block}
		</div>
		<div class="clear"></div>
		<div id="footer">
			Réalisé par la société <a href="http://www.midiconcept.fr" target="_blank">MIDICONCEPT</a>
			<a id="link-top" href="#top-of-page">Top</a>
			</div>
	</div>
	</body>
</html>
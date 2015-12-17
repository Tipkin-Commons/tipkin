<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="Content-Language" content="fr,en"/>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
		<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<script type="text/javascript" src="/lib/kick-start/js/prettify.js"></script>                                   <!-- PRETTIFY -->
		<script type="text/javascript" src="/lib/kick-start/js/kickstart.js"></script>                                  <!-- KICKSTART -->
		<link rel="stylesheet" type="text/css" href="/lib/kick-start/css/kickstart.css" media="all" />                  <!-- KICKSTART -->
		<link rel="stylesheet" type="text/css" href="/lib/kick-start/style.css" media="all" />                          <!-- CUSTOM STYLES -->
		<link rel="stylesheet" type="text/css" href="/css/frontend.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/css/app.css" media="all" />
		<title>
			{block name=page_title}TIPKIN{/block}
		</title>
	</head>
	<body>
	<a id="top-of-page"></a>
	<div id="wrap" class="clearfix">
		<div style="min-height: 400px;">
			<div class="col_8">
				<div class="col_12 visible">
					Catégorie :
					{foreach from=$categories item=category}
					{if $category->id() == $announce->getCategoryId()}
						{$category->getName()}
					{/if}
					{/foreach}	
					<h4>{$announce->getTitle()}</h4>
				</div>
				<div class="col_12 visible" >
					<span class="icon small red" data-icon="&"></span>{$announce->getCity()}
					<br />
					<div class="col_9">
						<img alt="image de l'annonce" style="width: 400px" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{$announce->getPhotoMain()}"/>
					</div>
					<div class="col_3">
						<div class="gallery">
							<a href="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{$announce->getPhotoMain()}">
								<img alt="image de l'annonce" class="v-card" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"/>
							</a>
							{if $announce->getPhotoOption1() != null}
							<a href="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{$announce->getPhotoOption1()}">
								<img alt="image de l'annonce" class="v-card" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoOption1()}"/>
							</a>
							{/if}
							{if $announce->getPhotoOption2() != null}
							<a href="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{$announce->getPhotoOption2()}">
								<img alt="image de l'annonce" class="v-card" src="{AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY}{$announce->id()}/{AnnouncementPro::THUMBNAILS_PREFIX}{$announce->getPhotoOption2()}"/>
							</a>
							{/if}
						</div>
					</div>
					<div class="col_12" style="text-align: justify;">
						<h5 style="margin: 0">Raconte-moi ton objet</h5>
						<hr style="margin: 0" />
						{$announce->getDescription()}
						<br /><br />
						{if $announce->getRawMaterial() != ''}
						<span style="font-weight: bold;">Matériel fourni :</span>
						<br />
						{$announce->getRawMaterial()}
						<br /><br />
						{/if}
						{if $announce->getTips() != ''}
						<span style="font-weight: bold;">Astuce :</span>
						<br />
						{$announce->getTips()}
						{/if}
					</div>
				</div>
			</div>
			<div class="col_4">
				<div class="col_12 visible">
					<table>
						<tr>
							<td style="width: 90px">
								<img alt="image de profil" src="{$profile->getAvatar()}" style="width: 80px" alt="" />
							</td>
							<td style="vertical-align: top;">
								{$profile->getLastname()} {$profile->getFirstname()}
								<br />
								<span class="icon small red" data-icon="&"></span>{$mainAddress->getCity()}
							</td>
						</tr>
					</table>
					{$profile->getDescription()}
					<hr />
					Prix Public :
					{$announce->getPricePublic()} €
					<hr />
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	</body>
</html>

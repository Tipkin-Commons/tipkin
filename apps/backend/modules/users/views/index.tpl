{extends file="layout.tpl"}

{block name=page_title}Gestion utilisateurs{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	$(".filterable tr:has(td)").each(function(){
   		var t = $(this).text().toLowerCase(); //all row text
   		$("<td class='indexColumn'></td>")
    	.hide().text(t).appendTo(this);
 	});

	$("#input-filter").keyup(function(){
   		var s = $(this).val().toLowerCase().split(" ");
   		//show all rows.
   		$(".filterable tr:hidden").show();
   		$.each(s, function(){
    		$(".filterable tr:visible .indexColumn:not(:contains('"
        		+ this + "'))").parent().hide();
   		});//each
 	});//key up.

	$('#search').click(function(){
		$('#input-filter').keyup();
	});
});

//-->
</script>
{/literal}
<div class="col_9">
	<h4>Gestion des utilisateurs</h4>
	{$message}
	<div>
		<ul class="tabs left">
			<li>
				<a href="#tab-member">Membres</a>
			</li>
			<li>
				<a href="#tab-member-pro">Membres Pro</a>
			</li>
			{if $admin->getRoleId() == Role::ROLE_SUPER_ADMIN}
			<li>
				<a href="#tab-admin">Administrateurs</a>
			</li>
			{/if}
		</ul>
		<div style="float: right; margin-top:-33px;">
			<input type="text" id="input-filter"  placeholder="Recherche..." style="width: 220px; margin-bottom: 0px"/>
			<button id="search" class="tooltip" title="Lancer la recherche"><span class="icon" data-icon="s"></span></button>
		</div>
		<div id="tab-member" class="tab-content">
			<table class="sortable filterable">
				<thead>
					<tr>
						<th>
							Nom d'utilisateur
						</th>
						<th>
							Email
						</th>
						<th>
							Code Postal
						</th>
						<th>
							Inscription
						</th>
						<th style="text-align: center;">
							Etat
						</th>
						<th style="text-align: center;">
							Actions
						</th>
					</tr>
				</thead>
				<tbody>
					{assign var="countMember" value=0}
					{assign var="countMemberOn" value=0} 
					{foreach from=$users item=user}
					{if $user->getRoleId() == Role::ROLE_MEMBER}
					{assign var="countMember" value=$countMember+1}
					<tr>
						<td>
							{$user->getUsername()} / {$user->id()}
						</td>
						<td>
							{$user->getMail()}
						</td>
						<td>
							{assign var="profile" value=$profilesManager->getByUserId($user->id())}
							{if !is_null($profile)}
								{$addressesManager->get($profile->getMainAddressId())->getZipCode()}
							{/if}
						</td>
						<td>
							{$user->getCreatedTime()}
						</td>
						<td style="text-align: center">
							<ul class="button-bar">
								<li>
									{if $user->getIsActive() == '1'}
									{assign var="countMemberOn" value=$countMemberOn+1}
									<a href="#confirm-action" id="disable/{$user->id()}"
									class="lightbox command tooltip" 
									title="Désactiver le compte de <em>{$user->getUsername()}</em>">
										<span class="icon blue" data-icon="q"></span>
									</a>
									{else}
									<a href="#confirm-action" id="enable/{$user->id()}"
									class="lightbox command tooltip" 
									title="Activer le compte de <em>{$user->getUsername()}</em>">
										<span class="icon red" data-icon="r"></span>
									</a>
									{/if}
								</li>
							</ul>
						</td>
						<td style="text-align: center;">
							<ul class="button-bar">
								{if $admin->getRoleId() == Role::ROLE_SUPER_ADMIN}
								<li>
									<a href="#confirm-action" id="promote/{$user->id()}"
									class="lightbox command tooltip" title="Promouvoir <em>{$user->getUsername()}</em> en tant qu'administrateur">
										<span class="icon blue" data-icon=")"></span>
									</a>
								</li>
								{/if}
<!--								<li>-->
<!--									<a href="#confirm-action" id="delete/{$user->id()}"-->
<!--									class="lightbox command tooltip" title="Supprimer <em>{$user->getUsername()}</em>">-->
<!--										<span class="icon red" data-icon="X"></span>-->
<!--									</a>-->
<!--								</li>-->
							</ul>
						</td>
					</tr>
					{/if}
					{/foreach}
				</tbody>
			</table>
		</div>
		<div id="tab-member-pro" class="tab-content">
			<table class="sortable filterable">
				<thead>
					<tr>
						<th>
							Nom d'utilisateur
						</th>
						<th>
							Email
						</th>
						<th>
							Code Postal
						</th>
						<th>
							Inscription
						</th>
						<th style="text-align: center;">
							Etat
						</th>
						<th style="text-align: center;">
							Actions
						</th>
					</tr>
				</thead>
				<tbody>
					{assign var="countMemberPro" value=0}
					{assign var="countMemberProOn" value=0} 
					{foreach from=$users item=user}
					{if $user->getRoleId() == Role::ROLE_MEMBER_PRO}
					{assign var="countMemberPro" value=$countMemberPro+1}
					<tr>
						<td>
							{$user->getUsername()}
						</td>
						<td>
							{$user->getMail()}
						</td>
						<td>
							{assign var="profile" value=$profilesProManager->getByUserId($user->id())}
							{if !is_null($profile)}
								{$addressesManager->get($profile->getMainAddressId())->getZipCode()}
							{/if}
						</td>
						<td>
							{$user->getCreatedTime()}
						</td>
						<td style="text-align: center">
							<ul class="button-bar">
								<li>
									{if $user->getIsActive() == '1'}
									{assign var="countMemberProOn" value=$countMemberProOn+1}
									<a href="#confirm-action" id="disable/{$user->id()}"
									class="lightbox command tooltip" 
									title="Désactiver le compte de <em>{$user->getUsername()}</em>">
										<span class="icon blue" data-icon="q"></span>
									</a>
									{else}
									<a href="#confirm-action" id="enable/{$user->id()}"
									class="lightbox command tooltip" 
									title="Activer le compte de <em>{$user->getUsername()}</em>">
										<span class="icon red" data-icon="r"></span>
									</a>
									{/if}
								</li>
							</ul>
						</td>
						<td style="text-align: center;">
							<ul class="button-bar">
<!--								<li>-->
<!--									<a href="#confirm-action" id="delete/{$user->id()}"-->
<!--									class="lightbox command tooltip" title="Supprimer <em>{$user->getUsername()}</em>">-->
<!--										<span class="icon red" data-icon="X"></span>-->
<!--									</a>-->
<!--								</li>-->
							</ul>
						</td>
					</tr>
					{/if}
					{/foreach}
				</tbody>
			</table>
		</div>
		{if $admin->getRoleId() == Role::ROLE_SUPER_ADMIN}
		<div id="tab-admin" class="tab-content">
			<table class="sortable filterable">
				<thead>
					<tr>
						<th>
							Nom d'utilisateur
						</th>
						<th>
							Email
						</th>
						<th>
							Code Postal
						</th>
						<th>
							Inscription
						</th>
						<th style="text-align: center;">
							Etat
						</th>
						<th style="text-align: center;">
							Actions
						</th>
					</tr>
				</thead>
				<tbody>
					{assign var="countAdmin" value=0}
					{assign var="countAdminOn" value=0} 
					{foreach from=$users item=user}
					{if $user->getRoleId() == Role::ROLE_ADMINISTRATEUR}
					{assign var="countAdmin" value=$countAdmin+1}
					<tr>
						<td>
							{$user->getUsername()}
						</td>
						<td>
							{$user->getMail()}
						</td>
						<td>
							{assign var="profile" value=$profilesManager->getByUserId($user->id())}
							{if !is_null($profile)}
								{$addressesManager->get($profile->getMainAddressId())->getZipCode()}
							{/if}
						</td>
						<td>
							{$user->getCreatedTime()}
						</td>
						<td style="text-align: center">
							<ul class="button-bar">
								<li>
									{if $user->getIsActive() == '1'}
									{assign var="countAdminOn" value=$countAdminOn+1}
									<a href="#confirm-action" id="disable/{$user->id()}"
									class="lightbox command tooltip" 
									title="Désactiver le compte de <em>{$user->getUsername()}</em>">
										<span class="icon blue" data-icon="q"></span>
									</a>
									{else}
									<a href="#confirm-action" id="enable/{$user->id()}"
									class="lightbox command tooltip" 
									title="Activer le compte de <em>{$user->getUsername()}</em>">
										<span class="icon red" data-icon="r"></span>
									</a>
									{/if}
								</li>
							</ul>
						</td>
						<td style="text-align: center;">
							<ul class="button-bar">
								<li>
									<a href="#confirm-action" id="revoque/{$user->id()}"
									class="lightbox command tooltip" title="Révoquer <em>{$user->getUsername()}</em> en tant que simple membre">
									<span class="icon blue" data-icon="("></span>
									</a>
								</li>
							</ul>
						</td>
					</tr>
					{/if}
					{/foreach}
				</tbody>
			</table>
		</div>
		{/if}
	</div>
</div>
<div class="col_3">
	<ul class="menu vertical right">
		<li>
			<a>
				Ajouter un utilisateur
			</a>
			<ul>
				<li>
					<a class="lightbox" href="/admin/users/add-member">
						Membre
					</a>
				</li>
				<li>
					<a class="lightbox" href="/admin/users/add-member-pro">
						Membre Pro
					</a>
				</li>
				{if $admin->getRoleId() == Role::ROLE_SUPER_ADMIN}
				<li>
					<a class="lightbox" href="/admin/users/add-admin">
						Administrateur
					</a>
				</li>
				{/if}
			</ul>
		</li>
	</ul>
	<div class="col_12 visible" style="margin-top: 45px;">
			<table>
				<thead>
					<tr>
						<th colspan="2" class="center">
							STATISTIQUES
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							Membre(s)
						</td>
						<td class="right">
							{$countMember}
						</td>
					</tr>
					<tr style="font-size: 12px;">
						<td>
							<span class="icon small gray" data-icon="}"></span> Actif(s)
							<br />
							<span class="icon small gray" data-icon="}"></span> Inactif(s)
						</td>
						<td class="right">
							{$countMemberOn}<br />
							{$countMember-$countMemberOn}
						</td>
					</tr>
					<tr>
						<td>
							Membre(s) PRO
						</td>
						<td class="right">
							{$countMemberPro}
						</td>
					</tr>
					<tr style="font-size: 12px;">
						<td>
							<span class="icon small gray" data-icon="}"></span> Actif(s)
							<br />
							<span class="icon small gray" data-icon="}"></span> Inactif(s)
						</td>
						<td class="right">
							{$countMemberProOn}<br />
							{$countMemberPro-$countMemberProOn}
						</td>
					</tr>
					{if $admin->getRoleId() == Role::ROLE_SUPER_ADMIN}
					<tr>
						<td>
							Administrateur(s)
						</td>
						<td class="right">
							{$countAdmin}
						</td>
					</tr>
					<tr style="font-size: 12px;">
						<td>
							<span class="icon small gray" data-icon="}"></span> Actif(s)
							<br />
							<span class="icon small gray" data-icon="}"></span> Inactif(s)
						</td>
						<td class="right">
							{$countAdminOn}<br />
							{$countAdmin-$countAdminOn}
						</td>
					</tr>
					{/if}
					<tr>
						<th>
							TOTAL
						</th>
						<th class="right">
							{if $admin->getRoleId() == Role::ROLE_SUPER_ADMIN}
								{$countMember + $countMemberPro + $countAdmin}
							{else}
								{$countMember + $countMemberPro}
							{/if}
						</th>
					</tr>
				</tbody>
			</table>
		</div>
</div>
<!-- MODAL POPUP -->
<div style="display: none">
	<div id="confirm-action" style="width: 400px;">
		{literal}
		<script type="text/javascript">
			$(function(){
				$('.command').click(function(){
					$('#confirm-command').attr('href', '/admin/users/' + $(this).attr('id'));
					$('#description-command').html('<ul class="alt"><li>' + $('#tiptip_content').html() + '</li></ul>');
				});
				
				$('#close').click(function(){
					$('#fancybox-close').click();
				});
				$('#confirm-button').click(function(){
					location.href = $('#confirm-command').attr('href');
				});
			});
		</script>
		{/literal}
		<div class="col_12 visible" style="font-weight: bolder; font-size: large;">
			Veuillez confirmer votre action :
		</div>
		<div class="col_12">
			<div id="description-command"></div>
			<div style="float: right;">
				<button class="green" id="confirm-button">Oui</button>
				<a id="confirm-command" href=""></a>
				<button class="red" id="close">Non</button>
			</div>
		</div>
	</div>
</div>
{/block}
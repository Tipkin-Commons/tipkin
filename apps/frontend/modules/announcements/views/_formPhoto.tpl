<form method="post" action="" enctype="multipart/form-data" id="form-photo">	
	<label for="photo-main">Photo principale : </label>
	{if $announce->getPhotoMain() != '' && $announce->getPhotoMain() != Announcement::IMAGE_DEFAULT}
	<table>
		<tr>
			<td style="width: 160px;">
				<img alt="image de l'annonce" src="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoMain()}"/>
			</td>
			<td>
				<input type="checkbox" name="delete-photo-main" id="delete-photo-main">
				<label class="inline" for="delete-photo-main">Supprimer cette photo</label>
			</td>
		</tr>
	</table>
	{/if}
	<input type="file" name="photo-main" id="photo-main" />
	
	<label for="photo-option-1">Photo optionelle 1 :</label>
	{if $announce->getPhotoOption1() != '' && $announce->getPhotoOption1() != Announcement::IMAGE_DEFAULT}
	<table>
		<tr>
			<td style="width: 160px;">
				<img alt="image de l'annonce" src="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoOption1()}"/>
			</td>
			<td>
				<input type="checkbox" name="delete-photo-option-1" id="delete-photo-option-1">
				<label class="inline" for="delete-photo-option-1">Supprimer cette photo</label>
			</td>
		</tr>
	</table>
	{/if}
	<input type="file" name="photo-option-1" id="photo-option-1"/>
	
	<label for="photo-option-2">Photo optionelle 2 :</label>
	{if $announce->getPhotoOption2() != '' && $announce->getPhotoOption2() != Announcement::IMAGE_DEFAULT}
	<table>
		<tr>
			<td style="width: 160px;">
				<img alt="image de l'annonce" src="{Announcement::ANNOUNCEMENT_DIRECTORY}{$announce->id()}/{Announcement::THUMBNAILS_PREFIX}{$announce->getPhotoOption2()}"/>
			</td>
			<td>
				<input type="checkbox" name="delete-photo-option-2" id="delete-photo-option-2">
				<label class="inline" for="delete-photo-option-2">Supprimer cette photo</label>
			</td>
		</tr>
	</table>
	{/if}
	<input type="file" name="photo-option-2" id="photo-option-2"/>
	<div class="right">
		<button id="submit-form" name="submit-form" class="small">Suivant</button>
		<a class="btn red small" href="/announcements">Annuler</a>
	</div>
</form>
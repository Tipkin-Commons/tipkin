{literal}
<script type="text/javascript">
<!--
$(function(){
	$('#form-update-mailing').validate();
});
//-->
</script>
{/literal}
<div style="width: 400px;">
	<h4>Gérer mon inscription à la newsletter</h4>
	<div class="col_12 visible">
		<div class="col_12">
			<form method="post" action="/profile/update-mailing" id="form-update-mailing">
                            {$radioMailingStateTrue=''}
                            {$radioMailingStateFalse=''}
                            {if $mailingState eq 1}
                                {$mailingStateString=" inscrit à "}
                                {$radioMailingStateTrue=" checked "}
                            {else}
                                {$mailingStateString=" désinscrit de "}
                                {$radioMailingStateFalse=" checked "}
                            {/if}
                                
				<label for="InfoMailingState">Vous êtes actuellement {$mailingStateString} notre newsletter</label>
                                <br>
                                <br><label for ="InfoHowToMailing">
                                    Vous pouvez modifier l'état de votre inscription ci dessous
                                    </label>
                                <br><br>
                                <input type=radio name="f_mailingState" value=1 {$radioMailingStateTrue}> Je souhaite recevoir régulièrement les informations Tipkin</input>
                                <br>
                                <input type=radio name="f_mailingState" value=0 {$radioMailingStateFalse}> Je ne souhaite pas recevoir les informations Tipkin</input>
                                <br><br>
				
				<button style="float: right" class="small green" name="save-new-mailing-state">Terminer</button>
			</form>
		</div>
	</div>
</div>
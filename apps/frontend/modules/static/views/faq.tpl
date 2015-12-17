{extends file="layout.tpl"}

{block name=page_title prepend}Foire Aux Questions (FAQ){/block}
{block name=meta_desc}Consultez notre FAQ où sont listés les questions/réponses les plus fréquentes{/block}

{block name=page_content}
{literal}
<script type="text/javascript">
<!--
$(function(){
	//var countH5 = 1;
	$('#faq h5').next().hide();
	$('#faq h5').css('cursor', 'pointer');
	$('#faq h5').hover(function(){
		$(this).css('textDecoration', 'underline');
	},function(){
		$(this).css('textDecoration', 'none');
	});
	$('#faq h5').click(function(){
		$(this).next().toggle('blind');
	});
});
//-->
</script>
{/literal}
<div id="faq" class="col_12" style="text-align: justify;">
	<h1>Foire Aux Questions</h1>
	<div class="col_12">	
		{include file="{$template}.tpl"}
	</div>
</div>
{/block}

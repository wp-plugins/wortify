<div class="wortifyModeElem" id="wortifyMode_options"></div>
<div class="wrap">
	<form id="wortifyConfigForm">
	<table class="wortifyConfigForm">
<?php foreach (wortifyConfig::$configs as $key => $values) { 
	foreach ($values as $keyb => $valuesb) {
?>		<tr>
			<tb><?php echo (defined($valuesb['title'])?constant($valuesb['title']):$valuesb['title']).'<br><font style="size: 0.89em">'.(defined($valuesb['description'])?constant($valuesb['description']):$valuesb['description']).'</font>' ?></tb>
			<tb><?php echo wortifyConfig::formElement($valuesb['name'], $valuesb['formtype'], $valuesb['options'], wortifyConfig::get($valuesb['name'], $valuesb['default'])); ?></tb>
		</tr>
	<?php }
	}?>	
	</table>
	<p><table border="0" cellpadding="0" cellspacing="0"><tr><td><input type="button" id="button1" name="button1" class="button-primary" value="Save Changes" onclick="WFAD.saveConfig();" /></td><td style="height: 24px;"><div class="wortifyAjax24"></div><span class="wortifySavedMsg">&nbsp;Your changes have been saved!</span></td></tr></table></p>
	</form>
</div>

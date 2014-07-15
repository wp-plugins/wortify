<link rel='stylesheet' href='<?php echo plugins_url( '/css/style.css', __FILE__ ); ?>' type='text/css' media='all' />
<?php 
	if (isset($_POST)&&count($_POST)>0) {
		wortifyConfig::setArray($_POST);
	}
?>
<div class="wortifyModeElem" id="wortifyMode_options"></div>
<div class="wrap">
	<form id="wortifyConfigForm" method="POST">
	<table class="wortifyConfigForm">
<?php foreach (wortifyConfig::$configs as $key => $values) { 
	foreach ($values as $keyb => $valuesb) {
		$cycle = ($cycle=='#d9d9d9'?'#fefefe':'#d9d9d9');
?>		<tr style="background-color: <?php echo $cycle; ?>; padding: 8px 8px 8px 8px; margin: 4px 4px 4px 4px;">
			<td width="33%" style="padding: 7px;"><?php echo '<font style="size: 1.69em; font-weight: bold;">' .(defined($valuesb['title'])?constant($valuesb['title']):$valuesb['title']).'</font><br><font style="size: 0.89em; font-weight: none;"><em>'.(defined($valuesb['description'])?constant($valuesb['description']):$valuesb['description']).'</em></font>' ?></tb>
			<td width="67%" style="padding: 7px;"><?php echo wortifyConfig::formElement($valuesb['name'], $valuesb['formtype'], $valuesb['options'], wortifyConfig::get($valuesb['name'], $valuesb['default']), $valuesb['valuetype']); ?></tb>
		</tr>
	<?php }
	}?>	
	</table>
	<p><table border="0" cellpadding="0" cellspacing="0"><tr><td><input type="submit" id="button1" name="button1" class="button-primary" value="Save Changes"/></td><td style="height: 24px;">&nbsp;</td></tr></table></p>
	</form>
</div>

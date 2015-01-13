<style>
	th, thead {background-color: #23AC5; padding : 2px; color: #0e45f6; font-size: 1.4356em; vertical-align : middle; padding: 5px;}
	table { width: 100%; margin-top: 18px; }
	.outer {border: 3px solid #c0c0c0;-webkit-box-shadow: 4px 4px 6px 2px rgba(95, 95, 15, 0.78);	-moz-box-shadow:    4px 4px 6px 2px rgba(95, 95, 15, 0.78);	box-shadow:         4px 4px 6px 2px rgba(95, 95, 15, 0.78);	-webkit-border-radius: 14px;	-moz-border-radius: 14px;	border-radius: 14px;	text-shadow: 2px 2px 2px rgba(103, 87, 101, 0.82);}.head {background-color: #c4ea66; padding: 5px; font-weight: bold;}
	.even {background-color: #d17fe7; padding: 5px; padding: 5px;font-size: 1.2123em;}
	.odd {background-color: #a6f4e1; padding: 5px; padding: 5px; font-size: 1.2123em;}
	.foot {background-color: #c2e7a1; padding: 5px; font-weight: bold;}
	tr.even td {background-color: #d17fe7; padding: 5px; padding: 5px;font-size: 1.2123em;}
	tr.odd td {background-color: #a6f4e1; padding: 5px; padding: 5px;font-size: 1.2123em;}
	tr.foot td {background-color: #c2cdd6; padding: 5px; color:inherit; font-weight: bold;}
	tr .head { min-width: 189px; }
	.caption-marker, #caption-marker { color: rgb(250,0,0);	float: right; font-size: 134%; font-weight: bold; }
	button, input, select, radio, checkbox, options, textarea {-webkit-box-shadow: 4px 4px 6px 2px rgba(95, 95, 15, 0.78); -moz-box-shadow: 4px 4px 6px 2px rgba(95, 95, 15, 0.78);	box-shadow: 4px 4px 6px 2px rgba(95, 95, 15, 0.78);	-webkit-border-radius: 5px;	-moz-border-radius: 5px; border-radius: 5px; text-shadow: 2px 2px 1px rgba(103, 87, 101, 0.82);}
</style>
<?php 
	if (isset($_POST)&&count($_POST)>0) {
		wortifyConfig::setArray($_POST);
	}
?>
<div class="wortifyModeElem" id="wortifyMode_options"></div>
<div class="wrap">
	<form id="wortifyConfigForm" method="POST">
	<div id="outer" class="outer">
	<table>
	<thead class="head">
		<th colspan="2" style="padding-bottom: 23px;">Wortify Configuration Options</th>
	</thead>
<?php foreach (wortifyConfig::$configs as $key => $values) { 
	foreach ($values as $keyb => $valuesb) {
?>		<tr>
			<td class="even" style="width: 45%;"><?php echo '<font style="size: 1.69em; font-weight: bold;">' .(defined($valuesb['title'])?constant($valuesb['title']):$valuesb['title']).'</font><br><font style="size: 0.89em; font-weight: none;"><em>'.(defined($valuesb['description'])?constant($valuesb['description']):$valuesb['description']).'</em></font>' ?></tb>
			<td class="odd"><?php echo wortifyConfig::formElement($valuesb['name'], $valuesb['formtype'], $valuesb['options'], wortifyConfig::get($valuesb['name'], $valuesb['default']), $valuesb['valuetype']); ?></tb>
		</tr>
	<?php }
	}?>	
	</table>
	</div>
	<p><table border="0" cellpadding="0" cellspacing="0"><tr><td><input type="submit" id="button1" name="button1" class="button-primary" value="Save Changes"/></td><td style="height: 24px;">&nbsp;</td></tr></table></p>
	</form>
</div>

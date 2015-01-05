<style>
	th, thead {background-color: #23AC5; margin-bottom : 12px; color: #0e45f6; font-size: 1.1356em; vertical-align : middle; padding: 5px;}
	.outer {border: 3px solid #c0c0c0;-webkit-box-shadow: 4px 4px 6px 2px rgba(95, 95, 15, 0.78);	-moz-box-shadow:    4px 4px 6px 2px rgba(95, 95, 15, 0.78);	box-shadow:         4px 4px 6px 2px rgba(95, 95, 15, 0.78);	-webkit-border-radius: 14px;	-moz-border-radius: 14px;	border-radius: 14px;	text-shadow: 2px 2px 2px rgba(103, 87, 101, 0.82);}
	.head {background-color: #c4ea66; padding: 5px; font-weight: bold;}
	table {margin-top: 18px; }
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
error_reporting(E_ERROR);
ini_set('display_errors', true);

include_once dirname(__FILE__) . '/class/cache/wortifyCache.php';
include_once dirname(__FILE__) . '/wortifyPageNav.php';

$log_handler =& wortify_getmodulehandler('log', 'xortify');

$ttl = $log_handler->getCount(NULL);
$limit = !empty($_GET['limit'])?intval($_REQUEST['limit']):30;
$start = !empty($_GET['start'])?intval($_REQUEST['start']):0;
$order = !empty($_GET['order'])?$_REQUEST['order']:'DESC';
$sort = !empty($_GET['sort'])?''.$_REQUEST['sort'].'':'date';

unset($_GET['limit']);
unset($_GET['start']);
unset($_GET['order']);
unset($_GET['sort']);

$pagenav = new WortifyPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&'.http_build_query($_GET));
$fullnav = $pagenav->renderNav();

foreach (array(	'action','provider','date','uname','email','ip4','ip6','proxy-ip4',
		'proxy-ip6','network-addy','agent') as $id => $key) {
		$headers[$key] = '<a style="color: #fffff;" href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&'.http_build_query($_GET).'">'.(defined('WORTIFY_ADMIN_TH_'.strtoupper(str_replace('-','_',$key)))?constant('WORTIFY_ADMIN_TH_'.strtoupper(str_replace('-','_',$key))):'WORTIFY_ADMIN_TH_'.strtoupper(str_replace('-','_',$key))).'</a>';
}

$criteria = new Criteria('1','1');
$criteria->setStart($start);
$criteria->setLimit($limit);
$criteria->setSort('`'.$sort.'`');
$criteria->setOrder($order);

$logs = $log_handler->getObjects($criteria, true);
foreach($logs as $id => $log) {
	$data[] = $log->toArray();
}


?><div class="wortifyModeElem" id="wortifyMode_blockedIPs"></div>
<h1><?php echo WORTIFY_ADMIN_LOG_H1; ?></h1>
<p><?php echo WORTIFY_ADMIN_LOG_P; ?></p>
<div style="float:right;"><?php echo $fullnav; ?></div>
<div id="outer" class="outer">
<table width="95%">
	<thead class="head">
		<th><?php echo $headers['action']; ?></th>
		<th><?php echo $headers['provider']; ?></th>
		<th><?php echo $headers['date']; ?></th>
		<th><?php echo $headers['uname']; ?></th>
		<th><?php echo $headers['email']; ?></th>
		<th><?php echo $headers['ip4']; ?></th>
		<th><?php echo $headers['ip6']; ?></th>
		<th><?php echo $headers['proxy-ip4']; ?></th>
		<th><?php echo $headers['proxy-ip6']; ?></th>
		<th><?php echo $headers['network-addy']; ?></th>
		<th><?php echo $headers['agent']; ?></th>
	</thead>
	<?php foreach ($data as $key => $item) { $cycle = ($cycle=='#d9d9d9'?'#fefefe':'#d9d9d9'); ?>
	<tr>
		<td align='center'><?php echo $item['action']; ?></td>
		<td align='center'><?php echo $item['provider']; ?></td>
		<td align='center'><?php echo $item['date_datetime']; ?></td>
		<td align='center'><?php echo $item['uname']; ?></td>
		<td align='center'><?php echo $item['email']; ?></td>
		<td align='right'><?php echo $item['ip4']; ?></td>
		<td align='right'><?php echo $item['ip6']; ?></td>
		<td align='right'><?php echo $item['proxy_ip4']; ?></td>
		<td align='right'><?php echo $item['proxy_ip6']; ?></td>
		<td align='right'><?php echo $item['network_addy']; ?></td>
		<td align='right'><?php echo $item['agent']; ?></td>
	</tr>
	<?php } ?>
	<tr class="foot">
		<td colspan="12">&nbsp;</td>
	</tr>
</table>
</div>

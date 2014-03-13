<?php 

include_once WORTIFY_VAR_PATH.'/lib/xortify/class/cache/wortifyCache.php';
include_once WORTIFY_VAR_PATH.'/lib/xortify/wortifyPageNav.php';
		

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
$GLOBALS['wortifyTpl']->assign('pagenav', $pagenav->renderNav());

foreach (array(	'action','provider','date','uname','email','ip4','ip6','proxy-ip4',
		'proxy-ip6','network-addy','agent') as $id => $key) {
		$headers[$key] = '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&'.http_build_query($_GET).'">'.(defined('WORTIFY_ADMIN_TH_'.strtoupper(str_replace('-','_',$key)))?constant('WORTIFY_ADMIN_TH_'.strtoupper(str_replace('-','_',$key))):'WORTIFY_ADMIN_TH_'.strtoupper(str_replace('-','_',$key))).'</a>';
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
<div class="wrap">
	<h1><?php echo WORTIFY_ADMIN_LOG_H1; ?></h1>
<p><?php echo WORTIFY_ADMIN_LOG_P; ?></p>
<div style="float:right;"><{$pagenav; ?></div>
<table>
	<tr class="head">
		<th><?php echo $headers['action']; ?></th>
		<th><?php echo $headers['provider']; ?></th>
		<th><?php echo $headers['date']; ?></th>
		<th><?php echo $headers['uname']; ?></th>
		<th><?php echo $headers['email']; ?></th>
		<th><?php echo $headers['ip4']; ?></th>
		<th><?php echo $headers['ip6']; ?></th>
		<th><?php echo $headers['proxy_ip4']; ?></th>
		<th><?php echo $headers['proxy_ip6']; ?></th>
		<th><?php echo $headers['network_addy']; ?></th>
		<th><?php echo $headers['agent']; ?></th>
	</tr>
	<?php foreach ($data as $key => $item) { ?>
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
		<td colspan="11">&nbsp;</td>
	</tr>
</table>
</div>

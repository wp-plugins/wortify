<div class="wortifyModeElem" id="wortifyMode_blockedIPs"></div>
<div class="wrap">
<?php 
		include_once dirname(__FILE__).'/xortify/class/cache/wortifyCache.php';
		include_once dirname(__FILE__).'/xortify/wortifyPageNav.php';
		
		if (!$bans = WortifyCache::read('xortify_bans_cache')) {
			include_once( dirname(__FILE__) . '/xortify/class/'.WortifyConfig::get('xortify_protocol').'.php' ); 	
			$func = strtoupper(WortifyConfig::get('xortify_protocol')).'WortifyExchange';
			ob_start();
			$soapExchg = new $func;
			$bans = $soapExchg->retrieveBans();
			ob_end_flush();
			
			WortifyCache::delete('xortify_bans_cache');
			WortifyCache::delete('xortify_bans_cache_backup');			
			WortifyCache::write('xortify_bans_cache', $bans, WortifyConfig::get('xortify_xortify_seconds'));
			WortifyCache::write('xortify_bans_cache_backup', $bans, (WortifyConfig::get('xortify_xortify_seconds') * 1.45));					
		}
		
		if ($bans['bans']==0) {
			echo WORTIFY_AM_NOCACHEMSG;	
		}	else {
		
			$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
			$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
		
			unset($_GET['start']);
			unset($_GET['limit']);
			$pagenav = new WortifyPageNav($bans['bans'], $limit, $start, 'start', 'limit='.$limit.'&'.http_build_query($_GET));
			$i=0;
			$num=0;
			foreach($bans['data'] as $key => $data) {
				$i++;
				if ($i>=$start&&$num<=$limit) {
					$num++;
					if (strlen($data['ip4'])>0) {
						$ipaddy = $data['ip4'];
						$iptype = WORTIFY_IPTYPE_IP4;
					} elseif (strlen($data['ip6'])>0) {
						$ipaddy = $data['ip6'];
						$iptype = WORTIFY_IPTYPE_IP6;
					} else {
						$ipaddy = '';
						$iptype = WORTIFY_IPTYPE_EMPTY;				
					}

					if (strlen($data['proxy-ip4'])>0) {
						$proxyip = $data['proxy-ip4'];
						$proxyiptype = WORTIFY_IPTYPE_IP4;
					} elseif (strlen($data['proxy-ip6'])>0) {
						$proxyip = $data['proxy-ip6'];
						$proxyiptype = WORTIFY_IPTYPE_IP6;
					} else {
						$proxyip = '';
						$proxyiptype = '';					
					}
				
					$bans[] = array('netaddy'=>$data['network-addy']?$data['network-addy']:'&nbsp;',
									 'macaddy'=>$data['mac-addy']?$data['mac-addy']:'&nbsp;',
									 'iptype'=>$iptype, 'ipaddy'=>$ipaddy, 
									 'proxyiptype'=>$proxyiptype,'ip'=>$proxyip,
									 'long' => $data['long']?$data['long']:'&nbsp;', 
									 'category' =>$data['category'],'comments'=>(isset($data['comments'])?$data['comments']:array()));
				}		
			}
			$hostname = 'xortify.com';
			$cloudurl = 'https://'.$hostname;
?>
			<div style="height:45px; clear:both;">
				<div style="float:right; clear:both;"><?php echo $pagenav; ?></div>
			</div>
			<table width="100%">
			<tr class="head">
				<td><?php echo WORTIFY_AM_IPTYPE; ?></td>
			   	<td><?php echo WORTIFY_AM_IPADDRESS; ?></td>
			   	<td><?php echo WORTIFY_AM_NETADDRESS; ?></td>
			   	<td><?php echo WORTIFY_AM_PROXYIP; ?></td>
			   	<td><?php echo WORTIFY_AM_MACADDRESS; ?></td>
			   	<td><?php echo WORTIFY_AM_LONG; ?></td>
			   	<td><?php echo WORTIFY_AM_CATEGORY; ?></td>    
			</tr>
			<?php foreach ($bans as $key => $ban) { ?>
			<tr>
				<td><?php echo $ban['iptype']; ?></td>
			   	<td><?php echo $ban['ipaddy']; ?></td>
			   	<td><?php echo $ban['netaddy']; ?></td>
			   	<td><?php echo $ban['proxyip']; ?></td>
			   	<td><?php echo $ban['macaddy']; ?></td>
			   	<td><?php echo $ban['long']; ?></td>
			   	<td><?php echo $ban['category']; ?></td>    
			</tr>
			<?php foreach ($ban['comments'] as $keyb => $comment) { ?>
			<?php if (!empty($comment['com_text'])) { ?>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo WORTIFY_AM_BANID; ?>&nbsp;<a href='<?php echo $cloudurl; ?>/ban/index.php?op=member&id=<?php echo $comment['com_itemid']; ?>'>#<?php echo $comment['com_itemid']; ?></a></td>
			   	<td colspan='5'><?php echo $comment['com_text']; ?></td>
			</tr>
			<?php } ?>
		<?php } ?>
	<?php } 
						
	} ?>
</table>
</div>
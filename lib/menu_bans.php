<?php wp_register_style( 'wortify-style', plugins_url('/css/style.css', __FILE__) ); ?>
<div class="wortifyModeElem" id="wortifyMode_blockedIPs"></div>
<div class="wrap">
<?php 		
		include_once dirname(__FILE__).'/xortify/class/cache/wortifyCache.php';
		include_once dirname(__FILE__).'/wortifyPageNav.php';

		if (!($bans = WortifyCache::read('xortify_bans_cache')) || $bans['bans']==0) {
			include_once( dirname(__FILE__) . '/xortify/class/'.WortifyConfig::get('xortify_protocol').'.php' ); 	
			$func = strtoupper(WortifyConfig::get('xortify_protocol')).'WortifyExchange';
			$soapExchg = new $func;
			$bans = $soapExchg->retrieveBans();
			WortifyCache::delete('xortify_bans_cache');
			WortifyCache::delete('xortify_bans_cache_backup');			
			WortifyCache::write('xortify_bans_cache', $bans, WortifyConfig::get('xortify_xortify_seconds'));
			WortifyCache::write('xortify_bans_cache_backup', $bans, (WortifyConfig::get('xortify_xortify_seconds') * 1.45));					
		}
		if ($bans['bans']==0) {
			echo WORTIFY_ADMIN_NOCACHEMSG;	
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
						$iptype = WORTIFY_ADMIN_IPTYPE_IP4;
					} elseif (strlen($data['ip6'])>0) {
						$ipaddy = $data['ip6'];
						$iptype = WORTIFY_ADMIN_IPTYPE_IP6;
					} else {
						$ipaddy = '';
						$iptype = WORTIFY_ADMIN_IPTYPE_EMPTY;				
					}

					if (strlen($data['proxy-ip4'])>0) {
						$proxyip = $data['proxy-ip4'];
						$proxyiptype = WORTIFY_ADMIN_IPTYPE_IP4;
					} elseif (strlen($data['proxy-ip6'])>0) {
						$proxyip = $data['proxy-ip6'];
						$proxyiptype = WORTIFY_ADMIN_IPTYPE_IP6;
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
			<h1 style="font-size: 3.111em; color: rgb(197,10,10); text-align: center;"><?php echo WORTIFY_ADMIN_BANS; ?></h1>
			<div style="height:45px; clear:both;">
				<div style="float:right; clear:both;"><?php echo $pagenav->renderNav(4); ?></div>
			</div>
			<table width="100%" class="wortify-table" style="font-size: 1.39em;">
			<thead class="head" style="background-color: #2A75C5; padding : 2px; color: #fff; vertical-align : middle; font-size: 135%;">
				<td style="padding: 7px;"><?php echo WORTIFY_ADMIN_IPTYPE; ?></td>
			   	<td style="padding: 7px;"><?php echo WORTIFY_ADMIN_IPADDRESS; ?></td>
			   	<td style="padding: 7px;"><?php echo WORTIFY_ADMIN_NETADDRESS; ?></td>
			   	<td style="padding: 7px;"><?php echo WORTIFY_ADMIN_PROXYIP; ?></td>
			   	<td style="padding: 7px;"><?php echo WORTIFY_ADMIN_MACADDRESS; ?></td>
			   	<td style="padding: 7px;"><?php echo WORTIFY_ADMIN_LONG; ?></td>
			   	<td style="padding: 7px;"><?php echo WORTIFY_ADMIN_CATEGORY; ?></td>
			   	<td style="padding: 7px;"><?php echo WORTIFY_ADMIN_TYPE; ?></td>        
			</thead>
			<?php foreach ($bans as $key => $ban) { 
				if (!empty($ban['category']['category_name'])) {
				?>
			<tr style="background-color: #1124a9; padding: 2px; color: #fff; text-align: center;">
				<td style="padding: 7px;"><?php echo (empty($ban['iptype'])?'---':$ban['iptype']); ?></td>
			   	<td style="padding: 7px;"><?php echo (empty($ban['ipaddy'])?'---':$ban['ipaddy']); ?></td>
			   	<td style="padding: 7px;"><?php echo (empty($ban['netaddy'])?'---':$ban['netaddy']); ?></td>
			   	<td style="padding: 7px;"><?php echo (empty($ban['proxyip'])?'---':$ban['proxyip']); ?></td>
			   	<td style="padding: 7px;"><?php echo (empty($ban['macaddy'])?'---':$ban['macaddy']); ?></td>
			   	<td style="padding: 7px;"><?php echo (empty($ban['long'])?'---':$ban['long']); ?></td>    
			   	<td style="padding: 7px;"><?php echo (empty($ban['category']['category_name'])?'---':$ban['category']['category_name']); ?></td>
			   	<td style="padding: 7px;"><?php echo (empty($ban['category']['category_type'])?'---':$ban['category']['category_type']); ?></td>
			</tr>
			<?php foreach ($ban['comments'] as $keyb => $comment) { ?>
			<?php if (!empty($comment['com_text'])) { ?>
			<tr style="background-color: #111; color: #eee; padding: 14px;">
				<td colspan='2' style="background-color: #1124a9; padding: 2px; color: #fff; vertical-align: middle; margin-top: 9px; text-align: center;"><?php echo WORTIFY_ADMIN_BANID; ?>&nbsp;<a target="_blank" href='<?php echo $cloudurl; ?>/ban/index.php?op=member&id=<?php echo $comment['com_itemid']; ?>'>#<?php echo $comment['com_itemid']; ?></a></td>
			   	<td colspan='6'><div style="overflow: scroll; max-height: 185px; padding: 11px;"><?php echo $comment['com_text']; ?></div></td>
			</tr>
			<?php } 
				} ?>
		<?php } ?>
	<?php } 
						
	} ?>
</table>
</div>
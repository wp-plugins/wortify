<div class="wortifyModeElem" id="wortifyMode_blockedIPs"></div>
<div class="wrap">
	<?php $pageTitle = "Wortify Blocked IPs"; include('pageTitle.php'); ?>
	<div class="wortifyLive">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr><td><h2>Wortify Live Activity:</h2></td><td id="wortifyLiveStatus"></td></tr>
		</table>
	</div>
	<?php if(! wortifyConfig::get('firewallEnabled')){ ?><div style="color: #F00; font-weight: bold;">Firewall is disabled. You can enable it on the <a href="admin.php?page=WortifySecOpt">Wortify Options page</a> at the top.</div><?php } ?>
	<div class="wortifyWrap" style="margin: 20px 20px 20px 30px;">
		<a href="#" onclick="WFAD.clearAllBlocked('blocked'); return false;">Clear all blocked IP addresses</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="WFAD.clearAllBlocked('locked'); return false;">Clear all locked out IP addresses</a><br />
		You can manually (and permanently) block an IP by entering the address here: <input type="text" id="wortifyManualBlock" size="20" maxlength="40" value="" onkeydown="if(event.keyCode == 13){ WFAD.blockIPTwo(jQuery('#wortifyManualBlock').val(), 'Manual block by administrator', true); return false; }" />&nbsp;<input type="button" name="but1" value="Manually block IP" onclick="WFAD.blockIPTwo(jQuery('#wortifyManualBlock').val(), 'Manual block by administrator', true); return false;" />
	</div>
	<div class="wortifyWrap">
		<div>
			<div id="wortifyTabs">
				<a href="#" class="wortifyTab1 wortifyTabSwitch selected" onclick="wortifyAdmin.switchTab(this, 'wortifyTab1', 'wortifyDataPanel', 'wortifyActivity_blockedIPs', function(){ WFAD.staticTabChanged(); }); return false;">IPs that are blocked from accessing the site</a>
				<a href="#" class="wortifyTab1 wortifyTabSwitch" onclick="wortifyAdmin.switchTab(this, 'wortifyTab1', 'wortifyDataPanel', 'wortifyActivity_lockedOutIPs', function(){ WFAD.staticTabChanged(); }); return false;">IPs that are Locked Out from Login</a>
				<a href="#" class="wortifyTab1 wortifyTabSwitch" onclick="wortifyAdmin.switchTab(this, 'wortifyTab1', 'wortifyDataPanel', 'wortifyActivity_throttledIPs', function(){ WFAD.staticTabChanged(); }); return false;">IPs who were recently throttled for accessing the site too frequently</a>
			</div>
			<div class="wortifyTabsContainer">
				<div id="wortifyActivity_blockedIPs" class="wortifyDataPanel"><div class="wortifyLoadingWhite32"></div></div>
				<div id="wortifyActivity_lockedOutIPs" class="wortifyDataPanel" style="display: none;"><div class="wortifyLoadingWhite32"></div></div>
				<div id="wortifyActivity_throttledIPs" class="wortifyDataPanel" style="display: none;"><div class="wortifyLoadingWhite32"></div></div>
			</div>
		</div>
	</div>
</div>
<script type="text/x-jquery-template" id="wortifyThrottledIPsTmpl">
<div>
<div style="border-bottom: 1px solid #CCC; padding-bottom: 10px; margin-bottom: 10px;">
<table border="0" style="width: 100%">
{{each(idx, elem) results}}
<tr><td style="vertical-align: top;">
	<div>
		{{if loc}}
			<img src="http://www.xortify.com/images/flags/${loc.countryCode.toLowerCase()}.png" width="16" height="11" alt="${loc.countryName}" title="${loc.countryName}" class="wortifyFlag" />
			<a href="http://maps.google.com/maps?q=${loc.lat},${loc.lon}&z=6" target="_blank">{{if loc.city}}${loc.city}, {{/if}}${loc.countryName}</a>
		{{else}}
			An unknown location at IP <a href="${WFAD.makeIPTrafLink(IP)}" target="_blank">${IP}</a>
		{{/if}}
	</div>
	<div>
		<strong>IP:</strong>&nbsp;<a href="${WFAD.makeIPTrafLink(IP)}" target="_blank">${IP}</a>
	</div>
	<div>
		<strong>Reason:</strong>&nbsp;${lastReason}
	</div>
	<div>
		<span class="wortifyReverseLookup"><span style="display:none;">${IP}</span></span>
	</div>
	<div>
		<span>Throttled <strong>${timesThrottled}</strong> times starting <strong>${startTimeAgo} ago</strong> and ending <strong>${endTimeAgo} ago</strong>.</span>
	</div>
</td>
</tr>
{{/each}}
</table>
</div>
</div>
</script>
<script type="text/x-jquery-template" id="wortifyLockedOutIPsTmpl">
<div>
<div style="border-bottom: 1px solid #CCC; padding-bottom: 10px; margin-bottom: 10px;">
<table border="0" style="width: 100%">
{{each(idx, elem) results}}
<tr><td>
	<div>
		{{if loc}}
			<img src="http://www.xortify.com/images/flags/${loc.countryCode.toLowerCase()}.png" width="16" height="11" alt="${loc.countryName}" title="${loc.countryName}" class="wortifyFlag" />
			<a href="http://maps.google.com/maps?q=${loc.lat},${loc.lon}&z=6" target="_blank">{{if loc.city}}${loc.city}, {{/if}}${loc.countryName}</a>
		{{else}}
			An unknown location at IP <a href="${WFAD.makeIPTrafLink(IP)}" target="_blank">${IP}</a>
		{{/if}}
	</div>
	<div>
		<strong>IP:</strong>&nbsp;<a href="${WFAD.makeIPTrafLink(IP)}" target="_blank">${IP}</a> [<a href="#" onclick="WFAD.unlockOutIP('${IP}'); return false;">unlock</a>]
	</div>
	<div>
		<strong>Reason:</strong>&nbsp;${reason}
	</div>
	<div>
		<span class="wortifyReverseLookup"><span style="display:none;">${IP}</span></span>
	</div>
	<div>
		{{if lastAttemptAgo}}
			<span class="wortifyTimeAgo">Last blocked attempt to sign-in or use the forgot password form was ${lastAttemptAgo} ago.</span>
		{{else}}
			<span class="wortifyTimeAgo">No attempts have been made to sign-in or use the forgot password form since this IP was locked out.</span>
		{{/if}}
	</div>
</td>
<td style="color: #999;">
	<ul>
	<li>${blockedHits} attempts have been blocked</li>
	<li>Will be unlocked in ${blockedForAgo}</li>
	</ul>
</td></tr>
{{/each}}
</table>
</div>
</div>
</script>
<script type="text/x-jquery-template" id="wortifyBlockedIPsTmpl">
<div>
<div style="border-bottom: 1px solid #CCC; padding-bottom: 10px; margin-bottom: 10px;">
<table border="0" style="width: 100%">
{{each(idx, elem) results}}
<tr><td>
	<div>
		{{if loc}}
			<img src="http://www.xortify.com/images/flags/${loc.countryCode.toLowerCase()}.png" width="16" height="11" alt="${loc.countryName}" title="${loc.countryName}" class="wortifyFlag" />
			<a href="http://maps.google.com/maps?q=${loc.lat},${loc.lon}&z=6" target="_blank">{{if loc.city}}${loc.city}, {{/if}}${loc.countryName}</a>
		{{else}}
			An unknown location at IP <a href="${WFAD.makeIPTrafLink(IP)}" target="_blank">${IP}</a>
		{{/if}}
	</div>
	<div>
		<strong>IP:</strong>&nbsp;<a href="${WFAD.makeIPTrafLink(IP)}" target="_blank">${IP}</a> [<a href="#" onclick="WFAD.unblockIPTwo('${IP}'); return false;">unblock</a>]
		{{if permanent == '1'}}
			[<span style="color: #F00;">permanently blocked</span>]
		{{else}}&nbsp;&nbsp;[<a href="#" onclick="WFAD.permBlockIP('${IP}'); return false;">make permanent</a>]{{/if}}
	</div>
	<div>
		<strong>Reason:</strong>&nbsp;${reason}
	</div>
	<div>
		<span class="wortifyReverseLookup"><span style="display:none;">${IP}</span></span>
	</div>
	<div>
		{{if lastAttemptAgo}}
			<span class="wortifyTimeAgo">Last blocked attempt to access the site was ${lastAttemptAgo} ago.</span>
		{{else}}
			<span class="wortifyTimeAgo">No attempts have been made to access the site since this IP was blocked.</span>
		{{/if}}
	</div>
	<div>
		{{if lastHitAgo}}
			<span class="wortifyTimeAgo">Last site access before this IP was blocked was ${lastHitAgo} ago.</span>
		{{/if}}
	</div>
</td>
<td style="color: #999;">
	<ul>
	<li>${totalHits} hits before blocked</li>
	<li>${blockedHits} blocked hits</li>
	<li>
		{{if permanent == '1'}}Permanently blocked{{else}}
		Will be unblocked in ${blockedForAgo}{{/if}}
	</li>
	</ul>
</td></tr>
{{/each}}
</table>
</div>
</div>
</script>
<script type="text/x-jquery-template" id="wortifyWelcomeContent4">
<div>
<h3>How to manage Blocked IP addresses</h3>
<strong><p>Block IP's temporarily or permanently</p></strong>
<p>
	When you block an IP address, it will appear here with some additional information. 
	You will be able to see the geographic location of the IP, how many hits occured before
	it was blocked and how many attempts it has made on your site since it was blocked.
</p>
<p>
	You can also see how long until a blocked IP will be automatically unblocked. 
	You can also manually add IP addresses on this page to be blocked.
</p>
<p>
	You also have the option to see IP addresses who have been locked out from the login system for too many login attempts. 
	And finally, when the firewall "throttles" someone's access for accessing the site too quickly, you can 
	see which IP addresses have been throttled.
</p>
</div>
</script>

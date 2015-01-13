<style>
    table { margin-top: 18px; }
	th, thead {background-color: #23AC5; padding : 2px; color: #0e45f6; font-size: 1.4356em; vertical-align : middle; padding: 5px;}
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
require_once dirname(__FILE__).'/wortifyPageNav.php' ;
require_once dirname(__FILE__).'/wortifyTextsanitizer.php' ;

$myts =& WortifyTextSanitizer::getInstance() ;

// GET vars
$pos = empty( $_GET[ 'pos' ] ) ? 0 : intval( $_GET[ 'pos' ] ) ;
$num = empty( $_GET[ 'num' ] ) ? 20 : intval( $_GET[ 'num' ] ) ;

// Table Name
$log_table = $GLOBALS['wortifyDB']->prefix( "wortify_protector_log" ) ;

// Protector object
require_once dirname(__FILE__).'/protector/class/protector.php' ;
$protector =& Protector::getInstance( $GLOBALS['wortifyDB']->conn ) ;
$conf = $protector->getConf() ;

//
// transaction stage
//

if( ! empty( $_POST['action'] ) ) {

	if( $_POST['action'] == 'update_ips' ) {
		$error_msg = '' ;

		$lines = empty( $_POST['bad_ips'] ) ? array() : explode( "\n" , trim( $_POST['bad_ips'] ) ) ;
		$bad_ips = array() ;
		foreach( $lines as $line ) {
			@list( $bad_ip , $jailed_time ) = explode( ':' , $line , 2 ) ;
			$bad_ips[ trim( $bad_ip ) ] = empty( $jailed_time ) ? 0x7fffffff : intval( $jailed_time ) ;
		}
		if( ! $protector->write_file_badips( $bad_ips ) ) {
			$error_msg .= WORTIFT_AM_MSG_BADIPSCANTOPEN ;
		}

		$group1_ips = empty( $_POST['group1_ips'] ) ? array() : explode( "\n" , trim( $_POST['group1_ips'] ) ) ;
		foreach( array_keys( $group1_ips ) as $i ) {
			$group1_ips[$i] = trim( $group1_ips[$i] ) ;
		}
		$fp = @fopen( $protector->get_filepath4group1ips() , 'w' ) ;
		if( $fp ) {
			@flock( $fp , LOCK_EX ) ;
			fwrite( $fp , serialize( array_unique( $group1_ips ) ) . "\n" ) ;
			@flock( $fp , LOCK_UN ) ;
			fclose( $fp ) ;
		} else {
			$error_msg .= WORTIFT_AM_MSG_GROUP1IPSCANTOPEN ;
		}

		$redirect_msg = $error_msg ? $error_msg : WORTIFT_AM_MSG_IPFILESUPDATED ;
		redirect_header( "center.php?page=center" , 2 , $redirect_msg ) ;
		exit ;

	} else if( $_POST['action'] == 'delete' && isset( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) {
		// remove selected records
		foreach( $_POST['ids'] as $lid ) {
			$lid = intval( $lid ) ;
			$GLOBALS['wortifyDB']->query( "DELETE FROM $log_table WHERE lid='$lid'" ) ;
		}
		redirect_header( "center.php?page=center" , 2 , WORTIFT_AM_MSG_REMOVED ) ;
		exit ;

	} else if( $_POST['action'] == 'deleteall' ) {
		// remove all records
		$GLOBALS['wortifyDB']->query( "DELETE FROM $log_table" ) ;
		redirect_header( "center.php?page=center" , 2 , WORTIFT_AM_MSG_REMOVED ) ;
		exit ;

	} else if( $_POST['action'] == 'compactlog' ) {
		// compactize records (removing duplicated records (ip,type)
		$result = $GLOBALS['wortifyDB']->query( "SELECT `lid`,`ip`,`type` FROM $log_table ORDER BY lid DESC" ) ;
		$buf = array() ;
		$ids = array() ;
		while( list( $lid , $ip , $type ) = $GLOBALS['wortifyDB']->fetchRow( $result ) ) {
			if( isset( $buf[ $ip . $type ] ) ) {
				$ids[] = $lid ;
			} else {
				$buf[ $ip . $type ] = true ;
			}
		}
		$GLOBALS['wortifyDB']->query( "DELETE FROM $log_table WHERE lid IN (".implode(',',$ids).")" ) ;
	}
}


//
// display stage
//

// query for listing
$rs = $GLOBALS['wortifyDB']->query( "SELECT count(lid) FROM $log_table" ) ;
list( $numrows ) = $GLOBALS['wortifyDB']->fetchRow( $rs ) ;
$prs = $GLOBALS['wortifyDB']->query( "SELECT l.lid, l.uid, l.ip, l.agent, l.type, l.description, UNIX_TIMESTAMP(l.timestamp), u.uname FROM $log_table l LEFT JOIN ".$GLOBALS['wortifyDB']->prefix("users")." u ON l.uid=u.uid ORDER BY timestamp DESC LIMIT $pos,$num" ) ;

// Page Navigation
$nav = new WortifyPageNav( $numrows , $num , $pos , 'pos' , "page=center&num=$num&".http_build_query($_GET) ) ;
$nav_html = $nav->renderNav( 10 ) ;

// Number selection
$num_options = '' ;
$num_array = array( 20 , 100 , 500 , 2000 ) ;
foreach( $num_array as $n ) {
	if( $n == $num ) {
		$num_options .= "<option value='$n' selected='selected'>$n</option>\n" ;
	} else {
		$num_options .= "<option value='$n'>$n</option>\n" ;
	}
}

// title
echo "<h3 style='text-align:left;'>Wortify Protector</h3>\n" ;

// bad_ips
$bad_ips = $protector->get_bad_ips( true ) ;
uksort( $bad_ips , 'protector_ip_cmp' ) ;
$bad_ips4disp = '' ;
foreach( $bad_ips as $bad_ip => $jailed_time ) {
	$line = $jailed_time ? $bad_ip . ':' . $jailed_time : $bad_ip ;
	$line = str_replace( ':2147483647' , '' , $line ) ; // remove :0x7fffffff
	$bad_ips4disp .= htmlspecialchars( $line , ENT_QUOTES ) . "\n" ;
}

// group1_ips
$group1_ips = $protector->get_group1_ips() ;
usort( $group1_ips , 'protector_ip_cmp' ) ;
$group1_ips4disp = htmlspecialchars(implode("\n",$group1_ips),ENT_QUOTES) ;

// edit configs about IP ban and IPs for group=1
echo "
<form name='ConfigForm' action='' method='POST'>
<input type='hidden' name='action' value='update_ips' />
<table width='95%' class='outer' cellpadding='4' cellspacing='1'>
  <tr valign='top' align='left'>
    <td class='head'>
      ".WORTIFT_AM_TH_BADIPS."
    </td>
    <td class='even'>
      <textarea name='bad_ips' id='bad_ips' style='width:200px;height:60px;'>$bad_ips4disp</textarea>
      <br />
      ".htmlspecialchars($protector->get_filepath4badips())."
    </td>
  </tr>
  <tr valign='top' align='left'>
    <td class='head'>
      ".WORTIFT_AM_TH_GROUP1IPS."
    </td>
    <td class='even'>
      <textarea name='group1_ips' id='group1_ips' style='width:200px;height:60px;'>$group1_ips4disp</textarea>
      <br />
      ".htmlspecialchars($protector->get_filepath4group1ips())."
    </td>
  </tr>
  <tr valign='top' align='left'>
    <td class='head'>
    </td>
    <td class='even'>
      <input type='submit' value='"._GO."' />
    </td>
  </tr>
</table>
</form>
" ;


// header of log listing
echo "
<table width='95%' border='0' cellpadding='4' cellspacing='0'><tr><td>
<form action='' method='GET' style='margin-bottom:0px;'>
  <table width='95%' border='0' cellpadding='4' cellspacing='0'>
    <tr>
      <td align='left'>
        <select name='num' onchange='submit();'>$num_options</select>
        <input type='submit' value='"._SUBMIT."'>
      </td>
      <td align='right'>
        $nav_html
      </td>
    </tr>
  </table>
 </div>
</form>
<form name='MainForm' action='' method='POST' style='margin-top:0px;'>
<input type='hidden' name='action' value='' />
<table width='95%' class='outer' cellpadding='4' cellspacing='1'>
  <tr valign='middle'>
    <th width='5'><input type='checkbox' name='dummy' onclick=\"with(document.MainForm){for(i=0;i<length;i++){if(elements[i].type=='checkbox'){elements[i].checked=this.checked;}}}\" /></th>
    <th>".WORTIFT_AM_TH_DATETIME."</th>
    <th>".WORTIFT_AM_TH_USER."</th>
    <th>".WORTIFT_AM_TH_IP."<br />".WORTIFT_AM_TH_AGENT."</th>
    <th>".WORTIFT_AM_TH_TYPE."</th>
    <th>".WORTIFT_AM_TH_DESCRIPTION."</th>
  </tr>
" ;

// body of log listing
$oddeven = 'odd' ;
while( list( $lid , $uid , $ip , $agent , $type , $description , $timestamp , $uname ) = $GLOBALS['wortifyDB']->fetchRow( $prs ) ) {
	$oddeven = ( $oddeven == 'odd' ? 'even' : 'odd' ) ;

	$ip = htmlspecialchars( $ip , ENT_QUOTES ) ;
	$type = htmlspecialchars( $type , ENT_QUOTES ) ;
	$description = htmlspecialchars( $description , ENT_QUOTES ) ;
	$uname = htmlspecialchars( ( $uid ? $uname : _GUESTS ) , ENT_QUOTES ) ;

	// make agents shorter
	if( preg_match( '/MSIE\s+([0-9.]+)/' , $agent , $regs ) ) {
		$agent_short = 'IE ' . $regs[1] ;
	} else if( stristr( $agent , 'Gecko' ) !== false ) {
		$agent_short = strrchr( $agent , ' ' ) ;
	} else {
		$agent_short = substr( $agent , 0 , strpos( $agent , ' ' ) ) ;
	}
	$agent4disp = htmlspecialchars( $agent , ENT_QUOTES ) ;
	$agent_desc = $agent == $agent_short ? $agent4disp : htmlspecialchars( $agent_short , ENT_QUOTES ) . "<img src='../images/dotdotdot.gif' alt='$agent4disp' title='$agent4disp' />" ;
	$cycle = ($cycle=='#d9d9d9'?'#fefefe':'#d9d9d9');
	echo "
  <tr style='background-color: ' . $cycle . '; padding: 8px 8px 8px 8px; margin: 4px 4px 4px 4px;'>
    <td><input type='checkbox' name='ids[]' value='$lid' /></td>
    <td>".formatTimestamp($timestamp)."</td>
    <td>$uname</td>
    <td>$ip<br />$agent_desc</td>
    <td>$type</td>
    <td width='100%'>$description</td>
  </tr>\n" ;
}

// footer of log listing
echo "
  <tr>
    <td colspan='8' align='left'>".WORTIFT_AM_LABEL_REMOVE."<input type='button' value='".WORTIFT_AM_BUTTON_REMOVE."' onclick='if(confirm(\"".WORTIFT_AM_JS_REMOVECONFIRM."\")){document.MainForm.action.value=\"delete\"; submit();}' /></td>
  </tr>
</table>
<div align='right'>
  $nav_html
</div>
<div style='clear:both;'><br /><br /></div>
<div align='right'>
".WORTIFT_AM_LABEL_COMPACTLOG."<input type='button' value='".WORTIFT_AM_BUTTON_COMPACTLOG."' onclick='if(confirm(\"".WORTIFT_AM_JS_COMPACTLOGCONFIRM."\")){document.MainForm.action.value=\"compactlog\"; submit();}' />
&nbsp;
".WORTIFT_AM_LABEL_REMOVEALL."<input type='button' value='".WORTIFT_AM_BUTTON_REMOVEALL."' onclick='if(confirm(\"".WORTIFT_AM_JS_REMOVEALLCONFIRM."\")){document.MainForm.action.value=\"deleteall\"; submit();}' />
</div>
</form>
</td></tr></table></div>
" ;

function protector_ip_cmp( $a , $b )
{
	$as = explode( '.' , $a ) ;
	$aval = @$as[0] * 167777216 + @$as[1] * 65536 + @$as[2] * 256 + @$as[3] ;
	$bs = explode( '.' , $b ) ;
	$bval = @$bs[0] * 167777216 + @$bs[1] * 65536 + @$bs[2] * 256 + @$bs[3] ;

	return $aval > $bval ? 1 : -1 ;
}

?>

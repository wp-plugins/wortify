<?php
/*
 * Prevents Spam, Harvesting, Human Rights Abuse, Captcha Abuse etc.
 * basic statistic of them in WORTIFY Copyright (C) 2012 Simon Roberts 
 * Contact: wishcraft - simon@chronolabs.com.au
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * See /docs/license.pdf for full license.
 * 
 * Shouts:- 	Mamba (www.wortify.org), flipse (www.nlwortify.nl)
 * 				Many thanks for your additional work with version 1.01
 * 
 * Version:		3.10 Final (Stable)
 * Published:	Chronolabs
 * Download:	http://code.google.com/p/chronolabs
 * This File:	forms.wortify.php		
 * Description:	Forms for Wortify Admin and User iNterface
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

	include ('forms.objects.php');
	include ('../class/auth/authfactory.php');
	
	function WortifySignupForm($disclaimer)
	{
		error_reporting(E_ALL);
		$form = new WortifyThemeForm(WORTIFY_FRM_TITLE, "wortify_member", "", "post");
		
		$form->addElement(new WortifyFormText(WORTIFY_FRM_UNAME, "uname", 35, 128, (isset($_REQUEST['uname'])?$_REQUEST['uname']:'')));					
		$form->addElement(new WortifyFormPassword(WORTIFY_FRM_PASS, "pass", 35, 128, (isset($_REQUEST['pass'])?$_REQUEST['pass']:'')), false);					
		$form->addElement(new WortifyFormPassword(WORTIFY_FRM_VPASS, "vpass", 35, 128, (isset($_REQUEST['vpass'])?$_REQUEST['vpass']:'')), false);					
		$form->addElement(new WortifyFormText(WORTIFY_FRM_EMAIL, "email", 35, 128, (isset($_REQUEST['email'])?$_REQUEST['email']:'')));											
		$form->addElement(new WortifyFormText(WORTIFY_FRM_URL, "url", 35, 128, (isset($_REQUEST['url'])?$_REQUEST['url']:'')));											
		$form->addElement(new WortifyFormRadioYN(WORTIFY_FRM_VIEWEMAIL, "viewemail", (isset($_REQUEST['viewemail'])?$_REQUEST['viewemail']:false)));
		$form->addElement(new WortifyFormSelectTimezone(WORTIFY_FRM_TIMEZONE, "timezone", (isset($_REQUEST['timezone'])?$_REQUEST['timezone']:'')));
		$form->addElement(new WortifyFormLabel(WORTIFY_FRM_DISCLAIMER, str_replace(array("\n", "\n\r"), "<br />", $disclaimer)));	
		$form->addElement(new WortifyFormRadioYN(WORTIFY_FRM_DISCLAIMER_AGREE, "agree", false));				
		$form->addElement(new WortifyFormHidden('op', 'signup'));	
		$form->addElement(new WortifyFormHidden('fct', 'save'));
		if ($disclaimer != WORTIFY_FRM_NOSOAP_DISCLAIMER)
			$form->addElement(new WortifyFormButton('', 'submit', WORTIFY_FRM_REGISTER, 'submit'));	
		return $form->render();
	}
?>
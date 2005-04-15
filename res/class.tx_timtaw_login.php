<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Robert Lemke (robert@typo3.org)
*  (c) 2005 Sebastian Kurfuerst (sebastian@garbage-group.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'virtual backend user' for the 'timtaw' extension.
 *
 * @author	Ingmar Schlecht <ingmar@typo3.org>
 * @author  Sebastian Kurfuerst <sebastian@garbage-group.de>
 */



class tx_timtaw_login {
	var $prefixId = 'tx_timtaw_login';		// Same as class name
	var $scriptRelPath = 'res/class.tx_timtaw_login.php';	// Path to this script relative to the extension dir.
	var $extKey = 'timtaw';	// The extension key.
	
	var $user;
	
	function getFrontendUserData ($frontendUserTSsetting = '') {
	
		if($frontendUserTSsetting) {
			$userSetting = $frontendUserTSsetting;
		} elseif($GLOBALS['TSFE']) {
			$userSetting = $GLOBALS['TSFE']->fe_user->getKey('ses','tx_timtaw_login');
		} else {
				// in the "backend", when creating new pages for example
			$sesId = $_COOKIE['be_typo_user'];
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('ses_userid','be_sessions','ses_id='."'".$sesId."'");
			$temp = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$userSetting = $temp['ses_userid'] * (-1);
		}
		if(!empty($userSetting)) {	
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',
				'fe_users',
				'uid='.intval($userSetting)
			);
			$userData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$userData['tstamp'] = time();
		} else {
			// FE user has to be logged in
			$userData = $GLOBALS['TSFE']->fe_user->user;
		}
		
		$this->user = $userData;
		
	}
	
	/**
	 * gets backend group for a certain FE user
	 */
	function getBEgroup($frontendUserTSsetting = '') {
		$retUrl = t3lib_div::_GET('returnUrl');
		if(!$GLOBALS['TSFE'] && empty($retUrl)) {
			return '';
		}
		
		$this->getFrontendUserData($frontendUserTSsetting);
		
		if(!empty($this->user['tx_timtaw_begroup'])) {
			return $this->user['tx_timtaw_begroup'];
		}
			// check FE user groups
		$fe_groups = explode(',', $this->user['usergroup']);
		
		foreach($fe_groups as $singleGroup) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'tx_timtaw_begroup',
				'fe_groups',
				'uid='.intval($singleGroup)
			);
			$temp = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$results[] = $temp['tx_timtaw_begroup'];
		}
		
		return implode(',', $results);
	}
	
	function createVirtualBackendUser ($params, $parent = '') {
		
		$beGroup = $this->getBEgroup();

		if($params['pObj']->session_table == 'be_sessions' && $beGroup) {

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','be_sessions','ses_id='."'".$params['pObj']->id."'");
			$sessionRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

			if($sessionRow['ses_userid'] < 0) {

				$user = array (
					'uid' => $sessionRow['ses_userid'], 
					'pid' => '0',
					'usergroup' => $beGroup,
					'admin' => '0',
					'options' => '3' // get DB and file mounts from groups
				);

					// set UC to the correct admin panel options
				$uc_new['TSFE_adminConfig'] = Array(
					'display_top'	=> 1,
					'display_edit' => 1,
					'edit_displayFieldIcons' => 0,
					'edit_displayIcons' => 1,
					'edit_editFormsOnPage' => 1,
					'edit_editNoPopup' => 0
				);
				$uc=serialize($uc_new);
				$user['uc'] = $uc;
				
				$tsConfig = "admPanel.enable.edit=1
					admPanel.hide=1";
				$user['TSconfig'] = $tsConfig;
				$user = array_merge($this->user,$user,$sessionRow);

				
				$user['ses_id'] = $params['pObj']->id;
				#debug($params['pObj']);
				#debug($user);
				$params['pObj']->user = $user;

			}
		}

	}
	
	
	/**
	 * Main function, registering itself as a hook
	 */
	function setBackendUserCookie($params, $parent = '')	{
		require_once (PATH_t3lib.'class.t3lib_befunc.php');

		$beGroup = $this->getBEgroup();
		
			// login user when tx_timtaw_login set
		if(t3lib_div::_GP('tx_timtaw_login') && $beGroup) {
			if($_COOKIE['be_typo_user']) {
				$sesId = $_COOKIE['be_typo_user'];
			} else {
				$sesId = substr(md5(uniqid('')),0,32);		// New random sesseion id $sesId is made
				setcookie('be_typo_user', $sesId, time()+3600, '/');
				$_COOKIE['be_typo_user'] = $sesId; // change cookie superglobal, else we need a refresh
			}

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('ses_id','be_sessions',"ses_id='".$sesId."'");

			if(!$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$ses_userid = $this->user['uid'] * (-1);
				$insertFields = array(
					'ses_id' => $sesId,
					'ses_name' => 'be_typo_user',
					'ses_iplock' => '[DISABLED]',
					'ses_hashlock' => t3lib_div::md5int(':'.t3lib_div::getIndpEnv('HTTP_USER_AGENT')),
					'ses_userid' => $ses_userid,
					'ses_tstamp' => time()
				);

				$GLOBALS['TYPO3_DB']->exec_INSERTquery('be_sessions', $insertFields);

			}
		}

		if(t3lib_div::_GP('tx_timtaw_logout')) {
			if($_COOKIE['be_typo_user']) {
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('ses_id, ses_userid','be_sessions','ses_id='."'".$_COOKIE['be_typo_user']."'");
				if($record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$GLOBALS['TYPO3_DB']->exec_DELETEquery('be_sessions', "ses_id='".$_COOKIE['be_typo_user']."'");
				}
				setcookie('be_typo_user', $_COOKIE['be_typo_user'], time() - 3600, '/');
				unset($_COOKIE['be_typo_user']);
			}
		}
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/timtaw/res/class.tx_timtaw_login.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/timtaw/res/class.tx_timtaw_login.php']);
}

?>
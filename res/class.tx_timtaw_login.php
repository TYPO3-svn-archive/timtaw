<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Robert Lemke (robert@typo3.org)
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
 * Plugin 'Timtawtoolbar' for the 'timtaw' extension.
 *
 * @author	Ingmar Schlecht <ingmar@typo3.org>
 */



class tx_timtaw_login {
	var $prefixId = "tx_timtaw_login";		// Same as class name
	var $scriptRelPath = "res/class.tx_timtaw_login.php";	// Path to this script relative to the extension dir.
	var $extKey = "timtaw";	// The extension key.

	/**
	 * Main function, registering itself as a hook
	 */
	function loginBackendUser($params, $parent = '')	{
		require_once ($params['PATH_t3lib'].'class.t3lib_befunc.php');
		if(t3lib_div::_GP('wikiLogin')) {
			$pid = t3lib_div::_GP('id');
			$record = t3lib_BEfunc::getRecordRaw('pages','uid='.intval($pid),'tx_timtaw_enable,tx_timtaw_backenduser');
		
			if($record['tx_timtaw_enable']) {
				if($_COOKIE['be_typo_user']) {
					$id = $_COOKIE['be_typo_user'];
				} else {
					$id = substr(md5(uniqid('')),0,32);		// New random session-$id is made
					setcookie('be_typo_user', $id, time()+3600, '/');
					$_COOKIE['be_typo_user'] = $id; // change cookie superglobal, else we need a refresh
				}

				$userId = $record['tx_timtaw_backenduser'];

				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('ses_id','be_sessions','ses_id='."'".$id."'");

				if(!$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

					$insertFields = array(
						'ses_id' => $id,
						'ses_name' => 'be_typo_user',
						'ses_iplock' => '[DISABLED]',
						'ses_hashlock' => t3lib_div::md5int(':'.t3lib_div::getIndpEnv('HTTP_USER_AGENT')),
						'ses_userid' => $userId,
						'ses_tstamp' => time()
					);

					$GLOBALS['TYPO3_DB']->exec_INSERTquery('be_sessions', $insertFields);
					
					// we have to set the user's start- and endtime as well.
					
					#return 'Timtaw Backenduser '.$userId.' wurde eingeloggt.';
				}
			} else {
			#	return 'Flag tx_timtaw_enable ist nicht gesetzt.';
			}
		} else {
			#return 'GPVar.wikiLogin ist nicht gesetzt.';
		}
	}

}


if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/timtaw/res/class.tx_timtaw_login.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/timtaw/res/class.tx_timtaw_login.php"]);
}

?>
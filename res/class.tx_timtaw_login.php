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
 * @author  Sebastian Kurfuerst <sebastian@garbage-group.de>
 */



class tx_timtaw_login {
	var $prefixId = 'tx_timtaw_login';		// Same as class name
	var $scriptRelPath = 'res/class.tx_timtaw_login.php';	// Path to this script relative to the extension dir.
	var $extKey = 'timtaw';	// The extension key.
	
	
	function getFrontendUID ($frontendUserTSsetting = '') {
		if($frontendUserTSsetting) {
			$userSetting = $frontendUserTSsetting;
		} else {
			$userSetting = $GLOBALS['TSFE']->fe_user->getKey('ses','tx_timtaw_login');
		}
		if(!empty($userSetting)) {
	/*		array (
  'ses_id' => 'ae4642dafbfb9bd426df745b7eac2f74',
  'ses_name' => 'be_typo_user',
  'ses_userid' => '1',
  'ses_tstamp' => '1110382588',
  'ses_data' => '',
  'ses_iplock' => '192.168.42.55',
  'ses_hashlock' => '26006954',
  'uid' => '1',
  'pid' => '0',
  'tstamp' => '1105477417',
  'username' => 'admin',
  'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
  'admin' => '1',
  'usergroup' => '',
  'disable' => '0',
  'starttime' => '0',
  'endtime' => '0',
  'lang' => '',
  'email' => '',
  'db_mountpoints' => '',
  'options' => '0',
  'crdate' => '1045429637',
  'cruser_id' => '0',
  'realName' => '',
  'userMods' => '',
  'uc' => 'a:23:{s:14:"interfaceSetup";s:0:"";s:10:"moduleData";a:36:{s:8:"tools_em";a:8:{s:8:"function";s:1:"1";s:9:"listOrder";s:3:"cat";s:15:"display_details";s:1:"1";s:13:"singleDetails";s:4:"info";s:11:"display_shy";s:1:"1";s:4:"fe_u";s:9:"kurfuerst";s:4:"fe_p";s:7:"kurfuer";s:15:"own_member_only";s:1:"1";}s:10:"web_layout";a:7:{s:8:"tt_board";s:1:"0";s:10:"tt_address";s:1:"0";s:8:"tt_links";s:1:"0";s:11:"tt_calender";s:1:"0";s:11:"tt_products";s:1:"0";s:8:"function";s:1:"1";s:8:"language";s:1:"0";}s:12:"tools_config";a:1:{s:8:"function";s:1:"0";}s:11:"tools_dbint";a:3:{s:8:"function";s:4:"tree";s:6:"search";s:3:"raw";s:22:"search_query_makeQuery";s:3:"all";}s:8:"web_func";a:3:{s:8:"function";s:22:"tx_funcwizards_webfunc";s:3:"wiz";s:26:"tx_wizardcrpages_webfunc_2";s:6:"cr_333";s:1:"0";}s:16:"xMOD_alt_doc.php";a:3:{s:12:"showPalettes";s:2:"on";s:10:"disableRTE";s:2:"on";s:16:"showDescriptions";s:2:"on";}s:32:"t3lib_BEfunc::getSetUpdateSignal";a:1:{s:3:"set";s:0:"";}s:11:"alt_doc.php";a:2:{i:0;a:2:{s:32:"a8171d7c0b90326083ef562812acc42b";a:3:{i:0;s:4:"wiki";i:1;a:7:{s:4:"edit";a:1:{s:9:"be_groups";a:1:{i:1;s:4:"edit";}}s:7:"defVals";N;s:12:"overrideVals";N;s:11:"columnsOnly";N;s:7:"disHelp";N;s:6:"noView";N;s:24:"editRegularContentFromId";N;}i:2;s:103:"&edit[be_groups][1]=edit&defVals=&overrideVals=&columnsOnly=&disHelp=&noView=&editRegularContentFromId=";}s:32:"5abaa71ea58b9c47f9dbcaea9d64bac5";a:3:{i:0;s:5:"wiki1";i:1;a:7:{s:4:"edit";a:1:{s:9:"fe_groups";a:1:{i:1;s:4:"edit";}}s:7:"defVals";N;s:12:"overrideVals";N;s:11:"columnsOnly";N;s:7:"disHelp";N;s:6:"noView";N;s:24:"editRegularContentFromId";N;}i:2;s:103:"&edit[fe_groups][1]=edit&defVals=&overrideVals=&columnsOnly=&disHelp=&noView=&editRegularContentFromId=";}}i:1;s:32:"5abaa71ea58b9c47f9dbcaea9d64bac5";}s:8:"web_list";a:2:{s:15:"bigControlPanel";s:1:"1";s:9:"clipBoard";s:1:"1";}s:8:"web_info";a:6:{s:8:"function";s:16:"tx_belog_webinfo";s:5:"pages";s:1:"0";s:9:"stat_type";s:1:"0";s:5:"depth";s:1:"0";s:9:"log_users";s:1:"0";s:8:"log_time";s:1:"0";}s:14:"web_txtimtawM1";a:1:{s:8:"function";s:1:"1";}s:12:"tools_beuser";a:1:{s:8:"function";s:7:"compare";}s:19:"xMOD_tx_version_cm1";a:1:{s:8:"function";s:0:"";}s:18:"xMOD_tx_timtaw_cm1";a:1:{s:8:"function";s:0:"";}s:12:"alt_menu.php";a:5:{s:3:"web";s:1:"0";s:4:"file";s:1:"1";s:4:"user";s:1:"1";s:5:"tools";s:1:"0";s:4:"help";s:1:"1";}s:14:"tools_uvdievM1";a:1:{s:8:"function";s:1:"2";}s:20:"tools_txextdevevalM1";a:3:{s:8:"function";s:1:"2";s:6:"extSel";s:6:"timtaw";s:7:"phpFile";s:27:"pi2/class.tx_timtaw_pi2.php";}s:9:"xMod_test";a:1:{s:19:"constant_editor_cat";s:5:"basic";}s:16:"browse_links.php";a:1:{s:10:"expandPage";s:1:"0";}s:26:"tools_txa4neditlocallangM1";a:1:{s:8:"function";s:1:"1";}s:9:"file_list";a:0:{}s:6:"web_ts";a:11:{s:8:"function";s:17:"tx_tstemplateinfo";s:33:"tx_tstemplatestyler_modfunc1_menu";s:6:"editor";s:35:"tx_tstemplatestyler_styleCollection";s:1:"0";s:19:"constant_editor_cat";s:7:"content";s:15:"ts_browser_type";s:5:"setup";s:25:"ts_browser_toplevel_setup";s:1:"0";s:25:"ts_browser_toplevel_const";s:1:"0";s:16:"ts_browser_const";s:1:"0";s:20:"tsbrowser_conditions";N;s:22:"ts_browser_linkObjects";s:2:"on";s:25:"tsbrowser_depthKeys_setup";a:4:{s:10:"tt_content";i:1;s:18:"tt_content.stdWrap";i:1;s:28:"tt_content.stdWrap.editPanel";i:1;s:33:"tt_content.stdWrap.editPanel.edit";i:1;}}s:16:"tools_txp2wlibM1";a:1:{s:8:"function";s:1:"1";}s:25:"web_txthspecialelementsM1";a:1:{s:8:"function";s:1:"3";}s:14:"help_txwelcome";a:1:{s:8:"function";s:0:"";}s:9:"tools_log";a:4:{s:5:"users";s:1:"0";s:4:"time";s:1:"0";s:3:"max";s:2:"20";s:6:"action";s:1:"1";}s:31:"xMOD_tx_rlmpofficedocuments_cm1";a:1:{s:8:"function";s:1:"1";}s:26:"xMOD_tx_borosimagecrop_cm1";a:1:{s:8:"function";s:0:"";}s:16:"web_txccbeinfoM1";a:1:{s:5:"gpvar";s:1:"1";}s:23:"xMOD_tx_cpimagecrop_cm1";a:1:{s:8:"function";s:0:"";}s:19:"tools_txccsysinfoM1";a:6:{s:8:"function";s:7:"sysinfo";s:11:"logfunction";s:7:"tail100";s:4:"user";s:16:"/usr/bin/who -aH";s:9:"processes";s:10:"/bin/ps ax";s:7:"netstat";s:12:"/bin/netstat";s:5:"mysql";s:5:"mytop";}s:30:"tools_beuser/index.php/compare";a:3:{s:14:"custom_options";s:1:"1";s:7:"modules";s:1:"1";s:6:"userTS";s:1:"1";}s:8:"web_perm";a:2:{s:5:"depth";s:1:"1";s:4:"mode";s:5:"perms";}s:13:"tools_isearch";a:1:{s:8:"function";s:10:"typo3pages";}s:18:"tools_txccsvauthM1";a:1:{s:8:"function";s:7:"install";}s:9:"clipboard";a:6:{s:6:"normal";a:0:{}s:5:"tab_1";a:1:{s:4:"mode";s:0:"";}s:5:"tab_2";a:0:{}s:5:"tab_3";a:0:{}s:7:"current";s:5:"tab_1";s:9:"_setThumb";N;}}s:19:"thumbnailsByDefault";N;s:14:"emailMeAtLogin";N;s:13:"condensedMode";N;s:10:"noMenuMode";s:0:"";s:17:"startInTaskCenter";N;s:18:"hideSubmoduleIcons";i:0;s:8:"helpText";s:2:"on";s:8:"titleLen";i:30;s:17:"edit_wideDocument";N;s:18:"edit_showFieldHelp";s:4:"icon";s:8:"edit_RTE";s:2:"on";s:20:"edit_docModuleUpload";s:2:"on";s:15:"disableCMlayers";N;s:13:"navFrameWidth";s:0:"";s:17:"navFrameResizable";i:0;s:4:"lang";s:0:"";s:15:"moduleSessionID";a:35:{s:8:"tools_em";s:32:"ae7f929a372807373a09b2f111d54949";s:10:"web_layout";s:32:"59e7062026046cf6ede2aa8a4533ca34";s:12:"tools_config";s:32:"59e7062026046cf6ede2aa8a4533ca34";s:11:"tools_dbint";s:32:"c9866ce89707bf160d974ccb6e143544";s:8:"web_func";s:32:"59e7062026046cf6ede2aa8a4533ca34";s:16:"xMOD_alt_doc.php";s:32:"b8f663c2bf5ed1a13f36f266bcd18874";s:32:"t3lib_BEfunc::getSetUpdateSignal";s:32:"ae7f929a372807373a09b2f111d54949";s:11:"alt_doc.php";s:32:"ae4642dafbfb9bd426df745b7eac2f74";s:8:"web_list";s:32:"ae4642dafbfb9bd426df745b7eac2f74";s:8:"web_info";s:32:"c94f33a662b090aa745839b44e270eaa";s:14:"web_txtimtawM1";s:32:"59e7062026046cf6ede2aa8a4533ca34";s:12:"tools_beuser";s:32:"54aa18b0dcab971f6870cab8f45dcba8";s:19:"xMOD_tx_version_cm1";s:32:"d8c99998e4a98c43bbb2932c9f66d318";s:18:"xMOD_tx_timtaw_cm1";s:32:"1e7690432ce60136b666e42f140dcfa7";s:14:"tools_uvdievM1";s:32:"a6ace6f4df354de45f9fc4afe4fb1c34";s:20:"tools_txextdevevalM1";s:32:"f81f1d74c78e1fe97be600484ceaba60";s:9:"xMod_test";s:32:"0f44568a6f3d3dac022597b5bf58e5d7";s:16:"browse_links.php";s:32:"fe7aea292b2e30af25b11968acb03863";s:26:"tools_txa4neditlocallangM1";s:32:"d19907dc09f0fdfd2663b4f909ca293a";s:9:"file_list";s:32:"b229eaf4b572c7efc20aee36cc023137";s:6:"web_ts";s:32:"7f00970bfbef37d59d750697f14ccb2f";s:16:"tools_txp2wlibM1";s:32:"667ef659ba5b1536b2ab420c4d0d5bda";s:25:"web_txthspecialelementsM1";s:32:"667ef659ba5b1536b2ab420c4d0d5bda";s:14:"help_txwelcome";s:32:"667ef659ba5b1536b2ab420c4d0d5bda";s:9:"tools_log";s:32:"5ce865d8edbd0d60acfac28428bbdc5d";s:31:"xMOD_tx_rlmpofficedocuments_cm1";s:32:"dd7a620d439a2bd82c5142ff41772e34";s:26:"xMOD_tx_borosimagecrop_cm1";s:32:"e2e0dd00bb50205de7f310ab4ec9e780";s:16:"web_txccbeinfoM1";s:32:"e2e0dd00bb50205de7f310ab4ec9e780";s:23:"xMOD_tx_cpimagecrop_cm1";s:32:"e2e0dd00bb50205de7f310ab4ec9e780";s:19:"tools_txccsysinfoM1";s:32:"e2e0dd00bb50205de7f310ab4ec9e780";s:30:"tools_beuser/index.php/compare";s:32:"dd7a620d439a2bd82c5142ff41772e34";s:8:"web_perm";s:32:"fe7aea292b2e30af25b11968acb03863";s:13:"tools_isearch";s:32:"dd7a620d439a2bd82c5142ff41772e34";s:18:"tools_txccsvauthM1";s:32:"979e83d11b289ac45e37452566c5bea6";s:9:"clipboard";s:32:"ae4642dafbfb9bd426df745b7eac2f74";}s:11:"browseTrees";a:2:{s:11:"browsePages";s:66:"a:1:{i:0;a:6:{i:0;i:1;i:1;i:1;i:2;i:1;i:21;i:1;i:6;i:1;i:55;i:1;}}";s:6:"folder";s:61:"a:1:{i:12903;a:3:{i:16331559;i:1;i:889007;i:1;i:638390;i:1;}}";}s:16:"TSFE_adminConfig";a:14:{s:11:"display_top";s:1:"1";s:15:"display_preview";s:1:"1";s:13:"display_cache";s:1:"0";s:15:"display_publish";s:0:"";s:15:"display_tsdebug";s:0:"";s:12:"display_info";s:0:"";s:12:"display_edit";s:1:"0";s:22:"edit_displayFieldIcons";s:1:"0";s:17:"edit_displayIcons";s:1:"1";s:20:"edit_editFormsOnPage";s:1:"1";s:16:"edit_editNoPopup";s:1:"0";s:13:"cache_noCache";s:1:"1";s:22:"cache_clearCacheLevels";s:1:"0";s:18:"cache_clearCacheId";s:1:"4";}s:10:"copyLevels";i:0;s:15:"recursiveDelete";s:2:"on";}',
  'file_mountpoints' => '',
  'fileoper_perms' => '7',
  'lockToDomain' => '',
  'deleted' => '0',
  'TSconfig' => 'admPanel.enable= 1
admPanel {
  hide = 0
  module.edit.forceDisplayFieldIcons = 1
}',
  'lastlogin' => '1110375814',
  'createdByAction' => '0',
  'usergroup_cached_list' => '',
  'allowed_languages' => '',
  'disableIPlock' => '0',
);*/
		} else {
			// FE user has to be logged in
		}
		
		// has to return: tx_timtaw_begroup, usergroup
	}
	
	/**
	 * gets backend group for a certain FE user
	 */
	function getBEgroup($frontendUserTSsetting = '') {
		if(!$GLOBALS['TSFE']) {
			return '';
		}
		
		#$frontendUser = tx_timtaw_login::getFrontendUID($frontendUserTSsetting);
		if(!empty($GLOBALS['TSFE']->fe_user->user['tx_timtaw_begroup'])) {
			return $GLOBALS['TSFE']->fe_user->user['tx_timtaw_begroup'];
		}
			// check FE user groups
		$fe_groups = explode(',', $GLOBALS['TSFE']->fe_user->user['usergroup']);
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
	
	function userLookup ($params, $parent = '') {
		
		
		$beGroup = $this->getBEgroup();
		if($params['pObj']->session_table == 'be_sessions' && $beGroup) {

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','be_sessions','ses_id='."'".$params['pObj']->id."'");
			$sessionRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

			if($sessionRow['ses_userid'] < 0) {

				$user = array (
					'uid' => $sessionRow['ses_userid'], 
					'pid' => '0',
					'usergroup' => $beGroup,
					'admin' => '1', // muss natürlich noch geändert werden
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
				$user = array_merge($GLOBALS['TSFE']->fe_user->user,$user,$sessionRow);

				$user['ses_id'] = $params['pObj']->id;
				$params['pObj']->user = $user;

			}
		}

	}
	
	
	/**
	 * Main function, registering itself as a hook
	 */
	function loginBackendUser($params, $parent = '')	{
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
				$ses_userid = $GLOBALS['TSFE']->fe_user->user['uid'] * (-1);
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

		/*
			// log out user if logged in when needed
		if($_COOKIE['be_typo_user']) {
				// select user id
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('ses_id, ses_userid','be_sessions','ses_id='."'".$_COOKIE['be_typo_user']."'");
			if($record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$uid = $record['ses_userid'];
					// select group id
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,usergroup','be_users','uid='.intval($record['ses_userid']));
				if($record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
						// check group whether it is a wiki group
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('tx_timtaw_enable','be_groups','uid='.intval($record['usergroup']));
					if($record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
						if($record['tx_timtaw_enable'] == 1) {
							$pid = t3lib_div::_GP('id'); // should be $GLOBALS['TSFE']->id, shouldn't it?
							$record = t3lib_BEfunc::getRecordRaw('pages','uid='.intval($pid),'tx_timtaw_enable,tx_timtaw_backenduser');
							if(!$record['tx_timtaw_enable'] || $record['tx_timtaw_backenduser'] != $uid) {
									// log out
								$GLOBALS['TYPO3_DB']->exec_DELETEquery('be_sessions', "ses_id='".$_COOKIE['be_typo_user']."'");
								setcookie('be_typo_user', $_COOKIE['be_typo_user'], time() - 3600, '/');
								unset($_COOKIE['be_typo_user']);
								
							}
						}
					}
				}
			}
		}
		*/

		
		
		/*
			// login user when set
		if(t3lib_div::_GP('wikiLogin')) {
			$pid = t3lib_div::_GP('id'); // should be $GLOBALS['TSFE']->id, shouldn't it?
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
		*/
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/timtaw/res/class.tx_timtaw_login.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/timtaw/res/class.tx_timtaw_login.php']);
}

?>